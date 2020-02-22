<?php //echo "<pre>";print_r($_SESSION);die;  ?>

<div class="col-lg-12">
          <section class="panel">
				<header class="panel-heading">
					Make roles
					<span class="tools pull-right">
						<a href="javascript:;" id="add_permission_group_down" class=""><b class="btn-xs bold  btn btn-success"> + Add new role </b></a>
					</span>
				</header>
					<?php if(form_error('permission_id[]') != '' || form_error('permission_group_name') != '' )
					{ 
						$css_value = 'display:block';
							} else {
						$css_value = 'display:none';
					}

					?>
              <div class="panel-body add_permission_body" style="<?php echo $css_value; ?>">
                  <form role="form" method="post" action="">
                      <div class="col-md-12">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Role Name</label>
                          <input type="text" placeholder="Enter role group name" name="permission_group_name" id="exampleInputEmail1" class="form-control input-sm " value="<?php echo set_value('permission_group_name') ?>">
                          <span style="color:red"><?php echo form_error('permission_group_name'); ?></span>
                      </div>
                      </div>
                      
                    
                    <div class=" ">
                      <div class="form-group checkbox_list">
						
						<span style="color:red"><?php echo form_error('permission_id[]'); ?></span>
						<div class="permission_checkboxes">
							<?php 
								$query_permission_group = $this->db->query("SELECT * FROM `backend_user_permission` GROUP BY permission_merge");
								$result_permission_group = $query_permission_group->result_array();
								foreach ($result_permission_group as $value_permission_group) {
							?>

							<label class=" control-label col-md-4" for="exampleInputPassword1"><?php echo $value_permission_group['permission_merge']; ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" class="group_permision_all"> <span style="font-weight: 700 !important;">  select all </span>
							</label>
							
						<div id="<?php echo 'check_box_group_id_'.$value_permission_group['id']; ?>" class="col-lg-12">
							<?php $query = $this->db->query("SELECT * FROM `backend_user_permission` where permission_merge = '".$value_permission_group['permission_merge']."'");

								$result = $query->result_array();
								//echo "<pre>";print_r($result);echo "</pre>";
								$temp = '' ;
								foreach ($result as $value) {
									if($temp == $value['permission_merge']){
										$temp = $value['permission_merge'];
									} 
							?>
							<label style="font-weight: 200 !important;" class="col-md-3">
							<input type="checkbox" value="<?php echo $value['id']; ?>" name="permission_id[]" id="permission_id"> <?php echo $value['permission_name']; ?> </label>
							<?php } ?>
						</div>
						<?php } ?>
						</div>

						
                      </div>
                      
                    </div><br><br>

                    <div class="col-md-12 "> 

                        <button class="btn btn-info pull-right btn-sm" type="submit">Submit</button>
                    </div>
                    
                  </form>
              </div>
            </section>
</div>




<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading">
            ROLE LIST(s)
        <span class="tools pull-right">
        <a href="javascript:;" class="fa fa-chevron-down"></a>
        </span>
        </header>
        <div class="panel-body">
        <div class="adv-table">
        <table  class="display table table-bordered table-striped" id="backend-user-grid">
        <thead>
            <tr>
	            <th># </th>
	            <th>Role Name </th>
	            <th>Action</th>
            </tr>
        </thead>
      <thead>
          <tr>
              <th><input type="text" data-column="0"  class="form-control search-input-text"></th>
              <th><input type="text" data-column="1"  class="form-control search-input-text"></th>
              <th></th>
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
             
              <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >

                jQuery(document).ready(function() {
                		$('form')[0].reset();
                	    var table = 'backend-user-grid';
                        var dataTable = jQuery("#"+table).DataTable( {
                           "processing": true,
                           "pageLength": 50,
                           "serverSide": true,
                           "order": [[ 0, "desc" ]],
                           "ajax":{
                               url :"$adminurl"+"admin/ajax_get_permission_group_list", // json datasource
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

                    $('.group_permision_all').click(function(event) {
                        var ids =  $(this).parent().next('div').attr('id'); 
                        if(this.checked) {
                                // Iterate each checkbox
                               $('#'+ids+' :checkbox').each(function() {
                                    this.checked = true;                        
                                });
                        } else {
                              // Iterate each checkbox
                             $('#'+ids+' :checkbox').each(function() {
                                  this.checked = false;                        
                              });
                        }
                    });

                    $('a').click(function() {
                    	var id = $(this).attr('id');
                    	if(id == 'add_permission_group_down') {
                    		$('.add_permission_body').css('display','block');
                    		$(this).attr('id','add_permission_group_up');
                    	} else if(id == 'add_permission_group_up') {
                    		$('.add_permission_body').css('display','none');
                    		$(this).attr('id','add_permission_group_down');
                    	}
                    })

                });
               </script>

EOD;

  echo modules::run('auth_panel/template/add_custum_js',$custum_js );
?>
