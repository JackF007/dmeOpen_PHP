<style>
    .user_types{
        list-style:none;
    }
    .user_types li{
        display:inline-block;
    }

    .proceedbtn {
        background: #ff7a1b;
        color: white;
        border: none;
        width: -webkit-fill-available;
        font-size: 17px;
        padding: 15px;
        margin: 10px 6px;
    }
    /* The container */
    .container-radio {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 15px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        width: 20%;
        display: inline-block;
        font-weight: 400;
    }
    .optionfield
    {  width: 45% !important;
       font-weight: 400;
    }
    .container-radio-option-2{
        width: 28% !important;
        font-weight: 400;
    }

    /* Hide the browser's default radio button */
    .container-radio input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    /* Create a custom radio button */
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 28px;
        width: 28px;
        background-color: #fff;
        border-radius: 50%;
        border:1px solid #ccc;
    }

    /* On mouse-over, add a grey background color */
    .container-radio:hover input ~ .checkmark {
        background-color: #ccc;
    }

    /* When the radio button is checked, add a blue background */
    .container-radio input:checked ~ .checkmark {
        background-color: #fff;
        border:1px solid #1c6b94;
    }

    /* Create the indicator (the dot/circle - hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the indicator (dot/circle) when checked */
    .container-radio input:checked ~ .checkmark:after {
        display: block;
    }

    /* Style the indicator (dot/circle) */
    .container-radio .checkmark:after {
        top: 4.55px;
        left: 5.22px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #1c6b94;
    }
    .tabbable-panel-2 {
        border: none;
        padding: 10px;
    }
    .tabbable-panel-1 {
        border: none;
        padding: 10px;
    }
    .tabbable-panel-8 {
        border: none;
        padding: 10px;
    }
    .tabbable-panel-15{
        border: none;
        padding: 10px;   
        width: 100%;
    }
    .tabbable-panel-16{
        border: none;
        padding: 10px;   
        width: 100%;
    }
    .logout{
        background-color:#ccc;
        float:right !important;
        color: black;
        text-align: center;
        padding: 2px 16px !important;
        text-decoration: none;
        font-size: 15px !important;
        margin-left:5px;
    }
    #topbar .modal-content, #geo-modal .modal-content {
        height: 650px;
    }
</style>

<div class="top-header">
    <div class="container-fluid">	
        <div class="row">
            <div class="topnav">
                <div class="login-signup">
                    <?php if (isset($this->session->userdata['active_user_data']['id'])) { ?>

                        <img src="<?php echo base_url('web_assets/') ?>images/icons/black-male-user-symbol.png">
                        <a class="logout" href="<?php echo base_url('index.php/web_panel/Login/logout'); ?>">Log Out</a>
                    <?php } else { ?>

                        <button type="button" class="btn btn-sm btn-login" data-toggle="modal" data-target="#topbar">login/Signup</button>
                    <?php } ?>
                </div>
                <div class="search-top">
                    <div class="search-container">
                        <form action="/action_page.php">
                            <input type="text" placeholder="" name="search">
                            <button type="submit"><img src="<?php echo base_url('web_assets/') ?>images/homepage/search.png" class="search-icon" ></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>		
</div>

<!-- Modal -->
<div class="modal fade" id="topbar" role="dialog" style="z-index:999999999;">
    <div class="modal-dialog">
        <div class="modal-content container">
            <div class="modal-body row flex-container">
                <div class="col-xs-12 col-sm-6 col-md-6 flex-container flex-center-v no-mdis">
                    <ul class="offers">
                        <li><div class="offers-text">Fast & Secure Payments</div></li>
                        <li><div class="offers-text">Pay UtilityBills or Mobile Recharge & get cashbacks</div></li>
                        <li><div class="offers-text">Pay over 500 millions paytm merchant network</div></li>
                        <li><div class="offers-text">Amazing Deals on Travel, Movies and Shopping</div></li>
                    </ul>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 flex-container">
                    <div class="tabbable-panel">
                        <div class="tabbable-line">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <ul class="nav nav-tabs ">
                                <li class="active">
                                    <a href="#tab_default_1" data-toggle="tab">Sign In to FIT</a>
                                </li>
                                <li>
                                    <a href="#tab_default_2" data-toggle="tab">Sign up </a>
                                </li>
                            </ul>

                            <!-- login form -->  
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_default_1">
                                    <form  method="POST">
                                        <span id="error_validation"></span>
                                        <div class="form-group">
                                            <input class="sign-input-box" type="text" name="email" class="form-control" id="username" placeholder="Registered Mobile Number/ Email ID">
                                        </div>
                                        <div class="form-group">
                                            <input class="sign-input-box" name="password" type="password" class="form-control" id="password" placeholder="Enter Password">
                                        </div>
                                        <!--  <div class="checkbox">
                                            <label><input type="checkbox"> Remember me</label>
                                        </div> -->
                                        <p>
                                            <a href="#" id="forgotpassword">Forgot Password</a> 
<!--                                            <a href="#" id="forgotpassword">Forgot Password</a> |  <a href="#">Other Login Issues?</a>-->
                                        </p>
                                        <button type="button" id="login" class="btn proceedbtn">Login Securely</button>
                                        <p>
                                            By logging in, you agree to  <a href="#">terms and conditions</a> & <a href="#">Privacy Policy </a>
                                        </p>
                                        <center><a href="<?php echo site_url('web_panel/Home'); ?>" style="font-size:16px">Skip>></a></center>
                                    </form>
                                </div>
                                <div class="tab-pane" id="tab_default_2">
                                    <span id="error_validation_registration"></span>
                                    <ul class="user_types">
                                        <input type="hidden" name="select_user_value" id="select_user_value">
                                        <li class="active">
                                            <a data-value="1" href="#owner" id="owner" data-toggle="tab" class="btn btn-default select_user">OWNER</a>
                                        </li>
                                        <li>
                                            <a data-value="2" href="#professional" id="professional" data-toggle="tab"  class="btn btn-default select_user">PROFESSIONAL</a>
                                        </li>
                                        <li>
                                            <a data-value="0" href="#tab_default_6" data-toggle="tab" class="btn btn-default select_user">ENTHUSIAST</a>
                                        </li>
                                    </ul>
                                    <form method="POST">
                                        <div class="form-group">
                                            <input class="sign-input-box alphabet" type="text" class="form-control " name="first_name" id="first_name" placeholder="First Name" maxlength="16">
                                        </div>
                                        <div class="form-group">
                                            <input class="sign-input-box alphabet" type="text" class="form-control " name="last_name" id="last_name" placeholder="Last Name" maxlength="20">
                                        </div>
                                        <div class="form-group">
                                            <input class="sign-input-box number" type="text" class="form-control " name="mobile" id="mobile" placeholder="Mobile Number" maxlength="16">
                                        </div>
                                        <div class="form-group">
                                            <input class="sign-input-box" type="password" class="form-control" id="o_password" name="password" placeholder="Create Password" maxlength="20">
                                        </div>
                                        <div class="form-group">
                                            <input class="sign-input-box" type="email" class="form-control" id="email" name="email" placeholder="Email ID">
                                        </div>
                                        <!--  <div class="checkbox">
                                            <label><input type="checkbox"> Remember me</label>
                                        </div> -->
                                        <p>
                                        </p>
                                        <button  type="button" id="registration" class="btn proceedbtn registration_otp">Proceed</button>
                                        <p>
                                            By creating this account, you agree to  <a href="#">terms and conditions</a> & <a href="#">Privacy Policy </a>
                                        </p>

                                    </form>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- forgot password -->

                    <div class="tabbable-panel-8" style="display: none;">
                        <div class="tabbable-line">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <ul class="nav nav-tabs ">
                                <li class="active">
                                    <a data-toggle="tab">Forgot Password</a>
                                </li>
                            </ul>
                            <div class="tab-pane active forgot-content" id="tab_default_5">
                                <h3><small>It's ok, it can happen to anyone. Enter your mobile number and we'll send you an OTP to reset your password.</small></h3>
                                <form  method="POST">
                                    <span id="error_validation_forgot_pass"></span>
                                    <div class="form-group">
                                        <input class="sign-input-box" type="text" name="email_for_otp" id="email_for_otp" class="form-control" id="username" placeholder="Registered Mobile Number/ Email ID">
                                    </div>
                                    <button type="button" id="otp" class="btn proceedbtn">Proceed</button>
                                </form>                                    
                            </div>
                        </div>
                    </div>

                    <div class="tabbable-panel-15" style="display: none;">
                        <div class="tabbable-line">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <ul class="nav nav-tabs ">
                                <li class="active">
                                    <a data-toggle="tab">Enter Received OTP</a>
                                </li>
                            </ul>
                            <div class="tab-pane active forgot-content" id="tab_default_5">
                                <h3><small></small></h3>
                                <form  method="POST">
                                    <span id="error_validation_otp"></span>
                                    <div class="form-group">
                                        <input class="sign-input-box" type="text" name="otp_value"  class="form-control" id="otp_value" placeholder="Enter OTP">
                                    </div>
                                    <p style="font-size: 15px; margin-left: 15px;">Haven't received?<span><a href="" style="color: #1d73a2; font-size:15px; font-weight: bold;"> Resend OTP</a></span></p>   
                                    <button type="button" id="newpass" class="btn proceedbtn">Login Securely</button>
                                </form>                                    
                            </div>
                        </div>
                    </div>

                     <div class="tabbable-panel-zx" id="otp_screen" style="display: none;border: none;padding: 10px;width: 100%;">
                       
                        <div class="tabbable-line">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <ul class="nav nav-tabs ">
                                <li class="active">
                                    <a data-toggle="tab">Enter Received OTP</a>
                                </li>
                            </ul>
                             <span id="error_validation_otp_new"></span>
                            <div class="tab-pane active forgot-content" id="tab_default">
                                <h3><small></small></h3>
                                <form  method="POST">
                                    <span id="error_validation"></span>
                                    <div class="form-group">
                                        <input class="sign-input-box" type="text" name="email" class="form-control" id="enter_received_otp_new" placeholder="Enter OTP">
                                        <input type="hidden" id="received_otp_new" name="rcv_otp_new" value="">
                                    </div>
                                    <p style="font-size: 15px; margin-left: 15px;">Haven't received?<span><button class="btn btn-xs btn-default" type="button" id="resend_registration_otp">Resend OTP</button></span></p>   
                                    <button type="button" id="received_otp_btn" class="btn proceedbtn">Login Securely</button>
                                </form>                                    
                            </div>
                        </div>
                    </div>

                    <div class="tabbable-panel-16" style="display: none;">
                        <div class="tabbable-line">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <ul class="nav nav-tabs ">
                                <li class="active">
                                    <a data-toggle="tab">Create New Password</a>
                                </li>
                            </ul>
                            <div class="tab-pane active forgot-content" id="tab_default_5">
                                <h3><small></small></h3>
                                <form  method="POST">
                                    <span id="error_validation_otp_pass"></span>
                                    <div class="form-group">
                                        <input class="sign-input-box" type="password"  maxlength="16" name="otp_pass" id="otp_pass" class="form-control" id="username" placeholder="Enter New Password">
                                    </div>
                                    <div class="form-group">
                                        <input class="sign-input-box" type="password" maxlength="16" name="otp_c_pass" id="otp_c_pass" class="form-control" id="username" placeholder="Confirm Password">
                                    </div>  
                                    <button type="button" id="update_pass_btn" class="btn proceedbtn">Create Now</button>
                                </form>                                    
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end of forgott password -->





                <!-- end of login form -->

                <div class="tabbable-panel-2" style="display: none;">
                    <div class="tabbable-line">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <ul class="nav nav-tabs ">
                            <li class="active">
                                <a href="">Owner</a>
                            </li>
                        </ul>
                        <span id="error_validation_Gym"></span>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_default_1">
                                <form  method="POST" id="create_profile">
                                    <input type="hidden" id="latitude" name="latitude" value="">
                                    <input type="hidden" id="longitude" name="longitude" value="">
                                    <div class="options-services">
                                        <label class="container-radio">Gym
                                            <input type="radio" value="0" checked="checked" name="is_class" class="select_user_type">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="container-radio">Yoga
                                            <input value="1" type="radio" name="is_class" class="select_user_type" disabled="">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="container-radio">Zumba
                                            <input value="2" type="radio" name="is_class" class="select_user_type" disabled="">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="container-radio">Others
                                            <input value="3" type="radio" name="is_class" class="select_user_type" disabled="">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>	

                                    <div class="form-group">
                                        <input class="sign-input-box" type="text" name="name" class="form-control" id="gymname" placeholder="Name of Gym">
                                    </div>
                                    <div class="form-group">
                                        <input class="sign-input-box" type="text" name="address" class="form-control" id="gymaddess" placeholder="Address">
                                    </div>
                                    <div class="form-group">
                                        <input class="sign-input-box" type="text"  class="form-control" id="gymcity" placeholder="City">
                                    </div>
                                    <!--  <div class="checkbox">
                                        <label><input type="checkbox"> Remember me</label>
                                    </div> -->
                                    <p>
                                        By logging in, you agree to  <a href="#">terms and conditions</a> & <a href="#">Privacy Policy </a>
                                    </p>
                                    <button type="button" id="submit_profile" class="btn proceedbtn">Proceed</button>
                                    <center><a href="<?php echo site_url('web_panel/Home'); ?>" style="font-size:16px">Skip>></a></center>
                                </form>
                            </div>
                            <div class="tab-pane">
                                <form action="/action_page.php">
                                    <div class="form-group">
                                        <input class="sign-input-box" type="text" class="form-control number" id="phone" placeholder="Mobile Number">
                                    </div>
                                    <div class="form-group">
                                        <input class="sign-input-box" type="password" class="form-control" id="pwd" placeholder="Create Password">
                                    </div>
                                    <div class="form-group">
                                        <input class="sign-input-box" type="email" class="form-control" id="email" placeholder="Email ID">
                                    </div>
                                    <!--  <div class="checkbox">
                                        <label><input type="checkbox"> Remember me</label>
                                    </div> -->
                                    <p>
                                    </p>
                                    <button type="button" id="" class="btn proceedbtn">Proceed</button>
                                    <p>
                                        By creating this account, you agree to  <a href="#">terms and conditions</a> & <a href="#">Privacy Policy </a>
                                    </p>
                                </form>                                    
                            </div>
                        </div>
                    </div>
                </div>
                <!-- professional signup -->

                <div class="tabbable-panel-1" style="display: none;">
                    <div class="tabbable-line">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <ul class="nav nav-tabs ">
                            <li class="active">
                                <a href="">Professional</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active">

                                <form  method="POST" id="create_profile_profess">
                                    <div class="options-services">
                                        <label class="container-radio container-radio-option-2">Trainer
                                            <input type="radio" value="1" checked="checked" name="professional_type">
                                            <span class="checkmark"></span>
                                        </label>
                                        <select name="professional_sub_type" class="optionfield">
                                            <option value="11">Gym Trainer</option>
                                            <option value="12">Yoga Trainer</option>
                                            <option value="13">Zumba Trainer</option>
                                        </select>
                                        <label class="container-radio container-radio-option-2">Front Office
                                            <input type="radio" value="3" name="professional_type">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="container-radio container-radio-option-2">Fitness manager 
                                            <input type="radio" value="2" name="professional_type">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="container-radio container-radio-option-2">Others
                                            <input type="radio" value="4" name="professional_type">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <input class="sign-input-box" type="text" name="experience" class="form-control" id="experience" placeholder="Experience">
                                    </div>
                                    <div class="form-group">
                                        <input class="sign-input-box" type="text" name="address" class="form-control" id="address" placeholder="Address">
                                    </div>
                                    <div class="form-group">
                                        <input class="sign-input-box" type="text" name="location" class="form-control" id="location" placeholder="City">
                                    </div>
                                    <!--  <div class="checkbox">
                                        <label><input type="checkbox"> Remember me</label>
                                    </div> -->
                                    <p>
                                        <a href="#">Forgot Password</a>
                                    </p>
                                    <button type="button" id="submit_profile_profess" class="btn proceedbtn">Proceed</button>
                                    <p>
                                        By logging in, you agree to  <a href="#">terms and conditions</a> & <a href="#">Privacy Policy </a>
                                    </p>
                                    <center><a href="<?php echo site_url('web_panel/Home'); ?>" style="font-size:16px">Skip>></a></center>
                                </form>
                            </div>
                            <div class="tab-pane">
                                <form action="/action_page.php">
                                    <div class="form-group">
                                        <input class="sign-input-box" type="text" class="form-control number" id="phone" placeholder="Mobile Number">
                                    </div>
                                    <div class="form-group">
                                        <input class="sign-input-box" type="password" class="form-control" id="pwd" placeholder="Create Password">
                                    </div>
                                    <div class="form-group">
                                        <input class="sign-input-box" type="email" class="form-control" id="email" placeholder="Email ID">
                                    </div>
                                    <!--  <div class="checkbox">
                                        <label><input type="checkbox"> Remember me</label>
                                    </div> -->
                                    <p>
                                    </p>
                                    <button type="button" id="" class="btn proceedbtn">Proceed</button>
                                    <p>
                                        By creating this account, you agree to  <a href="#">terms and conditions</a> & <a href="#">Privacy Policy </a>
                                    </p>
                                </form>                                    
                            </div>
                        </div>
                    </div>
                </div>




            </div>  
        </div>
    </div>	
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $('#login').click(function () {
        //alert();
        var username = $('#username').val();
        var password = $('#password').val();


        jQuery.ajax({
            url: "<?php echo base_url('index.php/web_panel/Login'); ?>",
            method: 'POST',
            dataType: 'json',
            data: {
                username: username,
                password: password
            },

            success: function (data) {
                if (data.status == true) {
                    window.location.href = "<?php echo base_url('index.php/web_panel/Home') ?>";
                } else {
                    $('#error_validation').text(data.message);
                    $('#error_validation').css({"font-size": "14px", "color": "red"});
                    //$('#error_validation').fadeOut(3000);
                }
            }
        });


    });

</script>
<script>
    $('#forgotpassword').click(function () {
        $(".tabbable-panel").hide();
        $(".tabbable-panel-8").toggle();
    });
</script>
<script>
//    $('#otp').click(function () {
//        $(".tabbable-panel-8").hide();
//        $(".tabbable-panel-15").toggle();
//    });
</script>
<script>
//    $('#newpass').click(function () {
//        $(".tabbable-panel-15").hide();
//        $(".tabbable-panel-16").toggle();
//    });
</script>
<script>
     $('.registration_otp').click(function () {
        var first_name = $('#first_name').val();
        var last_name = $('#last_name').val();
        var mobile = $('#mobile').val();
        var password = $('#o_password').val();
        var email = $('#email').val();
        var select_user_value = $('#select_user_value').val();
        if (select_user_value === "") {
            select_user_value = 1;
        }
        //alert(select_user_value);
        jQuery.ajax({
            url: "<?php echo base_url('index.php/web_panel/Registration/'); ?>",
            method: 'POST',
            dataType: 'json',
            data: {
                first_name: first_name,
                last_name: last_name,
                mobile: mobile,
                password: password,
                email: email,
                user_type: select_user_value
            },
            success: function (data) { 
                if (data.status == true) {
                       $("#otp_screen").show();
                        $("#tab_default_2").hide();
                        $(".tabbable-panel").hide();
                        $('#received_otp_new').val(data.otp);
                    // if (select_user_value == "1") { 
                    //     $(".tabbable-panel").hide();
                    //     $(".tabbable-panel-2").show();
                    // } 
                    // if (select_user_value == "2") { 
                    //     $(".tabbable-panel").hide();
                    //     $(".tabbable-panel-1").show();
                    // } 
                    // if (select_user_value == "0") { 
                    //     //window.location.href = "<?php echo base_url('index.php/web_panel/Home') ?>";

                    // } 

                } else { 
                    $('#error_validation_registration').text(data.message);
                    $('#error_validation_registration').css({"font-size": "14px", "color": "red"});
                    //$('#error_validation').fadeOut(3000);
                }
            }
        });

    });

     $('#resend_registration_otp').click(function () {
        var first_name = $('#first_name').val();
        var last_name = $('#last_name').val();
        var mobile = $('#mobile').val();
        var password = $('#o_password').val();
        var email = $('#email').val();
        var select_user_value = $('#select_user_value').val();
        if (select_user_value === "") {
            select_user_value = 1;
        }
        //alert(select_user_value);
        jQuery.ajax({
            url: "<?php echo base_url('index.php/web_panel/Registration/'); ?>",
            method: 'POST',
            dataType: 'json',
            data: {
                first_name: first_name,
                last_name: last_name,
                mobile: mobile,
                password: password,
                email: email,
                user_type: select_user_value
            },
            success: function (data) { 
                if (data.status == true) {
                    $('#error_validation_otp_new').text('OTP has been sent successfully.');
                    $('#error_validation_otp_new').css({"font-size": "14px", "color": "green"});

                } 
            }
        });

    });

      $('#received_otp_btn').click(function () { 
        var first_name = $('#first_name').val();
        var last_name = $('#last_name').val();
        var mobile = $('#mobile').val();
        var password = $('#o_password').val();
        var otp = $('#received_otp_new').val();
        var email = $('#email').val();
        var enter_received_otp_new = $('#enter_received_otp_new').val();
        var select_user_value = $('#select_user_value').val();
        if (select_user_value === "") {
            select_user_value = 1;
        }
        //alert(select_user_value);
        jQuery.ajax({
            url: "<?php echo base_url('index.php/web_panel/Registration/register_after_verification'); ?>",
            method: 'POST',
            dataType: 'json',
            data: {
                first_name: first_name,
                last_name: last_name,
                mobile: mobile,
                password: password,
                email: email,
                user_type: select_user_value,
                otp:otp,
                enter_received_otp_new:enter_received_otp_new
            },
            success: function (data) { 
                if (data.status == true) { 
                    if (select_user_value == "1") { 
                        $(".tabbable-panel").hide();
                        $(".tabbable-panel-2").show();
                         $("#otp_screen").hide();
                    } 
                    if (select_user_value == "2") { 
                        $(".tabbable-panel").hide();
                        $(".tabbable-panel-1").show();
                         $("#otp_screen").hide();
                    } 
                    if (select_user_value == "0") { 
                    window.location.href = "<?php echo base_url('index.php/web_panel/Home') ?>";

                     } 

                } else { 
                    $('#error_validation_otp_new').text(data.message);
                    $('#error_validation_otp_new').css({"font-size": "14px", "color": "red"});
                    //$('#error_validation').fadeOut(3000);
                }
            }
        });



    });


</script>
<script>
    $.getJSON('https://geoip-db.com/json/geoip.php?jsonp=?')
            .done(function (location) {
                if ($('#latitude').val() === '') {
                    $('#latitude').val(location.latitude);
                }
                if ($('#longitude').val() === '') {
                    $('#longitude').val(location.longitude);
                }
            });
</script>
<script>
    $('#submit_profile').click(function () {
        var data = $('#create_profile').serialize();
        //alert(data);
        jQuery.ajax({
            url: "<?php echo base_url('index.php/web_panel/Registration/create_profile/'); ?>",
            method: 'POST',
            dataType: 'json',
            data: data,
            success: function (data) {
                if (data.status == true) {
                    //window.location.href = "<?php echo base_url('index.php/web_panel/Home') ?>";
                    window.location.href = "<?php echo base_url('index.php/web_panel/Profile/add_profile_details?dfdsf=1&hjghj=1') ?>";
                }
            }
        });
    });
</script>

<script>
    $('#submit_profile_profess').click(function () {
        var data = $('#create_profile_profess').serialize();
        jQuery.ajax({
            url: "<?php echo base_url('index.php/web_panel/Registration/create_profile/'); ?>",
            method: 'POST',
            dataType: 'json',
            data: data,
            success: function (data) {
                if (data.status == true) {
                    //window.location.href = "<?php echo base_url('index.php/web_panel/Home') ?>";
                    if (data.type == 1) {
                        window.location.href = "<?php echo base_url('index.php/web_panel/Profile/add_profile_details?dfdsf=2&hjghj=1') ?>";
                    } else {
                        window.location.href = "<?php echo base_url('index.php/web_panel/Profile/add_profile_details?dfdsf=2&hjghj=2') ?>";
                    }

                }
            }
        });
    });
</script>


<script>
    $('.select_user').on('click', function () {
        var value = $(this).attr('data-value');
        //alert(value);
        $('#select_user_value').val(value);
    });

</script>
<script>
    $('.select_user_type').on('click', function () {
        var value = $(this).val();
        //alert(value);
    });

</script>
<script>
    $(".number").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            //$("#reg_agent_error_validation").html("Digits Only").show().fadeOut("slow");
            return false;
        }
    });
    $('.alphabet').keypress(function (e) {
        var regex = new RegExp(/^[a-zA-Z\s]+$/);
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        } else {
            e.preventDefault();
            return false;
        }
    });
</script>
<script >
    $('input:radio[name=professional_type]').on('click', function () {
        var inputValue = $(this).attr("value");
        // alert(inputValue);
        if (inputValue == 1)
        {
            $('.optionfield').show();
        } else
        {
            $('.optionfield').hide();
        }

    });
</script>

<script>
    $('#otp').click(function () {
        var username = $('#email_for_otp').val();
        //alert(username);
        jQuery.ajax({
            url: "<?php echo base_url('index.php/web_panel/Registration/forgot_password'); ?>",
            method: 'POST',
            dataType: 'json',
            data: {
                username: username
            },
            success: function (data) {
                //alert(data);exit;
                if (data.status == true) {
                    //window.location.href = "<?php //echo base_url('index.php/web_panel/Home')  ?>";
                    $(".tabbable-panel-8").hide();
                    $(".tabbable-panel-15").toggle();
                } else {
                    $('#error_validation_forgot_pass').text(data.message);
                    $('#error_validation_forgot_pass').css({"font-size": "14px", "color": "red"});
                }
            }
        });
    });
    
    
    $('#newpass').click(function () {
        var otp_value = $('#otp_value').val();
        //alert(otp_value); 
        jQuery.ajax({
            url: "<?php echo base_url('index.php/web_panel/Registration/otp_verification'); ?>",
            method: 'POST',
            dataType: 'json',
            data: {
                otp_value: otp_value
            },
            success: function (data) {
                //alert(data);exit;
                if (data.status == true) {
                    //window.location.href = "<?php //echo base_url('index.php/web_panel/Home')  ?>";
                    $(".tabbable-panel-15").hide();
                    $(".tabbable-panel-16").toggle();
                } else {
                    $('#error_validation_otp').text(data.message);
                    $('#error_validation_otp').css({"font-size": "14px", "color": "red"});
                }
            }
        });
    });


    
    
    $('#update_pass_btn').click(function () {
        var otp_pass = $('#otp_pass').val();
        var otp_c_pass = $('#otp_c_pass').val();
        //alert(otp_value); 
        jQuery.ajax({
            url: "<?php echo base_url('index.php/web_panel/Registration/reset_password'); ?>",
            method: 'POST',
            dataType: 'json',
            data: {
                otp_pass: otp_pass,
                otp_c_pass: otp_c_pass
            },
            success: function (data) {
                //alert(data);exit;
                if (data.status == true) {
                    window.location.href = "<?php echo base_url('index.php/web_panel/Home')  ?>";
//                    $(".tabbable-panel-15").hide();
//                    $(".tabbable-panel-16").toggle();
                } else {
                    $('#error_validation_otp_pass').text(data.message);
                    $('#error_validation_otp_pass').css({"font-size": "14px", "color": "red"});
                }
            }
        });
    });
</script>
