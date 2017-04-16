<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if(isset($_SESSION['last'])) {
    unset($_SESSION['last']);
}
?>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="icon" href="public/images/favicon.ico" type="image/x-icon">
        <title>Zorell</title>
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,500" rel="stylesheet">
        <script src="https://use.fontawesome.com/6164402c68.js"></script>
        <link rel="stylesheet" href="public/css/style.css">
        <script src="public/js/jquery-3.2.1.min.js" language="javascript" type="text/javascript"></script>
        <script src="public/js/app.js"></script>
    </head>

    <body>
        <div class='container'>

            <nav class="col-1">
                <div class="fixed white">
                    <div class="menu-wrap">
                        <div class="menu-box">
                            <div class="menu-header">
                                <img class="menu-avatar" id="menu-avatar" src=""></img>
                                <p id="menu-name" class="avatar-name"></p>
                                <p id="menu-subname" class="avatar-subname"></p>
                            </div>
                            <div class="menu-links">
                                <a href="#" id="timeline-btn" class="menu-open">Timeline</a><br>
                                <a href="#" id="friends-btn">Friends</a><br>
                                <a href="#" id="settings-btn">Settings</a><br>
                                <a href="server/api/logout.php" id="logout-btn">Logout</a>
                            </div>
                        </div>
                    </div>

                </div>
            </nav>
            <main class="col-2">
                <section id="timeline-page">
                    <div class="add-post" style="display: hidden;">
                        <div class="post-text-wrap">
                            <div class="post-text-box">
                                <textarea id="post-text" maxlength="150" class="post-text" name="post" placeholder="Write something..."></textarea>
                            </div>
                        </div>
                        <div class="post-menu">
                            <input id="post-btn" type="button" class="btn blue" value="Post" disabled>
                        </div>
                    </div>
                    <div id="posts">

                    </div>
                </section>
                <section id="friends-page">
                    <div class="search-bar">
                        <div class="search-text-wrap">
                            <div class="search-text-box">
                                <input id="search-text" type="search" class="search-text" placeholder="Search peoples..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div id="friend-list">
                        <div id="friend-header">
                            <p id="friend-header-title">Friends</p>
                        </div>
                        <div id="friend-footer">
                            <p id="friend-footer-title">You have no friends</p>
                        </div>
                    </div>
                </section>
                <section id="settings-page">
                    <div class="settings-wrap">
                        <div class="settings-box">
                            <div style="display: flex" class="settings-header">
                                <a id="settings-user-btn" href="#" class="setting-btn setting-open">User Settings</a>
                                <a id="settings-password-btn" href="#" class="setting-btn">Password</a>
                            </div>
                            <div class="settings">
                                <div id="settings-user-page">
                                    <form id="form-settings-user" action="" method="POST" enctype="multipart/form-data">
                                        <table>
                                            <tr>
                                                <td class="settings-text">Profile image</td>
                                                <td>
                                                    <input id="settings-img" type="file" class="file-input" name="profile_image" accept="image/jpeg, image/png">
                                                    <label id="settings-img-text" for="settings-img" class="settings-img-label blue">Select Image</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="settings-text">First Name</td>
                                                <td><input id="settings-fname" type="text" class="text-input" name="user_fname"
                                                        placeholder=""></td>
                                            </tr>
                                            <tr>
                                                <td class="settings-text">Last Name</td>
                                                <td><input id="settings-lname" type="text" class="text-input" name="user_lname"
                                                        placeholder=""></td>
                                            </tr>
                                            <tr>
                                                <td class="settings-text">Subname</td>
                                                <td><input id="settings-subname" type="text" class="text-input" name="user_subname"
                                                        placeholder=""></td>
                                            </tr>
                                            <tr>
                                                <td class="settings-text">Email</td>
                                                <td><input id="settings-email" type="text" class="text-input" name="user_email"
                                                        placeholder="E-mail"></td>
                                            </tr>
                                        </table>
                                        <input type="submit" class="btn blue bottom" value="Apply">
                                    </form>
                                </div>
                                <div id="settings-password-page">
                                    <form id="form-settings-pass" action="" method="POST">
                                    <table>
                                        <tr>
                                            <td class="settings-text">Old Password </td>
                                            <td><input name="user_passc" type="password" class="text-input"></td>
                                        </tr>
                                        <tr>
                                            <td class="settings-text">New Password </td>
                                            <td><input name="user_passn" type="password" class="text-input"></td>
                                        </tr>
                                        <tr>
                                            <td class="settings-text">Password Again </td>
                                            <td><input name="user_passna" type="password" class="text-input"></td>
                                        </tr>
                                    </table>
                                    <input type="submit" class="btn blue bottom" value="Change">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
            <aside class="col-3">

            </aside>
        </div>
    </body>

    </html>