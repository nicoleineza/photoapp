$(document).ready(function(){
    function checkPasswordMatch() {
        var password = $("#password").val();
        var confirmPassword = $("#confirm_password").val();
        if (password !== confirmPassword) {
            $("#confirm_password_error").html("Passwords do not match!");
        } else {
            $("#confirm_password_error").html("");
        }
    }
    $("#confirm_password").keyup(checkPasswordMatch);
    
    $("#signupForm").submit(function(event){
        event.preventDefault(); 
        checkPasswordMatch();
        if ($("#confirm_password_error").html() !== "") {
            return; 
        }
        var formData = $(this).serialize();
        $.ajax({
            url: '../functions/signup_action.php',
            type: 'post',
            data: formData,
            success: function(response){
                if(response.trim() === "success"){
        
                    $("#signupForm")[0].reset();
                    
                    window.location.replace("../login/login.php");
                    
                    alert("Signed up successfully!");
                }else{
                    $("#signup_message").html(response);
                }
            }
        });
    });
    $("#username").keyup(function(){
        var username = $(this).val().trim();
        if(username != ''){
            $.ajax({
                url: '../functions/check_username.php',
                type: 'post',
                data: {username: username},
                success: function(response){
                    $('#username_error').html(response);
                }
            });
        }else{
            $("#username_error").html("");
        }
    });
    $("#email").keyup(function(){
        var email = $(this).val().trim();
        if(email != ''){
            $.ajax({
                url: '../functions/check_email.php',
                type: 'post',
                data: {email: email},
                success: function(response){
                    $('#email_error').html(response);
                }
            });
        }else{
            $("#email_error").html("");
        }
    });
});