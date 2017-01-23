var QTitle;
var QContext;
var QTags=[];
var QWebLinks = [];
var QFileName=[];
var FirstDialog;
var SecondDialog;
var tag_review, link_review, htmlTxt;
var RouteUserIDArray=[];
var InitRouteUserIDArray=[];
var QuestionID;
var AcceptUserIDArray = [];
var RouterListDialog;

jQuery( document ).ready( function( $ ) {   
    $( '#site-header a img' ).attr( "src", site_url + "assets/images/onlinkedIn.png" );
} );

function AddTag(){
	   htmlTxt = '<li>'+ $("#tagname").val()+'<a class="close x" style="color:white;" onclick="tagRemove(this)">&times;</a></li>';
            $("#selected_tag").append(htmlTxt);

     QTags.push($("#tagname").val());
     $("#tagname").val("");
}

function AddLink(){
      if($("#web-link").val().indexOf("http://") < 0 && $("#web-link").val().indexOf("https://") < 0){
       alert("You must paste in a web link!");
       $("#web-link").val("");
       return;
      }
     htmlTxt = '<li>'+ $("#web-link").val()+'<a class="close x" style="color:white;" onclick="linkRemove(this)">&times;</a></li>';
            $("#selected_link").append(htmlTxt);

     QWebLinks.push($("#web-link").val());
     $("#web-link").val("");
}

function tagRemove(obj) {
    var strTxt = $(obj).parent().text();
    strTxt = strTxt.substring(0,strTxt.length-1);
    var index = QTags.indexOf(strTxt);
	  QTags.splice(index, 1);
    $(obj).parent().remove();
}

function linkRemove(obj) {
    var strTxt = $(obj).parent().text();
    strTxt = strTxt.substring(0,strTxt.length-1);
    var index = QWebLinks.indexOf(strTxt);
    QWebLinks.splice(index, 1);
    $(obj).parent().remove();
}

function searchStringInArray (str, strArray) {
    for (var j=0; j<strArray.length; j++) {
        if (strArray[j].match(str)) return j;
    }
    return -1;
}

function detect(e, object) {
    var key=e.keyCode || e.which;
    if (key==13){
        AddTag();
        
    }
}

function detect_link(e, object) {
    var key=e.keyCode || e.which;
    if (key==13){
        AddLink();
        
    }
}

var currentID;
function FirstQuestion(cid) {
    $("#FirstQuestion").modal("show");
    $(".content").removeClass("scrollbar");
}

function closeFirstQuestion(){
  $("#FirstQuestion").modal("hide");
  $(".content").addClass("scrollbar");
}

function closeSecondQuestion(){
  $("#SecondQuestion").modal("hide");
}

function closeFinalQuestion(){
  $("#FinalQuestion").modal("hide");
}

function SecondQuestion() {

    QTitle = $("#title").val();

    if(QTitle === ""){
      alert('Title is required');
      return;
    } 
    $("#SecondQuestion").modal("show");
    QFileName = [];
    
}

function FinalQuestion(){
  QContext = $("#context-data").val();
  $("#FinalQuestion").modal("show");
  tag_review = "";
    for(var index in QTags){
      tag_review = tag_review + '<li>'+ QTags[index] + '</li>';      
    }

    link_review = "";
    for(var index in QWebLinks){
      link_review = link_review + '<p><a href="' + QWebLinks[index] + '">' + QWebLinks[index] + '</a></p>';      
    }

    file_review = "";
    var spary, ext;
    var imageExts=[];
    for(var index in QFileName){
      spary = QFileName[index].split(".");
      ext = spary[spary.length - 1] ;
      imageExts = ["png", "jpg", "jpeg", "PNG", "JPG", "JPEG"];

      if(imageExts.indexOf(ext)>=0){
         file_section = '<img class="preview-Img" src="'+ uploads_base_url +QFileName[index]+'">';  
      }
      else if(ext === "pdf"){         
        file_section = '<img class="preview-Img" src="'+ site_url + "assets/images/pdf.png" + '">';  
      }
      else if(ext === "gif"){
        file_section = '<img class="preview-Img" src="'+ site_url + "assets/images/gif.png" + '">';  
      }
      else{
        file_section = '<img class="preview-Img" src="'+ site_url + "assets/images/file.png" + '">';  
      }
      
      var len = QFileName[index].split("_")[0].length;
      var fname = QFileName[index].substring(len + 1);
      file_review +=  '<div class="preview-file-section">' +
                        '<div class="mid-text">' +
                          file_section +
                        '</div>'+
                        '<div class="mid-text">' +
                          fname +
                        '</div>' +
                      '</div>';
      
    }
    $("#draft_file").html(file_review);
    $("#draft_title").text(QTitle);
    $("#draft_context").text(QContext);
    $(".draft_tags").html(tag_review);
    $("#web_links").html(link_review);
    $("#selected_tag").html(tag_review);
 
}

function SubmitToGroup(){
  $("#post-group-spinner").show();
  $.ajax({
     url: site_url + 'questions/add',
     data: {
        fname:JSON.stringify(QFileName),
        title: QTitle,
        context: QContext,
        tags: JSON.stringify(QTags),
        link: JSON.stringify(QWebLinks),
        status: 1,//QUESTION_STATUS_SUBMIT
        post:"private"

     },
     success: function(data) {
        $("#FirstQuestion").modal("hide");
        $("#SecondQuestion").modal("hide");
        $("#FinalQuestion").modal("hide");
        location.reload();
     },
     type: 'POST'
  });         
}

function SubmitToRelayy(){
  if(currentUser_gname.length == 0){
            $("#post-relayy-spinner").show();
            $.ajax({
               url: site_url + 'questions/add',
               data: {
                  fname:JSON.stringify(QFileName),
                  title: QTitle,
                  context: QContext,
                  tags: JSON.stringify(QTags),
                  link: JSON.stringify(QWebLinks),
                  status: 1,//QUESTION_STATUS_SUBMIT
                  post:"public"

               },
               success: function(data) {
                  $("#FirstQuestion").modal("hide");
                  $("#SecondQuestion").modal("hide");
                  $("#FinalQuestion").modal("hide");
                  location.reload();
               },
               type: 'POST'
            });       
  }
  else{
        BootstrapDialog.show({
            type: BootstrapDialog.TYPE_PRIMARY,
            title: 'Warnning',
            message: 'Are you sure you don\'t want to submit your question to your group('+currentUser_gname+')?',
            buttons: [{
                label: 'Submit to Relayy',
                cssClass: 'ob',
                action: function(dialogRef) {  
                    $.ajax({
                       url: site_url + 'questions/add',
                       data: {
                          fname:JSON.stringify(QFileName),
                          title: QTitle,
                          context: QContext,
                          tags: JSON.stringify(QTags),
                          link: JSON.stringify(QWebLinks),
                          status: 1,//QUESTION_STATUS_SUBMIT
                          post:"public"

                       },
                       success: function(data) {
                          dialogRef.close();
                          $("#FirstQuestion").modal("hide");
                          $("#SecondQuestion").modal("hide");
                          $("#FinalQuestion").modal("hide");
                          location.reload();
                       },
                       type: 'POST'
                    });         
                }
            },{
                label: 'Submit to Group',
                cssClass: 'bb',
                action: function(dialogRef) {  
                    $.ajax({
                       url: site_url + 'questions/add',
                       data: {
                          fname:JSON.stringify(QFileName),
                          title: QTitle,
                          context: QContext,
                          tags: JSON.stringify(QTags),
                          link: JSON.stringify(QWebLinks),
                          status: 1,//QUESTION_STATUS_SUBMIT
                          post:"private"

                       },
                       success: function(data) {
                          dialogRef.close();
                          $("#FirstQuestion").modal("hide");
                          $("#SecondQuestion").modal("hide");
                          $("#FinalQuestion").modal("hide");
                          location.reload();
                       },
                       type: 'POST'
                    });         
                    
                }
            }]
      });
  }
  



  
}

function deleteUploadFile(obj){
  var item = $(obj).parent().find("input").data("file");
  var index = QFileName.indexOf(item);
  QFileName.splice(index, 1);
  $(obj).parent().remove();
}

function uploadImage(obj) { 
    // select the form and submit
    var data = new FormData($(obj).parent()[0]);
    $(obj).parent().parent().parent().append('<p class="mid-num">Uploading...</p>');   
        
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
                        if(data.indexOf("_") > 0){
                          MoreFile();
                          QFileName.push(data); 
                          $(obj).attr("data-file", data);  
                        }
                        else{
                          alert(data);                            
                        }
                        $(obj).parent().parent().parent().find('p').remove();
                        
                        
                  }
    }); 
}

function MoreFile(){
  var htm = '<div class="file-div">'+
                      '<form action="" class="load-img" method="POST" enctype="multipart/form-data">'+
                         '<input type="file" data-file="pp" class="Qinput pull-left addfile" name="FileName" onchange="uploadImage(this)"/>'+
                      '</form>'+
                      '<span class="pull-right big-button" onclick="deleteUploadFile(this);" style="margin:15px 5px;">&times;</span>'+
            '</div>';
  $("#add_file").append(htm);
};


function list_questions(){
  $.ajax({
        url: site_url + 'questions/load_question_list',
           data: {
              
           },
           success: function(data) {
              $(".content").html(data);
           },
           type: 'POST'
        });
}

function callme(hash) {
        if($("#title_"+hash).text() === "HIDE DETAILS") {
            $("#title_"+hash).text("VIEW DETAILS");
            $("."+hash).hide();
        } else {
            $("#title_"+hash).text("HIDE DETAILS");
            $("."+hash).show();
        }        
    
};

function viewFeedDetail(){
   if($("#feed_detail").val() === "HIDE DETAILS") {
            $("#feed_detail").val("VIEW DETAILS");
            $(".feed_toggle").hide();
    } else {
        $("#feed_detail").val("HIDE DETAILS");
        $(".feed_toggle").show();
    }    
}

function RouteQuestion(id){
  location.href = site_url + "questions/RouteQuestion/" + id;
}



function onCheck(id){
  var index = RouteUserIDArray.indexOf(id);
  if(index == -1){
    RouteUserIDArray.push(id);
  }else{
    RouteUserIDArray.splice(index, 1);
  }
  
  if(RouteUserIDArray.length == InitRouteUserIDArray.length){
    $(".select-all").prop('checked', false);
  } 
}

function onRoute(){

  if(JSON.stringify(RouteUserIDArray) === JSON.stringify(InitRouteUserIDArray)){
    alert('you selected nothing!');
    return;
  }
  $.ajax({
      url: site_url + 'questions/SubmitRoute',
         data: {
            q_id: QuestionID,
            r_ids: RouteUserIDArray
         },
         success: function(data) {
            location.href = site_url + 'questions';
         },
         type: 'POST'
      });
}

function selectAll(){
  if(!$(".select-all").prop('checked')){// unselect all
    RouteUserIDArray = JSON.parse(JSON.stringify(InitRouteUserIDArray))
    $('input:checkbox.select-checkbox').prop('checked', false);
  }
  else{// select all    
    $('input:checkbox.select-checkbox').prop('checked', true);
    $('input:checkbox.select-checkbox').each(function(){

      if($(this).parent().parent().css('display') === 'none') return;
      var index = RouteUserIDArray.indexOf(parseInt($(this).val()));
      if($(this).css('display') === 'block' && index == -1){
        RouteUserIDArray.push(parseInt($(this).val())); 
      }

    });
  }
 

}

function onAdvisorSearch(){

  $(".advisor_contacts").each(function(){
    if($(this).find('span:first').text().toLowerCase().indexOf($("#advisor_name").val().toLowerCase()) >=0 && $(this).attr('data-skills').toLowerCase().indexOf($("#advisor_skill").val().toLowerCase()) >=0){
      $(this).css('display', 'block');
    }
    else{
      $(this).css('display', 'none');
    }
  

  });
}



function deleteRouter(d_id){   
  

  BootstrapDialog.show({
        type: BootstrapDialog.TYPE_PRIMARY,
        title: 'Warning',
        message: 'are you sure you want to remove this user from the route list?',
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
                var index = AcceptUserIDArray.indexOf(d_id.toString());
                  if(index > -1) AcceptUserIDArray.splice(index, 1);
                  $.ajax({
                    url: site_url + 'questions/deleteRouter',
                       data: {
                          q_id: QuestionID,
                          r_ids: JSON.stringify(AcceptUserIDArray)
                       },
                       success: function(data) {
                          location.reload();
                       },
                       type: 'POST'
                    });
                
            }
        }]
  });
}

function deleteWaiter(d_id){   
  

  BootstrapDialog.show({
        type: BootstrapDialog.TYPE_PRIMARY,
        title: 'Warning',
        message: 'are you sure you want to delete this user from waiting list?',
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
                    $.ajax({
                      url: site_url + 'questions/deleteWaiter',
                       data: {
                          q_id: QuestionID,
                          u_id: d_id
                       },
                       success: function(data) {
                          location.reload();
                       },
                       type: 'POST'
                    });
                
            }
        }]
  });
}

function deleteWaiter(d_id){   
  

  BootstrapDialog.show({
        type: BootstrapDialog.TYPE_PRIMARY,
        title: 'Warnning',
        message: 'are you sure you want to delete this user?',
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
                    $.ajax({
                      url: site_url + 'questions/deleteWaiter',
                       data: {
                          q_id: QuestionID,
                          u_id: d_id
                       },
                       success: function(data) {
                          location.reload();
                       },
                       type: 'POST'
                    });
                
            }
        }]
  });
}

function getRouterUsers(email, callback) {
    $.ajax({
       url: site_url + 'chat/users',
       data: {
          email: email
       },
       success: function(data) {
          var jsonObj = JSON.parse(data);
          // if (email == '' && chatusers.length == 0) chatusers = jsonObj;
          console.log(jsonObj);
          callback(jsonObj);
       },
       type: 'POST'
    });
}

function buildRoutersHTML(json, email, str_r_ids, q_id, type) {

  AcceptUserIDArray = str_r_ids.split(" ");
    $("#q_contacts").html("");
    if (json.length == 0 && email) {
        json.push({email:email, fname:"", lname:"", photo:"", uid:""});
    }
    console.log(JSON.stringify(json));
    json.forEach(function(item, index) {
        // console.log("#####################");
        // console.log(item);
        var userName = item.fname+' '+item.lname;
        if (userName.trim() == '') {
            var nameArr = item.email.split('@');
            userName = nameArr[0];
        }
        var index = AcceptUserIDArray.indexOf(item.id);
        if (index == -1) return;
        var htmlTxt;
        if((currentUser_type == 1 || currentUser_type == 4) && type == 1) htmlTxt = '<li style="padding:10px;"><span onclick="deleteRouter(' + item.id + ')" class="glyphicon glyphicon-trash pull-right text-primary"></span>';
        else if((currentUser_type == 1 || currentUser_type == 4) && type == 2) htmlTxt = '<li style="padding:10px;"><span onclick="deleteWaiter(' + item.id + ')" class="glyphicon glyphicon-trash pull-right text-primary"></span>';
        else htmlTxt = '<li style="padding:10px;">';         
        if (item.photo) htmlTxt += '<img class="avatar avatar_small" src="'+item.photo+'">';
        else htmlTxt += '<img class="avatar avatar_small" src="'+site_url+"/assets/images/emp-sm.jpg"+'">';
        htmlTxt = htmlTxt +       '<span class="contacts_name">'+userName+'</span>'+                        
                    '</li>';
        // alert(htmlTxt);
        $("#q_contacts").append(htmlTxt);
    });
}

function viewRouteList(q_id, title, str_r_ids){
  QuestionID = q_id;
  $("#ShowUserListDialog").modal("show");
  $("#question-popup-title").text("ROUTE LIST of " + title);
  getRouterUsers('', function(data) {
      buildRoutersHTML(data, '',str_r_ids, q_id, 1);
  });
}

function viewWaitingList(q_id, title, str_a_ids){
  QuestionID = q_id;
  $("#ShowUserListDialog").modal("show");
  $("#question-popup-title").text("Waiting Advisors of " + title);
  getRouterUsers('', function(data) {
      buildRoutersHTML(data, '',str_a_ids, q_id, 2);
  });
}

function onCancelPopup(){
  $("#ShowUserListDialog").modal("hide");
}

function passFeed(q_id, c_id, b_accept, body_class){
  if(!b_accept) $("#pass-spinner").show();
	var index = router_ids.indexOf(c_id);
	if(index > -1)router_ids.splice(index, 1);
  if(b_accept && accept_ids.indexOf(c_id) < 0){
    accept_ids.push(c_id);
  } 
    $.ajax({
      url: site_url + 'questions/nextFeed',
         data: {
            q_id: q_id,
            r_ids: JSON.stringify(router_ids),
            a_ids: JSON.stringify(accept_ids),
            b_accept: b_accept
         },
         success: function(data) {
            if(body_class === "question-feed") location.href = site_url + "questions";
            else $(".content").html(data);
            $("#pass-spinner").hide();
         },
         type: 'POST'
      });
}

function acceptFeed(asker_email, asker_uid, c_id, q_id){
	var params = {
		type: 2,
		name: "Group"
	};
  $("#join-spinner").show();

	    $.ajax({
         url: site_url + 'questions/AcceptQuestion',
         data: {
            q_id: q_id,
            accepter_id: c_id,
         },
         success: function(data) {
            passFeed(q_id, c_id, true);   
            
         },
         type: 'POST'
      });



		                         
		 
}





function viewFeed(){
  
}

