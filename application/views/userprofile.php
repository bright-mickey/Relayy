
<div class="profile-container white_back border1234 radius-item">
<div class="container-widget" style="margin:0px;line-height:1.5;">
  <?php if($status == USER_STATUS_DELETE) { ?>
    <p style="text-align:center; width:100%; margin-top:20px;">Your account has been removed.</p>
  <?php } else { ?>

<!-- //====================Photo and Role -->
  <div class="row padding_md">
                <div class="col-md-2 col-xs-12" style="text-align:center;">
                  <img class="preview-Img" src="<?= strlen($photo)>0?$photo:asset_base_url().'/images/emp.jpg'?>" style="border-radius:100%;">
                </div>
                <div class="col-md-9 col-xs-12 container-widget">
                  <div class="row font-18">
                    <div class="col-xs-4"><p class="gray-text">Name: </p></div>
                    <div class="col-xs-8"><p><b><?= $name ?></b></p></div>
                  </div>
                  <div class="row font-16">
                    <div class="col-xs-4"><p class="gray-text">Role: </p></div>
                    <div class="col-xs-8">
                      <p>
                      <?php if($current_type == 1) echo "Admin";
                      else if($current_type == 2) echo "Advisor";
                      else if($current_type == 3) echo "Entrepreneur";
                      else echo "Moderator";?></p>
                    </div>
                  </div>
                  <div class="row font-16">
                    <div class="col-xs-4"><p class="gray-text">Bio: </p></div>
                    <div class="col-xs-8"><p><?= $bio?></p></div>
                  </div>
               

                  <div class="row font-16">
                    <div class="col-xs-4"><p class="gray-text">Location: </p></div>
                    <div class="col-xs-8"><p><?= $location?$location:"-" ?></p></div>
                  </div>
                  <div class="row font-16">
                    <div class="col-xs-4"><p class="gray-text">Social Media: </p></div>
                    <div class="col-xs-8"><a target="_blank" href="<?= $public_url ?>"><p class="wrapword"><?= $public_url?$public_url:"-"?></p></a></div>
                  </div>

                </div>
                <div class="col-md-1 col-xs-12">
                  <button type="button" class="ob pull-right" id="startchat" onclick = "chatWithUser('<?= $email?>', '<?= $current_id?>')" style="margin-right:20px;">CHAT</button>
                </div>
        </div>

  <?php if(strlen($group_name) > 0){ ?>
        <div class="row div-item border1" style="margin-top:0px;">
            <div class="col-sm-6 col-xs-12">
              <div class="col-xs-12">
                <h5 class="col-xs-10 gray-text">GROUP MEMBERSHIP:</h5>
              </div>
              <div class="col-xs-12">
                <?php if(strlen($group_name) > 0){ ?>
                  <img class="pull-left round" width="40" height="40" src="<?= strlen($group_image_name) > 0?uploads_base_url().$group_image_name:asset_base_url().'/images/ava-group.svg' ?>">
                  <p class="pull-left padding_xs gray-80"><?= $group_name ?></p>
                <?php } else {?>
                  <p class="padding_xs gray-80">No group to display</p>
                <?php } ?>
              </div>
            </div>
            <div class="col-sm-6 col-xs-12">
              <div class="col-xs-12">
                <h5 class="col-xs-10 gray-text">MEMBERSHIP CATEGORY:</h5>
              </div>
              <div class="col-xs-12">
                <?php if($type == 2){ ?>
                  <img class="pull-left round" id="category_image" width="40" height="40" src="<?= $category == 1?asset_base_url().'/images/MentorIcon.png':asset_base_url().'/images/ProviderIcon.png' ?>">
                  <p class="pull-left padding_xs gray-80" id="category_text"><?= $category == 1?"Mentor":"Service Provider" ?></p>
                <?php } ?>
              </div>
            </div>
        </div>
        <?php } ?>
   

<!-- //====================Profile Detail -->
  <div class="row div-item border1" style="margin-top:0px;">
        <h4 class="gray-text"> PROFILE STATS:</h4>
    </div>

    <div class="row">

      <div class="col-md-6 col-xs-12" style="padding:0px 20px;">
          <div class="col-xs-9">
            <p class="gray-text">NUMBER OF TEAMUP CHATS ENTERED</p>
          </div>
          <div class="col-xs-3">
            <p class="font-20"><?= $entered_chats?></p>
          </div>    
      </div>

      <div class="col-md-6 col-xs-12" style="padding:0px 20px;">
          <div class="col-xs-9 Qinput">
            <p class="gray-text">COMMENTS ADDED IN TEAMUP CHATS</p>
          </div>
          <div class="col-xs-3">
            <p class="font-20"><?= $self_comments?></p>
          </div>
      </div>

      <div class="col-md-6 col-xs-12" style="padding:0px 20px;">
          <div class="col-xs-9 Qinput">
            <p class="gray-text">COMMENTS THAT OTHERS SAVED</p>
          </div>
          <div class="col-xs-3">
            <p class="font-20"><?= $other_comments?></p>
          </div>
      </div>

      <div class="col-md-6 col-xs-12" style="padding:0px 20px;">
          <div class="col-xs-9 Qinput">
            <p class="gray-text">NUMBER OF REVIEWS</p>
          </div>
          <div class="col-xs-3">
            <p class="font-20"><?= $reviews?></p>
          </div>
      </div>

    </div>

<!-- //====================  Profile Skills  //====================-->


      <div class="row">
        <div class="col-md-6 col-xs-12 border1 border2" id="section-seeking" style="padding:20px;">
<?php if($type != 2) { ?>
      

          <div class="row">
            <h4 class="col-xs-12 nm gray-text"> CURRENTLY SEEKING: </h4>
          </div>

          <div class="row section-seeking" id="looking_container">
            <?php if(isset($looking) && $looking !== "" && $looking !== "[]") { ?>
            <?php foreach(json_decode($looking) as $node) { ?>
                <div class="online_tags pull-left" id="li_looking"><?= $node ?><a class="close more-close" style="display:none;" onclick="looking_Remove(this)">&times;</a></div>
            <?php } ?>
            <?php } else { echo "<p class='empty_looking'> There is no data</p>"; }?>
          </div>

    <?php } else {?>

          <div class="row">
            <h4 class="col-xs-12 nm gray-text"> CURRENTLY SEEKING: </h4>
          </div>

          <div class="row section-seeking" id="interesting_container">
            <?php if(isset($interesting) && $interesting !== "" && $interesting !== "[]") { ?>
            <?php foreach(json_decode($interesting) as $node) { ?>
                <div class="online_tags pull-left" id="li_interesting"><?= $node ?><a class="close more-close" style="display:none;" onclick="interesting_Remove(this)">&times;</a></div>
            <?php } ?>
            <?php } else { echo "<p class='empty_interesting'> There is no data</p>"; }?>
          </div>

          
    <?php } ?>
      </div>

      <div class="col-md-6 col-xs-12 border1 border2" id="section-skill" style="padding:20px;">   

        <div class="row">
          <h4 class="col-xs-12 nm gray-text"> SKILLS:</h4>
        </div>

        <div class="row section-skill" id="skill_container">
            <?php if(isset($skill) && $skill !== "" && $skill !== "[]") { ?>
            <?php foreach(json_decode($skill) as $node) { ?>
              <div class="online_tags pull-left" id="li_skill"><?= $node ?><a class="close more-close" style="display:none;" onclick="skill_Remove(this)">&times;</a></div>
            <?php } ?>
            <?php } else { echo "<p class='empty_skill'> There is no data</p>"; }?>
        </div>

      </div>

      <div class="col-md-6 col-xs-12 border1 border2" id="section-position" style="padding:20px;">

        <div class="row section-position" id="position-field">
            <div class="col-xs-12">
              <h4 class="col-xs-12 nm gray-text">EXPERIENCE & POSITIONS HELD:</h4>
            </div>
            <div class="col-text col-xs-12"></div>
            <?php if(isset($position) && $position !== "" && $position !== "[]") { ?>
            <?php foreach(json_decode($position) as $pos) { ?>
              <div class="col-xs-12">
                <div class="col-xs-1" style="padding:5px;">
                  <img src="<?= asset_base_url().'/images/list-disc.png'?>" class="pull-left">
                </div>
                <div class="col-xs-11">
                  <?= $pos ?>
                </div>
              </div>
            <?php } ?>
            <?php } ?>
        </div>

      </div>

      <div class="col-md-6 col-xs-12 border1 border2" id="section-education" style="padding:20px;">

        <div class="row section-education" id="education-field">
            <div class="col-xs-12">
              <h4 class="col-xs-12 nm gray-text">EDUCATION:</h4>
            </div>
            <div class="col-text col-xs-12"></div>
            <?php if(isset($education) && $education !== "" && $education !== "[]") { ?>
            <?php foreach(json_decode($education) as $edu) { ?>
              <div class="col-xs-12">
                <div class="col-xs-1" style="padding:5px;">
                  <img src="<?= asset_base_url().'/images/list-disc.png'?>" class="pull-left">
                </div>
                <div class="col-xs-11">
                  <?= $edu ?>
                </div>
              </div>
            <?php } ?>
            <?php } ?>
        </div>

      </div>

    </div>


    <!-- =====================   Current Venture and Link   ================== -->
    <div class="row border1 font-16" style="padding:20px;">
        <?php if($current_type == 3) { ?>
        <div class="row">
              <h4 class="nm pull-left gray-text"> CURRENT VENTURE:</h4>
        </div>

        <div class="row col-text">
          <div class="col-sm-4 col-xs-12 gray-80">Name:</div>
          <div class="col-sm-7 col-xs-11 italic_value" id="venture_name"><?php echo $venture_name?$venture_name:"No data" ?></div>
        </div>

        <div class="row">
          <div class="col-sm-4 col-xs-12 gray-80">Summary:</div>
          <div class="col-sm-7 col-xs-11 italic_value" id="summary"><?php echo $summary?$summary:"No data" ?></div>
        </div>

        <div class="row">
          <div class="col-sm-4 col-xs-12 gray-80">Industry:</div>
          <div class="col-sm-7 col-xs-11 italic_value" id="industry"><?php echo $industry?$industry:"No data" ?></div>
        </div>

        <div class="row">
          <div class="col-sm-4 col-xs-12 gray-80">Business stage:</div>
          <div class="col-sm-7 col-xs-11 italic_value" id="stage"><?php echo $stage?$stage:"No data" ?></div>
        </div>

        <div class="row">
          <div class="col-sm-4 col-xs-12 gray-80">Employees:</div>
          <div class="col-sm-7 col-xs-11 italic_value" id="employee_num"><?php echo $employee_num?$employee_num:"No data" ?></div>
        </div>

        <div class="row">
          <div class="col-sm-4 col-xs-12 gray-80">Funding Raised:</div>
          <div class="col-sm-7 col-xs-11 italic_value" id="funding"><?php echo $funding?$funding:"No data" ?></div>
        </div>
      <?php } else { ?>
        <div class="row">
              <h4 class="nm pull-left gray-text"> CURRENT COMPANY:</h4>
        </div>

        <div class="row col-text">
          <div class="col-sm-4 col-xs-12 gray-80">Name:</div>
          <div class="col-sm-7 col-xs-11 italic_value" id="companyName"><?= isset($company->company->name)?$company->company->name:$c_name ?></div>
        </div>

        <div class="row">
          <div class="col-sm-4 col-xs-12 gray-80">Location:</div>
          <div class="col-sm-7 col-xs-11 italic_value" id="companyLocation"><?= isset($company->location->name)?$company->location->name:$c_location ?></div>

        </div>

        <div class="row">
          <div class="col-sm-4 col-xs-11 gray-80">Summary:</div>
          <div class="col-sm-7 col-xs-11 italic_value" id="companySummary"><?= isset($company->summary)?$company->summary:$c_summary ?></div>
        </div>

      <?php } ?>
      
    </div>
      


        <div class="row border1 div-item">
          <h4 class="pull-left gray-text"> LINKS:</h4>
        </div>

        <div class="row" id="Link_field">
        <?php foreach($array_link as $link) { ?>
          <!-- <div class="canvas link-Img-div link-item" style="margin:20px;">
            <a href="<?= $link['link'] ?>" class="mask-div"><img class="stretch-Img" src="<?= $link['image'] ?>"></a>
            <div class="full-width row link-title" style="display:none;">
            <center class="context_title"><?= $link['title'] ?></center>
            </div>
            <div class="full-width row link-url" style="display:none;">
            <center><?= $link['link'] ?></center>
            </div>
                                               
          </div> -->
          <div class="col-xs-12" style="padding-left:30px;">
            <p class="wrapword">
              <a target="_blank" class="link-txt pull-left" href="<?= $link['link'] ?>"><?= $link['link'] ?></a>
            </p>
          </div>
        <?php } ?>
        </div>


  </div>

<!-- ===============  Reviews  ============= -->
    

 <div class="row border1 padding_sm">
      <div class="col-md-9 col-sm-9 col-xs-12">
        <h4 class="nm gray-text"> REVIEWS: </h4>
      </div>   
      <div class="col-md-3 col-sm-3 col-xs-12">
        <a href="<?php echo site_url('profile/leaveReview/'.$current_id) ?>"><input type="button" class="ob pull-right" value="LEAVE REVIEW"/></a>
      </div> 
  </div>

  <div class="row last_div">
    <div id="channel_edit" class="pre-scrollable nice-scroll" style="overflow:auto;height:40vh;">                      
            <ul id="routed-contacts" style="height:100%;"> 
              <?php foreach($array_review as $review) {?>

                <li class="li-item" id="review_<?= $review['id']?>">
                  <div class="row"> 

                    <div class="col-sm-11 col-xs-10">
                      <img class="avatar avatar_small" src="<?= $review['photo'] ?>" >
                      <span class="rtext Qinput" style="padding"><?= $review['review'] ?></span>                        
                    </div>

                    <?php if($u_id == $review[TBL_REVIEW_FROM]) { ?>
                      <div class="col-sm-1 col-xs-2">
                          <span class="glyphicon glyphicon-pencil pull-right text-primary" onclick="edit_Review(<?= $review['id'] ?>)"></span>
                      </div>
                    <?php } ?>

                  </div>
                </li> 

              <?php } ?>                   
            </ul>
        </div>

  </div>

  
  <?php } ?>
</div>
</div>

<script>

  function match_sections(){
          var value;
          var space = 5;
          if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            console.log('mobile');
          }
          else{
            if($(".section-seeking").height() > $(".section-skill").height()){
              value = $(".section-seeking").height();
              value = 30+(Math.round(value/10) + space) * 10;//30: title's height
              $("#section-skill").height(value);
              $("#section-seeking").height(value);
            }
            else{
              value = $(".section-skill").height();
              value = 30+(Math.round(value/10) + space) * 10;//30: title's height
              $("#section-skill").height(value);
              $("#section-seeking").height(value);
            } 

            if($(".section-position").height() > $(".section-education").height()){
              value = $(".section-position").height();
              value = (Math.round(value/10) + space) * 10;
              $("#section-education").height(value);
              $("#section-position").height(value);
            }
            else{
              value = $(".section-education").height();
              value = (Math.round(value/10) + space) * 10;
              $("#section-position").height(value);
              $("#section-education").height(value);
            } 
          }

      }
      match_sections();


      

      function edit_Review(id){
            var name = $("#review_"+id+" .rtext").text();
            BootstrapDialog.show({
                title: 'Edit Review',
                message:  '<span class="row padding_sm">Your review:</span>'+
                          '<input class="row edit_review_field full-width border-style-xs" type="text" id="dialog_name" value="'+name+'">',
                buttons: [{
                    label: 'Delete',  
                    cssClass: "rb",                        
                    action: function(dialogRef) { 
                        $.ajax({
                           url: site_url + 'profile/deleteReview',
                           data: {             
                              r_id: id             
                           },
                           success: function(data) {
                              $("#review_" + id).hide();
                              dialogRef.close();
                           },
                           type: 'POST'
                        });
                    }
                },{
                    label: 'Update',
                    cssClass: 'btn-primary',
                    autospin: true,                
                    action: function(dialogRef) {
                      var txt = $(".edit_review_field").val();
                      $.ajax({
                         url: site_url + 'profile/editReview',
                         data: {   
                            txt: txt,          
                            id: id            
                         },
                         success: function(data) {
                            $("#review_"+id+" .rtext").text(txt);
                            dialogRef.close();
                         },
                         type: 'POST'
                      });

                    }
                }]
            });
      }

</script>








