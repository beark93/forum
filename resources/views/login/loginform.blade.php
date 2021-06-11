<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>
        <div class="login_form">
            <form method="POST" name="loginForm" id="loginForm">
                @csrf
                <div class="id">
                    <div class="info">
                        아이디
                    </div>
                    <div class="val">
                        <input type="text" name="id" value="" maxlength="20" />
                    </div>
                </div>
                <div class="pw">
                    <div class="info">
                        비밀번호
                    </div>
                    <div class="val">
                        <input type="pw" name="pw" value="" maxlength="20" />
                    </div>
                </div>
                <div class="btn_area">
                    <div class="btn_submit join" id="join">회원가입</div>
                    <div class="btn_submit login" id="login">로그인</div>
                </div>
            </form>
        </div>

        <script>
            $(function() {
                $('#login').click(function(e) {
                    e.preventDefault();

                    if(formCheck() === false) {
                        return false;
                    }

                    var postFormData = $("form[name=loginForm]").serialize();

                    ajaxLoginAuth(postFormData);
                });

                $('#join').click(function(e) {
                    e.preventDefault();

                    location.href = './user/create';
                });
            });

            function formCheck() {
                var loginForm = document.loginForm;

                if(loginForm.id.value === "") {
                    alert("ID를 입력해주세요.");
                    return false;
                }

                if(loginForm.pw.value === "") {
                    alert("비밀번호를 입력해주세요.");
                    return false;
                }

                return true;
            }

            function ajaxLoginAuth(postFormData) {
                $.ajax({
                    type: "POST",
                    url:"/login",
                    data:postFormData,
                    dataType:"json",
                    success : function(result){
                        if(result.result == "success") {
                            alert(result.msg);

                            window.opener.location.reload();

                            window.close();
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
