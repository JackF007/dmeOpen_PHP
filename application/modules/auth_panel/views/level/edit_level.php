<?php //echo "<pre>";print_r($user_data);die; ?>

    <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading">
      EDIT LEVEL
    <span class="tools pull-right">
    <a href="javascript:;" class="fa fa-chevron-down"></a>
    <!--<a href="javascript:;" class="fa fa-times"></a>-->
    </span>
    </header>
          <div class="panel-body">
              <form method="post" enctype="multipart/form-data">
                     <div class="form-group">
                      <input type="hidden" name="id" value="<?php echo $level_data['id']; ?>">
                <label for="exampleInputEmail1">Syllabus</label>
                <?php if(set_value('syllabus_name') != ''){$category1=set_value('syllabus_name');}
                      else{$category1='';} 
?>
                <select class="form-control" name="syllabus_name">
                    <option value="">Select Syllabus</option>
                    <?php foreach ($get_syllabus as $syllabus) 
                    { $category1 = $level_data['syllabus_id'];
                      ?>
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
                    { $category1 = $level_data['category_id'].$level_data['category_name'];
                      ?>
                      <option value="<?= $category['id']."-".$category['category_name']; ?>" <?php if($category['id']== $category1){?> selected <?php }?>><?= $category['category_name']; ?></option>
                     
                      <?php } ?>
                  </select>
                <span class="text-danger"><?php echo form_error('category_name');?></span>
            </div>

             <div class="form-group">
                <label for="exampleInputEmail1">Image</label>
                <input type="file"  id="exampleInputEmail1" name="level_image" onchange="return readURL(this)" value="<?php echo set_value('level_image')?>">
                <span class="text-danger"><?php echo form_error('level_image');?></span>
            </br>
                <figure class="upload_img">
                       <?php  if(!empty($level_data['level_image']))
                       {
                        $image = $level_data['level_image'];
                       }else {
                        $image = 'black-male-user-symbol.png';
                       } ?>       
               <img src="<?php echo base_url().'web_assets/images/level_images/'.$image;?>" id="profile-img-tag" width="100px" height="100px"/></figure>
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

                 $('#profile-img-tag').attr('src', e.target.result);
             }
             reader.readAsDataURL(input.files[0]);
         }
     }
    </script>