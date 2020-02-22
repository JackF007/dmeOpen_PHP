
<?php //echo "<pre>";print_r($user_data);die; ?>

<div class="col-md-12"><a href="javascript:history.go(-1)"><button class="pull-right btn btn-info btn-xs bold">Back to user list</button></a></div><br><br>

<aside class="profile-nav col-lg-3">
      <section class="panel">
          <div class="user-heading round">
              <a href="#">
                  <img alt="" src="<?php echo $user_data['profile_image']; ?>">
              </a>
              <h1><?php echo $user_data['username']; ?></h1>
              <p><?php echo $user_data['email']; ?></p>
          </div>

          <ul class="nav nav-pills nav-stacked">
              <li class="active"><a href="#"> <i class="fa fa-user"></i> Profile</a></li>
              <!-- <li><a href="<?php echo AUTH_PANEL_URL.'fan_wall/all_post?search_user_post_id='.$user_data['id']; ?>"> <i class="fa fa-calendar"></i> Post<span class="label label-danger pull-right"><?php echo $user_data['post_count']; ?></span></a></li>
              <li><a href="#"> <i class="fa fa-calendar"></i> Followers <span class="label label-danger pull-right"><?php echo $user_data['followers_count']; ?></span></a></li>
              <li><a href="#"> <i class="fa fa-calendar"></i> Following <span class="label label-danger pull-right"><?php echo $user_data['following_count']; ?></span></a></li> -->
          </ul>

      </section>

      <!--moderator form -->
  </aside>
  <aside class="profile-info col-lg-9">
      
      <section class="panel">
          <div class="panel-body bio-graph-info">
              <h1>Bio Graph</h1>
              <div class="row">
                  <div class="bio-row">
                      <p><span>Sr.No </span>:- <?php echo $user_data['id']; ?></p>
                  </div>
                  <div class="bio-row">
                      <p><span>Name </span>:- <?php echo $user_data['name']; ?></p>
                  </div>
                  <div class="bio-row">
                      <p><span>Email </span>:- <?php echo $user_data['email']; ?></p>
                  </div>
                  <div class="bio-row">
                      <p><span>Mobile</span>:- <?php echo $user_data['mobile']; ?></p>
                  </div>
                  <!--<div class="bio-row">
                      <p><span>Status</span>: N/A</p>
                  </div>-->
                  <div class="bio-row">
                      <p><span>Date/Time of Registration </span>: <?php echo date("d-m-Y H:i:s", $user_data['created']) ?></p>
                  </div>
                  <div class="bio-row">
                      <p><span>Date/Time of Last Login </span>: <?php echo date("d-m-Y H:i:s", $user_data['modified']) ?></p>
                  </div>
                  <!--<div class="bio-row">
                      <p><span>DAMS User/Non DAMS User</span>: N/A</p>
                  </div>-->
                  <!--<div class="bio-row">
                      <p><span>User can be Active/Deactive</span>: N/A</p>
                  </div>-->
                  <!--<div class="bio-row">
                      <p><span>Location</span>: N/A</p>
                  </div>-->
                  <!--<div class="bio-row">
                      <p><span>Ip Address</span>: N/A</p>
                  </div>-->
              </div>
               
            <div class="col-md-8 pull-right">

              <?php if($user_data['status'] != '2') { ?>
                <div class="col-md-3 pull-right">
                    <a href="<?php echo AUTH_PANEL_URL . 'mobile_users/delete_user/delete/'.$user_data['id']; ?>" onclick="return confirm('Are you sure to delete this user');"><button class="pull-right btn btn-danger btn-xs bold">Delete User</button></a>
                </div>
                <div class="col-md-3 pull-right">
                <?php if($user_data['status'] == '1') { ?>
                    <a href="<?php echo AUTH_PANEL_URL . 'mobile_users/enable_user/enable/'.$user_data['id']; ?>"><button class="pull-right btn btn-warning btn-xs bold">Enable login</button></a>
                <?php } else { ?>
                    <a href="<?php echo AUTH_PANEL_URL . 'mobile_users/disable_user/disable/'.$user_data['id']; ?>" onclick="return confirm('Are you sure to disable this user');"><button class="pull-right btn btn-warning btn-xs bold">Disable login</button></a>
                <?php } ?>
                </div>
                <?php } else { ?>
                <div class="col-md-3 pull-right">
                    <a href="#"><button class="pull-right btn btn-info btn-xs bold">User Deleted</button></a>
                </div>
              <?php } ?>

                <!-- <div class="col-md-3 pull-right">
                  <a href="<?php echo AUTH_PANEL_URL . 'bulk_messenger/bulk_message/send_bulk_message?M='.base64_encode($user_data['mobile']); ?>"><button class="pull-right btn btn-success btn-xs bold">Send Sms</button></a>
              </div> -->
              
              <!-- <div class="col-md-3 pull-right">
                  <a href="<?php echo AUTH_PANEL_URL . 'bulk_messenger/push_notification/send_push_notification?q='.base64_encode(json_encode(array('id'=>$user_data['id'],'name'=>$user_data['name'],'device_type'=>$user_data['device_type'],'device_tokken'=>$user_data['device_tokken']))); ?>"><button class="pull-right btn btn-info btn-xs bold">Push Notification</button></a>
              </div> -->


            </div>
          </div>		  
      </section>
	  
	  
</aside>
<?php 
$is_instructor = 0;
if(isset($user_instructor_data)){
	$is_instructor = 1; }

	
$instructor_id = $user_data['id'];
$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
                <script src="https://cdn.ckeditor.com/4.7.3/standard/ckeditor.js"></script>
				<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              	<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
                <script type="text/javascript" language="javascript" > 
					var is_instructor = '$is_instructor';
					if(is_instructor == 1){
						jQuery(document).ready(function() {
						   var table = 'all-instructor-reviews-grid';
						   var dataTable = jQuery("#"+table).DataTable( {
							   "processing": true,
								"pageLength": 15,
								"lengthMenu": [[15, 25, 50], [15, 25, 50]],
							   "serverSide": true,
							   "order": [[ 4, "desc" ]],
							   "ajax":{
								   url :"$adminurl"+"web_user/ajax_instructor_ratings_list/$instructor_id", // json datasource
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
				   }
				    
               </script>              

EOD;
echo modules::run('auth_panel/template/add_custum_js',$custum_js );





