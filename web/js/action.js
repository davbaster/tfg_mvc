$(document).ready(function(){

    // request para registro de usuario
   $("#register-btn").click(function(e){
       if($("#register-form")[0].checkValidity()){
           e.preventDefault();
           $("#register-btn").val('Por favor espere..');
           if(("#rpassword").val() != $("#cpassword").val() ){
               $("#passError").text('Password no coincide');
               $("register-btn").val('Sign Up');
           }
           else{
               $("#passError").text('');
               $.ajax({
                   url: 'assets/php/action.php',
                   method: 'post',
                   data: $("#register-form").serialize()+'&action=register',
                   sucess:function(response){
                       $("#register-btn").val('Sign Up');
                       if(response == 'register'){
                           window.location = 'principal.php';
                       }
                       else{
                           $("#regAlert").html(response);
                       }
                   }


               });
           }
       }
   });



   // login ajax request 
   $("#login-btn").click(function(e){
       if($("#login-form")[0].checkValidity()){
           e.preventDefault();

           $("login-btn").val()('Por favor espere...');
           $.ajax({
               url: 'php/action.php',
               method: 'post',
               data: $("#login-form").serialize()+'&action=login',
               sucess:function(response){
                   console.log(response);
               }
           });
       }
   });   
   
   
});

