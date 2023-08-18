<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $title = 'Quản lý người dùng';
        $user = Auth::user();
        $id = $user->id;
        $list = User::where('id', '!=', $id)
            ->where('group_id', '!=', 0)
            ->get();

        return view('backend.user.list', compact('title', 'list'));
    }
    public function getAdd()
    {
        $title = 'Thêm người dùng';
        return view('backend.user.addForm', compact('title'));
    }
    public function postAdd(Request $request)
    {
        $request->validate([
            'txtUserName' => 'required|min:5',
            'txtUserEmail' => 'required|email|unique:users,email',
            'txtUserPassword' => 'required|confirmed|min:8',
            'txtUserPassword_confirmation' => 'required|min:8',
            'slUserGroup' => 'required',
            'slUserActive' => 'required',
            'imgCover' => 'image|mimes:jpg,png,jpeg|max:2048',
        ], [
            // 'txtUserName.required' => ':attribute không được bỏ trống',
            'txtUserName.min' => ':attribute phải có ít nhất :min ký tự',
            'txtUserEmail.required' => ':attribute không được bỏ trống',
            'txtUserEmail.email' => ':attribute không phải định dạng email',
            'txtUserEmail.unique' => ':attribute đã tồn tại',
            'txtUserPassword.required' => ':attribute không được bỏ trống',
            'txtUserPassword.confirmed' => ':attribute không trùng khớp',
            'txtUserPassword.min' => ':attribute phải có ít nhất :min ký tự',
            'txtUserPassword_confirmation.required' => ':attribute không được bỏ trống',
            'txtUserPassword_confirmation.min' => ':attribute phải có ít nhất :min ký tự',
            'slUserGroup.required' => ':attribute chưa chọn',
            'slUserActive.required' => ':attribute chưa chọn',
            'imgCover.required' => ':attribute chưa chọn',
            'imgCover.image' => ':attribute không đúng định dạng',
            'imgCover.mimes' => ':attribute không đúng định dạng',
            'imgCover.max' => ':attribute quá lớn',
        ], [
            'txtUserName' => 'Tên người dùng',
            'txtUserEmail' => 'Email',
            'txtUserPassword' => 'Mật khẩu',
            'txtUserPassword_confirmation' => 'Xác nhận mật khẩu',
            'slUserGroup' => 'Nhóm người dùng',
            'slUserActive' => 'Trạng thái người dùng',
            'imgCover' => 'Avatar',
        ]);
        $user = new User();
        $user->name = $request->txtUserName;
        $user->email = $request->txtUserEmail;
        $user->password = Hash::make($request->txtUserPassword);
        $user->group_id = $request->slUserGroup;
        $user->active = $request->slUserActive;
        $user->user_id = Auth::id();
        // upload avatar;
        // $image = $request->file('imgCosver');

        // Generate a unique name for the image.
        if ($request->file('imgCover')) {
            $extension = pathinfo($request->file('imgCover')->getClientOriginalName(), PATHINFO_EXTENSION);
            $uniqueName = uniqid() . '.' . $extension;
            $store = $request->file('imgCover')->storeAs('avatar', $uniqueName, 'public');
            $imgName = basename($store);
        } else {
            $imgName = 'default.jpg';
        }

        $user->avatar = $imgName;
        $user->save();
        if ($user->id) {
            return redirect()->route('admin.user.list')->with('msg', 'thêm người dùng thành công');
        } else {
            return redirect()->route('admin.user.list')->with('msg', 'Đã xảy ra lỗi');
        }
    }
    public function getEdit(Request $request, User $user)
    {
        $title = 'Sửa người dùng';
        return view('backend.user.editForm', compact('title', 'user'));
    }
    public function postEdit(Request $request, User $user)
    {
        $request->validate([
            'txtUserName' => 'required|min:5',
            'txtUserEmail' => 'required|email|unique:users,email,' . $user->id,
        ], [
            'txtUserName.required' => ':attribute không được bỏ trống',
            'txtUserName.min' => ':attribute phải có ít nhất :min ký tự',
            'txtUserEmail.required' => ':attribute không được bỏ trống',
            'txtUserEmail.email' => ':attribute không phải định dạng email',
            'txtUserEmail.unique' => ':attribute đã tồn tại',
        ], [
            'txtUserName' => 'Tên người dùng',
            'txtUserEmail' => 'Email',
        ]);
        $user->name = $request->txtUserName;
        $user->email = $request->txtUserEmail;
        $superAdmin = Auth::user();
        if ($superAdmin->isSuperAdmin()) {
            $request->validate([
                'slUserGroup' => 'required',
                'slUserActive' => 'required',
            ], [
                'slUserGroup.required' => ':attribute chưa chọn',
                'slUserActive.required' => ':attribute chưa chọn',
            ], [
                'slUserGroup' => 'Nhóm người dùng',
                'slUserActive' => 'Trạng thái người dùng',
            ]);
            $user->group_id = $request->slUserGroup;
            $user->active = $request->slUserActive;
        }
        // upload avatar;
        // $image = $request->file('imgCover');
        if ($request->file('imgCover')) {
            $request->validate([
                'imgCover' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            ], [
                'imgCover.required' => ':attribute chưa chọn',
                'imgCover.image' => ':attribute không đúng định dạng',
                'imgCover.mimes' => ':attribute không đúng định dạng',
                'imgCover.max' => ':attribute quá lớn',
            ], [
                'imgCover' => 'Avatar',
            ]);
            // Generate a unique name for the image.
            $extension = pathinfo($request->file('imgCover')->getClientOriginalName(), PATHINFO_EXTENSION);
            $uniqueName = uniqid() . '.' . $extension;
            $store = $request->file('imgCover')->storeAs('avatar', $uniqueName, 'public');
            if ($store) {
                $imgName = basename($store);
                if (Storage::exists('public/avatar/' . $user->avatar) && $user->avatar != 'default.jpg') {
                    Storage::delete('public/avatar/' . $user->avatar);
                } else {
                    //return false
                }
            } else {
                $imgName = 'default.jpg';
            }
            $user->avatar = $imgName;
        }
        $user->save();
        if ($user->id) {
            return redirect()->route('admin.user.list')->with('msg', 'Sửa thông tin người dùng thành công');
        } else {
            return redirect()->route('admin.user.list')->with('msg', 'Đã xảy ra lỗi');
        }
    }
    public function delete(User $user)
    {
        if ($user) {
            if ($user->delete()) {
                if (Storage::exists('public/avatar/' . $user->avatar) && $user->avatar != 'default.jpg') {
                    Storage::delete('public/avatar/' . $user->avatar);
                }
                return redirect()->route('admin.user.list')->with('msg', 'xóa thành công');
            }
        }
        return redirect()->route('admin.user.list')->with('msg', 'xóa thất bại');
    }
}
