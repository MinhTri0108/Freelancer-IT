<?php

namespace App\Http\Controllers;

use App\Bidding;
use App\Education;
use App\Experience;
use App\Fee;
use App\Notifications\ProjectNotification;
use App\Project;
use App\User;
use App\PayRange;
use App\Qualification;
use App\Rating;
use App\ResetPassword;
use App\Skill;
use App\TrHistory;
use App\UploadFile;
use App\WithdrawReq;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Request as IlluminateRequest;
use Illuminate\Support\Str;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use App\PayPalClient;
use PayPalCheckoutSdk\Orders\OrdersAuthorizeRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use stdClass;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function sendEmail($data, $to, $subject)
    {
        Mail::send('mail', $data, function ($message) use ($to, $subject) {
            $message->from(env('MAIL_FROM_ADDRESS', ''), 'FreelancerIT.com');
            $message->to($to->email, $to->name)->subject($subject);
        });
    }

    public function getResendEmail()
    {
        return view('resendemail');
    }

    public function postResendEmail(Request $req)
    {
        switch ($req->emailType) {
            case 'resetpw':
                return $this->postForgotPass($req);

            default:
                # code...
                break;
        }
    }

    public function showMaster()
    {
        return view('master');
    }

    public function getLogin()
    {
        return view('login');
    }

    public function postLogin(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = array('email' => $req->email, 'password' => $req->password);
        if (Auth::attempt($credentials, $req->has('remember'))) {
            return redirect()->route('daskboard');
        } else
            return redirect()->route('login')->with('message', 'Tên đăng nhập hoặc mật khẩu không chính xác');
    }

    public function getLogout()
    {
        Auth::logout();
        return redirect()->route('master');
    }

    public function getRegister()
    {
        return view('register');
    }

    public function postRegister(Request $req)
    {
        $req->validate([
            'username' => 'required|min:10|max:50|unique:users,username',
            'lastname' => 'required|max:45',
            'firstname' => 'required|max:45',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'repassword' => 'required|same:password'
        ], [
            'username.min' => 'Tên người dùng ít nhất 10 ký tự',
            'username.unique' => 'Tên người dùng đã tồn tại',
            'required' => 'Trường này không được để trống',
            'email.email' => 'Định dạng email không hợp lệ',
            'email.unique' => 'Email đã tồn tại trên hệ thống',
            'password.min' => 'Mật khẩu ít nhất 8 ký tự',
            'repassword.same' => 'Mật khẩu xác nhận không giống',
        ]);

        try {
            $user = new User();
            // $strprovider = Str::random(64);
            $user->username = $req->username;
            $user->last_name = $req->lastname;
            $user->first_name = $req->firstname;
            $user->email = $req->email;
            $user->password = bcrypt($req->password);
            $user->save();
            return view('login');
        } catch (Exception $e) {
            return redirect()->view('register')->with();
        }
    }

    public function getForgotPass()
    {
        return view('forgotpass');
    }

    public function postForgotPass(Request $req)
    {
        $hasEmail = User::where('email', $req->email)->first();
        if ($hasEmail) {
            $resetPassword = ResetPassword::firstOrCreate(['email' => $req->email, 'token' => Str::random(60)]);

            $token = ResetPassword::where('email', $req->email)->first();

            $link = url('resetpassword') . "/" . $token->token; //send it to email
            $linkName = 'Đặt lại mật khẩu';
            $content = 'Truy cập đường dẫn phía dưới để đặt lại mật khẩu, nếu bạn không phải người gửi yêu cầu đặt lại mật khẩu thì hãy bỏ qua mail này';
            $title = $linkName;
            // $toArray = ['email' => $req->email, 'name' => $hasEmail->last_name + ' ' + $hasEmail->first_name];

            $data = array('link' => $link, 'linkName' => $linkName, 'content' => $content, 'title' => $title);

            $to = new stdClass();
            $to->email = $req->email;
            $to->name = $hasEmail->last_name . ' ' . $hasEmail->first_name;

            $this->sendEmail($data, $to, $title);
            return redirect()->route('resendemail')->with('email', $req->email)->with('emailType', 'resetpw');
        } else {
            // echo 'Email không có trong hệ thống, vui lòng kiểm tra lại';
            return redirect()->route('forgotpass')->with('message', 'Email không có trong hệ thống, vui lòng kiểm tra lại');
        }
    }

    public function getResetPassword($token)
    {
        $data  = ResetPassword::where('token', $token)->first();
        if ($data) {
            return view('resetpass', compact('data'));
        } else {
            echo 'Link đã hết hạn';
        }
    }

    public function postResetPassword(Request $req)
    {
        $user = User::where('email', $req->email)->first();
        $user->password = Hash::make($req->password);
        $user->save();
        return redirect()->view('login');
    }

    public function getProfile($id)
    {
        $user = User::where('id', $id)->first();
        $mypj_posted = Project::where('user_id', $id)->count();
        $mypj_completed = Project::where([['user_id', '=', $id], ['state', '=', 'completed']])->count();
        $mypj_inprogress = Project::where([['user_id', '=', $id], ['state', '=', 'in progress']])->count();
        $parpj_completed = Project::where([['freelancer_id', '=', $id], ['state', '=', 'completed']])->count();
        $parpj_inprogress = Project::where([['freelancer_id', '=', $id], ['state', '=', 'in progress']])->count();
        $skills_arr = array_map('trim', explode(',', $user->skills));
        $user->skills = Skill::whereIn('id', $skills_arr)->get();
        $skills_list = Skill::all();
        $edus = Education::where('user_id', $id)->orderBy('created_at', 'desc')->get();
        $exps = Experience::where('user_id', $id)->orderBy('created_at', 'desc')->get();
        $qualifes = Qualification::where('user_id', $id)->orderBy('created_at', 'desc')->get();
        $rating = Rating::selectRaw('count(*) as rated_count, avg(points) as avg_point, rating_user')->where('rating_user', '=', $id)->groupBy('rating_user')->first();
        foreach ($exps as $exp) {
            $stat_exp = array_map('trim', explode('-', $exp->start_at));
            $end_exp = array_map('trim', explode('-', $exp->end_at));
            $exp->start_at = $stat_exp;
            $exp->end_at = $end_exp;
        }
        return view('profile', compact('user', 'skills_list', 'skills_arr', 'edus', 'exps', 'mypj_posted', 'mypj_completed', 'mypj_inprogress', 'parpj_completed', 'parpj_inprogress', 'qualifes', 'rating'));
    }

    public function updateProfile(Request $req, $id)
    {
        switch ($req->ud_type) {
            case "udInfo":
                if ($this->updateInfo($req, $id)) {
                    return redirect()->route('profile', $id);
                }
                break;
            case "udSkill":
                if ($this->updateSkill($req, $id)) {
                    return redirect()->route('profile', $id);
                }
                break;
        }
    }

    public function updateInfo(Request $req, $id)
    {
        $update = User::where('id', $id)->update(['introduce' => $req->introduce, 'current_job' => $req->current_job]);
        if ($update)
            return true;
    }

    public function updateSkill(Request $req, $id)
    {
        $skills_str = implode(',', $req->skill);
        $update = User::where('id', $id)->update(['skills' => $skills_str]);
        if ($update)
            return true;
    }

    public function postEducation(Request $req)
    {
        if ($req->edu_type == "add") {
            $now = new DateTime();
            $currenttime = $now->getTimestamp();
            $edu = new Education();
            $edu->user_id = Auth::user()->id;
            $edu->school_name = $req->school_name;
            $edu->degree = $req->degree;
            $edu->start_year = $req->start_year;
            $edu->end_year = $req->end_year;
            $edu->created_at = $currenttime;
            $edu->updated_at = $currenttime;
            $edu->save();
            return redirect()->route('profile', Auth::user()->id);
        }
        if ($req->edu_type == "edit") {
            $now = new DateTime();
            $currenttime = $now->getTimestamp();
            $edu = Education::where('id', $req->edu_id)->first();
            $edu->school_name = $req->ud_school_name;
            $edu->degree = $req->ud_degree;
            $edu->start_year = $req->from_year;
            $edu->end_year = $req->to_year;
            $edu->updated_at = $currenttime;
            $edu->save();
            return redirect()->route('profile', Auth::user()->id);
        }
    }

    public function delEducation($id)
    {
        if (Auth::check()) {
            Education::where([['user_id', '=', Auth::user()->id], ['id', '=', $id]])->delete();
            return redirect()->route('profile', Auth::user()->id);
        }
    }

    public function postAvatar(Request $req)
    {
        $req->validate([
            'avatar' => 'required|image|max:1024'
        ]);
        $uploadPath = 'images/avatar';
        $input = 'avatar';
        $upload = new UploadFile();
        $filename = $upload->uploadFile($req, $input, $uploadPath);
        if ($filename != null) {
            $user = User::where('id', Auth::user()->id)->first();
            $old_avatar = $user->avatar;
            if ($old_avatar != "anonymous.jpg") {
                $upload->removeFile($old_avatar, $uploadPath);
            }
            $user->avatar = $filename;
            $user->save();
            return redirect()->route('profile', Auth::user()->id);
        }
    }

    public function postExperience(Request $req)
    {
        if ($req->exp_type == "add") {
            $now = new DateTime();
            $currenttime = $now->getTimestamp();
            $exp = new Experience();
            $exp->user_id = Auth::user()->id;
            $exp->position = $req->position;
            $exp->company = $req->company;
            $exp->start_at = $req->start_at;
            $exp->end_at = $req->end_at;
            if ($req->cur_working) {
                $exp->cur_working = 1;
            } else {
                $exp->cur_working = 0;
            }
            $exp->created_at = $currenttime;
            $exp->updated_at = $currenttime;
            $exp->save();
            return redirect()->route('profile', Auth::user()->id);
        }
        if ($req->exp_type == "edit") {
            $now = new DateTime();
            $currenttime = $now->getTimestamp();
            $exp = Experience::where('id', $req->exp_id)->first();
            $exp->position = $req->ud_position;
            $exp->company = $req->ud_company;
            $exp->start_at = $req->ud_start_at;
            $exp->end_at = $req->ud_end_at;
            if ($req->ud_cur_working) {
                $exp->cur_working = 1;
            } else {
                $exp->cur_working = 0;
            }
            $exp->updated_at = $currenttime;
            $exp->save();
            return redirect()->route('profile', Auth::user()->id);
        }
    }

    public function delExperience($id)
    {
        if (Auth::check()) {
            Experience::where([['user_id', '=', Auth::user()->id], ['id', '=', $id]])->delete();
            return redirect()->route('profile', Auth::user()->id);
        }
    }

    public function postQualification(Request $req)
    {
        if ($req->qualif_type == "add") {
            $now = new DateTime();
            $currenttime = $now->getTimestamp();
            $qualif = new Qualification();
            $qualif->user_id = Auth::user()->id;
            $qualif->qualif_name = $req->qualif_name;
            $qualif->organization = $req->organization;
            $qualif->got_at = $req->got_at;
            $qualif->created_at = $currenttime;
            $qualif->updated_at = $currenttime;
            $qualif->save();
            return redirect()->route('profile', Auth::user()->id);
        }
        if ($req->qualif_type == "edit") {
            $now = new DateTime();
            $currenttime = $now->getTimestamp();
            $qualif = Qualification::where('id', $req->qualif_id)->first();
            $qualif->qualif_name = $req->ud_qualif_name;
            $qualif->organization = $req->ud_organization;
            $qualif->got_at = $req->ud_got_at;
            $qualif->updated_at = $currenttime;
            $qualif->save();
            return redirect()->route('profile', Auth::user()->id);
        }
    }

    public function delQualification($id)
    {
        if (Auth::check()) {
            Qualification::where([['user_id', '=', Auth::user()->id], ['id', '=', $id]])->delete();
            return redirect()->route('profile', Auth::user()->id);
        }
    }

    public function getMyProject(Request $req)
    {
        if (Auth::check()) {
            $now = new DateTime();
            $currenttime = $now->getTimestamp();
            $user_id = Auth::user()->id;

            $projects_e = Project::leftJoin('bidding', 'projects.id', 'project_id')
                ->selectRaw('projects.id, projects.user_id, name, description, state, ifnull(round(avg(bid_amount),2),0) as average_bid, count(bidding.user_id) as bid, if((bid_end_at - ?) / 86400<0,0,ceil((bid_end_at - ?) / 86400)) as close_date', [$currenttime, $currenttime])
                ->where([['projects.user_id', '=', $user_id], ['state', '=', 'recruiting']])
                ->groupBy('projects.id')->orderBy('projects.created_at', 'desc')->paginate(10);

            $ubproject_id = Bidding::where('user_id', $user_id)->pluck('project_id');
            $projects = Project::leftJoin('bidding', 'projects.id', 'project_id')
                ->selectRaw('projects.id, projects.user_id, name, state, projects.created_at, ifnull(round(avg(bid_amount),2),0) as average_bid, count(bidding.user_id) as bid, if((bid_end_at - ?) / 86400<0,0,ceil((bid_end_at - ?) / 86400)) as close_date', [$currenttime, $currenttime])
                ->whereIn('projects.id', $ubproject_id)
                ->groupBy('projects.id');
            $projects_f = Bidding::joinSub($projects, 'nproject', function ($joins) {
                $joins->on('bidding.project_id', '=', 'nproject.id');
            })
                ->selectRaw('nproject.name, nproject.id, bid, bid_amount, average_bid, close_date, nproject.created_at, nproject.state')
                ->where([['bidding.user_id', $user_id], ['nproject.state', 'recruiting']])
                ->orderBy('nproject.created_at', 'desc')->paginate(10);

            if ($req->ajax()) {
                switch ($req->type_p) {
                    case "inprogress":
                        $projects_e = Project::join('bidding', 'projects.id', 'project_id')->join('users', 'freelancer_id', 'users.id')
                            ->selectRaw('projects.name, projects.id, freelancer_id, username, bid_amount, state, if((ended_at - ?) / 86400<0,0,ceil((ended_at - ?) / 86400)) as deadline', [$currenttime, $currenttime])
                            ->whereRaw('freelancer_id = bidding.user_id and state = "in progress" and projects.user_id = ?', $user_id)
                            ->orderBy('projects.created_at', 'desc')->paginate(10);
                        return view('inprogress', compact('projects_e'));
                        break;

                    case "completed":
                        $projects_e = Project::join('bidding', 'projects.id', 'project_id')->join('users', 'freelancer_id', 'users.id')
                            ->selectRaw('projects.name, projects.id, freelancer_id, username, bid_amount, state')
                            ->whereRaw('freelancer_id = bidding.user_id and state = "completed" and projects.user_id = ?', $user_id)
                            ->orderBy('projects.created_at', 'desc')->paginate(10);
                        return view('completed', compact('projects_e'));
                        break;

                    case "recruitingf":
                        $ubproject_id = Bidding::where('user_id', $user_id)->pluck('project_id');
                        $projects = Project::leftJoin('bidding', 'projects.id', 'project_id')
                            ->selectRaw('projects.id, projects.user_id, name, state, projects.created_at, ifnull(round(avg(bid_amount),2),0) as average_bid, count(bidding.user_id) as bid, if((bid_end_at - ?) / 86400<0,0,ceil((bid_end_at - ?) / 86400)) as close_date', [$currenttime, $currenttime])
                            ->whereIn('projects.id', $ubproject_id)
                            ->groupBy('projects.id');
                        $projects_f = Bidding::joinSub($projects, 'nproject', function ($joins) {
                            $joins->on('bidding.project_id', '=', 'nproject.id');
                        })
                            ->selectRaw('nproject.name, nproject.id, bid, bid_amount, average_bid, close_date, nproject.created_at, nproject.state')
                            ->where([['bidding.user_id', $user_id], ['nproject.state', 'recruiting']])
                            ->orderBy('nproject.created_at', 'desc')->paginate(10);
                        return view('recruitingf', compact('projects_f'));
                        break;

                    case "inprogressf":
                        $projects_f = Project::join('bidding', 'projects.id', 'project_id')->join('users', 'projects.user_id', 'users.id')
                            ->selectRaw('projects.name, projects.id, projects.user_id, username, bid_amount, state, if((ended_at - ?) / 86400<0,0,ceil((ended_at - ?) / 86400)) as deadline', [$currenttime, $currenttime])
                            ->whereRaw('freelancer_id = bidding.user_id and state = "in progress" and freelancer_id = ?', $user_id)
                            ->orderBy('projects.created_at', 'desc')->paginate(10);
                        return view('inprogressf', compact('projects_f'));
                        break;

                    case "completedf":
                        $projects_f = Project::join('bidding', 'projects.id', 'project_id')->join('users', 'projects.user_id', 'users.id')
                            ->selectRaw('projects.name, projects.id, projects.user_id, username, bid_amount, state')
                            ->whereRaw('freelancer_id = bidding.user_id and state = "completed" and freelancer_id = ?', $user_id)
                            ->orderBy('projects.created_at', 'desc')->paginate(10);
                        return view('completedf', compact('projects_f'));
                        break;

                    default:
                        $projects_e = Project::leftJoin('bidding', 'projects.id', 'project_id')
                            ->selectRaw('projects.id, projects.user_id, name, description, state, ifnull(round(avg(bid_amount),2),0) as average_bid, count(bidding.user_id) as bid, if((bid_end_at - ?) / 86400<0,0,ceil((bid_end_at - ?) / 86400)) as close_date', [$currenttime, $currenttime])
                            ->where([['projects.user_id', '=', $user_id], ['state', '=', $req->type_p]])
                            ->groupBy('projects.id')->orderBy('projects.created_at', 'desc')->paginate(10);
                        return view('recruiting', compact('projects_e'));
                }
            }
            return view('myprojects', compact('projects_e', 'projects_f'));
        }
    }

    public function getDaskboard()
    {
        if (Auth::check()) {
            $now = new DateTime();
            $currenttime = $now->getTimestamp();
            $user_id = Auth::user()->id;
            if (Auth::user()->is_freelancer == 1) {
                $projects = Project::leftJoin('bidding', 'projects.id', 'project_id')
                    ->selectRaw('projects.id, projects.user_id, name, description, state, ifnull(round(avg(bid_amount),2),0) as average_bid, count(bidding.user_id) as bid, if((bid_end_at - ?) / 86400<0,0,ceil((bid_end_at - ?) / 86400)) as close_date', [$currenttime, $currenttime])
                    ->where('freelancer_id', $user_id)
                    ->groupBy('projects.id')->orderBy('projects.created_at', 'desc')->take(5)->get();
            } else {
                $projects = Project::leftJoin('bidding', 'projects.id', 'project_id')
                    ->selectRaw('projects.id, projects.user_id, name, description, state, ifnull(round(avg(bid_amount),2),0) as average_bid, count(bidding.user_id) as bid, if((bid_end_at - ?) / 86400<0,0,ceil((bid_end_at - ?) / 86400)) as close_date', [$currenttime, $currenttime])
                    ->where('projects.user_id', $user_id)
                    ->groupBy('projects.id')->orderBy('projects.created_at', 'desc')->take(5)->get();
            }
            return view('daskboard', compact('projects'));
        }
    }

    public function getAllProject()
    {
        if (Auth::check()) {
            $now = new DateTime();
            $currenttime = $now->getTimestamp();
            $user_id = Auth::user()->id;
            $projects = Project::leftJoin('bidding', 'projects.id', 'project_id')
                ->selectRaw('projects.id, projects.user_id, name, description, state, ifnull(avg(bid_amount),0) as average_bid, count(bidding.user_id) as bid, if((bid_end_at - ?) / 86400<0,0,ceil((bid_end_at - ?) / 86400)) as close_date', [$currenttime, $currenttime])
                ->groupBy('projects.id')->orderBy('projects.created_at', 'desc')->paginate(10);
            return view('allproject', compact('projects'));
        }
    }

    public function getPostProject()
    {
        if (Auth::check()) {
            $skills_list = Skill::all();
            $fixed_prices = PayRange::orderBy('from', 'asc')->get();
            return view('postproject', compact('fixed_prices', 'skills_list'));
        }
    }

    public function postProject(Request $req)
    {
        $req->validate([
            'fileupload' => 'max:10240'
        ]);

        $now = new DateTime();
        $currenttime = $now->getTimestamp();
        $skills_required = implode(',', $req->skills);
        $project = new Project();
        $project->user_id = Auth::user()->id;
        $project->name = $req->projectname;
        $project->description = $req->description;
        $project->skills_required = $skills_required;
        if (!is_null($req->skills)) {
            foreach ($req->skills as $skill) {
                if ($skill != null) {
                    Skill::where('id', $skill)->increment('jobs', 1);
                }
            }
        }

        if ($req->fixedprice == 'customPrice') {
            $project->pay_range = $req->customFrom . '_' . $req->customTo;
        } else {
            $project->pay_range = $req->fixedprice;
        }

        $project->created_at = $currenttime;
        $project->updated_at = $currenttime;
        $project->bid_end_at = $currenttime + (7 * 24 * 60 * 60);

        $input = 'fileupload';

        if ($req->hasFile($input)) {
            $upload_path = 'documents';
            $originalFileName = $req->file($input)->getClientOriginalName();
            if (trim(pathinfo($originalFileName, PATHINFO_FILENAME)) !== '') {
                $upload = new UploadFile();
                $filename = $upload->uploadFile($req, $input, $upload_path);
                if ($filename != null) {
                    $new_path = 'documents/' . $filename;
                    $file_arr = array('name' => $originalFileName, 'path' => $new_path);
                    $file_json = json_encode($file_arr);
                    $project->file_uploaded = $file_json;
                }
            }
        }

        $project->save();
        $toid = $project->user_id;
        $data = ['type' => 'newproject', 'project_name' => $project->name, 'project_id' => $project->id];
        $this->storeNotif($data, $toid);
        return redirect()->route('myproject');
    }

    public function listSkills()
    {
        return Skill::all();
    }

    public function getProjectDetail($id)
    {
        $had_bidded = Bidding::where([
            ['project_id', '=', $id],
            ['user_id', '=', Auth::user()->id]
        ])->count();
        $fee = Fee::first();
        $rating = Rating::where([['p_id', '=', $id], ['rating_user', '=', Auth::user()->id]])->first();
        $project = Project::leftJoin('bidding', 'projects.id', 'project_id')->leftJoin('users', 'projects.user_id', 'users.id')
            ->selectRaw('projects.id, projects.user_id, username, name, description, pay_range, skills_required, ifnull(round(avg(bid_amount),2),0) as average_bid, count(bidding.user_id) as bid, state, freelancer_id, bid_end_at, ended_at, file_uploaded')
            ->where('projects.id', $id)->groupBy('projects.id')->first();
        $price_array = array_map('trim', explode('_', $project->pay_range));
        if ($price_array[1] == '0') {
            $project->pay_range = $price_array[0] . '+ VND';
        } else {
            $project->pay_range = $price_array[0] . ' - ' . $price_array[1] . ' VND';
        }
        $skills = array_map('trim', explode(',', $project->skills_required));
        $project->skills_required = Skill::select('skillname')->whereIn('id', $skills)->get();
        if (!is_null($project->file_uploaded)) {
            $project->file_uploaded = json_decode($project->file_uploaded);
        }
        $avg_price = collect($price_array)->avg();
        if (Auth::user()->id == $project->user_id) {
            if (is_null($project->freelancer_id)) {
                $biddings = Bidding::join('users', 'user_id', 'users.id')
                    ->select('username', 'proposal', 'bid_amount', 'bidding.id', 'avatar', 'email')
                    ->where('project_id', $id)->paginate(10);
            } else {
                $now = new DateTime();
                $currenttime = $now->getTimestamp();
                if (($project->ended_at - $currenttime) < 0) {
                    $project->ended_at = 0;
                } else {
                    $deadline = ceil(($project->ended_at - $currenttime) / 86400);
                    $project->ended_at = $deadline;
                }
                $biddings = Bidding::join('users', 'user_id', 'users.id')
                    ->select('username', 'proposal', 'bid_amount', 'bidding.id', 'milestones', 'avatar', 'email', 'user_id')
                    ->where([
                        ['project_id', '=', $id],
                        ['user_id', '=', $project->freelancer_id]
                    ])->first();
                $milestones = json_decode($biddings->milestones);
                $biddings->milestones = $milestones;
            }
            return view('projectdetail', compact('project', 'price_array', 'biddings', 'fee', 'rating'));
        } else {
            if ($had_bidded == 0) {
                $now = new DateTime();
                $currenttime = $now->getTimestamp();
                $is_closed = false;
                if ((($project->bid_end_at - $currenttime) / 86400) < 0)
                    $is_closed = true;
                return view('projectdetail', compact('project', 'avg_price', 'price_array', 'had_bidded', 'is_closed', 'fee', 'rating'));
            } else {
                $now = new DateTime();
                $currenttime = $now->getTimestamp();
                if (($project->ended_at - $currenttime) < 0) {
                    $project->ended_at = 0;
                } else {
                    $deadline = ceil(($project->ended_at - $currenttime) / 86400);
                    $project->ended_at = $deadline;
                }
                $biddings = Bidding::join('users', 'user_id', 'users.id')
                    ->select('username', 'proposal', 'bid_amount', 'bidding.id', 'milestones', 'period', 'avatar', 'email', 'user_id')
                    ->where([
                        ['project_id', '=', $id],
                        ['user_id', '=', Auth::user()->id]
                    ])->first();
                $milestones = json_decode($biddings->milestones);
                $biddings->milestones = $milestones;
                return view('projectdetail', compact('project', 'avg_price', 'price_array', 'had_bidded', 'biddings', 'fee', 'rating'));
            }
        }
    }

    public function deleteProject($id)
    {
        $project = Project::where('id', $id)->first();
        $skills_list = explode(',', Project::where('id', $id)->value('skills_required'));
        $del_project = Project::where('id', $id)->delete();
        if ($del_project) {
            foreach ($skills_list as $skill) {
                Skill::where('id', $skill)->decrement('jobs', 1);
            }
            Bidding::where('project_id', $id)->delete();
            if (!is_null($project->file_uploaded)) {
                $project->file_uploaded = json_decode($project->file_uploaded);
                $path = $project->file_uploaded->path;
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
        }
        return redirect()->route('daskboard');
    }

    public function postBidding(Request $req, $id)
    {
        $now = new DateTime();
        $currenttime = $now->getTimestamp();
        $bidding = new Bidding();
        $bidding->project_id = $id;
        $bidding->user_id = Auth::user()->id;
        $bidding->bid_amount = $req->bid_amount;
        $bidding->period = $req->period;
        $bidding->proposal = $req->proposal;
        $bidding->created_at = $currenttime;
        $bidding->updated_at = $currenttime;
        $json_arr = array();
        foreach ($req->milestone_name as $index => $m_name) {
            $json_arr[$index]['id'] = $index + 1;
            $json_arr[$index]['ms_name'] = $m_name;
            $json_arr[$index]['ms_price'] = $req->milestone_price[$index];
            $json_arr[$index]['is_completed'] = false;
            $json_arr[$index]['is_late'] = false;
            $json_arr[$index]['is_paid'] = false;
        }
        $ms_json = json_encode($json_arr);
        $bidding->milestones = $ms_json;
        $bidding->save();
        $project = Project::where('id', $id)->select('name', 'id', 'user_id')->first();
        $freelancer = User::where('id', $bidding->user_id)->select('username', 'id')->first();
        $toid = $project->user_id;
        $data = ['type' => 'bidding', 'freelancer_name' => $freelancer->username, 'freelancer_id' => $freelancer->id, 'project_name' => $project->name, 'project_id' => $project->id];
        $this->storeNotif($data, $toid);
        $this->storeNotif($data, $freelancer->id);
        return redirect()->route('daskboard');
    }

    public function getEditBidding($id)
    {
        $bidding = Bidding::join('users', 'user_id', 'users.id')
            ->select('username', 'proposal', 'bid_amount', 'bidding.id', 'milestones', 'period', 'project_id')
            ->where('bidding.id', $id)->first();
        $project = Project::leftJoin('bidding', 'projects.id', 'project_id')
            ->selectRaw('projects.id, projects.user_id, name, description, pay_range, skills_required, ifnull(round(avg(bid_amount),2),0) as average_bid, count(bidding.user_id) as bid, state, freelancer_id, bid_end_at')
            ->where('projects.id', $bidding->project_id)->groupBy('projects.id')->first();
        $fee = Fee::first();
        $price_array = array_map('trim', explode('_', $project->pay_range));
        $project->pay_range = '$' . $price_array[0] . ' - ' . $price_array[1] . ' USD';
        $skills = array_map('trim', explode(',', $project->skills_required));
        $project->skills_required = Skill::select('skillname')->whereIn('skillname', $skills)->get();
        $avg_price = collect($price_array)->avg();
        $milestones = json_decode($bidding->milestones);
        $bidding->milestones = $milestones;
        return view('editbidding', compact('project', 'avg_price', 'price_array', 'bidding', 'fee'));
    }

    public function postEditBidding(Request $req, $id)
    {
        $now = new DateTime();
        $currenttime = $now->getTimestamp();
        $bidding = Bidding::where('id', $id)->first();
        $bidding->bid_amount = $req->bid_amount;
        $bidding->period = $req->period;
        $bidding->proposal = $req->proposal;
        $bidding->updated_at = $currenttime;
        $json_arr = array();
        foreach ($req->milestone_name as $index => $m_name) {
            $json_arr[$index]['id'] = $index + 1;
            $json_arr[$index]['ms_name'] = $m_name;
            $json_arr[$index]['ms_price'] = $req->milestone_price[$index];
            $json_arr[$index]['is_completed'] = false;
            $json_arr[$index]['is_paid'] = false;
        }
        $ms_json = json_encode($json_arr);
        $bidding->milestones = $ms_json;
        $bidding->save();
        return redirect()->route('projectdetail', $bidding->project_id);
    }

    public function deleteBidding($id)
    {
        Bidding::where('id', $id)->delete();
        return redirect()->route('daskboard');
    }

    public function getMilestone(Request $req)
    {
        if ($req->ajax()) {
            $json_arr = Bidding::where('id', '=', $req->b_id)->value('milestones');
            $milestones = json_decode($json_arr);
            $table_html =  '<table class="table table-borderless">
                                <thead>
                                    <tr>
                                    <th>Tên cột mốc</th>
                                    <th>Giá trị</th>
                                    </tr>
                                </thead>
                                <tbody>';
            foreach ($milestones as $milestone) {
                $table_html .= '<tr>
                                    <td>' . $milestone->ms_name . '</td>
                                    <td>$' . $milestone->ms_price . ' USD</td>
                                </tr>';
            }
            $table_html .= '    </tbody>
                            </table>';
            $bidding = Bidding::where('id', '=', $req->b_id)->first();
            return response()->json(['milestones' => $table_html, 'userid' => $bidding->user_id, 'period' => $bidding->period]);
        } else {
            return response()->json(['notajax' => 'Không phải ajax']);
        }
    }

    public function chooseFreelancer(Request $req)
    {
        $now = new DateTime();
        $currenttime = $now->getTimestamp();
        $endday = $currenttime + $req->period2 * 86400;
        $update = Project::where('id', $req->project_id)
            ->update(['freelancer_id' => $req->freelancer_id, 'updated_at' => $currenttime, 'started_at' => $currenttime, 'ended_at' => $endday, 'state' => 'in progress']);
        if ($update) {
            $project = Project::where('id', $req->project_id)->select('name', 'user_id')->first();
            $freelancer_name = User::where('id', $req->freelancer_id)->value('username');
            $toid = $req->freelancer_id;
            $data = ['type' => 'chosen', 'freelancer_name' => $freelancer_name, 'freelancer_id' => $req->freelancer_id, 'project_name' => $project->name, 'project_id' => $req->project_id];
            $this->storeNotif($data, $toid);
            $this->storeNotif($data, $project->user_id);
            return redirect()->route('projectdetail', $req->project_id);
        }
    }

    public function postPay(Request $req)
    {
        if ($req->ajax()) {
            $succeed = false;
            $now = new DateTime();
            $currenttime = $now->getTimestamp();
            $bidding = Bidding::where('id', '=', $req->bidid)->first();
            $employer_id = Project::where('id', '=', $bidding->project_id)->value('user_id');
            $project = Project::where('id', $bidding->project_id)->select('name', 'id', 'user_id')->first();
            $milestones = collect(json_decode($bidding->milestones));
            $milestone = $milestones->firstWhere('id', $req->msid);
            $cur_balances = User::where('id', $employer_id)->value('balances');
            if ($cur_balances < $milestone->ms_price) {
                $needbalances = true;
                return response()->json(['needbalances' => $needbalances]);
            } else {
                $fee = Fee::first();
                if ($milestone->is_late) {
                    $penalty_price = ($milestone->ms_price * (100 - $fee->penalty_rate)) / 100;
                    $employer = User::where('id', $employer_id)->decrement('balances', $penalty_price);
                    $freelancer = User::where('id', '=', $bidding->user_id)->increment('balances', $penalty_price);
                    $data = [
                        ['of_user' => $employer_id, 'relate_user' => $bidding->user_id, 'type' => "pay", 'amount' => $penalty_price, 'is_in' => 0, 'description' => 'Thanh toán tiền cho cột mốc ' . $milestone->ms_name . ' thuộc dự án ' . $project->name . ', được giảm giá ' . $fee->penalty_rate . '% do freelancer hoàn thành trễ hạn', 'created_at' => $currenttime],
                        ['of_user' => $bidding->user_id, 'relate_user' => $employer_id, 'type' => "paid", 'amount' => $penalty_price, 'is_in' => 1, 'description' => 'Nhận được tiền thanh toán cho cột mốc ' . $milestone->ms_name . ' thuộc dự án ' . $project->name . ', bị trừ ' . $fee->penalty_rate . '% do freelancer hoàn thành trễ hạn', 'created_at' => $currenttime],
                    ];
                    TrHistory::insert($data);
                } else {
                    $employer = User::where('id', $employer_id)->decrement('balances', $milestone->ms_price);
                    $freelancer = User::where('id', '=', $bidding->user_id)->increment('balances', $milestone->ms_price);
                    $data = [
                        ['of_user' => $employer_id, 'relate_user' => $bidding->user_id, 'type' => "pay", 'amount' => $milestone->ms_price, 'is_in' => 0, 'description' => 'Thanh toán tiền cho cột mốc ' . $milestone->ms_name . ' thuộc dự án ' . $project->name, 'created_at' => $currenttime],
                        ['of_user' => $bidding->user_id, 'relate_user' => $employer_id, 'type' => "paid", 'amount' => $milestone->ms_price, 'is_in' => 1, 'description' => 'Nhận được tiền thanh toán cho cột mốc ' . $milestone->ms_name . ' thuộc dự án ' . $project->name, 'created_at' => $currenttime],
                    ];
                    TrHistory::insert($data);
                }
                if ($employer && $freelancer) {
                    $milestone->is_paid = true;
                    $ms_json = json_encode($milestones);
                    $udbidding = Bidding::where('id', '=', $req->bidid)->update(['milestones' => $ms_json]);
                }
                if ($udbidding) {
                    $succeed = true;
                    if ($this->checkCompleteProject($bidding->project_id)) {
                        if ($bidding->bid_amount > $fee->fixed_boundary) {
                            $completed_fee = ($bidding->bid_amount * $fee->fee_rate) / 100;
                        } else {
                            $completed_fee = $fee->fixed_fee;
                        }
                        User::where('id', '=', $bidding->user_id)->decrement('balances', $completed_fee);
                        $data = [
                            ['of_user' => $bidding->user_id, 'relate_user' => 0, 'type' => "pay", 'amount' => $completed_fee, 'is_in' => 0, 'description' => 'Trả phí thực hiện dự án ' . $project->name, 'created_at' => $currenttime],
                        ];
                        TrHistory::insert($data);
                        Project::where('id', '=', $bidding->project_id)->update(['state' => 'completed']);
                    }
                    $toid = $bidding->user_id;
                    $data = ['type' => 'paid', 'ms_name' => $milestone->ms_name, 'project_name' => $project->name, 'project_id' => $project->id];
                    $this->storeNotif($data, $toid);
                }
                return response()->json(['success' => $succeed]);
            }
        } else {
            return response()->json(['notajax' => 'Không phải ajax']);
        }
    }

    public function postComplete(Request $req)
    {
        if ($req->ajax()) {
            $succeed = false;
            $late = false;
            $bidding = Bidding::where('id', '=', $req->bidid)->first();
            // $employer_id = Project::where('id','=',$bidding->project_id)->value('user_id');
            $milestones = collect(json_decode($bidding->milestones));
            $milestone = $milestones->firstWhere('id', $req->msid);
            $deadline = Project::where('id', $bidding->project_id)->value('ended_at');
            $now = new DateTime();
            $currenttime = $now->getTimestamp();
            if (($deadline - $currenttime) < 0) {
                $milestone->is_late = true;
                $late = true;
            }
            $milestone->is_completed = true;
            $ms_json = json_encode($milestones);
            $udbidding = Bidding::where('id', '=', $req->bidid)->update(['milestones' => $ms_json]);
            if ($udbidding) {
                $succeed = true;
                $project = Project::where('id', $bidding->project_id)->select('name', 'id', 'user_id')->first();
                $freelancer = User::where('id', $bidding->user_id)->select('username', 'id')->first();
                $toid = $project->user_id;
                $data = ['type' => 'completed', 'freelancer_name' => $freelancer->username, 'freelancer_id' => $freelancer->id, 'project_name' => $project->name, 'project_id' => $project->id];
                $this->storeNotif($data, $toid);
            }

            return response()->json(['success' => $succeed, 'late' => $late]);
        } else {
            return response()->json(['notajax' => 'Không phải ajax']);
        }
    }

    public function checkCompleteProject($id)
    {
        $freelancer_id = Project::where('id', $id)->value('freelancer_id');
        if ($freelancer_id == null)
            return false;
        $bidding = Bidding::where([
            ['project_id', '=', $id],
            ['user_id', '=', $freelancer_id]
        ])->first();
        $milestones = json_decode($bidding->milestones);
        foreach ($milestones as $milestone) {
            if ($milestone->is_paid != true)
                return false;
        }
        return true;
    }

    public function storeNotif($data, $toid)
    {
        if ($toid == 0) {
            $user = User::all();
            Notification::send($user, new ProjectNotification($data));
        } else {
            $user = User::find($toid);
            $user->notify(new ProjectNotification($data));
        }
    }

    public function getSearchUser()
    {
        $skills_list = Skill::all();
        $users = User::where('id', '!=', Auth::user()->id)->paginate(10);
        foreach ($users as $user) {
            $skills = array_map('trim', explode(',', $user->skills));
            $user->skills = Skill::select('skillname')->whereIn('id', $skills)->get();
        }
        return view('searchusers', compact('users', 'skills_list'));
    }

    public function postSearchUser(Request $req)
    {
        $skills_list = Skill::all();
        $or_users = User::where('id', '!=', Auth::user()->id);

        if (!is_null($req->keyword)) {
            $or_users->where('first_name', 'like', '%' . $req->keyword . '%')->orWhere('last_name', 'like', '%' . $req->keyword . '%')->orWhere('username', 'like', '%' . $req->keyword . '%');
        }

        if (!is_null($req->skills)) {
            $idu_arr = array();
            $getusers = User::where('id', '!=', Auth::user()->id)->get();
            $col_users = collect($getusers);
            foreach ($col_users as $key => $item) {
                if (!is_null($item->skills)) {
                    if (count(array_intersect(explode(",", $item->skills), $req->skills)) > 0)
                        array_push($idu_arr, $item->id);
                }
            }
            $or_users->whereIn('id', $idu_arr);
        }

        $users = $or_users->paginate(10);

        foreach ($users as $user) {
            $skills = array_map('trim', explode(',', $user->skills));
            $user->skills = Skill::select('skillname')->whereIn('id', $skills)->get();
        }
        return view('searchusers', compact('users', 'skills_list'));
    }

    public function getSearchProject()
    {
        $skills_list = Skill::all();
        $now = new DateTime();
        $currenttime = $now->getTimestamp();
        $projects = Project::leftJoin('bidding', 'projects.id', 'project_id')
            ->selectRaw('projects.id, name, description, state, pay_range, skills_required, count(bidding.user_id) as bid, if((bid_end_at - ?) / 86400<0,0,ceil((bid_end_at - ?) / 86400)) as close_date', [$currenttime, $currenttime])
            ->where('state', 'recruiting')->groupBy('projects.id')->orderBy('projects.created_at', 'desc')->paginate(10);
        foreach ($projects as $project) {
            $price_array = array_map('trim', explode('_', $project->pay_range));
            $project->pay_range = '$' . $price_array[0] . ' - $' . $price_array[1] . ' USD';
            $skills = array_map('trim', explode(',', $project->skills_required));
            $project->skills_required = Skill::select('skillname')->whereIn('id', $skills)->get();
        }
        // $projects = Project::paginate(5);
        return view('searchprojects', compact('projects', 'skills_list'));
    }

    public function postSearchProject(Request $req)
    {
        $skills_list = Skill::all();
        $now = new DateTime();
        $currenttime = $now->getTimestamp();
        $or_projects = Project::leftJoin('bidding', 'projects.id', 'project_id')
            ->selectRaw('projects.id, name, description, state, pay_range, skills_required, count(bidding.user_id) as bid, if((bid_end_at - ?) / 86400<0,0,ceil((bid_end_at - ?) / 86400)) as close_date', [$currenttime, $currenttime])
            ->where('state', 'recruiting')->groupBy('projects.id')->orderBy('projects.created_at', 'desc');

        if (!is_null(trim($req->keyword))) {
            $or_projects->where('name', 'like', '%' . $req->keyword . '%');
        }

        if (!is_null($req->skills)) {
            $id_arr = array();
            $getprojects = Project::leftJoin('bidding', 'projects.id', 'project_id')
                ->selectRaw('projects.id, name, description, state, pay_range, skills_required, count(bidding.user_id) as bid, if((bid_end_at - ?) / 86400<0,0,ceil((bid_end_at - ?) / 86400)) as close_date', [$currenttime, $currenttime])
                ->where('state', 'recruiting')->groupBy('projects.id')->orderBy('projects.created_at', 'desc')->get();
            $col_projects = collect($getprojects);
            foreach ($col_projects as $key => $item) {
                if (count(array_intersect(explode(",", $item->skills_required), $req->skills)) > 0)
                    array_push($id_arr, $item->id);
            }
            $or_projects->whereIn('projects.id', $id_arr);
        }

        $projects = $or_projects->paginate(10);

        foreach ($projects as $project) {
            $price_array = array_map('trim', explode('_', $project->pay_range));
            $project->pay_range = '$' . $price_array[0] . ' - $' . $price_array[1] . ' USD';
            $skills_arr = array_map('trim', explode(',', $project->skills_required));
            $project->skills_required = Skill::select('skillname')->whereIn('id', $skills_arr)->get();
        }

        return view('searchprojects', compact('projects', 'skills_list'));
    }

    public function getAccount($id)
    {
        $user = User::where('id', $id)->first();
        return view('account', compact('user'));
    }

    public function postAccount(Request $req, $id)
    {
        $user = User::where('id', $id)->first();
        switch ($req->pa_type) {
            case "account":
                $user->first_name = $req->first_name;
                $user->last_name = $req->last_name;
                $user->address = $req->address;
                $user->city_province = $req->city_province;
                $user->phone = $req->phone;
                $user->is_freelancer = $req->type_acc;
                $user->save();
                return view('account', compact('user'));
                break;
            case "password":
                if (Auth::check()) {
                    $req->validate([
                        'cur_pw' => 'required|min:8',
                        'new_pw' => 'required|min:8',
                        'conf_pw' => 'required|min:8|same:new_pw',
                    ], [
                        'required' => 'Trường này không được để trống',
                        'cur_pw.min' => 'Mật khẩu ít nhất 8 ký tự',
                        'new_pw.min' => 'Mật khẩu mới ít nhất 8 ký tự',
                        'conf_pw.min' => 'Xác nhận mật khẩu ít nhất 8 ký tự',
                        'conf_pw.same' => 'Mật khẩu xác nhận không giống mật khẩu mới',
                    ]);
                    try {
                        $current_password = Auth::user()->password;
                        if (Hash::check($req->cur_pw, $current_password)) {
                            $user->password = Hash::make($req->new_pw);
                            $user->save();
                            Auth::logout();
                            return view('login');
                        }
                    } catch (Exception $e) {
                        return redirect()->view('account', compact('user'));
                    }
                }
                break;
        }
    }

    public function getFinace(Request $req)
    {
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $history = TrHistory::where('of_user', $user_id)->orderBy('created_at', 'desc')->paginate(10);
            $withdraw_req = WithdrawReq::where('user_id', $user_id)->orderBy('created_at', 'desc')->paginate(10);
            foreach ($withdraw_req as $w_req) {
                $w_req->created_at = Carbon::createFromTimestamp($w_req->created_at)->format('d-m-Y H:i:s');
                $w_req->amount = round($w_req->amount, 2);
            }
            if ($req->ajax()) {
                switch ($req->type_t) {
                    case "in":
                        $history = TrHistory::where([['of_user', $user_id], ['is_in', 1]])->orderBy('created_at', 'desc')->paginate(10);
                        return view('trhistory', compact('history'));
                        break;

                    case "out":
                        $history = TrHistory::where([['of_user', $user_id], ['is_in', 0]])->orderBy('created_at', 'desc')->paginate(10);
                        return view('trhistory', compact('history'));
                        break;

                    default:
                        $history = TrHistory::where('of_user', $user_id)->orderBy('created_at', 'desc')->paginate(10);
                        return view('trhistory', compact('history'));
                }
            }
            return view('finace', compact('history', 'withdraw_req'));
        }
    }

    public function postRating(Request $req, $id)
    {
        $rating = new Rating();
        $rating->p_id = $id;
        $rating->rating_user = $req->rating_user;
        $rating->rated_user = $req->rated_user;
        $rating->points = $req->rating;

        $rating->save();

        return redirect()->route('projectdetail', $id);
    }

    public function postDeposit(Request $req)
    {
        if (Auth::check()) {
            $req->validate([
                'd_money' => 'required'
            ]);

            $depositReq = new OrdersCreateRequest();
            $depositReq->prefer('return=representation');
            $depositReq->body = [
                "intent" => "CAPTURE",
                "purchase_units" => [[
                    "reference_id" => "test_ref_id1",
                    "amount" => [
                        "value" => $req->d_money,
                        "currency_code" => "USD"
                    ]
                ]],
                "application_context" => [
                    "cancel_url" => route('daskboard'),
                    "return_url" => route('udbalances', ['user_id' => Auth::user()->id, 'amount' => $req->d_money])
                ]
            ];
            try {
                // Call API with your client and get a response for your call
                $client = PayPalClient::client();
                $response = $client->execute($depositReq);

                // If call returns body in response, you can get the deserialized version from the result attribute of the response
                return redirect($response->result->links[1]->href);
            } catch (HttpException $ex) {
                echo $ex->statusCode;
                print_r($ex->getMessage());
            }
        }
    }

    public function updateUserBalances($user_id, $amount)
    {
        if (Auth::check() && Auth::user()->id == $user_id) {
            $now = new DateTime();
            $currenttime = $now->getTimestamp();
            $user = User::where('id', $user_id)->increment('balances', $amount);
            $data = [
                ['of_user' => $user_id, 'type' => "deposit", 'amount' => $amount, 'is_in' => 1, 'description' => 'Nạp ' . $amount . ' VND vào tài khoản', 'created_at' => $currenttime],
            ];
            TrHistory::insert($data);

            return redirect('daskboard');
        }
    }

    public function sendWithdrawRequest(Request $req)
    {
        if (Auth::check()) {
            $req->validate([
                'w_money' => 'required|min:3',
                'w_email' => 'required|email',
                'password' => 'required|min:8',
            ]);
            // try {
            if (Hash::check($req->password, Auth::user()->password)) {
                $now = new DateTime();
                $currenttime = $now->getTimestamp();
                $withdraw_req = new WithdrawReq();
                $withdraw_req->user_id = Auth::user()->id;
                $withdraw_req->paypal_email = $req->w_email;
                $withdraw_req->amount = $req->w_money;
                $withdraw_req->created_at = $currenttime;
                $withdraw_req->updated_at = $currenttime;
                $withdraw_req->save();
                return redirect()->route('finace');
            } else {
                return redirect()->route('finace')->with('wrong_pass', 'Mật khẩu không chính xác');
            }
            // } catch (Exception $e) {
            //     return redirect()->view('account', compact('user'));
            // }
        }
    }
}
