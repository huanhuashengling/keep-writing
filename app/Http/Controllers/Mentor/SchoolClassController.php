<?php

namespace App\Http\Controllers\Mentor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\SchoolClass;

class SchoolClassController extends Controller
{
    public function index()
    {
        return view('teacher/class/index')->withSchoolClasses(SchoolClass::all());
    }
}
