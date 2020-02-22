
<div class="col-lg-12">
  <section class="panel">
    <header class="panel-heading">
      CREATE LEVEL
    <span class="tools pull-right">
    <a href="javascript:;" class="fa fa-chevron-down"></a>
    <!--<a href="javascript:;" class="fa fa-times"></a>-->
    </span>
    </header>
    <div class="panel-body">
      <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="exampleInputEmail1">Syllabus</label>
                <?php if(set_value('syllabus_name') != ''){$category1=set_value('syllabus_name');}
                      else{$category1='';} 
?>
                <select class="form-control" name="syllabus_name">
                    <option value="">Select Syllabus</option>
                    <?php foreach ($get_syllabus as $syllabus) 
                    { ?>
                      <option value="<?= $syllabus['id']; ?>" <?php if($syllabus['id']==$category1){?> selected <?php }?> ><?= $syllabus['syllabus_name']; ?></option>
                     
                      <?php } ?>
                  </select>
                <span class="text-danger"><?php echo form_error('syllabus_name');?></span>
            </div>

             <div class="form-group">
                <label for="exampleInputEmail1">Category</label>
                 <?php if(set_value('category_name') != ''){$category1=set_value('category_name');}
                      else{$category1='';} ?>
                <select class="form-control" name="category_name">
                    <option value="">Select Category</option>
                    <?php foreach ($get_category as $category) 
                    { ?>
                      <option value="<?= $category['id']."-".$category['category_name']; ?>" <?php if($category['id']."-".$category['category_name']==$category1){?> selected <?php }?>><?= $category['category_name']; ?></option>
                     
                      <?php } ?>
                  </select>
                <span class="text-danger"><?php echo form_error('category_name');?></span>
            </div>

        <div class="form-group">
                <label for="exampleInputEmail1">Image</label>
                <input type="file"  id="exampleInputEmail1" name="level_image" onchange="return readURL(this)" value="<?php echo set_value('level_image')?>">
                <span class="text-danger"><?php echo form_error('level_image');?></span>
            </br>
                <figure class="upload_img" style="display: none;">
                 
               <img src="" id="profile-img-tag" width="100px" height="100px"/></figure>
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

                 $('#profile-img-tag').attr('src', e.target.result);
             }
             reader.readAsDataURL(input.files[0]);
         }
     }

    </script>







