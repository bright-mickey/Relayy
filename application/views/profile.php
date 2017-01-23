<script src="<?php echo asset_base_url()?>/js/jquery.ui.widget.js"></script>
<script type="text/javascript">
    var inSkill = '<?php echo $skill ?>';
    var inLooking = '<?php echo $looking ?>';
    var inInteresting ='<?php echo $interesting ?>';
    var position = '<?php echo $position ?>';
    var education = '<?php echo $education ?>';
    var array_skill = [];
    var array_looking = [];
    var array_interesting = [];
    var group_image_name = "<?php echo isset($group_image_name) && strlen($group_image_name)<40?$group_image_name:"" ?>";
    var group_name = "<?php echo $group_name ?>";
    var group_image = group_image_name;

    // Setup an event listener to make an API call once auth is complete

    

    // function companyProfile(c_id){
    //     $.ajax({
    //        url: site_url + 'profile/companyProfile',
    //        data: {             
    //           c_id: c_id             
    //        },
    //        success: function(data) {
    //           $(".container-widget").html(data);
    //        },
    //        type: 'POST'
    //     });
    // }

    


  function deleteReview(id){
    BootstrapDialog.confirm({
          title: 'Confirm',
          message: 'are you sure you want to delete this review?',
          type: BootstrapDialog.TYPE_DANGER,
          closable: true,
          draggable: true,
          btnCancelLabel: 'Cancel',
          btnOKLabel: 'Delete',
          btnOKClass: 'btn-danger',
          callback: function(result) {
              if(result) {
                  $.ajax({
                 url: site_url + 'profile/deleteReview',
                 data: {             
                    r_id: id             
                 },
                 success: function(data) {
                    $("#" + id).hide();
                 },
                 type: 'POST'
              });

              }
          }
      });
  }
  //=========================  Edit skill ========
  function edit_skill(){
    $("#skill_Editfield").show();
    $("#skill_button").hide();
    $("#li_skill .close").show();
    array_skill = JSON.parse(inSkill); 
    match_sections();  
  }

  function add_skill(){
    var skl = $("#add_skill").val();
    htmlTxt = '<div class="online_tags pull-left" id="li_skill">'+ skl +'<a class="close more-close" onclick="skill_Remove(this)">&times;</a></div>';
    $("#skill_container").append(htmlTxt);
     array_skill.push(skl);
     document.getElementById('add_skill').value="";
     $(".empty_skill").hide();
     saveSkill();
  }

  function skill_Remove(obj) {
    var strTxt = $(obj).parent().text();
    strTxt = strTxt.substring(0,strTxt.length - 1);
    var index = array_skill.indexOf(strTxt);
    array_skill.splice(index, 1);
    $(obj).parent().remove();
    saveSkill();
  }

  function detect_skill(e, object) {
      var key=e.keyCode || e.which;
      if (key==13){
          add_skill();        
      }
  }

  function doneSkill(){
    $("#skill_Editfield").hide();
    $("#skill_button").show();
    $("#li_skill .close").hide();
    match_sections();  
  }

  function saveSkill(){
    
    //save updated skills to database
    document.getElementById('add_skill').value="";
    inSkill = JSON.stringify(array_skill);
    
    $.ajax({
           url: site_url + 'profile/SaveSkill',
           data: {             
              skill: inSkill
           },
           success: function(data) {},
           type: 'POST'
    });
    match_sections();  


  }  

  //=========================  Edit skill End  ========


  //=========================  Edit looking Start  ========

  function edit_looking(){
    $("#looking_Editfield").show();
    $("#looking_button").hide();
    $("#li_looking .close").show();
    array_looking = JSON.parse(inLooking); 
    match_sections();  
  }

  function add_looking(){
    var skl = $("#add_looking").val();
    htmlTxt = '<div class="online_tags pull-left" id="li_looking">'+ skl +'<a class="close more-close" onclick="looking_Remove(this)">&times;</a></div>';
    $("#looking_container").append(htmlTxt);
     array_looking.push(skl);
     document.getElementById('add_looking').value="";
     $(".empty_looking").hide();
     savelooking();
  }

  function looking_Remove(obj) {
    var strTxt = $(obj).parent().text();
    strTxt = strTxt.substring(0,strTxt.length - 1);
    var index = array_looking.indexOf(strTxt);
    array_looking.splice(index, 1);
    $(obj).parent().remove();
    savelooking();
  }

  function detect_looking(e, object) {
      var key=e.keyCode || e.which;
      if (key==13){
          add_looking(); 
      }
  }

  function donelooking(){
    $("#looking_Editfield").hide();
    $("#looking_button").show();
    $("#li_looking .close").hide();
    match_sections();       
  }

  function savelooking(){
    
    //save updated skills to database
    document.getElementById('add_looking').value="";
    inLooking = JSON.stringify(array_looking);
    
    $.ajax({
           url: site_url + 'profile/SaveLooking',
           data: {             
              looking: inLooking
           },
           success: function(data) {},
           type: 'POST'
    });
    match_sections();       


  }  

  //=========================  Edit looking End  ========

  //=========================  Edit interesting Start  ========

  function edit_interesting(){
    $("#interesting_Editfield").show();
    $("#interesting_button").hide();
    $("#li_interesting .close").show();
    array_interesting = JSON.parse(inInteresting); 
    match_sections();  
  }

  function add_interesting(){
    var skl = $("#add_interesting").val();
    htmlTxt = '<div class="online_tags pull-left" id="li_interesting">'+ skl +'<a class="close more-close" onclick="interesting_Remove(this)">&times;</a></div>';
    $("#interesting_container").append(htmlTxt);
     array_interesting.push(skl);
     document.getElementById('add_interesting').value="";
     $(".empty_interesting").hide();
     saveinteresting();

  }

  function interesting_Remove(obj) {
    var strTxt = $(obj).parent().text();
    strTxt = strTxt.substring(0,strTxt.length - 1);
    var index = array_interesting.indexOf(strTxt);
    array_interesting.splice(index, 1);
    $(obj).parent().remove();
    saveinteresting();
  }

  function detect_interesting(e, object) {
      var key=e.keyCode || e.which;
      if (key==13){
          add_interesting();        
      }
  }

  function doneinteresting(){
    $("#interesting_Editfield").hide();
    $("#interesting_button").show();
    $("#li_interesting .close").hide();
    match_sections();       
  }

  function saveinteresting(){
    
    //save updated skills to database
    document.getElementById('add_interesting').value="";
    inInteresting = JSON.stringify(array_interesting);
    
    $.ajax({
           url: site_url + 'profile/SaveInteresting',
           data: {             
              interesting: inInteresting
           },
           success: function(data) {},
           type: 'POST'
    });
    match_sections();       


  }  

  //=========================  Edit interesting End  ========



  

  

  function edit_position(){

    var m_array=["","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December",];
    var htm_start_year = '<select class="full-width pull-left" id="psy">';
    var CYear = new Date().getFullYear() ;
    for(var y=CYear; y > 1930; y--){
      htm_start_year +='<option>'+ y +'</option>';
    }
    htm_start_year += '</select>';

    var htm_start_month = '<select class="full-width pull-left" id="psm">';//psm- position start year
    for(var index=1; index <= 12; index++){
      htm_start_month +='<option>'+ m_array[index] +'</option>';
    }
    htm_start_month += '</select>';

    var htm_end_year = '<select class="full-width pull-left" id="pey">';
    var CYear = new Date().getFullYear() ;
    for(var y=CYear; y > 1930; y--){
      htm_end_year +='<option>'+ y +'</option>';
    }
    htm_end_year += '</select>';

    var htm_end_month = '<select class="full-width pull-left" id="pem">';
    for(var index=1; index <= 12; index++){
      htm_end_month +='<option>'+ m_array[index] +'</option>';
    }
    htm_end_month += '</select>';
    

    BootstrapDialog.show({
        type: BootstrapDialog.TYPE_PRIMARY,
        title: "Edit Position",
        message: '<div class="container-widget" style="padding:20px;">'+
                    '<div class="row">'+
                      '<h5>Description:</h5>'+
                      '<textarea class="Qinput border1234 textview" id="position" maxlength="1000" placeholder="Type here..." ></textarea>'+
                    '</div>'+
                  '</div>',
        buttons: [{
            label: 'Cancel',                          
            action: function(dialogRef) { 
                dialogRef.close();
            }
        },{
            label: 'Add',
            cssClass: 'btn-primary',
            icon: 'glyphicon glyphicon-chevron-right',
            autospin: true,                
            action: function(dialogRef) {
              var p_array =[];
              if(position) p_array = JSON.parse(position);
              p_array.push($("#position").val().replace("\n", ""));
              //$("#position-field").append('<div class="col-xs-12 div-item"><span class="sender-pic glyphicon glyphicon-play"></span>' + $("#position").val() + '<span class="line-item glyphicon glyphicon-trash" onclick="deletePos(event, this)"></span></div>');
              $("#position-field").append('<div class="col-xs-12">'+
                                          '<div class="col-xs-1" style="padding:5px;">'+
                                            '<img class="pull-left" src="'+site_url+'/assets/images/list-disc.png'+'">'+
                                          '</div>'+
                                          '<div class="col-xs-11">'+
                                            $("#position").val()+'<span class="line-item glyphicon glyphicon-trash" onclick="deletePos(event, this)"></span>'+
                                          '</div>'+
                                        '</div>');
              match_sections();
              var prep = JSON.stringify(p_array);
              $.ajax({
                     url: site_url + 'profile/SavePosition',
                     data: {             
                        position: prep
                     },
                     success: function(data) {
                        position = prep;
                        dialogRef.close();
                     },
                     type: 'POST'
              });

            }
        }]
    });


  }

  function edit_education(){
    var htm_start_year = '<select class="full-width pull-left" id="esy">';
    var CYear = new Date().getFullYear() ;
    for(var y=CYear; y > 1930; y--){
      htm_start_year +='<option>'+ y +'</option>';
    }
    htm_start_year += '</select>';

    var htm_end_year = '<select class="full-width pull-left" id="eey">';
    var CYear = new Date().getFullYear() ;
    for(var y=CYear; y > 1930; y--){
      htm_end_year +='<option>'+ y +'</option>';
    }
    htm_end_year += '</select>';

    

    BootstrapDialog.show({
        type: BootstrapDialog.TYPE_PRIMARY,
        title: "Edit Education",
        message: '<div class="container-widget" style="padding:20px;">'+
                    '<div class="row">'+
                      '<h5>Description:</h5>'+
                      '<textarea class="Qinput border1234 textview" id="education" maxlength="1000" placeholder="Type here..." ></textarea>'+
                    '</div>'+
                  '</div>',
        buttons: [{
            label: 'Cancel',                          
            action: function(dialogRef) { 
                dialogRef.close();
            }
        },{
            label: 'Add',
            cssClass: 'btn-primary',
            icon: 'glyphicon glyphicon-chevron-right',
            autospin: true,                
            action: function(dialogRef) {
              var e_array =[];
              if(education) e_array = JSON.parse(education);
              e_array.push($("#education").val().replace("\n", ""));
              //$("#education-field").append('<div class="col-xs-12 div-item"><span class="sender-pic glyphicon glyphicon-play"></span>' + $("#education").val() + '<span class="line-item glyphicon glyphicon-trash" onclick="deleteEdu(event, this)"></span></div>');
              $("#education-field").append('<div class="col-xs-12">'+
                                            '<div class="col-xs-1" style="padding:5px;">'+
                                              '<img class="pull-left" src="'+site_url+'/assets/images/list-disc.png'+'">'+
                                            '</div>'+
                                            '<div class="col-xs-11">'+
                                              $("#education").val()+'<span class="line-item glyphicon glyphicon-trash" onclick="deleteEdu(event, this)"></span>'+
                                            '</div>'+
                                          '</div>');
              match_sections();
              var pree = JSON.stringify(e_array);
              $.ajax({
                     url: site_url + 'profile/SaveEducation',
                     data: {             
                        education: pree
                     },
                     success: function(data) {
                        education = pree;
                        dialogRef.close();
                     },
                     type: 'POST'
              });

            }
        }]
    });

  }

  function edit_company_name(){
    var name;
    if($("#companyName").text() === '-') name = "";
    else name = $("#companyName").text();
    BootstrapDialog.show({
        type: BootstrapDialog.TYPE_PRIMARY,
        title: "Edit Company Name",
        message: '<div class="container-widget" style="padding:20px;">'+
                    '<div class="row">'+
                      '<h5>Company Name:</h5>'+
                      '<textarea class="Qinput border1234 textview" id="company_name" maxlength="128" placeholder="Type here..." >'+ name + '</textarea>'+
                    '</div>'+
                  '</div>',
        buttons: [{
            label: 'Cancel',                          
            action: function(dialogRef) { 
                dialogRef.close();
            }
        },{
            label: 'Save',
            cssClass: 'btn-primary',
            icon: 'glyphicon glyphicon-chevron-right',
            autospin: true,                
            action: function(dialogRef) {
              
              $.ajax({
                     url: site_url + 'profile/SaveCompanyInfo',
                     data: {             
                        category: "c_name",
                        value: $("#company_name").val()
                     },
                     success: function(data) {
                        $("#companyName").text($("#company_name").val());
                        dialogRef.close();
                     },
                     type: 'POST'
              });

            }
        }]
    });
  }

  function edit_company_location(){
    var name;
    if($("#companyLocation").text() === '-') name = "";
    else name = $("#companyLocation").text();
    BootstrapDialog.show({
        type: BootstrapDialog.TYPE_PRIMARY,
        title: "Edit Company Location",
        message: '<div class="container-widget" style="padding:20px;">'+
                    '<div class="row">'+
                      '<h5>Company Location:</h5>'+
                      '<textarea class="Qinput border1234 textview" id="company_location" maxlength="128" placeholder="Type here..." >'+ name + '</textarea>'+
                    '</div>'+
                  '</div>',
        buttons: [{
            label: 'Cancel',                          
            action: function(dialogRef) { 
                dialogRef.close();
            }
        },{
            label: 'Save',
            cssClass: 'btn-primary',
            icon: 'glyphicon glyphicon-chevron-right',
            autospin: true,                
            action: function(dialogRef) {
              
              $.ajax({
                     url: site_url + 'profile/SaveCompanyInfo',
                     data: {             
                        category: "c_location",
                        value: $("#company_location").val()
                     },
                     success: function(data) {
                        $("#companyLocation").text($("#company_location").val());
                        dialogRef.close();
                     },
                     type: 'POST'
              });

            }
        }]
    });
  }

  function edit_company_summary(){
    var name;
    if($("#companySummary").text() === '-') name = "";
    else name = $("#companySummary").text();
    BootstrapDialog.show({
        type: BootstrapDialog.TYPE_PRIMARY,
        title: "Edit Company Summary",
        message: '<div class="container-widget" style="padding:20px;">'+
                    '<div class="row">'+
                      '<h5>Company Summary:</h5>'+
                      '<textarea class="Qinput border1234 textview" id="company_summary" maxlength="1024" placeholder="Type here..." >'+ name + '</textarea>'+
                    '</div>'+
                  '</div>',
        buttons: [{
            label: 'Cancel',                          
            action: function(dialogRef) { 
                dialogRef.close();
            }
        },{
            label: 'Save',
            cssClass: 'btn-primary',
            icon: 'glyphicon glyphicon-chevron-right',
            autospin: true,                
            action: function(dialogRef) {
              
              $.ajax({
                     url: site_url + 'profile/SaveCompanyInfo',
                     data: {             
                        category: "c_summary",
                        value: $("#company_summary").val()
                     },
                     success: function(data) {
                        $("#companySummary").text($("#company_summary").val());
                        dialogRef.close();
                     },
                     type: 'POST'
              });

            }
        }]
    });
  }

  function deletePos(e, obj){
    var p_array = JSON.parse(position);
    var index = p_array.indexOf($(obj).parent().text());
    p_array.splice(index, 1);
    $(obj).parent().parent().remove();
    var prep = JSON.stringify(p_array);
    $.ajax({
             url: site_url + 'profile/SavePosition',
             data: {             
                position: prep
             },
             success: function(data) {
                position = prep;
             },
             type: 'POST'
      });
  }

  function deleteEdu(e, obj){
    var e_array = JSON.parse(education);
    var index = e_array.indexOf($(obj).parent().text());
    e_array.splice(index, 1);
    $(obj).parent().parent().remove();
    var pree = JSON.stringify(e_array);
    $.ajax({
             url: site_url + 'profile/SaveEducation',
             data: {             
                education: pree
             },
             success: function(data) {
                education = pree;
             },
             type: 'POST'
      });
  }

  var title;
  var text;

  function edit_link(){
    BootstrapDialog.show({
        type: BootstrapDialog.TYPE_PRIMARY,
        title: "Input Link Information",
        message: '<div id="channel_edit" class="container-widget padding_md" >'+
                    '<label class="d-label">'+
                      '<h5 style="margin-left:10px;">ADD WEBSITE LINK</h5>'+
                      '<input   class="Qinput" type="text" placeholder="Paste in a web link and hit Enter" id="link-text">'+
                    '</label>'+
                  '</div>',
        buttons: [{
            label: 'Cancel',                          
            action: function(dialogRef) {  
                dialogRef.close();
            }
        },{
            label: 'Add',
            cssClass: 'btn-primary',
            icon: 'glyphicon glyphicon-chevron-right',
            autospin: true,                
            action: function(dialogRef) {  
                text = $("#link-text").val();
                if(text.indexOf('http://') < 0 && text.indexOf('https://') < 0){
                  alert('You must paste in a web link');
                  return;
                }
                $.ajax({
                                     url: site_url + 'profile/addLink',
                                     data: {             
                                        text:  text, 
                                        title: "",
                                        fname: ""
                                     },
                                     success: function(data) {
                                        location.reload();
                                        dialogRef.close();
                                     },
                                     type: 'POST'
                              });
              
            }
        }]
    });
  }

  function uploadImage(obj) { 
    // select the form and submit
    var data = new FormData($(obj).parent()[0]);
    $(obj).parent().append('<p class="pull-right">Uploading...</p>');   
        
        $.ajax({
                 type:"POST",
                 url:site_url + 'questions/fileupload',
                 data:data,
                 mimeType: "multipart/form-data",
                  contentType: false,
                  cache: false,
                  processData: false,
                  success:function(data)
                  {
                        $(obj).parent().find('p').remove();
                        if(data.indexOf("Following files are allowed") > -1){//check error
                          alert(data);
                          return;
                        }
                        group_image = data;
                        getHtmlfromFileName(data);
                  }
    }); 
  }

  function getHtmlfromFileName(file){
      
      var spary = file.split(".");
      var ext = spary[spary.length - 1] ;
      imageExts = ["png", "jpg", "jpeg", "PNG", "JPG", "JPEG"];
      if(imageExts.indexOf(ext)>=0){
        $("#group-preview-img").attr("src", uploads_base_url+file);
      }
      else if(ext === "pdf"){         
        $("#group-preview-img").attr("src", site_url + 'assets/images/pdf.png');  
      }
      else if(ext === "gif"){
        $("#group-preview-img").attr("src", site_url + "assets/images/gif.png");
      }
      else{
        $("#group-preview-img").attr("src", site_url + "assets/images/file.png"); 
      }
  }

  

  function deleteLink(obj){
    var link = $(obj).parent().find(".link-txt").text();
    BootstrapDialog.show({
        message: "are you sure you want to delete this link ?",
        type: BootstrapDialog.TYPE_DANGER,
        buttons: [{
            label: 'Delete',
            cssClass: 'btn-danger',
            autospin: true,
            action: function(dialogRef){
                $.ajax({
                   url: site_url + 'profile/deleteLink',
                   data: {             
                      link: link
                   },
                   success: function(data) {
                      console.log(data);
                      $(obj).parent().remove();
                      dialogRef.close();
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

  
  function edit_name(){
    var name = $("#venture_name").text();
    if(name.indexOf('(please edit') > -1) name="";
    BootstrapDialog.confirm({
            title: 'Edit Current Venture Name',
            message:  '<span>Name:</span>'+
                      '<input class="edit_input border-style-xs" type="text" id="dialog_name" value="'+name+'">',
            type: BootstrapDialog.TYPE_PRIMARY,
            closable: true,
            draggable: true,
            btnCancelLabel: 'Cancel',
            btnOKLabel: 'Save',
            btnOKClass: 'btn-danger',
            callback: function(result) {
                var txt = $("#dialog_name").val();
                if(result) {
                    $.ajax({
                       url: site_url + 'profile/updateVentureInfo',
                       data: {   
                          category: 'venture_name',          
                          data: txt            
                       },
                       success: function(data) {
                          $("#venture_name").text(txt);
                       },
                       type: 'POST'
                    });

                }
            }
        });
  }

function edit_summary(){
  var summary = $("#summary").text();
  if(summary.indexOf('(please edit') > -1) summary="";
  BootstrapDialog.confirm({
          title: 'Edit Summary',
          message:  '<div>Summary:</div>'+
                    '<textarea class="full-width border-style-xs" type="text" id="dialog_summary">'+ summary +'</textarea>',
          type: BootstrapDialog.TYPE_PRIMARY,
          closable: true,
          draggable: true,
          btnCancelLabel: 'Cancel',
          btnOKLabel: 'Save',
          btnOKClass: 'btn-danger',
          callback: function(result) {
              var txt = $("#dialog_summary").val();
              if(result) {
                  $.ajax({
                     url: site_url + 'profile/updateVentureInfo',
                     data: {   
                        category: 'summary',          
                        data: txt            
                     },
                     success: function(data) {
                        $("#summary").text(txt);
                     },
                     type: 'POST'
                  });

              }
          }
      });
}

function edit_industry(){
  var industry = $("#industry").text();
  if(industry.indexOf('(please edit') > -1) industry="";
  BootstrapDialog.confirm({
          title: 'Edit Industry',
          message:  '<span>Industry:</span>'+
                    '<input class="edit_input border-style-xs" type="text" id="dialog_industry" value="'+industry+'">',
          type: BootstrapDialog.TYPE_PRIMARY,
          closable: true,
          draggable: true,
          btnCancelLabel: 'Cancel',
          btnOKLabel: 'Save',
          btnOKClass: 'btn-danger',
          callback: function(result) {
              var txt = $("#dialog_industry").val();
              if(result) {
                  $.ajax({
                     url: site_url + 'profile/updateVentureInfo',
                     data: {   
                        category: 'industry',          
                        data: txt            
                     },
                     success: function(data) {
                        $("#industry").text(txt);
                     },
                     type: 'POST'
                  });

              }
          }
      });
}

function edit_stage(){
  var stage = $("#stage").text();
  if(stage.indexOf('(please edit') > -1) stage="";
  var htm = '<span>Business stage:</span>'+
    '<select class="edit_input" name="thelist" id="select_stage">';
      if(stage === 'idea') htm += '<option selected>idea</option>';
      else htm+= '<option>idea</option>';
      if(stage === 'startup') htm += '<option selected>startup</option>';
      else htm+= '<option>startup</option>';
      if(stage === 'growth') htm += '<option selected>growth</option>';
      else htm+= '<option>growth</option>';
      if(stage === 'established') htm += '<option selected>established</option>';
      else htm+= '<option>established</option>';

  htm += '</select>';
  BootstrapDialog.confirm({
          title: 'Edit Business Stage',
          message:  htm,
          type: BootstrapDialog.TYPE_PRIMARY,
          closable: true,
          draggable: true,
          btnCancelLabel: 'Cancel',
          btnOKLabel: 'Save',
          btnOKClass: 'btn-danger',
          callback: function(result) {
              var txt = $("#select_stage option:selected").text();
              if(result) {
                  $.ajax({
                     url: site_url + 'profile/updateVentureInfo',
                     data: {   
                        category: 'stage',          
                        data: txt            
                     },
                     success: function(data) {
                        $("#stage").text(txt);
                     },
                     type: 'POST'
                  });

              }
          }
      });
}

function edit_employee(){
  var num = $("#employee_num").text();
  if(num.indexOf('(please edit') > -1) num="";
  var htm = '<span>Employees:</span>'+
    '<select class="edit_input" name="thelist" id="select_employee">';
      if(num === 'Less than 10') htm += '<option selected>Less than 10</option>';
      else htm+= '<option>Less than 10</option>';
      if(num === '10 to 50') htm += '<option selected>10 to 50</option>';
      else htm+= '<option>10 to 50</option>';
      if(num === '50 to 200') htm += '<option selected>50 to 200</option>';
      else htm+= '<option>50 to 200</option>';
      if(num === 'More than 200') htm += '<option selected>More than 200</option>';
      else htm+= '<option>More than 200</option>';

  htm += '</select>';
  BootstrapDialog.confirm({
          title: 'Edit Number of Employees',
          message:  htm,
          type: BootstrapDialog.TYPE_PRIMARY,
          closable: true,
          draggable: true,
          btnCancelLabel: 'Cancel',
          btnOKLabel: 'Save',
          btnOKClass: 'btn-danger',
          callback: function(result) {
              var txt = $("#select_employee option:selected").text();
              if(result) {
                  $.ajax({
                     url: site_url + 'profile/updateVentureInfo',
                     data: {   
                        category: 'employee_num',          
                        data: txt            
                     },
                     success: function(data) {
                        $("#employee_num").text(txt);
                     },
                     type: 'POST'
                  });

              }
          }
      });
}

function edit_funding(){
  var funding = $("#funding").text();
  if(funding.indexOf('(please edit') > -1) funding="";
  var htm = '<span>Employees:</span>'+
    '<select class="edit_input" name="thelist" id="select_funding">';
      if(funding === 'self funded') htm += '<option selected>self funded</option>';
      else htm+= '<option>self funded</option>';
      if(funding === 'not yet') htm += '<option selected>not yet</option>';
      else htm+= '<option>not yet</option>';
      if(funding === 'Seed round') htm += '<option selected>Seed round</option>';
      else htm+= '<option>Seed round</option>';
      if(funding === 'Series A') htm += '<option selected>Series A</option>';
      else htm+= '<option>Series A</option>';
      if(funding === 'Series B+') htm += '<option selected>Series B+</option>';
      else htm+= '<option>Series B+</option>';
      if(funding === 'Profitable') htm += '<option selected>Profitable</option>';
      else htm+= '<option>Profitable</option>';

  htm += '</select>';
  BootstrapDialog.confirm({
          title: 'Edit Funding Raised',
          message:  htm,
          type: BootstrapDialog.TYPE_PRIMARY,
          closable: true,
          draggable: true,
          btnCancelLabel: 'Cancel',
          btnOKLabel: 'Save',
          btnOKClass: 'btn-danger',
          callback: function(result) {
              var txt = $("#select_funding option:selected").text();
              if(result) {
                  $.ajax({
                     url: site_url + 'profile/updateVentureInfo',
                     data: {   
                        category: 'funding',          
                        data: txt            
                     },
                     success: function(data) {
                        $("#funding").text(txt);
                     },
                     type: 'POST'
                  });

              }
          }
      });
}

function link_Remove(obj){
  var link = $(obj).parent().find(".link-txt").text();
  alert(link);
}

  function getImage(file){
      var spary = file.split(".");
      var ext = spary[spary.length - 1] ;
      imageExts = ["png", "jpg", "jpeg", "PNG", "JPG", "JPEG"];
      if(imageExts.indexOf(ext)>=0){
        return uploads_base_url+file;
      }
      else if(ext === "pdf"){         
        return site_url + 'assets/images/pdf.png';  
      }
      else if(ext === "gif"){
        return site_url + "assets/images/gif.png";
      }
      else{
        return site_url + "assets/images/file.png"; 
      }
  }

  function edit_group(){
    group_name = $("#group_membership").text();
    BootstrapDialog.show({
        type: BootstrapDialog.TYPE_PRIMARY,
        title: "Input Link Information",
        message: '<div id="channel_edit" class="container-widget padding_md" >'+
                    '<div class="row">'+
                      '<h5>Group Name</h5>'+
                      '<input class="Qinput full-width padding_xs" type="text" value="'+group_name+'" id="group_name">'+
                    '</div>'+
                    '<div class="row">'+
                      '<h5>Group Image</h5>'+
                      '<form action="" id="group-img" method="POST" enctype="multipart/form-data">'+
                         '<input type="file" class="Qinput full-width" name="FileName" onchange="uploadImage(this)"/>'+                       
                      '</form>'+
                      '<img class="" id="group-preview-img" src="'+getImage(group_image_name)+'">'+
                      '<p id="image_name">'+group_image_name+'</p>'+
                    '</div>'+                   
                  '</div>',
        buttons: [{
            label: 'Delete',
            cssClass: 'rb',
            autospin: true,                
            action: function(dialogRef) {  

                BootstrapDialog.show({
                    title: 'Confirm',
                    message: 'Are you sure you want to delete your group? If not, click cancel. If yes, select the type of user you would like to revert to.',
                    type: BootstrapDialog.TYPE_DANGER,
                    buttons: [{
                        label: 'Entrepreneur',
                        cssClass: 'gb',
                        autospin: true,
                        action: function(dialogRef){
                            $.ajax({
                                   url: site_url + 'profile/deleteGroupForModerator',
                                   data: {
                                      type: 3
                                   },
                                   success: function(data) {
                                      dialogRef.close();
                                      location.reload();                            
                                   },
                                   type: 'POST'
                            });   
                        }
                    },{
                        label: 'Advisor',
                        cssClass: 'ob',
                        autospin: true,
                        action: function(dialogRef){
                            $.ajax({
                                   url: site_url + 'profile/deleteGroupForModerator',
                                   data: {
                                      type: 2
                                   },
                                   success: function(data) {
                                      dialogRef.close();
                                      location.reload();                            
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
        },{
            label: 'Update',
            cssClass: 'ob',
            autospin: true,                
            action: function(dialogRef) {  
                var name = $("#group_name").val();
                if(name === ""){
                  alert("The group name is empty.");
                  return;
                }
                $.ajax({
                       url: site_url + 'profile/addGroup',
                       data: {             
                          name:  name, 
                          image: group_image,
                          id: currentUser_id
                       },
                       success: function(data) {
                          if(data === "success"){
                            
                          }else if(name !== group_name){
                            alert("The group name is already taken.");
                            return;
                          }
                          location.reload();
                          dialogRef.close();
                       },
                       type: 'POST'
                });
                          
            }
        },{
            label: 'Cancel',                          
            action: function(dialogRef) {  
                dialogRef.close();
            }
        }]
    });
  }

  function LeaveGroup(type){
    if(type != 4){
      BootstrapDialog.confirm({
          title: 'Confirm',
          message: 'are you sure you want to leave your group?',
          type: BootstrapDialog.TYPE_DANGER,
          closable: true,
          draggable: true,
          btnCancelLabel: 'Cancel',
          btnOKLabel: 'Leave',
          btnOKClass: 'btn-danger',
          callback: function(result) {
              if(result){
                $.ajax({
                       url: site_url + 'profile/LeaveGroup',
                       data: {             
                          id: currentUser_id
                       },
                       success: function(data) {
                          location.reload();
                       },
                       type: 'POST'
                });
              }              
          }
      });
    }
    else{
      BootstrapDialog.show({
            title: 'Confirm',
            message: 'Are you sure you want to leave your group? If not, click cancel. If yes, select the type of user you would like to revert to.',
            type: BootstrapDialog.TYPE_DANGER,
            buttons: [{
                label: 'Entrepreneur',
                cssClass: 'gb',
                autospin: true,
                action: function(dialogRef){
                    $.ajax({
                       url: site_url + 'profile/LeaveGroupForModerator',
                       data: {             
                          id: currentUser_id,
                          type: 3
                       },
                       success: function(data) {
                          dialogRef.close();
                          location.reload();
                       },
                       type: 'POST'
                    });
                }
            },{
                label: 'Advisor',
                cssClass: 'ob',
                autospin: true,
                action: function(dialogRef){
                    $.ajax({
                       url: site_url + 'profile/LeaveGroupForModerator',
                       data: {             
                          id: currentUser_id,
                          type: 2
                       },
                       success: function(data) {
                          dialogRef.close();
                          location.reload();
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

   
  }

  function edit_category(category){
    var htm = '<span>Employees:</span>'+
      '<select class="edit_input" name="thelist" id="select_category">';
        if(category == 1) htm += '<option selected>Mentor</option>';
        else htm+= '<option>Mentor</option>';
        if(category == 2) htm += '<option selected>Service Provider</option>';
        else htm+= '<option>Service Provider</option>';
    htm += '</select>';

    BootstrapDialog.show({
        type: BootstrapDialog.TYPE_PRIMARY,
        title: "Edit Membership Category",
        message: htm,
        buttons: [{
            label: 'Cancel',                          
            action: function(dialogRef) { 
                dialogRef.close();
            }
        },{
            label: 'Save',
            cssClass: 'btn-primary',
            icon: 'glyphicon glyphicon-chevron-right',
            autospin: true,                
            action: function(dialogRef) {
              var txt = $("#select_category option:selected").text();
              var param = 2;
              if(txt === "Mentor") param = 1;
              $.ajax({
                     url: site_url + 'profile/SaveCategory',
                     data: {             
                        category: param
                     },
                     success: function(data) {
                        if(param == 1) $("#category_image").prop("src", site_url + 'assets/images/MentorIcon.png');
                        else $("#category_image").prop("src", site_url + 'assets/images/ProviderIcon.png');
                        $("#category_text").text(txt);
                        dialogRef.close();
                     },
                     type: 'POST'
              });

            }
        }]
    });
  }

  function EditProfile(){
    location.href = site_url + 'profile/edit';
  }
  

</script>

<div class="white_back" style="padding:100px 20px 50px 20px;margin:-10px;">
  <h3 class="pull-left gray-text" style="margin-top:10px;">MY PROFILE</h3>
</div>
<div class="col-text white_back border1234 radius-item">
  <div class="container-widget" style="margin:0px;line-height:1.5;">
  <?php if($status == USER_STATUS_DELETE) { ?>
    <p style="text-align:center; width:100%; margin-top:20px;">The account has been removed.</p>
  <?php } else { ?>

          <!-- //====================Photo and Role -->
          <div class="row padding_xs">
                <div class="col-md-2 col-xs-12 padding_xs canvas" id="myProfileImage" style="text-align:center;">
                  <img class="preview-Img" src="<?= strlen($u_photo)>0?$u_photo:asset_base_url().'/images/emp.jpg'?>" style="border-radius:100%;">
                </div>
                <div class="col-md-6 col-xs-12 col-text container-widget" style="line-height:22px;">

                  <div class="row font-18">
                      <div class="col-xs-3"><p class="gray-text">Name: </p></div>
                      <div class="col-xs-7"><p class=""><b><?= $u_fname?> <?= $u_lname?></b></p></div>
                      <div class="col-xs-2"><p onclick="EditProfile();" class="gray-text glyphicon glyphicon-pencil"></p></div>
                  </div>

                  <div class="row font-16">
                    <div class="col-xs-3"><p class="gray-text">Role: </p></div>
                    <div class="col-xs-9">
                      <p class="">
                      <?php if($u_type == 1) echo "Admin";
                      else if($u_type == 2) echo "Advisor";
                      else if($u_type == 3) echo "Entrepreneur";
                      else if($op_leave == 1) echo "sub-moderator";
                      else echo "Moderator"; ?></p>
                    </div>
                  </div>
                  <div class="row font-16">
                    <div class="col-xs-3"><p class="gray-text">Bio: </p></div>
                    <div class="col-xs-9"><p class=""><?= $u_bio?></p></div>
                  </div>

                  <div class="row font-16">
                    <div class="col-xs-3"><p class="gray-text">Location: </p></div>
                    <div class="col-xs-9"><p class=""><?= $location?$location:"-" ?></p></div>
                  </div>
                  <div class="row font-16">
                    <div class="col-xs-3"><p class="gray-text">LinkedIn: </p></div>
                    <div class="col-xs-9"><a target="_blank" href="<?= $public_url ?>"><p class="wrapword"><?= $public_url?$public_url:"-"?></p></a></div>
                  </div>
                </div>

                <div class="col-md-4 col-xs-12">                  
                  <div class="col-xs-6 padding_xs">
                     <a href="<?php echo site_url('setting')?>"><button class="btn full-width" type="button"><center><img class="image-Item" src="<?= asset_base_url().'/images/notifications.png' ?>"><div>Notifications</div></center></button></a>
                  </div>
                  <div class="col-xs-6 padding_xs">
                     <a href="<?php echo site_url('setting/profile')?>"><button class="btn full-width" type="button"><center><img class="image-Item" src="<?= asset_base_url().'/images/settings.png' ?>"><div>Settings</div></center></button></a>
                  </div>
                </div>

        </div>

        <?php if(strlen($group_name) > 0){ ?>
        <div class="row div-item border1" style="margin-top:0px;">
            <div class="col-sm-6 col-xs-12">
              <div class="col-xs-12">
                <h5 class="col-xs-10 gray-text">GROUP MEMBERSHIP:</h5>
                <?php if($u_type == 4 && $op_leave == 0){ ?>
                  <h5 class="col-xs-2 gray-text glyphicon glyphicon-pencil" onclick="edit_group()"></h5>
                <?php } else if($u_type != 1 && strlen($u_group) > 0){ ?>
                  <h5 class="col-xs-2 gray-text glyphicon glyphicon-pencil" onclick="LeaveGroup(<?= $u_type ?>);"></h5>
                <?php } ?>
              </div>
              <div class="col-xs-12">
                <?php if(strlen($group_name) > 0){ ?>
                  <img class="pull-left round" width="40" height="40" src="<?= isset($group_image_name) && strlen($group_image_name)<40?uploads_base_url().$group_image_name:asset_base_url().'/images/ava-group.svg' ?>">
                  <p class="pull-left padding_xs gray-80" id="group_membership"><?= $group_name ?></p>
                <?php } else {?>
                  <p class="padding_xs gray-80">No group to display</p>
                <?php } ?>
              </div>
            </div>
            <div class="col-sm-6 col-xs-12">
              <div class="col-xs-12">
                <h5 class="col-xs-10 gray-text">MEMBERSHIP CATEGORY:</h5>
                <?php if($u_type == 2){ ?>
                <h5 class="col-xs-2"><button type="button" class="pull-right gray-text trans" onclick="edit_category('<?= $category ?>')"><span class="glyphicon glyphicon-pencil"></span></button></h5>
                <?php } ?>
              </div>
              <div class="col-xs-12">
                <?php if($u_type == 2){ ?>
                  <img class="pull-left round" id="category_image" width="40" height="40" src="<?= $category == 1?asset_base_url().'/images/MentorIcon.png':asset_base_url().'/images/ProviderIcon.png' ?>">
                  <p class="pull-left padding_xs gray-80" id="category_text"><?= $category == 1?"Mentor":"Service Provider" ?></p>
                <?php } ?>
              </div>
            </div>
        </div>
        <?php } ?>
             
<!-- ======================   Profile Status   ================== -->


  <div class="row div-item border1" style="margin-top:0px;">
        <h4 class="gray-text">PROFILE STATS:</h4>
    </div>

    <div class="row">

      <div class="col-md-6 col-xs-12" style="padding:0px 20px;">
          <div class="col-xs-9">
            <p class="gray-text">TEAMUP CHATS: JOINED</p>
          </div>
          <div class="col-xs-3">
            <p class="font-20"><?= $entered_chats?></p>
          </div>    
      </div>

      <div class="col-md-6 col-xs-12" style="padding:0px 20px;">
          <div class="col-xs-9 Qinput">
            <p class="gray-text">TEAMUP CHATS: COMMENTS ADDED</p>
          </div>
          <div class="col-xs-3">
            <p class="font-20"><?= $self_comments?></p>
          </div>
      </div>

      <div class="col-md-6 col-xs-12" style="padding:0px 20px;">
          <div class="col-xs-9 Qinput">
            <p class="gray-text">TEAMUP CHATS: COMMENTS SAVED</p>
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
<?php if($u_type != 2) { ?>
      

          <div class="row">
            <h4 class="col-xs-10 nm gray-text"> INTERESTED IN: </h4>
            <h4 class="col-xs-2 nm"><button type="button" class="pull-right gray-text" id="looking_button" onclick="edit_looking()" style="background:transparent;"><span class="glyphicon glyphicon-pencil"></span></button></h4>
          </div>

          <div class="row section-seeking" id="looking_container">
            <?php if(isset($looking) && $looking !== "" && $looking !== "[]") { ?>
            <?php foreach(json_decode($looking) as $node) { ?>
                <div class="online_tags pull-left" id="li_looking"><?= $node ?><a class="close more-close" style="display:none;" onclick="looking_Remove(this)">&times;</a></div>
            <?php } ?>
            <?php } else { echo "<p class='empty_looking'> There is no data</p>"; }?>
          </div>

          <div class="row" id="looking_Editfield" style="display:none;">
            <div class="col-sm-9 col-xs-12">
            <input type="text" class="border-style-xs radius-input" id="add_looking" placeholder="Type and hit enter to add to list" onkeypress="detect_looking(event, this)" style="width:100%;">
            </div>
            <div class="col-sm-3 col-xs-12">
              <button type="button" class="btn radius-input" onclick="donelooking()" style="width:100%;">Done</button>
            </div>
          </div>

    <?php } else {?>

          <div class="row">
            <h4 class="col-xs-10 nm gray-text"> INTERESTED IN: </h4>
            <h4 class="col-xs-2 nm"><button type="button" class="pull-right gray-text" id="interesting_button" onclick="edit_interesting()" style="background:transparent;"><span class="glyphicon glyphicon-pencil"></span></button></h4>
          </div>

          <div class="row section-seeking" id="interesting_container">
            <?php if(isset($interesting) && $interesting !== "" && $interesting !== "[]") { ?>
            <?php foreach(json_decode($interesting) as $node) { ?>
                <div class="online_tags pull-left" id="li_interesting"><?= $node ?><a class="close more-close" style="display:none;" onclick="interesting_Remove(this)">&times;</a></div>
            <?php } ?>
            <?php } else { echo "<p class='empty_interesting'> There is no data</p>"; }?>
          </div>

          <div class="row" id="interesting_Editfield" style="display:none;">
            <div class="col-sm-9 col-xs-12">
            <input type="text" class="border-style-xs radius-input" id="add_interesting" placeholder="Type and hit enter to add to list" onkeypress="detect_interesting(event, this)" style="width:100%;">
            </div>
            <div class="col-sm-3 col-xs-12">
              <button type="button" class="btn radius-input" onclick="doneinteresting()" style="width:100%;">Done</button>
            </div>
          </div>


    <?php } ?>
      </div>

      <div class="col-md-6 col-xs-12 border1 border2" id="section-skill" style="padding:20px;">   

        <div class="row">
          <h4 class="col-xs-10 nm gray-text"> SKILLS:</h4>
          <h4 class="col-xs-2 nm"><button type="button" class="pull-right gray-text" id="skill_button" onclick="edit_skill()" style="background:transparent;"><span class="glyphicon glyphicon-pencil"></span></button></h4>
        </div>

        <div class="row section-skill" id="skill_container">
            <?php if(isset($skill) && $skill !== "" && $skill !== "[]") { ?>
            <?php foreach(json_decode($skill) as $node) { ?>
              <div class="online_tags pull-left" id="li_skill"><?= $node ?><a class="close more-close" style="display:none;" onclick="skill_Remove(this)">&times;</a></div>
            <?php } ?>
            <?php } else { echo "<p class='empty_skill'> There is no data</p>"; }?>
        </div>

        <div class="row" id="skill_Editfield" style="display:none;">
          <div class="col-sm-9 col-xs-12">
          <input type="text" class="border-style-xs radius-input" id="add_skill" placeholder="Type and hit enter to add to list" onkeypress="detect_skill(event, this)" style="width:100%;">
          </div>
          <div class="col-sm-3 col-xs-12">
            <button type="button" class="btn radius-input" onclick="doneSkill()" style="width:100%;">Done</button>
          </div>
        </div>

      </div>

      <div class="col-md-6 col-xs-12 border1 border2" id="section-position" style="padding:20px;">

        <div class="row section-position" id="position-field">
            <div class="col-xs-12">
              <h4 class="col-xs-10 nm gray-text">EXPERIENCE & POSITIONS HELD:</h4>
              <h4 class="col-xs-2 nm"><button type="button" class="pull-right gray-text" id="position_button" onclick="edit_position()" style="background:transparent;"><span class="glyphicon glyphicon-pencil"></span></button></h4>
            </div>
            <div class="col-text col-xs-12"></div>
            <?php if(isset($position) && $position !== "" && $position !== "[]") { ?>
            <?php foreach(json_decode($position) as $pos) { ?>
              <div class="col-xs-12">
                <div class="col-xs-1" style="padding:5px;">
                  <img src="<?= asset_base_url().'/images/list-disc.png'?>" class="pull-left">
                </div>
                <div class="col-xs-11">
                  <?= $pos ?><span class="line-item glyphicon glyphicon-trash" onclick="deletePos(event, this)"></span>
                </div>
              </div>
            <?php } ?>
            <?php } ?>
        </div>

      </div>

      <div class="col-md-6 col-xs-12 border1 border2" id="section-education" style="padding:20px;">

        <div class="row section-education" id="education-field">
            <div class="col-xs-12">
              <h4 class="col-xs-10 nm gray-text">EDUCATION:</h4>
              <h4 class="col-xs-2 nm"><button type="button" class="pull-right gray-text" id="education_button" onclick="edit_education()" style="background:transparent;"><span class="glyphicon glyphicon-pencil"></span></button></h4>
            </div>
            <div class="col-text col-xs-12"></div>
            <?php if(isset($education) && $education !== "" && $education !== "[]") { ?>
            <?php foreach(json_decode($education) as $edu) { ?>
              <div class="col-xs-12">
                <div class="col-xs-1" style="padding:5px;">
                  <img src="<?= asset_base_url().'/images/list-disc.png'?>" class="pull-left">
                </div>
                <div class="col-xs-11">
                  <?= $edu ?><span class="line-item glyphicon glyphicon-trash" onclick="deleteEdu(event, this)"></span>
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
          <div class="col-sm-7 col-xs-11 italic_value" id="venture_name"><?php echo $venture_name?$venture_name:"Null   (please edit)" ?></div>
          <span class="col-xs-1" style="color:gray;" class="pull-right"><button type="button" onclick="edit_name()" class="trans"><span class="glyphicon glyphicon-pencil"></span></button></span>
        </div>

        <div class="row">
          <div class="col-sm-4 col-xs-12 gray-80">Summary:</div>
          <div class="col-sm-7 col-xs-11 italic_value" id="summary"><?php echo $summary?$summary:"Null   (please edit)" ?></div>
          <span class="col-xs-1" style="color:gray;" class="pull-right"><button type="button" onclick="edit_summary()" class="trans"><span class="glyphicon glyphicon-pencil"></span></button></span>
        </div>

        <div class="row">
          <div class="col-sm-4 col-xs-12 gray-80">Industry:</div>
          <div class="col-sm-7 col-xs-11 italic_value" id="industry"><?php echo $industry?$industry:"Null   (please edit)" ?></div>
          <span class="col-xs-1" style="color:gray;" class="pull-right"><button type="button" onclick="edit_industry()" class="trans"><span class="glyphicon glyphicon-pencil"></span></button></span>
        </div>

        <div class="row">
          <div class="col-sm-4 col-xs-12 gray-80">Business stage:</div>
          <div class="col-sm-7 col-xs-11 italic_value" id="stage"><?php echo $stage?$stage:"Null   (please edit)" ?></div>
          <span class="col-xs-1" style="color:gray;" class="pull-right"><button type="button" onclick="edit_stage()" class="trans"><span class="glyphicon glyphicon-pencil"></span></button></span>
        </div>

        <div class="row">
          <div class="col-sm-4 col-xs-12 gray-80">Employees:</div>
          <div class="col-sm-7 col-xs-11 italic_value" id="employee_num"><?php echo $employee_num?$employee_num:"Null   (please edit)" ?></div>
          <span class="col-xs-1" style="color:gray;" class="pull-right"><button type="button" onclick="edit_employee()" class="trans"><span class="glyphicon glyphicon-pencil"></span></button></span>
        </div>

        <div class="row">
          <div class="col-sm-4 col-xs-12 gray-80">Funding Raised:</div>
          <div class="col-sm-7 col-xs-11 italic_value" id="funding"><?php echo $funding?$funding:"Null   (please edit)" ?></div>
          <span class="col-xs-1" style="color:gray;" class="pull-right"><button type="button" onclick="edit_funding()" class="trans"><span class="glyphicon glyphicon-pencil"></span></button></span>
        </div>
      <?php } else { ?>
        <div class="row">
              <h4 class="nm pull-left gray-text"> CURRENT COMPANY:</h4>
        </div>

        <div class="row col-text">
          <div class="col-sm-4 col-xs-12 gray-80">Name:</div>
          <div class="col-sm-7 col-xs-11 italic_value" id="companyName"><?= isset($company->company->name)?$company->company->name:$c_name ?></div>
          <span class="col-xs-1" style="color:gray;" class="pull-right"><button type="button" onclick="edit_company_name()" class="trans pull-right"><span class="glyphicon glyphicon-pencil"></span></button></span>
        </div>

        <div class="row">
          <div class="col-sm-4 col-xs-12 gray-80">Location:</div>
          <div class="col-sm-7 col-xs-11 italic_value" id="companyLocation"><?= isset($company->location->name)?$company->location->name:$c_location ?></div>
          <span class="col-xs-1" style="color:gray;" class="pull-right"><button type="button" onclick="edit_company_location()" class="trans pull-right"><span class="glyphicon glyphicon-pencil"></span></button></span>

        </div>

        <div class="row">
          <div class="col-sm-4 col-xs-12 gray-80">Summary:</div>
          <div class="col-sm-7 col-xs-11 italic_value" id="companySummary"><?= isset($company->summary)?$company->summary:$c_summary ?></div>
          <span class="col-xs-1" style="color:gray;" class="pull-right"><button type="button" onclick="edit_company_summary()" class="pull-right trans"><span class="glyphicon glyphicon-pencil"></span></button></span>
        </div>

      <?php } ?>
      
    </div>

        <div class="row border1 div-item">
          <h4 class="pull-left gray-text"> LINKS:</h4>
          <h4 class="pull-right"><button type="button" id="link_button" onclick="edit_link()" class="big-button">+</button></h4>
        </div>

        <div class="row" id="Link_field">
        <?php foreach($array_link as $link) { ?>
         
          <div class="col-xs-12 profile-link-div" style="padding-left:30px;">
            <a target="_blank" class="col-xs-10 wrapword link-txt pull-left" href="<?= $link['link'] ?>"><?= $link['link'] ?></a>
            <button class="col-xs-2 close pull-left" onclick="deleteLink(this)" style="display:none;">&times;</button>
          </div>

        <?php } ?>
        </div>




<!-- ===============  Reviews  ============= -->
    

  <div class="row border1 padding_sm">
      <div class="col-md-9 col-sm-9 col-xs-12">
        <h4 class="nm gray-text"> REVIEWS: </h4>
      </div>    
  </div>

  <div class="row last_div">
    <div id="channel_edit" style="height:40vh;">
            <ul class="full-height scrollbar" id="routed-contacts" style="overflow:auto;"> 
              <?php foreach($array_review as $review) {?>

                <li class="padding_xs" id="<?= $review['id']?>">
                  <div class="row"> 

                    <div class="col-sm-11 col-xs-10">
                      <img class="avatar avatar_small pull-left" src="<?= $review['photo'] ?>" >
                      <span class="contacts_name Qinput pull-left" style="padding"><?= $review['review'] ?></span>                        
                    </div>

                    <?php if($current_id == $u_id) { ?>
                      <div class="col-sm-1 col-xs-2">
                          <span class="glyphicon glyphicon-trash pull-right text-primary" onclick="deleteReview(<?= $review['id'] ?>)"></span>
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
      $(window).load(function(){
         $('body').find(".fillme").each(function(){
            var fillmeval = $(this).width()/$(this).height();
            var imgval = $(this).children('img').width()/$(this).children('img').height();
            var imgClass;
            if(imgval > fillmeval){
                imgClass = "stretchy";
            }else{
                imgClass = "stretchx";
            }
            $(this).children('img').addClass(imgClass);
         });
      });

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

      $("#myProfileImage").hover(function(){
        $(".mask-div").show();
      }, function(){
        $(".mask-div").hide();
      })

      $(".profile-link-div").hover(function(){
        $(this).find("button").show();
      }, function(){
        $(this).find("button").hide();
      })





</script>




