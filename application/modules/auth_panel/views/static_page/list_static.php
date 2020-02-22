<div class="col-sm-12">
	<section class="panel">
		<header class="panel-heading">
 			Static Page
		<span class="tools pull-right">
		<a href="javascript:;" class="fa fa-chevron-down"></a>
		<!--<a href="javascript:;" class="fa fa-times"></a>-->
		</span>
		</header>
		<div class="panel-body">
		<div class="adv-table">
		<table  class="display table table-bordered table-striped hover" id="course-list-grid">
  		<thead>
    		<tr>
                <th># </th>
                <th>Title</th>
                <th>Content</th>
                <th>Files</th>
                <th>Updated On</th>
                <th>Action</th>
               
    		</tr>
  		</thead>
       <thead>
          <tr>
              <th><input type="text" data-column="0"  class="form-control search-input-text"></th>
              <th><input type="text" data-column="1"  class="form-control search-input-text"></th>
              <th><input type="text" data-column="2"  class="form-control search-input-text" ></th>
              <th></th>
              <th><input type="text" data-column="4"  class="form-control search-input-text"> </th>     
              <th style="width:50px;"></th>
          </tr>
      </thead>
		</table>
		</div>
		</div>
	</section>
</div>

<?php

$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
              <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >

                   jQuery(document).ready(function() {
                       var table = 'course-list-grid';
                       var dataTable = jQuery("#"+table).DataTable( {
                           "processing": true,
                           "serverSide": true,
                           "order": [[ 0, "desc" ]],
                           "ajax":{
                               url :"$adminurl"+"Static_Page/ajax_static_page_list", // json datasource
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
                           dataTable.columns(i).search(v).draw();
                       } );
									//		 $('.search-input-select').on( 'change', function () {   // for select box
									//			    var i =$(this).attr('data-column');
									//			    var v =$(this).val();
									//			    dataTable.columns(i).search(v).draw();
									//			} );
                   } );
               </script>

EOD;

	echo modules::run('auth_panel/template/add_custum_js',$custum_js );
?>
