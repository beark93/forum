<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <title>Forum - KimBY</title>

        <style>
            .body {width:100%; position: relative;}

            .top {width:100%; position: relative; margin-bottom: 30px;}
            .top .title_area {width:900px; position: relative; margin: 0 auto;}
            .top .title_area .title {width:100%; text-align: center; font-size: 4em;}
            .top .title_area .login {position: absolute; bottom:0px; right:10px; cursor: pointer;}
            .top .title_area .logout {position: absolute; bottom:0px; right:10px; cursor: pointer;}
            .contents {width:100%; position: relative;}

            /* post list */
            .contents .posts {width:900px; position: relative; margin: 0 auto;}
            .contents .posts .post_list {width:100%; height position: relative; margin-bottom:20px;}
            .contents .posts .post_list .empty_list{width: 100%; text-align: center; font-size: 1em;}
            .contents .posts .post_list .post{width:100%; text-align: center; font-size: 1em; margin-bottom:5px; cursor: pointer;}
            .contents .posts .nav_list {width:100%; text-align: center; margin: 0 auto; position: relative;}
            .contents .posts .nav_list .nav{width:10px; text-align: center; cursor: pointer; display: inline-block;}
            .contents .posts .nav_list .nav.now{font-weight: bold;}
            .contents .posts .nav_list .nav:not(:first-child){margin-left:5px;}
            .contents .posts .write {position: absolute; bottom:0px; right:10px; cursor: pointer;}

            /* post post */
            .contents .post {width:900px; position: relative; margin: 0 auto;}
            .contents .post .post_info {width:100%; text-align: center; position: relative; padding-bottom: 10px; border-bottom: 1px solid black;}
            .contents .post .title {width:100%; text-align: center; margin-bottom: 5px; font-size: 1.5em;}
            .contents .post .slash {display: inline-block; margin: 0 5px;}
            .contents .post .writer {display: inline-block;}
            .contents .post .date {display: inline-block;}
            .contents .post .content {margin-top: 10px}
            .contents .post .btn_area {position: absolute; top:10px; right:10px;}
            .contents .post .btn_area .edit{display: inline-block; cursor: pointer;}
            .contents .post .btn_area .delete{display: inline-block; cursor: pointer; color: red;}
            .contents .post .btn_area .back{display: inline-block; cursor: pointer;}
        </style>
    </head>
    <body>
        <div class="top">
            <div class="title_area">
                <div class="title">Forum - KimBY</div>
                @if($login !== "Y")
                <div class="login" id="goLogin">로그인</div>
                @else
                <div class="logout" id="goLogout">로그아웃</div>
                @endif
            </div>
        </div>
        <div class="contents">
        </div>

        <script>
            $(function(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#goLogin').click(function(e) {
                    e.preventDefault();

                    loginPop();
                });

                $('#goLogout').click(function(e) {
                    e.preventDefault();

                    ajaxLogout();
                });

                ajaxPostList(1);
            });

            function loginPop() {
                window.open('/login', '_blank', "toolbar=no,scrollbars=no,resizable=no,width=500,height=600");
            }

            function ajaxLogout() {
                $.ajax({
                    type: "GET",
                    url:"/logout/",
                    dataType:"json",
                    success : function(result){
                        location.reload();
                    },
                    error : function(a, b, c){
                        console.log(a + b + c);
                    }
                });
            }

            function ajaxPostStore(postFormData) {
                $.ajax({
                    type: "POST",
                    url:"/post",
                    data:postFormData,
                    dataType:"json",
                    success : function(result){
                        if(result.result == "success") {
                            alert(result.msg);

                            ajaxPostList(1);
                        } else {
                            alert(result.msg);
                        }
                    },
                    error : function(a, b, c){
                        console.log(a + b + c);
                    }
                });
            }

            function ajaxPostList(page) {
                $.ajax({
                    type: "GET",
                    url:"/post?page=" + page,
                    dataType:"text",
                    success : function(result){
                        $('.contents').html(result);
                    },
                    error : function(a, b, c){
                        alert(a + b + c);
                    }
                });
            }

            function ajaxPostView(no) {
                $.ajax({
                    type: "GET",
                    url:"/post/" + no,
                    dataType:"json",
                    success : function(result){
                        if(result.result == "success") {
                            $('.contents').html(result.view);
                        } else {
                            alert(result.msg)
                        }
                    },
                    error : function(a, b, c){
                        alert(a + b + c);
                    }
                });
            }

            function ajaxPostCreate() {
                $.ajax({
                    type: "GET",
                    url:"/post/create",
                    dataType:"json",
                    success : function(result){
                        if(result.result == "success") {
                            $('.contents').html(result);
                        } else {
                            alert(result.msg)
                        }
                    },
                    error : function(a, b, c){
                        alert(a + b + c);
                    }
                });
            }

            function ajaxPostStore(postFormData) {
                $.ajax({
                    type: "POST",
                    url:"/post",
                    data:postFormData,
                    dataType:"json",
                    success : function(result){
                        if(result.result == "success") {
                            alert(result.msg);

                            ajaxPostList(1);
                        } else {
                            alert(result.msg);
                        }
                    },
                    error : function(a, b, c){
                        console.log(a + b + c);
                    }
                });
            }

            function ajaxPostEdit(no) {
                $.ajax({
                    type: "GET",
                    url:"/post/" + no + "/edit",
                    dataType:"json",
                    success : function(result){
                        if(result.result == "success") {
                            $('.contents').html(result.view);
                        } else {
                            alert(result.msg)
                        }
                    },
                    error : function(a, b, c){
                        alert(a + b + c);
                    }
                });
            }

            function ajaxPostUpdate(postFormData, no) {
                $.ajax({
                    type: "PUT",
                    url:"/post/" + no,
                    data:postFormData,
                    dataType:"json",
                    success : function(result){
                        if(result.result == "success") {
                            alert(result.msg);

                            ajaxPostView(no);
                        } else {
                            alert(result.msg);
                        }
                    },
                    error : function(a, b, c){
                        console.log(a + b + c);
                    }
                });
            }

            function ajaxPostDelete(no) {
                $.ajax({
                    type: "DELETE",
                    url:"/post/" + no,
                    dataType:"json",
                    success : function(result){
                        if(result.result == "success") {
                            alert(result.msg);

                            ajaxPostList(1);
                        } else {
                            alert(result.msg);
                        }
                    },
                    error : function(a, b, c){
                        console.log(a + b + c);
                    }
                });
            }
        </script>
    </body>
</html>
