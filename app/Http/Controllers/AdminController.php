<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Fee;
use App\PayRange;
use App\Skill;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                return redirect()->route('admin.master');
            }
        } else
            return redirect()->route('admin.login')->with('message', 'Tên đăng nhập hoặc mật khẩu không chính xác');
    }

    public function getLogout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.master');
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
}
