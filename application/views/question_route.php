<script src="<?php echo asset_base_url()?>/js/questions.js" type="text/javascript"></script>

<div class="container-widget"  style="height:100vh;margin-top:70px;padding:0px 10px;">    
    <div class="row" style="padding:15px;margin-top:35px;">
      <div class="col-sm-5 col-xs-12">
        <p>ADVISOR NAME</p>
        <input type="text" class="Qinput padding_xs"  id = "advisor_name" style="width:90%;">
      </div>
      <div class="col-sm-5 col-xs-12">
        <p id = "advisor_tag">ADVISOR TAGS</p>
        <input type="text"  id = "advisor_skill" class="Qinput padding_xs" style="width:90%;">
      </div>
      <div class="col-sm-2 col-xs-12">
        <input type="button" class="btn btn-primary" value="SEARCH" id="Advisor_Search" onclick="onAdvisorSearch()" style="width:90%;margin-top:25px;">
      </div>
    </div>
    <div class="row" style="margin-top:20px;margin-left:30px;">
      <p id="selected_advisor_num">Select the advisors to which you would like to route this question.</p>
    </div>
    <div class="row round_table" style="height:70vh;">
      
        
        <div style="padding:20px 0px;height:80px;border-bottom:1px solid #ABABAB;">
          
          <div class="col-xs-1"><input type="checkbox" class="select-all pull-right" onclick="selectAll()" style="margin-top:15px;"></div>
          <div class="col-xs-7" style="margin-top:15px;"><span>     Select All</span></div>
          <div class="col-xs-4"><input type="button" class="btn btn-primary pull-right" value="ROUTE" id="Advisor_Search" onclick="onRoute()" style="margin-right:15px;"></div>
        </div>

        <div id="channel_edit" class="col-md-12 scrollbar" style="height:75%;overflow:auto;">
          <ul id="route_contacts">
              <?php $num = 0;
                    foreach($advisors as $advisor){
                      if(!in_array($advisor['id'], $routed_users) && !in_array($advisor['id'], $accepted_users)) {
                      $num = $num + 1; 
                      $skill = str_replace(array(' ', ',', '?', '(', ')', '&', '[', ']', '"'), '', $advisor['business_skill']);
                      if($u_type != 1 && $u_group !== $advisor['group']) continue;
                  ?>
                  <li onclick="" class = "li-item advisor_contacts row" data-skills="<?= $skill ?>">

                    <div class="col-xs-1"><input type="checkbox" class="select-checkbox pull-right" value = "<?= $advisor['id']?>" onclick="onCheck(<?= $advisor['id']?>)"></div>
                    <?php if(strlen($advisor['photo']) > 0) {?>  <div class="col-sm-1 col-xs-3"><a href="<?= site_url().'profile/user/'.$advisor['id'] ?>"><img class="avatar avatar_small" src="<?= $advisor['photo'] ?>"></a></div>
                    <?php } else {?><div class="col-sm-1 col-xs-2"><a href="<?= site_url().'profile/user/'.$advisor['id'] ?>"><img class="avatar avatar_small" src="<?= asset_base_url()."/images/emp-sm.jpg"?>"></a></div>
                    <?php } ?>                        
                    <div class="col-sm-10 col-xs-8 container-widget">
                      <div class="row"><span class="contacts_name" id="<?= $advisor['id']?>"><?= $advisor['fname'] ?> <?= $advisor['lname'] ?></span></div>
                      <div class="row"><span class="contacts_email" id="<?= $advisor['id']?>"><?= $advisor['bio'] ?></span></div>
                    </div>

                  </li> 
                <?php } ?>
              <?php } ?>
              <?php if($num == 0) {?>
                <li onclick="" style="padding:15px;">
                  <p style="text-align:center;">There is no available advisor</p>
                </li>
              <?php } ?>
          </ul>
        </div>  
    </div>

    <div class="last_div">
      <button class="pull-left btn" onclick="backfromroute()">Back</button>
    </div>
</div>
<script>

  var string = <?php echo json_encode($routed_users)?>;
  InitRouteUserIDArray = JSON.parse("[" + string + "]");   
  RouteUserIDArray =  JSON.parse("[" + string + "]");  
  QuestionID = <?php echo $question_id ?>;
  function backfromroute(){
    location.href = site_url + "questions"; 
  }

  $("#advisor_name").on('input', function(){
      onAdvisorSearch();
  });

  $("#advisor_skill").on('input', function(){
      onAdvisorSearch();
  });

</script>




