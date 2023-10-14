<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use ApiResponseTrait;

    public function index(){
        //collection for many posts
        $posts = PostResource::collection(Post::get());
        return $this->apiResponse($posts,'success',200);
    }

    public function show($id){

        $post = Post::find($id);

        if($post){
            return $this->apiResponse(new PostResource($post),'ok',200);
        }

        return $this->apiResponse(null,'The Post Not Found',404);

    }

    public function store(Request $request){

        $post = Post::create($request->all());

        if($post){

            return $this->apiResponse(new PostResource($post),'Post added succesfully',201);

        }

        return $this->apiResponse(null,'Creation failed',400);
    }
}
