<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>
        <div class="user_form">
            <form method="POST" name="userForm" id="userForm">
                @csrf
                @if($mode == "create")
                <div class="id">
                    <div class="info">
                        아이디
                    </div>
                    <div class="val">
                        <input name="id" value="{{ $user->id }}" maxlength="10" />
                    </div>
                </div>
                @endif
                <div class="pw">
                    <div class="info">
                        비밀번호
                    </div>
                    <div class="val">
                        <input type="password" name="pw" value="" maxlength="15" />
                    </div>
                </div>
                <div class="name">
                    <div class="info">
                        이름
                    </div>
                    <div class="val">
                        <input name="name" value="{{ $user->name }}" maxlength="10" />
                    </div>
                </div>
                <div class="btn_area">
                    @if($mode == "create")
                        <div class="btn_submit create" id="create">회원가입</div>
                    @else
                        <div class="btn_submit update" id="update" no="{{ $user->no }}">수정</div>
                    @endif
                    <div class="back" id="goLogin">뒤로</div>
                </div>
            </form>
        </div>
        <script>
            $(function() {

                $('#goLogin').click(function(e) {
                    e.preventDefault();

                    location.href = '/login';
                });

                $('#create').click(function(e) {
                    e.preventDefault();

                    if(formCheck() === false) {
                        return false;
                    }

                    var userFormData = $("form[name=userForm]").serialize();

                    ajaxUserStore(userFormData);
                });

                $('#update').click(function(e) {
                    e.preventDefault();

                    if(formCheck() === false) {
                        return false;
                    }

                    var userFormData = $("form[name=userForm]").serialize();

                    var no = $(this).attr('no');

                    ajaxUserUpdate(userFormData, no);
                });
            });

            function ajaxUserStore(postFormData) {
                $.ajax({
                    type: "POST",
                    url:"/user",
                    data:postFormData,
                    dataType:"json",
                    success : function(result){
                        if(result.result == "success") {
                            alert(result.msg);

                            location.href = '/login';
                        } else {
                            alert(result.msg);
                        }
                    },
                    error : function(a, b, c){
                        console.log(a + b + c);
                    }
                });
            }

            function formCheck() {
                var userForm = document.userForm;

                if(userForm.id.value === "") {
                    alert("아이디를 입력해주세요.");
                    return false;
                }
                if(userForm.id.value.length > 10) {
                    alert("아이디는 최대 10자까지 입력 가능합니다.");
                    return false;
                }

                if(userForm.pw.value === "") {
                    alert("비밀번호를 입력해주세요.");
                    return false;
                }
                if(userForm.pw.value.length > 15) {
                    alert("비밀번호는 최대 15자까지 입력 가능합니다.");
                    return false;
                }

                if(userForm.name.value === "") {
                    alert("이름을 입력해주세요.");
                    return false;
                }
                if(userForm.name.value.length > 10) {
                    alert("이름은 최대 10자까지 입력 가능합니다.");
                    return false;
                }

                return true;
            }
        </script>
    </body>
</html>
