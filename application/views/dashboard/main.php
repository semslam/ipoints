<div class="breadcrumb">
	<a href="">Home</a>
<!-- </div>
<div class="top-banner">
	<div class="top-banner-title">Dashboard</div>
	<div class="top-banner-subtitle">Welcome back, <?php //echo $active_user->name; ?></div>
</div> -->
<?php 
if($this->session->userdata('active_user')->group_name == 'Administrator'){
	$this->load->view('dashboard/admin');
}else if($this->session->userdata('active_user')->group_name == 'Merchant'){
	$this->load->view('dashboard/merchants');
}elseif($this->session->userdata('active_user')->group_name == 'Accounts'){
	$this->load->view('dashboard/accounts');
}elseif($this->session->userdata('active_user')->group_name == 'Underwriter'){
	$this->load->view('dashboard/underwriters');
}elseif($this->session->userdata('active_user')->group_id == 2){
	$this->load->view('dashboard/customer_servies');
}elseif($this->session->userdata('active_user')->group_name == 'Auditor'){
	$this->load->view('dashboard/auditor');
}elseif($this->session->userdata('active_user')->group_name == 'Developers'){
	$this->load->view('dashboard/developers');
}elseif($this->session->userdata('active_user')->group_name == 'Subscriber'){
	$this->load->view('dashboard/subscriber');
}elseif($this->session->userdata('active_user')->group_name == 'Partner'){
	$this->load->view('dashboard/partner');
}