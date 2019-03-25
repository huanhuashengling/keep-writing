<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Resource;
use App\Models\ResourceLog;

class ResourcesController extends Controller
{
    public function index(Request $request)
    {   
        $id = auth()->guard("teacher")->id();

        $penResources = Resource::where("writing_types_id", "=", 1)->where("is_open", "=", 1)->get();

        $chalkResources = Resource::where("writing_types_id", "=", 2)->where("is_open", "=", 1)->get();

        $brushResources = Resource::where("writing_types_id", "=", 3)->where("is_open", "=", 1)->get();

        $baseUrl = env('APP_URL');

        return view('teacher/resources/index', compact('penResources', 'chalkResources', 'brushResources', 'baseUrl'));
    }

    public function addResourceLog(Request $request)
    {
        $resourceLog = new ResourceLog();
        $resourceLog->teachers_id = auth()->guard("teacher")->id();
        $resourceLog->resources_id = $request->get("resourcesId");
        $resourceLog->writing_types_id = $request->get("writingTypesId");
        if ($resourceLog->save()) {
            return "true";
        } else {
            return "false";
        }
    }
}
