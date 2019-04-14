<?php

namespace App\Http\Controllers\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\WritingRule;
use App\Models\WritingDetail;
use App\Models\WritingType;

class WritingRuleController extends Controller
{
    public function index() 
    {
        $schoolsId = \Auth::guard("school")->id();
        $writingTypes = WritingType::all();
        return view('school/writing-rule/index', compact('schoolsId', "writingTypes"));
    }

    public function getWritingRule(Request $request)
    {
        $schoolsId = \Auth::guard("school")->id();

        $writingRules = WritingRule::select("writing_rules.*", "writing_types.name")
        ->leftJoin("writing_types", 'writing_rules.writing_types_id', '=', 'writing_types.id')->get();
        return $writingRules;
    }

    public function getWritingDetail(Request $request)
    {
        $schoolsId = \Auth::guard("school")->id();
        $writingRulesId = $request->get("writingRulesId");
        $writingDetails = WritingDetail::where("writing_details.writing_rules_id", "=", $writingRulesId)->get();
        return $writingDetails;
    }

    public function createWritingRule(Request $request)
    {
        $id = $request->get("id");
        $ruleDesc = $request->get("rule_desc");
        $weightRatio = $request->get("weight_ratio");
        $writingTypesId = $request->get("writing_types_id");

        $writingRule = WritingRule::find($id);
        if (isset($writingRule)) {
            $writingRule->rule_desc = $ruleDesc;
            $writingRule->weight_ratio = $weightRatio;
            $writingRule->writing_types_id = $writingTypesId;
            $writingRule->update();
        } else {
            $writingRule = new WritingRule();
            $writingRule->rule_desc = $ruleDesc;
            $writingRule->weight_ratio = $weightRatio;
            $writingRule->writing_types_id = $writingTypesId;
            $writingRule->save();
        }
    }

    public function createWritingDetail(Request $request)
    {
        $id = $request->get("id");
        $detailDesc = $request->get("detail_desc");
        $writingRulesId = $request->get("writing_rules_id");

        $writingDetail = WritingDetail::find($id);
        if (isset($writingDetail)) {
            $writingDetail->detail_desc = $detailDesc;
            $writingDetail->writing_rules_id = $writingRulesId;
            $writingDetail->update();
        } else {
            $writingDetail = new WritingDetail();
            $writingDetail->detail_desc = $detailDesc;
            $writingDetail->writing_rules_id = $writingRulesId;
            $writingDetail->save();
        }
    }
}
