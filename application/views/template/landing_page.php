<div class="wrapper">
	<div class="top-content">
		<nav class="main-nav">
			<div class="container">
				<div class="logo">
				<a href="<?php echo base_url(); ?>">
				<img alt="<?php echo $this->config->item('sitename'); ?>" src="<?php echo base_url() . 'assets/images/logo.png'; ?>">
				</a>
				</div>
				<div class="menu">
					<ul class="simple-menu">
						<li class="active"><a href="javascript:;" data-link="body">Home</a></li>
						<li><a href="javascript:;" data-link=".feature-wrapper">Team</a></li>
						<li><a href="javascript:;" data-link=".testimonial-wrapper">Testimonial</a></li>
						<li><a href="javascript:;" data-link=".contact-wrapper">Legal</a></li>
					</ul>
					<ul class="rounded-menu">
						<?php if($this->session->userdata('active_user') == null){?>
							<li><a href="<?php echo base_url() . 'auth/login'; ?>">Login</a></li>
							<li><a href="<?php echo base_url() . 'auth/register'; ?>">Register</a></li>
						<?php }else{?>
							<li><a href="<?php echo base_url('dashboard'); ?>">Back to dashboard</a></li>
							<li><a href="<?php echo base_url() . 'auth/logout'; ?>">Logout</a></li>						
						<?php }?>
					</ul>
				</div>
				<div class="mobile-nav">
					<i class="fa fa-bars"></i>
				</div>
			</div>
		</nav>
		<div class="content">
			<div class="container">
				<div class="main-text">
					<div class="title">UICI</div>
					<div class="subtitle"><?php echo $this->config->item('sitedescription'); ?></div>
					<?php echo $this->config->item('about'); ?>
					
					<a href="#" data-toggle="modal" data-target="#about" id="about"> 
						About iPoints
					</a>
					<!-- Button trigger modal -->
					<a class="w3-button w3-account w3-clearfix" data-number="<?php echo $this->config->item('whatsapp_number'); ?>" data-message="Hi! I would like to know more about the iPoint. Will you help me?" target="_blank">
					  <i class="fa fa-whatsapp" aria-hidden="true"></i>Chat via Whatsapp
					</a>
					<a href="#" data-toggle="modal" data-target="#exampleModal" id="buy_ipoints">
					  <i class="fa fa-shopping-cart"></i>Buy iPoints
					</a>
				</div>
				<div class="image-preview">
					<div class="image-list"><img alt="health cover" src="<?php echo base_url() . 'assets/images/image-1.jpg'; ?>"></div>
					<div class="image-list"><img alt="Insurance Policy" src="<?php echo base_url() . 'assets/images/image-2.jpg'; ?>"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="report-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-4 report">
					<a class='product' href="#" data-toggle="modal" data-target="#iInsurance">
						<div class="count">
							 iInsurance
						</div>
					</a>
					<div class="devider"></div>
					<div class="count-subtitle">Incidental Insurance <br />
						<small>
							<a class='product' href="#" data-toggle="modal" data-target="#iInsurance">Learn more...</a>
						</small>
					</div>
				</div>
				<div class="col-md-4 report">				
					<a class='product' href="#" data-toggle="modal" data-target="#isavings">
						<div class="count">	
							 iSavings
						</div>
					</a>
					<div class="devider"></div>
					<div class="count-subtitle">Incidental Savings <br />
						<small>
							<a class='product' href="#" data-toggle="modal" data-target="#isavings">Learn more...</a>
						</small>
					</div>
				</div>
				<div class="col-md-4 report">				
					<a class='product' href="#" data-toggle="modal" data-target="#ipensions">
						<div class="count">	
							 iPensions
						</div>
					</a>
					<div class="devider"></div>
					<div class="count-subtitle">Incidental Pension <br />
						<small>
							<a class='product' href="#" data-toggle="modal" data-target="#iPensions">Learn more...</a>
						</small>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="feature-wrapper">
		<div class="container">
			<div class="title">UICI Team</div>
			<div class="subtitle"></div>
			<div class="col-md-12 feature-list">
				<div class="row">
					<div class="col-md-4 feature">
						<img alt="Bernadine Okeke" src="<?php echo base_url() . 'assets/images/team/bernadine-okeke-200x200.png'; ?>">
						<div class="title">Bernadine Okeke</div>
						<div class="content">Chair, <span data-toggle="tooltip" data-placement="top" title="Board of Directors">Board of Directors</span></div>
					</div>
					<div class="col-md-4 feature">
						<img alt="Dr Adeleke Oshunniyi" src="<?php echo base_url() . 'assets/images/team/adeleke-oshunniyi-200x200.png'; ?>">
						<div class="title">Dr Adeleke Oshunniyi</div>
						<div class="content">Member,  <span data-toggle="tooltip" data-placement="top" title="Board of Directors">Board</span></div>
					</div>
					<div class="col-md-4 feature">
						<img alt="Lai Labode" src="<?php echo base_url() . 'assets/images/team/lai-labode-200x200.png'; ?>">
						<div class="title">Lai Labode</div>
						<div class="content">Member,  <span data-toggle="tooltip" data-placement="top" title="Board of Directors">Board</span></div>
					</div>
					<div class="col-md-4 feature">
						<img alt="Ijeoma Lai-Labode" src="<?php echo base_url() . 'assets/images/team/ij.jpg'; ?>">
						<div class="title">Ijeoma Lai-Labode</div>
						<div class="content">Member, Board & Project Director</div>
					</div>
					<div class="col-md-4 feature">
						<img alt="Damilola Ajasa" src="<?php echo base_url() . 'assets/images/team/dami.jpg'; ?>">
						<div class="title">Damilola Ajasa</div>
						<div class="content">Finance Lead</div>
					</div>
					<div class="col-md-4 feature">
						<img width='200px' height='auto' alt="ponMzer Michael Terungwago" src="<?php echo base_url() . 'assets/images/team/mike.jpg'; ?>">
						<div class="title">Mzer Michael Terungwa</div>
						<div class="content">Technology Lead</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="testimonial-wrapper">
		<div class="container">
			<div class="title">Testimonials</div>
			<div class="subtitle"></div>
			<div class="col-md-12 testimonial-list">
				<div class="row">
					<div class="col-md-4 testimonial">
						<div class="testimonial-content">
							<div class="title">Great Service!</div>
							<div class="content"></div>
							<div class="by">
								<img alt="pongo" src="<?php echo base_url() . 'assets/images/profile-woman.png'; ?>">
								<span class="name">Adewala Ojo,</span>
								<span class="from"> Ikeja-Lagos</span>
							</div>
						</div>
					</div>
					<div class="col-md-4 testimonial">
						<div class="testimonial-content">
							<div class="title">Great Service!</div>
							<div class="content"></div>
							<div class="by">
								<img alt="pongo" src="<?php echo base_url() . 'assets/images/profile-woman.png'; ?>">
								<span class="name">Alhassan Musa,</span>
								<span class="from"> Zaria City-Kaduna</span>
							</div>
						</div>
					</div>
					<div class="col-md-4 testimonial">
						<div class="testimonial-content">
							<div class="title">Great Service!</div>
							<div class="content"></div>
							<div class="by">
								<img alt="pongo" src="<?php echo base_url() . 'assets/images/profile-woman.png'; ?>">
								<span class="name">Emeka Okafor,</span>
								<span class="from"> Abakiliki-Ebonyi</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="footer-wrapper">
		<div class="container">
				<div class="row">
					<div class="col-md-12 copyright">
						Use of this site constitutes acceptance of our 
						<a href="#" data-toggle="modal" data-target="#terms">User Agreement</a> and 
						<a href="#" data-toggle="modal" data-target="#privacy">Privacy Policy</a>. &copy; <?php auto_copyright("2018");  // 2010 - 2017 ?>
						<?php function auto_copyright($year = 'auto'){ ?>
						   <?php if(intval($year) == 'auto'){ $year = date('Y'); } ?>
						   <?php if(intval($year) == date('Y')){ echo intval($year); } ?>
						   <?php if(intval($year) < date('Y')){ echo intval($year) . ' - ' . date('Y'); } ?>
						   <?php if(intval($year) > date('Y')){ echo date('Y'); } ?>
						<?php } ?> <?php echo $this->config->item('company_name'); ?>. All rights reserved. <br /><br />Address: <?php echo $this->config->item('company_address'); ?></div>
				</div>
			</div>
	</div>
</div>
<div class="move-top">
	<i class="fa fa-chevron-up"></i>
</div>