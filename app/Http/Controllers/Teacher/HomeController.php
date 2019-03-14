<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use App\Models\Teacher;
use App\Models\WritingType;
use App\Models\School;
use App\Models\Lesson;
use App\Models\LessonLog;
use App\Models\Post;
use App\Models\PostRate;
use App\Models\Comment;
use App\Models\Mark;
use Carbon\Carbon;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use \Auth;
use \Storage;
use EndaEditor;

class HomeController extends Controller
{
    public function index(Request $request)
    {   
        $middir = "/posts/" . $this->getSchoolCode() . "/";
        $id = auth()->guard("teacher")->id();
        $teacher = Teacher::find($id);
        $writingTypes = WritingType::all();
        
        $carbonNow = Carbon::now('Asia/Shanghai');
        $selectedWritingDate = $carbonNow->year . $this->addZero($carbonNow->month) . $this->addZero($carbonNow->day);
        $selectedWritingTypesId = 1;
        

        if ($request->session()->has('writingTypesId')) {
            $selectedWritingTypesId = $request->session()->get('writingTypesId');
        }

        if ($request->session()->has('writingDate')) {
            $selectedWritingDate = $request->session()->get('writingDate');
        }
        
        $post = Post::where(['writing_types_id' => $selectedWritingTypesId, 'teachers_id' => $id, "writing_date" => $selectedWritingDate])->first();
        if ($post) {
          $post->storage_name = env('APP_URL'). $middir .$post->storage_name;
        };
        $writingDates = [];


        $carbonNow = Carbon::now('Asia/Shanghai');
        $carbonNow = $carbonNow->subDays(2);
        $tWritingDate = $carbonNow->year . $this->addZero($carbonNow->month) . $this->addZero($carbonNow->day);
        $selected = ($selectedWritingDate == $tWritingDate)?"selected":"";
        $writingDates[] = ["label" => "前天 - ".$carbonNow->month . "月" . $carbonNow->day,
                          "value" => $tWritingDate,
                            "selected" => $selected
                          ];


        $carbonNow = Carbon::now('Asia/Shanghai');
        $carbonNow = $carbonNow->subDay();
        $tWritingDate = $carbonNow->year . $this->addZero($carbonNow->month) . $this->addZero($carbonNow->day);
        $selected = ($selectedWritingDate == $tWritingDate)?"selected":"";
        $writingDates[] = ["label" => "昨天 - ".$carbonNow->month . "月" . $carbonNow->day,
                          "value" => $tWritingDate,
                            "selected" => $selected
                          ];


        $carbonNow = Carbon::now('Asia/Shanghai');
        $tWritingDate = $carbonNow->year . $this->addZero($carbonNow->month) . $this->addZero($carbonNow->day);
        $selected = ($selectedWritingDate == $tWritingDate)?"selected":"";
        $writingDates[] = ["label" => "今天 - ".$carbonNow->month . "月" . $carbonNow->day,
                  "value" => $tWritingDate,
                  "selected" => $selected
                ];

        $carbonNow = Carbon::now('Asia/Shanghai');
        $carbonNow = $carbonNow->addDay();
        $tWritingDate = $carbonNow->year . $this->addZero($carbonNow->month) . $this->addZero($carbonNow->day);
        $selected = ($selectedWritingDate == $tWritingDate)?"selected":"";
        $writingDates[] = ["label" => "明天 - ".$carbonNow->month . "月" . $carbonNow->day,
                          "value" => $tWritingDate,
                            "selected" => $selected
                          ];

        $carbonNow = Carbon::now('Asia/Shanghai');
        $carbonNow = $carbonNow->addDays(2);
        $tWritingDate = $carbonNow->year . $this->addZero($carbonNow->month) . $this->addZero($carbonNow->day);
        $selected = ($selectedWritingDate == $tWritingDate)?"selected":"";
        $writingDates[] = ["label" => "后天 - ".$carbonNow->month . "月" . $carbonNow->day,
                          "value" => $tWritingDate,
                            "selected" => $selected
                          ];

        return view('teacher/home', compact('writingTypes', 'post', 'writingDates', 'selectedWritingTypesId', 'selectedWritingDate'));
    }

    public function addZero($str) 
    {
      if (1 == strlen($str)) {
        return "0" . $str;
      } else {
        return $str;
      }
    }

    public function removeZero($str) 
    {
      if (1 == strlen($str)) {
        return "0" . $str;
      } else {
        return $str;
      }
    }

    public function upload(Request $request)
    {
      $id = auth()->guard("teacher")->id();
      $teacher = Teacher::find($id);
      $file = $request->file('source');
      // $redirectUrl = ($request->input('url'))?("/" . $request->input('url')):"";
      if(!$file) {
        return Redirect::to('teacher')->with('danger', '请重新选择作业提交！');
      }

      $teachersId = Auth::guard("teacher")->id();
      
      $writingTypesId = $request->get('writing_types_id');
      $writingDate = $request->get('writing_date');

      $request->session()->put('writingTypesId', $writingTypesId);
      $request->session()->put('writingDate', $writingDate);
            
      $tWriteDate = substr($writingDate, 4, 2) . "月" . substr($writingDate, 6, 2) . "日";

      $oldPost = Post::where(['writing_types_id' => $writingTypesId, 'teachers_id' => $teachersId, "writing_date" => $writingDate])->orderBy('id', 'desc')->first();

      if ($file->isValid()) {
        // 原文件名
        $originalName = $file->getClientOriginalName();
        // $bytes = File::size($filename);
        // 扩展名
        $ext = $file->getClientOriginalExtension();
        // $originalName = str_replace($originalName, ".".$ext);
        // MimeType
        $type = $file->getClientMimeType();
        // dd($originalName);
        // 临时绝对路径
        $realPath = $file->getRealPath();

        $uniqid = uniqid();
        $filename = $teacher->username . '-' . $uniqid . '.' . $ext;
        $img = \Image::make($realPath);
        $img->orientate();
        // $image = imagecreatefromstring(file_get_contents($realPath));
        // $exif = @exif_read_data($realPath, 'IFD0');
        // if(!empty($exif['Orientation'])) {
        //     switch($exif['Orientation']) {
        //         case 8:
        //             $image = imagerotate($image,90,0);
        //             break;
        //         case 3:
        //             $image = imagerotate($image,180,0);
        //             break;
        //         case 6:
        //             $image = imagerotate($image,-90,0);
        //             break;
        //     }
        // }
        try {
          $img->save(public_path(config('definitions.images_path') . "/" . $this->getSchoolCode() . "/" . $filename));
        } catch (\Exception $e) {
             $e->getMessage();
            return Redirect::to('teacher')->with('danger', '操作失败，请稍后重试或联系技术支持！');
             //TODO remove once tested
             // return false;
         }
        // $bool = Storage::disk($this->getSchoolCode() . 'posts')->put($filename, $img); 
        //TDDO update these new or update code
        if ($img) {
          if($oldPost) {
            $oldFilename = $oldPost->storage_name;
            $oldPost->storage_name = $filename;
            $oldPost->original_name = $originalName;
            $oldPost->file_ext = $ext;
            $oldPost->mime_type = $type;
            $oldPost->post_code = $uniqid;
            $oldPost->writing_date = $writingDate;
            if ($oldPost->update()) {
              $bool = Storage::disk($this->getSchoolCode() . 'posts')->delete($oldFilename); 

              // Session::flash('success', '打卡成功！'); 
              // return Redirect::to('teacher');
              return Redirect::to('teacher')->with('success', $tWriteDate . '，打卡成功！');
            } else {
              return Redirect::to('teacher')->with('danger', '打卡失败，请重新操作！');
              // Session::flash('error', '作业提交失败'); 
            }
          } else {
            $post = new Post();
            $post->teachers_id = $teachersId;
            $post->writing_types_id = $writingTypesId;
            $post->storage_name = $filename;
            $post->original_name = $originalName;
            $post->file_ext = $ext;
            $post->mime_type = $type;
            $post->post_code = $uniqid;
            $post->writing_date = $writingDate;
            if ($post->save()) {
              // Session::flash('success', '打卡成功！'); 
              // return Redirect::to('teacher');
              return Redirect::to('teacher')->with('success', $tWriteDate . '，打卡成功！');
            } else {
              return Redirect::to('teacher')->with('danger', '打卡失败，请重新操作！');
              // Session::flash('error', '作业提交失败'); 
            }
          }
        }
        
      } else {
        return Redirect::to('teacher')->with('danger', '文件上传失败，请确认是否文件过大？');
      }
    }

    public function getReset()
    {
        return view('teacher.login.reset');
    }

    public function postReset(Request $request)
    {
        $oldpassword = $request->input('oldpassword');
        $password = $request->input('password');
        $data = $request->all();
        $rules = [
            'oldpassword'=>'required|between:6,20',
            'password'=>'required|between:6,20|confirmed',
        ];
        $messages = [
            'required' => '密码不能为空',
            'between' => '密码必须是6~20位之间',
            'confirmed' => '新密码和确认密码不匹配'
        ];
        $validator = Validator::make($data, $rules, $messages);
        $user = Auth::guard("teacher")->user();
        $validator->after(function($validator) use ($oldpassword, $user) {
            if (!\Hash::check($oldpassword, $user->password)) {
                $validator->errors()->add('oldpassword', '原密码错误');
            }
        });
        if ($validator->fails()) {
            return back()->withErrors($validator);  //返回一次性错误
        }
        $user->password = bcrypt($password);
        $user->save();
        Auth::guard("teacher")->logout();  //更改完这次密码后，退出这个用户
        return redirect('/teacher/login');
    }

    public function getCommentByPostsId(Request $request)
    {
        $comment = Comment::where(['posts_id' => $request->get('posts_id')])->first();

        if (isset($comment)) {
            return json_encode($comment);
        } else {
            return "false";
        }
    }

    public function getPostRate(Request $request)
    {
        $postRate = PostRate::where(['posts_id' => $request->input('posts_id')])->first();

        if (isset($postRate)) {
            return $postRate['rate'];
        } else {
            return "false";
        }
    }

    public function getTeacherInfo()
    {
        $userId = auth()->guard('teacher')->id();
        $teacher = Teacher::select('teachers.*', 'terms.grade_key', 'sclasses.class_title', 'schools.title', 'districts.title as district_title')
                ->leftJoin('sclasses', 'sclasses.id', '=', "teachers.sclasses_id")
                ->leftJoin('schools', 'schools.id', '=', "sclasses.schools_id")
                ->leftJoin('districts', 'districts.id', '=', "schools.districts_id")
                ->leftJoin('terms', 'terms.enter_school_year', '=', "sclasses.enter_school_year")
                ->where(['teachers.id' => $userId, 'terms.is_current' => 1])
                ->first();
        $posts = Post::where(['posts.teachers_id' => $userId])->get();
        $postNum = count($posts);
        $rateYouNum = 0;
        $rateLiangNum = 0;
        $rateHegeNum = 0;
        $rateBuhegeNum = 0;
        $rateWeipingNum = 0;
        $commentNum = 0;
        $markNum = 0;
        
        $allLessonLogNum = LessonLog::where(['lesson_logs.sclasses_id' => $teacher->sclasses_id])->count();
        $unPostNum = $allLessonLogNum - $postNum;
        foreach ($posts as $key => $post) {
          $post_rate = PostRate::where(['post_rates.posts_id' => $post->id])->first();
          if (!$post_rate) {
            $rateWeipingNum++;
          } else if ("优+" == $post_rate->rate) {
            $rateYouJiaNum++;
          } else if ("优" == $post_rate->rate) {
            $rateYouNum++;
          }else if ("待完" == $post_rate->rate) {
            $rateDaiWanNum++;
          }
          $comment = Comment::where(['comments.posts_id' => $post->id])->first();
          if ($comment) {
            $commentNum++;
          }
          $mark = Mark::where(['marks.posts_id' => $post->id, 'marks.state_code' => 1])->count();
          $markNum += $mark;
        }
        $markOthersNum = Mark::where(['marks.teachers_id' => $userId])->count();
        return view('teacher/login/info', compact('teacher', 'postNum', 'rateYouJiaNum', 'rateYouNum', 'rateDaiWanNum', 'commentNum', 'markNum', 'markOthersNum', 'rateWeipingNum', 'unPostNum', 'allLessonLogNum'));
    }

    public function getOnePost(Request $request)
    {
        $userId = auth()->guard('teacher')->id();
        $request->session()->put('writingTypesId', $request->input('writing_types_id'));
        $request->session()->put('writingDate', $request->input('writing_date'));

        $middir = "/posts/" . $this->getSchoolCode() . "/";
        $imgTypes = ['jpg', 'jpeg', 'bmp', 'gif', 'png'];
        $post = Post::leftjoin('teachers', 'teachers.id', '=', "posts.teachers_id")
                ->where("posts.writing_date", "=", $request->input('writing_date'))
                ->where("posts.writing_types_id", "=", $request->input('writing_types_id'))
                ->where("teachers.id", "=", $userId)
                ->first();
                // return var_dump($post);
        if (isset($post)) {
            return ["filetype"=>"img", 
                    "storage_name" => getThumbnail($post['storage_name'], 300, 500, $this->getSchoolCode(), 'fit', $post['file_ext']), ];
        } else {
            return "false";
        }
    }

    public function getMarkNumByPostsId(Request $request)
    {
        $marks = Mark::where(["posts_id" => $request->input('postsId'), "state_code" => 1])->get();

        if (isset($marks)) {
            return count($marks);
        } else {
            return 0;
        }
    }

    public function getIsMarkedByMyself(Request $request)
    {
      $teachersId = Auth::guard("teacher")->id();
      $postsId =  $request->input('postsId');
      $mark = Mark::where(["posts_id" => $postsId, "teachers_id" => $teachersId, "state_code" => 1])->first();

        if (isset($mark)) {
            return "true";
        } else {
            return "false";
        }
    }

    public function updateMarkState(Request $request)
    {
        $teachersId = Auth::guard("teacher")->id();
        $postsId =  $request->input('postsId');
        $stateCode =  $request->input('stateCode');
        
        $mark = Mark::where(["posts_id" => $postsId, "teachers_id" => $teachersId])->first();
        // dd($mark);
        if (isset($mark)) {
          $mark->state_code = $stateCode;
          if ($mark->save()) {
            return "true";
          }
        } else {
          $mark = new Mark();
          $mark->posts_id = $postsId;
          $mark->teachers_id = $teachersId;
          $mark->state_code = $stateCode;
          if ($mark->save()) {
            return "true";
          }
        }
    }

    public function getSchoolCode()
    {
      $teacher = Teacher::find(Auth::guard("teacher")->id());

      $school = School::find($teacher->schools_id);
      return $school->code;
    }
}
