
<div class="col-lg-12">
  <section class="panel">
    <header class="panel-heading">
      CREATE SLIDES
      <span class="tools pull-right">
      <a href="javascript:;" class="fa fa-chevron-down"></a>
      </span>
    </header>
    <div class="panel-body">
      <form method="post">

        <div class="form-group">
          <label for="exampleInputEmail1">Syllabus<span style="color: red;">*</span></label>
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
          <span class="text-danger" id="syllabus_error"><?php echo form_error('syllabus_name');?></span>
        </div>



        <div class="form-group">
          <label for="exampleInputEmail1">Level<span style="color: red;">*</span></label>
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
        <span class="text-danger" id="level_error"><?php echo form_error('level_name');?></span>
        </div>



        <div class="form-group">
          <label for="exampleInputEmail1">Sub Level<span style="color: red;">*</span></label>
          <?php if(set_value('sublevel_name') != ''){ $category3=set_value('sublevel_name');}
                      else{ $category3='';} 
?>
            <select class="form-control" name="sublevel_name" id="sublevel_name">
              <option value="0">First Select Level</option>
                <?php if(!empty($sublevel)){
                    foreach ($sublevel as $sublevels) { ?>
              <option value="<?= $sublevels['id']; ?>" <?php if($sublevels['id']==$category3){?> selected <?php }?> ><?= $sublevels['sub_level']; ?></option><?php } } ?>
            </select>
            <span class="text-danger" id="sublevel_error"><?php echo form_error('sublevel_name');?></span>
        </div>


       <div class="form-group">
          <label for="exampleInputEmail1">Unit<span style="color: red;">*</span></label>
          <?php if(set_value('unit_name') != ''){ $category4=set_value('unit_name');}
                      else{ $category4='';} 
?>
            <select class="form-control" name="unit_name" id="unit_name">
              <option value="0">First Select Sub Level</option>
                <?php if(!empty($unit)){
                    foreach ($unit as $units) { ?>
              <option value="<?= $units['id']; ?>" <?php if($units['id']==$category4){?> selected <?php }?> ><?= $units['unit_name']; ?></option><?php } } ?>
            </select>
            <span class="text-danger" id="unit_error"><?php echo form_error('unit_name');?></span>
        </div>
<!-- Question -->

         <div class="form-group">  <!--value="<?php //echo set_value('unit'); ?>" -->
          <label for="exampleInputEmail1">Question<span style="color: red;">*</span></label>
        <br>
            <!-- <input type="radio" class="question"  value="1" name="question">Image Only  -->
            <input type="radio" class="question"  value="1" name="question">Image with text 
            <input type="radio" class="question"  value="2" name="question">Text Only 
            <br>
          <span class="text-danger" id="question_error"><?php echo form_error('question');?></span>
        </div>

        <!-- <div class="form-group" style="display: none;" id="imageonly"> 
            <input type="file"  id="imageOnlyQuestion" name="imageOnlyQuestion">
          <span class="text-danger" id="imageOnlyQuestionError"><?php //echo form_error('imageOnlyQuestion');?></span>
        </div> -->

        <div class="form-group" style="display: none;" id="imagewtext"> 
          <input type="file" id="imageWithQuestion" name="imageWithQuestion">
          <span class="text-danger" id="imageWithQuestionError"><?php echo form_error('imageWithQuestion');?></span>
          <br>
          <input type="text" class="form-control" id="textWithQuestion" name="textWithQuestion" placeholder="Enter Question?">
          <span class="text-danger" id="textWithQuestionError"><?php echo form_error('textWithQuestion');?></span>
        </div>

        <div class="form-group" style="display: none;" id="textonly">
            <input type="text" class="form-control" id="textOnlyQuestion" name="textOnlyQuestion" placeholder="Enter Question?">
          <span class="text-danger" id="textOnlyQuestionError"><?php echo form_error('textOnlyQuestion');?></span>
        </div>
 <!-- End Question -->

 <!-- Options -->
 <br>
        <div class="form-group"> 
            <label for="exampleInputEmail1">Options<span style="color: red;">*</span></label>
            <br>
            <!-- <input type="radio" class="options" value="1" name="options">Image Only  -->
            <input type="radio" class="options" value="1" name="options">Image with text 
            <input type="radio" class="options" value="2" name="options">Text Only 
            <br>
            <span class="text-danger" id="option_error"><?php echo form_error('options');?></span>
        </div>

        <!-- Image Only -->
 <!--        <div class="form-group" style="display: none;" id="imageonlyopt"> 
          <div class="col-sm-6">
            <label>Option 1<span style="color: red;">*</span>:</label>
            <input type="file"  name="imageonly">
            <span class="text-danger"><?php// echo form_error('imageonly');?></span>
          <br>
            <label>Option 2<span style="color: red;">*</span>:</label>
            <input type="file"  name="imageonly">
            <span class="text-danger"><?php //echo form_error('imageonly');?></span>
          </div>

          <div class="col-sm-6">
            <label>Option 3<span style="color: red;">*</span>:</label> 
            <input type="file"  name="imageonly">
            <span class="text-danger"><?php //echo form_error('imageonly');?></span>
          <br>
            <label>Option 4<span style="color: red;">*</span>:</label>
            <input type="file"  name="imageonly">
            <span class="text-danger"><?php //echo form_error('imageonly');?></span>
          </div>

        </div> -->
      <!-- End Image Only  -->

      <!-- Image with text  -->
      <div class="form-group" style="display: none;" id="imagewtextopt">
        <div class="col-sm-6">
          <label>Option 1<span style="color: red;">*</span>:</label> 
            <input type="file" name="imagewith">
            <span class="text-danger"><?php echo form_error('imagewith');?></span>
          <br>
            <input type="text" class="form-control" name="textwith" placeholder="Enter Option 1">
            <span class="text-danger"><?php echo form_error('textwith');?></span>
          <br>
            <label>Option 2<span style="color: red;">*</span>:</label> 
            <input type="file" name="imagewith">
            <span class="text-danger"><?php echo form_error('imagewith');?></span>
          <br>
            <input type="text" class="form-control" name="textwith" placeholder="Enter Option 2">
            <span class="text-danger"><?php echo form_error('textwith');?></span>
        </div>

        <div class="col-sm-6">
          <label>Option 3<span style="color: red;">*</span>:</label> 
            <input type="file" name="imagewith">
            <span class="text-danger"><?php echo form_error('imagewith');?></span>
          <br>
            <input type="text" class="form-control" name="textwith" placeholder="Enter Option 3">
            <span class="text-danger"><?php echo form_error('textwith');?></span>
          <br>
            <label>Option 4<span style="color: red;">*</span>:</label> 
            <input type="file" name="imagewith">
            <span class="text-danger"><?php echo form_error('imagewith');?></span>
          <br>
            <input type="text" class="form-control" name="textwith" placeholder="Enter Option 4">
            <span class="text-danger"><?php echo form_error('textwith');?></span>
        </div>
        
      </div>
    <!-- End Image with text  -->
    <!-- Text Only -->
      <div class="form-group" style="display: none;" id="textonlyopt">
        <div class="col-md-6">  
          <label>Option 1<span style="color: red;">*</span>:</label> 
          <input type="text" class="form-control" name="textonly" placeholder="Enter Option 1">
          <span class="text-danger"><?php echo form_error('textonly');?></span>
          <br>
          <label>Option 2<span style="color: red;">*</span>:</label> 
          <input type="text" class="form-control" name="textonly" placeholder="Enter Option 2">
          <span class="text-danger"><?php echo form_error('textonly');?></span>
        </div>
        <div class="col-md-6">
          <label>Option 3<span style="color: red;">*</span>:</label> 
          <input type="text" class="form-control" name="textonly" placeholder="Enter Option 3">
          <span class="text-danger"><?php echo form_error('textonly');?></span>
          <br>
          <label>Option 4<span style="color: red;">*</span>:</label> 
          <input type="text" class="form-control" name="textonly" placeholder="Enter Option 4">
          <span class="text-danger"><?php echo form_error('textonly');?></span>
        </div>  
      </div>
      <!-- End Text Only -->
      <!-- End Options -->

<br>
       <div class="form-group"> 
        <!--value="<?php //echo set_value('unit'); ?>" -->
          <label for="exampleInputEmail1">Answer<span style="color: red;">*</span></label>
        <br>
            <!-- <input type="radio" class="answer"  value="1" name="answer">Image Only  -->
            <input type="radio" class="answer"  value="1" name="answer">Image with text 
            <input type="radio" class="answer"  value="2" name="answer">Text Only 
          <span class="text-danger" id="answer_error"><?php echo form_error('answer');?></span>
        </div>

        <!-- <div class="form-group" style="display: none;" id="imageonlyans"> 
            <input type="file"   name="imageonly">
          <span class="text-danger"><?php// echo form_error('imageonly');?></span>
        </div> -->

        <div class="form-group" style="display: none;" id="imagewtextans"> 
          <input type="file" name="imagewith">
          <br>
          <input type="text" class="form-control" name="textwith" placeholder="Enter Correct Answer">
          <span class="text-danger"><?php echo form_error('imagewith');?></span>
          <span class="text-danger"><?php echo form_error('textwith');?></span>
        </div>

        <div class="form-group" style="display: none;" id="textonlyans">
            <input type="text" class="form-control" name="textonly" placeholder="Enter Correct Answer">
          <span class="text-danger"><?php echo form_error('textonly');?></span>
        </div>


        <div  class="form-group" align="center">
            <input type="submit" class="btn btn-primary" id="add_slides"></input>
        </div>  
      </form>
    </div>
  </section>
</div>
<script src="<?php echo AUTH_ASSETS; ?>js/jquery3.3.1.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  window.base_url = '<?php echo base_url(); ?>'; 
  $(document).on('change', '#syllabus_name', function(){
    if($(this).val() != ''){
      $.ajax({
              dataType: 'JSON',
              url :base_url+'index.php/auth_panel/Unit/get_level',
              type :'POST',
              data : { syllabus_name : $(this).val() },
              success: function(data){
                  if(data.login){
                      $('#level_name').empty().append('<option value="0">Select Level</option>');
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
    }else{
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
              data : { level_name : $(this).val() },
              success: function(data){
                  if(data.login){
                    $('#sublevel_name').empty().append('<option value="0">Select Sub Level</option>');
                    $("#sublevel_name").append(data.data); 
                  }else{
                      alert(data.message);
                  }
              },
              error: function(jqXHR,textStatus,errorThrown){
                  alert('Some issue found please check and try again.');
              }
            });
    }else{
        $('#sublevel_name').empty().append('<option value="0">First Select Sub Level</option>');
    }
  });
//////////////////////////////////////////////////////////////////////////////////////////////////////
$(document).on('change', '#sublevel_name', function(){
    if($(this).val() != ''){
      $.ajax({
              dataType: 'JSON',
              url :base_url+'index.php/auth_panel/Slides/get_unit',
              type :'POST',
              data : { sublevel_name : $(this).val() },
              success: function(data){
                  if(data.login){
                    $('#unit_name').empty().append('<option value="0">Select Unit</option>');
                    $("#unit_name").append(data.data);
                  }else{
                      alert(data.message);
                  }
              },
              error: function(jqXHR,textStatus,errorThrown){
                  alert('Some issue found please check and try again.');
              }
            });
    }else{
         $('#unit_name').empty().append('<option value="0">First Select Sub Level</option>');
    }
  });
////////////////////////////////////////////////////////////////////////////////////////////////////
$(document).on('change', '.question', function(){
    $('#imageonly').css({"display":"none"});
    $('#imagewtext').css({"display":"none"});
    $('#textonly').css({"display":"none"});
      if($(this).val() == '1'){
        $('#imageonly').css({"display":"block"});
      } else if($(this).val() == '2'){
        $('#imagewtext').css({"display":"block"});
      } else if($(this).val() == '3'){
        $('#textonly').css({"display":"block"});
      }
  });
////////////////////////////////////////////////////////////////////////////////////////////////////
$(document).on('change', '.options', function(){
    $('#imageonlyopt').css({"display":"none"});
    $('#imagewtextopt').css({"display":"none"});
    $('#textonlyopt').css({"display":"none"});
      if($(this).val() == '1'){
        $('#imageonlyopt').css({"display":"block"});
      } else if($(this).val() == '2'){
        $('#imagewtextopt').css({"display":"block"});
      } else if($(this).val() == '3'){
        $('#textonlyopt').css({"display":"block"});
      }
  });
////////////////////////////////////////////////////////////////////////////////////////////////////
$(document).on('change', '.answer', function(){
    $('#imageonlyans').css({"display":"none"});
    $('#imagewtextans').css({"display":"none"});
    $('#textonlyans').css({"display":"none"});
      if($(this).val() == '1'){
        $('#imageonlyans').css({"display":"block"});
      } else if($(this).val() == '2'){
        $('#imagewtextans').css({"display":"block"});
      } else if($(this).val() == '3'){
        $('#textonlyans').css({"display":"block"});
      }
  });
////////////////////////////////////////////////////////////////////////////////////////////////////
$("#add_slides").bind("click", function () { 

    $('#syllabus_error').html('');  
    $('#level_error').html('');
    $('#sublevel_error').html('');
    $('#unit_error').html('');
    $('#question_error').html('');
    $('#imageOnlyQuestionError').html('');
    $('#imageWithQuestionError').html('');
    $('#textWithQuestionError').html('');
    $('#textOnlyQuestionError').html('');

      // syllabus========================= 
      if($('#syllabus_name').val() == ""){
        $('#syllabus_error').html('Select Syllabus').css({"color":"red"});
      }
      //================================
      //level===========================
      if($('#level_name').val() == "0"){
        $('#level_error').html('Select Level').css({"color":"red"});
      } 
      //===================================
      // sub level=========================
      if($('#sublevel_name').val() == "0"){
        $('#sublevel_error').html('Select Sub Level').css({"color":"red"});
      }
      //===============================
      // unit==========================
      if($('#unit_name').val() == '0'){
        $('#unit_error').html('Select Unit').css({"color":"red"});
      }
      //=========================================
      // question================================
      if($('.question').is(':checked') == false){
        $('#question_error').html('Choose question type').css({"color":"red"});
      } else{
            //======================================
            // image only===========================
            if($('.question:checked').val() == '1'){
                if($('#imageOnlyQuestion').val() ==''){
                  $('#imageOnlyQuestionError').html('Choose Question Image').css({"color":"red"});
                }
            }
            //======================================
            // image with text======================
            if($('.question:checked').val() == '2'){
                if($('#imageWithQuestion').val() =='' && $('#textWithQuestion').val() ==''){
                    $('#imageWithQuestionError').html('Choose Question Image').css({"color":"red"});
                    $('#textWithQuestionError').html('Enter Question?').css({"color":"red"});
                } else if($('#imageWithQuestion').val() ==''){
                    $('#imageWithQuestionError').html('Choose Question Image').css({"color":"red"});
                } else if($('#textWithQuestion').val() ==''){
                    $('#textWithQuestionError').html('Enter Question?').css({"color":"red"});
                }
            }
            //======================================
            // text only============================
            if($('.question:checked').val() == '3'){
                if($('#textOnlyQuestion').val() ==''){
                  $('#textOnlyQuestionError').html('Enter Question?').css({"color":"red"});
                } 
            }
        }
        //========================================
        // options================================
        if($('.options').is(':checked') == false){
        $('#option_error').html('Choose option type').css({"color":"red"});
        return false;
        } else{
              if($('.options:checked').val() == '1'){
                  if($('#imageOnlyQuestion').val() ==''){
                    $('#imageOnlyQuestionError').html('Choose Question Image').css({"color":"red"});
                  }
                return false;  
              }
              if($('.options:checked').val() == '2'){
                  if($('#imageWithQuestion').val() =='' && $('#textWithQuestion').val() ==''){
                      $('#imageWithQuestionError').html('Choose Question Image').css({"color":"red"});
                      $('#textWithQuestionError').html('Enter Question?').css({"color":"red"});
                  } else if($('#imageWithQuestion').val() ==''){
                      $('#imageWithQuestionError').html('Choose Question Image').css({"color":"red"});
                  } else if($('#textWithQuestion').val() ==''){
                      $('#textWithQuestionError').html('Enter Question?').css({"color":"red"});
                  }
                return false;
              }
              if($('.options:checked').val() == '3'){
                  if($('#textOnlyQuestion').val() ==''){
                    $('#textOnlyQuestionError').html('Enter Question?').css({"color":"red"});
                  }
                return false;
              }
          }
        //========================================
        // options================================
        if($('.answer').is(':checked') == false){
        $('#answer_error').html('Choose answer type').css({"color":"red"});
        return false;
        } else{
              if($('.answer:checked').val() == '1'){
                  if($('#imageOnlyAnswer').val() ==''){
                    $('#imageOnlyAnswerError').html('Choose Answer Image').css({"color":"red"});
                  }
                return false;  
              }
              if($('.answer:checked').val() == '2'){
                  if($('#imageWithAnswer').val() =='' && $('#textWithAnswer').val() ==''){
                      $('#imageWithAnswerError').html('Choose Answer Image').css({"color":"red"});
                      $('#textWithAnswerError').html('Enter Answer').css({"color":"red"});
                  } else if($('#imageWithAnswer').val() ==''){
                      $('#imageWithAnswerError').html('Choose Answer Image').css({"color":"red"});
                  } else if($('#textWithAnswer').val() ==''){
                      $('#textWithAnswerError').html('Enter Answer').css({"color":"red"});                  
                  }
                return false;
              }
              if($('.answer:checked').val() == '3'){
                  if($('#textOnlyAnswer').val() ==''){
                    $('#textOnlyAnswerError').html('Enter Answer').css({"color":"red"});
                  }
                return false;
              }
          }
  });
      
////////////////////////////////////////////////////////////////////////////////////////////////////
});
</script>




