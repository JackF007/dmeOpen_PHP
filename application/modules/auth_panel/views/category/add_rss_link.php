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
$category_id=$cat_id;
//pre($content);
if (!empty($content)) {
    $static = $content['link'];
    //$root_id=$content['root_id'];
    
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
<!--            <form method="post" action="<?=AUTH_PANEL_URL . 'Category/add_rss_link/'?>" enctype="multipart/form-data">-->
                <span id="err123" style="color:brown"></span>
                <div class="form-group">
                    <input type="url" class="form-control" required="required" value="<?=($content['link']!='')?$content['link']:''?>"  id="link" name="link" placeholder="Enter RSS Link...">                
                </div>
                <input type="hidden" id="root_id" name="root_id" value="<?=$root_id?>">
                <input type="hidden" id="category_id" name="category_id" value="<?=$category_id?>">
                <?php if (!empty($content)) { ?>  
                <input type="hidden" value="<?=$content['id']?>" id="id" name="id" class="btn btn-info">
                <input type="submit" value="Update" id="edit_all123" name="edit" class="btn btn-info">
                <?php }else{ ?>    
                <input type="button"  value="Add" id="submit_all"  name="add" class="btn btn-info">
                <?php }?>
<!--            </form>-->
        </div>
    </section>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $('#link').keyup(function(){
            $('#err123').html(''); 
    });
    $('#edit_all123').click(function(){      
        var link=$('#link').val();
        var category_id=$('#category_id').val();
        var root_id=$('#root_id').val();
        var id=$('#id').val();
        var edit='edit';
        
        $.ajax({
            url:'<?=AUTH_PANEL_URL."/Category/add_rss_link"?>',
            method:'POST',
            dataType:'json',
            data:{
                link:link,
                category_id:category_id,
                root_id:root_id,
                id:id,
                edit:edit
            },
            success:function(response){
                if(response.status==true){
                    alert(response.message);
                    $('#err123').html('');  
                    if(response.data){
                        window.location.href=response.data;
                    }
                }else{
                    $('#err123').html(response.message);  
                    
                }
            }
        });
    });
    $('#submit_all').click(function(){      
        var link=$('#link').val();
        var category_id=$('#category_id').val();
        var root_id=$('#root_id').val();
        var edit='edit';
        
        $.ajax({
            url:'<?=AUTH_PANEL_URL."/Category/add_rss_link"?>',
            method:'POST',
            dataType:'json',
            data:{
                link:link,
                category_id:category_id,
                root_id:root_id,
                add:edit
            },
            success:function(response){
                if(response.status==true){
                    $('#err123').css({'color':'green'});
                    $('#err123').html(response.message); 
                    if(response.data){
                        window.location.href=response.data;
                    }
                }else{
                    $('#err123').css({'color':'brown'});
                    $('#err123').html(response.message);           
                }
            }
        });
    });
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
