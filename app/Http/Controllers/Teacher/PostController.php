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
        $posts = Post::select('posts.id as pid', 'posts.file_ext', 'posts.storage_name', 'posts.writing_date', 'posts.writing_types_id', 'writing_types.name as writing_type_name', 'teachers.username', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                // ->where('posts.students_id', '<>', $id)
                ->leftjoin('teachers', 'posts.teachers_id', '=', 'teachers.id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->leftjoin('writing_types', 'writing_types.id', '=', 'posts.writing_types_id')
                ->where('teachers.schools_id', '=', $teacher->schools_id)
                ->where('teachers.id', '=', $id)
                ->groupBy('posts.id', 'posts.file_ext', 'posts.storage_name', 'teachers.username', 'posts.writing_date', 'posts.writing_types_id', 'writing_types.name')
                ->orderby("posts.writing_date", "DESC")->get();
        // dd($posts);
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
                    "storage_name" => getThumbnail($post['storage_name'], 300, 400, $this->getSchoolCode(), 'fit', $post['file_ext']), 
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
            $markStr = isset($post->mark_num)?$post->mark_num ."个赞":"";
            $resultHtml  .= "<div class='col-md-2 col-sm-4 col-xs-6' style=''><div class='alert alert-info' style='padding: 5px;'><img class='img-responsive post-btn center-block' value='". $post->pid . "' src='" . getThumbnail($post->storage_name, 120, 170, $this->getSchoolCode(), 'fit', $post->file_ext) . "' alt=''><div><h5 style='margin-bottom:5px; margin-top: 5px; text-align: center'><small>" . $writeDate ." ". $post->writing_type_name." ". $markStr ."</small></h5></div></div></div>";
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
