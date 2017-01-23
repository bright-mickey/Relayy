<div class="profile-container">
<div class="container-widget" style="height:100vh;padding:0px 30px 0px 30px;">

<?php 
  if ($u_status == 2) {?>
  <div class="alert alert-info">
    <strong>Congratulations!</strong><br> You've been registered and activated automatically. Please fill your profile details.
  </div>
<?php } else if ($u_status != 1) {?>
  <div class="alert alert-warning">
    <strong>Sorry!</strong> Your account is not approved by admin. Please wait for admin's action.
  </div>
<?php }
?>

  
<form method="post" action="<?php echo site_url('profile/save')?>">
  <div class="row">

    <div class="col-xs-12 upload-image user-type">

      <div class="image-wrap text-center" style="margin:0 auto;">
        <img id="user_pic" class="img-responsive round" src="<?= strlen($u_photo)>0?$u_photo:asset_base_url().'/images/emp.jpg'?>" style="width:100px; height:100px; margin:0 auto;">

        <input id="user_pic_info" type="hidden" name="picture" value="<?= strlen($u_photo)>0?$u_photo:asset_base_url().'/images/emp.jpg'?>">

        <input id="img-file" type="file" name="files[]" multiple style="margin:0 auto;">

      </div>

    </div>

  </div>
  <div class="row">
  
  <div class="account-info container-widget">
    <div class="row" style="margin-top:30px;">
      <div class="col-xs-4" style="text-align:center;margin-top:5px;">First Name:</div>
      <div class="col-xs-8">
        <input type="text" name="fname" class="form-control" placeholder="First Name" required="true" value="<?= $u_fname?>" autocomplete="off"/>
      </div>

    </div>

    <div class="row" style="margin-top:10px;">
      <div class="col-xs-4" style="text-align:center;margin-top:5px;">Last Name:</div>
      <div class="col-xs-8">
        <input type="text" name="lname" class="form-control" placeholder="Last Name" required="true" value="<?= $u_lname?>" autocomplete="off"/>
      </div>
    </div>

    <div class="row" style="margin-top:10px;">

      <div class="col-xs-4" style="text-align:center;margin-top:5px;">Email:</div>

      <div class="col-xs-8">
        <input type="email" class="form-control" placeholder="Email" required="true" value="<?= $u_email?>" disabled="disabled"/>
      </div>

    </div>
    
    
    <div class="row" style="margin-top:10px;">

      <div class="col-xs-4" style="text-align:center;margin-top:5px;">Bio:</div>

      <div class="col-xs-8">
        <textarea name="bio" class="form-control" required="true"><?= $u_bio?></textarea>
      </div>

    </div>

    <div class="row" style="margin-top:10px;">     

        <div class="col-xs-12" style="padding-right:20px;">
          <input type="submit" class="btn btn-primary pull-right" value="Save" style="width:100px;">
        </div>

    </div>
    
  </div>

  

<!-- 

  <div class="alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" ><span aria-hidden="true">×</span></button>
    
  </div>
  <div class="alert alert-success alert-dismissible" role="alert" ng-show="updateSuccess">
    <button type="button" class="close"><span aria-hidden="true">×</span></button>
    <span>Profile saved successfully.</span></div>
 -->
  </form>
  </div>
</div>
</div>

