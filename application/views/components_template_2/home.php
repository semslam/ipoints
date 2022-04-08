
		<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
		<div class="container">
			<a class="navbar-brand" href="#">iPoints</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarsExampleDefault">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Solutions</a>
						<div class="dropdown-menu" aria-labelledby="dropdown01">
							<a class='dropdown-item' href="#" data-toggle="modal" data-target="#isavings">iSavings</a>
							<a class='dropdown-item' href="#" data-toggle="modal" data-target="#iInsurance">iInsurance</a>
							<a class='dropdown-item' href="#" data-toggle="modal" data-target="#iPensions">iPension</a>
						</div>
					</li>
				</ul>
				<?php if($this->session->userdata('active_user') == null){?>
				<ul class="navbar-nav">
					<li class="nav-item">
						<a style="color:white" class="btn btn-default btn-xs" href="https://www.uicinnovations.com" class="nav-link" target='_blank'>Corporate-site</a>
					</li>
					<li class="nav-item">&nbsp;
						<a class="btn  btn-outline-secondary btn-xs" href="<?php echo base_url() . 'auth/login'; ?>" class="nav-link"><i class="fa fa-lock" aria-hidden="true"></i> <b>Login</b></a>
					</li>
					<li class="nav-item">&nbsp;
						<a class="btn btn-xs btn-outline-secondary" href="<?php echo base_url() . 'auth/register'; ?>" class="nav-link"><i class="fa fa-user-plus" aria-hidden="true"></i> <b>Register</b></a>
					</li>
				</ul>
				<?php }else{?>
					<ul class="navbar-nav">
						<li class="nav-item">
							<a class="btn btn-outline-secondary btn-xs" href="<?php echo base_url() . 'dashboard'; ?>"><i class="fa fa-tachometer" aria-hidden="true"></i> <b>Dashboard</b></a>
						</li>
						<li class="nav-item">&nbsp;
							<a class="btn btn-outline-secondary btn-xs" href="<?php echo base_url() . 'auth/logout'; ?>"><i class="fa fa-sign-out" aria-hidden="true"></i> <b>Logout</b></a>
						</li>
					</ul>
				<?php }?>
			</div>
			
			</div>
		</nav>

    <main role="main">

      <section class="jumbotron intro text-center">
        <div class="container">
					<div class="row" id='uici_main'>
          	<div class="col-md-6 text-left">
				<h1 class="jumbotron-heading text-success"><?php echo 'UICI: '.$this->config->item('sitedescription'); ?></h1>
				  <p class="lead text-muted text-left">
					 <?php echo $this->config->item('about'); ?>
				  </p><br /><br />
				  <p>
					<a href="#" class="btn btn-success my-2" data-toggle="modal" data-target="#about" id="read_more">
						<i class="fa fa-eye" aria-hidden="true"></i> Read More about us
					</a>
				  </p>
			</div>
          	<div class="col-md-6" id='main'>					
				<div class="card-group">
				  <div class="card border-warning col-md-4">
					
							<div class="card-body text-success">
								<h5 class="card-title">
									<i class="fa fa-spinner" aria-hidden="true"></i> 
								</h5>
								<p class="card-text">You have an iPin voucher to redeem?</p>
						
								<button data-toggle="modal" data-target="#ipinvoucher"type="button" class="btn btn-sm btn-block btn-outline-secondary">Redeem iPin</button>
							</div>

				  </div>
				  <div class="card border-warning col-md-4">
					  
						<div class="card-body text-success">
						  <h5 class="card-title">
								<i class="fa fa-exchange" aria-hidden="true"></i>
							</h5>
						  <p class="card-text">Gift iPoints to reward loyal customers</p>
							<button data-toggle="modal" data-target="#transfer"type="button" class="btn btn-sm btn-block btn-outline-secondary">Gift iPoints</button>
						</div>
						
				  </div>				  
				  <div class="card border-warning col-md-4">
					  
						<div class="card-body text-success">
						  <h5 class="card-title"><i class="fa fa-cc-visa" aria-hidden="true"></i>&nbsp;<i class="fa fa-cc-mastercard" aria-hidden="true"></i></h5>
							<p class="card-text">Buy ipoints to reward loyal customers</p>
							<a href="<?=base_url('purchase/product')?>" class='wassap'>Buy iPoints</a>
						</div>
				  </div>
				</div>
				
				<div class="card-group">
				  <div class="card border-warning col-md-4">
						<div class="card-body text-success">					
						  <h5 class="card-title"><i class="fa fa-whatsapp" aria-hidden="true"></i></h5>
						  <p class="card-text">You can reach us via WhatsApp Chat</p>
						</div>						
						<a href="#" class="wassap w3-button w3-account w3-clearfix" data-number="<?php echo $this->config->item('whatsapp_number'); ?>" data-message="" target="_blank">
						Chat with us
					  </a>
				  </div>
				  <div class="card border-warning col-md-4">
						<div class="card-body text-success">
						  <h5 class="card-title"><i class="fa fa-question-circle" aria-hidden="true"></i></h5>
						  <p class="card-text">Do you have any questions? Take a look at out FAQs.</p>
						</div>
						<button data-toggle="modal" data-target="#faq" type="button" class="btn btn-sm btn-block btn-outline-secondary">Click here</button>
				  </div>
				  <div class="card border-warning col-md-4">
						<div class="card-body text-success">
						  <h5 class="card-title"><i class="fa fa-gavel" aria-hidden="true"></i></h5>
						  <p class="card-text">To read about our terms and conditions...</p>
						</div>						
						<button data-toggle="modal" data-target="#terms" type="button" class="btn btn-sm btn-block btn-outline-secondary">Click here</button>
				  </div>
				</div>
			</div>
          </div>
        </div>
      </section>

      
	  </div>
    </main>

    <footer class="text-muted">
      <div class="container">
        <p class="float-right">
          <a href="#">Back to top</a>
        </p>
		<p class='text-center'>
        Use of this site constitutes acceptance of our 
		<a href="#" data-toggle="modal" data-target="#terms">User Agreement</a> and 
		<a href="#" data-toggle="modal" data-target="#privacy">Privacy Policy</a>. &copy; <?php auto_copyright("2018");  // 2010 - 2017 ?>
		<?php function auto_copyright($year = 'auto'){ ?>
		<?php if(intval($year) == 'auto'){ $year = date('Y'); } ?>
		<?php if(intval($year) == date('Y')){ echo intval($year); } ?>
		<?php if(intval($year) < date('Y')){ echo intval($year) . ' - ' . date('Y'); } ?>
		<?php if(intval($year) > date('Y')){ echo date('Y'); } ?>
		<?php } ?> <?php echo $this->config->item('company_name'); ?>. All rights reserved.
		</p>
		
		<p class='text-center'>Address: <?php echo $this->config->item('company_address'); ?></p>
      </div>
    </footer>

   