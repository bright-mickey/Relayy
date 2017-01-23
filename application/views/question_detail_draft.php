<script src="<?php echo asset_base_url()?>/js/questions.js" type="text/javascript"></script>
<script src="<?php echo asset_base_url()?>/js/dist/hello.all.js"></script>
<script>
  var email = "<?php echo $u_email ?>";
  function fullSize(obj){
    $('.attached-Img').prop("class", "preview-Img attached-Img");
    $(obj).prop("class", "fullsize-Img attached-Img");    
  }

  function sortRouted(){
    $(".question-style").each(function(){
      $(this).show();
      if($(this).find(".btn-question-state").text()==="DRAFTED" || $(this).find(".btn-question-state").text()==="SUBMITTED"){
        $(this).hide();
      }
    });
  }

  function sortNotRouted(){
    $(".question-style").each(function(){
      $(this).show();
      if($(this).find(".btn-question-state").text()==="ROUTED" || $(this).find(".btn-question-state").text()==="ACCEPTED"){
        $(this).hide();
      }
    });
  }

  function CreateTeamUp(q_id){
    location.href = site_url + "chat/createteamup/" + q_id;
  }

  function filter_Submitted(){
    $("#search_num_question").text("No result");
    $(".question-style").show();
    $(".question-style").each(function(){
      if($(this).find(".btn-question-state").text().indexOf("SUBMITTED") < 0){
        $(this).hide();
      }
    });
    $(".question_filter_tab").removeClass("active");
    $("#tab-submitted").addClass("active");
  }

  function filter_Routed(){
    $("#search_num_question").text("No result");
    $(".question-style").show();
    $(".question-style").each(function(){
      if($(this).find(".btn-question-state").text().indexOf("ROUTED") < 0){
        $(this).hide();
      }
    });
    $(".question_filter_tab").removeClass("active");
    $("#tab-routed").addClass("active");
  }

  function filter_Accepted(){
    $("#search_num_question").text("No result");
    $(".question-style").show();
    $(".question-style").each(function(){
      if($(this).find(".btn-question-state").text().indexOf("ACCEPTED") < 0){
        $(this).hide();
      }
    });
    $(".question_filter_tab").removeClass("active");
    $("#tab-accepted").addClass("active");
  }

  function filter_Launched(){
    $("#search_num_question").text("No result");
    $(".question-style").show();
    $(".question-style").each(function(){
      if($(this).find(".btn-question-state").text().indexOf("LAUNCHED") < 0){
        $(this).hide();
      }
    });
    $(".question_filter_tab").removeClass("active");
    $("#tab-launched").addClass("active");
  }

  function filter_Waiting(){
    $("#search_num_question").text("No result");
    $(".question-style").show();
    $(".question-style").each(function(){
      if($(this).find(".btn-question-state").attr("data-waiter-num") === "0"){
        $(this).hide();
      }
    });
    $(".question_filter_tab").removeClass("active");
    $("#tab-waiting").addClass("active");

  }

  function filter_All(){
    if(currentUser_type != 3) $("#search_num_question").text("No questions have been asked yet");
    else $("#search_num_question").html("Ask a question and start getting answers by clicking the <span class=\"blue-text\">Add a Question</p> button above");
    $(".question-style").show();
    $(".question_filter_tab").removeClass("active");
    $("#tab-all").addClass("active");
  }

  function ShareWithLinkedIn(qid, title) {
      var payload;
      if( 1 ){
         var articleUrl = encodeURIComponent(site_url + "questions/preview/" + qid);
         var articleTitle = encodeURIComponent(title);
         var articleSummary = encodeURIComponent('Relayy is on-demand advice for business owners. Questions are matched with advisors in private and secure messaging chats. Business owners get answers and advisors get business leads and connections.');
         var articleSource = encodeURIComponent(site_url + "assets/images/onlinkedIn.png");
         var goto = 'http://www.linkedin.com/shareArticle?mini=true'+
             '&url='+articleUrl+
             '&title='+articleTitle+
             '&summary='+articleSummary+
             '&source='+articleSource;
         window.open(goto, "LinkedIn", "width=320,height=500,scrollbars=no;resizable=no");
        
      }
      else{

        //Build the JSON payload containing the content to be shared(not work on safari);
        alert('Sharing');
        payload = {
          "comment": "Check out " + site_url,
          "content": {
            "title": "I think you can benefit from this lead on Relayy",
            "description": $("#linkedIn-message").val(),
            "submitted-url": site_url + "questions/preview/" + q_id,  
            "submitted-image-url": site_url + "assets/images/onLinkedIn.jpg"
          },
          "visibility": {
            "code": "anyone"
          }  
        }

        IN.API.Raw("/people/~/shares?format=json")
          .method("POST")
          .body(JSON.stringify(payload))
          .result(onSuccess)
          .error(onError);

      }
      // passFeed(q_id, c_id, true); 
      // dialog.close();
      //location.href = "http://www.linkedin.com/shareArticle?mini=true&url=SendMessageForm&title=SendMessageToYourConnections";
    }

    function deleteQuestion(qid){
      BootstrapDialog.show({
        title:"Delete a question",
        message: "are you sure you want to delete this question ?",
        type: BootstrapDialog.TYPE_DANGER,
        buttons: [{
            label: 'Delete',
            cssClass: 'btn-danger',
            autospin: true,
            action: function(dialogRef){
                  $.ajax({
                     url: site_url + 'questions/deleteQuestion',
                     data: {             
                        qid: qid,
                     },
                     success: function(data) {    
                        dialogRef.close();                
                        if(data === "success"){
                          $("#card_"+qid).remove();
                        }
                        else{
                          console.log(data);
                        }                       
                     },
                     type: 'POST'
                  });
            }
        }, {
            label: 'Cancel',
            action: function(dialogRef){
                dialogRef.close();
            }
        }]
    });
    }

    function referToColleague(qid, title){
        var top_text;
        if(currentUser_type != 3) top_text = "Do you know someone who is qualified to answer this question?<br> Provide value to your colleagues by sharing this opportunity with them.";
        else top_text = "Would you like to invite someone to answer your question?<br>When you share, a question preview is shared that ONLY includes the question title and question tags. Only registered advisors on Relayy can view the full question and your name.";
        dialog = BootstrapDialog.show({
            type: BootstrapDialog.TYPE_PRIMARY,
            title: "<p class='font-20'>Refer to Colleague</p>",
            message: '<div class="row padding_30">'+
                      '<h5 class="gray-text">'+top_text+'<br>Many times this results in a lead, or an opportunity to increase their network.</h5>'+
                    '</div>'+

                    '<div class="row">'+
                     '<center class="padding_xs"><button type="button" class="btn padding_xs" onclick="ShareWithLinkedIn('+'\''+qid+'\', \''+title+'\')" style="font-size:20px;">Share via LinkedIn</button></center>'+
                    '</div>'+

                    '<div class="row">'+
                      '<center class="padding_xs"><button type="button" class="btn padding_xs" onclick="ShareWithEmail(\''+qid+'\')" style="font-size:20px;">Share via Email</button></center>'+
                    '</div>'
            
        
       });
      }

           
      function ShareWithEmail(qid){
        var top_text;
        if(currentUser_type != 3){
          top_text = "Write a short message to your colleague, and we will include a unique link so that they can look at the question details.";
        } 
        else{
          top_text = "Write a short message, and we will include a unique link to a question preview.";
        } 
        BootstrapDialog.show({
            type: BootstrapDialog.TYPE_PRIMARY,
            title: "Refer to Colleague",
            message:'<div class="scrollbar" style="height:70vh;">' +
                    '<div class="row">'+
                      '<h5>' + top_text + '</h5>'+
                    '</div>'+

                    '<div class="container-widget" style="padding:10px;">'+
                        '<div class="row"><p>Your Name: '+currentUser_name + '</p></div>'+
                        '<div class="row"><p>Your Email: '+email + '</p></div>'+

                        '<div style="margin-top:20px">'+
                          '<p>To Name:</p>'+
                          '<input   class="full-width padding_xs" type="text" id="refer-name">'+
                        '</div>'+

                        '<div style="margin-top:20px">'+
                          '<p>To Email:</p>'+
                          '<input   class="full-width padding_xs" type="text" id="refer-email">'+
                        '</div>'+

                        '<div style="margin-top:20px">'+
                          '<p>Personal Message:</p>'+
                          '<textarea class="scrollbar full-width padding_xs border1234 textview" type="text" id="refer-message"></textarea>'+
                        '</div>'+
                    '</div>'+
                    '</div>',
              buttons: [{
                  label: 'SEND',
                  cssClass: "ob pull-right",
                  icon: 'glyphicon glyphicon-chevron-right',
                  autospin: true,                
                  action: function(dialogRef) {  
                    dialog = dialogRef;
                    SendReferEmail(qid);
                  }

              },
              {
                  label: 'Cancel',
                  cssClass: 'rb pull-left',
                  icon: 'glyphicon glyphicon-remove',
                  autospin: true,                
                  action: function(dialogRef) {  
                    dialogRef.close();
                  }

              }]
        
       });
      }

      function SendReferEmail(qid){
        var dname = $("#refer-name").val();
        var demail = $("#refer-email").val();
        var dmessage = $("#refer-message").val();
        if(dname === "" || demail === "" || dmessage === ""){
          alert('All fields are required.');
          return;
        } 
        $.ajax({
           url: site_url + 'questions/refer',
           data: {
              fname: currentUser_name,
              femail: email,
              tname: dname,
              temail: demail,
              tmessage: dmessage,
              qid: qid
           },
           success: function(data) {
              alert("Email was sent successfully!");
              dialog.close();
           },
           type: 'POST'
        });  
      }

     

</script>

<div class="container-widget question-container canvas" style="height:100vh;margin-top:70px;padding:0px 10px;">
    


<div class="desktop-visible-item">

      <div class="row white_back" style="padding:10px 20px 0px 20px;margin:-10px;">
        <h3 class="pull-left gray-text">QUESTIONS</h3>     
        <div class="pull-right">
          <button type="button" id="question-add-button" onclick="FirstQuestion(<?= $current_id ?>)" class = "btn"><span class="glyphicon glyphicon-plus-sign btn-icon"></span>ADD A QUESTION</button>
        </div>
      </div>

      <div class="row col-text">
        <ul class="nav nav-tabs border1234">
          <li class="question_filter_tab active" id="tab-all"><a onclick="filter_All()"><p>All</p></a></li>
          <li class="question_filter_tab" id="tab-submitted"><a onclick="filter_Submitted()"><p>Submitted</p></a></li>
          <li class="question_filter_tab" id="tab-routed"><a onclick="filter_Routed()"><p>Routed</p></a></li>
          <li class="question_filter_tab" id="tab-accepted"><a onclick="filter_Accepted()"><p>Accepted</p></a></li>
          <li class="question_filter_tab" id="tab-launched"><a onclick="filter_Launched()"><p>Launched</p></a></li>
          <?php if($u_type != 3){ ?>
            <li class="question_filter_tab" id="tab-waiting"><a onclick="filter_Waiting()"><p>Advisor Waiting</p></a></li>
          <?php } ?>
        </ul>
      </div>
        
</div>

<div class="mobile-visible-item">
      

      <div class="row white_back" style="padding:10px 20px 0px 20px;margin:-10px;">
        <h3 class="row gray-text mid-text">QUESTIONS</h3>
       
        <div class="row mid-text">
          <div class="col-xs-5" style="padding:5px;">
            <div class="row mid-text">
              <select class="no-border-input full-width" id="question_sortby">
                <option select value="All">All</option>
                <option value="Submitted">Submitted</option>
                <option value="Routed">Routed</option>
                <option value="Accepted">Accepted</option>
                <option value="Launched">Launched</option>
                <?php if($u_type != 3){ ?>
                  <option value="Advisor Waiting">Advisor Waiting</option>
                <?php } ?>
              </select>
            </div>  
          </div>

          <div class="col-xs-7">
            <button type="button" id="question-add-button" onclick="FirstQuestion(<?= $current_id ?>)" class = "btn"><span class="glyphicon glyphicon-plus-sign btn-icon"></span>ADD A QUESTION</button>
          </div>
        </div>
        
      </div>

      
</div>

<div class="row border1234 white_back padding_xs col-text">
  <div class="col-sm-9 col-xs-12 canvas">
    <img class="pull-left explore-section-image" src="<?= asset_base_url().'/images/lamp.png' ?>" width="70" height="70">
    <div class="explore-section">
      <h5 class="col-xs-12 BT" style="margin:7px 0px;">
        Not sure where to start? Need some question suggestions?
      </h5>
    </div>
    <div class="explore-section">
        Jog your memory and conduct a self-assessment on your business with our list of common and critical business questions. You can also browse our list of featured Relayy advisors.
    </div>  
  </div>
  <div class="col-sm-3 col-xs-12">
    <a href="http://relayy.io/explore" target="_blank"><button class="ob pull-right col-text question-explore-button">EXPLORE QUESTIONS & EXPERTS</button></a>
  </div>
</div>



  <?php if($u_type == 3 && sizeof($array_question) == 0){ ?>
    <div class="padding_sm gray-80">
      <div class="row">
        <center class="padding_sm">
          <img src="<?= asset_base_url().'/images/EmptyStateQ.png' ?>" width="150">
        </center>
        <center>You haven't asked any questions.</center>
        <center>Add a question in less than a minute to start getting top notch advice you can trust.</center>
        <center class="blue-text">Click the blue "Add a Question" button to get started.</center>
        <center class="padding_xs">(An example of a good question is shown below)</center>

      </div>
    </div>

    <div class="padding_sm">
      <img class="full-width desktop-visible-item" src="<?= asset_base_url().'/images/EntrepQpageDetailv2.png'?>">
      <img class="full-width mobile-visible-item" src="<?= asset_base_url().'/images/EntrepQpageMobile.png'?>">
    </div>


    
  <?php } else if($u_type != 2){ ?>
    <p id="search_num_question" style="position:absolute;text-align:center; width:100%; top:250px;z-index:-1;">
    "No questions have been asked yet"
    </p>  
  <?php } ?>

<div id="card-div" class="gray-80">
<?php foreach($array_question as $question){?>
        
            <div class="question-style" data-time="<?= $question['time'] ?>" data-cardid="<?= $question['q_id'] ?>" id="card_<?= $question['q_id'] ?>">
                <!-- if user is Entrepenure , top section is useless -->
                <div class="row padding_xs">
                          <?php if($u_type == 3){ ?>
                              <div class="col-sm-6 col-xs-12">
                                <div class="pull-left">
                                  <a class="" href="<?= site_url("profile/user/".$question['askerid'])?>"> 
                                  <img src="<?= strlen($question['photo'])>0?$question['photo']:asset_base_url().'/images/emp.jpg'?>" width="50" height="50" class="round"/></a>
                                </div>
                                <div class="pull-left padding_xs">
                                  <p style="color:#444; font-size:20px"><?= $question['fname']?>  <?= $question['lname']?></p>
                                  <p><?php echo $question['bio']?$question['bio']:"None" ?></p>
                                </div>
                              </div>
                              
                          
                              <div class="col-sm-6 col-xs-12">
                                <div class="col-sm-3 col-xs-6 pull-right">
                                  <center>

                                  <span><button type="button" data-waiter-num = "<?php echo sizeof(json_decode($question['w_ids'])) ?>" class="ob full-width btn-question-state" disabled="true">
                                  <?php if($question['state'] == 1) echo "SUBMITTED";
                                        else if($question['state'] == 2) echo "ROUTED";
                                        else if($question['state'] == 3) echo "ACCEPTED";
                                        else echo "LAUNCHED";
                                  ?>
                                  </button> </span>

                                  </center>
                                </div>

                                <div class="dropdown col-sm-3 col-xs-6 pull-right">
                                  <center>
                                    <button class="btn full-width"  style="height:36px;">ACTIONS</button>
                                    <div class="dropdown-content">
                                        <a onclick="referToColleague('<?= $question['q_id']?>', '<?= $question['title']?>')">Refer Question</a>
                                        <a onclick="deleteQuestion('<?= $question['q_id']?>')">Delete</a>
                                    </div>
                                  </center>
                            
                                </div>


                              </div>
                          <?php } else {?>
                          <div class="col-lg-6 col-xs-12">
                            <div class="pull-left">
                              <a class="" href="<?= site_url("profile/user/".$question['askerid'])?>"> 
                              <img src="<?= strlen($question['photo'])>0?$question['photo']:asset_base_url().'/images/emp.jpg'?>" width="50" height="50" class="round"/></a>
                            </div>
                            <div class="pull-left padding_xs">
                              <p class="BT gray-80 font-20"><?= $question['fname']?>  <?= $question['lname']?></p>
                              <p><?php echo $question['bio']?$question['bio']:"None" ?></p>
                            </div>
                          </div>
                          
                      
                      
                          <div class="col-lg-6 col-xs-12 pull-right">
<!-- --------------------------------------------------------------  Question State Button -------------------------------------- -->
                            <div class="col-sm-3 col-xs-6 pull-right">
                              <center>

                              <span><button type="button" data-waiter-num = "<?php echo sizeof(json_decode($question['w_ids'])) ?>" class="ob full-width btn-question-state" disabled="true">
                              <?php if($question['state'] == 1) echo "SUBMITTED";
                                    else if($question['state'] == 2) echo "ROUTED";
                                    else if($question['state'] == 3) echo "ACCEPTED";
                                    else echo "LAUNCHED";
                              ?>
                              </button> </span>

                              </center>
                            </div>

<!-- --------------------------------------------------------------  Action Button -------------------------------------- -->

                            
                              <div class="dropdown col-sm-3 col-xs-6 pull-right">
                                <center>
                                  <button class="btn full-width"  style="height:36px;">ACTIONS</button>
                                  <div class="dropdown-content">
                                    
                                      <a onclick="RouteQuestion(<?= $question['q_id']?>)">Route</a>
                                                                      
                                      <?php if(($u_type == 1 || $u_type == 4) && sizeof(json_decode($question['a_ids'])) > 0) {?>
                                        <a onclick="CreateTeamUp(<?= $question['q_id']?>)">Create TeamUps</a>
                                      <?php }?>  
                                    
                                      <?php if($u_type == 4){?>
                                        <a class="togglePostState" data-id="<?= $question['q_id'] ?>" data-state = "<?= $question['post'] ?>">BroadCast</a>
                                      <?php }?> 

                                      <a onclick="referToColleague('<?= $question['q_id']?>', '<?= $question['title']?>')">Refer Question</a>

                                      <?php if($question['state'] != 4){?>
                                        <a onclick="deleteQuestion('<?= $question['q_id']?>')">Delete</a>
                                      <?php } ?>
                                  </div>
                                </center>
                          
                              </div>

<!-- --------------------------------------------------------------  Route List Button -------------------------------------- -->

                            
                                <?php if(sizeof(json_decode($question['r_ids'])) > 0) { ?>
                                <div class="col-sm-3 col-xs-6 pull-right">
                                  <center>
                                    <span><button type="button" class="btn font-10 full-width" style="height:36px;" onclick='viewRouteList(<?= $question['q_id']?>, "<?= $question['title']?>", "<?php echo implode(" ",json_decode($question['r_ids'])); ?>")'>ROUTE LIST (<?php echo sizeof(json_decode($question['r_ids'])) ?>)</button></span>
                                  </center>
                                </div>
                                <?php } ?>
                           


<!-- --------------------------------------------------------------  Waiting Button -------------------------------------- -->
                            
                            <?php if(sizeof(json_decode($question['w_ids'])) > 0) { ?>
                            <div class="col-sm-3 col-xs-6 pull-right">
                              <center>
                                  <span><button type="button" class="btn-waiting-state font-10 waiting-button ob full-width" <?php echo sizeof(json_decode($question['w_ids'])) > 0?"":"disabled" ?> onclick='viewWaitingList(<?= $question['q_id']?>, "<?= $question['title']?>", "<?php echo implode(' ',json_decode($question['w_ids'])); ?>")' style="height:36px;">WAITING ADVISORS (<?php echo sizeof(json_decode($question['w_ids'])) ?>)</button></span>
                              </center>
                            </div>
                            <?php } ?>
                          </div>
                        <?php } ?>

                </div>

                <div class="row question-about padding_sm">
                  <div class="row">
                    <h4 class="desktop-visible-item BT gray-80" style="margin:0px;"><?= $question['title']?></h4>
                    <h5 class="mobile-visible-item BT gray-80" style="margin:0px;"><?= $question['title']?></h5>
                  </div>
                  
                  <?php $Stitle = 'style_'.$question['q_id']; ?>
                  <div class="<?= $Stitle ?> row" style="display:none;">
                    <div class="row">
                    <h6 class="Qinput gray-80"><?= $question['context']?></h6>
                    </div>
                    
                    <div class="row">
                    <h5 class="gray-80">TAGS</h5>
                      <?php foreach(json_decode($question['tags']) as $tag) { ?>
                        <div class="online_tags Qinput pull-left <?php echo strlen($tag)>25?'wrapword':'' ?>"><?= $tag?></div>
                      <?php } ?>
                    </div>
                    
                    <div class="row">
                      <h5 class="gray-80">Links attached</h5>
                      <?php foreach(json_decode($question['links']) as $link) { ?>
                        <a target="_blank" href="<?= $link?>" class="<?php echo strlen($link)>25?'wrapword':'' ?>"><?= $link?></a>
                      <?php } ?>
                    </div>
                    
                    <div class="row">
                      <h5 class="gray-80">Files Attached</h5>
                      <?php if($question['filename'] === 'No file'){ ?>
                        <h6> No file </h6>
                      <?php } else { ?>
                        <?php foreach(json_decode($question['filename']) as $file) {
                          $spl=explode('.',$file);
                          $file_ext = $spl[count($spl) - 1];
                          $expensions= array("jpeg", "jpg", "png", "PNG", "JPG", "JPEG");
                         ?>
                            <div class="pull-left preview-Img canvas image-Item">
                            <?php if(in_array($file_ext, $expensions)=== true){ ?>
                              <img class="preview-Img attached-Img" src="<?= uploads_base_url().$file?>" alt="<?= $file ?>"/>
                              <button type="button" class="expand-Img trans" style="display:none;"><span class="glyphicon glyphicon-zoom-in"></span></button>
                              <a href="<?= uploads_base_url().$file?>" target="_blank" download="download" class="def-text">
                                <button type="button" class="download-Img trans" style="display:none;"><span class="glyphicon glyphicon-download-alt"></span></button>
                              </a>
                            <?php } else if($file_ext == "pdf") { ?>
                              <img class="preview-Img attached-Img" src="<?= asset_base_url().'/images/pdf.png' ?>" alt="<?= $file ?>"/>
                              <a href="<?= uploads_base_url().$file?>" target="_blank" download="download" class="def-text">
                                <button type="button" class="download-Img trans" style="display:none;"><span class="glyphicon glyphicon-download-alt"></span></button>
                              </a>
                            <?php } else if($file_ext == "gif") { ?>
                              <img class="preview-Img attached-Img" src="<?= asset_base_url().'/images/gif.png' ?>" alt="<?= $file ?>"/>
                              <a href="<?= uploads_base_url().$file?>" target="_blank" download="download" class="def-text">
                                <button type="button" class="download-Img trans" style="display:none;"><span class="glyphicon glyphicon-download-alt"></span></button>
                              </a>
                            <?php } else { ?>
                              <img class="preview-Img attached-Img" src="<?= asset_base_url().'/images/file.png' ?>" alt="<?= $file ?>"/>
                              <a href="<?= uploads_base_url().$file?>" target="_blank" download="download" class="def-text">
                                <button type="button" class="download-Img trans" style="display:none;"><span class="glyphicon glyphicon-download-alt"></span></button>
                              </a>
                            <?php } ?>
                              
                            </div>
                        <?php } ?>
                      <?php } ?>
                    </div>
                  </div>



                  <div class="col-xs-12" style="padding:10px 20px;">
                    <button type="button" class="desktop-visible-item btn pull-left" style="margin-top:5px;" id="<?= 'title_style_'.$question['q_id'] ?>" onclick="callme('<?= 'style_'.$question['q_id'] ?>')">VIEW DETAILS</button>
                    <button type="button" class="mobile-visible-item col-xs-12 btn pull-left" style="margin-top:5px;" id="<?= 'title_style_'.$question['q_id'] ?>" onclick="callme('<?= 'style_'.$question['q_id'] ?>')">VIEW DETAILS</button>
                    <p class="desktop-visible-item col-md-6 col-sm-6 col-xs-12 pull-right" style="margin-top:5px; text-align:right;"><?= date("D M d, Y h:i A", $question['time']) ?></p>
                    <p class="mobile-visible-item col-md-6 col-sm-6 col-xs-12 pull-right" style="margin-top:5px; text-align:center;"><?= date("D M d, Y h:i A", $question['time']) ?></p>
                  </div>                  

                </div>

                <div class="row question-about padding_xs">
                  <span class="pull-left"><h6 class="gray-80">ADVISORS ANSWERING:</h6></span>
                  <div class="pull-left">
                    <?php if($question['a_ids']) {?>
                      <?php foreach(json_decode($question['a_ids']) as $router){?>
                        <?php foreach($advisors as $advisor){?>
                          <?php if($router === $advisor['id']){?>     
                            <a class="" href="<?= site_url("profile/user/".$advisor['id'])?>" title="<?= $advisor['fname'] ?> <?= $advisor['lname'] ?>">                   
                            <img  style="vertical-align:middle;" class="round accepter-Img" width="40" height="40" src="<?= strlen($advisor['photo'])>0?$advisor['photo']:asset_base_url().'/images/emp.jpg'?>" /></a>
                          <?php }?>
                        <?php }?>                     
                      <?php }?>  
                    <?php }?>
                  </div>       
                </div>
            </div>

        
<?php } ?>
</div>

<div class="last_div" style="height:30px;">

<div>
</div>


<script>
  
  $(".togglePostState").click(function(e){
      var que = $(this);
      BootstrapDialog.show({
          type: BootstrapDialog.TYPE_PRIMARY,
          title: 'Warning',
          message: 'Are you sure you want to post this question to the entire Relayy advisor network?',
          buttons: [{
              label: 'Cancel',
              cssClass: 'btn-primary',
              action: function(dialogRef) {  
                  dialogRef.close();
              }
          },{
              label: 'Yes',
              cssClass: 'btn-primary',
              action: function(dialogRef) {  
                  var id = que.attr("data-id");
                  var state = que.attr("data-state");
                  if(state === "private"){
                    // $(this).find("img").attr("src", site_url+'assets/images/public.png');
                    // $(this).attr("data-state", "public");
                    // $(this).find("a").attr("title", "post to your Group");
                    changePostState(id, "public");
                    $("#card_"+id).remove();
                    dialogRef.close();
                  } 
                  
              }
          }]
    });

    

  })

  function changePostState(id, state){
    $.ajax({
       url: site_url + 'questions/changePost',
       data: {
          id: id,
          post: state
       },
       success: function(data) {
       },
       type: 'POST'
    });      
  }

  $("#question_sortby").change(function(){

    if($("#question_sortby").val() === "All") filter_All();
    else if($("#question_sortby").val() === "Submitted") filter_Submitted();
    else if($("#question_sortby").val() === "Routed") filter_Routed();
    else if($("#question_sortby").val() === "Accepted") filter_Accepted();
    else if($("#question_sortby").val() === "Launched") filter_Launched();
    else filter_Waiting();
  });

  $('#card-div').find('.question-style').sort(function (a, b) {
     return $(b).attr('data-time') - $(a).attr('data-time');
  })
  .appendTo('#card-div');

   






</script>
