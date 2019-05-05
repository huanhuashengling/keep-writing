<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \DB;
use App\Models\Teacher;
use App\Models\WritingType;
use App\Models\Post;
use App\Models\PostRate;
use App\Models\School;
use \Auth;

class ColleagueController extends Controller
{
    public function colleaguePost(Request $request)
    {
        // echo($request->session()->get('colleagueWritingTypesId'));
        $tWritingTypesId = 1;
        if ($request->input('writingTypesId')) {
            $tWritingTypesId = $request->input('writingTypesId');
        }
        // if ($request->session()->has('colleagueWritingTypesId')) {
            // $tWritingTypesId = $request->session()->get('colleagueWritingTypesId');
        // }
        $posts = $this->getAllPostsData($tWritingTypesId);
        $schoolCode = $this->getSchool()->code;
        $baseUrl = env('APP_URL') . '/posts/' . $schoolCode . "/";
        $writingTypes = WritingType::all();


        // $tFilterType = "all";
        // if ($request->session()->has('colleagueFilterType')) {
        //     $tFilterType = $request->session()->get('colleagueFilterType');
        // }

        // $getDataType = ($request->input('type'))?$request->input('type'):$tFilterType;
        // $posts = [];


        // switch ($getDataType) {
        //     case 'my':
        //         $posts = $this->getMyPostsData($tWritingTypesId);
        //         break;
        //     case 'all':
        //         $posts = $this->getAllPostsData($tWritingTypesId);
        //         break;
        //     case 'most-star':
        //         $posts = $this->getMostStarPostsData($tWritingTypesId);
        //         break;
        //     case 'same-sex':
        //         $posts = $this->getSameGradePostsData($tWritingTypesId);
        //         break;
        //     case 'my-marked':
        //         $posts = $this->getMyMarkedPostsData($tWritingTypesId);
        //         break;
        //     case 'most-marked':
        //         $posts = $this->getMostMarkedPostsData($tWritingTypesId);
        //         break;
        //     case 'has-comment':
        //         $posts = $this->getHasCommentPostsData($tWritingTypesId);
        //         break;
        //     default:
        //         if ("search-name" == explode("=",$getDataType)[0]) {
        //             $posts = $this->getSearchNamePostsData(explode("=",$getDataType)[1]);
        //         }
        //         break;
        // }
        // dd($posts);
        return view('teacher/colleaguePost', compact('posts', 'schoolCode', 'baseUrl', 'writingTypes', 'tWritingTypesId', 'getDataType'));
    }

    public function getMyPostsData($writingTypesId) {
        $schoolsId = $this->getSchool()->id;
        $id = \Auth::guard("teacher")->id();

        $posts = Post::select('posts.id as pid', 'posts.file_ext', 'posts.storage_name', 'posts.writing_date', 'teachers.username', 'writing_types.name as writing_type_name', 'post_rates.rate', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                // ->where('posts.students_id', '<>', $id)
                ->leftjoin('teachers', 'posts.teachers_id', '=', 'teachers.id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->leftjoin('post_rates', 'post_rates.posts_id', '=', 'posts.id')
                ->leftjoin('writing_types', 'writing_types.id', '=', 'posts.writing_types_id')
                ->where('teachers.schools_id', '=', $schoolsId)
                ->where('writing_types.id', '=', $writingTypesId)
                ->where('teachers.id', '=', $id)
                ->groupBy('posts.id', 'posts.file_ext', 'posts.storage_name', 'teachers.username', 'posts.writing_date', 'post_rates.rate', 'writing_types.name')
                ->orderby("posts.writing_date", "DESC")->paginate(12);
        return $posts;
    }

    public function getAllPostsData($writingTypesId) {
        $schoolsId = $this->getSchool()->id;
        $posts = Post::select('posts.id as pid', 'posts.file_ext', 'posts.storage_name', 'posts.writing_date', 'teachers.username', 'writing_types.name as writing_type_name', 'post_rates.rate', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                // ->where('posts.students_id', '<>', $id)
                ->join('teachers', 'posts.teachers_id', '=', 'teachers.id')
                ->join('writing_types', 'writing_types.id', '=', 'posts.writing_types_id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->leftjoin('post_rates', 'post_rates.posts_id', '=', 'posts.id')
                ->where('teachers.schools_id', '=', $schoolsId)
                ->where('writing_types.id', '=', $writingTypesId)
                ->groupBy('posts.id', 'posts.file_ext', 'posts.storage_name', 'teachers.username', 'posts.writing_date', 'post_rates.rate', 'writing_types.name')
                ->orderby("posts.writing_date", "DESC")->paginate(12);
        return $posts;
    }

    public function getMostStarPostsData($writingTypesId) {
        $schoolsId = $this->getSchool()->id;
        $posts = Post::select('posts.id as pid', 'posts.file_ext', 'posts.storage_name', 'posts.writing_date', 'teachers.username', 'writing_types.name as writing_type_name', 'post_rates.rate', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                // ->where('posts.students_id', '<>', $id)
                ->leftjoin('teachers', 'posts.teachers_id', '=', 'teachers.id')
                ->leftjoin('writing_types', 'writing_types.id', '=', 'posts.writing_types_id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->leftjoin('post_rates', 'post_rates.posts_id', '=', 'posts.id')
                ->where('teachers.schools_id', '=', $schoolsId)
                ->where('writing_types.id', '=', $writingTypesId)
                ->where('post_rates.rate', '>', 0)
                ->groupBy('posts.id', 'posts.file_ext', 'posts.storage_name', 'teachers.username', 'posts.writing_date', 'post_rates.rate', 'writing_types.name')
                ->orderby("post_rates.rate", "DESC")->paginate(12);
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

    public function getMyMarkedPostsData($writingTypesId) {

        $schoolsId = $this->getSchool()->id;
        $id = \Auth::guard("teacher")->id();

        $posts = Post::select('posts.id as pid', 'posts.file_ext', 'posts.storage_name', 'posts.writing_date', 'teachers.username', 'writing_types.name as writing_type_name', 'post_rates.rate', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                // ->where('posts.students_id', '<>', $id)
                ->leftjoin('teachers', 'posts.teachers_id', '=', 'teachers.id')
                ->leftjoin('writing_types', 'writing_types.id', '=', 'posts.writing_types_id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->leftjoin('post_rates', 'post_rates.posts_id', '=', 'posts.id')
                ->where('teachers.schools_id', '=', $schoolsId)
                ->where('marks.teachers_id', '=', $id)
                ->where('writing_types.id', '=', $writingTypesId)
                ->where('marks.state_code', '=', 1)
                ->groupBy('posts.id', 'posts.file_ext', 'posts.storage_name', 'teachers.username', 'posts.writing_date', 'post_rates.rate', 'writing_types.name')
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

    public function getMostMarkedPostsData($writingTypesId) {
        $id = \Auth::guard("teacher")->id();
        $schoolsId = $this->getSchool()->id;
        $posts = Post::select('posts.id as pid', 'posts.file_ext', 'posts.storage_name', 'posts.writing_date', 'teachers.username', 'writing_types.name as writing_type_name', 'post_rates.rate', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                ->leftjoin('teachers', 'posts.teachers_id', '=', 'teachers.id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->leftjoin('post_rates', 'post_rates.posts_id', '=', 'posts.id')
                ->leftjoin('writing_types', 'writing_types.id', '=', 'posts.writing_types_id')
                ->where('teachers.schools_id', '=', $schoolsId)
                ->where('writing_types.id', '=', $writingTypesId)
                ->where('marks.state_code', '=', 1)
                ->groupBy('posts.id', 'posts.file_ext', 'posts.storage_name', 'teachers.username', 'posts.writing_date', 'post_rates.rate', 'writing_types.name')
                ->orderby("mark_num", "DESC")->paginate(12);
        return $posts;
    }

    public function getHasCommentPostsData($writingTypesId) {
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

    public function storeWritingTypesId(Request $request)
    {
        echo($request->get('writingTypesId'));
        $request->session()->put('colleagueWritingTypesId', $request->get('writingTypesId'));
    }

    public function storeFilterType(Request $request)
    {
        $request->session()->put('colleagueFilterType', $request->get('filterType'));
    }

    public function getSchool()
    {
      $teacher = Teacher::find(Auth::guard("teacher")->id());

      $school = School::where('id', '=', $teacher->schools_id)->first();
      return $school;
    }
}
