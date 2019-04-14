<?php

namespace App\Http\Controllers\Mentor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\WritingType;
use App\Models\WritingDetail;
use App\Models\WritingRule;
use App\Models\RuleComment;
use App\Models\Comment;
use App\Models\Post;
use App\Models\StageCheck;
use DB;

class StageController extends Controller
{
    public function penIndex() 
    {
        return $this->makeIndex(1);
    }

    public function chalkIndex() 
    {
        return $this->makeIndex(2);
    }

    public function brushIndex() 
    {
        return $this->makeIndex(3);
    }

    public function mandarinIndex() 
    {
        return $this->makeIndex(4);
    }

    public function makeIndex($writingTypesId)
    {
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

        $writingDetails = WritingDetail::select("writing_details.*")
            ->leftJoin("writing_rules", "writing_rules.id", "=", "writing_details.writing_rules_id")
            ->where("writing_rules.writing_types_id", "=", $writingTypesId)
            ->get();
        // dd($writingDetails);
        return view('mentor/stage/index', compact("stageCheckData", 'writingTypesId', 'writingDetails'));
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

            $prevPostsId = isset($posts[$key - 1])?($key - 1):"";
            $nextPostsId = isset($posts[$key + 1])?($key + 1):"";

            $returnHtml .= "<div class='alert " . $rateCss . " col-md-2 col-xs-4' style='margin:10px;'><img class='img-responsive post-btn center-block' thumbnail='" . $bigThumbnail . "' prevPostsId='" . $prevPostsId . "' nextPostsId='" . $nextPostsId . "' rate='" . $post->rate . "' value='" .  $post->id . "' src='" . $smallThumbnail . "' alt=''><div><h4 style='margin-top: 10px; ' class='text-center'><small>" . $post->username . " </small> " . $rateStr ."</h4>  </div></div>";
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

    public function addOtherComment(Request $request)
    {
        $postsId = $request->get("posts_id");
        $otherCommentContent = $request->get("other_comment_content");
        $comment = Comment::where("posts_id", "=", $postsId)->first();
        if (isset($comment)) {
            $comment->content = $otherCommentContent;
            $comment->update();
        } else {
            if ("" != $otherCommentContent) {
                $mentorsId = auth()->guard('mentor')->id();
                $comment = new Comment();
                $comment->mentors_id = $mentorsId;
                $comment->posts_id = $postsId;
                $comment->content = $otherCommentContent;
                $comment->save();
            }
        }

        
    }

    public function getOtherComment(Request $request)
    {
        $postsId = $request->get("posts_id");
        $comment = Comment::where("posts_id", "=", $postsId)->first();
        if (isset($comment)) {
            return $comment->content;
        } else {
            return "";
        }
    }

    public function getRuleComment(Request $request)
    {
        $postsId = $request->get("posts_id");
        $ruleComments = RuleComment::where("posts_id", "=", $postsId)->get();
        $goodDetailIds = "";
        $badDetailIds = "";
        foreach ($ruleComments as $key => $ruleComment) {
            if ("good" == $ruleComment->state_flag) {
                $goodDetailIds .= $ruleComment->writing_details_id . ",";
            } else {
                $badDetailIds .= $ruleComment->writing_details_id . ",";
            }
        }
        return $goodDetailIds . " " . $badDetailIds;
    }

    public function addRuleComment(Request $request)
    {
        $postsId = $request->get("posts_id");
        $detailsId = $request->get("details_id");
        $stateFlag = $request->get("state_flag");
        $writingDetail = WritingDetail::find($detailsId);

        $mentorsId = auth()->guard('mentor')->id();
        $ruleComment = new RuleComment();
        $ruleComment->mentors_id = $mentorsId;
        $ruleComment->posts_id = $postsId;
        $ruleComment->writing_rules_id = $writingDetail->writing_rules_id;
        $ruleComment->writing_details_id = $detailsId;
        $ruleComment->state_flag = $stateFlag;
        $ruleComment->save();
    }

    public function deleteRuleComment(Request $request)
    {
        $postsId = $request->get("posts_id");
        $detailsId = $request->get("details_id");
        $stateFlag = $request->get("state_flag");
        $writingDetail = WritingDetail::find($detailsId);

        $mentorsId = auth()->guard('mentor')->id();
        $ruleComment = RuleComment::where(["posts_id" => $postsId, "writing_details_id" => $detailsId, "state_flag" => $stateFlag, "mentors_id" => $mentorsId])->first();
        if (isset($ruleComment)) {
            $ruleComment->delete();
        }
    }

    public function getPrevOrNextPost(Request $request)
    {
        $postsId = $request->get("posts_id");

    }

    public function getSchoolCode()
    {
      // $teacher = Teacher::find(Auth::guard("teacher")->id());
      // $school = School::where('schools.id', '=', $teacher->schools_id)->first();
      // return $school->code;
      return "ys";
    }
}
