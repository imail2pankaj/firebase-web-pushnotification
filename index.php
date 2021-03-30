<!DOCTYPE html>
<html lang="en">

<head>
    <title>Hello World!</title>
</head>

<body>
    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/8.3.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.0/firebase-messaging.js"></script>

    <!-- Just include firebase.js file. The other firebase will be internally registered from this file -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</body>


<form action="" method="post" id="frmRegister" class="form-signup text-center">
    <h1 class="h3 mb-3 font-weight-normal">Sign Up</h1>

    <div class="alert alert-danger text-center" style="display:none" id="errorDiv"></div>
    <div class="alert text-gray text-center" style="display:none" id="statusDiv"></div>
    <div class="alert alert-success text-center" style="display:none" id="successDiv"></div>

    <label for="name" class="sr-only">Email address</label>
    <input type="text" id="name" name="name" class="form-control" placeholder="Username" required autofocus>
    <input class="btn btn-lg btn-primary btn-block mb-3" id="btnSubmit" type="submit" value="Register">
    <a href="./login.php">Signin</a>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<script src="./firebase-init.js"></script>
<script>

$(document).ready(function() {
    var $frmRegister = $('#frmRegister');
    var $inpName = $('#name');
    var $errorDiv = $('#errorDiv');
    var $statusDiv = $('#statusDiv');
    var $successDiv = $('#successDiv');
    var $btnSubmit = $('#btnSubmit');
    var regName = "";

    $inpName.on('change', function() {
        regName = $(this).val().trim();
    });
    $frmRegister.on('submit', function(e) {
        e.preventDefault();

        if ($inpName.val().trim().length < 3) {
            showFormError('<i class="fa fa-warning"></i> Enter a valid name with at least 3 character (alphabets and spaces only)!');
            return;
        }
        requestNotificationPermission();
    });

    function showSuccessDiv(msg) {
        hideAlerts();
        $successDiv.html(msg).css({
            display: 'block'
        });
        $('html,body').animate({
            scrollTop: $successDiv.offset().top - 100
        }, 200);
    }

    function hideAlerts() {
        $('.alert').css({
            display: 'none'
        });
    }

    function showFormError(msg) {
        hideAlerts();
        $errorDiv.html(msg).css({
            display: 'block'
        });
        $('html,body').animate({
            scrollTop: $errorDiv.offset().top - 100
        }, 200);
        enableForm();
    }

    function disableForm(msg) {
        hideAlerts();
        $inpName.attr('disabled', true);
        $btnSubmit.attr('disabled', true);
        $statusDiv.html(undefined == msg ? 'Please wait...' : msg).css({
            display: 'block'
        });
    }

    function enableForm() {
        $statusDiv.css({
            display: 'none'
        });
        $inpName.removeAttr('disabled');
        $btnSubmit.removeAttr('disabled');
    }

    function requestNotificationPermission() {
        disableForm();
        Notification.requestPermission().then(showNotification);
    }

    function showNotification(permission) {

        if (permission === 'denied' || permission === 'default') {
            showFormError('You must grant notification permission in order to continue!');
            return;
        }

        messaging.getToken()
            .then(function (token) {
                $.ajax({
                    method: 'post',
                    dataType: 'json',
                    url: 'register.php',
                    data: {
                        name: regName,
                        token: token,
                        n: encodeURIComponent(btoa(regName)),
                        proceed_to_register: true,
                    },
                    success: function(data, textStatus, jqXHR) {
                        if (!data.success) {
                            return showFormError('Registration was not successful. Please, try again!');
                        }
                        $inpName.val('');
                        enableForm();

                        var successMessage = 'Registration was successful!<br>' +
                            'Click on the notification to view your login details';

                        showSuccessDiv(successMessage);
                    },
                    error: function(jqXHR, textStatus) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        showFormError("An error occured during registration. Please, try again later!");
                    }
                })
            })
            .catch(function (error) {
                updateUIForPushPermissionRequired();
                console.log('Error while fetching the token ' + error);
            });

    }

    if (regName != "") {
        requestNotificationPermission();
    }
})
</script>
<style>
html,
body {
  height: 100%;
}

body {
  padding-top: 80px;
  padding-bottom: 80px;
  background-color: #f5f5f5;
}

.form-signup {
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signup .form-control {
  position: relative;
  box-sizing: border-box;
  height: auto;
  padding: 10px;
  font-size: 16px;
}
.form-signup .form-control:focus {
  z-index: 2;
}
.form-signup input[type="text"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
</style>
</html>
