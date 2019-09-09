<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\WritingDetail;
use App\Models\WritingRule;
use App\Models\RuleComment;
use App\Models\WordComment;
use App\Models\MutualRate;
use App\Models\Teacher;
use App\Models\School;
use App\Models\Post;
use App\Models\StageCheck;
use \Auth;


class MutualEvaluationController extends Controller
{
    public function index()
    {
        $id = auth()->guard("teacher")->id();
        $schoolCode = $this->getSchool()->code;
        $baseUrl = env('APP_URL') . '/posts/' . $schoolCode . "/";

        $stageChecks = StageCheck::all();
        $post = "";
        $stageCheckIds = [];
        foreach ($stageChecks as $key => $tStageCheck) {
            $stageCheckIds[] = $tStageCheck->id;
        }
        // dd($stageCheckIds[rand(0, count($stageCheckIds)-1)]);
        $stageCheck = StageCheck::find($stageCheckIds[rand(0, count($stageCheckIds)-1)]);
        // foreach ($stageChecks as $key => $stageCheck) {
            $writingTypesId = $stageCheck->writing_types_id;
            $checkDate = $stageCheck->check_date;

            $posts = Post::where(["writing_types_id" => $writingTypesId, "writing_date" => $checkDate])
            ->get();
            
            foreach ($posts as $key => $tPost) {
                $mutualRate = MutualRate::where(["teachers_id" => $id, "posts_id" => $tPost->id])->first();
                if (!isset($mutualRate)) {
                    $post = $tPost;
                    break;
                }
            }

            // if ("" != $post) {
            //     break;
            // }
        // }

        

        // dd($tPost);

        $writingRules = WritingRule::where("writing_rules.writing_types_id", "=", $writingTypesId)->get();

        $writingDetailsData = [];
        foreach ($writingRules as $key => $writingRule) {
            $writingDetails = WritingDetail::where("writing_rules_id", "=", $writingRule->id)->get();
            $writingDetailsData[$writingRule->rule_desc] = [];
            foreach ($writingDetails as $key => $writingDetail) {
                $writingDetailsData[$writingRule->rule_desc][] = ["id" => $writingDetail->id, "desc" => $writingDetail->detail_desc, 'score' => $writingDetail->score];
            }
        }

        return view('teacher/mutual-evaluation/index', compact("writingDetailsData", "post", "schoolCode"));
    }

    public function addWordComment(Request $request)
    {
        // dd("asasasas");
        $id = auth()->guard("teacher")->id();

        $postsId = $request->get("posts_id");
        $goodWord = $request->get("good_word");
        $badWord = $request->get("bad_word");
        $wordComment = WordComment::where(["posts_id" => $postsId, "teachers_id" => $id])->first();
        // dd($wordComment);
        if (isset($wordComment)) {
            $wordComment->good_word = $goodWord;
            $wordComment->bad_word = $badWord;
            $wordComment->update();
        } else {
            $wordComment = new WordComment();
            $wordComment->teachers_id = $id;
            $wordComment->posts_id = $postsId;
            $wordComment->good_word = $goodWord;
            $wordComment->bad_word = $badWord;
            $wordComment->save();
        }
    }

    public function getWordComment(Request $request)
    {
        $id = auth()->guard("teacher")->id();
        $postsId = $request->get("posts_id");
        $wordComment = WordComment::where(["posts_id" => $postsId, "teachers_id" => $id])->first();
        if (isset($wordComment)) {
            return $wordComment;
        } else {
            return "";
        }
    }

    public function addMutualRate(Request $request)
    {
        $id = auth()->guard("teacher")->id();

        $postsId = $request->get("posts_id");
        $rate = $request->get("mutual_rate");
        $mutualRate = MutualRate::where(["posts_id" => $postsId, "teachers_id" => $id])->first();
        // dd($wordComment);
        
        if (isset($mutualRate)) {
            $mutualRate->rate = $rate;
            $mutualRate->update();
        } else {
            $mutualRate = new MutualRate();
            $mutualRate->teachers_id = $id;
            $mutualRate->posts_id = $postsId;
            $mutualRate->rate = $rate;
            $mutualRate->save();
        }
    }

    public function getMutualRate(Request $request)
    {
        $id = auth()->guard("teacher")->id();

        $postsId = $request->get("posts_id");
        $mutualRate = MutualRate::where(["posts_id" => $postsId, "teachers_id" => $id])->first();
        if (isset($mutualRate)) {
            return $mutualRate->rate;
        } else {
            return "";
        }
    }

    public function getRuleComment(Request $request)
    {
        $id = auth()->guard("teacher")->id();

        $postsId = $request->get("posts_id");
        $ruleComments = RuleComment::where(["posts_id" => $postsId, "teachers_id" => $id])->get();
        $goodDetailIds = "";
        foreach ($ruleComments as $key => $ruleComment) {
            if ("good" == $ruleComment->state_flag) {
                $goodDetailIds .= $ruleComment->writing_details_id . ",";
            }
        }
        return $goodDetailIds;
    }

    public function addRuleComment(Request $request)
    {
        $postsId = $request->get("posts_id");
        $detailsId = $request->get("details_id");
        $stateFlag = $request->get("state_flag");
        $writingDetail = WritingDetail::find($detailsId);

        $teachersId = auth()->guard('teacher')->id();
        $ruleComment = new RuleComment();
        $ruleComment->teachers_id = $teachersId;
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

        $teachersId = auth()->guard('teacher')->id();
        $ruleComment = RuleComment::where(["posts_id" => $postsId, "writing_details_id" => $detailsId, "state_flag" => $stateFlag, "teachers_id" => $teachersId])->first();
        if (isset($ruleComment)) {
            $ruleComment->delete();
        }
    }

    public function getPrevOrNextPost(Request $request)
    {
        $postsId = $request->get("posts_id");

    }

    public function getSchool()
    {
      $teacher = Teacher::find(Auth::guard("teacher")->id());

      $school = School::where('id', '=', $teacher->schools_id)->first();
      return $school;
    }
}
