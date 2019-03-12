<?php

namespace App\Http\Controllers\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Teacher;
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
        
        // dd($teachers);
        // order username postednum unpostnum rate1num rate2num rate3num rate4num commentnum marknum scorecount
        $dataset = [];
        foreach ($teachers as $key => $teacher) {
            $tData = [];
            $tData['users_id'] = $teacher->id;
            $tData['phone_number'] = $teacher->phone_number;
            $tData['username'] = $teacher->username;
            $tData['postedNum'] = Post::where("posts.teachers_id", '=', $teacher->id)->count();
            // $tData['postedNum'] 
            $posts = Post::select('posts.id', DB::raw("SUM(`marks`.`state_code`) as mark_num"))
                            ->leftJoin("marks", 'marks.posts_id', '=', 'posts.id')
                            ->where("posts.teachers_id", '=', $teacher->id)
                            ->where("marks.state_code", "=", 1)
                            ->groupBy('posts.id')
                            ->get();
            $tData['markNum'] = 0;
            foreach ($posts as $key => $post) {
                $tData['markNum'] += $post->mark_num;
            }
            // dd($posts);
            // $tData['commentNum'] = Comment::leftJoin("posts", 'posts.id', '=', 'comments.posts_id')
            //                                 ->leftJoin("lesson_logs", 'lesson_logs.id', '=', 'posts.lesson_logs_id')
            //                                 ->leftJoin("teachers", 'teachers.id', '=', 'posts.teachers_id')
            //                                 ->where("posts.teachers_id", '=', $teacher->id)
            //                                 ->whereBetween('lesson_logs.created_at', array($from, $to))
            //                                 ->count();
                    
            // $rates = PostRate::leftJoin("posts", 'posts.id', '=', 'post_rates.posts_id')
            //                     ->leftJoin("lesson_logs", 'lesson_logs.id', '=', 'posts.lesson_logs_id')
            //                     ->leftJoin("teachers", 'teachers.id', '=', 'posts.teachers_id')
            //                     ->where("posts.teachers_id", '=', $teacher->id)
            //                     ->whereBetween('lesson_logs.created_at', array($from, $to))
            //                     ->get();
            $tData['rateYouJiaNum'] = 0;
            $tData['rateYouNum'] = 0;
            $tData['rateDaiWanNum'] = 0;
            $tData['scoreCount'] = $tData['postedNum'] * 1 + $tData['rateYouNum'] * 1 + $tData['rateYouJiaNum'] * 2;
            $dataset[] = $tData;

        }
        return $dataset;
    }
}
