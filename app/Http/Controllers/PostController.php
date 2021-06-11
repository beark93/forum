<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Post;
use App\Models\User;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = intval($request->input('page'));

        $postCntPage = 5; // 페이지당 출력 post 수
        $pageCntNav  = 3; // nav내 출력 페이지 수


        // post table 조회
        $posts = Post::orderBy('no', 'desc')->offset((intval($page-1) * $postCntPage))->limit($postCntPage)->get();
        $count = Post::count();


        // nav 셋팅
        $total = (int)(($count-1)/$postCntPage) + 1;
        $start = (int)(($page-1)/$pageCntNav) * $pageCntNav + 1;

        $prev = (($start-1) > 0) ? ($start-1) : 0;
        $next = (($start+$pageCntNav) > $total) ? 0 : ($start+$pageCntNav);

        $nav = array(
            'now'   => $page,
            'max'  => ($total < ($start + $pageCntNav - 1)) ? $total : ($start + $pageCntNav - 1),
            'start' => $start,
            'prev'  => $prev,
            'next'  => $next,
        );


        // post 리스트 view
        return view('post.postlist', ['posts' => $posts, 'nav' => $nav]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // 로그인 확인
        $sessionUser = $request->sessionUser;
        if($sessionUser['login'] !== "Y") {
            $result = array(
                'result' => "err",
                'msg' => '로그인 후 글쓰기가 가능합니다.'
            );
            return json_encode($result);
        }

        $post = array(
            'no' => "",
            'title' => "",
            'content' => ""
        );

        // post 작성 view
        $view = view('post.postform', ['mode' => "create", 'post' => (object)$post]);

        $result = array(
            'result' => "success",
            'view' => $view->render()
        );
        return json_encode($result);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 로그인 확인
        $sessionUser = $request->sessionUser;
        if($sessionUser['login'] !== "Y") {
            $result = array(
                'result' => "err",
                'msg' => '로그인 후 글쓰기가 가능합니다.'
            );
            return json_encode($result);
        }

        // 유효성 검사
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:20'],
            'content' => ['required', 'max:100'],
        ], [
            'title.required' => '제목을 입력해주세요.',
            'title.max:20' => '제목은 최대 20자까지 입력 가능합니다.',
            'content.required' => '내용을 입력해주세요.',
            'content.max:100' => '내용은 최대 100자까지 입력 가능합니다.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $result = array(
                'result' => "err",
                'msg' => $errors->first()
            );
            return json_encode($result);
        }

        // post 생성
        $post = new Post;

        $post->user_no = $sessionUser['no'];
        $post->title = $request->title;
        $post->content = $request->content;

        $post->save();

        $result = array(
            'result' => "success",
            'msg' => '게시글 생성을 완료하였습니다.'
        );
        return json_encode($result);

    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $no
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $no)
    {
        // 로그인 확인
        $sessionUser = $request->sessionUser;

        // post 존재 확인
        $count = Post::where('no', $no)->count();
        if($count <= 0) {
            $err = array(
                'result' => "err",
                'msg' => "게시글이 존재하지 않습니다."
            );

            return json_encode($err);
        }

        // post 조회
        $post = Post::where('no', $no)->first();

        // member 조회
        $user = User::where('no', $post->user_no)->first();
        $post->writer = $user->name;

        $edit = "N";
        if($sessionUser['no'] == $user->no) $edit = "Y";

        $view = view('post.postview', ['post' => $post, 'edit' => $edit]);

        // post 작성 view
        $result = array(
            'result' => "success",
            'view' => $view->render()
        );
        return json_encode($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $no
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $no)
    {
        // 로그인 확인
        $sessionUser = $request->sessionUser;
        if($sessionUser['login'] !== "Y") {
            $result = array(
                'result' => "err",
                'msg' => '작성자 본인만 수정 가능합니다.'
            );
            return json_encode($result);
        }

        // post 존재 확인
        $count = Post::where('no', $no)->count();
        if($count <= 0) {
            $err = array(
                'result' => "err",
                'msg' => "게시글이 존재하지 않습니다."
            );

            return json_encode($err);
        }

        // post 조회
        $post = Post::where('no', $no)->first();

        // member 조회
        $user = User::where('no', $post->user_no)->first();
        $post->writer = $user->name;

        if($sessionUser['no'] !== $user->no) {
            $err = array(
                'result' => "err",
                'msg' => "작성자 본인만 수정 가능합니다."
            );

            return json_encode($err);
        }

        $view = view('post.postform', ['post' => $post, 'mode' => "update"]);

        // post view
        $result = array(
            'result' => "success",
            'view' => $view->render()
        );
        return json_encode($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $no
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $no)
    {
        // 유효성 검사
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:20'],
            'content' => ['required', 'max:100'],
        ], [
            'title.required' => '제목을 입력해주세요.',
            'title.max:20' => '제목은 최대 20자까지 입력 가능합니다.',
            'content.required' => '내용을 입력해주세요.',
            'content.max:100' => '내용은 최대 100자까지 입력 가능합니다.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            $result = array(
                'result' => "err",
                'msg' => $errors->first()
            );
            return json_encode($result);
        }

        // post 존재 확인
        $count = Post::where('no', $no)->count();
        if($count <= 0) {
            $err = array(
                'result' => "err",
                'msg' => "게시글이 존재하지 않습니다."
            );

            return json_encode($err);
        }

        // post 수정
        $post = Post::where('no', $no)->first();

        $post->title = $request->title;
        $post->content = $request->content;

        $post->save();

        $result = array(
            'result' => "success",
            'msg' => '게시글 수정을 완료하였습니다.'
        );
        return json_encode($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $no
     * @return \Illuminate\Http\Response
     */
    public function destroy($no)
    {
        // post 존재 확인
        $count = Post::where('no', $no)->count();
        if($count <= 0) {
            $err = array(
                'result' => "err",
                'msg' => "게시글이 존재하지 않습니다."
            );

            return json_encode($err);
        }

        // post 삭제
        $post = Post::where('no', $no)->first();

        $post->delete();

        $result = array(
            'result' => "success",
            'msg' => '게시글을 삭제하였습니다.'
        );
        return json_encode($result);
    }
}
