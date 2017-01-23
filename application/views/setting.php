<script>

    function deactivate(cid){
      BootstrapDialog.confirm({
        title: 'Confirm',
        message: 'are you sure you want to delete your account?',
        type: BootstrapDialog.TYPE_DANGER,
        closable: true,
        draggable: true,
        btnCancelLabel: 'Cancel',
        btnOKLabel: 'Delete',
        btnOKClass: 'btn-danger',
        callback: function(result) {
            if(result) {
                var status;
                var cValue;

                if($("#active-button").text() === 'DEACTIVATE'){
                  status = 0;
                  cValue = 'ACTIVATE';
                  cActive = 'Active My Account';
                }
                else{
                  status = 1;
                  cValue = 'DEACTIVATE';
                  cActive = 'Deactivate My Account';
                }
                $.ajax({
                   url: site_url + 'users/Deactivate',
                   data: {             
                      id: cid,
                      status: status         
                   },
                   success: function(data) {
                      $("#active-button").text(cValue);
                      $("#active_account").text(cActive);
                   },
                   type: 'POST'
                });
            }
        }
      });
      
    }

    
    

    function onValue(value){
      $.ajax({
               url: site_url + 'setting/setChat',
               data: {             
                  value: value        
               },
               success: function(data) {
               },
               type: 'POST'
            });
    }

    function onCheck(value){
      $.ajax({
         url: site_url + 'setting/setOtherNotification',
         data: {             
            value: value        
         },
         success: function(data) {
         },
         type: 'POST'
      });
    }

    function deleteAccount(id){
      BootstrapDialog.confirm({
        title: 'Confirm',
        message: 'are you sure you want to delete your account?',
        type: BootstrapDialog.TYPE_DANGER,
        closable: true,
        draggable: true,
        btnCancelLabel: 'Cancel',
        btnOKLabel: 'Delete',
        btnOKClass: 'btn-danger',
        callback: function(result) {
            if(result) {

              $.ajax({
                 url: site_url + 'users/delete/'+id+'/100',
                 data: {             
                 },
                 success: function(data) {
                 },
                 type: 'POST'
              });
                
            }
        }
    });
    }

    function onOption(field, value){
        $.ajax({
           url: site_url + 'setting/updateOption',
           data: {    
              field: field,
              value: value         
           },
           success: function(data) {
           },
           type: 'POST'
        });
    }


</script>


  
  <?php if($page_title === 'Profile Setting | Relayy') {?>

  <div class="white_back" style="padding:100px 20px 50px 20px;margin:-10px;">
    <h3 class="pull-left gray-text" style="margin-top:10px;">PROFILE SETTINGS</h3>
  </div>

  <div class="col-text white_back border1234 radius-item">
      <div class="row" style="padding:20px;">
          <img class="img-responsive pull-left sender-pic round" src="<?= strlen($u_photo)>0?$u_photo:asset_base_url().'/images/emp.jpg'?>" style="width:100px;height:100px;">
          <p class="gray-text" style="font-size:20px;margin-top:40px;"> <?= $u_fname?> <?= $u_lname?> </p>
      </div>

      <div class="row">
        <div class="col-md-6 col-xs-12 border1 border2">
          <div class="col-xs-12">
            <h4 class="gray-text"> PROFILE SETTINGS </h4>
          </div>  

          <div class="col-xs-7 Qinput">
            <h6 class="gray-text"> Give Feedback </h6>
          </div>
          <div class="col-xs-5" style="margin-top:10px;">
            <input type="button" class="ob pull-right" disabled = "disabled" value="CONNECT" onclick="chatWithAdmin(1)"/>
          </div>

          <div class="col-xs-7 Qinput">
            <h6 class="gray-text"> Share On Social Media </h6>
          </div>
          <div class="col-xs-5" style="margin-top:10px;">
            <input type="button" class="ob pull-right" disabled = "disabled" value="SHARE" onclick=""  style="margin-bottom:10px;"/>
          </div>
        </div>      

        <div class="col-md-6 col-xs-12 border1">
          <div class="col-xs-12">
            <h4 class="gray-text"> ACCOUNT SETTINGS </h4>
          </div>    

          <div class="col-xs-7 Qinput">
            <h6 class="gray-text" id="active_account"> <?php if($my_status == 1){echo 'Deactivate My Account';}else{echo 'Activate My Account';} ?></h6>
          </div>
          <div class="col-xs-5" style="margin-top:10px;">
            <button type="button" id="active-button" class="pull-right ob" onclick="deactivate(<?= $u_id ?>)"><?php if($my_status == 1){echo 'DEACTIVATE';}else{echo 'ACTIVATE';} ?></button>
          </div>

          <div class="col-xs-7 Qinput">
            <h6 class="gray-text"> Delete My Account </h6>
          </div>
          <div class="col-xs-5" style="margin-top:10px;">
            <input type="button" class="ob pull-right" value="DELETE" onclick="deleteAccount(<?= $u_id ?>)" style="margin-bottom:10px;"/>
          </div>
        </div>
      </div>

      

  </div>
  <?php } else { ?>
  <div class="white_back" style="padding:100px 20px 50px 20px;margin:-10px;">
    <h3 class="pull-left gray-text" style="margin-top:10px;">NOTIFICATIONS</h3>
  </div>

  <div class="col-text white_back border1234 radius-item">
      <div class="row padding_xs">
        <div class="col-md-6 col-xs-12">
          <h4 class="gray-text"> NOTIFICATION SETTINGS </h4>
        </div>    
        <div class="col-md-2 col-xs-4">
          <center class="gray-text">INCLUDE IN SUMMARY EMAIL</center>
        </div>
        <div class="col-md-2 col-xs-4">
          <center class="gray-text">SEND EMAIL RIGHT AWAY</center>
        </div>
        <div class="col-md-2 col-xs-4">
          <center class="gray-text">PAUSE THIS NOTIFICATION</center>
        </div>

      </div>

      <div class="row noti-opt-row">
        <div class="col-md-6 col-xs-12">
          <p class="font-20 gray-text">Unread messages</p>
          <p class="def-font">Notify me when I miss messages that happen in my conversations.</p>   
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-unread" value="1" onclick="onOption('unread', 1)" <?= $setval[0]['unread']==1?'checked':'' ?>></center>
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-unread" value="2" onclick="onOption('unread', 2)" <?= $setval[0]['unread']==2?'checked':'' ?>></center>
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-unread" value="3" onclick="onOption('unread', 3)" <?= $setval[0]['unread']==3?'checked':'' ?>></center>
        </div>
      </div>

      <div class="row noti-opt-row">
        <div class="col-md-6 col-xs-12">
          <p class="font-20 gray-text">Chat invitation</p>
          <p class="def-font">Notify me when I am invited to a new conversation.</p>   
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-invite" value="1" onclick="onOption('invite', 1)" <?= $setval[0]['invite']==1?'checked':'' ?>></center>
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-invite" value="2" onclick="onOption('invite', 2)" <?= $setval[0]['invite']==2?'checked':'' ?>></center>
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-invite" value="3" onclick="onOption('invite', 3)" <?= $setval[0]['invite']==3?'checked':'' ?>></center>
        </div>
      </div>

      <?php if($u_type == 3) { ?>
      <div class="row noti-opt-row">
        <div class="col-md-6 col-xs-12">
          <p class="font-20 gray-text">Chat requrest approved</p>
          <p class="def-font">Notify me when a new conversation has been accepted and approved</p>   
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-approve" value="1" onclick="onOption('approve', 1)" <?= $setval[0]['approve']==1?'checked':'' ?>></center>
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-approve" value="2" onclick="onOption('approve', 2)" <?= $setval[0]['approve']==2?'checked':'' ?>></center>
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-approve" value="3" onclick="onOption('approve', 3)" <?= $setval[0]['approve']==3?'checked':'' ?>></center>
        </div>
      </div>
      <?php } ?>

      <?php if($u_type == 1 || $u_type == 4) { ?>
      <div class="row noti-opt-row">
        <div class="col-md-6 col-xs-12">
          <p class="font-20 gray-text">Question needs to be routed</p>
          <p class="def-font">Notify me when someone asks a question, and this question needs to be routed to potential advisors.</p>   
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-submit" value="1" onclick="onOption('submit', 1)" <?= $setval[0]['submit']==1?'checked':'' ?>></center>
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-submit" value="2" onclick="onOption('submit', 2)" <?= $setval[0]['submit']==2?'checked':'' ?>></center>
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-submit" value="3" onclick="onOption('submit', 3)" <?= $setval[0]['submit']==3?'checked':'' ?>></center>
        </div>
      </div>

      <div class="row noti-opt-row">
        <div class="col-md-6 col-xs-12">
          <p class="font-20 gray-text">Accepted question</p>
          <p class="def-font">Notify me when a question has been accepted by someone</p>   
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-accept" value="1" onclick="onOption('accept', 1)" <?= $setval[0]['accept']==1?'checked':'' ?>></center>
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-accept" value="2" onclick="onOption('accept', 2)" <?= $setval[0]['accept']==2?'checked':'' ?>></center>
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-accept" value="3" onclick="onOption('accept', 3)" <?= $setval[0]['accept']==3?'checked':'' ?>></center>
        </div>
      </div>
      <?php } ?>

      <div class="row noti-opt-row">
        <div class="col-md-6 col-xs-12">
          <p class="font-20 gray-text">Someone saves my comment</p>
          <p class="def-font">Notify me when another user saves one of my comments in a conversation</p>   
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-comment" value="1" onclick="onOption('comment', 1)" <?= $setval[0]['comment']==1?'checked':'' ?>></center>
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-comment" value="2" onclick="onOption('comment', 2)" <?= $setval[0]['comment']==2?'checked':'' ?>></center>
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-comment" value="3" onclick="onOption('comment', 3)" <?= $setval[0]['comment']==3?'checked':'' ?>></center>
        </div>
      </div>

      <div class="row noti-opt-row">
        <div class="col-md-6 col-xs-12">
          <p class="font-20 gray-text">Someone leaves me a review</p>
          <p class="def-font">Notify me when another user posts a review to my profile</p>   
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-review" value="1" onclick="onOption('review', 1)" <?= $setval[0]['review']==1?'checked':'' ?>></center>
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-review" value="2" onclick="onOption('review', 2)" <?= $setval[0]['review']==2?'checked':'' ?>></center>
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-review" value="3" onclick="onOption('review', 3)" <?= $setval[0]['review']==3?'checked':'' ?>></center>
        </div>
      </div>

      <?php if($u_type == 2) { ?>
      <div class="row noti-opt-row">
        <div class="col-md-6 col-xs-12">
          <p class="font-20 gray-text">I'm sent a new question.</p>
          <p class="def-font">Notify me when a new question is routed to me, and added to my question list</p>   
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-route" value="1" onclick="onOption('route', 1)" <?= $setval[0]['route']==1?'checked':'' ?>></center>
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-route" value="2" onclick="onOption('route', 2)" <?= $setval[0]['route']==2?'checked':'' ?>></center>
        </div>
        <div class="col-md-2 col-xs-4">
          <center><input type="radio" name="opt-route" value="3" onclick="onOption('route', 3)" <?= $setval[0]['route']==3?'checked':'' ?>></center>
        </div>
      </div>
      <?php } ?>

      <div class="row noti-opt-row" >
          <p class="col-xs-12 font-20 gray-text">How often do you want a summary about new activity that you missed?</p>
          <p class="col-xs-12 def-font">You will only get a summary email if new activity occurs during that time period</p>   
      </div>

      <div class="row lp">
        <p class="col-xs-12 font-20 gray-text">No more than every</h6>
      </div>

      <div class="row lp last_div">
        <div class="col-sm-2 col-xs-4 padding_xs">
          <button type="button" class="border1234 sum-button padding_xs btn full-width <?= $setval[0]['interval']==='1 Hour'?'ob':'btn-question-state' ?>">1 Hour</button>
        </div>
        <div class="col-sm-2 col-xs-4 padding_xs">
          <button type="button" class="border1234 sum-button padding_xs btn full-width <?= $setval[0]['interval']==='4 Hour'?'ob':'btn-question-state' ?>">4 Hour</button>
        </div>
        <div class="col-sm-2 col-xs-4 padding_xs">
          <button type="button" class="border1234 sum-button padding_xs btn full-width <?= $setval[0]['interval']==='8 Hour'?'ob':'btn-question-state' ?>">8 Hour</button>
        </div>
        <div class="col-sm-2 col-xs-4 padding_xs">
          <button type="button" class="border1234 sum-button padding_xs btn full-width <?= $setval[0]['interval']==='Day'?'ob':'btn-question-state' ?>">Day</button>
        </div>
        <div class="col-sm-2 col-xs-4 padding_xs">
          <button type="button" class="border1234 sum-button padding_xs btn full-width <?= $setval[0]['interval']==='Week'?'ob':'btn-question-state' ?>">Week</button>
        </div>
        <div class="col-sm-2 col-xs-4 padding_xs">
          <button type="button" class="border1234 sum-button padding_xs btn full-width <?= $setval[0]['interval']==='Off'?'ob':'btn-question-state' ?>">Off</button>
        </div>
      </div>

  </div>

  <?php } ?>

 
  


<script>


$(".sum-button").click(function(){
      $(".sum-button").prop("class", "border1234 sum-button padding_xs btn btn-question-state full-width");
      $(this).toggleClass("ob");
      $(this).toggleClass("btn-question-state");
      if($(this).text() == "1 Hour"){
        onOption("interval", "1 Hour");
      }
      else if($(this).text() == "4 Hour"){
        onOption("interval", "4 Hour");
      }
      else if($(this).text() == "8 Hour"){
        onOption("interval", "8 Hour");
      }
      else if($(this).text() == "Day"){
        onOption("interval", "Day");
      }
      else if($(this).text() == "Week"){
        onOption("interval", "Week");
      }
      else{
        onOption("interval", "Off");
      }
    });

</script>




