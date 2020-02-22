<?php //echo "<pre>";print_r($user_data);die; ?>

    <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading">
      EDIT UNIT
    <span class="tools pull-right">
    <a href="javascript:;" class="fa fa-chevron-down"></a>
    <!--<a href="javascript:;" class="fa fa-times"></a>-->
    </span>
    </header>
          <div class="panel-body">
               <form method="post"> 
           <input type="hidden" name="unit_id" value="<?php echo $unit_data['id']; ?>">
           <input type="hidden" name="syllabus_name" value="<?php echo $unit_data['syllabus_id']; ?>">
           <input type="hidden" name="level_name" value="<?php echo $unit_data['level_id']; ?>">
           <input type="hidden" name="sublevel_name" value="<?php echo $unit_data['sub_level_id']; ?>">
        <div class="form-group">
          <label for="exampleInputEmail1">Syllabus</label>
            <input type="text"  class="form-control" name="syllabus_name" id="syllabus_name" value="<?php echo $syllabusName[$unit_data['syllabus_id']]; ?>">
            <span class="text-danger"></span>
        </div>



        <div class="form-group">
          <label for="exampleInputEmail1">Level</label>
            <input type="text" class="form-control" name="level_name" id="level_name" value="<?php echo $levelName[$unit_data['level_id']]; ?>">
            <span class="text-danger"></span>
        </div>



        <div class="form-group">
          <label for="exampleInputEmail1">Sub Level</label>
            <input type="text" class="form-control" name="sublevel_name" id="sublevel_name" value="<?php echo $sublevelName[$unit_data['sub_level_id']]; ?>">
            <span class="text-danger"><?php echo form_error('sublevel_name');?></span>
        </div>



       <div class="form-group">  <!--value="<?php //echo set_value('unit'); ?>" -->
          <label for="exampleInputEmail1">Unit Name</label>
            <input type="text" class="form-control" id="unit" name="unit"  value="<?php echo $unit_data['unit_name']; ?>" placeholder="Enter Unit Name">
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
    $("#syllabus_name").attr("disabled","disabled");
    $("#level_name").attr("disabled","disabled");
    $("#sublevel_name").attr("disabled","disabled");
};
</script>