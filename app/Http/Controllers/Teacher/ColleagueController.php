<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \DB;
use App\Models\Teacher;
use App\Models\WritingType;
use App\Models\StageCheck;
use App\Models\Post;
use App\Models\PostRate;
use App\Models\School;
use \Auth;

class ColleagueController extends Controller
{
    public function colleaguePost(Request $request)
    {
        $getDataType = $request->input('type');
        $tWritingTypesId = $request->input('wtId');
        $wtId = $tWritingTypesId;
        $writingType = WritingType::find($tWritingTypesId);
        $writingTypeName = $writingType->name;
        $totalDesc = "";
        if (!isset($getDataType)) {
            $posts = [];
            return view('teacher/colleaguePost', compact('posts', 'getDataType', 'tWritingTypesId', 'totalDesc', 'writingTypeName'));
        }
        
        $schoolCode = $this->getSchool()->code;
        $baseUrl = env('APP_URL') . '/posts/' . $schoolCode . "/";

        switch ($getDataType) {
            case 'my':
                $posts = $this->getMyPostsData($tWritingTypesId);
                $score = $this->getMyScore($tWritingTypesId, $posts->total());
                // $totalDesc = $writingTypeName . "共计打卡" . $posts->total() . '次, 得' . $score . "分";
                $totalDesc = $posts->total();
                break;
            case 'all':
                $posts = $this->getAllPostsData($tWritingTypesId);
                // $totalDesc = $writingTypeName . "共计打卡" . $posts->total() . '次';
                $totalDesc = $posts->total();
                break;
            case 'most-star':
                $posts = $this->getMostStarPostsData($tWritingTypesId);
                break;
            case 'same-sex':
                $posts = $this->getSameGradePostsData($tWritingTypesId);
                break;
            case 'my-marked':
                $posts = $this->getMyMarkedPostsData($tWritingTypesId);
                break;
            case 'most-marked':
                $posts = $this->getMostMarkedPostsData($tWritingTypesId);
                break;
            case 'has-comment':
                $posts = $this->getHasCommentPostsData($tWritingTypesId);
                break;
            default:
                if ("search-name" == explode("=",$getDataType)[0]) {
                    $posts = $this->getSearchNamePostsData(explode("=",$getDataType)[1]);
                }
                break;
        }
        // dd($posts);
        return view('teacher/colleaguePost', compact('posts', 'schoolCode', 'baseUrl', 'writingTypeName', 'tWritingTypesId', 'getDataType', 'wtId', 'totalDesc'));
    }

    public function getMyPostsData($writingTypesId) {
        $schoolsId = $this->getSchool()->id;
        $id = \Auth::guard("teacher")->id();

        $posts = Post::select('posts.id as pid', 'posts.post_code', 'posts.export_name', 'posts.writing_date', 'teachers.username', 'post_rates.rate', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                ->join('teachers', 'posts.teachers_id', '=', 'teachers.id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->leftjoin('post_rates', 'post_rates.posts_id', '=', 'posts.id')
                ->where('teachers.schools_id', '=', $schoolsId)
                ->where('posts.writing_types_id', '=', $writingTypesId)
                ->where('teachers.id', '=', $id)
                ->groupBy('posts.id', 'posts.post_code', 'posts.export_name', 'teachers.username', 'posts.writing_date', 'post_rates.rate')
                ->orderby("posts.writing_date", "DESC")->paginate(24);
        return $posts;
    }

    public function getMyScore($writingTypesId, $totalNum) {
        $stageCheckNum = 0;
        $stageCheckScore = 0;
        $schoolsId = $this->getSchool()->id;
        $id = \Auth::guard("teacher")->id();
        $stageChecks = StageCheck::where("writing_types_id", "=", $writingTypesId)
                        ->where("schools_id", "=", $schoolsId)->get();
        $postRate = "";
        foreach ($stageChecks as $key => $stageCheck) {
            $post = Post::where("writing_date", "=", $stageCheck->check_date)
                        ->where("teachers_id", "=", $id)->first();
            if(isset($post)) {
                $stageCheckNum ++;
                $postRate = PostRate::where("posts_id", "=", $post->id)->first();
                if(isset($postRate)) {
                    $stageCheckScore += $postRate->rate;
                }
            }
        }
        return ($totalNum - $stageCheckNum + $stageCheckScore);
    }

    public function getAllPostsData($writingTypesId) {
        $schoolsId = $this->getSchool()->id;
        $posts = Post::select('posts.id as pid', 'posts.post_code', 'posts.export_name', 'posts.writing_date', 'teachers.username', 'post_rates.rate', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                ->join('teachers', 'posts.teachers_id', '=', 'teachers.id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->leftjoin('post_rates', 'post_rates.posts_id', '=', 'posts.id')
                ->where('teachers.schools_id', '=', $schoolsId)
                ->where('posts.writing_types_id', '=', $writingTypesId)
                ->groupBy('posts.id', 'posts.post_code', 'posts.export_name', 'teachers.username', 'posts.writing_date', 'post_rates.rate')
                ->orderby("posts.writing_date", "DESC")->paginate(24);
        return $posts;
    }

    public function getMostStarPostsData($writingTypesId) {
        $schoolsId = $this->getSchool()->id;
        $posts = Post::select('posts.id as pid', 'posts.file_ext', 'posts.export_name', 'posts.writing_date', 'teachers.username', 'writing_types.name as writing_type_name', 'post_rates.rate', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                // ->where('posts.students_id', '<>', $id)
                ->leftjoin('teachers', 'posts.teachers_id', '=', 'teachers.id')
                ->leftjoin('writing_types', 'writing_types.id', '=', 'posts.writing_types_id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->leftjoin('post_rates', 'post_rates.posts_id', '=', 'posts.id')
                ->where('teachers.schools_id', '=', $schoolsId)
                ->where('writing_types.id', '=', $writingTypesId)
                ->where('post_rates.rate', '>', 0)
                ->groupBy('posts.id', 'posts.file_ext', 'posts.export_name', 'teachers.username', 'posts.writing_date', 'post_rates.rate', 'writing_types.name')
                ->orderby("post_rates.rate", "DESC")->paginate(12);
        return $posts;
    }

    // public function getSameGradePostsData() {
    //     $id = \Auth::guard("student")->id();
    //     $schoolsId = $this->getSchool()->schoolsId;
    //     $sclass = Sclass::leftjoin('students', 'students.sclasses_id', '=', 'sclasses.id')
    //             ->where('students.id', '=', $id)->first();
    //     $posts = Post::select('posts.id as pid', 'sclasses.class_title', 'terms.grade_key', 'post_rates.rate', 'posts.file_ext', 'posts.export_name', 'students.username', 'comments.content', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
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
    //             ->groupBy('posts.id', 'sclasses.class_title', 'terms.grade_key', 'post_rates.rate', 'posts.file_ext', 'posts.export_name', 'students.username', 'comments.content')
    //             ->orderby("posts.writing_date", "DESC")->paginate(12);
    //     return $posts;
    // }

    public function getMyMarkedPostsData($writingTypesId) {

        $schoolsId = $this->getSchool()->id;
        $id = \Auth::guard("teacher")->id();

        $posts = Post::select('posts.id as pid', 'posts.file_ext', 'posts.export_name', 'posts.writing_date', 'teachers.username', 'writing_types.name as writing_type_name', 'post_rates.rate', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                // ->where('posts.students_id', '<>', $id)
                ->leftjoin('teachers', 'posts.teachers_id', '=', 'teachers.id')
                ->leftjoin('writing_types', 'writing_types.id', '=', 'posts.writing_types_id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->leftjoin('post_rates', 'post_rates.posts_id', '=', 'posts.id')
                ->where('teachers.schools_id', '=', $schoolsId)
                ->where('marks.teachers_id', '=', $id)
                ->where('writing_types.id', '=', $writingTypesId)
                ->where('marks.state_code', '=', 1)
                ->groupBy('posts.id', 'posts.file_ext', 'posts.export_name', 'teachers.username', 'posts.writing_date', 'post_rates.rate', 'writing_types.name')
                ->orderby("posts.writing_date", "DESC")->paginate(12);
                // dd($posts);
        return $posts;


        // $id = \Auth::guard("student")->id();
        // $posts = Post::select('posts.id as pid', 'sclasses.class_title', 'terms.grade_key', 'post_rates.rate', 'posts.file_ext', 'posts.export_name', 'students.username', 'comments.content', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
        //         // ->where('posts.students_id', '<>', $id)
        //         ->leftjoin('students', 'posts.students_id', '=', 'students.id')
        //         ->leftjoin('sclasses', 'students.sclasses_id', '=', 'sclasses.id')
        //         ->leftjoin('post_rates', 'posts.id', '=', 'post_rates.posts_id')
        //         ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
        //         ->leftjoin('comments', 'comments.posts_id', '=', 'posts.id')
        //         ->leftjoin('terms', 'terms.enter_school_year', '=', 'sclasses.enter_school_year')
        //         ->where('terms.is_current', '=', 1)
        //         ->where('marks.students_id', '=', $id)
        //         ->groupBy('posts.id', 'sclasses.class_title', 'terms.grade_key', 'post_rates.rate', 'posts.file_ext', 'posts.export_name', 'students.username', 'comments.content')
        //         ->orderby("posts.id", "DESC")->paginate(12);
        // return $posts;
    }

    public function getMostMarkedPostsData($writingTypesId) {
        $id = \Auth::guard("teacher")->id();
        $schoolsId = $this->getSchool()->id;
        $posts = Post::select('posts.id as pid', 'posts.file_ext', 'posts.export_name', 'posts.writing_date', 'teachers.username', 'writing_types.name as writing_type_name', 'post_rates.rate', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                ->leftjoin('teachers', 'posts.teachers_id', '=', 'teachers.id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->leftjoin('post_rates', 'post_rates.posts_id', '=', 'posts.id')
                ->leftjoin('writing_types', 'writing_types.id', '=', 'posts.writing_types_id')
                ->where('teachers.schools_id', '=', $schoolsId)
                ->where('writing_types.id', '=', $writingTypesId)
                ->where('marks.state_code', '=', 1)
                ->groupBy('posts.id', 'posts.file_ext', 'posts.export_name', 'teachers.username', 'posts.writing_date', 'post_rates.rate', 'writing_types.name')
                ->orderby("mark_num", "DESC")->paginate(12);
        return $posts;
    }

    public function getHasCommentPostsData($writingTypesId) {
        $id = \Auth::guard("student")->id();
        $schoolsId = $this->getSchool()->id;
        $posts = Post::select('posts.id as pid', 'sclasses.class_title', 'terms.grade_key', 'post_rates.rate', 'posts.file_ext', 'posts.export_name', 'students.username', 'comments.content', 'comments.id as cid', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                // ->where('posts.students_id', '<>', $id)
                ->leftjoin('students', 'posts.students_id', '=', 'students.id')
                ->leftjoin('sclasses', 'students.sclasses_id', '=', 'sclasses.id')
                ->leftjoin('post_rates', 'posts.id', '=', 'post_rates.posts_id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->leftjoin('comments', 'comments.posts_id', '=', 'posts.id')
                ->leftjoin('terms', 'terms.enter_school_year', '=', 'sclasses.enter_school_year')
                ->where('terms.is_current', '=', 1)
                ->where('sclasses.schools_id', '=', $schoolsId)
                ->groupBy('posts.id', 'sclasses.class_title', 'terms.grade_key', 'post_rates.rate', 'posts.file_ext', 'posts.export_name', 'students.username', 'comments.id', 'comments.content')
                ->orderby("cid", "DESC")->paginate(12);
        return $posts;
    }

    public function getSearchNamePostsData($searchName) {
        $schoolsId = $this->getSchool()->id;
        $posts = Post::select('posts.id as pid', 'sclasses.class_title', 'terms.grade_key', 'post_rates.rate', 'posts.file_ext', 'posts.export_name', 'students.username', 'comments.id as cid', 'comments.content', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                ->join('students', 'posts.students_id', '=', 'students.id')
                ->join('sclasses', 'students.sclasses_id', '=', 'sclasses.id')
                ->leftjoin('post_rates', 'posts.id', '=', 'post_rates.posts_id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->leftjoin('comments', 'comments.posts_id', '=', 'posts.id')
                ->leftjoin('terms', 'terms.enter_school_year', '=', 'sclasses.enter_school_year')
                ->where('terms.is_current', '=', 1)
                ->where('sclasses.schools_id', '=', $schoolsId)
                ->where('students.username', 'like', '%'.$searchName.'%')
                ->groupBy('posts.id', 'sclasses.class_title', 'terms.grade_key', 'post_rates.rate', 'posts.file_ext', 'posts.export_name', 'students.username', 'comments.id', 'comments.content')
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
