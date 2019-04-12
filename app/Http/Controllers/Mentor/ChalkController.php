<?php

namespace App\Http\Controllers\Mentor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\WritingType;
use App\Models\Post;
use App\Models\StageCheck;
use DB;

class ChalkController extends Controller
{
    public function index() 
    {
        $writingTypesId = 2;
        $stageChecks = StageCheck::select('stage_checks.check_date')
                        ->where("stage_checks.writing_types_id", "=", $writingTypesId)
                        ->orderBy("stage_checks.check_date", "ASC")->get();
        $stageCheckData = [];                
        foreach ($stageChecks as $key => $stageCheck) {
            $posts = Post::select('posts.writing_date', DB::raw("COUNT(`post_rates`.`id`) as rate_num"), DB::raw("COUNT(`posts`.`id`) as post_num"))
                    ->leftJoin("post_rates", 'post_rates.posts_id', '=', 'posts.id')
                    ->where("posts.writing_types_id", "=", $writingTypesId)
                    ->where("posts.writing_date", "=", $stageCheck->check_date)
                    ->groupBy('posts.writing_date')
                    ->get();
            if (isset($posts[0])) {
                $stageCheckData[] = ["writing_date" => $stageCheck->check_date, "rate_num" => $posts[0]->rate_num, "post_num" => $posts[0]->post_num];
            } else {
                $stageCheckData[] = ["writing_date" => $stageCheck->check_date, "rate_num" => 0, "post_num" => 0]; 
            }
        }

        return view('mentor/stage/index', compact("stageCheckData", 'writingTypesId'));
    }

    public function getWritingDateByWritingType(Request $request)
    {
        $returnHtml = "";
        $writingTypesId = $request->get('writingTypesId');
        $stageChecks = StageCheck::select('stage_checks.check_date')
                        ->where("stage_checks.writing_types_id", "=", $writingTypesId)
                        ->orderBy("stage_checks.check_date", "ASC")->get();
        $stageCheckData = [];                
        foreach ($stageChecks as $key => $stageCheck) {
            $posts = Post::select('posts.writing_date', DB::raw("COUNT(`post_rates`.`id`) as rate_num"), DB::raw("COUNT(`posts`.`id`) as post_num"))
                    ->leftJoin("post_rates", 'post_rates.posts_id', '=', 'posts.id')
                    ->where("posts.writing_types_id", "=", $writingTypesId)
                    ->where("posts.writing_date", "=", $stageCheck->check_date)
                    ->groupBy('posts.writing_date')
                    ->get();
        $stageCheckData[] = $posts[0]; 
        }
        foreach ($stageCheckData as $key => $stageCheck) {
            $returnHtml .= "<button class='btn btn-default writing-date-btn' value='" . $stageCheck->writing_date . "'>".$stageCheck->writing_date . " <span class='badge'>" . $stageCheck->rate_num . "/" . $stageCheck->post_num . "</span></button>";
        }
        return $returnHtml;
    }

    public function getPostsCountByWritingType(Request $request)
    {
        $returnHtml = "";
        $writingTypesId = $request->get('writingTypesId');
        $teachers = Teacher::select('teachers.username', 'teachers.id as teachersId', DB::raw("COUNT(`post_rates`.`id`) as rate_num"), DB::raw("COUNT(`posts`.`id`) as post_num"))
                    ->leftJoin("posts", 'posts.teachers_id', '=', 'teachers.id')
                    ->leftJoin("post_rates", 'post_rates.posts_id', '=', 'posts.id')
                    ->where("posts.writing_types_id", "=", $writingTypesId)
                    ->groupBy('teachers.username', 'teachers.id')
                    ->get();
        foreach ($teachers as $key => $teacher) {
            $returnHtml .= "<button class='btn btn-default teacher-btn' value='" . $teacher->teachersId . "'>".$teacher->username . " <span class='badge'>" . $teacher->rate_num . "/" . $teacher->post_num . "</span></button>";
        }
        return $returnHtml;
    }

    public function getPostsByWritingTypeAndDate(Request $request)
    {
        $schoolCode = $this->getSchoolCode();
        $returnHtml = "";
        $writingDate = $request->get('writingDate');
        $writingTypesId = $request->get('writingTypesId');

        $posts = Post::select("posts.*", "post_rates.rate", "teachers.username")
                    ->leftJoin("post_rates", 'post_rates.posts_id', '=', 'posts.id')
                    ->leftJoin("teachers", 'teachers.id', '=', 'posts.teachers_id')
                    ->leftJoin("schools", 'schools.id', '=', 'teachers.schools_id')
                    ->where('teachers.schools_id', '=', 1)
                    ->where('posts.writing_types_id', '=', $writingTypesId)
                    ->where('posts.writing_date', '=', $writingDate)
                    ->orderBy("posts.writing_date", "DESC")
                    ->get();
    // dd($posts);

        foreach ($posts as $key => $post) {
            $smallThumbnail = getThumbnail($post->storage_name, 121, 162, $schoolCode, 'background', $post->file_ext);
            $bigThumbnail = getThumbnail($post->storage_name, 700, 930, $schoolCode, 'background', $post->file_ext);
            $rateStr = "";
            $rateCss = "alert-default";
            if (isset($post->rate)) {
                $rateStr = $post->rate . "星";
                $rateCss = "alert-danger";
            }
            $tWriteDate = substr($post->writing_date, 4, 2) . "月" . substr($post->writing_date, 6, 2) . "日";

            $returnHtml .= "<div class='alert " . $rateCss . " col-md-2 col-xs-4' style='margin:10px;'><img class='img-responsive post-btn center-block' thumbnail='" . $bigThumbnail . "' rate='" . $post->rate . "' value='" .  $post->id . "' src='" . $smallThumbnail . "' alt=''><div><h4 style='margin-top: 10px; ' class='text-center'><small>" . $tWriteDate . " </small> " . $rateStr ."</h4>  </div></div>";
        }
        return $returnHtml;

    }

    public function rateOnePost(Request $request)
    {
        $rate = $request->get("rate");
        $postsId = $request->get("postsId");
        $userId = auth()->guard('mentor')->id();

        $postRate = PostRate::where("mentors_id", '=', $userId)
                ->where("posts_id", '=', $postsId)
                ->first();
        if(isset($postRate)) {
            $postRate->rate = $rate;
            if ($postRate->update()) {
                return "true";
            } else {
                return "false";
            }
        } else {
            $postRate = new PostRate();
            $postRate->rate = $rate;
            $postRate->posts_id = $postsId;
            $postRate->mentors_id = $userId;
            if ($postRate->save()) {
                return "true";
            } else {
                return "false";
            }
        }
    }

    public function getSchoolCode()
    {
      // $teacher = Teacher::find(Auth::guard("teacher")->id());
      // $school = School::where('schools.id', '=', $teacher->schools_id)->first();
      // return $school->code;
      return "ys";
    }
}
