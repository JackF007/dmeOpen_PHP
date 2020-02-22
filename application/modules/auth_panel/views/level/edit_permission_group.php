<?php //echo "<pre>";print_r($permission_detail);die; ?>
<div class="col-lg-12">
    
      <a href="<?php echo AUTH_PANEL_URL.'admin/make_permission_group'; ?>"><button class="pull-right btn btn-info btn-xs bold">Back </button></a>
    
    <div class="col-lg-6"></div>
</div><br><br>

<div class="col-lg-12">
                      <section class="panel">
                          <header class="panel-heading">
                              Edit role
                          </header>
                          <div class="panel-body">
                              <form role="form" method="post" action="">
                                  <div class="col-md-6">
                                  <div class="form-group">
                                      <label for="exampleInputEmail1">Role Name</label>
                                      <input type="text" placeholder="Enter permission group name" name="permission_group_name" id="exampleInputEmail1" class="form-control input-sm " value="<?php echo $permission_detail['permission_group_name']; ?>">
                                      <span style="color:red"><?php echo form_error('permission_group_name'); ?></span>
                                  </div>
                                  </div>
                                  <div class="col-md-6">
                                  
                                  </div>
                                
                                <div class="col-md-12 ">
                                  <div class="form-group checkbox_list">
									<div class="col-lg-12">
									<span style="color:red"><?php echo form_error('permission_id[]'); ?></span>
									<div class="form-group col-md-12 permission_checkboxes">
										<?php 
											$query_permission_group = $this->db->query("SELECT * FROM `backend_user_permission` GROUP BY permission_merge");
											$result_permission_group = $query_permission_group->result_array();
											foreach ($result_permission_group as $value_permission_group) {
										?>

										<label class="col-sm-4 control-label col-lg-4" for="exampleInputPassword1"><?php echo $value_permission_group['permission_merge']; ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" class="group_permision_all"> <span style="font-weight: 700 !important;">  select all </span>
										</label>
										
									<div id="<?php echo 'check_box_group_id_'.$value_permission_group['id']; ?>" class="col-lg-10">
										<?php $query = $this->db->query("SELECT * FROM `backend_user_permission` where permission_merge = '".$value_permission_group['permission_merge']."'");

											$result = $query->result_array();
											
											//$temp = '' ;
											$prmission_array = explode(",",$permission_detail['permission_fk_id']);
											foreach ($result as $value) {
												/*if($temp == $value['permission_merge']){
													$temp = $value['permission_merge'];
												} */

												if(in_array($value['id'], $prmission_array)) {
													echo '<label style="font-weight: 200 !important;" class="col-md-4">
										<input type="checkbox" value='.$value['id'].' name="permission_id[]" id="permission_id" checked="checked">&nbsp;'.$value['permission_name'].'</label>';
												} else {
										echo '<label style="font-weight: 200 !important;" class="col-md-4">
										<input type="checkbox" value='.$value['id'].' name="permission_id[]" id="permission_id">&nbsp;'.$value['permission_name'].'</label>';
										 } 
										}
										?>
									</div>
									<?php } ?>
									</div>

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



<?php
$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
             
              <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >

                jQuery(document).ready(function() {
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

                });
               </script>

EOD;

  echo modules::run('auth_panel/template/add_custum_js',$custum_js );
?>