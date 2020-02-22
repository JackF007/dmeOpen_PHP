$(document).ready(function() {
  window.base_url = base; 
//////////////////////////Get Level Drop Down////////////////////////////////////////////////
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
/////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////Get Sub Level Drop Down////////////////////////////////////////////
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

//////////////////////////Get Unit Drop Down////////////////////////////////////////////
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
   
    $('#imagewtext').css({"display":"none"});
    $('#textonly').css({"display":"none"});
      if($(this).val() == '1'){
       $('#imagewtext').css({"display":"block"});
      } else if($(this).val() == '2'){
         $('#textonly').css({"display":"block"});
      } 
  });

////////////////////////////////////////////////////////////////////////////////////////////////////

$(document).on('change', '.options', function(){

    $('#imagewtextopt').css({"display":"none"});
    $('#textonlyopt').css({"display":"none"});
      if($(this).val() == '1'){
         $('#imagewtextopt').css({"display":"block"});
      } else if($(this).val() == '2'){
          $('#textonlyopt').css({"display":"block"});
      } 
  });

////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////// All Fields Required Validation ////////////////////////////////
$("#add_slides").bind("click", function () { 
  var flag = 0;
    $('#syllabus_error').html('');  
    $('#level_error').html('');
    $('#sublevel_error').html('');
    $('#unit_error').html('');
    $('#question_error').html('');
    $('#imageWithQuestionError').html('');
    $('#textWithQuestionError').html('');
    $('#textOnlyQuestionError').html('');
    $('#option_error').html('');
    $('#AnswerError').html('');
    $('#imageWithOption1Error').html('');
    $('#imageWithOption2Error').html('');
    $('#imageWithOption3Error').html('');
    $('#imageWithOption4Error').html('');
    $('#textWithOption1Error').html('');
    $('#textWithOption2Error').html('');
    $('#textWithOption3Error').html('');
    $('#textWithOption4Error').html('');
    $('#textOnlyOption1Error').html('');
    $('#textOnlyOption2Error').html('');
    $('#textOnlyOption3Error').html('');
    $('#textOnlyOption4Error').html('');
      // syllabus========================= 
      if($('#syllabus_name').val() == ""){
        $('#syllabus_error').html('Select Syllabus');
        flag = 1;
      }
      //================================
      //level===========================
      if($('#level_name').val() == "0"){
        $('#level_error').html('Select Level');
        flag = 1;
      } 
      //===================================
      // sub level=========================
      if($('#sublevel_name').val() == "0"){
        $('#sublevel_error').html('Select Sub Level');
        flag = 1;
      }
      //===============================
      // unit==========================
      if($('#unit_name').val() == '0'){
        $('#unit_error').html('Select Unit');
        flag = 1;
      }
      //=========================================
      // question================================
      if($('.question').is(':checked') == false){
        $('#question_error').html('Choose question type');
        flag = 1;
      } else{
            //======================================
            // image with text======================
            if($('.question:checked').val() == '1'){
                if($('#imageWithQuestion').val() =='' && $('#textWithQuestion').val() ==''){
                    $('#imageWithQuestionError').html('Choose Question Image');
                    $('#textWithQuestionError').html('Enter Question?');
                    flag = 1;
                } else if($('#imageWithQuestion').val() ==''){
                    $('#imageWithQuestionError').html('Choose Question Image');
                    flag = 1;
                } else if($('#textWithQuestion').val() ==''){
                    $('#textWithQuestionError').html('Enter Question?');
                    flag = 1;
                }
            }
            //======================================
            // text only============================
            if($('.question:checked').val() == '2'){
                if($('#textOnlyQuestion').val() ==''){
                  $('#textOnlyQuestionError').html('Enter Question?');
                  flag = 1;
                } 
            }
        }
        //========================================
        // options================================
        if($('.options').is(':checked') == false){
        $('#option_error').html('Choose option type');
        flag = 1;
        } else{

              if($('.options:checked').val() == '1'){
                  if($('#imageWithOption1').val() =='' && $('#imageWithOption2').val() =='' && $('#imageWithOption3').val() =='' && $('#imageWithOption4').val() =='' && $('#textWithOption1').val() =='' && $('#textWithOption2').val() =='' && $('#textWithOption3').val() =='' && $('#textWithOption4').val() ==''){
                      $('#imageWithOption1Error').html('Choose Option 1 file');
                      $('#textWithOption1Error').html('Enter Option 1');
                      $('#imageWithOption2Error').html('Choose Option 2 file');
                      $('#textWithOption2Error').html('Enter Option 2');
                      $('#imageWithOption3Error').html('Choose Option 3 file');
                      $('#textWithOption3Error').html('Enter Option 3');
                      $('#imageWithOption4Error').html('Choose Option 4 file');
                      $('#textWithOption4Error').html('Enter Option 4');
                      flag = 1;
                  } 

                  if($('#imageWithOption1').val() =='' && $('#textWithOption1').val() ==''){
                      $('#imageWithOption1Error').html('Choose Option 1 file');
                      $('#textWithOption1Error').html('Enter Option 1');
                      flag = 1;
                  } else if($('#imageWithOption1').val() =='' ){
                      $('#imageWithOption1Error').html('Choose Option 1 file');
                      flag = 1;
                  } else if($('#textWithOption1').val() ==''){
                      $('#textWithOption1Error').html('Enter Option 1');
                      flag = 1;
                  }

                  if($('#imageWithOption2').val() =='' && $('#textWithOption2').val() ==''){
                      $('#imageWithOption2Error').html('Choose Option 2 file');
                      $('#textWithOption2Error').html('Enter Option 2');
                      flag = 1;
                  } else if($('#imageWithOption2').val() =='' ){
                      $('#imageWithOption2Error').html('Choose Option 2 file');
                      flag = 1;
                  } else if($('#textWithOption2').val() ==''){
                      $('#textWithOption2Error').html('Enter Option 2');
                      flag = 1;
                  }

                  if($('#imageWithOption3').val() =='' && $('#textWithOption3').val() ==''){
                      $('#imageWithOption3Error').html('Choose Option 3 file');
                      $('#textWithOption3Error').html('Enter Option 3');
                      flag = 1;
                  } else if($('#imageWithOption3').val() =='' ){
                      $('#imageWithOption3Error').html('Choose Option 3 file');
                      flag = 1;
                  } else if($('#textWithOption3').val() ==''){
                      $('#textWithOption3Error').html('Enter Option 3');
                      flag = 1;
                  }

                  if($('#imageWithOption4').val() =='' && $('#textWithOption4').val() ==''){
                      $('#imageWithOption4Error').html('Choose Option 4 file');
                      $('#textWithOption4Error').html('Enter Option 4');
                      flag = 1;
                  } else if($('#imageWithOption4').val() =='' ){
                      $('#imageWithOption4Error').html('Choose Option 4 file');
                      flag = 1;
                  } else if($('#textWithOption4').val() ==''){
                      $('#textWithOption4Error').html('Enter Option 4');
                      flag = 1;
                  }
              }
              if($('.options:checked').val() == '2'){
                  if($('#textOnlyOption1').val() =='' && $('#textOnlyOption2').val() =='' && $('#textOnlyOption3').val() =='' && $('#textOnlyOption4').val() ==''){
                    $('#textOnlyOption1Error').html('Enter Option 1');
                    $('#textOnlyOption2Error').html('Enter Option 2');
                    $('#textOnlyOption3Error').html('Enter Option 3');
                    $('#textOnlyOption4Error').html('Enter Option 4');
                    flag = 1;
                  }
                  
                  if($('#textOnlyOption1').val() =='' ){
                      $('#textOnlyOption1Error').html('Enter Option 1');
                      flag = 1;
                  }
                  if($('#textOnlyOption2').val() =='' ){
                      $('#textOnlyOption2Error').html('Enter Option 2');
                      flag = 1;
                  } 
                  if($('#textOnlyOption3').val() =='' ){
                      $('#textOnlyOption3Error').html('Enter Option 3');
                      flag = 1;
                  } 
                  if($('#textOnlyOption4').val() ==''){
                      $('#textOnlyOption4Error').html('Enter Option 4');
                      flag = 1;
                  }
              }
          }
        //========================================
        // answer================================
        if($('#Answer').val() == ""){
        $('#AnswerError').html('Enter Answer');
        flag = 1;
        } 

        if(flag == 0 &&  $('#imageWithQuestionError').html('') && $('#imageWithOption1Error').html('') && $('#imageWithOption2Error').html('') && $('#imageWithOption3Error').html('') && $('#imageWithOption3Error').html('')){
          return true;
        } else if(flag == 1){
          return false;
        }

  });
      
////////////////////////////////////////////////////////////////////////////////////////////////////
});

function readURL(input, type) {

   $('#imageWith'+type+'Error').html('');
    var url = input.value;
    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
      if(input.files && input.files[0] && (ext == "png" || ext == "jpeg" || ext == "jpg" || ext == "gif")) {
         if(input.files[0].size < 2097152){
              $('.upload_img'+type).css({"display":"block"});
              var reader = new FileReader();
              reader.onload = function (e) {
                $('#profile-img-tag'+type).attr('src', e.target.result);
              }
              reader.readAsDataURL(input.files[0]);
        } else{
            $('#imageWith'+type+'Error').html('File size is larger than 2MB!');
            $('#imageWith'+type).val('');
            $('.upload_img'+type).css({"display":"none"});
        }
      } else{
           $('#imageWith'+type+'Error').html('Allowed only PNG, JPG, JPEG, GIF');
            $('#imageWith'+type).val('');
            $('.upload_img'+type).css({"display":"none"});
      }
  }