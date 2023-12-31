<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        $validator = Validator::make($request->all(),[

            'title' => 'required|unique:posts|max:255',
            'body' => 'required',

        ]);

        if($validator->fails()){

            return $this->apiResponse(null,$validator->errors(),400);
        }

        $post = Post::create($request->all());

        if($post){

            return $this->apiResponse(new PostResource($post),'Post added succesfully',201);

        }

        return $this->apiResponse(null,'Creation failed',400);
    }

    public function update(Request $request, $id){

        $validator = Validator::make($request->all(),[

            'title' => 'required|max:255',
            'body' => 'required',

        ]);

        if($validator->fails()){

            return $this->apiResponse(null,$validator->errors(),400);
        }

        $post = Post::find($id);

        if(!$post){

            return $this->apiResponse(null,'Post does not exist',400);

        }

        $post->update($request->all());

        if($post){

            return $this->apiResponse(new PostResource($post),'Changes saved successfully',200);

        }

        return $this->apiResponse(null,'Changes failed',400);
    }

    public function destory($id){

        $post = Post::find($id);

        if(!$post){

            return $this->apiResponse(null,'Post does not exist',400);

        }

        $post->delete($id);

        return $this->apiResponse(null,'Post deleted successfully',200);

    }
}
