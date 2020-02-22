
<div class="col-lg-12">
  <section class="panel">
    <header class="panel-heading">
      CREATE SUBCATEGORY 
    <span class="tools pull-right">
    <a href="javascript:;" class="fa fa-chevron-down"></a>
    <!--<a href="javascript:;" class="fa fa-times"></a>-->
    </span>
    </header>
    <div class="panel-body">
      <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="exampleInputEmail1">Name</label>
                <input type="text" class="form-control" id="exampleInputEmail1" name="sub_category_name" placeholder="Enter Name" value="<?php echo set_value('sub_category_name') ?>">
                <span class="text-danger"><?php echo form_error('sub_category_name');?></span>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Image</label>
                <input type="file"  id="exampleInputEmail1" name="sub_category_logo" onchange="return readURL(this)" value="<?php echo set_value('sub_category_logo')?>">
                <span class="text-danger"><?php echo form_error('sub_category_logo');?></span>
            <br>
                <figure class="upload_img" style="display: none;">
                 
               <img src="" id="logo-img-tag" width="100px" height="100px"/></figure>
            </div>
            

          

            <div  class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
      </form>
    </div>
    </section>
</div>

<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
    <script type="text/javascript">
      /* Image Upload */

function readURL(input) {
    var url = input.value;
    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
    if (input.files && input.files[0]&& (ext == "png" || ext == "jpeg" || ext == "jpg")) {
      $('.upload_img').css({"display":"block"});
          var reader = new FileReader();
             reader.onload = function (e) {

                 $('#logo-img-tag').attr('src', e.target.result);
             }
             reader.readAsDataURL(input.files[0]);
         }
     }

    </script>







