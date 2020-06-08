<?php

namespace App\Http\Controllers\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Teacher;
use App\Models\WritingType;
use App\Models\Post;
use \DB;
use Carbon\Carbon;


class KeepRecordController extends Controller
{
    public function index() 
    {
        $startWeekInfo = $this->getTimeInfo('2020-02-10');
        $nowWeekInfo = $this->getTimeInfo(Carbon::now('Asia/Shanghai')->format('Ymd'));
        // var_dump($startWeekInfo);
        // var_dump($nowWeekInfo);
        $currentWeekNum = $nowWeekInfo["week"] - $startWeekInfo["week"];
        $weekDate = [];
        // echo $week;
        for ($i=$currentWeekNum; $i >= 0 ; $i--) { 
        $weekStart = Carbon::now('Asia/Shanghai')->subWeek($i)->startOfWeek()->format('Ymd');
        $weekEnd =  Carbon::now('Asia/Shanghai')->subWeek($i)->endOfWeek()->format('Ymd');
        $weekDate[] = ["weekNum" => $currentWeekNum - $i+1, "weekStart" => $weekStart, "weekEnd" => $weekEnd];
            
        }
        // var_dump($weekDate);   

        return view('school/keep-record/index', compact("weekDate"));
    }

    public function getKeepRecord(Request $request) {
        $userId = auth()->guard('school')->id();
        $school = School::find($userId);
        $teachers = Teacher::where("schools_id", "=", $userId)->where("teachers.is_lock", "!=", "1")->get();
        $writingTypes = WritingType::all();

        $dayGap = $request->get("dayGap");
        $startDay = explode('-', $dayGap)[0];
        $endDay = explode('-', $dayGap)[1];
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
                ->whereBetween("writing_date", [$startDay, $endDay])
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

        $age=(int)$year-(int)$arr[0];
        if($month<$arr[1]){
            $age=$age-1;

        }elseif($month==$arr[1]&&$day<$arr[2]){
            $age=$age-1;

        }

        return ($age < 35)?"青年":"-";

    }


    public function getTimeInfo($now)
    {
        $str = array();
        //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
        $str['year'] = date('Y', strtotime($now));
        $first = 1;
        //当日在整年中的第几周
        $str['week'] = date('W', strtotime($now));
        //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $w = date('w', strtotime($now));
        //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $week_start = date('Ymd', strtotime("$now -" . ($w ? $w - $first : 6) . ' days'));
        $str['week_start'] = $week_start;
        //本周结束日期
        $week_end = date('Ymd', strtotime("$week_start +6 days"));
        $str['week_end'] = $week_end;
        return $str;

    }
}
