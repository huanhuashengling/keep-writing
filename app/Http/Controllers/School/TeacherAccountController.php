<?php

namespace App\Http\Controllers\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\TeachersImport;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class TeacherAccountController extends Controller
{
    public function index()
    {
        $schoolsId = \Auth::guard("school")->id();
        $subjects = Subject::all();
        return view('school/teacher-account/index', compact('schoolsId', 'subjects'));
    }

    public function getTeachersAccountData()
    {
        $schoolsId = \Auth::guard("school")->id();
        $school = School::find($schoolsId);
        if (isset($school)) {
            $teachers = Teacher::select('schools.*', 'teachers.*')
                        ->leftJoin('schools', function($join){
                          $join->on('schools.id', '=', 'teachers.schools_id');
                        })
                        ->where(['schools_id' => $school->id])->get();
            return json_encode($teachers);
        } else {
            return "false";
        }
    }

    public function createOneTeacherAccount(Request $request)
    {
        try {
            $teacher = Teacher::create([
                'username' => $request->get('username'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password')),
                'schools_id' => $request->get('schools_id'),
                'remember_token' => str_random(10),
            ]);
        } catch (Exception $e) {
            throw new Exception("Error Processing Request", 1);
        }
    }

    public function resetTeacherPassword(Request $request) {
        $teacher = Teacher::find($request->get('users_id'));
        if ($teacher) {
            $teacher->password = bcrypt("123456");
            $teacher->save();
            return "true";
        } else {
            return "false";
        }
    }

    public function importTeachers(Request $request)
    {
        if($request->hasFile('xls')){
            $path = $request->file('xls')->getRealPath();
            $data = Excel::load($path, function($reader) {})->get();
            if(!empty($data) && $data->count()){
// dd($data);
                foreach ($data[0]->toArray() as $value) {
                    // dd($value);

                    if(!empty($value)){
                        $this->createTeacherAccount($value);
                        // die();
                    }
                }
            }
        }
    }

    public function lockOneTeacherAccount(Request $request) {
        $teacher = Teacher::find($request->get('users_id'));
        if ($teacher) {
            $teacher->is_lock = 1;
            $teacher->save();
            return "true";
        } else {
            return "false";
        }
    }

    public function unlockOneTeacherAccount(Request $request) {
        $teacher = Teacher::find($request->get('users_id'));
        if ($teacher) {
            $teacher->is_lock = 0;
            $teacher->save();
            return "true";
        } else {
            return "false";
        }
    }

    public function createOneTeacher(Request $request)
    {
        $data = [];
        $data["username"] = $request->get('username');
        $data["sex"] = $request->get('sex');
        $data["password"] = "123456";
        $data["birth_date"] = $request->get('birth_date');
        $data["subjects_id"] = $request->get('subjects_id');
        $data["email"] = $request->get('email');
        $data["phone_number"] = $request->get('phone_number');
        $data["is_formal"] = $request->get('is_formal');
        $data["schools_id"] = \Auth::guard("school")->id();
        return $this->createTeacherAccount($data);
    }

    public function createTeacherAccount($data) {
        try {
            $teacher = Teacher::create([
                'username' => $data['username'],
                'email' => isset($data["email"])?$data["email"]:"",
                'password' => bcrypt($data['password']),
                'sex' => $data['sex'],
                'subjects_id' => $data['subjects_id'],
                'birth_date' => $data['birth_date'],
                'phone_number' => $data['phone_number'],
                'schools_id' => \Auth::guard("school")->id(),
                'is_lock' => 0,
                'is_formal' => $data['is_formal'],
                'remember_token' => str_random(10),
            ]);
        } catch (Exception $e) {
            throw new Exception("Error Processing Request", 1);
        }
    }
}
