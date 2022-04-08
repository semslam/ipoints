
    <header>
      <div class="collapse bg-dark" id="navbarHeader">
        <div class="container">
          <div class="row">
            <div class="col-sm-8 col-md-7 py-4 vision">
              <?php echo $this->config->item('vision_mission'); ?>	
            </div>
            <div class="col-sm-4 offset-md-1 py-4">
              <h4 class="text-white">Contact</h4>
              <ul class="list-unstyled">
                <li><a href="#" class="text-white">Follow on Twitter</a></li>
                <li><a href="#" class="text-white">Like on Facebook</a></li>
                <li><a href="#" class="text-white">Email me</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container d-flex justify-content-between">
          <a href="#" class="navbar-brand d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
            <strong>iPoints</strong>
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
      </div>
    </header>

    <main role="main">

      <section class="jumbotron intro text-center">
        <div class="container">
          <div class="row">
          	<div class="col-md-6 text-left">
				<h1 class="jumbotron-heading text-success"><?php echo 'UICI: '.$this->config->item('sitedescription'); ?></h1>
				  <p class="lead text-muted text-left">
					 <?php echo $this->config->item('about'); ?>
				  </p>
				  <p>
					<a href="#" class="btn btn-success my-2" data-toggle="modal" data-target="#about" id="">
						<i class="fa fa-eye" aria-hidden="true"></i> Read More about us
					</a>
					<a class='btn btn-success my-2' href="#" data-toggle="modal" data-target="#stats">
						<i class="fa fa-spinner" aria-hidden="true"></i> Load iPin Voucher
					</a>
					<a class='btn btn-success my-2' href="#" data-toggle="modal" data-target="#transfer">
						<i class="fa fa-exchange" aria-hidden="true"></i> iPoint Transfer
					</a>
				  </p>
			</div>
          	<div class="col-md-6" id='main'>					
				<div class="card-group">
				<?php if($this->session->userdata('active_user') == null){?>
				  <div class="card border-warning bg-info col-md-4">
					  <a href="<?php echo base_url() . 'auth/login'; ?>">
						<div class="card-body text-success">
						  <h5 class="card-title"><i class="fa fa-sign-in" aria-hidden="true"></i></h5>
						  <p class="card-text">Login</p>
						</div>
					  </a>
				  </div>
				  <div class="card border-warning bg-info col-md-4">
					  <a href="<?php echo base_url() . 'auth/register'; ?>">
						<div class="card-body text-success">
						  <h5 class="card-title"><i class="fa fa-user-circle" aria-hidden="true"></i></h5>
						  <p class="card-text">Register</p>
						</div>
					  </a>
				  </div>
				  <?php }else{?>
					  <div class="card border-warning bg-info col-md-4">
						  <a href="<?php echo base_url() . 'dashboard'; ?>">
							<div class="card-body text-success">
							  <h5 class="card-title"><i class="fa fa-dashboard" aria-hidden="true"></i></h5>
							  <p class="card-text">Dashboard</p>
							</div>
						  </a>
					  </div>
					  <div class="card border-warning bg-info col-md-4">
						  <a href="<?php echo base_url() . 'auth/logout'; ?>">
							<div class="card-body text-success">
							  <h5 class="card-title"><i class="fa fa-sign-out" aria-hidden="true"></i></h5>
							  <p class="card-text">Logout</p>
							</div>
						  </a>
					  </div>
				  <?php }?>
				  
				  <div class="card border-warning bg-info col-md-4">
					  <a href="">
						<div class="card-body text-success">
						  <h5 class="card-title"><i class="fa fa-cc-visa" aria-hidden="true"></i>&nbsp;<i class="fa fa-cc-mastercard" aria-hidden="true"></i></h5>
						  <p class="card-text"><a href="<?=base_url('purchase/product')?>">Buy iPoints</a></p>
						</div>
					  </a>
				  </div>
				</div>
				
				
				<div class="card-group">
				  <div class="card border-warning bg-info col-md-4">
					  <a class="w3-button w3-account w3-clearfix" data-number="<?php echo $this->config->item('whatsapp_number'); ?>" data-message="Hi! I would like to know more about the iPoint. Will you help me?" target="_blank">
						<div class="card-body text-success">					
						  <h5 class="card-title"><i class="fa fa-whatsapp" aria-hidden="true"></i></h5>
						  <p class="card-text">Chat with us</p>
						</div>
					  </a>
				  </div>
				  <div class="card border-warning bg-info col-md-4">
					<a class='product' href="#" data-toggle="modal" data-target="#faq">
						<div class="card-body text-success">
						  <h5 class="card-title"><i class="fa fa-question-circle" aria-hidden="true"></i></h5>
						  <p class="card-text">FAQ</p>
						</div>
					</a>
				  </div>
				  <div class="card border-warning bg-info col-md-4">
					  <a class='product' href="#" data-toggle="modal" data-target="#terms">
						<div class="card-body text-success">
						  <h5 class="card-title"><i class="fa fa-gavel" aria-hidden="true"></i></h5>
						  <p class="card-text">Legal Matters</p>
						</div>
					  </a>
				  </div>
				</div>
			</div>
          </div>
        </div>
      </section>

      <div class="album py-5 bg-light">
        <div class="container">
			<div class="row">
			<div class="col-md-12">
				<div class="card-group">
				  <div class="card">
						<div class="card-body">						
							<a class='product' href="#" data-toggle="modal" data-target="#iInsurance">
							  <h5 class="card-title">iInsurance</h5>
							</a>
							<p class="card-text">
								<div class="count-subtitle">Incidental Insurance <br />
									<small>
										<a class='product' href="#" data-toggle="modal" data-target="#iInsurance">Learn more...</a>
									</small>
								</div>
							</p>
						</div>
				  </div>
				  <div class="card">
					<div class="card-body">
					  <h5 class="card-title">
						<a class='product' href="#" data-toggle="modal" data-target="#isavings">
							<div class="count">	
								 iSavings
							</div>
						</a>
					  </h5>
					  <p class="card-text">
						<div class="count-subtitle">Incidental Savings <br />
							<small>
								<a class='product' href="#" data-toggle="modal" data-target="#isavings">Learn more...</a>
							</small>
						</div>
					  </p>
					</div>
				  </div>
				  <div class="card">
					<div class="card-body">
					  <h5 class="card-title">
						<a class='product' href="#" data-toggle="modal" data-target="#ipensions">
							<div class="count">	
								 iPensions
							</div>
						</a>
					  </h5>
					  <p class="card-text">
						<div class="count-subtitle">Incidental Pension <br />
							<small>
								<a class='product' href="#" data-toggle="modal" data-target="#iPensions">Learn more...</a>
							</small>
						</div>
					  </p>
					</div>
				  </div>
				</div>
			</div>
			</div>
		</div>
	  </div>
	  <div class="album py-2 py_width">
			<div class="feature-wrapper">
		<div class="container">
			<div class="title"><h1 id='team'>UICI Board &amp; Management Team</h1></div>
			<div class="subtitle"></div>
			<div class="col-md-12 feature-list">
				<div class="row">
					<div class="col-md-4 feature">
						<img alt="Bernadine Okeke" src="<?php echo base_url('assets/images/team/bernadine-okeke-200x200.png'); ?>">
						<div class="title">Bernadine Okeke</div>
						<div class="content">Chair, <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Board of Directors">Board of Directors</span></div>
					</div>
					<div class="col-md-4 feature">
						<img alt="Dr Adeleke Oshunniyi" src="<?php echo base_url('assets/images/team/adeleke-oshunniyi-200x200.png'); ?>">
						<div class="title">Dr Adeleke Oshunniyi</div>
						<div class="content">Member,  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Board of Directors">Board</span></div>
					</div>
					<div class="col-md-4 feature">
						<img alt="Lai Labode" src="<?php echo base_url('assets/images/team/lai-labode-200x200.png'); ?>">
						<div class="title">Lai Labode</div>
						<div class="content">Member,  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Board of Directors">Board</span></div>
					</div>
					<div class="col-md-4 feature">
						<img alt="Ijeoma Lai-Labode" src="<?php echo base_url('assets/images/team/ij.jpg'); ?>">
						<div class="title">Ijeoma Lai-Labode</div>
						<div class="content">Member, Board &amp; Project Director</div>
					</div>
					<div class="col-md-4 feature">
						<img alt="Damilola Ajasa" src="<?php echo base_url('assets/images/team/dami.jpg'); ?>">
						<div class="title">Damilola Ajasa</div>
						<div class="content">Finance Lead</div>
					</div>
					<div class="col-md-4 feature">
						<img alt="ponMzer Michael Terungwago" src="<?php echo base_url('assets/images/team/mike.jpg'); ?>" width="200px" height="auto">
						<div class="title">Mzer Michael Terungwa</div>
						<div class="content">Technology Lead</div>
					</div>
				</div>
			</div>
			</div>
		</div>
	  </div>
		<!-- <div class="album py-4 bg-light" id="slanted">
		<div class="container straight">
		<h2>Testimonial:</h2>
		<div class="row">
			<div class="col-md-3">
				<div class="card text-white bg-success mb-3">
					<div class="card-header">Header</div>
					<div class="card-body">
						<h5 class="card-title">Success card title</h5>
						<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card text-white bg-success mb-3">
					<div class="card-header">Header</div>
					<div class="card-body">
						<h5 class="card-title">Success card title</h5>
						<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card text-white bg-success mb-3">
					<div class="card-header">Header</div>
					<div class="card-body">
						<h5 class="card-title">Success card title</h5>
						<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card text-white bg-success mb-3">
					<div class="card-header">Header</div>
					<div class="card-body">
						<h5 class="card-title">Success card title</h5>
						<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
					</div>
				</div>
			</div>
		</div> 

	</div>
	</div> -->

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

   