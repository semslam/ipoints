<?php
class Cummulative_insurance_report extends CI_Model {

    public function getCumulativePointReport() {

        $this->load->model('uici_levies');
        $uici_levies = $this->uici_levies->getLevies();

        //$ipoint_charge = $uici_levies['iPoint_unit_price'] + 1;// am not clear with plus one
        $ipoint_charge = $uici_levies['iPoint_unit_price'];// am not clear with plus one
        
        $value  = "SELECT u.mobile_number, 
        (b.balance+(IF(s.total_paid is NULL,0,s.total_paid))) AS 
        cash_point, IF(s.total_paid is NULL,0,s.total_paid) AS 
        annual_sub, ((b.balance+(IF(s.total_paid is NULL,0,s.total_paid)))*{$ipoint_charge}) AS 
        cash_ngn, (((b.balance+(IF(s.total_paid is NULL,0,s.total_paid)))*{$ipoint_charge})-b.balance-(IF(s.total_paid is NULL,0,s.total_paid))) AS 
        comm_per_point, (IF(s.total_paid is NULL,0,s.total_paid)+(((b.balance+(IF(s.total_paid is NULL,0,s.total_paid)))*{$ipoint_charge})-b.balance-(IF(s.total_paid is NULL,0,s.total_paid)))) AS 
        total_fee, (((b.balance+(IF(s.total_paid is NULL,0,s.total_paid)))*{$ipoint_charge})-(IF(s.total_paid is NULL,0,s.total_paid)+(((b.balance+(IF(s.total_paid is NULL,0,s.total_paid)))*{$ipoint_charge})-b.balance-(IF(s.total_paid is NULL,0,s.total_paid))))) AS 
        net_cash_ngn, w.name FROM users u 
        JOIN user_balance b ON u.id=b.user_id 
        LEFT OUTER JOIN user_subscriptions s ON u.id=s.user_id AND s.is_active=1 JOIN wallets w ON b.wallet_id=w.id AND w.name='iSavings'";
        $query = $this->db->query($value);

        return $query->result_array();

    }

}
