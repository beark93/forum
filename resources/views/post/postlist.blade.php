<div class="posts">
    <div class="post_list">
        @foreach ($posts as $post)
        <div class="post post{{$post->no}}" no="{{$post->no}}">
            {{ $post->title }}
        </div>
        @endforeach
    </div>

    <div class="nav_list">
        <div class="nav nav_prev" page="{{ $nav['prev'] }}"><</div>
        @for ($page=$nav['start']; $page <= $nav['max']; $page++)
        <div class="nav {{ ($nav['now'] == $page) ? 'now' : '' }}" page="{{ $page }}" >
            {{ $page }}
        </div>
        @endfor
        <div class="nav nav_next" page="{{ $nav['next'] }}">></div>
    </div>
    <div class="write" id="goWrite">글쓰기</div>
</div>

<script>
    $(function() {
        $('.contents .nav_list .nav').click(function (e) {
            e.preventDefault();

            var page = $(this).attr('page');

            if(page !== '0') {
                ajaxPostList(page);
            }
        });

        $('.contents .post_list .post').click(function (e) {
            e.preventDefault();

            var no = $(this).attr('no');

            if(no !== '0') {
                ajaxPostView(no);
            }
        });

        $('#goWrite').click(function(e) {
            e.preventDefault();

            ajaxPostCreate();
        });
    });
</script>
