<div class="auth-wrapper">
	<div class="auth-header">
		<div class="auth-title">iPoints</div>
		<!--<div class="auth-subtitle">Simple and Clean Admin Template</div>-->
		<div class="auth-label">Register</div>
	</div>
	<div class="auth-body">
		<div class="auth-content">
			<div class="form-group">
				<label>Account Type</label>
				<select class="form-control">
					<option selected>Merchant</option>
					<option>Subscriber</option>
					<option>Agent</option>
					<option>Underwriter</option>
					<option>Partner</option>
				</select>
			</div>
			<div class="form-group">
				<label>Industry</label>
				<select class="form-control">
					<option selected>Banking</option>
					<option>Hotels</option>
				</select>
			</div>
			<div class="form-group">
				<label>Date Picker</label>
				<input class="single-daterange form-control" placeholder="Date of birth" type="text" value="04/12/1978">
			</div>
			<div class="form-group">
				<label>Mobile Number</label>
				<input class="form-control" placeholder="Enter Mobile" type="text">
			</div>
			<div class="form-group">
				<label>Email</label>
				<input class="form-control" placeholder="Enter email" type="email">
			</div>
			<div class="form-group">
				<label>Local Government of Residence</label>
				<select class="form-control">
					<option selected>Eti-Osa</option>
					<option>Yaba</option>
					<option>Mainland</option>
					<option>Lekki</option>
					<option>Sabo</option>
				</select>
			</div>
			<div class="form-group">
				<label>Name of Next of Kin</label>
				<input class="form-control" placeholder="Enter Next of Kin" type="text">
			</div>
			<div class="form-group">
				<label>Next of Kin Mobile Number</label>
				<input class="form-control" placeholder="Next of Kin Mobile Number" type="text">
			</div>
			<div class="form-group">
				<label>Business Address</label>
				<input class="form-control" placeholder="Enter business Address" type="text">
			</div>
			<div class="form-group">
				<label>Business Address</label>
				<input class="form-control" placeholder="Enter business Address" type="text">
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label> Password</label>
						<input class="form-control" placeholder="Password" type="password">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label>Confirm Password</label>
						<input class="form-control" placeholder="Password" type="password">
					</div>
				</div>
			</div>
		</div>
		<div class="auth-footer">
			<button class="btn btn-primary">Register Now!</button>
			<div class="pull-right auth-link">
				<a href="<?php echo base_url() . 'auth/login'; ?>">Already have an account?</a>
			</div><hr />
			<div class="row">
				<div class="col-sm-12 text-center"><a href="<?php echo  base_url();?>">Back to Site</a></div>
			</div>
		</div>
	</div>
</div>