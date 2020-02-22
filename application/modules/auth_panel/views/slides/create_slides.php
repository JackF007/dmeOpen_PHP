
<div class="col-lg-12">
  <section class="panel">
    <header class="panel-heading">
      CREATE SLIDES
      <span class="tools pull-right">
      <a href="javascript:;" class="fa fa-chevron-down"></a>
      </span>
    </header>
    <div class="panel-body">
      <form method="post" enctype='multipart/form-data'>

<!-- Syllabus--------------------------------------------------------------------- -->
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
<!-- End Syllabus------------------------------------------------------------------ -->


<!-- Level------------------------------------------------------------------------- -->
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
<!-- End Level----------------------------------------------------------------------- -->


<!-- Sub Level----------------------------------------------------------------------- -->
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
<!-- End Sub Level------------------------------------------------------------------- -->


<!-- Unit---------------------------------------------------------------------------- -->
       <div class="form-group">
          <label for="exampleInputEmail1">Unit<span style="color: red;">*</span></label>
          <?php if(set_value('unit_name') != ''){ $category4=set_value('unit_name');}
                      else{ $category4='';} 
          ?>
            <select class="form-control" name="unit_name" id="unit_name">
              <option value="0">First Select Sub Level</option>
                <?php if(!empty($unit)){
                  $i=1;
                    foreach ($unit as $units) { ?>
              <option value="<?= $units['id']; ?>" <?php if($units['id']==$category4){?> selected <?php }?> ><?= "Unit-".$i."-".$units['unit_name']; ?></option><?php $i++; } } ?>
            </select>
            <span class="text-danger" id="unit_error"><?php echo form_error('unit_name');?></span>
        </div>
<!-- End Unit----------------------------------------------------------------------- -->



<!-- Question------------------------------------------------------------------------ -->
        <div class="form-group"> 
          <label for="exampleInputEmail1">Question<span style="color: red;">*</span></label>
          <br>
          <input type="radio" class="question"  value="1" name="question">Image with text 
          <input type="radio" class="question"  value="2" name="question">Text Only 
          <br>
          <span class="text-danger" id="question_error"><?php echo form_error('question');?></span>
        </div>

         <!-- Image with text--------------------------------------------------------  -->
        <div class="form-group" style="display: none;" id="imagewtext"> 
          <div class="col-md-3">
          <input type="file" id="imageWithQuestion" name="imageWithQuestion" onchange="return readURL(this, 'Question','')">
          <span class="text-danger" id="imageWithQuestionError"><?php echo form_error('imageWithQuestion');?></span><br>
          </div>
          <div class="col-md-3">
          <figure class="upload_imgQuestion" style="display: none;">
          <img src="" id="profile-img-tagQuestion" width="70px" height="70px"/></figure><br>
        </div>
          <br>
          <input type="text" class="form-control" id="textWithQuestion" name="textWithQuestion" placeholder="Enter Question?">
          <span class="text-danger" id="textWithQuestionError"><?php echo form_error('textWithQuestion');?></span>
        </div>
        <!-- End Image with text-----------------------------------------------------  -->
        <!-- Text Only---------------------------------------------------------------- -->
        <div class="form-group" style="display: none;" id="textonly">
          <input type="text" class="form-control" id="textOnlyQuestion" name="textOnlyQuestion" placeholder="Enter Question?">
          <span class="text-danger" id="textOnlyQuestionError"><?php echo form_error('textOnlyQuestion');?></span>
        </div>
        <!-- End Text Only------------------------------------------------------------ -->
<!-- End Question--------------------------------------------------------------------- -->



<!-- Options-------------------------------------------------------------------------- -->
        <br>
        <div class="form-group"> 
            <label for="exampleInputEmail1">Options<span style="color: red;">*</span></label>
            <br>
            <input type="radio" class="options" value="1" name="options">Image with text 
            <input type="radio" class="options" value="2" name="options">Text Only 
            <br>
            <span class="text-danger" id="option_error"><?php echo form_error('options');?></span>
        </div>


      <!-- Image with text-------------------------------------------------------------  -->
      <div class="form-group" style="display: none;" id="imagewtextopt">
        <div class="col-sm-6">
          <!-- Option 1------------------------------------------------------------------ -->
          <label>Option 1<span style="color: red;">*</span>:</label> 
          <div class="row">
              <div class="col-md-6">
              <input type="file" name="imageWithOption1" id="imageWithOption1" onchange="return readURL(this, 'Option1')">
              <span class="text-danger" id="imageWithOption1Error"><?php echo form_error('imageWithOption1');?></span>
            </div>
            <div class="col-md-6">
             <figure class="upload_imgOption1" style="display: none;">
          <img src="" id="profile-img-tagOption1" width="70px" height="70px"/></figure><br>
            </div>
          </div>
           <br>
            <input type="text" class="form-control" name="textWithOption1"  id="textWithOption1" placeholder="Enter Option 1">
            <span class="text-danger" id="textWithOption1Error"><?php echo form_error('textWithOption1');?></span>
          <!-- End Option 1------------------------------------------------------------ -->
            <br>
          <!-- Option 2---------------------------------------------------------------- -->
          <label>Option 2<span style="color: red;">*</span>:</label> 
            <div class="row">
              <div class="col-md-6">
              <input type="file" name="imageWithOption2" id="imageWithOption2" onchange="return readURL(this, 'Option2')">
              <span class="text-danger" id="imageWithOption2Error"><?php echo form_error('imageWithOption2');?></span>
            </div>
            <div class="col-md-6">
             <figure class="upload_imgOption2" style="display: none;">
          <img src="" id="profile-img-tagOption2" width="70px" height="70px"/></figure><br>
            </div>
          </div>
            <br>
            <input type="text" class="form-control" name="textWithOption2" id="textWithOption2" placeholder="Enter Option 2">
            <span class="text-danger" id="textWithOption2Error"><?php echo form_error('textWithOption2');?></span>
          <!-- End Option 2------------------------------------------------------------ -->
        </div>

        <div class="col-sm-6">
          <!-- Option 3---------------------------------------------------------------- -->
          <label>Option 3<span style="color: red;">*</span>:</label> 
            <div class="row">
              <div class="col-md-6">
              <input type="file" name="imageWithOption3" id="imageWithOption3" onchange="return readURL(this, 'Option3')">
              <span class="text-danger" id="imageWithOption3Error"><?php echo form_error('imageWithOption3');?></span>
            </div>
            <div class="col-md-6">
             <figure class="upload_imgOption3" style="display: none;">
          <img src="" id="profile-img-tagOption3" width="70px" height="70px"/></figure><br>
            </div>
          </div>
            <br>
            <input type="text" class="form-control" name="textWithOption3" id="textWithOption3" placeholder="Enter Option 3">
            <span class="text-danger" id="textWithOption3Error"><?php echo form_error('textWithOption3');?></span>
          <!-- End Option 3------------------------------------------------------------ -->
            <br>
          <!-- Option 4---------------------------------------------------------------- -->
          <label>Option 4<span style="color: red;">*</span>:</label> 
            <div class="row">
              <div class="col-md-6">
              <input type="file" name="imageWithOption4" id="imageWithOption4" onchange="return readURL(this, 'Option4')">
              <span class="text-danger" id="imageWithOption4Error"><?php echo form_error('imageWithOption4');?></span>
            </div>
            <div class="col-md-6">
             <figure class="upload_imgOption4" style="display: none;">
          <img src="" id="profile-img-tagOption4" width="70px" height="70px"/></figure><br>
            </div>
          </div>
            <br>
            <input type="text" class="form-control" name="textWithOption4" id="textWithOption4" placeholder="Enter Option 4">
            <span class="text-danger" id="textWithOption4Error"><?php echo form_error('textWithOption4');?></span>
          <!-- End Option 4------------------------------------------------------------ --> 
        </div>
        
      </div>
    <!-- End Image with text-------------------------------------------------------------  -->


    <!-- Text Only------------------------------------------------------------------------ -->
      <div class="form-group" style="display: none;" id="textonlyopt">
        <div class="col-md-6">
        <!-- Option 1---------------------------------------------------------------- -->
          <label>Option 1<span style="color: red;">*</span>:</label> 
          <input type="text" class="form-control" name="textOnlyOption1" id="textOnlyOption1" placeholder="Enter Option 1">
          <span class="text-danger" id="textOnlyOption1Error"><?php echo form_error('textOnlyOption1');?></span>
        <!-- End Option 1------------------------------------------------------------ -->
          <br>
        <!-- Option 2---------------------------------------------------------------- -->
          <label>Option 2<span style="color: red;">*</span>:</label> 
          <input type="text" class="form-control" name="textOnlyOption2" id="textOnlyOption2" placeholder="Enter Option 2">
          <span class="text-danger" id="textOnlyOption2Error"><?php echo form_error('textOnlyOption2');?></span>
        <!-- End Option 2------------------------------------------------------------ --> 
        </div>
        <div class="col-md-6">
        <!-- Option 3---------------------------------------------------------------- -->
          <label>Option 3<span style="color: red;">*</span>:</label> 
          <input type="text" class="form-control" name="textOnlyOption3" id="textOnlyOption3" placeholder="Enter Option 3">
          <span class="text-danger" id="textOnlyOption3Error"><?php echo form_error('textOnlyOption3');?></span>
        <!-- End Option 3------------------------------------------------------------ -->
          <br>
        <!-- Option 4---------------------------------------------------------------- -->
          <label>Option 4<span style="color: red;">*</span>:</label> 
          <input type="text" class="form-control" name="textOnlyOption4" id="textOnlyOption4" placeholder="Enter Option 4">
          <span class="text-danger" id="textOnlyOption4Error"><?php echo form_error('textOnlyOption4');?></span>
        <!-- End Option 4------------------------------------------------------------ -->
        </div>  
      </div>
      <!-- End Text Only------------------------------------------------------------- -->
<!-- End Options--------------------------------------------------------------------- -->
      <div class="clearfix"></div>
      <br>
<!-- Answer--------------------------------------------------------------------------- -->
      <div class="form-group"> 
          <label for="exampleInputEmail1">Answer<span style="color: red;">*</span></label>
        <br>
          <input type="text" class="form-control" name="Answer" id="Answer" placeholder="Enter Correct Answer">
          <span class="text-danger" id="AnswerError"><?php echo form_error('Answer');?></span>
        </div>
       
<!-- End Answer------------------------------------------------------------------------------- -->

        <div  class="form-group" align="center">
            <input type="submit" class="btn btn-primary" id="add_slides"></input>
        </div>  
      </form>
    </div>
  </section>
</div>
<script src="<?php echo AUTH_ASSETS; ?>js/jquery3.3.1.js"></script>
<script type="text/javascript"> var base = '<?php echo base_url(); ?>';</script>
<script src="<?php echo AUTH_ASSETS; ?>js/customJS.js"></script>




