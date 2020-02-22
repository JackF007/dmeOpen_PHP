<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <link rel="stylesheet" href="/resources/demos/style.css">
 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
//print_r($expert_data);
//die;
//$id = '';


	//print_r($data);
	//die;
	//$id = $data['id'];
	//$name = $data['name'];
	//$description = $data['description'];
	//$slogan = $data['slogan'];

if($this->session->flashdata('upload_error')){
?>
<script>
alert('Image exceeds maximum allowed size!!');
</script>
<?php }?>
<style>
#img_container {
    position:relative;
    display:inline-block;
    text-align:center;
    border:1px solid red;
}

.button {
    position:absolute;
    bottom:10px;
    right:10px;
    width:100px;
    height:30px;
}

</style>
<link href="https://silviomoreto.github.io/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
<div class="col-sm-12">
	<section class="panel">
		<header class="panel-heading">
 			HashTag Add
		<span class="tools pull-right">
		</span>
		</header>
		<div class="panel-body">

		<section class="panel">
		  <div class="panel-body">
			  <div class=" form">
				  <form action="<?php echo base_url('index.php/auth_panel/Category/add_hastag');?>" style="width: 65%;" class="cmxform form-horizontal tasi-form" id="commentForm" method="post" enctype="multipart/form-data" >
					<input type="hidden" name="id" value="<?php //if(isset($data['id'])){echo $data['id'];}?>">


					  <div class="form-group ">
						  <label for="cname" class="control-label col-lg-2"> HashTag Name</label>
						  <div class="col-lg-10">
							  <input class=" form-control" id="cname" value="" name="tag"  type="text" >
						  </div>
					  </div>

					  <div class="form-group">
						  <div class="col-lg-offset-2 col-lg-10">
							  <button class="btn btn-danger pull-right" type="submit" onclick="return abc()">Save</button>
						  </div>
					  </div>
				  </form>
			  </div>

		  </div>
        </section>
		</div>
	</section>
</div>



<div class="col-sm-12">
    <section class="panel">

        <header class="panel-heading">

            <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <!--<a href="javascript:;" class="fa fa-times"></a>-->
            </span>
        </header>
        <div class="panel-body">
            <div class="adv-table">

                <table  class="display table table-bordered table-striped" id="category-grid">
                    <thead>
                        <tr>

                            <th>Tag Name</th>
                            <th>Create Time</th>
                            <th>Update Time</th>


                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th><input type="text" data-column="0"  class="form-control search-input-text"></th>
                            <th><input type="text" data-column="1"  class="form-control search-input-text"></th>
                            <th><input type="text" data-column="2"  class="form-control search-input-text"></th>

                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>


<?php
$adminurl = AUTH_PANEL_URL;
$base_url=AUTH_ASSETS;
$custum_js = <<<EOD

  <script src="$base_url/js/form-validation-script.js"></script>
 <script type="text/javascript" src="$base_url/assets/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
 <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
 <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>


 <script type="text/javascript" language="javascript" >

     jQuery(document).ready(function() {
         var table = 'category-grid';
         var dataTable = jQuery("#"+table).DataTable( {
             "processing": true,
             "pageLength": 50,
             "serverSide": true,
             "order": [[ 0, "desc" ]],
             "columnDefs": [ {
             "targets": [1,2], // column or columns numbers
             "orderable": false, // set orderable for selected columns

            }],
             "ajax":{

                 url :"$adminurl"+"Category/ajax_hash_list", // json datasource
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
          $('.search-input-select').on( 'change', function () {   // for select box
                     var i =$(this).attr('data-column');
                     var v =$(this).val();
                     dataTable.columns(i).search(v).draw();
                 } );
     } );
 </script>


  <script>
  function abc(){

      var start = $("#cname").val();
        if(start ==""){
          alert("Can not blank");
          return false;
       }

    }
  </script>


EOD;

	echo modules::run('auth_panel/template/add_custum_js',$custum_js );
?>
