$(document).ready(function () {

    $('#tologin-btn').on('click', function () {
        $('#register-form').hide();
        $('#login-form').show();
    });

    $('#toregister-btn').on('click', function () {
        $('#register-form').show();
        $('#login-form').hide();
    });

    $('#login-btn').on('click', function () {

        var lemail = $('#login-email').val();
        var lpass = $('#login-pass').val();

        $.post('server/api/loginprocess.php', {
            'action': 'login',
            'lemail': lemail,
            'lpass': lpass
        }, function (response) {
            if (response == 'success') {
                window.location = 'index.php';
                window.logged_in = true;
            } else {
                alert('Sikertelen bejelntkezés!');
            }
        });
    });

    $('#register-btn').click(function () {

        var rfname = $('#register-fname').val();
        var rlname = $('#register-lname').val();
        var remail = $('#register-email').val();
        var rpass = $('#register-pass').val();

        $.post('server/api/loginprocess.php', {
            'action': 'register',
            'rfname': rfname,
            'rlname': rlname,
            'remail': remail,
            'rpass': rpass
        }, function (response) {
            if (response == 'success') {
                alert('Sikeres regisztráció!');
            } else {
                alert('Sikertelen regisztrácó!');
            }
        });
    });
});