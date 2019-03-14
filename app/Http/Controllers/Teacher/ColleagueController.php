<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \DB;
use App\Models\Teacher;
use App\Models\Sclass;
use App\Models\Post;
use App\Models\PostRate;
use App\Models\School;
use \Auth;

class ColleagueController extends Controller
{
    public function colleaguePost(Request $request)
    {
        $getDataType = ($request->input('type'))?$request->input('type'):"my";
        $posts = [];
        $schoolCode = $this->getSchool()->code;
        switch ($getDataType) {
            case 'my':
                $posts = $this->getMyPostsData();
                break;
            case 'all':
                $posts = $this->getAllPostsData();
                break;
            case 'same-subject':
                $posts = $this->getSameSubjectPostsData();
                break;
            case 'same-sex':
                $posts = $this->getSameGradePostsData();
                break;
            case 'my-marked':
                $posts = $this->getMyMarkedPostsData();
                break;
            case 'most-marked':
                $posts = $this->getMostMarkedPostsData();
                break;
            case 'has-comment':
                $posts = $this->getHasCommentPostsData();
                break;
            default:
                if ("search-name" == explode("=",$getDataType)[0]) {
                    $posts = $this->getSearchNamePostsData(explode("=",$getDataType)[1]);
                }
                break;
        }
        // dd($posts);
        return view('teacher/colleaguePost', compact('posts', 'schoolCode'));
    }

    public function getMyPostsData() {
        $schoolsId = $this->getSchool()->id;
        $id = \Auth::guard("teacher")->id();

        $posts = Post::select('posts.id as pid', 'posts.file_ext', 'posts.storage_name', 'posts.writing_date', 'teachers.username', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                // ->where('posts.students_id', '<>', $id)
                ->leftjoin('teachers', 'posts.teachers_id', '=', 'teachers.id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->where('teachers.schools_id', '=', $schoolsId)
                ->where('teachers.id', '=', $id)
                ->groupBy('posts.id', 'posts.file_ext', 'posts.storage_name', 'teachers.username', 'posts.writing_date')
                ->orderby("posts.writing_date", "DESC")->paginate(12);
        return $posts;
    }

    public function getAllPostsData() {
        $schoolsId = $this->getSchool()->id;
        $posts = Post::select('posts.id as pid', 'posts.file_ext', 'posts.storage_name', 'posts.writing_date', 'teachers.username', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                // ->where('posts.students_id', '<>', $id)
                ->leftjoin('teachers', 'posts.teachers_id', '=', 'teachers.id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->where('teachers.schools_id', '=', $schoolsId)
                ->groupBy('posts.id', 'posts.file_ext', 'posts.storage_name', 'teachers.username', 'posts.writing_date')
                ->orderby("posts.writing_date", "DESC")->paginate(12);
        return $posts;
    }

    public function getSameSubjectPostsData() {
        $schoolsId = $this->getSchool()->id;
        $id = \Auth::guard("teacher")->id();
        $teacher = Teacher::find($id);
        $posts = Post::select('posts.id as pid', 'posts.file_ext', 'posts.storage_name', 'posts.writing_date', 'teachers.username', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                // ->where('posts.students_id', '<>', $id)
                ->leftjoin('teachers', 'posts.teachers_id', '=', 'teachers.id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->where('teachers.schools_id', '=', $schoolsId)
                ->where('teachers.subjects_id', '=', $teacher->subjects_id)
                ->where('marks.state_code', '=', 1)
                ->groupBy('posts.id', 'posts.file_ext', 'posts.storage_name', 'teachers.username', 'posts.writing_date')
                ->orderby("posts.writing_date", "DESC")->paginate(12);
        return $posts;
    }

    // public function getSameGradePostsData() {
    //     $id = \Auth::guard("student")->id();
    //     $schoolsId = $this->getSchool()->schoolsId;
    //     $sclass = Sclass::leftjoin('students', 'students.sclasses_id', '=', 'sclasses.id')
    //             ->where('students.id', '=', $id)->first();
    //     $posts = Post::select('posts.id as pid', 'sclasses.class_title', 'terms.grade_key', 'post_rates.rate', 'posts.file_ext', 'posts.storage_name', 'students.username', 'comments.content', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
    //             // ->where('posts.students_id', '<>', $id)
    //             ->leftjoin('students', 'posts.students_id', '=', 'students.id')
    //             ->leftjoin('sclasses', 'students.sclasses_id', '=', 'sclasses.id')
    //             ->leftjoin('post_rates', 'posts.id', '=', 'post_rates.posts_id')
    //             ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
    //             ->leftjoin('comments', 'comments.posts_id', '=', 'posts.id')
    //             ->leftjoin('terms', 'terms.enter_school_year', '=', 'sclasses.enter_school_year')
    //             ->where('terms.is_current', '=', 1)
    //             ->where('sclasses.enter_school_year', '=', $sclass->enter_school_year)
    //             ->where('sclasses.schools_id', '=', $schoolsId)
    //             ->groupBy('posts.id', 'sclasses.class_title', 'terms.grade_key', 'post_rates.rate', 'posts.file_ext', 'posts.storage_name', 'students.username', 'comments.content')
    //             ->orderby("posts.writing_date", "DESC")->paginate(12);
    //     return $posts;
    // }

    public function getMyMarkedPostsData() {

        $schoolsId = $this->getSchool()->id;
        $id = \Auth::guard("teacher")->id();

        $posts = Post::select('posts.id as pid', 'posts.file_ext', 'posts.storage_name', 'posts.writing_date', 'teachers.username', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                // ->where('posts.students_id', '<>', $id)
                ->leftjoin('teachers', 'posts.teachers_id', '=', 'teachers.id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->where('teachers.schools_id', '=', $schoolsId)
                ->where('marks.teachers_id', '=', $id)
                ->where('marks.state_code', '=', 1)
                ->groupBy('posts.id', 'posts.file_ext', 'posts.storage_name', 'teachers.username', 'posts.writing_date')
                ->orderby("posts.writing_date", "DESC")->paginate(12);
                // dd($posts);
        return $posts;


        // $id = \Auth::guard("student")->id();
        // $posts = Post::select('posts.id as pid', 'sclasses.class_title', 'terms.grade_key', 'post_rates.rate', 'posts.file_ext', 'posts.storage_name', 'students.username', 'comments.content', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
        //         // ->where('posts.students_id', '<>', $id)
        //         ->leftjoin('students', 'posts.students_id', '=', 'students.id')
        //         ->leftjoin('sclasses', 'students.sclasses_id', '=', 'sclasses.id')
        //         ->leftjoin('post_rates', 'posts.id', '=', 'post_rates.posts_id')
        //         ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
        //         ->leftjoin('comments', 'comments.posts_id', '=', 'posts.id')
        //         ->leftjoin('terms', 'terms.enter_school_year', '=', 'sclasses.enter_school_year')
        //         ->where('terms.is_current', '=', 1)
        //         ->where('marks.students_id', '=', $id)
        //         ->groupBy('posts.id', 'sclasses.class_title', 'terms.grade_key', 'post_rates.rate', 'posts.file_ext', 'posts.storage_name', 'students.username', 'comments.content')
        //         ->orderby("posts.id", "DESC")->paginate(12);
        // return $posts;
    }

    public function getMostMarkedPostsData() {
        $id = \Auth::guard("teacher")->id();
        $schoolsId = $this->getSchool()->id;
        $posts = Post::select('posts.id as pid', 'posts.file_ext', 'posts.storage_name', 'posts.writing_date', 'teachers.username', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                ->leftjoin('teachers', 'posts.teachers_id', '=', 'teachers.id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->where('teachers.schools_id', '=', $schoolsId)
                ->where('marks.state_code', '=', 1)
                ->groupBy('posts.id', 'posts.file_ext', 'posts.storage_name', 'teachers.username', 'posts.writing_date')
                ->orderby("mark_num", "DESC")->paginate(12);
        return $posts;
    }

    public function getHasCommentPostsData() {
        $id = \Auth::guard("student")->id();
        $schoolsId = $this->getSchool()->id;
        $posts = Post::select('posts.id as pid', 'sclasses.class_title', 'terms.grade_key', 'post_rates.rate', 'posts.file_ext', 'posts.storage_name', 'students.username', 'comments.content', 'comments.id as cid', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                // ->where('posts.students_id', '<>', $id)
                ->leftjoin('students', 'posts.students_id', '=', 'students.id')
                ->leftjoin('sclasses', 'students.sclasses_id', '=', 'sclasses.id')
                ->leftjoin('post_rates', 'posts.id', '=', 'post_rates.posts_id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->leftjoin('comments', 'comments.posts_id', '=', 'posts.id')
                ->leftjoin('terms', 'terms.enter_school_year', '=', 'sclasses.enter_school_year')
                ->where('terms.is_current', '=', 1)
                ->where('sclasses.schools_id', '=', $schoolsId)
                ->groupBy('posts.id', 'sclasses.class_title', 'terms.grade_key', 'post_rates.rate', 'posts.file_ext', 'posts.storage_name', 'students.username', 'comments.id', 'comments.content')
                ->orderby("cid", "DESC")->paginate(12);
        return $posts;
    }

    public function getSearchNamePostsData($searchName) {
        $schoolsId = $this->getSchool()->id;
        $posts = Post::select('posts.id as pid', 'sclasses.class_title', 'terms.grade_key', 'post_rates.rate', 'posts.file_ext', 'posts.storage_name', 'students.username', 'comments.id as cid', 'comments.content', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                ->leftjoin('students', 'posts.students_id', '=', 'students.id')
                ->leftjoin('sclasses', 'students.sclasses_id', '=', 'sclasses.id')
                ->leftjoin('post_rates', 'posts.id', '=', 'post_rates.posts_id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->leftjoin('comments', 'comments.posts_id', '=', 'posts.id')
                ->leftjoin('terms', 'terms.enter_school_year', '=', 'sclasses.enter_school_year')
                ->where('terms.is_current', '=', 1)
                ->where('sclasses.schools_id', '=', $schoolsId)
                ->where('students.username', 'like', '%'.$searchName.'%')
                ->groupBy('posts.id', 'sclasses.class_title', 'terms.grade_key', 'post_rates.rate', 'posts.file_ext', 'posts.storage_name', 'students.username', 'comments.id', 'comments.content')
                ->orderby("cid", "DESC")->paginate(12);
        return $posts;
    }

    public function getSchool()
    {
      $teacher = Teacher::find(Auth::guard("teacher")->id());

      $school = School::where('id', '=', $teacher->schools_id)->first();
      return $school;
    }
}
