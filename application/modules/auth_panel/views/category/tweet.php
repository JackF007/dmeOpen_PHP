
<div class="col-lg-12">
  <section class="panel">
    <header class="panel-heading">
     <?php //echo $title; ?>
    <span class="tools pull-right">
    <a href="javascript:;" class="fa fa-chevron-down"></a>
    <!--<a href="javascript:;" class="fa fa-times"></a>-->
    </span>
    </header>
    <div class="panel-body">
      <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <!-- <input type="hidden" value="<?php //echo $id; ?>" name="sub_category_id"> -->
                <label for="exampleInputEmail1">Name</label>

                <span id="err" style="color:brown;display:none">Page content can not be blank.</span>
                <textarea style="width:100%;height:100px" required="required"  id="post" name="post" placeholder="Enter content here..."></textarea>
            </div>

            <div class="form-group">
                <label for="exampleInputEmail1">Image</label>
                <input type="file"  id="photo" name="photo" onchange="return readURL(this)" value="">
                <span class="text-danger"><?php echo form_error('sub_category_logo');?></span>
            <br>
                <figure class="upload_img" style="display: none;">
               <img src="" id="logo-img-tag" width="100px" height="100px"/></figure>
            </div>


            <div class="dropzone" id="myDropzone"></div>

           <!-- <div class="dropzone" id="myDropzone"></div> -->

            <div  class="form-group">
                <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                <input type="button"  value="Add" id="submit-all"  name="add" class="btn btn-info">
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

     Dropzone.options.myDropzone= {
     url: '<?='http://52.205.20.98/gyacomo/index.php/auth_panel/category/add_post/'?>',
     autoProcessQueue: false,
     uploadMultiple: true,
     parallelUploads: 5,
     maxFiles: 5,
     maxFilesize: 10,
     // acceptedFiles: 'image/*',
     acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf,.docx,.xlsx,.csv",
     addRemoveLinks: true,
     init: function() {
         dzClosure = this; // Makes sure that 'this' is understood inside the functions below.

         // for Dropzone to process the queue (instead of default form behavior):
         document.getElementById("submit-all").addEventListener("click", function(e) {
             // Make sure that the form isn't actually being sent.
             e.preventDefault();
             e.stopPropagation();
             dzClosure.processQueue();
         });

         //send all the form data along with the files:
         this.on("sendingmultiple", function(data, xhr, formData) {
                var image=document.getElementById('photo').files[0];
                formData.append("post", $("[name='post']").val());
                formData.append('photo',image);

         });

         this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            if(obj.status='true'){
               //  alert(obj.message);
                // setTimeout(function(){window.location.reload()},1000);
              
                setTimeout(function(){window.location.href='<?=base_url('index.php/auth_panel/Category/open_data')?>'},1000);
            }
            if(obj.status='false'){
                alert(obj.message);
            }

         });
     }

     }



</script>
