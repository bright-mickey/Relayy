
function signin(){
  $("#loginForm").modal("show");
}

function signup(){
  $("#registerForm").modal("show");
}

function signup_signin(){
  $("#registerForm").modal("hide");
  $("#InviteCodeForm").modal("hide");
  $("#loginForm").modal("show");
}

function registerDialogPopup() {
  $("#loginForm").modal("hide");
  $("#registerForm").modal("show");
}

function LoginUser(uid, email, fname, lname, picture, bio, role, location, public_url, company){
    $.ajax({
       url: site_url + 'home/checkUser',// check email is live or not
       data: {
          email: email
       },
       success: function(data) {
          if(data === "not_active"){
            alertstate("You are not active user.\nYou must sign up now.");
              return;
          }
          else{
              $("#li_id").val(uid);
              $("#li_fname").val(fname);
              $("#li_lname").val(lname);
              $("#li_email").val(email);
              $("#li_photo").val(picture);
              $("#li_bio").val(bio);
              $("#li_role").val(data);
              $("#li_location").val(location);
              $("#li_public").val(public_url);
              $("#li_company").val(company);
              $("#linkedin_form").submit(); 
          }
       },
       type: 'POST'
      });
}




function registerFacebook(uid, email, fname, lname, picture, bio, role, location, public_url, company, group) {
  var params = { 'login': email, 'password': QBApp.authKey, 'full_name': fname+" "+lname, 'email': email };
  var filters = {filter: { field: 'email', param: 'eq', value: email }};

  QB.users.listUsers(filters, function(err, result){
    if (result && result.items.length> 0) {      //=======Old User in QuickBlox
      var user = result.items[0];

      $.ajax({
       url: site_url + 'home/link',// check email is live or not
       data: {
          email: email
       },
       success: function(data) {
          if (data == 11 || data == 4) {//unlive
              
              $("#loginForm").modal("hide");
              $("#lir_id").val(user.user.id);
              $("#lir_login").val(user.user.login);
              $("#lir_fname").val(fname);
              $("#lir_lname").val(lname);
              $("#lir_email").val(user.user.email);
              $("#lir_photo").val(picture);
              $("#lir_bio").val(bio);
              $("#lir_location").val(location);
              $("#lir_public").val(public_url);
              $("#lir_company").val(company);  
              $("#lir_group").val(group); 
              $("#lir_code").val(currentUser_signup_code);            
              if(role != 4)$("#linkedinForm").modal("show");
              else{
                $("#user_role").val(role);
                $("#linkedin_register_form").submit(); 
              }     
              
          }
          else if(data == 1){
              alertstate("You are already a active user.\nPlease go back to sign in.");
              return;
          }
          else if(data == 0){
              alertstate("You can't signup with this email. Because the user is in pending state now.\nYou must sign up with other email.");
              return;
          }
          else{
              alertstate("You are invited User now. You must sign up with the invite url.\nPlease check your email to link to the invite page.");
              return;
          }
          
       },
       type: 'POST'
      });
    } else if (result && result.items.length == 0) {
      QB.users.create(params, function(err, user){//=========== New User in QuiclBlox
        if (user) {
              $("#loginForm").modal("hide");
              $("#lir_id").val(user.user.id);
              $("#lir_login").val(user.user.login);
              $("#lir_fname").val(fname);
              $("#lir_lname").val(lname);
              $("#lir_email").val(user.user.email);
              $("#lir_photo").val(picture);
              $("#lir_bio").val(bio);
              $("#lir_location").val(location);
              $("#lir_public").val(public_url);
              $("#lir_company").val(company);  
              $("#lir_group").val(group); 
              $("#lir_code").val(currentUser_signup_code);            
              if(role != 4)$("#linkedinForm").modal("show");
              else{
                $("#lir_role").val(role);
                $("#linkedin_register_form").submit(); 
              }     
        } else  {
          alert("***********************" + JSON.stringify(err));
        }
      }); 
    } else {

      console.log(result);
    }
  });

}


function registerInvitedUser(uid, email, fname, lname, picture, bio, role, location, public_url, company) {

  var params = { 'login': email, 'password': QBApp.authKey, 'full_name': fname+" "+lname, 'email': email };
  
  var filters = {filter: { field: 'email', param: 'eq', value: email }};
  QB.users.listUsers(filters, function(err, result){
    if (result && result.items.length> 0) {//================= Old User in QuiclBlox
      var user = result.items[0];
      // console.log(user.user);return;
      $.ajax({
       url: site_url + 'invite/register',
       data: {
          id:uid,
          uid:user.user.id,
          email: user.user.email,
          fname:fname,
          lname:lname,
          photo:picture,
          bio:bio,
          type:role,
          location:location,
          public_url:public_url,
          company:company

       },
       success: function(data) {
          window.location.href=site_url;          
       },
       type: 'POST'
      });
    } else if (result && result.items.length == 0) {
      
      QB.users.create(params, function(err, user){//============= New User in QuickBlox
        if (user) {
          $.ajax({
             url: site_url + 'invite/register',
                 data: {
                    id:uid,
                    uid:user.id,
                    email: user.email,
                    fname:fname,
                    lname:lname,
                    photo:picture,
                    bio:bio,
                    type:role,
                    location:location,
                    public_url:public_url,
                    company:company

                 },
                 success: function(data) {
                    window.location.href=site_url;
                 },
                 type: 'POST'
            });
        } else  {
          alert("***********************" + JSON.stringify(err));
        }
      }); 
    } else {
      console.log(result);
    }
  });

}

$(document).ready(function() {

  // First of all create a session and obtain a session token
  // Then you will be able to run requests to Users
  //
  QB.createSession(function(err,result){
    console.log('Session create callback', err, result);
  });
  
  // Create user
  //
  $('#sign_up').on('click', function() {
  	$("#load-users").addClass("visible");
  	$("#load-users").attr('disabled', true);

    var login = $('#usr_reg_n_lgn').val();
    var password = $('#usr_reg_n_pwd').val();
    var fname = $('#usr_reg_n_fname').val();
    var lname = $('#usr_reg_n_lname').val();
    var user_role = $('#user_role').val();

      var filters = {filter: { field: 'email', param: 'eq', value: login }};
      QB.users.listUsers(filters, function(err, result){
        if (result && result.items.length> 0) {
          console.log("----------------linkedin register: old user");
          var user = result.items[0];
          $("#user_id").val(user.id);
          $("#register_form").submit();

        } else if (result && result.items.length == 0) {
          var params = { 'login': login, 'password': QBApp.authKey, 'full_name': fname+" "+lname, 'email': login };

            QB.users.create(params, function(err, user){
              if (user) {
                //alert(JSON.stringify(user));
                $("#user_id").val(user.id);
                $("#register_form").submit();
              } else  {
                alert(JSON.stringify(err));
              }

              $("#load-users").removeClass("visible");
                $("#load-users").attr('disabled', false);
              //$("#progressModal").modal("hide");
              //$("html, body").animate({ scrollTop: 0 }, "slow");
            }); 
        } else {
          console.log(err);
        }
      });
  });


  // Login user
  //
  $('#sign_in').on('click', function() {
    var login = $('#usr_sgn_n_lgn').val();
    var password = $('#usr_sgn_n_pwd').val();

    var params = { 'login': login, 'password': QBApp.authKey};

    QB.login(params, function(err, user){
      if (user) {
        $('#output_place').val(JSON.stringify(user));
      } else  {
        $('#output_place').val(JSON.stringify(err));
      }

      $("#progressModal").modal("hide");

      $("html, body").animate({ scrollTop: 0 }, "slow");
    });
  });

  // Login user with social provider
  //
  $('#sign_in_social').on('click', function() {

    var provider = $('#usr_sgn_n_social_provider').val();
    var token = $('#usr_sgn_n_social_token').val();
    var secret = $('#usr_sgn_n_social_secret').val();

    var params = { 'provider': provider, 'keys[token]': token, 'keys[secret]': secret};

    QB.login(params, function(err, user){
      if (user) {
        $('#output_place').val(JSON.stringify(user));
      } else  {
        $('#output_place').val(JSON.stringify(err));
      }

      $("#progressModal").modal("hide");

      $("html, body").animate({ scrollTop: 0 }, "slow");
    });
  });

  // Update user
  //
//  $('#update').on('click', function() {
//    var user_id = $('#usr_upd_id').val();
//    var user_fullname = $('#usr_upd_full_name').val();
//
//      if (user) {
//        $('#output_place').val(JSON.stringify(user));
//      } else  {
//        $('#output_place').val(JSON.stringify(err));
//      }
//
//      $("#progressModal").modal("hide");
//
//      $("html, body").animate({ scrollTop: 0 }, "slow");
//    });
//  });
});
