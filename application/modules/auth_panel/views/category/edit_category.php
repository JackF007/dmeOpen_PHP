<?php  ?>

    <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading">
      EDIT CATEGORY
    <span class="tools pull-right">
    <a href="javascript:;" class="fa fa-chevron-down"></a>
    <!--<a href="javascript:;" class="fa fa-times"></a>-->
    </span>
    </header>
          <div class="panel-body">
              <form  method="post"  enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="hidden" name="id" value="<?php echo $data['sub_category_id']; ?>">
                        <label for="exampleInputEmail1">Name</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" name="sub_category_name" placeholder="Enter Category name" value="<?php echo $data['sub_category_name']; ?>">
                        <span class="text-danger"><?php echo form_error('sub_category_name');?></span>

                    </div>

                    <div class="form-group">
                        <label for="logo">Image</label>
                        <input type="file"  id="logo" name="sub_category_logo" onchange="return readURL(this)">
                        <span class="text-danger"><?php echo form_error('sub_category_logo');?></span>
                      <br>
                      <figure class="upload_img">
                       <?php  if(!empty($data['sub_category_logo']))
                       {

                        $image = $data['sub_category_logo'];

                       }else {
                        $image = 'black-male-user-symbol.png';
                       } ?>
               <img src="<?php echo base_url().'images/sub_category/'.$image;?>" id="tag-img-tag" width="100px" height="100px"/></figure>


                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
              </form>
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
