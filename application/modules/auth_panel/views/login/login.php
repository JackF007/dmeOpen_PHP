<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>demOpen LOGIN</title>

    <!-- Bootstrap core CSS auth_panel_assets -->
    <link href="<?php echo base_url();?>auth_panel_assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>auth_panel_assets/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="<?php echo base_url();?>auth_panel_assets/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url();?>auth_panel_assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url();?>auth_panel_assets/css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="<?php echo base_url();?>auth_panel_assets/js/html5shiv.js"></script>
    <script src="<?php echo base_url();?>auth_panel_assets/js/respond.min.js"></script>
    <![endif]-->
    </style>
</head>

  <body class="login-body">

    <div class="container">
      <form class="form-signin" method="POST" action="<?php echo site_url('auth_panel/login/index');?>">
          <h2 class="form-signin-heading">Sign in to <span style="text-transform: none">demOpen</span></h2>
        <div class="login-wrap">
            <input type="text" class="form-control" name="email" placeholder="Enter Email" id="login_username" value="<?php echo set_value('email');?>">
            <span class="error bold"><?php echo form_error('email');?></span>
            <input type="password" class="form-control" name="password" placeholder="Password" id="login_pwd" >
             <span class="error bold"><?php echo form_error('password');?></span>
            <label class="checkbox">
                <!--<input type="checkbox" value="remember-me"> Remember me-->
                <span class="pull-right">
                    <a data-toggle="modal" href="#myModal"> Forgot Password?</a>

                </span>
            </label>
            <button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>
        </div>

      </form>

    </div>

<!-- ################### Forget password of admin pop up  model ################################-->

   <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                    <h4 class="modal-title">Forgot Password ?</h4>
                </div>
            <div class="modal-body">
                    <span id="validate_message"></span>
                    <p>Enter your e-mail address below to reset your password.</p>
                    <input type="text" class="form-control placeholder-no-fix" autocomplete="off" placeholder="Email" name="email" id="email">

                    <div id="change_password" class="hide">

                    </div>

            </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-success submit_form">Submit</button>
                </div>
            </div>
        </div>
    </div>


    <!-- js placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url();?>auth_panel_assets/js/jquery.js"></script>
    <script src="<?php echo base_url();?>auth_panel_assets/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.submit_form').click(function() {
                var post_type = $('#post_type').val();
                var data = '';
                if(post_type == 'change_pwd') {
                     data ={'email':$('#email').val(),'tokken':$('#tokken').val(),'new_pwd':$('#new_pwd').val(),'cnf_pwd':$('#cnf_pwd').val(),'post_type':$('#post_type').val()};
                     
                } else {
                    data ={'email':$('#email').val()};
                }
                
                jQuery.ajax({
                        url: '<?php echo base_url('index.php/auth_panel/login/forget_password'); ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: data,
                        success: function (data) {
                            if(data.status) { 
                                if(data.post_type == '') { 
                                $('#validate_message').css('color','green');
                                $('#validate_message').text(data.message);
                                $('#change_password').removeClass('hide');
                                $('#change_password').html('<p>Enter tokken here</p><input autocomplete=off class="form-control placeholder-no-fix"id=tokken name=tokken placeholder="Enter Tokken"><p>Enter new password</p><input autocomplete=off class="form-control placeholder-no-fix"id=new_pwd name=new_pwd placeholder="Enter New Password" type="password"><p>Enter confirm password</p><input autocomplete=off class="form-control placeholder-no-fix"id=cnf_pwd name=cnf_pwd type="password" placeholder="Enter Confirm Password"> <input autocomplete=off class="form-control placeholder-no-fix"id=post_type name=post_type placeholder=""type=hidden value=change_pwd>');
                                } else {
                                    $('#validate_message').css('color','green');
                                    $('#validate_message').html(data.message);
                                    $('#myModal input').val('');
                                }
                            } else {
                                $('#validate_message').css('color','red');
                                $('#validate_message').text(data.message);
                            }
                           
                        }
                    });
            })
        })
    </script>


  </body>
</html>
