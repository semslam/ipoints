<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" /> -->
<!-- <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css"> -->
<style type="text/css">
      a{ color: #007bff; font-weight: bold;}
</style>
<div class="breadcrumb">
	<a href="">Home</a> 
	<a href="">Products</a> 
	<a href="">Iphone 6</a>
</div>
<div class="content">
	<div class="panel">
		<div class="container">
			<div class="card">
				<div class="card-header">
					Codeigniter Ajax Pagination Example - ItSolutionStuff.com
				</div>
				<div class="card-body">
					<!-- Posts List -->
					<table class="table table-borderd" id='postsList'>
						<thead>
						<tr>
							<th>S.no</th>
							<th>Password</th>
						</tr>
						</thead>
						<tbody></tbody>
					</table>
					
					<!-- Paginate -->
					<div id='pagination'></div>
				</div>
			</div>
		</div>
	</div>
</div>

 
   <script type='text/javascript'>
   $(document).ready(function(){
 
     $('#pagination').on('click','a',function(e){
       e.preventDefault(); 
       var pageno = $(this).attr('data-ci-pagination-page');
       loadPagination(pageno);
     });
 
     loadPagination(0);
 
     function loadPagination(pagno){
		
       $.ajax({
         url: "<?php echo base_url() . 'PaginationTesting/loadRecord/'; ?>"+pagno,
         type: 'get',
         dataType: 'json',
         success: function(response){
            $('#pagination').html(response.pagination);
            createTable(response.result,response.row);
         }
       });
     }
 
     function createTable(result,sno){
	  // sno = Number(sno);
	   console.log(result)
	  // console.log(sno)
       $('#postsList tbody').empty();
       for(index in result){
          var id = result[index].id;
          var username = result[index].mobile_number ||  result[index].email;
          var content = result[index].created_at;
          content = content.substr(0, 60) + " ...";
          var link = result[index].created_at;
          //sno+=1;
		  console.log(result)
          var tr = "<tr>";
          tr += "<td>"+ id +"</td>";
          tr += "<td><a href='"+ link +"' target='_blank' >"+ username +"</a></td>";
          tr += "</tr>";
          $('#postsList tbody').append(tr);
  
        }
      }
       
    });
    </script>