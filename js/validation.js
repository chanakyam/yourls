$(document).ready(function(){
   $("form[name='Registration'] input").on('blur',validateForm);
   $("form[name='loginpage'] input").on('blur',loginValidateForm);
});
//  validation 
function validateForm(){
var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?";
var invalid = '';
    //validate firstname
    var firstname=document.forms["Registration"]["firstname"].value;
    firstname = $.trim(firstname);
    $("#firstname").val(firstname);
    var firstname_max_length=64; 
      if (firstname==null || firstname==""){
          //errorMessage("label_firstname", "");
          $('input#firstname').addClass('required');
         invalid = true;
      }else if(firstname.length>firstname_max_length){
        //errorMessage("label_firstname", "(first name should not be greater than "+firstname_max_length+" characters)");
        $('input#firstname').addClass('required');
        invalid = true;
    }else{
        //errorMessage("label_firstname", "");
        $('input#firstname').removeClass('required');
          
      }

  //special characters validation for firstname
  for (var i = 0; i < document.Registration.firstname.value.length; i++) {
    if (iChars.indexOf(document.Registration.firstname.value.charAt(i)) != -1)
    {
      //errorMessage("label_firstname", "(Special characters are not allowed)");
      $('input#firstname').addClass('required');
    return false;
    }
  }

    //validate lastname
    var lastname=document.forms["Registration"]["lastname"].value;
    lastname = $.trim(lastname);
    $("#lastname").val(lastname);
    var lastname_max_length=64;
    if (lastname==null || lastname=="")
      {
        //errorMessage("label_lastname", "(This field is required)");
        $('input#lastname').addClass('required');
        invalid = true;
    }else if(lastname.length>lastname_max_length){
        //errorMessage("label_lastname", "(last name should not be greater than "+lastname_max_length+" characters)");
        $('input#lastname').addClass('required');
        invalid = true;
    }else{
      //errorMessage("label_lastname", "");
      $('input#lastname').removeClass('required');
    }
  //special characters validation for lastname
      for (var i = 0; i < document.Registration.lastname.value.length; i++) {
        if (iChars.indexOf(document.Registration.lastname.value.charAt(i)) != -1) 
          {
            //errorMessage("label_lastname", "(Special characters are not allowed)");
            $('input#lastname').addClass('required');
            return false;
          }
      }


    //email validation
    if(document.forms["Registration"]["email"]){
      var email=document.forms["Registration"]["email"].value;
      email = $.trim(email);
      $("#email").val(email);
      var email_max_length=128;
      var email_min_length=7;
      if (email==null || email=="")
        {
          //errorMessage("label_email", "(This field is required)");
          $('input#email').addClass('required');
          invalid = true;
      }else if(email.length>email_max_length || email.length<email_min_length){
          //errorMessage("label_email", "(email length should be between "+email_min_length+" & "+email_max_length+")");
          $('input#email').addClass('required');
          invalid = true;
      }else if(!email.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)){
        //errorMessage("label_email", "Please enter valid Email");
        $('input#email').addClass('required');
        invalid = true;
      }else{
        //errorMessage("label_email", "");
        $('input#email').removeClass('required');
      }
    }

    //validate password
    if(document.forms["Registration"]["password"]){
      var password=document.forms["Registration"]["password"].value;
      password = $.trim(password);
      $("#password").val(password);
      var password_max_length= 64;
      var password_min_length= 6;
      if (password==null || password==""){
          //errorMessage("label_password", "(This field is required)");
          $('input#password').addClass('required');
          invalid = true;
       }else if(password.length > password_max_length || password.length < password_min_length){
          $('input#password').addClass('required');
          errorMessage("label_password", "(Required minimum "+password_min_length+" characters)");          
          invalid = true;
      }//else if(!password.match(/^.*(?=.{6,})(?=.*[a-zA-Z])(?=.*\d)(?=.*[_.!@#$%^()+ "]).*$/)){
        else if(!password.match(((/^\S |^[a-zA-Z0-9~!@#$%^&*\(\)_+}{\|\":?><`\-=\\\]\[';\/\.\,]{6,}$/)))){
           //errorMessage("label_password", "Please enter valid password");
           $('input#password').addClass('required');
           invalid = true;
       }else{
        errorMessage("label_password", "");
        $('input#password').removeClass('required');
      }
    }
  //special characters validation for password
      // for (var i = 0; i < document.Registration.password.value.length; i++) {
      //   if (iChars.indexOf(document.Registration.password.value.charAt(i)) != -1) 
      //     {
      //       //errorMessage("label_password", "(Special characters are not allowed)");
      //       $('input#password').addClass('required');
      //       return false;
      //     }
      // }

    //confirm password validation
    if(document.forms["Registration"]["confpassword"]){

      var confpassword=document.forms["Registration"]["confpassword"].value;
      confpassword = $.trim(confpassword);
      $("#confpassword").val(confpassword);
      if (confpassword != password)
        {
          //errorMessage("label_confpassword", "(Password not matching)");
          $('input#confpassword').addClass('required');
          invalid = true;
      }else{
        //errorMessage("label_confpassword", "");
        $('input#confpassword').removeClass('required');
      }
    }

    //catcha validation
    var recaptcha_response_field=document.forms["Registration"]["recaptcha_response_field"].value;
    recaptcha_response_field = $.trim(recaptcha_response_field);
    $("#recaptcha_response_field").val(recaptcha_response_field);
      if (recaptcha_response_field==null || recaptcha_response_field==""){
          //errorMessage("label_firstname", "");
          $('#recaptcha_area').addClass('required');
         invalid = true;
      }else{
        //errorMessage("label_firstname", "");
        $('#recaptcha_area').removeClass('required');
          
      }

    if(invalid){
      //console.log("validation failed");
      return false;
    }

 		return true;

	}
  
  //validate login.php

  //editprofile validation

  function validateEditProfileForm(){
    var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?";
    //validate firstname
    var firstname=document.forms["Registration"]["firstname"].value; 
    var firstname_max_length=64; 
    if (firstname==null || firstname=="")
      {
        //errorMessage("label_firstname", "(This field is required)");
        $('input#firstname').addClass('required');
        invalid = true;
    }else if(firstname.length>firstname_max_length){
        //errorMessage("label_firstname", "(first name should not be greater than "+firstname_max_length+" characters)");
        $('input#firstname').addClass('required');
        invalid = true;
    }else{
      //errorMessage("label_firstname", "");
      $('input#firstname').removeClass('required');
    }

  //special characters validation for firstname
  for (var i = 0; i < document.Registration.firstname.value.length; i++) {
    if (iChars.indexOf(document.Registration.firstname.value.charAt(i)) != -1)
    {
      //errorMessage("label_firstname", "(Special characters are not allowed)");
      $('input#firstname').addClass('required');
    return false;
    }
  }

    //validate lastname
    var lastname=document.forms["Registration"]["lastname"].value;
    var lastname_max_length=64;
    if (lastname==null || lastname=="")
      {
        //errorMessage("label_lastname", "(This field is required)");
        $('input#lastname').addClass('required');
        invalid = true;
    }else if(lastname.length>lastname_max_length){
        //errorMessage("label_lastname", "(last name should not be greater than "+lastname_max_length+" characters)");
        $('input#lastname').addClass('required');
        invalid = true;
    }else{
      //errorMessage("label_lastname", "");
      $('input#lastname').removeClass('required');
    }
  //special characters validation for lastname
      for (var i = 0; i < document.Registration.lastname.value.length; i++) {
        if (iChars.indexOf(document.Registration.lastname.value.charAt(i)) != -1) 
          {
            //errorMessage("label_lastname", "(Special characters are not allowed)");
           $('input#lastname').addClass('required'); 

            return false;
          }
      }


    //email validation
    if(document.forms["Registration"]["email"]){
      var email=document.forms["Registration"]["email"].value;
      var email_max_length=128;
      var email_min_length=7;
      if (email==null || email=="")
        {
          //errorMessage("label_email", "(This field is required)");
         $('input#email').addClass('required'); 
          invalid = true;
      }else if(email.length>email_max_length || email.length<email_min_length){
          //errorMessage("label_email", "(email length should be between "+email_min_length+" & "+email_max_length+")");
          $('input#email').addClass('required');
          invalid = true;
      }else if(!email.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)){
        //errorMessage("label_email", "Please enter valid Email");
        $('input#email').addClass('required');
        invalid = true;
      }else{
        //errorMessage("label_email", "");
        $('input#email').removeClass('required');
      }
    }

  

    if(invalid){
      // console.log("validation failed");
      return false;
    }

    return true;

  }
  

	//dynamic error message 
    function errorMessage(label_id, message){
    	// document.getElementById("label_fusername").innerHTML = "Please enter username";
      document.getElementById(label_id).innerHTML = message;
    }

  // Ajax Edit
function showUser()
{
  var form_validation = validateEditProfileForm();
  
  if(form_validation==false){
    return false;
  }
  
  
// return false;
  var firstname  = document.getElementById("firstname").value;
  var lastname   = document.getElementById("lastname").value;
  var email      = document.getElementById("email").value;
 
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }

xmlhttp.onreadystatechange=
  function(){
    if (xmlhttp.readyState==4 && xmlhttp.status==200){
        document.getElementById("submit").innerHTML=xmlhttp.responseText;
    }
  }

xmlhttp.open("POST","process.php?form_type=Update",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");

xmlhttp.send("firstname="+firstname+"&lastname="+lastname+"&email="+email);

return false;
}

//login validation
function loginValidateForm(){
    var invalid = '';
      //validate username
      var username=document.forms["loginpage"]["username"].value;
      var username_max_length=128;
      var username_min_length=7;
        if(username==null || username==''){
        $('input#username').addClass('required');
              invalid = true;
      }else if(username.length>username_max_length || username.length<username_min_length){
          //errorMessage("username", "(username length should be between "+email_min_length+" & "+email_max_length+")");
          $('input#username').addClass('required');
          invalid = true;
      }else if(!username.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)){
        //errorMessage("label_email", "Please enter valid Email");
        $('input#username').addClass('required');
        invalid = true;
      }else{
        $('input#username').removeClass('required');
      }
      //validate password
    var password=document.forms["loginpage"]["password"].value;
    var password_max_length= 64;
    var password_min_length= 6;
      if(password==null || password==''){
        $('input#password').addClass('required');
              invalid = true;
      }else if(password.length > password_max_length || password.length < password_min_length){
          //errorMessage("label_password", "(Required minimum "+password_min_length+" characters)");
          $('input#password').addClass('required');
          invalid = true;
      }else{
        $('input#password').removeClass('required');
      } 

      if(invalid){
        //console.log("validation failed");
        return false;
      } 

      return true;

  }
