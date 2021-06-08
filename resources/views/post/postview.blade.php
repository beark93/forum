<div class="post">
    <div class="post_info">
        <div class="title">
            {{ $post->title }}
        </div>
        <div class="writer">
            {{ $post->writer }}
        </div>
        <div class="slash">/</div>
        <div class="date">
            {{ $post->created_at }}
        </div>
    </div>
    <div class="content">
        {{ $post->content }}
    </div>
    <div class="btn_area">
        <div class="edit" id="edit" no="{{ $post->no }}">수정</div>
        <div class="delete" id="delete" no="{{ $post->no }}">삭제</div>
        <div class="back" id="goList">뒤로</div>
    </div>
</div>

<script>
    $(function(){
        $('#goList').click(function(e) {
            e.preventDefault();

            ajaxPostList(1);
        });

        $('#edit').click(function(e) {
            e.preventDefault();

            var no = $(this).attr('no');

            if(no !== '0') {
                ajaxPostEdit(no);
            }
        });

        $('#delete').click(function(e) {
            e.preventDefault();

            var no = $(this).attr('no');

            if(no !== '0') {
                ajaxPostDelete(no);
            }
        });
    });
</script>
