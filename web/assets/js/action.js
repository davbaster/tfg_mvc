$(document).ready(function(){

     // request para registro de usuario
    $("#add-user-btn").click(function(e){
        if($("#add-user-form")[0].checkValidity()){
            e.preventDefault();
            $("#add-user-btn").val('Por favor espere..');
            if($("#password").val() != $("#cpassword").val() ){
                $("#passError").text('Password no coincide');
                $("add-user-btn").val('Agregar');
            }
            else{
                $("#passError").text('');
                $.ajax({
                    url: './php/action.php',
                    method: 'post',
                    data: $("#add-user-form").serialize()+'&action=agregar',
                    success:function(response){
                        $("#add-user-btn").val('Agregar');
                        //si php responde agregar                         
                        if(response == 'agregado'){
                            window.location = './usuarios.php';
                            
                        }
                        else{
                            $("#regAlert").html(response);
                        }
                    }


                });
            }
        }
    });



    // login ajax request interpreter
    $("#login-btn").click(function(e){
        if($("#login-form")[0].checkValidity()){
            e.preventDefault();

            $("#login-btn").val('Por favor espere...');
            $.ajax({
                url: './php/action.php',
                method: 'post',
                data: $("#login-form").serialize()+'&action=login',
                success:function(response){
                    console.log('Llega a success function '+response);
                    $("#login-btn").val('Ingresar');
                    if(response === 'login'){
                        window.location = './principal.php';
                    }
                    else{
                        $("#loginAlert").html(response);
                    }
                }
            });
        }
    });   
    
    
});

