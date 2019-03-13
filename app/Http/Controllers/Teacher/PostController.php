<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Teacher;
use App\Models\Post;
use App\Models\Mark;
use App\Models\Comment;
use App\Models\PostRate;
use App\Models\Lesson;
use App\Models\WritingType;
use App\Models\Term;
use App\Models\Sclass;
use App\Models\School;
use \Auth;

use EndaEditor;
use \DB;

class PostController extends Controller
{
    public function index()
    {
        return view('teacher/posts');
    }

    public function getPosts(Request $request) {
        $middir = "/posts/" . $this->getSchoolCode() . "/";
        $imgTypes = ['jpg', 'jpeg', 'bmp', 'gif', 'png'];

        $id = \Auth::guard("teacher")->id();
        $teacher = Teacher::find($id);
        $posts = Post::where("teachers_id", "=", $id)->orderBy("writing_date", "ASC")->get();
        
        return $this->buildPostListHtml($posts);
    }

    public function getOnePostById(Request $request) {
        $middir = "/posts/" . $this->getSchoolCode() . "/";
        $imgTypes = ['jpg', 'jpeg', 'bmp', 'gif', 'png'];
        $post = Post::select("teachers.username", "posts.*", "writing_types.name")
                    ->leftjoin('teachers', 'teachers.id', '=', "posts.teachers_id")
                    ->leftjoin('writing_types', 'writing_types.id', '=', "posts.writing_types_id")
                    ->where("posts.id", "=", $request->input('posts_id'))->first();
                // return var_dump($post);
        if (isset($post)) {
            $writeDate = substr($post["writing_date"], 4, 2) . "月" . substr($post["writing_date"], 6, 2) . "日";
            return ["filetype"=>"img", 
                    "storage_name" => getThumbnail($post['storage_name'], 360, 576, $this->getSchoolCode(), 'fit', $post['file_ext']), 
                    "username" => $post["username"],
                    "writingType" => $post["name"],
                    "writingDate" => $writeDate,
                    "filePath" => env('APP_URL'). $middir . $post['storage_name'],
                ];
        } else {
            return "false";
        }
    }

    public function buildPostListHtml($posts) {
        $resultHtml = "";

        foreach ($posts as $key => $post) {
            $writeDate = substr($post->writing_date, 4, 2) . "月" . substr($post->writing_date, 6, 2) . "日";
            $writeType = WritingType::find($post->writing_types_id);
            $resultHtml  .= "<div class='col-md-2 col-sm-4 col-xs-6' style='padding-left: 5px; padding-right: 5px;'><div class='alert alert-info' style='padding: 5px;'><div><img class='img-responsive post-btn center-block' value='". $post->id . "' src='" . getThumbnail($post->storage_name, 100, 160, $this->getSchoolCode(), 'fit', $post->file_ext) . "' alt=''></div><div><h5 style='margin-top: 10px;'>" . $writeDate ." ". $writeType->name." 3个赞</h5></div></div></div>";
        }
        return $resultHtml;
    }

    public function getSchoolCode()
    {
      $teacher = Teacher::find(Auth::guard("teacher")->id());

      $school = School::find($teacher->schools_id);
      return $school->code;
    }

}
