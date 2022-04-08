<div class="breadcrumb">
	<a href="">Home</a> 
	<a href="">Products</a> 
	<a href="">Iphone 6</a>
</div>
<div class="content">
	<div class="panel">
		<h5>&nbsp;&nbsp;&nbsp;&nbsp; Subscribers Manager</h5>
			<div class="content-box">
				<div class="row">
						<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5><i class="fa fa-search-plus" aria-hidden="true"></i> Filter Search</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<form id="userForm">
												<div class="row">
													<div class="form-group col-sm-3">
														<input title="Enter Users Email or Phone" type="text" placeholder="Users Phone Or Email (Optional)" name="customerName"  class="form-control customerName">
														<input type="hidden" name="customerId" class="customerId">
														<div class="validation-message" data-field="customerId"></div>
													</div>
													<div class='form-group col-sm-3'>
														<select class="form-control fitter" name="fitter">
															<option value="">Choose User's Filter </option>
															<option value="date_range">Date Range</option>
															<option value="kyc">KYC</option>
															<option value="state">State</option>
															<option value="status">Status</option>
														</select>
														<div class="validation-message" data-field="fitter"></div> 
													</div>
													<div class='col-md-3 date_range all'>
														<div class="form-group">
															<div class='input-group date' id='start'>
																<input type='text' placeholder="Start Date" name="start_date" class="form-control clear start_date" />
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<div class="validation-message" data-field="start_date"></div>
														</div>
													</div>
													<div class='col-md-3 date_range all'>
														<div class="form-group">
															<div class='input-group date' id='end'>
																<input type='text'  placeholder="End Date" name="end_date"  class="form-control clear end_date" />
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<div class="validation-message" data-field="end_date"></div>
														</div>
													</div>
													<div class='form-group kyc all col-sm-3'>
														<select class="form-control clear kyc_status" name="kyc_status">
															<option value="">KYC Status</option>
															<option value="complete">Completed KYC</option>
															<option value="none-complete">Uncompleted KYC</option>
														</select>
														<div class="validation-message" data-field="kyc_status"></div>
													</div>
											
													<div class='form-group state all col-sm-3'>
														<input placeholder="Start typing to select State" type='text' class="form-control clear state" />
														<input type="hidden" name="states"  id="states" />
														<div class="validation-message" data-field="states"></div> 
													</div>
													<div class='form-group status all col-sm-3'>
														<select class="form-control clear status" name="status">
															<option value="">Choose Active User</option>
															<option value="1">Active User</option>
															<option value="0">None Active User</option>
														</select>
														<div class="validation-message" data-field="status"></div> 
													</div>
													<div class="form-group col-sm-3">
														<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
														<!-- <input type="hidden" name="hidden_start_date" value="<?php echo $start_date ?>" class="hidden_start_date">
														<input type="hidden" name="hidden_end_date" value="<?php echo $end_date ?>" class="hidden_end_date"> -->
														<button class="btn btn-success"  id="userSearch">Search</button>
													</div>
											</div>
										</form>
									</div>
								</div>
						</div>
				</div>
				<div class="row">
					<div class="col-md-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5><i class="fa fa-download" aria-hidden="true"></i>New Subscribers List</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<a  href="<?php echo base_url('dashboard/userExportReport')?>" class="btn btn-success userExportExcel url">EXPORT TO EXCEL (.XLS)</a>
										<hr>
										<table id="example" class="display" style="width:100%">
											<thead>
												<tr>
													<th>Name</th>
													<th>Position</th>
													<th>Office</th>
													<th>Age</th>
													<th>Start date</th>
													<th>Salary</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Tiger Nixon</td>
													<td>System Architect</td>
													<td>Edinburgh</td>
													<td>61</td>
													<td>2011/04/25</td>
													<td>$320,800</td>
												</tr>
												<tr>
													<td>Garrett Winters</td>
													<td>Accountant</td>
													<td>Tokyo</td>
													<td>63</td>
													<td>2011/07/25</td>
													<td>$170,750</td>
												</tr>
												<tr>
													<td>Ashton Cox</td>
													<td>Junior Technical Author</td>
													<td>San Francisco</td>
													<td>66</td>
													<td>2009/01/12</td>
													<td>$86,000</td>
												</tr>
												<tr>
													<td>Cedric Kelly</td>
													<td>Senior Javascript Developer</td>
													<td>Edinburgh</td>
													<td>22</td>
													<td>2012/03/29</td>
													<td>$433,060</td>
												</tr>
												<tr>
													<td>Airi Satou</td>
													<td>Accountant</td>
													<td>Tokyo</td>
													<td>33</td>
													<td>2008/11/28</td>
													<td>$162,700</td>
												</tr>
												<tr>
													<td>Brielle Williamson</td>
													<td>Integration Specialist</td>
													<td>New York</td>
													<td>61</td>
													<td>2012/12/02</td>
													<td>$372,000</td>
												</tr>
												<tr>
													<td>Herrod Chandler</td>
													<td>Sales Assistant</td>
													<td>San Francisco</td>
													<td>59</td>
													<td>2012/08/06</td>
													<td>$137,500</td>
												</tr>
												<tr>
													<td>Rhona Davidson</td>
													<td>Integration Specialist</td>
													<td>Tokyo</td>
													<td>55</td>
													<td>2010/10/14</td>
													<td>$327,900</td>
												</tr>
												<tr>
													<td>Colleen Hurst</td>
													<td>Javascript Developer</td>
													<td>San Francisco</td>
													<td>39</td>
													<td>2009/09/15</td>
													<td>$205,500</td>
												</tr>
												<tr>
													<td>Sonya Frost</td>
													<td>Software Engineer</td>
													<td>Edinburgh</td>
													<td>23</td>
													<td>2008/12/13</td>
													<td>$103,600</td>
												</tr>
												<tr>
													<td>Jena Gaines</td>
													<td>Office Manager</td>
													<td>London</td>
													<td>30</td>
													<td>2008/12/19</td>
													<td>$90,560</td>
												</tr>
												<tr>
													<td>Quinn Flynn</td>
													<td>Support Lead</td>
													<td>Edinburgh</td>
													<td>22</td>
													<td>2013/03/03</td>
													<td>$342,000</td>
												</tr>
												<tr>
													<td>Charde Marshall</td>
													<td>Regional Director</td>
													<td>San Francisco</td>
													<td>36</td>
													<td>2008/10/16</td>
													<td>$470,600</td>
												</tr>
												<tr>
													<td>Haley Kennedy</td>
													<td>Senior Marketing Designer</td>
													<td>London</td>
													<td>43</td>
													<td>2012/12/18</td>
													<td>$313,500</td>
												</tr>
												<tr>
													<td>Tatyana Fitzpatrick</td>
													<td>Regional Director</td>
													<td>London</td>
													<td>19</td>
													<td>2010/03/17</td>
													<td>$385,750</td>
												</tr>
												<tr>
													<td>Michael Silva</td>
													<td>Marketing Designer</td>
													<td>London</td>
													<td>66</td>
													<td>2012/11/27</td>
													<td>$198,500</td>
												</tr>
												<tr>
													<td>Paul Byrd</td>
													<td>Chief Financial Officer (CFO)</td>
													<td>New York</td>
													<td>64</td>
													<td>2010/06/09</td>
													<td>$725,000</td>
												</tr>
												<tr>
													<td>Gloria Little</td>
													<td>Systems Administrator</td>
													<td>New York</td>
													<td>59</td>
													<td>2009/04/10</td>
													<td>$237,500</td>
												</tr>
												<tr>
													<td>Bradley Greer</td>
													<td>Software Engineer</td>
													<td>London</td>
													<td>41</td>
													<td>2012/10/13</td>
													<td>$132,000</td>
												</tr>
												<tr>
													<td>Dai Rios</td>
													<td>Personnel Lead</td>
													<td>Edinburgh</td>
													<td>35</td>
													<td>2012/09/26</td>
													<td>$217,500</td>
												</tr>
												<tr>
													<td>Jenette Caldwell</td>
													<td>Development Lead</td>
													<td>New York</td>
													<td>30</td>
													<td>2011/09/03</td>
													<td>$345,000</td>
												</tr>
												<tr>
													<td>Yuri Berry</td>
													<td>Chief Marketing Officer (CMO)</td>
													<td>New York</td>
													<td>40</td>
													<td>2009/06/25</td>
													<td>$675,000</td>
												</tr>
												<tr>
													<td>Caesar Vance</td>
													<td>Pre-Sales Support</td>
													<td>New York</td>
													<td>21</td>
													<td>2011/12/12</td>
													<td>$106,450</td>
												</tr>
												<tr>
													<td>Doris Wilder</td>
													<td>Sales Assistant</td>
													<td>Sidney</td>
													<td>23</td>
													<td>2010/09/20</td>
													<td>$85,600</td>
												</tr>
												<tr>
													<td>Angelica Ramos</td>
													<td>Chief Executive Officer (CEO)</td>
													<td>London</td>
													<td>47</td>
													<td>2009/10/09</td>
													<td>$1,200,000</td>
												</tr>
												<tr>
													<td>Gavin Joyce</td>
													<td>Developer</td>
													<td>Edinburgh</td>
													<td>42</td>
													<td>2010/12/22</td>
													<td>$92,575</td>
												</tr>
												<tr>
													<td>Jennifer Chang</td>
													<td>Regional Director</td>
													<td>Singapore</td>
													<td>28</td>
													<td>2010/11/14</td>
													<td>$357,650</td>
												</tr>
												<tr>
													<td>Brenden Wagner</td>
													<td>Software Engineer</td>
													<td>San Francisco</td>
													<td>28</td>
													<td>2011/06/07</td>
													<td>$206,850</td>
												</tr>
												<tr>
													<td>Fiona Green</td>
													<td>Chief Operating Officer (COO)</td>
													<td>San Francisco</td>
													<td>48</td>
													<td>2010/03/11</td>
													<td>$850,000</td>
												</tr>
												<tr>
													<td>Shou Itou</td>
													<td>Regional Marketing</td>
													<td>Tokyo</td>
													<td>20</td>
													<td>2011/08/14</td>
													<td>$163,000</td>
												</tr>
												<tr>
													<td>Michelle House</td>
													<td>Integration Specialist</td>
													<td>Sidney</td>
													<td>37</td>
													<td>2011/06/02</td>
													<td>$95,400</td>
												</tr>
												<tr>
													<td>Suki Burks</td>
													<td>Developer</td>
													<td>London</td>
													<td>53</td>
													<td>2009/10/22</td>
													<td>$114,500</td>
												</tr>
												<tr>
													<td>Prescott Bartlett</td>
													<td>Technical Author</td>
													<td>London</td>
													<td>27</td>
													<td>2011/05/07</td>
													<td>$145,000</td>
												</tr>
												<tr>
													<td>Gavin Cortez</td>
													<td>Team Leader</td>
													<td>San Francisco</td>
													<td>22</td>
													<td>2008/10/26</td>
													<td>$235,500</td>
												</tr>
												<tr>
													<td>Martena Mccray</td>
													<td>Post-Sales support</td>
													<td>Edinburgh</td>
													<td>46</td>
													<td>2011/03/09</td>
													<td>$324,050</td>
												</tr>
												<tr>
													<td>Unity Butler</td>
													<td>Marketing Designer</td>
													<td>San Francisco</td>
													<td>47</td>
													<td>2009/12/09</td>
													<td>$85,675</td>
												</tr>
												<tr>
													<td>Howard Hatfield</td>
													<td>Office Manager</td>
													<td>San Francisco</td>
													<td>51</td>
													<td>2008/12/16</td>
													<td>$164,500</td>
												</tr>
												<tr>
													<td>Hope Fuentes</td>
													<td>Secretary</td>
													<td>San Francisco</td>
													<td>41</td>
													<td>2010/02/12</td>
													<td>$109,850</td>
												</tr>
												<tr>
													<td>Vivian Harrell</td>
													<td>Financial Controller</td>
													<td>San Francisco</td>
													<td>62</td>
													<td>2009/02/14</td>
													<td>$452,500</td>
												</tr>
												<tr>
													<td>Timothy Mooney</td>
													<td>Office Manager</td>
													<td>London</td>
													<td>37</td>
													<td>2008/12/11</td>
													<td>$136,200</td>
												</tr>
												<tr>
													<td>Jackson Bradshaw</td>
													<td>Director</td>
													<td>New York</td>
													<td>65</td>
													<td>2008/09/26</td>
													<td>$645,750</td>
												</tr>
												<tr>
													<td>Olivia Liang</td>
													<td>Support Engineer</td>
													<td>Singapore</td>
													<td>64</td>
													<td>2011/02/03</td>
													<td>$234,500</td>
												</tr>
												<tr>
													<td>Bruno Nash</td>
													<td>Software Engineer</td>
													<td>London</td>
													<td>38</td>
													<td>2011/05/03</td>
													<td>$163,500</td>
												</tr>
												<tr>
													<td>Sakura Yamamoto</td>
													<td>Support Engineer</td>
													<td>Tokyo</td>
													<td>37</td>
													<td>2009/08/19</td>
													<td>$139,575</td>
												</tr>
												<tr>
													<td>Thor Walton</td>
													<td>Developer</td>
													<td>New York</td>
													<td>61</td>
													<td>2013/08/11</td>
													<td>$98,540</td>
												</tr>
												<tr>
													<td>Finn Camacho</td>
													<td>Support Engineer</td>
													<td>San Francisco</td>
													<td>47</td>
													<td>2009/07/07</td>
													<td>$87,500</td>
												</tr>
												<tr>
													<td>Serge Baldwin</td>
													<td>Data Coordinator</td>
													<td>Singapore</td>
													<td>64</td>
													<td>2012/04/09</td>
													<td>$138,575</td>
												</tr>
												<tr>
													<td>Zenaida Frank</td>
													<td>Software Engineer</td>
													<td>New York</td>
													<td>63</td>
													<td>2010/01/04</td>
													<td>$125,250</td>
												</tr>
												<tr>
													<td>Zorita Serrano</td>
													<td>Software Engineer</td>
													<td>San Francisco</td>
													<td>56</td>
													<td>2012/06/01</td>
													<td>$115,000</td>
												</tr>
												<tr>
													<td>Jennifer Acosta</td>
													<td>Junior Javascript Developer</td>
													<td>Edinburgh</td>
													<td>43</td>
													<td>2013/02/01</td>
													<td>$75,650</td>
												</tr>
												<tr>
													<td>Cara Stevens</td>
													<td>Sales Assistant</td>
													<td>New York</td>
													<td>46</td>
													<td>2011/12/06</td>
													<td>$145,600</td>
												</tr>
												<tr>
													<td>Hermione Butler</td>
													<td>Regional Director</td>
													<td>London</td>
													<td>47</td>
													<td>2011/03/21</td>
													<td>$356,250</td>
												</tr>
												<tr>
													<td>Lael Greer</td>
													<td>Systems Administrator</td>
													<td>London</td>
													<td>21</td>
													<td>2009/02/27</td>
													<td>$103,500</td>
												</tr>
												<tr>
													<td>Jonas Alexander</td>
													<td>Developer</td>
													<td>San Francisco</td>
													<td>30</td>
													<td>2010/07/14</td>
													<td>$86,500</td>
												</tr>
												<tr>
													<td>Shad Decker</td>
													<td>Regional Director</td>
													<td>Edinburgh</td>
													<td>51</td>
													<td>2008/11/13</td>
													<td>$183,000</td>
												</tr>
												<tr>
													<td>Michael Bruce</td>
													<td>Javascript Developer</td>
													<td>Singapore</td>
													<td>29</td>
													<td>2011/06/27</td>
													<td>$183,000</td>
												</tr>
												<tr>
													<td>Donna Snider</td>
													<td>Customer Support</td>
													<td>New York</td>
													<td>27</td>
													<td>2011/01/25</td>
													<td>$112,000</td>
												</tr>
											</tbody>
										</table>
										<div id="pagination">
											<div class="col-sm-5">
												<div class="pagging text-left">
													<div class="dataTables_info" id="example_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div>
												</div>
											</div>
											<div class="col-sm-7">
												<div class="pagging text-right">
													<nav>
														<ul class="pagination">
															<li class="page-item"><span class="page-link"><a href="http://localhost/uici_repo/paginationTesting/loadRecord" data-ci-pagination-page="1" rel="start">‹ First</a></span></li>
															<li class="page-item"><span class="page-link"><a href="http://localhost/uici_repo/paginationTesting/loadRecord/9" data-ci-pagination-page="9" rel="prev">&lt;</a></span></li>
															<li class="page-item"><span class="page-link"><a href="http://localhost/uici_repo/paginationTesting/loadRecord/8" data-ci-pagination-page="8">8</a></span></li>
															<li class="page-item"><span class="page-link"><a href="http://localhost/uici_repo/paginationTesting/loadRecord/9" data-ci-pagination-page="9">9</a></span></li>
															<li class="page-item active"><span class="page-link">10<span class="sr-only">(current)</span></span></li>
															<li class="page-item"><span class="page-link"><a href="http://localhost/uici_repo/paginationTesting/loadRecord/11" data-ci-pagination-page="11">11</a></span></li>
															<li class="page-item"><span class="page-link"><a href="http://localhost/uici_repo/paginationTesting/loadRecord/12" data-ci-pagination-page="12">12</a></span></li>
															<li class="page-item"><span class="page-link"><a href="http://localhost/uici_repo/paginationTesting/loadRecord/11" data-ci-pagination-page="11" rel="next">&gt;</a><span aria-hidden="true"></span></span></li>
															<li class="page-item"><span class="page-link"><a href="http://localhost/uici_repo/paginationTesting/loadRecord/170" data-ci-pagination-page="170">Last ›</a></span></li>
														</ul>
													</nav>
												</div>
											</div>
										</div>
									</div>
								</div>
					</div>
				</div>
			</div>
	</div>
</div>

<script type='text/javascript'>
   $(document).ready(function(){
	// var tableUser = $('#userTable')
	var table = $('#example').DataTable({
		// "lengthMenu": [ [2, 4, 8, -1], [2, 4, 8, "All"] ],
		   // "pageLength": 10
		//    "searching": false, //desable search bar
		//    "bFilter": false,//desable search bar
		   "bInfo": false, //Dont display info e.g. "Showing 1 to 4 of 4 entries"
		   "paging": false,//Dont want paging 
		   "bPaginate": false,//Dont want paging 
	});
		// Handle click on "Expand All" button
		$('#btn-show-all-children').on('click', function(){
			// Expand row details
			table.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');
		});

		// Handle click on "Collapse All" button
		$('#btn-hide-all-children').on('click', function(){
			// Collapse row details
			table.rows('.parent').nodes().to$().find('td:first-child').trigger('click');
		});

		$('#example tbody').on('click', 'tr', function () {
			var data = table.row( this ).data();
			console.log( 'You clicked on '+data[0]+'\'s row' );
		} );
    });
    </script>