<?php
  //  pre($content);die;
?>
<style>
#profile-upload{
background-image: url('https://cdn4.iconfinder.com/data/icons/keynote-and-powerpoint-icons/256/Plus-512.png');
background-size:cover;
background-position: center;
height: 150px; width: 150px;
border: 1px solid #bbb;
position:relative;
border-radius:250px;
overflow:hidden;
}
#profile-upload:hover input.upload{
display:block;
}
#profile-upload:hover .hvr-profile-img{
display:inline-block;
}
#profile-upload .fa{   margin: auto;
position: absolute;
bottom: -4px;
left: 0;
text-align: center;
right: 0;
padding: 6px;
opacity:1;
transition:opacity 5s linear;
-webkit-transform: scale(.75);
}
#profile-upload:hover .fa{
opacity:1;
-webkit-transform: scale(1);
}
#profile-upload input.upload {
z-index:1;
left: 0;
margin: 0;
bottom: 0;
top: 0;
padding: 0;
opacity: 0;
outline: none;
cursor: pointer;
position: absolute;
background:#ccc;
width:100%;
display:none;
}

#profile-upload .hvr-profile-img {
width:100%;
height:100%;
display: none;
position:absolute;
vertical-align: middle;
position: relative;
background: transparent;
}
#profile-upload .fa:after {
content: "";
position:absolute;
bottom:0; left:0;
width:100%; height:0px;
background:rgba(0,0,0,0.3);
z-index:-1;
transition: height 0.3s;
}

#profile-upload:hover .fa:after { height:80%; }
</style>
<?php

if (!empty($content)) {
    $static = $content['content'];
    $sub_cat_id=$content['sub_category_id'];
    $file_name=$content['file_name'];
     $file =json_decode($file_name,TRUE);

     $count_file = count($file);
     for($i=0;$i<$count_file;$i++)
     {
       $ext[] = pathinfo($file[$i], PATHINFO_EXTENSION);
     }

}

?>
<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading">
<?php echo $title; ?>
            <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <!--<a href="javascript:;" class="fa fa-times"></a>-->
            </span>
        </header>
        <div class="panel-body">
            <form method="post" action="<?=AUTH_PANEL_URL . 'Category/add_static_content/'?>" enctype="multipart/form-data">
                <div class="adv-table"style>
                    <span id="err" style="color:brown;display:none">Page content can not be blank.</span>
                    <textarea style="width:100%;height:200px" required="required"  id="content" name="content" placeholder="Enter content here..."><?php if($content['content']!='')echo $content['content'];?></textarea>
                </div>
                <input type="hidden" id="cat" name="cat" value="<?=$cat?>">
                <input type="hidden" id="sub_cat" name="sub_cat" value="<?=$id?>">


                <?php if (!empty($content)) { ?>
                <div class="adv-table" style="border: 2px #000">
                    <div class="row">
                        <div class="col-md-3">
                            <!-- <img title="Add file" style="htight:100px;width:100px" class="img-responsive" src=""> -->
                           <!-- Select images: <input type="file" name="logo" multiple> -->

                        </div>
                    </div>
                </div><br/>
        <div class="form-group ">
				   <div class="col-lg-12" style="margin-bottom:10px">
   						  <?php for($i=0;$i<count($file);$i++){?>
       						  <div class="col-sm-3">
                         <?php
                         if(isset($file[$i]) && $file[$i]!=''){?>

                        <img class='img_container' src='<?php
                        if($ext[$i]=='pdf'){ echo base_url()."images/pdf.png";}

                        else if($ext[$i]=='docx'){echo base_url()."images/docx.jpg";}
                        else if($ext[$i]=='xlsx'){echo base_url()."images/xlsx.png";}
                        else{ echo base_url()."images/static_content/".$file[$i];}
                        ?>' style='width:150px;height:150px'>
                        <a href="<?=AUTH_PANEL_URL.'Category/delete_image/'.$id.'/'.$cat.'/'.$file[$i]?>"><img style="margin: -90px;height: 25px;" src="<?=AUTH_ASSETS.'img/details_close.png'?>"></a>
                        <div id="showfileUpload<?=$i?>"></div>
                      <?php }else{
                         }?>
       						  </div>
                 <?php } ?>
                 <div id='profile-upload' class="col-sm-3">
                    <div class="hvr-profile-img"><input type="file" name="img" id='getval'  class="upload w180" title="Add files" id="imag"></div>
                      <i class="fa fa-camera"></i>
                </div>
			    </div>
		  </div></br></br>
    <input type="submit" value="Update" onclick="return input_chk()" name="edit" class="btn btn-info">
    <?php }else{ ?>
    <div class="dropzone" id="myDropzone"></div>
    <br/>
    <!-- <input type="submit"  value="Add" onclick="input_chk()" name="add" class="btn btn-info"> -->
    <input type="button"  value="Add" id="submit-all"  name="add" class="btn btn-info">

    <?php }?>
</form>
<!--            <form action="/" class="dropzone">

   If you want control over the fallback form, just add it here:
  <div class="fallback">  This div will be removed if the fallback is not necessary
    <input type="file" name="youfilename" />
    etc...
  </div>
</form>-->
        </div>
    </section>
</div>
<script>
    // CKEDITOR.replace('content');
    // var input=CKEDITOR.instances['content'].getData();
    function input_chk(){

      console.log(input);
      alert(input); exit();
    }


    Dropzone.options.myDropzone= {
    url: '<?='http://52.205.20.98/gyacomo/index.php/auth_panel/category/add_static_content/'?>',
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
              formData.append("cat", jQuery("#cat").val());
              formData.append("sub_category_id", jQuery("#sub_cat").val());
              formData.append("content", $("[name='content']").val());
              formData.append("add", $("[name='content']").val());

        });

        this.on("success", function(file, response) {
           var obj = jQuery.parseJSON(response);
           if(obj.status='true'){
              //  alert(obj.message);
               setTimeout(function(){window.location.reload()},1000);
           }
           if(obj.status='false'){
               alert(obj.message);
           }

        });
    }

}

document.getElementById('getval').addEventListener('change', readURL, true);
function readURL(){
    var file = document.getElementById("getval").files[0];
    var reader = new FileReader();
    reader.onloadend = function(){
        document.getElementById('profile-upload').style.backgroundImage = "url(" + reader.result + ")";
    }
    if(file){
        reader.readAsDataURL(file);
    }else{
    }
}
// function upl_imm() {
//  $("#file_upld").click();
// }
</script>
<?php
$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
              <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >

                   jQuery(document).ready(function() {
                       var table = 'category-grid';
                       var dataTable = jQuery("#"+table).DataTable( {
                           "processing": true,
                           "pageLength": 50,
                           "serverSide": true,
                           "order": [[ 0, "desc" ]],
                           "ajax":{
                               url :"$adminurl"+"Category/ajax_static_page/$id", // json datasource
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
                        $('.search-input-select').on( 'change', function () {   // for select box
                                   var i =$(this).attr('data-column');
                                   var v =$(this).val();
                                   dataTable.columns(i).search(v).draw();
                               } );
                   } );


               </script>

EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
