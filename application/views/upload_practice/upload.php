<html>
<head>
    <title><?php echo $title; ?></title>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js"></script> -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> -->
    <!-- Font Awesome -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
<!-- Bootstrap core CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
<!-- Material Design Bootstrap -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.9/css/mdb.min.css" rel="stylesheet">
<!-- JQuery -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.9/js/mdb.min.js"></script>
</head>
<body>
<div class="container text-center">
		<h2>Upload An Excel File</h2>
		<div style="border: 1px solid #a1a1a1;text-align: center;width: 500px;padding:30px;margin:0px auto">
<form method="post" id="upload_form" align="center" enctype="multipart/form-data">  
<div class="custom-file">
<input type="file" name="image_file" id="image_file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01"/>
<label class="custom-file-label" for="inputGroupFile01">Choose file</label>
</div>  
                <br />  
                <br />  
                <input type="submit" name="upload" id="upload" value="Upload" class="btn btn-info" />
<?php echo "</form>"?>
<br />  
           <br />  
           <div id="uploaded_image">  
           </div> 
           <div id="loading"></div> 
           </div>
	</div>
      </body>
      <script>  
 $(document).ready(function(){  
      $('#upload_form').on('submit', function(e){  
           e.preventDefault();  
           if($('#image_file').val() == '')  
           {  
                alert("Please Select the File");  
           }  
           else  
           {  
                $.ajax({  
                     url: "<?php echo base_url(); ?>myqueue/do_upload",   
                     method:"POST",  
                     data:new FormData(this),  
                     contentType: false,  
                     cache: false,  
                     processData:false,
                     beforeSend: function() {
                        $('#uploaded_image').text("");
                        $("#loading").show();
                     $("#loading").html('Please wait....');
                      },
                     success:function(data)  
                     { 
                        $('#uploaded_image').html(data);
                        $('#loading').hide();
                        if (data) {
                            console.log(data);
                        }
                     }  
                });  
           }  
      });  
 });  
 </script>
</html>