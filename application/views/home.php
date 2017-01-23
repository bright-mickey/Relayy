<script type="text/javascript" src="//platform.linkedin.com/in.js">
    //app: 78kvui9d2pw0jq
    //dev: 789n2d8njlgknx
    //staging: 78mfwbj5aj9mwy
    api_key: linkedIn_API
    authorize: true
</script>

<script src="<?php echo asset_base_url()?>/libs/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo asset_base_url()?>/libs/jquery.nicescroll.min.js" type="text/javascript"></script>
<script src="<?php echo asset_base_url()?>/libs/jquery.timeago.min.js" type="text/javascript"></script>
<script src="<?php echo asset_base_url()?>/libs/bootstrap.min.js" type="text/javascript"></script>

<script src="<?php echo asset_base_url()?>/libs/quickblox.min.js"></script>
<script src="<?php echo asset_base_url()?>/js/bootstrap-dialog.min.js" type="text/javascript"></script>
<script src="<?php echo asset_base_url()?>/js/config.js"></script>
<script src="<?php echo asset_base_url()?>/js/dist/hello.all.js"></script>

<script type="text/javascript">
    
    var INFO;//retrived data from linkedIn
    var platform;
    var VerifyCode;
    //1: login   0:signup

    // Setup an event listener to make an API call once auth is complete
    function onLinkedInLoad() {
        IN.Event.on(IN, "auth", getProfileData);
    }

    function onLinkedInClk(login) {
      b_login = login;
      var iOS = ['iPad', 'iPhone', 'iPod'].indexOf(navigator.platform) >= 0;
        if(1){
              hello.init({
                linkedin: linkedIn_API
              });

              hello('linkedin').login({scope:'email, publish'}).then(function() {
                
                hello('linkedin').api('me').then(function(json) {
                  //alert(JSON.stringify(json));
                  INFO = json;
                  platform = "iOS";
                  if(b_login == 1){
                    LoginUser(INFO.id, INFO.emailAddress, INFO.firstName, INFO.lastName, INFO.pictureUrl,
                    "", 3, "", "", "");
                  }else{
                    $("#InviteCodeForm").modal("show");
                    HoverOutWhy();
                  }
                  
                }, function(e) {
                  alert('Whoops! ' + e.error.message);
                });

              }, function(e) {
                alert('Signin error: ' + e.error.message);
              });            

        } 
        else{
          IN.UI.Authorize().place();
          onLinkedInLoad();
        }
    }

    function onInviteCode(){
      //check invite code
      $("#invite_code_button").attr("disabled", "disabled");
      currentUser_signup_code = $("#invite_code").val();
      if(currentUser_signup_code.length != 6){
        alert("Invalid code");
        $("#verify_code").hide();
        $("#invite_code_button").removeAttr("disabled");
        return;
      }
      $("#verify_code").show();
      $.ajax({            
           url: site_url + 'invite/check_code',
           data: {
              code: currentUser_signup_code
           },
           success: function(data) {
              $("#invite_code_button").removeAttr("disabled");
              $("#verify_code").hide();
              if(platform !== "iOS"){
                var companyInfo = "";
                for(var index = 0; index < INFO.positions._total; index++){
                  if(INFO.positions.values[index].isCurrent){
                    companyInfo = JSON.stringify(INFO.positions.values[index]);
                    break;
                  }
                }
              }
              if(data === "Invalid") {
                alertstate('That invite code is invalid, or has been used the maximum amount of times allowed. Request an invite code on previous page.');
              }
              else if(data === "no_group"){

                  if(platform === "iOS"){
                      registerFacebook(INFO.id, INFO.emailAddress, INFO.firstName, INFO.lastName, INFO.pictureUrl,
                      "", 3, "", "", "", "");
                  }
                  else{
                      registerFacebook(INFO.id, INFO.emailAddress, INFO.firstName, INFO.lastName, INFO.pictureUrl,
                      INFO.headline, 3, INFO['location']['name'], INFO.publicProfileUrl, companyInfo, "");
                  }

              }
              else if(data.indexOf("Moderator") > -1){
                if(platform === "iOS"){
                    registerFacebook(INFO.id, INFO.emailAddress, INFO.firstName, INFO.lastName, INFO.pictureUrl,
                    "", 4, "", "", "", data.split("_")[1]);
                }
                else{
                   registerFacebook(INFO.id, INFO.emailAddress, INFO.firstName, INFO.lastName, INFO.pictureUrl,
                   INFO.headline, 4, INFO['location']['name'], INFO.publicProfileUrl, companyInfo, data.split("_")[1]);
                }
              }
              else{//moderator's group  code
                if(platform === "iOS"){
                    registerFacebook(INFO.id, INFO.emailAddress, INFO.firstName, INFO.lastName, INFO.pictureUrl,
                    "", 3, "", "", "", data);
                }
                else{
                   registerFacebook(INFO.id, INFO.emailAddress, INFO.firstName, INFO.lastName, INFO.pictureUrl,
                   INFO.headline, 3, INFO['location']['name'], INFO.publicProfileUrl, companyInfo, data);
                }
              }

           },
           type: 'POST'
        });
    }

    function RequestInvite(){
        window.open('mailto:jake@relayy.io?subject='+encodeURIComponent('request invite')+'&body='+encodeURIComponent('Hey, I want an invite code to Relayy!'));
    }

   
    // Handle the successful return from the API call
    function onSuccess(data) {
        //console.log(data);
        INFO = data.values[0];
        // console.log(dataObj.id+ dataObj.emailAddress+ dataObj.firstName+ dataObj.lastName);
        if(b_login == 1){// if login, invite code is not required
               
               var companyInfo = "";
                for(var index = 0; index < INFO.positions._total; index++){
                  if(INFO.positions.values[index].isCurrent){
                    companyInfo = JSON.stringify(INFO.positions.values[index]);
                    break;
                  }
                }
               LoginUser(INFO.id, INFO.emailAddress, INFO.firstName, INFO.lastName, INFO.pictureUrl,
               INFO.headline, 3, INFO['location']['name'], INFO.publicProfileUrl, companyInfo);

        }
        else{
          
          $.ajax({            
             url: site_url + 'users/check_email',
             data: {
                email: INFO.emailAddress
             },
             success: function(data) {
                if(data === "exist"){
                  alertstate("You can't signup, because you are already a user in Relayy.\n Please sign in to join.");
                  return;
                }
                else if(data === "deleted_user"){
                  alertstate("You can't signup, because you are deleted a user in Relayy.");
                  return;
                }
                else{
                  platform = "desktop";
                  $("#InviteCodeForm").modal("show");
                  HoverOutWhy();
                }
             },
             type: 'POST'
          });
          
        }
      
        
    }

    // Handle an error response from the API call
    function onError(error) {
        alert('error!');
        console.log(error);
    }

    // Use the API call wrapper to request the member's basic profile data
    function getProfileData() {
        IN.API.Profile("me").fields("id", "pictureUrl", "first-name", "last-name", "email-address", "headline", "industry", "positions", "location", "public-profile-url", "summary", "specialties").result(onSuccess).error(onError);
        // IN.API.Raw("/people/~").result(onSuccess).error(onError);
    }

    function onVerification() {

        var email = $("#auth_email").val();
        if(email == ""){
          alert("Email field is empty!");
          return;
        }
        $("#token_button").text('Sending token...');
        $("#token_button").attr('disabled', true);

        VerifyCode = Math.random().toString().substring(2,8);

        $.ajax({            
           url: site_url + 'auth/send_token',
           data: {
              token: VerifyCode,
              email: email
           },
           success: function(data) {
              $("#token_button").text('Next');
              $("#token_button").attr('disabled', false);

              if (data == "success")
              {
                  $("#token_button").text('Next');
                  $("#VerifyDialog").modal("show");
                  $("#SAT_Button").attr('disabled', false);
                  $("#VE_Button").attr('disabled', false);
              }
              else if(data == "not_exist"){
                alertstate("You are not registered.");
              }
              else if(data == "deleted_user"){
                alertstate("You can't login, because your account has been removed.");
              }
              else if(data == "pending_user"){
                alertstate("You can't login, because your account is pending temporarily.");
              }
              

           },
           type: 'POST'
        });
        

    }

    function SendAnotherToken(){
        VerifyCode = Math.random().toString().substring(2,8);

        $.ajax({            
            url: site_url + 'auth/send_token',
            data: {
              token: VerifyCode,
              email: email
            },
            success: function(data) {
              alertstate("Another token has been sent to your email successfully!");
            },
            type: 'POST'
        }); 
    }

    function VerifyEmail(){
        var email = $("#auth_email").val();
        $("#VE_Button").attr('disabled', true);
        var token = document.getElementById("auth_token").value;  
        if(token == ""){
          alert("Token field is empty!");
          $("#VE_Button").attr('disabled', false);
          return;
        }
        $("p").text("checking...");
        if(token == VerifyCode){
          $.ajax({
             url: site_url + "Auth/pass_token",
             data: {                                
                email: email
             },
             success: function(data) {
                if (data === "questions") location.href = site_url + 'questions';
                else alertstate("You are not active now.");
                $("#VerifyDialog").modal("hide");   
                $("#token_button").attr('disabled', false);                                
             },
             type: 'POST'
          });                      
        }   
        else{
          $("p").text("");
          alert('Invalid token!');
          $("#VE_Button").attr('disabled', false);   
        }
    }

    function alertstate(text){
      BootstrapDialog.show({
        type: BootstrapDialog.TYPE_INFO,
        title: "<h4 class='modal-title' style='color:white;'>Relayy</h4>",
        message: text + "\n",
        buttons: [{
            label: 'Close',
            cssClass: 'btn-primary',                           
            action: function(dialogRef) {  
               dialogRef.close();
            }
        }]
    });
    }

    function closeSignUpForm(){
      $("#registerForm").modal("hide");
    }

    function closeLoginForm(){
      $("#loginForm").modal("hide");
    }

    function closeCodeForm(){
      $("#InviteCodeForm").modal("hide");
    }

    function closeFirstForm(){
      //$("#first-login").modal("hide");
    }

</script>
<div id="main" class="main blue_main">
  <center>
    <div class="container-widget sign_in_up_div">
        <div class="row border3" style="margin:0px;">
          <div class="col-xs-offset-2 col-xs-8 padding_sm">
            <center><img style="width:100%;" src="<?php echo asset_base_url()?>/images/logo.jpg"></center>
          </div>
        </div>
        <div class="row padding_xs">
          <center class="padding_xs"><button type="button" class="login_button" onclick="signin()">SIGN IN</button></center>
          <center class="padding_xs"><button type="button" class="signup_button" onclick="signup()">CREATE FREE ACCOUNT</button></center>
        </div>
    </div>
  </center>
  

    <div id="loginForm" class="modal fade <?= isset($email)?"show":""?>" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header row" style="background:#FFF; margin:0px; padding:10px 0px;">
            <div class="col-xs-offset-2 col-xs-8">
              <center><img style="width:80%;" src="<?php echo asset_base_url()?>/images/logo.jpg"></center>
            </div>
            <div class="col-xs-2" style="padding:0px;">
              <a class="col-text pull-right" style="margin-top:0px;" onclick="closeLoginForm()"><img src="<?php echo asset_base_url()?>/images/close.png"></a>
            </div>
          </div>
          <div class="modal-body">
            <?php if (isset($did)){
                echo "<input type='hidden' name='did' value='$did'>";
            } ?>
            <div class="container-widget padding_xs" id="channel_edit">
              <div class="row"><h5><center class="paddding_xs gray-text">ENTER YOUR ACCOUNT EMAIL TO LOG IN</center></h5></div>

              <div class="row form-group">
                <div class="col-sm-8 col-xs-12">
                  <input type="email" class="form-control" name="sgn_email" placeholder="Enter your email" id="auth_email" value="<?= isset($email)?$email:""?>" <?= isset($email)?"readonly":""?>  style="height:49px;">
                </div>
                <div class="col-sm-4 col-xs-12">
                  <button type="button" class="btn wtext full-width" id="token_button" onclick="onVerification()" style="background:#72b7f8;height:50px;">Next</button>
                </div>
              </div>

              <div class="row"><center class="gray-text">*If you signed up with LinkedIn, enter the email associated with your LinkedIn account.</center></div>
              <div class="row border1 col-text canvas" style="margin-top:40px;">
                <center><button type="button" class="home_OR">OR</button></center>
              </div>

              <div class="row">
                <form method="post" action="<?php echo site_url('auth/linkedin') ?>" id="linkedin_form">
                  <input type="hidden" id="li_id" name="li_id" value="">
                  <input type="hidden" id="li_email" name="li_email" value="">
                  <input type="hidden" id="li_login" name="li_login" value="">
                  <input type="hidden" id="li_fname" name="li_fname" value="">
                  <input type="hidden" id="li_lname" name="li_lname" value="">
                  <input type="hidden" id="li_photo" name="li_photo" value="">
                  <input type="hidden" id="li_bio" name="li_bio" value="">
                  <input type="hidden" id="li_role" name="li_role" value="">
                  <input type="hidden" id="li_location" name="li_location" value="">
                  <input type="hidden" id="li_public" name="li_public" value="">
                  <input type="hidden" id="li_company" name="li_company" value="">
                  <input type="hidden" id="li_group" name="li_group" value="">
                  <input type="hidden" id="li_code" name="li_code" value="">
                  <center><button type="button" class="btn wtext facebook"  onclick="onLinkedInClk(1)">LOGIN WITH LINKEDIN</button></center>
                </form>
              </div>

              <div class="row"><center class="gray-text">*Nothing ever gets shared on LinkedIn without your consent.</center></div>
            </div>

            <div class="modal-footer" style="text-align:center;">
              <div class="col-sm-6 col-xs-12 gray-text">
                <h5>Don't you have an account?</h5>
              </div>
              <div class="col-sm-6 col-xs-12">
                <h5><a onclick="signup()">CREATE A FREE ACCOUNT HERE</a></h5>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div id="VerifyDialog" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" style="color:white;">Please input authentication token after checking your email</h4>
          </div>
          <div class="modal-body">
            <input type="text" placeholder="token" id="auth_token" name="auth_token" class="form-control"><br><p align="center"></p>
          </div>
          <div class="modal-footer" style="text-align:center;">
            <div class="col-xs-6" style="padding:0px;">
              <button class="btn pull-left wtext" id="SAT_Button" onclick="SendAnotherToken()" style="background: #72B7F8"><span class="glyphicon glyphicon-send"></span> Send another token</button>
            </div>
            <div class="col-xs-6" style="padding:0px;">
              <button class="pull-right btn wtext" id="VE_Button" onclick="VerifyEmail()" style="background: #72B7F8"><span class="glyphicon glyphicon-send"></span> Verify</button>
            </div>
          </div>
        </div>
      </div>
    </div>

   

    <div id="registerForm" class="modal fade <?= isset($email)?"show":""?>" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header row" style="background:#FFF; margin:0px; padding:10px 0px;">
            <div class="col-xs-offset-2 col-xs-8">
              <center><img style="width:80%;" src="<?php echo asset_base_url()?>/images/logo.jpg"></center>
            </div>
            <div class="col-xs-2" style="padding:0px;">
              <a class="col-text pull-right" style="margin-top:0px;" onclick="closeSignUpForm()"><img src="<?php echo asset_base_url()?>/images/close.png"></a>
            </div>
          </div>
          <div class="modal-body" style="padding:10px 40px;">
            
  
              <div class="row">
                <center><h5>CREATE QUESTIONS, CONVERSATIONS, AND OPPORTUNITIES FOR FREE.</h5></center>
                <center><h5>- ONE CLICK SIGN UP -</h5></center>
              </div>

              <div class="row">
                <form method="post" action="<?php echo site_url('auth/linkedin') ?>" id="linkedin_form">
                  <input type="hidden" id="li_id" name="li_id" value="">
                  <input type="hidden" id="li_email" name="li_email" value="">
                  <input type="hidden" id="li_login" name="li_login" value="">
                  <input type="hidden" id="li_fname" name="li_fname" value="">
                  <input type="hidden" id="li_lname" name="li_lname" value="">
                  <input type="hidden" id="li_photo" name="li_photo" value="">
                  <input type="hidden" id="li_bio" name="li_bio" value="">
                  <input type="hidden" id="li_role" name="li_role" value="">
                  <input type="hidden" id="li_location" name="li_location" value="">
                  <input type="hidden" id="li_public" name="li_public" value="">
                  <input type="hidden" id="li_company" name="li_company" value="">
                  <input type="hidden" id="li_group" name="li_group" value="">
                  <input type="hidden" id="li_code" name="li_code" value="">

                  <center><button type="button" class="btn wtext facebook"  onclick="onLinkedInClk(0)">SIGN UP WITH LINKEDIN</button></center>
                </form>
              </div>

              <div class="row padding_sm"><center class="uline" id="whysignup">Why must I sign up with LinkedIn?</center></div>
              <div class="row" style="padding:20px 30%;"><center>By clicking "Sign Up" you indicate that you have read and agree to the <a href="http://relayy.io/termsofservice" target="_blank">Terms of Service</a> and <a href="http://relayy.io/privacypolicy" target="_blank">Privacy policy</a>.</center></div>

              
          </div>
          <div class="modal-footer" style="text-align:center;">
            <div class="col-sm-6 col-xs-12">
              <h5 class="gray-text">Already have an account?</h5>
            </div>
            <div class="col-sm-6 col-xs-12">
              <h5><a onclick="signup_signin()">LOG IN HERE</a></h5>
            </div>
          </div>

        </div>
      </div>
    </div>



    <div id="InviteCodeForm" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header row" style="background:#FFF; margin:0px; padding:10px 0px;">
            <div class="col-xs-offset-2 col-xs-8">
              <center><img style="width:80%;" src="<?php echo asset_base_url()?>/images/logo.jpg"></center>
            </div>
            <div class="col-xs-2" style="padding:0px;">
              <a class="col-text pull-right" style="margin-top:0px;" onclick="closeCodeForm()"><img src="<?php echo asset_base_url()?>/images/close.png"></a>
            </div>
          </div>
          <div class="modal-body" style="padding:10px 40px;">
            
  
              <div class="row">
                <center><h5 class="gray-text">Relayy is privately testing the app with a select group of users. An invite code is needed to register.</h3></center>
              </div>

              <div class="row">
                <center><h3 class="gray-text">Enter invite code below:</h3></center>
                <center><input type="text" class="padding_xs" id="invite_code" style="width:140px;"></center>
                <center><button type="button" onclick="onInviteCode()" id="invite_code_button" class="canvas ob col-text" style="width:140px;">Continue<div class="teamup-spinner" id="verify_code" style="display:none;"><i class="fa sm-button fa-spinner fa-spin"></i></div></button></center>
              </div>

              <div class="row">
                <center><h5 class="gray-text">Don't have an invite code?</h5></center>
                <center><button type="button" onclick="RequestInvite()" class="bb"  style="width:140px;">Request Invite</button></center>
              </div>
              
          </div>
          <div class="modal-footer" style="text-align:center;">
            <div class="row">
              <h5 class="gray-text">Already have an account?<a onclick="signup_signin()">LOG IN HERE</a></h5>
            </div>
          </div>

        </div>
      </div>
    </div>


    <div id="linkedinForm" class="modal fade" data-keyboard="false" data-backdrop="static" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title wtext">Register to Relayy with LinkedIn</h3>
          </div>
          <div class="modal-body">
            <form class="form-horizontal" method="post" action="<?php echo site_url('auth/linkedin') ?>" id="linkedin_register_form">
              <input type="hidden" id="lir_id" name="li_id" value="">
              <input type="hidden" id="lir_email" name="li_email" value="">
              <input type="hidden" id="lir_login" name="li_login" value="">
              <input type="hidden" id="lir_fname" name="li_fname" value="">
              <input type="hidden" id="lir_lname" name="li_lname" value="">
              <input type="hidden" id="lir_photo" name="li_photo" value="">
              <input type="hidden" id="lir_bio" name="li_bio" value="">
              <input type="hidden" id="lir_location" name="li_location" value="">
              <input type="hidden" id="lir_public" name="li_public" value="">
              <input type="hidden" id="lir_company" name="li_company" value="">
              <input type="hidden" id="lir_group" name="li_group" value="">
              <input type="hidden" id="lir_code" name="li_code" value="">
              <div class="form-group">
                <label class="col-sm-4 control-label">I'm a </label>
                <div class="col-sm-6 selectContainer">
                  <select class="form-control" name="li_role" id="user_role">
                    <option value="3" selected="selected">Entrepreneur</option>
                    <option value="2">Advisor</option>
                  </select>
                </div>
              </div>
              <div class="form-group" style="margin-bottom:0">
                <div class="col-sm-offset-4 col-sm-6">
                  <input type="submit" class="ob full-width" data-toggle="modal" value="Register">
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
</div>

<script>
  $("#whysignup").hover(function(){
    HoverOnWhy();
  }, function(){
    HoverOutWhy();
  });

  function HoverOnWhy(){
    guiders.createGuider({
      attachTo: "#whysignup",
      buttons: [],
      description:  '<div class="row padding_sm" style="font-family:\'proximanovar\';">'+
                    '<p>Why must I sign up with LinkedIn?<p>'+
                    '<p style="margin-left:30px;">Enhances trust between members. You know exactly who you are talking with.<br>Saves you time during the sign up process</p>'+
                    '<br>'+
                    '<p>**NOTHING will get shared on LinkedIn. You are the only one that can share anything about Relayy on LinkedIn.</p>'+
                    '</div>',
      id: "whysignupwithlinkedIn",
      position: 6,
      title: "",
      width: 300
    }).show();
  }

  function HoverOutWhy(){
    $("#whysignupwithlinkedIn").hide();
  }


</script>












