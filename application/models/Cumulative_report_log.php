<?php
class Cumulative_report_log extends CI_Model {

    public function addReportLog($entries) {

        if (is_array($entries) && isset($entries['file_path'], $entries['expires_at'])) {

            $query = $this->db->insert('cumulative_report_log', $entries);

            return true;

        } else return false;
    }

    public function removeReportLog($logID) {

        if (is_numeric($logID)) {
            $this->db->query("DELETE FROM `cumulative_report_log` WHERE `id`={$logID}");

            return true;

        } else return false;

    }

    public function getExpiredLogs() {

        $currentTime = strtotime(date('d-m-Y H:i'));

        $query = $this->db->query("SELECT `id`, `file_path` FROM `cumulative_report_log` WHERE `expires_at`<={$currentTime};");

        $result = $query->result_array();

        if (is_array($result) && count($result) > 0) {
            return $result;

        } else return false;
    }
}
