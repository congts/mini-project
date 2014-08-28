<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php
    session_start();
    ?>
    <meta charset="utf-8">
    <script src="//code.jquery.com/jquery-1.10.0.min.js"></script>
    <style>
        .dim {opacity: 0.5;}
        ul {
            margin: 0px;
        }
        ul li {
            list-style-type: none;
            margin: 5px;
        }
        ul li span {
            text-color: white;
            background-color: navajowhite;
            margin-right: 10px;
        }
        .chat-area {
            width: 500px;
            margin: 0 auto;
            position: relative;
            background-color: #fafad2;
        }
        .chat-area .box-chat {
            height: 250px;
            overflow-x:hidden;
            overflow-y:visible;
        }
        .chat-area .input-chat {
            position: relative;
        }
        .chat-area .input-chat .input {
            width: 380px;
            height: 50px;
            padding: 5px;
            margin: 15px 5px;
        }
        .chat-area .input-chat submit {
            position: absolute;
            top: 19px;
            border: solid 1px grey;
            padding: 18px 20px;
            margin-left: 5px;
            background-color: wheat;
            cursor: pointer;
        }
        .start {
            position: absolute;
            top: 100px;
            left: 200px;
        }
    </style>
</head>
<body>
    <div class="chat-area">
        <div class="box-chat"></div>
        <a href="" class="start">Start chatting</a>
        <div class="input-chat">
            <textarea class="input" name="user" disabled></textarea>
            <submit>Enter</submit>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(function(){
            jQuery('.start').click(function(e){
                jQuery('.input').removeAttr('disabled');
                jQuery(this).hide();
                jQuery('.box-chat').removeClass('dim');
                refreshData();
                getUserId();
                e.preventDefault();
            });
            jQuery('.input-chat .input').keypress(function(e){
                if(e.which==13){
                    insertChatText();
                    e.preventDefault();
                }
            });
            jQuery('submit').click(function(){
                insertChatText();
            });
        });


        var i = 1;
        var temp = "";
        function refreshData(){
            var time = setTimeout(refreshData,3000);
            jQuery.ajax({
                type: "POST",
                url: "data.php",
                success: function(json){
                    var data = jQuery.parseJSON(json);
                    if (data.success == 1) {
                        //auto turn off chat after 20s if user does not chat
                        if (i == 20) {
                            clearTimeout(time);
                            jQuery('.input').attr('disabled','disabled');
                            jQuery('.start').show();
                            jQuery('.box-chat').addClass('dim');
                        }
                        i++;
                        if (i > 20) {
                            i = 1;
                        }

                    }
                    jQuery('.box-chat').html(data.content);
                },
                error: function(){
                    jQuery('.box-chat ul').html("Something wrong!!!");
                }
            });
        }

        function getUserId(){
            jQuery.ajax({
                type: "POST",
                url: "user.php",
                success: function(data){
                    jQuery('.input-chat .input').attr('name','user'+data);
                },
                error: function(){
                    alert("Something wrong!!!");
                }
            });
        }

        function insertChatText(){
            i = 1;
            var input = jQuery('.input-chat .input').val();
            jQuery('.input-chat .input').val('');
            var user = jQuery('.input-chat .input').attr('name');
            if(input != "" && input != " "){
                jQuery('.box-chat ul').append('<li class="content dim"><span>User '+ user.replace("user","") +': </span>'+input+'</li>');
                jQuery.ajax({
                    type: "POST",
                    url: "insert.php",
                    data: 'val='+input+'&user='+user,
                    success: function(data){
                        if (data == 'success') {
                            jQuery('.box-chat ul li').removeClass('dim');
                        }
                    },
                    error: function(){
                        alert('Error');
                    }
                });
            }
            jQuery(".box-chat").animate({ scrollTop: jQuery(".box-chat")[0].scrollHeight }, "fast");
        }

    </script>
</body>
