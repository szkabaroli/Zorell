$(document).ready(function () {

    var login_php = "loginprocess.php";
    var post_php = 'postdata.php';
    var stream_php = 'stream.php'
    var post_template;
    var friend_template;
    var timer;
    var poll_xhr;
    var page_num = 1;
    var ammount = 10;
    var friend_list = $('#friend-list');
    var friend_header_title = $('#friend-header-title');
    var friend_footer = $('#friend-footer');
    var friend_footer_title = $('#friend-footer-title');
    var on_timeline = true;

    $('#friends-page').hide();
    $('#settings-page').hide();
    $('#settings-password-page').hide();

    $.ajax({
        url: "public/templates/post.htm",
        type: 'GET',
        success: function (response) {
            post_template = response;
        },
        dataType: 'html',
        complete: function () {
            poll();
        }
    });

    $.ajax({
        url: "public/templates/friend.htm",
        type: 'GET',
        success: function (response) {
            friend_template = response;
        },
        dataType: 'html'
    });

    $(document).on('click', '.round-btn', function () {

        var icon = $(this).find('i');

        card = $(this).parent().parent();

        fid = card.data('fid');
        fon = card.data('fon');

        $(this).toggleClass('green', fon);
        icon.toggleClass('fa-plus', fon);
        icon.toggleClass('fa-check', 1 - fon);

        if (fon == 0) {
            card.data('fon', 1);
            $.post('server/api/friendlist.php', {
                'action': 'addfriend',
                'friendid': fid
            });
        } else {
            card.data('fon', 0);
            $.post('server/api/friendlist.php', {
                'action': 'removefriend',
                'friendid': fid
            });
        }
    });

    $('#post-text').on("keyup", function () {
        if ($(this).val().length) {
            $('#post-btn').prop('disabled', false);
        } else {
            $('#post-btn').prop('disabled', true);
        }
    });

    ///Ezt kell megcsinálni
    $('#search-text').on("keyup", function () {
        var text = $(this).val();

        if (text.length) {


            friend_header_title.text('Search Result');
            $.ajax({
                url: "/server/api/friendlist.php",
                type: "POST",
                data: {
                    "action": 'search',
                    "text": text
                },
                success: function (response) {
                    $('.friend-card').remove();
                    if (response.length) {
                        friend_footer_title.text('');
                        $.each(response, function (i, result) {
                            var friend_card = CreateFriendCard(result.user_id, result.user_fname, result.user_lname, result.user_subname, result.user_avatar, result.user_isfriend);
                            friend_footer.before(friend_card);

                        });
                    } else {
                        friend_footer_title.text('No people found!');

                    }

                },
                dataType: "json"
            });

        } else {

            $('.friend-card').remove();
            friend_footer_title.text('');
            friend_header_title.text('Friends');
            LoadFriends();
        }
    });

    $('#settings-img').on('change', function () {
        var file = this.files[0];
        $('#settings-img-text').text(file.name);
    });




    $('#timeline-btn').click(function () {
        $('#friends-page').hide();
        $('#settings-page').hide();
        $('#timeline-page').show();
        $('.friend-card').remove();
        $('a').removeClass('menu-open');
        $(this).addClass('menu-open');
        on_timeline = true;
        poll();
    });

    $('#friends-btn').click(function () {
        $('#friends-page').show();
        $('#timeline-page').hide();
        $('#settings-page').hide();
        $('a').removeClass('menu-open');
        $(this).addClass('menu-open');
        $('.friend-card').remove();
        on_timeline = false;
        StopPoll();
        LoadFriends();
    });
    $('#settings-btn').click(function () {
        $('#settings-page').show();
        $('#friends-page').hide();
        $('#timeline-page').hide();
        $('a').removeClass('menu-open');
        $(this).addClass('menu-open');
        on_timeline = false;
        StopPoll();
    });

    $('#settings-user-btn').click(function () {
        console.log('dsad');
        $(this).addClass('setting-open');
        $('#settings-password-btn').removeClass('setting-open');
        $('#settings-password-page').hide();
        $('#settings-user-page').show();
    });

    $('#settings-password-btn').click(function () {
        console.log('dsadsda');
        $(this).addClass('setting-open');
        $('#settings-user-btn').removeClass('setting-open');
        $('#settings-password-page').show();
        $('#settings-user-page').hide();
    });


    UpdateProfile();

    function UpdateProfile() {
        $.get('server/api/loginprocess.php', {
            'action': 'getuser'
        }, function (response) {
            $('#menu-name').text(response.user_fname + ' ' + response.user_lname);
            $('#menu-subname').text('#' +  response.user_subname);
            $('#menu-avatar').attr('src', response.user_avatar);
            $('#settings-fname').attr('placeholder', response.user_fname);
            $('#settings-lname').attr('placeholder', response.user_lname);
            $('#settings-subname').attr('placeholder', response.user_subname);
            $('#settings-email').attr('placeholder', response.user_email);
        }, 'json');

    }


    function LoadFriends() {
        $.ajax({
            url: "/server/api/friendlist.php",
            type: "POST",
            data: {
                'action': 'getfriends'
            },
            success: function (response) {
                if (response.length) {
                    friend_footer_title.text('');
                    $.each(response, function (i, result) {
                        var friend_card = CreateFriendCard(result.user_id, result.user_fname, result.user_lname, result.user_subname, result.user_avatar, 1);
                        friend_footer.before(friend_card);
                    });
                } else {
                    friend_footer_title.text('You have no friends');
                }
            },
            dataType: "json"
        });
    }

    function poll() {
        poll_xhr = $.ajax({
            url: "/server/api/stream.php",
            type: "POST",
            data: {
                "page": page_num,
                "ammount": ammount
            },
            success: function (response) {
                if (response.action == 'init') {
                    $.each(response.updates, function (i, update) {
                        console.log(response.updates);
                        var post = CreatePost(update.post_id, update.user_fname, update.user_lname, update.user_subname, update.user_avatar, update.post_date, update.post_text);
                        $('#posts').append(post);
                    });
                } else if (response.action == 'update') {
                    $.each(response.updates, function (i, update) {
                        UpdatePost(update.post_id, update.user_fname, update.user_lname, update.user_subname, update.user_avatar, update.post_date, update.post_text);
                    });
                    $.each(response.deletes, function (i, delete_item) {
                        DeletePost(delete_item.post_id);
                    });
                } else if (response.action == 'pageload') {
                    $('#posts').empty();
                    $.each(response.updates, function (i, update) {
                        var post = CreatePost(update.post_id, update.user_fname, update.user_lname, update.user_subname, update.user_avatar, update.post_date, update.post_text);
                        $('#posts').append(post);
                    });
                }

                $('.post-date').each(function (i, item) {
                    var date = $(this).data("date");
                    UpdateTime(date, function (text) {
                        $(item).text(text);
                    });
                });
            },
            dataType: "json",
            complete: timer = setTimeout(function () {
                poll()
            }, 5000),
            timeout: 2000
        });
    };

    function StopPoll() {
        clearTimeout(timer);
        poll_xhr.abort();
    }
    var fired = false;

    $(window).scroll(function () {
        fired = false
        if ($(window).scrollTop() + window.innerHeight > ($(document).height() - 1) && fired == false && on_timeline) {
            StopPoll();
            page_num++;
            poll();

            fired = true;
        }
    });

    $('#form-settings-user').submit(function (event) {
        event.preventDefault();
        var data = new FormData(this);
        data.append('action', 'updateprofile');
        $.ajax({
            url: "server/api/settingsprocess.php",
            type: "POST",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                if (response == 'error') {
                    alert('wrong filetype');
                } else {
                    UpdateProfile();
                    $('#form-settings-pass').reset();
                    $('#settings-img-text').text('Select Image');
                }
            }
        });
    });

    $('#form-settings-pass').submit(function (event) {
        event.preventDefault();
        var data = new FormData(this);
        console.log('dsad');
        data.append('action', 'updatepass');
        $.ajax({
            url: "server/api/settingsprocess.php",
            type: "POST",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                if (response == 'success') {
                    $('#form-settings-pass')[0].reset();
                    alert('Password successfully changed!')
                } else {
                    alert('Failed to change password!');
                }
            }
        });
    });


    $('#post-btn').click(function () {
        var post = $('#post-text').val();
        $('#post-btn').prop('disabled', true);
        $('#post-text').val('');
        $.post('server/api/postdata.php', {
            'action': 'sendpost',
            'post': post
        }, function (response) {
            StopPoll();
            poll();
        });
    });


    function LoadMorePosts() {
        $.post('server/api/postdata.php', {
            "action": "getnewposts",
            "page": page_num,
            "ammount": 5
        }, function (data) {
            $.each(data.posts, function (i, post) {
                new_post = CreatePost(post.post_id, post.user_fname,user_lname, post.user_subname, post.user_avatar, post.post_date, post.post_text);
                $('#posts').append(new_post);
            })
            page_num++;
        });
    }

    function CreatePost(post_id, user_fname, user_lname, user_subname, avatar, date, post_text) {
        var p = post_template;
        p = p.replace('{{date}}', date);
        p = p.replace('{{post_id}}', post_id);
        p = p.replace('{{user_name}}', user_fname + ' ' + user_lname);
        p = p.replace('{{user_subname}}', '#' + user_subname);
        p = p.replace('{{avatar}}', avatar);
        p = p.replace('{{post_text}}', post_text);
        p = $(p);
        return p;
    }

    function UpdatePost(post_id, user_fname, user_lname, user_subname, avatar, date, post_text) {
        var post = $('[data-pid="' + post_id + '"]');
        if (post.length == 0) {
            new_post = CreatePost(post_id, user_fname, user_lname, user_subname, avatar, date, post_text);
            $('#posts').prepend(new_post);
        } else {
            post.find(".post").text(post_text);
            post.find(".avatar-name").text(user_fname + ' ' + user_lname);
            post.find(".avatar-subname").text('#' + user_subname);
            post.find(".avatar").attr('src', avatar);
            post.find(".post-date").data('date', date);
        }
    }

    function DeletePost(post_id) {
        var post = $('[data-id="' + post_id + '"]');
        $(post).remove();
    }

    function CreateFriendCard(friend_id, friend_fname, friend_lname, friend_subname, friend_avatar, friend_or_not) {
        var f = friend_template;

        f = f.replace('{{friend_id}}', friend_id);
        f = f.replace('{{friend_name}}', friend_fname + ' ' + friend_lname);
        f = f.replace('{{friend_subname}}', '#' +  friend_subname);
        f = f.replace('{{friend_avatar}}', friend_avatar);
        f = f.replace('{{friend_or_not}}', friend_or_not);
        if (friend_or_not == 1) {
            f = f.replace('{{icon}}', 'fa-check');
            f = f.replace('{{class}}', 'green');
        } else {
            f = f.replace('{{icon}}', 'fa-plus');
            f = f.replace('{{class}}', '');
        }
        return $(f);

    };

    function UpdateTime(date, callback) {

        var date = new Date(date)
        date.setHours(date.getHours() + 2);

        var seconds = Math.floor((new Date() - date) / 1000);
        var minutes = Math.round(seconds/60);
        var hours = Math.round(minutes/60);
        var days = Math.round(hours/24);
        var weeks = Math.round(days/7);
        var months = Math.round(days/30.4);
        var years =Math.round(days/365.25);

        if(years >= 2){
            callback(years + " years ago")
        }
        else if(years >= 1){ 
            callback("1 year ago")
        }
        else if(months >= 2){ 
            callback(months + " months ago");
        }
        else if(months >= 1){ 
            callback("1 month ago");
        }
        else if(weeks >= 2){ 
            callback(weeks + " weeks ago");
        }
        else if(weeks >= 1){ 
            callback("1 week ago");
        }
        else if(days >= 2){ 
            callback(days + " days ago");
        }
        else if(days >= 1){ 
            callback("1 day ago");
        }
        else if(hours >= 2){ 
            callback(hours + " hours ago");
        }
        else if(hours >= 1){ 
            callback("1 hour ago");
        }
        else if(minutes >= 2){ 
            callback(minutes + " minutes ago");
        }
        else if(minutes >= 1) {
            callback("1 minute ago");
        }
        else {
            callback("just now");
        }
    }



});