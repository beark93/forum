<div class="post_form">
    <form method="POST" name="postForm" id="postForm">
        @csrf
        <div class="title">
            <div class="info">
                제목
            </div>
            <div class="val">
                <input name="title" value="{{ $post->title }}" maxlength="20" />
            </div>
        </div>
        <div class="content">
            <textarea name="content" maxlength="100">{{ $post->content }}</textarea>
        </div>
        <div class="btn_area">
                @if($mode == "create")
                    <div class="btn_submit create" id="create">등록</div>
                @else
                    <div class="btn_submit update" id="update" no="{{ $post->no }}">수정</div>
                @endif
            </div>
            @if($mode == "create")
                <div class="back" id="goList">뒤로</div>
            @else
                <div class="back" id="goPost" no="{{ $post->no }}">뒤로</div>
            @endif
        </div>
    </form>
</div>

<script>
    $(function() {
        $('#goList').click(function(e) {
            e.preventDefault();

            ajaxPostList(1);
        });

        $('#goPost').click(function(e) {
            e.preventDefault();

            var no = $(this).attr('no');

            ajaxPostView(no);
        });

        $('#create').click(function(e) {
            e.preventDefault();

            if(formCheck() === false) {
                return false;
            }

            var postFormData = $("form[name=postForm]").serialize();

            ajaxPostStore(postFormData);
        });

        $('#update').click(function(e) {
            e.preventDefault();

            if(formCheck() === false) {
                return false;
            }

            var postFormData = $("form[name=postForm]").serialize();

            var no = $(this).attr('no');

            ajaxPostUpdate(postFormData, no);
        });
    });

    function formCheck() {
        var postForm = document.postForm;

        if(postForm.title.value === "") {
            alert("제목을 입력해주세요.");
            return false;
        }
        if(postForm.title.value.length > 20) {
            alert("제목은 최대 20자까지 입력 가능합니다.");
            return false;
        }

        if(postForm.content.value === "") {
            alert("내용을 입력해주세요.");
            return false;
        }
        if(postForm.content.value.length > 100) {
            alert("내용은 최대 100자까지 입력 가능합니다.");
            return false;
        }

        return true;
    }
</script>
