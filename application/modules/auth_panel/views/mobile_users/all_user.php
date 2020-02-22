
<div class="col-sm-12">
	<section class="panel">
		<header class="panel-heading">
		 MOBILE USER(s) LIST
		</header>
		<div class="panel-body">
		<div class="adv-table">
		<table  class="display table table-bordered table-striped" id="all-user-grid">
  		<thead>
    		<tr>
          <th>#</th>
      		<th>User name </th>
      		<th>Email </th>
          <th>Mobile </th>
          <th>Status </th>
		      <th>Creation time </th>
          <th>Action</th>
    		</tr>
  		</thead>
      <thead>
          <tr>
              <th><input type="text" data-column="0"  class="search-input-text form-control"></th>
              <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
              <th><input type="text" data-column="2"  class="search-input-text form-control"></th>
              <th><input type="text" data-column="3"  class="search-input-text form-control"></th>
              <th><select data-column="4"  class="form-control search-input-select">
                    <option value="">All</option>
                     <option value="1">Active</option>
                     <option value="0">Disable</option>
				  
                </select></th>
			         <th></th>
              <th></th>
              
          </tr>
      </thead>
		</table>
		</div>
		</div>
	</section>
</div>

<?php
$query_string = "";
$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
              <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >

                   jQuery(document).ready(function() {
                       var table = 'all-user-grid';
                       var dataTable_user = jQuery("#"+table).DataTable( {
                           "processing": true,
                            "pageLength": 15,
                            "lengthMenu": [[15, 25, 50], [15, 25, 50]],
                           "serverSide": true,
                           "order": [[ 0, "desc" ]],
                           "ajax":{
                               url :"$adminurl"+"mobile_users/ajax_all_mobile_user_list/", // json datasource
                               type: "post",  // method  , by default get
                               error: function(){  // error handling
                                   jQuery("."+table+"-error").html("");
                                   jQuery("#"+table+"_processing").css("display","none");
                               }
                           }
                       } );
                       jQuery("#"+table+"_filter").css("display","none");
                       $('.search-input-text').on( 'keyup click', function () {   // for text boxes
                           var i =$(this).attr('data-column');  // getting column index
                           var v =$(this).val();  // getting search input value
                           dataTable_user.columns(i).search(v).draw();
                       } );
                        $('.search-input-select').on( 'change', function () {   // for select box
                            var i =$(this).attr('data-column');
                            var v =$(this).val();
                            dataTable_user.columns(i).search(v).draw();
                        } );
						// Re-draw the table when the a date range filter changes
                        $('.date-range-filter').change(function() {
                            if($('#min-date-user').val() !="" && $('#max-date-user').val() != "" ){
                                var dates = $('#min-date-user').val()+','+$('#max-date-user').val();
                                dataTable_user.columns(8).search(dates).draw();
                            }    
                        }); 
                   } );
				   
				   $('#min-date-user').datepicker({
				  		format: 'dd-mm-yyyy',
						autoclose: true
						
					});
					$('#max-date-user').datepicker({
						format: 'dd-mm-yyyy',
						autoclose: true
						
					});
               </script>

EOD;

	echo modules::run('auth_panel/template/add_custum_js',$custum_js );
?>
