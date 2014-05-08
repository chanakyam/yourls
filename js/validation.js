

//  validation 
function validateForm(){
var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?";
var invlid = '';
    //validate firstname
    var firstname=document.forms["Registration"]["firstname"].value;  
    if (firstname==null || firstname=="")
      {
        errorMessage("label_firstname", "(This field is required)");
        invalid = true;
    }
    else{
      errorMessage("label_firstname", "");
    }

  //special characters validation for firstname
  for (var i = 0; i < document.Registration.firstname.value.length; i++) {
    if (iChars.indexOf(document.Registration.firstname.value.charAt(i)) != -1)
    {
      errorMessage("label_firstname", "(Special characters are not allowed)");
    return false;
    }
  }

    //validate lastname
    var lastname=document.forms["Registration"]["lastname"].value;
    if (lastname==null || lastname=="")
      {
        errorMessage("label_lastname", "(This field is required)");
        invalid = true;
    }else{
      errorMessage("label_lastname", "");
    }
  //special characters validation for firstname
      for (var i = 0; i < document.Registration.lastname.value.length; i++) {
        if (iChars.indexOf(document.Registration.lastname.value.charAt(i)) != -1) 
          {
            errorMessage("label_lastname", "(Special characters are not allowed)");
            return false;
          }
      }


    //email validation
    if(document.forms["Registration"]["email"]){
      var email=document.forms["Registration"]["email"].value;
      if (email==null || email=="")
        {
          errorMessage("label_email", "(This field is required)");
          invalid = true;
      }else if(!email.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)){
        errorMessage("label_email", "Please enter valid Email");
        invalid = true;
      }else{
        errorMessage("label_email", "");
      }
    }

    //validate password
    if(document.forms["Registration"]["password"]){
      var password=document.forms["Registration"]["password"].value;
      if (password==null || password==""){
          errorMessage("label_password", "(This field is required)");
          invalid = true;
      }else{
        errorMessage("label_password", "");
      }
    }
  //special characters validation for password
      for (var i = 0; i < document.Registration.password.value.length; i++) {
        if (iChars.indexOf(document.Registration.password.value.charAt(i)) != -1) 
          {
            errorMessage("label_password", "(Special characters are not allowed)");
            return false;
          }
      }

    //confirm password validation
    if(document.forms["Registration"]["confpassword"]){

      var confpassword=document.forms["Registration"]["confpassword"].value;
      if (confpassword != password)
        {
          errorMessage("label_confpassword", "(Password not matching)");
          invalid = true;
      }else{
        errorMessage("label_confpassword", "");
      }
    }

    if(invalid){
       console.log("validation failed");
      return false;
    }

 		return true;

	}
  
  //validate login.php

  //myprofile validation

  function validateMyProfileForm(){
    var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?";
    //validate firstname
    var firstname=document.forms["Registration"]["firstname"].value;  
    if (firstname==null || firstname=="")
      {
        errorMessage("label_firstname", "(This field is required)");
        invalid = true;
    }
    else{
      errorMessage("label_firstname", "");
    }

  //special characters validation for firstname
  for (var i = 0; i < document.Registration.firstname.value.length; i++) {
    if (iChars.indexOf(document.Registration.firstname.value.charAt(i)) != -1)
    {
      errorMessage("label_firstname", "(Special characters are not allowed)");
    return false;
    }
  }

    //validate lastname
    var lastname=document.forms["Registration"]["lastname"].value;
    if (lastname==null || lastname=="")
      {
        errorMessage("label_lastname", "(This field is required)");
        invalid = true;
    }else{
      errorMessage("label_lastname", "");
    }
  //special characters validation for firstname
      for (var i = 0; i < document.Registration.lastname.value.length; i++) {
        if (iChars.indexOf(document.Registration.lastname.value.charAt(i)) != -1) 
          {
            errorMessage("label_lastname", "(Special characters are not allowed)");
            return false;
          }
      }


    //email validation
    if(document.forms["Registration"]["email"]){
      var email=document.forms["Registration"]["email"].value;
      if (email==null || email=="")
        {
          errorMessage("label_email", "(This field is required)");
          invalid = true;
      }else if(!email.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)){
        errorMessage("label_email", "Please enter valid Email");
        invalid = true;
      }else{
        errorMessage("label_email", "");
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
  var form_validation = validateMyProfileForm();
  
  if(form_validation==false){
    return false;
  }
  
  
// return false;
  var firstname     = document.getElementById("firstname").value;
  var lastname      = document.getElementById("lastname").value;
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


