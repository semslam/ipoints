<?php

trait DatatableQueryFormatter
{
    private $hasWhere = false;
    private $_reqst = [];
    private $dqf_bindings = [];  // SQL query parameter bindings
    
    public function getParam($key) 
    {
        $ci =& get_instance();
        $method = strtolower($ci->input->server('REQUEST_METHOD'));
        
        if ($this->_reqst[$key]??false) {
            return $this->_reqst[$key];
        }

        if (!empty($req = $ci->input->{$method}()) && is_array($req)) {
            $this->_reqst = $ci->input->{$method}() ?: [];
            return $ci->security->xss_clean($this->_reqst[$key]??null);
        }
        elseif (strcasecmp($ci->input->server('CONTENT_TYPE'), 'application/json') === 0) {
            $stream_clean = $ci->security->xss_clean($ci->input->raw_input_stream);
            $this->_reqst = json_decode($stream_clean, true);
            return $this->_reqst[$key] ?? null;
        }
        return null;
    }

    public function getColumns()
    {
        return array_column($this->getParam('columns'), 'data');
    }

    public function getPhoneParam($key)
    {
        $ci =& get_instance();
        $ci->load->library('Applib');

        try {
            $phone = $this->getParam($key);

            if ($ci->applib->validatePhoneNumber($phone)) {
                return $ci->applib->cleanPhoneNumber($phone);
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function generatePeriod($periodField = '')
    {
        $startDate = $this->getParam('startDate');
        $endDate = $this->getParam('endDate');
        
        // test for yadcf plugin
        if (empty($startDate) && empty($endDate) && !empty($periodField)) {
            $dtcols = $this->getParam('columns') ?: [];
            if (!empty($dtcols)) {
                $ind = array_search($periodField, array_column($dtcols, 'data'));
                if (is_numeric($ind)) {
                    $delim = '-yadcf_delim-';
                    $val = trim($dtcols[$ind]['search']['value'])??'';
                    $pos = strpos($val, $delim);
                    if (is_numeric($pos)) {
                        $period = preg_split("#$delim#", $val);
                        $startDate = $period[0];
                        $endDate = $period[1];
                    }
                }
            }
        }
        
        if (empty($startDate)) {
            $startDate = '1970-01-01';
        }
        if (empty($endDate)) {
            $endDate = date('Y-m-d');
        }
        
        $conj = " AND";

        if (! $this->hasWhere) {
            $conj = " WHERE";
            $this->hasWhere = true;
        }

        return "$conj `$periodField` BETWEEN '{$startDate} 00:00:00' AND '{$endDate} 23:59:59' ";
    }

    public function generateOrderBy($default='', array $columns=[])
    {
        $defCol = $this->security->xss_clean($default);
        $order = $this->getParam('order');
        $dtcols = $this->getParam('columns') ?: [];
        $rColumns = array_column($dtcols, 'data');
        
        if (empty($columns) && !empty($rColumns)) {
            $columns = $rColumns;
        }

        $column = $order['0']['column'] ?? '';
        $columnName = $columns[$column] ?? '';
        $dir = $order['0']['dir'] ?? '';

        if (!empty($order) && !empty($columnName) && !empty($dir)) {
            return " ORDER BY `$columnName` $dir ";
        }
        elseif(!empty($default)) {
            return " ORDER BY `$defCol` ";
        }
        $default = ($defCol = array_shift($columns)) ? " `$defCol` ": 1;

        
        return !empty($default) ? " ORDER BY $default DESC " : '';
    }

    public function generateLimit()
    {
        $length = $this->getParam('length');
        $offset = $this->getParam('start');

        if (!empty($length) && $length != -1) {
            $offset = $offset ?: 0;

            return " LIMIT $offset, $length ";
        }

        return '';
    }

    public function generateSearch($columns = [])
    {
        $req = $this->getParam('search');
        $term = $req['value'] ?? '';
        $search = [];
        $andSearches = [];
        $dtcols = $this->getParam('columns') ?: [];
        $rColumns = array_column($dtcols, 'data');
        $rSearchable = [];
        
        if (empty($columns) && !empty($rColumns)) {
            $columns = $rColumns;
            $rSearchable = array_column($dtcols, 'searchable');
            $searchVals = array_column($dtcols, 'search');
        }

        if (!empty($columns)) {
            foreach ($columns as $ind => $field) {
                if (($rSearchable[$ind]?? false) == 'true') {
                    if (!empty($val = $searchVals[$ind]['value'])) {
                        $andSearches[] = " `$field` LIKE '%$val%' ";
                    }
                    if (!empty($term)) {
                        $search[] = " `$field` LIKE '%$term%' ";
                    }
                }
            }

            $conj = " AND ";

            if (! $this->hasWhere) {
                $conj = "WHERE";
                $this->hasWhere = true;
            }

            $search = !empty($search) ? '( ' . implode(' OR ', $search) . ' )' : '';
            $andSearches = !empty($andSearches) ? implode(' AND ', $andSearches) : '';
            
            if (!empty($search) && !empty($andSearches)) {
                return " $conj $search AND $andSearches ";
            }
            elseif (!empty($search)) {
                return " $conj $search ";
            }
            elseif (!empty($andSearches)) {
                return " $conj $andSearches ";
            }
        }
        
        return '';
    }

    public function generateConditions(array $options)
    {
        $sql = '';

        foreach ($options as $field => $val) {
            if (! $this->hasWhere) {
                $conj = " WHERE";
                $this->hasWhere = true;
            }
            else {
                $conj = " AND";
            }
            
            $sql .= " $conj `$field` = ? ";

            $this->dqf_bindings[] = $val;
        }

        return $sql;
    }

    public function compileQuery($query='', array $criteria=[], $periodCol='', $orderByDef='')
    {
        if (empty($query)) {
            throw new Exception ('Cannot compile an empty query');
        }

        $sql = "SELECT (@tbsn:=@tbsn+1) sn, m.* 
            FROM ($query) m 
            INNER JOIN (select @tbsn:=0) tbsn";
        $this->rawSQLWrapper = 'SELECT COUNT(*) `total` FROM ('.$sql.') sub';
        
        $sql .= $this->generateConditions($criteria);
        $sql .= $this->generateSearch();
        if (!empty($periodCol)) {
            $sql .= $this->generatePeriod($periodCol);
        }
        
        $this->filteredSQL = 'SELECT COUNT(*) `filtered` FROM ('.$sql.') sub';

        $sql .= $this->generateOrderBy($orderByDef);
        $sql .= $this->generateLimit();
        $this->pageredSQL = $sql;
        $this->queryCompiled = true;
        return $this;
    }

    public function getDrawId()
    {
        return $this->getParam('draw');
    }

    public function getResult(array $params=[])
    {
        $respData = ['draw' => $this->getDrawId()];
        try {
            $params += $this->dqf_bindings;
            // export result
            if (!empty($this->getParam('exportTo'))) {
                if ($this->getParam('exportAs')) {
                    return $this->filteredSQL;
                }
                else {
                    // @TODO needs to send back a file, not data
                    $rs = $filtered->result_array();
                    exit(json_encode($rs));
                }
            }
            $ci =& get_instance();
            $filtered = ($mrow = $ci->db->query($this->filteredSQL, $params)->row(0)) ? $mrow->filtered : 0;
            $total = ($mrow = $ci->db->query($this->rawSQLWrapper, $params)->row(0)) ? $mrow->total : 0;
            $pagered = $ci->db->query($this->pageredSQL, $params);
            $respData += [
                'data' => $pagered->result_array(),
                'recordsTotal' => $total,
                'recordsFiltered' => $filtered
            ];
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            $respData += [
                'data' => [],
                'recordsTotal' => 0,
                'recordsFiltered' => 0
            ];
        } finally {
            $header = array_change_key_case($this->input->request_headers());
            $arr = isset($header['accept']) ? explode(', ', $header['accept']) : [];
            if (in_array('application/json', $arr)) {
                header('Content-Type: application/json');
                
                exit(json_encode($respData));
            }
            else {
                return $respData;
            }
        }
    }

    public function getQueryString()
    {
        return $this->pageredSQL;
    }

}