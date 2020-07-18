<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Bidding;
use App\Fee;
use App\PayRange;
use App\Project;
use App\Skill;
use App\TrHistory;
use App\UploadFile;
use App\User;
use App\WithdrawReq;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //
    public function getLogin()
    {
        return view('admin.login');
    }

    public function postLogin(Request $req)
    {
        $req->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        $credentials = array('username' => $req->username, 'password' => $req->password);
        if (Auth::guard('admin')->attempt($credentials, $req->has('remember'))) {
            if (Auth::guard('admin')->user()->is_changedpw == 0) {
                return redirect()->route('admin.changepw');
            } else {
                return redirect()->route('admin.adminmgmt');
            }
        } else
            return redirect()->route('admin.login')->with('message', 'Tên đăng nhập hoặc mật khẩu không chính xác');
    }

    public function getLogout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function getChangepw()
    {
        return view('admin.changepw');
    }

    public function postChangepw(Request $req)
    {
        if (Auth::guard('admin')->check()) {
            $req->validate([
                'password' => 'required|min:8',
                'repassword' => 'required|min:8|same:password',
            ], [
                'required' => 'Trường này không được để trống',
                'password.min' => 'Mật khẩu ít nhất 8 ký tự',
                'repassword.min' => 'Xác nhận mật khẩu ít nhất 8 ký tự',
                'repassword.same' => 'Mật khẩu xác nhận phải giống mật khẩu mới',
            ]);
            try {
                $admin = Admin::where('id', Auth::guard('admin')->user()->id)->first();
                $admin->password = Hash::make($req->password);
                $admin->is_changedpw = 1;
                $admin->save();
                Auth::guard('admin')->logout();
                return view('admin.login');
            } catch (Exception $e) {
                return redirect()->view('admin.changepw')->with();
            }
        }
    }

    public function getAdminmgmt()
    {
        $admins = Admin::paginate(10);
        return view('admin.adminmgmt', compact('admins'));
    }

    public function postAdminmgmt(Request $req)
    {
        if ($req->admin_type == "add") {
            $req->validate([
                'username' => 'required|min:5|max:50|unique:admins,username',
                'fullname' => 'required|min:5|max:45',
                'phone' => 'required|max:10|min:10',
                'email' => 'required|email|unique:admins,email',
                'level' => 'required|numeric|min:1|max:5'
            ], [
                'username.min' => 'Tên đăng nhập ít nhất 5 ký tự',
                'username.unique' => 'Tên đăng nhập đã tồn tại',
                'fullname.min' => 'Họ tên admin ít nhất 5 ký tự',
                'required' => 'Trường này không được để trống',
                'phone.min' => 'Số điện thoại phải có đúng 10 ký tự',
                'phone.max' => 'Số điện thoại phải có đúng 10 ký tự',
                'email.email' => 'Định dạng email không hợp lệ',
                'email.unique' => 'Email đã tồn tại trên hệ thống',
                'level.numeric' => 'Cấp bậc phải là số',
                'level.min' => 'Cấp bậc lớn nhất là 1',
                'level.max' => 'Cấp bậc nhỏ nhất là 5',
            ]);

            try {
                $now = new DateTime();
                $currenttime = $now->getTimestamp();
                $admin = new Admin();
                $admin->username = $req->username;
                $admin->full_name = $req->fullname;
                $admin->phone = $req->phone;
                $admin->email = $req->email;
                $admin->password = bcrypt('12345678');
                $admin->level = $req->level;
                $admin->created_at = $currenttime;
                $admin->updated_at = $currenttime;
                $admin->save();
                return redirect()->route('admin.adminmgmt');
            } catch (Exception $e) {
                return redirect()->view('admin.adminmgmt')->with();
            }
        }
        if ($req->admin_type == "delete") {
            Admin::where('id', $req->admin_id)->delete();
            return redirect()->route('admin.adminmgmt');
        }
        if ($req->admin_type == "resetpass") {
            $admin = Admin::where('id', $req->admin_id)->first();
            $admin->password = Hash::make("12345678");
            $admin->is_changedpw = 0;
            $admin->save();
            if (Auth::guard('admin')->user()->id === $req->admin_id) {
                Auth::guard('admin')->logout();
            }
            return redirect()->route('admin.adminmgmt');
        }
        if ($req->admin_type == "update") {
            $req->validate([
                'ud_phone' => 'required|max:10|min:10',
                'ud_level' => 'required|numeric|min:1|max:5'
            ], [
                'required' => 'Trường này không được để trống',
                'ud_phone.min' => 'Số điện thoại phải có đúng 10 ký tự',
                'ud_phone.max' => 'Số điện thoại phải có đúng 10 ký tự',
                'ud_level.numeric' => 'Cấp bậc phải là số',
                'ud_level.min' => 'Cấp bậc lớn nhất là 1',
                'ud_level.max' => 'Cấp bậc nhỏ nhất là 5',
            ]);

            try {
                $now = new DateTime();
                $currenttime = $now->getTimestamp();
                $admin = Admin::where('id', $req->admin_id)->first();
                $admin->phone = $req->ud_phone;
                $admin->level = $req->ud_level;
                $admin->updated_at = $currenttime;
                $admin->save();
                return redirect()->route('admin.adminmgmt');
            } catch (Exception $e) {
                return redirect()->view('admin.adminmgmt')->with();
            }
        }
        if ($req->admin_type == "search") {
            if (!is_null($req->keyword)) {
                $admins = Admin::where('full_name', 'like', '%' . $req->keyword . '%')->orwhere('username', 'like', '%' . $req->keyword . '%')->paginate(10);
            } else {
                $admins = Admin::paginate(10);
            }
            return view('admin.adminmgmt', compact('admins'));
        }
    }

    public function getUsermgmt()
    {
        $users = User::leftJoin('projects', function ($join) {
            $join->on('users.id', '=', 'projects.user_id')->orOn('users.id', '=', 'projects.freelancer_id');
        })
            ->selectRaw('users .*, sum(if(projects.user_id = users.id,1,0)) as projects_e, sum(if(projects.freelancer_id = users.id,1,0)) as projects_f')
            ->groupBy('users.id')
            ->paginate(10);
        return view('admin.usermgmt', compact('users'));
    }

    public function postUsermgmt(Request $req)
    {
        if ($req->action == "delete") {
            User::where('id', $req->user_id)->delete();
            return redirect()->route('admin.usermgmt');
        }

        if ($req->action == "search") {
            if (!is_null($req->keyword)) {
                $users = User::leftJoin('projects', function ($join) {
                    $join->on('users.id', '=', 'projects.user_id')->orOn('users.id', '=', 'projects.freelancer_id');
                })
                    ->selectRaw('users .*, sum(if(projects.user_id = users.id,1,0)) as projects_e, sum(if(projects.freelancer_id = users.id,1,0)) as projects_f')
                    ->where('first_name', 'like', '%' . $req->keyword . '%')->orwhere('last_name', 'like', '%' . $req->keyword . '%')->orwhere('username', 'like', '%' . $req->keyword . '%')
                    ->groupBy('users.id')
                    ->paginate(10);
            } else {
                $users = User::leftJoin('projects', function ($join) {
                    $join->on('users.id', '=', 'projects.user_id')->orOn('users.id', '=', 'projects.freelancer_id');
                })
                    ->selectRaw('users .*, sum(if(projects.user_id = users.id,1,0)) as projects_e, sum(if(projects.freelancer_id = users.id,1,0)) as projects_f')
                    ->groupBy('users.id')
                    ->paginate(10);
            }
            return view('admin.usermgmt', compact('users'));
        }
    }

    public function getProjectmgmt()
    {
        $projects = Project::join('users', 'projects.user_id', 'users.id')
            ->selectRaw('projects .*, username')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        foreach ($projects as $project) {
            $price_array = array_map('trim', explode('_', $project->pay_range));
            if ($price_array[1] == '0') {
                $project->pay_range = $price_array[0] . '+ VND';
            } else {
                $project->pay_range = $price_array[0] . ' - ' . $price_array[1] . ' VND';
            }
        }
        return view('admin.projectmgmt', compact('projects'));
    }

    public function postProjectmgmt(Request $req)
    {
        if ($req->action == "delete") {
            $project = Project::where('id', $req->project_id)->first();
            $skills_list = explode(',', $project->skills_required);
            $deleted = Project::where('id', $req->project_id)->delete();
            if ($deleted) {
                foreach ($skills_list as $skill) {
                    Skill::where('id', $skill)->decrement('jobs', 1);
                }
                Bidding::where('project_id', $req->project_id)->delete();
                if (!is_null($project->file_uploaded)) {
                    $project->file_uploaded = json_decode($project->file_uploaded);
                    $path = $project->file_uploaded->path;
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                }
            }
            return redirect()->route('admin.projectmgmt');
        }

        if ($req->action == "search") {
            if (!is_null($req->keyword)) {
                $projects = Project::join('users', 'projects.user_id', 'users.id')
                    ->selectRaw('projects .*, username')
                    ->where('name', 'like', '%' . $req->keyword . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            } else {
                $projects = Project::join('users', 'projects.user_id', 'users.id')
                    ->selectRaw('projects .*, username')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            }
            return view('admin.projectmgmt', compact('projects'));
        }
    }

    public function getPricemgmt()
    {
        $prices = PayRange::orderBy('from', 'asc')->paginate(10);
        return view('admin.pricemgmt', compact('prices'));
    }

    public function postPricemgmt(Request $req)
    {
        if ($req->price_type == "add") {
            $req->validate([
                'price_name' => 'required|min:2|max:45',
                'from' => 'required|numeric|min:10',
                'to' => 'required|numeric',
            ], [
                'price_name.min' => 'Tên mức giá cần ít nhất 5 ký tự',
                'required' => 'Trường này không được để trống',
                'from.min' => 'Giá khởi điểm ít nhất phải bằng 10',
            ]);

            try {
                $price = new PayRange();
                $price->name = $req->price_name;
                $price->from = $req->from;
                $price->to = $req->to;
                $price->save();
                return redirect()->route('admin.pricemgmt');
            } catch (Exception $e) {
                return redirect()->view('admin.pricemgmt')->with();
            }
        }

        if ($req->price_type == "delete") {
            PayRange::where('id', $req->price_id)->delete();
            return redirect()->route('admin.pricemgmt');
        }

        if ($req->price_type == "update") {
            $req->validate([
                'ud_price_name' => 'required|min:2|max:45',
                'ud_from' => 'required|numeric|min:10',
                'ud_to' => 'required|numeric',
            ], [
                'ud_price_name.min' => 'Tên mức giá cần ít nhất 5 ký tự',
                'required' => 'Trường này không được để trống',
                'ud_from.min' => 'Giá khởi điểm ít nhất phải bằng 10',
            ]);

            try {
                $price = PayRange::where('id', $req->price_id)->first();
                $price->name = $req->ud_price_name;
                $price->from = $req->ud_from;
                $price->to = $req->ud_to;
                $price->save();
                return redirect()->route('admin.pricemgmt');
            } catch (Exception $e) {
                return redirect()->view('admin.pricemgmt')->with();
            }
        }

        if ($req->price_type == "search") {
            if (!is_null($req->keyword)) {
                $prices = PayRange::where('name', 'like', '%' . $req->keyword . '%')
                    ->orderBy('from', 'asc')
                    ->paginate(10);
            } else {
                $prices = PayRange::orderBy('from', 'asc')->paginate(10);
            }
            return view('admin.pricemgmt', compact('prices'));
        }
    }

    public function getSkillmgmt()
    {
        $skills = Skill::orderBy('skillname', 'asc')->paginate(10);
        return view('admin.skillmgmt', compact('skills'));
    }

    public function postSkillmgmt(Request $req)
    {
        if ($req->skill_type == "add") {
            $req->validate([
                'skillname' => 'required|min:1|max:70|unique:skills,skillname',
            ], [
                'skillname.min' => 'Tên kỹ năng cần ít nhất 1 ký tự',
                'skillname.unique' => 'Tên kỹ năng không được trùng lặp',
                'required' => 'Trường này không được để trống',
            ]);

            try {
                $skill = new Skill();
                $skill->skillname = $req->skillname;
                $skill->save();
                return redirect()->route('admin.skillmgmt');
            } catch (Exception $e) {
                return redirect()->view('admin.skillmgmt')->with();
            }
        }

        if ($req->skill_type == "delete") {
            Skill::where('id', $req->skill_id)->delete();
            return redirect()->route('admin.skillmgmt');
        }

        if ($req->skill_type == "update") {
            $req->validate([
                'ud_skillname' => 'required|min:1|max:70|unique:skills,skillname',
            ], [
                'ud_skillname.min' => 'Tên kỹ năng cần ít nhất 1 ký tự',
                'ud_skillname.unique' => 'Tên kỹ năng không được trùng lặp',
                'required' => 'Trường này không được để trống',
            ]);

            try {
                $skill = Skill::where('id', $req->skill_id)->first();
                $skill->skillname = $req->ud_skillname;
                $skill->save();
                return redirect()->route('admin.skillmgmt');
            } catch (Exception $e) {
                return redirect()->view('admin.skillmgmt')->with();
            }
        }

        if ($req->skill_type == "search") {
            if (!is_null($req->keyword)) {
                $skills = Skill::where('skillname', 'like', '%' . $req->keyword . '%')
                    ->orderBy('skillname', 'asc')
                    ->paginate(10);
            } else {
                $skills = Skill::orderBy('skillname', 'asc')->paginate(10);
            }
            return view('admin.skillmgmt', compact('skills'));
        }
    }

    public function getFeemgmt()
    {
        $min_price = PayRange::min('from');
        $fee = Fee::first();
        return view('admin.feemgmt', compact('fee', 'min_price'));
    }

    public function postFeemgmt(Request $req)
    {
        $req->validate([
            'fixed_fee' => 'required|numeric|min:1',
            'fixed_boundary' => 'required|numeric|min:1',
            'fee_rate' => 'required|numeric|min:1|max:20',
            'penalty_rate' => 'required|numeric|min:1|max:40',
        ], [
            'fixed_fee.min' => 'Phí cố định phải lớn hơn 0',
            'fixed_boundary.min' => 'Mức giá áp dụng phí phải lớn hơn 0',
            'fee_rate.min' => 'Tỉ lệ phí tham gia phải lớn hơn 0',
            'fee_rate.max' => 'Tỉ lệ phí tham gia phải nhỏ hơn hoặc bằng 20',
            'penalty_rate.min' => 'Tỉ lệ phí phạt phải lớn hơn 0',
            'penalty_rate.max' => 'Tỉ lệ phí phạt phải nhỏ hơn hoặc bằng 40',
            'required' => 'Trường này không được để trống',
        ]);
        try {
            $fee = Fee::first();
            $fee->fixed_fee = $req->fixed_fee;
            $fee->fixed_boundary = $req->fixed_boundary;
            $fee->fee_rate = $req->fee_rate;
            $fee->penalty_rate = $req->penalty_rate;
            $fee->save();
            return redirect()->route('admin.feemgmt');
        } catch (Exception $e) {
            return redirect()->view('admin.feemgmt')->with();
        }
    }

    public function getTxnmgmt()
    {
        $withdraw_req = WithdrawReq::leftJoin('users', 'withdraw_req.user_id', 'users.id')->selectRaw('withdraw_req .*, username, balances')->orderBy('approved_at', 'asc')->orderBy('created_at', 'desc')->paginate(10);
        if (!is_null($withdraw_req)) {
            foreach ($withdraw_req as $w_req) {
                $w_req->created_at = Carbon::createFromTimestamp($w_req->created_at)->format('d-m-Y H:i:s');
                $w_req->amount = round($w_req->amount, 2);
                $w_req->balances = round($w_req->balances, 2);
            }
        }
        return view('admin.txnmgmt', compact('withdraw_req'));
    }

    public function postTxnmgmt(Request $req)
    {
        if ($req->action == "approve") {
            $withdraw_req = WithdrawReq::where('id', $req->withdraw_id)->first();
            $now = new DateTime();
            $currenttime = $now->getTimestamp();
            $user = User::where('id', $withdraw_req->user_id)->decrement('balances', $withdraw_req->amount);
            $data = [
                ['of_user' => $withdraw_req->user_id, 'type' => "withdraw", 'amount' => $withdraw_req->amount, 'is_in' => 0, 'description' => 'Rút ' . $withdraw_req->amount . ' VND khỏi tài khoản', 'created_at' => $currenttime],
            ];
            TrHistory::insert($data);
            $withdraw_req->approved_at = $currenttime;
            $withdraw_req->updated_at = $currenttime;
            $withdraw_req->save();
            return redirect()->route('admin.txnmgmt');
        }

        if ($req->action == "search") {
            if (!is_null($req->keyword)) {
                $withdraw_req = WithdrawReq::leftJoin('users', 'withdraw_req.user_id', 'users.id')
                    ->selectRaw('withdraw_req .*, username, balances')
                    ->where('username', 'like', '%' . $req->keyword . '%')
                    ->orwhere('withdraw_req.user_id', 'like', '%' . $req->keyword . '%')
                    ->orderBy('approved_at', 'asc')->orderBy('created_at', 'desc')->paginate(10);
            } else {
                $withdraw_req = WithdrawReq::leftJoin('users', 'withdraw_req.user_id', 'users.id')->selectRaw('withdraw_req .*, username, balances')->orderBy('approved_at', 'asc')->orderBy('created_at', 'desc')->paginate(10);
            }
            if (!is_null($withdraw_req)) {
                foreach ($withdraw_req as $w_req) {
                    $w_req->created_at = Carbon::createFromTimestamp($w_req->created_at)->format('d-m-Y H:i:s');
                    $w_req->amount = round($w_req->amount, 2);
                    $w_req->balances = round($w_req->balances, 2);
                }
            }
            return view('admin.txnmgmt', compact('withdraw_req'));
        }
    }
}
