
<div class="col-lg-12">
  <section class="panel">
    <div class="panel-body">
      <form action="" method="post">
            <div class="form-group">
                <label for="exampleInputEmail1">Name</label>
                <input type="text" class="form-control" id="exampleInputEmail1" name="username" placeholder="Enter Name" value="<?php echo set_value('username') ?>">
                <span class="text-danger"><?php echo form_error('username');?></span>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="Enter email" value="<?php echo set_value('email') ?>">
                <span class="text-danger"><?php echo form_error('email');?></span>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password" value="<?php echo set_value('password') ?>">
                <span class="text-danger"><?php echo form_error('password');?></span>
            </div>

            <div class="form-group">
                <label for="exampleInputPassword1">Role Permissions</label>
                  <select class="form-control" id="permission_group" name="permission_group">
                  <?php 
                      $query_permission_group = $this->db->query("SELECT * FROM `permission_group` ");
                      $result_permission_group = $query_permission_group->result_array();
                      echo '<option value="">Select Permission</option>';
                      foreach ($result_permission_group as $value_permission_group) {
                        echo '<option value="'.$value_permission_group['id'].'">'.$value_permission_group['permission_group_name'].'</option>';
                      }
                  ?>
                  </select>
                <span class="text-danger"><?php echo form_error('permission_group');?></span>
            </div>

            <div  class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
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
                    $('#select_all').click(function(event) {   
                        if(this.checked) {
                                // Iterate each checkbox
                               $('.permission_checkboxes :checkbox').each(function() {
                                    this.checked = true;                        
                                });
                        } else {
                              // Iterate each checkbox
                             $('.permission_checkboxes :checkbox').each(function() {
                                  this.checked = false;                        
                              });
                        }
                    });

                   
                    $('.group_permision_all').click(function(event) {  
                        var ids =  $(this).parent().parent().children('div').attr('id'); 
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







