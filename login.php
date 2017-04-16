<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="public/images/favicon.ico" type="image/x-icon">
    <title>Zorell Login</title>
    <link rel="stylesheet" href="public/css/style.css">
    <script src="public/js/jquery-3.2.1.min.js"></script>
    <script src="public/js/login.js"></script>
</head>

<body>
    <div class="container">
        <div class="login-col-1">
        </div>
        <div class="login-col-2">
            <div class="login-container">
                <div id="login-form">
                    <h3>Login</h3>
                    <input type="text" id="login-email" class="text-input" placeholder="E-mail" name="login-email"></br>
                    <input type="password" id="login-pass" class="text-input" placeholder="Password" name="pass"></br>
                    <input type="button" id="toregister-btn" class=" btn blue" value="To regiser">
                    <input type="button" id="login-btn" class="right btn blue" value="Login">
                </div>
                <div id="register-form" style="display: none">
                    <h3>Register</h3>
                    <input type="text" id="register-fname" placeholder="First Name" class="text-input" name="login-name"></br>
                    <input type="text" id="register-lname" placeholder="Last Name" class="text-input" name="login-name"></br>
                    <input type="text" id="register-email" placeholder="E-mail" class="text-input" name="login-email"></br>
                    <input type="password" id="register-pass" placeholder="Password" class="text-input" name="pass"></br>
                    <input type="button" id="tologin-btn" class="btn blue" value="To Login">
                    <input type="button" id="register-btn" class="right btn blue" value="Register">
                </div>
            </div>
        </div>
    </div>
</body>

</html>