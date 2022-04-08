<?php

class Migrate extends CI_Controller {

    public function __construct(){
        parent::__construct();
        if(!is_cli()){
            $this->load->helpers('url');
            redirect(base_url());
        }
        $this->load->library('migration');
        $this->load->database();
        $this->config->load('migration',true);
    }

    public function index(){
        $this->up();
    }

    public function up($confirm=''){
        $migrations = $this->migration->find_migrations();
        $new  = $this->xlist($migrations);
        $count = 0;
        if(!empty($new)){
            $silent = strcasecmp($confirm, 'y') === 0;
            if (!$silent) {
                fwrite(STDOUT, "Apply migration(s)? (Y/N)");
                $confirm = fgetc(STDIN);
            }
            if($confirm == 'Y' || $confirm== 'y'){
                foreach($new as $key=>$val){
                    $migration = $this->migration->version($key);
                    if($migration === FALSE){
                        show_error($this->migration->error_string());
                    } elseif($migration === TRUE) {

                    } else {
                        $count++;
                    }
                }
                echo $count.' Migrations were applied!';
            }
        } else {
            echo 'No migrations applied!';
        }
    }

    public function create($name=''){
        try{
            if(empty($name)){
                throw new Exception('File name not specified');
            }
            if(!preg_match('/^[A-Za-z][A-Za-z]*(?:_[A-Za-z]+)*$/',$name)){
                throw new Exception('File name is invalid. Only aphabetical characters separated by underscores are allowed');
            }
            $classname = 'Migration_'.ucfirst($name);
            $timestamp = date('YmdHis');
            $name = $timestamp.'_'.$name;
            $ext = '.php';
            fwrite(STDOUT, "Create new migration ".$name." (Y/N)");
            $confirm = fgetc(STDIN);
            if($confirm == 'Y' || $confirm == 'y'){
                $this->load->helper('file');
                if(write_file($this->config->item('migration_path','migration').DIRECTORY_SEPARATOR.$name.$ext,$this->migrationContents($classname))){
                    echo 'Migration created!';
                } else {
                    echo 'Migration could not be created';
                }
            }
        } catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function find(){
        //var_dump($this->migration->find_migrations());
        $migrations = $this->migration->find_migrations();
        $this->xlist($migrations);
    }

    protected function xlist($migrations){
        $current = $this->getCurrentVersion();
        $count = 0;
        $new = [];
        foreach($migrations as $key=>$val){
            if((float)$key > (float)$current){
                fwrite(STDOUT,$this->get_migration_name($val).PHP_EOL);
                $new[$key] = $val;
                $count++;
            }
        }
        fwrite(STDOUT,$count.' new migrations'.PHP_EOL);
        return $new;
    }

    protected function get_migration_name($migration)
	{
		$parts = explode('_', $migration);
		array_shift($parts);
		return implode('_', $parts);
	}

    protected function getCurrentVersion(){
		$row = $this->db->select('version')->get($this->config->item('migration_table','migration'))->row();
		return $row ? $row->version : '0';
    }

    public function version(){
        echo $this->getCurrentVersion();
    }

    private function migrationContents($name){
        return "<?php\ndefined('BASEPATH') OR exit('No direct script access allowed');\nclass ".$name." extends CI_Migration\n{\n\tpublic function up()\n\t{\n\n\t}\n\tpublic function down()\n\t{\n\n\t}\n}\n?>";
}
}
