<?php

namespace App\Http\Controllers\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Teacher;
use App\Models\WritingType;
use App\Models\Post;
use \DB;


class KeepRecordController extends Controller
{
    public function index() 
    {
        return view('school/keep-record/index');
    }

    public function getKeepRecord(Request $request) {
        $userId = auth()->guard('school')->id();
        $school = School::find($userId);
        $teachers = Teacher::where("schools_id", "=", $userId)->where("teachers.is_lock", "!=", "1")->get();
        $writingTypes = WritingType::all();
        
        // dd($teachers);
        // order username postednum unpostnum rate1num rate2num rate3num rate4num commentnum marknum scorecount
        $dataset = [];
        foreach ($teachers as $key => $teacher) {
            $tData = [];
            $tData['users_id'] = $teacher->id;
            $tData['username'] = $teacher->username;
            $birthDateStr = substr($teacher->birth_date, 0, 4) . "-" .  substr($teacher->birth_date, 4, 2) . "-" .  substr($teacher->birth_date, 6, 2);
            $tData['ageSection'] = $this->countage($birthDateStr);
            $tData['isFormal'] = $teacher->is_formal;
            $tData['allScoreCount'] = 0;
            foreach ($writingTypes as $key => $writingType) {
                $posts = Post::select('posts.id as pid', 'posts.file_ext', 'posts.storage_name', 'posts.writing_date', 'posts.writing_types_id', 'writing_types.name as writing_type_name', 'teachers.username', 'post_rates.rate', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                // ->where('posts.students_id', '<>', $id)
                ->join('teachers', 'posts.teachers_id', '=', 'teachers.id')
                ->leftjoin('marks', 'marks.posts_id', '=', 'posts.id')
                ->leftjoin('post_rates', 'post_rates.posts_id', '=', 'posts.id')
                ->join('writing_types', 'writing_types.id', '=', 'posts.writing_types_id')
                ->where('teachers.schools_id', '=', $userId)
                ->where('teachers.id', '=', $teacher->id)
                ->where("writing_types.id", '=', $writingType->id)
                ->groupBy('posts.id', 'posts.file_ext', 'posts.storage_name', 'teachers.username', 'posts.writing_date', 'posts.writing_types_id', 'writing_types.name', 'post_rates.rate')
                ->orderby("posts.writing_date", "DESC")->get();

                $tData[$writingType->code . 'PostNum'] = count($posts);
                $markNum = 0;
                $starNum = 0;
                foreach ($posts as $key => $post) {
                    if (isset($post->mark_num)) {
                        $markNum += $post->mark_num;
                    }
                    if (isset($post->rate)) {
                        $starNum += $post->rate;
                    }
                }
                $tData[$writingType->code . 'MarkNum'] = $markNum;
                $tData[$writingType->code . 'StarNum'] = $starNum;
                $tData[$writingType->code . 'Score'] = $starNum + count($posts);
                $tData['allScoreCount'] += count($posts) + $starNum;
            }
            $dataset[] = $tData;

        }
        return $dataset;
    }

    function countage($birthday){
        $year=date('Y');
        $month=date('m');
        if(substr($month,0,1)==0){
            $month=substr($month,1);
        }
        $day=date('d');
        if(substr($day,0,1)==0){
            $day=substr($day,1);
        }
        $arr=explode('-',$birthday);

        $age=$year-$arr[0];
        if($month<$arr[1]){
            $age=$age-1;

        }elseif($month==$arr[1]&&$day<$arr[2]){
            $age=$age-1;

        }

        return ($age < 35)?"青年":"-";

    }
}
