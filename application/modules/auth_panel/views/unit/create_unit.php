
<div class="col-lg-12">
  <section class="panel">
    <header class="panel-heading">
      CREATE UNIT
      <span class="tools pull-right">
      <a href="javascript:;" class="fa fa-chevron-down"></a>
      </span>
    </header>
    <div class="panel-body">
      <form method="post">

        <div class="form-group">
          <label for="exampleInputEmail1">Syllabus</label>
           <?php if(set_value('syllabus_name') != ''){$category1=set_value('syllabus_name');}
                      else{$category1='';} 
?>
            <select class="form-control" name="syllabus_name" id="syllabus_name">
              <option value="">Select Syllabus</option>
                <?php  if(!empty($get_syllabus)){
                        foreach ($get_syllabus as $syllabus) { ?>
              <option value="<?= $syllabus['id']; ?>"  <?php if($syllabus['id']==$category1){?> selected <?php }?>><?= $syllabus['syllabus_name']; ?></option> 
                <?php } } ?>
            </select>
          <span class="text-danger"><?php echo form_error('syllabus_name');?></span>
        </div>



        <div class="form-group">
          <label for="exampleInputEmail1">Level</label>
          <?php if(set_value('level_name') != ''){$category2=set_value('level_name');}
                      else{$category2='';} 
?>
            <select class="form-control" name="level_name" id="level_name">
              <option value="0">First Select Syllabus</option>
                <?php if(!empty($level)){
                          foreach ($level as $levels) { ?>
              <option value="<?= $levels['id']; ?>" <?php if($levels['id']==$category2){?> selected <?php }?> ><?= $levels['level_name']; ?></option>
                <?php } } ?>
            </select>
        <span class="text-danger"><?php echo form_error('level_name');?></span>
        </div>



        <div class="form-group">
          <label for="exampleInputEmail1">Sub Level</label>
          <?php if(set_value('sublevel_name') != ''){ $category3=set_value('sublevel_name');}
                      else{ $category3='';} 
?>
            <select class="form-control" name="sublevel_name" id="sublevel_name">
              <option value="0">First Select Level</option>
                <?php if(!empty($sublevel)){
                    foreach ($sublevel as $sublevels) { ?>
              <option value="<?= $sublevels['id']; ?>" <?php if($sublevels['id']==$category3){?> selected <?php }?> ><?= $sublevels['sub_level']; ?></option><?php } } ?>
            </select>
            <span class="text-danger"><?php echo form_error('sublevel_name');?></span>
        </div>



       <div class="form-group">  <!--value="<?php //echo set_value('unit'); ?>" -->
          <label for="exampleInputEmail1">Unit Name</label>
            <input type="text" class="form-control" id="unit" name="unit"  placeholder="Enter Unit Name" disabled>
          <span class="text-danger"><?php echo form_error('unit');?></span>
        </div>


        <div  class="form-group">
            <input type="submit" class="btn btn-primary" id="add_unit"></input>
        </div>  
      </form>
    </div>
  </section>
</div>
<script src="<?php echo AUTH_ASSETS; ?>js/jquery3.3.1.js"></script>
<script type="text/javascript">
  window.onload = function() {
  
  if($('#sublevel_name').val() != '0'){
        $("#unit").removeAttr("disabled");
      }else{
          $("#unit").attr("disabled","disabled");
      }
};
$(document).ready(function() {
  window.base_url = '<?php echo base_url(); ?>'; 
  $(document).on('change', '#syllabus_name', function(){
    if($(this).val() != ''){
      $.ajax({
              dataType: 'JSON',
              url :base_url+'index.php/auth_panel/Unit/get_level',
              type :'POST',
              data : {
                  syllabus_name : $(this).val()
              },
              
              success: function(data){
                  if(data.login){
                      $('#level_name').empty().append('<option value="">Select Level</option>');
                      $("#level_name").append(data.data);
                  }
                  else{
                      alert(data.message);
                  }
              },
              error: function(jqXHR,textStatus,errorThrown){
                  alert('Some issue found please check and try again.');
              }
            });
    }
    else{
        $('#level_name').empty().append('<option value="0">First Select Syllabus</option>');
        $('#sublevel_name').empty().append('<option value="0">First Select Level</option>');
    }
  });
//////////////////////////////////////////////////////////////////////////////////////
    $(document).on('change', '#level_name', function(){
    if($(this).val() != ''){
      $.ajax({
              dataType: 'JSON',
              url :base_url+'index.php/auth_panel/Unit/get_sublevel',
              type :'POST',
              data : {
                  level_name : $(this).val()
              },
              
              success: function(data){
                  if(data.login){
                    $('#sublevel_name').empty().append('<option value="">Select Sub Level</option>');
                    $("#sublevel_name").append(data.data);
                    
                  }
                  else{
                      alert(data.message);
                  }
              },
              error: function(jqXHR,textStatus,errorThrown){
                  alert('Some issue found please check and try again.');
              }
            });
    }
    else{
        $('#sublevel_name').empty().append('<option value="0">First Select Sub Level</option>');
    }
  });
//////////////////////////////////////////////////////////////////////////////////////////////////////
$(document).on('change', '#sublevel_name', function(){

      if($(this).val() != '0'){
        $("#unit").removeAttr("disabled");
         $("#unit").val("");
      }else{
          $("#unit").attr("disabled","disabled");
      }

  });
////////////////////////////////////////////////////////////////////////////////////////////////////
// $(document).on('click', '#add_unit', function(){
//   $('#err1').html('');
//   $('#err2').html('');
//   $('#err3').html('');
//   $('#err4').html('');
//     if($('#syllabus_name').val() == ""){
//       $('#err1').html('The Syllabus Name is required Field');
//     }else if($('#level_name').val() == ""){
//       $('#err2').html('The Level Name is required Field');
//     }else if($('#sublevel_name').val() == ""){
//       $('#err3').html('The Sub Level Name is required Field');
//     }else if($('#unit').val() == ""){
//       $('#err4').html('The Unit Name is required Field');
//     }else{
//       $.ajax({
//               dataType: 'JSON',
//               url :base_url+'index.php/auth_panel/Unit/create_unit',
//               type :'POST',
//               data : {
//                   syllabus_name : $('#syllabus_name').val(),
//                   level_name : $('#level_name').val(),
//                   sublevel_name : $('#sublevel_name').val(),
//                   unit :  $('#unit').val()
//               },
//               success: function(data){
//                   if(data.login){
                   
                    
//                   }
//                   else{
//                       alert(data.message);
//                   }
//               },
//               error: function(jqXHR,textStatus,errorThrown){
//                   alert('Some issue found please check and try again.');
//               }
//             });
//     }
//   });
////////////////////////////////////////////////////////////////////////////////////////////////////
});
</script>




