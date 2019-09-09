<?php

namespace App\Http\Controllers\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\WritingType;
use App\Models\StageCheck;
use App\Models\Teacher;
use App\Models\Post;
use DB;

class StageCheckController extends Controller
{
    public function index() 
    {
        $schoolsId = \Auth::guard("school")->id();
        $writingTypes = WritingType::all();
        return view('school/stage-check/index', compact("writingTypes", 'schoolsId'));
    }

    public function getStageCheck(Request $request)
    {
        $schoolsId = \Auth::guard("school")->id();

        $stageChecks = StageCheck::leftJoin("writing_types", 'stage_checks.writing_types_id', '=', 'writing_types.id')
        ->orderBy("stage_checks.check_date", "ASC")->get();

        // foreach ($stageChecks as $key => $stageCheck) {
        //     $post = Post::select('posts.writing_date', 'writing_types.name', DB::raw("COUNT(`post_rates`.`id`) as rate_num"), DB::raw("COUNT(`posts`.`id`) as post_num"))
        //         ->leftJoin("post_rates", 'post_rates.posts_id', '=', 'posts.id')
        //         ->leftJoin("writing_types", 'posts.writing_types_id', '=', 'writing_types.id')
        //         ->where("posts.writing_date", "=", $stageCheck->check_date)
        //         ->where("posts.writing_types_id", "=", $stageCheck->writing_types_id)
        //         ->groupBy('posts.writing_date', 'writing_types.name')
        //         ->first();
        //     if (isset($post)) {
        //         $stageCheckData[] = $post;
        //     }
        // }
        return $stageChecks;
    }

    public function getStageReport(Request $request)
    {
        $schoolsId = \Auth::guard("school")->id();

        $writingTypesId = $request->get("writingTypesId");
        $writingDate = $request->get("writingDate");
        $teachers = Teacher::leftJoin("posts", 'posts.teachers_id', '=', 'teachers.id')
        ->leftJoin("post_rates", 'post_rates.posts_id', '=', 'posts.id')
        ->where("teachers.schools_id", "=", $schoolsId)
        ->where("posts.writing_types_id", "=", $writingTypesId)
        ->where("posts.writing_date", "=", $writingDate)
        ->get();

        return $teachers;


    }

    public function getStageCheckData(Request $request)
    {
        $schoolsId = \Auth::guard("school")->id();

        $stageChecks = StageCheck::orderBy("stage_checks.check_date", "ASC")->get();

        foreach ($stageChecks as $key => $stageCheck) {
            $post = Post::select('posts.writing_date', 'writing_types.name', DB::raw("COUNT(`post_rates`.`id`) as rate_num"), DB::raw("COUNT(`posts`.`id`) as post_num"))
                ->leftJoin("post_rates", 'post_rates.posts_id', '=', 'posts.id')
                ->leftJoin("writing_types", 'posts.writing_types_id', '=', 'writing_types.id')
                ->where("posts.writing_date", "=", $stageCheck->check_date)
                ->where("posts.writing_types_id", "=", $stageCheck->writing_types_id)
                ->groupBy('posts.writing_date', 'writing_types.name')
                ->first();
            if (isset($post)) {
                $stageCheckData[] = $post;
            }
        }
        return $stageCheckData;
    }

    public function createStageCheck(Request $request)
    {
        $check_date = $request->get("check_date");
        $writing_types_id = $request->get("writing_types_id");
        $schools_id = $request->get("schools_id");
        $stageCheck = new StageCheck();
        $stageCheck->check_date = $check_date;
        $stageCheck->writing_types_id = $writing_types_id;
        $stageCheck->schools_id = $schools_id;
        if ($stageCheck->save()) {
            return "true";
        } else {
            return "false";
        }
    }
}
