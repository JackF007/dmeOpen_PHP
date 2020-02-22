<?php //echo "<pre>";print_r($user_data);die; ?>

<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            EDIT STATIC PAGE
            <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <!--<a href="javascript:;" class="fa fa-times"></a>-->
            </span>
        </header>
        <div class="panel-body">
            <?php //print_r($static_data);die;?>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="hidden" name="id" value="<?php if(isset($static_data)){ echo $static_data['id']; } ?>">
                    <label for="exampleInputEmail1">Content</label>
                    <textarea cols="10" rows="6" class="form-control" id="exampleInputEmail1" name="content" placeholder="Enter content" value=""><?php if(isset($static_data)){  echo $static_data['content']; }?></textarea>
                    <span class="text-danger">
                        <?php echo form_error('content');?></span>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Image</label>
                    <input type="file" id="exampleInputEmail1" name="files[]" onchange="return readURL(this)" multiple>
                    <span class="text-danger">
                        <?php echo form_error('files');?></span>
                    <br>
                    <figure class="upload_img">
                        <?php  if(isset($static_data['files']))
                       {
                            
                            $image=array();
                            $image =explode(',',$static_data['files']);
                            $count = count($image);
//                            print_r($image);die;
//                        $image = $static_data['files'];
                        if($count>1)
                                {
                          for($i=0;$i<$count;$i++){ ?>
                        <img src="<?php echo base_url().'images/static_content/'.$image[$i]; ?>" id="logo-img-tag" width="100px" height="100px" />
                        <?php } }else if($count==1) {    ?>
    
                        <img src="<?php echo base_url().'images/static_content/'.$static_data['files']; ?>" id="logo-img-tag" width="100px" height="100px" />
                        <?php } else{ }}else{}?>
                            

                    </figure>

                       
                    </div>

                     <button type="submit" class="btn btn-primary">Update</button>
              </form>
          </div>
      </section>
    </div>
    <script type="text/javascript">
      /* Image Upload */

var i=0;
function readURL(input) {
   
    var url = input.value;
    url=url+"_"+i;
//      $('.upload_img').css({"display":"block"});
//          var reader = new FileReader();
//             reader.onload = function (e) {
//                 $('#logo-img-tag').attr('src', e.target.result);
//             }
//             
//             reader.readAsDataURL(input.files[0]);
         ++i;
     }
    </script>
<?php

$custum_js = <<<EOD
               

               <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>

                <script>
                $(function () {
                    $("form[name='change_password']").validate({
                        // Specify validation rules
                        rules: {
                            new_password: "required",
                            conform_password:{required: true, equalTo: "#new_password"}
                        },
                        // Specify validation error messages
                        messages: {
                            new_password: "Please enter new password.",
                            conform_password: {required: "Please enter conform password.", equalTo:"Password not match."}
                        },
                        submitHandler: function (form) {
                            form.submit();
                        }
                    });
                });

               
              $(function() {
                $('#new_password,#conform_password').on('keypress', function(e) {
                  if (e.which == 32)
                      return false;
                  });
              });



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
