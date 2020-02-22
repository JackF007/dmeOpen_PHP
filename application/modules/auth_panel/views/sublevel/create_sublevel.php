
<div class="col-lg-12">
  <section class="panel">
    <header class="panel-heading">
      CREATE SUB LEVEL
    <span class="tools pull-right">
    <a href="javascript:;" class="fa fa-chevron-down"></a>
    <!--<a href="javascript:;" class="fa fa-times"></a>-->
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
                    <?php foreach ($get_syllabus as $syllabus) 
                    { ?>
                      <option value="<?= $syllabus['id']; ?>" <?php if($syllabus['id']==$category1){?> selected <?php }?> ><?= $syllabus['syllabus_name']; ?></option>
                     
                      <?php } ?>
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
                    <?php 
                    foreach ($level as $levels)
                    {
                      
                      ?>
                      <option value="<?= $levels['id']; ?>" <?php if($levels['id']==$category2){?> selected <?php }?> ><?= $levels['level_name']; ?></option>
                     
                      <?php }  ?>
                  </select>
                <span class="text-danger"><?php echo form_error('level_name');?></span>
            </div>

             <div class="form-group">
                <label for="exampleInputEmail1">Sub Level</label>
                <input type="text" class="form-control" id="sublevel" name="sublevel" placeholder="First Select Level" value="<?php if(!empty($sublevel)){ echo $sublevel;}else{ echo "";} ?>" readonly="readonly">
                <span class="text-danger"><?php echo form_error('sublevel');?></span>
            </div>



            <div  class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
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
              url :base_url+'index.php/auth_panel/Sublevel/get_level',
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
    }
  });
//////////////////////////////////////////////////////////////////////////////////////
    $(document).on('change', '#level_name', function(){
    if($(this).val() != ''){
      $.ajax({
              dataType: 'JSON',
              url :base_url+'index.php/auth_panel/Sublevel/get_sublevel',
              type :'POST',
              data : {
                  level_name : $(this).val()
              },
              
              success: function(data){
                  if(data.login){
                      $('#sublevel').val(data.data);
                     
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
        $('#sublevel').val('');
    }
  });
//////////////////////////////////////////////////////////////////////////////////////////////////////
});
</script>




