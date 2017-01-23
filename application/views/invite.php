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

<script type="text/javascript">
    var userId = '<?php echo $current_id ?>';
    var userType = '<?php echo $current_type ?>';
    // Setup an event listener to make an API call once auth is complete
    function onLinkedInLoad() {
        IN.Event.on(IN, "auth", getProfileData);
    }

    function onLinkedInClk() {
        IN.UI.Authorize().place();
        onLinkedInLoad();
    }

    // Handle the successful return from the API call
    function onSuccess(data) {
        var type = 0;
        if($("#inviteuser_role").val() === "Admin") type = 1;
        else if($("#inviteuser_role").val() === "Advisor") type = 2;
        else if($("#inviteuser_role").val() === "Entreprenuer") type = 3;
        else type = 4;
        //console.log(data);
        var dataObj = data.values[0];
        // console.log(dataObj.id+ dataObj.emailAddress+ dataObj.firstName+ dataObj.lastName);
        var companyInfo = "";
        for(var index = 0; index < dataObj.positions._total; index++){
          if(dataObj.positions.values[index].isCurrent){
            companyInfo = JSON.stringify(dataObj.positions.values[index]);
            break;
          }
        }
      
        registerInvitedUser(userId, dataObj.emailAddress, dataObj.firstName, dataObj.lastName, dataObj.pictureUrl,
         dataObj.headline, type, dataObj['location']['name'], dataObj.publicProfileUrl, companyInfo);
    }

    // Handle an error response from the API call
    function onError(error) {
        console.log(error);
    }

    // Use the API call wrapper to request the member's basic profile data
    function getProfileData() {
        IN.API.Profile("me").fields("id", "pictureUrl", "first-name", "last-name", "email-address", "headline", "industry", "positions", "location", "public-profile-url", "summary", "specialties").result(onSuccess).error(onError);
        // IN.API.Raw("/people/~").result(onSuccess).error(onError);
    }







</script>




<div id="main" class="main blue_main">

    <center>
        <div class="container-widget invite_up_div">
            <div class="row border3" style="margin:0px;">
              <div class="col-xs-offset-2 col-xs-8 padding_sm">
                <center><img style="width:100%;" src="<?php echo asset_base_url()?>/images/logo.jpg"></center>
              </div>
            </div>

            <div class="row border3" style="margin:0px;">

                <div class="row" style="margin:0px;">
                    <center><h3 class="hi_tit">You have been invited to join Relayy.</h3></center>
                </div>
                
                <div class="row invite_up_div" style="min-width:250px;margin:0px;">
                    <div class="row">
                        <center>
                        <h5 class="col-sm-4 control-label" style="padding:0px;">User Role:</h5>
                        <div class="col-sm-6 selectContainer">
                            <select class="form-control" id="inviteuser_role" <?php echo $current_type > 10?"disabled='disabled'":"" ?>>
                                <?php if($current_type > 10) { ?>
                                    <option <?php echo $current_type % 10 == 1?"selected":"" ?>>Admin</option>
                                <?php } ?>
                                <option <?php echo $current_type % 10 == 2?"selected":"" ?>>Advisor</option>
                                <option <?php echo $current_type % 10 == 3?"selected":"" ?>>Entreprenuer</option>
                                <option <?php echo $current_type % 10 == 4?"selected":"" ?>>Moderator</option>
                            </select>
                        </div>
                        </center>
                    </div>
                        
                    <div class="row padding_sm">
                        <button type="button" class="btn btn-primary btn-lg btn-block facebook"  onclick="onLinkedInClk()">Sign up with LinkedIn</button>
                    </div>

                    <div class="row lp"><center class="uline" id="whysignup">Why must I sign up with LinkedIn?</center></div>
                    <div class="row padding_sm">
                        <center>By clicking "Sign Up" you indicate that you have read and agree to the <a href="http://relayy.io/termsofservice" target="_blank">Terms of Service</a> and <a href="http://relayy.io/privacypolicy" target="_blank">Privacy policy</a>.</center>
                    </div>

                </div>
            </div>

            <div class="row padding_sm" style="margin:0px;">
                <center><h2 style="color:#72b7f8;">WHAT IS RELAYY?</h2></center>
                <center>Relayy is on-demand advice for business owners. Questions are matched with advisors in private and secure messaging chats. Business owners get answers and advisors get business leads and connections.</center>
                <center>Learn more about Relayy here: <a href="http://relayy.io" target="_blank">http://relayy.io</a></center>
            </div>
        </div>

      </center>
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

