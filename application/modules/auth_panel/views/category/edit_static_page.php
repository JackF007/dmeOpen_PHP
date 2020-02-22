<?php //print_r($image);die; ?>

    <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading">
      EDIT SUBCATEGORY
    <span class="tools pull-right">
    <a href="javascript:;" class="fa fa-chevron-down"></a>
    <!--<a href="javascript:;" class="fa fa-times"></a>-->
    </span>
    </header>
          <div class="panel-body">
              <form  method="post"  enctype="multipart/form-data">
                    <div class="form-group">

                         <input type="hidden" name="id" value="<?php echo $static_data['sub_category_id']; ?>">

                        <!-- <input type="hidden" name="cat_id" value="<?php //echo $static_data['id']; ?>"> -->
                        <label for="exampleInputEmail1">Name</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" name="content" placeholder="Enter Category name" value="<?php echo $static_data['content']; ?>">
                        <span class="text-danger"><?php echo form_error('sub_category_name');?></span>

                    </div>

                      <div class="form-group">
                        <div class="col-md-12">
                          <?php foreach ($image as $img) { ?>
                            <div class="col-md-3">
                        <label for="logo">Image</label>
                        <input type="file"  id="logo" name="file_name" onchange="return readURL(this)">
                        <span class="text-danger"><?php echo form_error('file_name');?></span>

                      <br>
                      <figure class="upload_img">
                       <?php  if(!empty($img['file_name']))
                       {

                        $image = $img['file_name'];

                       }else {
                        $image = 'black-male-user-symbol.png';
                       } ?>
               <img src="<?php echo base_url().'images/static_content/'.$image;?>" width="100px" height="100px"/>
             </figure>
             </div>
                      <?php }?>
                    </div>

                    <button type="submit" class="btn btn-primary pull-right">Update</button>
              </form>
            </div>
          </div>
      </section>
    </div>

 <script type="text/javascript">
      /* Image Upload */


function readURL(input) {
    var url = input.value;
    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
    if (input.files && input.files[0]&& (ext == "png" || ext == "jpeg" || ext == "jpg")) {
      $('.upload_img').css({"display":"block"});
          var reader = new FileReader();
             reader.onload = function (e) {

                 $('#tag-img-tag').attr('src', e.target.result);
             }
             reader.readAsDataURL(input.files[0]);
         }
     }
    </script>
