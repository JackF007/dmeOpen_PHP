<!-- /*<style media="screen">
  .site-min-height{
    display: none;
  }
</style>*/ -->
<div class="col-sm-12">
    <section class="panel">

        <header class="panel-heading">
            <?php //echo $title; ?>
            <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <!--<a href="javascript:;" class="fa fa-times"></a>-->
            </span>
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <!-- <a class="btn-xs bold  btn btn-info pull-right" href="<?php //echo AUTH_PANEL_URL . 'Category/create_subcategory/'.$cat_id;?>">Add New</a> -->
                <a class="btn-xs bold  btn btn-info pull-right" data-toggle="modal"  href="#myModal">
                  <i class="glyphicon glyphicon-plus"></i> Add Sub Category
                </a>
                <table  class="display table table-bordered table-striped" id="category-grid">
                    <thead>
                        <tr>
                            <!-- <th> Id</th> -->
                            <th>Sub Category Name</th>
                            <th>Logo</th>
                            <th>Create Time</th>
                            <th>Update Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th><input type="text" data-column="0"  class="form-control search-input-text"></th>
                            <!-- <th><input type="text" data-column="1"  class="form-control search-input-text"></th> -->
                            <th></th>
                            <th><input type="text" data-column="2"  class="form-control search-input-text"></th>
                            <th><input type="text" data-column="3"  class="form-control search-input-text"></th>
                            <th><input type="text" data-column="4"  class="form-control search-input-text"></th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add Sub Category</h4>
            </div>
            <div class="modal-body">
               <section class="panel">
                  <div class="panel-body">
                      <div class=" form">

                          <form class="cmxform form-horizontal tasi-form" id="commentForm" method="Post" action="<?=AUTH_PANEL_URL . 'Category/add_sub_copp/'.$id. "/".$c_id;?>" novalidate="novalidate" enctype="multipart/form-data">
                              <div class="form-group ">
                                  <label for="cname" class="control-label col-lg-3">Name*</label>
                                  <div class="col-lg-9">
                                      <input class=" form-control" id="cname" name="child_name" minlength="2" type="text" required="" aria-required="true">
                                  </div>
                              </div>
                              <div class="form-group ">
                                  <label for="cemail" class="control-label col-lg-3">Logo</label>
                                  <div class="col-lg-9">
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <span class="btn btn-white btn-file">
                                        <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select file</span>
                                        <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                                        <input type="file" name="child_logo" class="default">
                                        </span>
                                        <span class="fileupload-preview" style="margin-left:5px;"></span>
                                        <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none; margin-left:5px;"></a>
                                      </div>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                      <button class="btn btn-danger" type="submit">Save</button>
                                      <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                                  </div>
                              </div>
                          </form>
                      </div>
                  </div>
              </section>
            </div>
        </div>
    </div>
</div>




<?php
$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
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
                            "targets": [1,2,3,4,5], // column or columns numbers
                            "orderable": false, // set orderable for selected columns
                            }],
                           "ajax":{
                              url :"$adminurl"+"Category/ajax_static_page1/"+"$id", // json datasource
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

EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
