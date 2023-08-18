<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Policies\GroupPolicy;

class GroupController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Group::class);

        if (Auth::user()->group_id == 1) {
            $list = Group::all();
        } else {
            $list = Group::where('user_id', '=', Auth::user()->id)->get();
        }
        $title = 'Nhóm người dùng';
        return view('backend.group.list', compact('list', 'title'));
    }
    public function getAdd()
    {

        $title = 'Thêm nhóm người dùng';
        $module = Module::all();
        $roleArr = [
            'View' => 'Xem',
            'Add' => 'Thêm',
            'Edit' => 'Sửa',
            'Delete' => 'Xóa',
        ];
        return view('backend.group.addForm', compact('title', 'module', 'roleArr'));
    }
    public function postAdd(Request $request)
    {
        $request->validate([
            'txtGroupName' => 'required|max:30|unique:groups,name',
            'txtGroupDescribe' => 'max:255',
            'slGroupStatus' => 'required',
        ], [
            'txtGroupName.required' => ':attribute không được bỏ trống',
            'txtGroupName.max' => ':attribute phải tối đa :max ký tự',
            'txtGroupName.unique' => ':attribute đã tồn tại',
            'txtGroupDescribe.max' => ':attribute phải tối đa :max ký tự',
            'slGroupStatus.required' => ':attribute không được bỏ trống',
        ], [
            'txtGroupName' => 'Tên nhóm',
            'txtGroupDescribe' => 'Miêu tả nhóm',
            'slGroupStatus' => 'Trạng thái nhóm',
        ]);
        if (!empty($request->role)) {
            $request->validate([
                'role' => 'required'
            ], [
                'role.required' => ':attribute không được bỏ trống'

            ], [
                'role' => 'Phân quyền'
            ]);
            $roleArr = $request->role;
            $roleJson = json_encode($roleArr);
        } else {
            $roleArr = [];
        }
        $roleJson = json_encode($roleArr);

        $group = new Group;
        $group->name = $request->txtGroupName;
        $group->describe = $request->txtGroupDescribe;
        $group->active = $request->slGroupStatus;
        $group->permissions = $roleJson;
        $group->user_id = Auth::user()->id;
        $group->save();
        if ($group->id) {
            return redirect()->route('admin.group.list')->with('msg', 'thêm thành công');
        } else {
            return redirect()->route('admin.group.list')->with('msg', 'Đã xảy ra lỗi');
        }
    }
    public function getEdit(Group $group)
    {
        $this->authorize('update', $group);
        $title = 'Sửa nhóm người dùng';
        $roleArr = [
            'View' => 'Xem',
            'Add' => 'Thêm',
            'Edit' => 'Sửa',
            'Delete' => 'Xóa',
        ];
        $module = Module::all();
        $groupRoleArr = json_decode($group->permissions, true);

        return view('backend.group.editForm', compact('title', 'group', 'module', 'roleArr', 'groupRoleArr'));
    }
    public function postEdit(Group $group, Request $request)
    {
        $request->validate([
            'txtGroupName' => 'required|max:30|unique:groups,name,' . $group->id,
            'txtGroupDescribe' => 'max:255',
            'slGroupStatus' => 'required',
        ], [
            'txtGroupName.required' => ':attribute không dược bỏ trống',
            'txtGroupName.max' => ':attribute phải tối đa :max ký tự',
            'txtGroupName.unique' => ':attribute đã tồn tại',
            'txtGroupDescribe.max' => ':attribute phải tối đa :max ký tự',
            'slGroupStatus.required' => ':attribute không dược bỏ trống',

        ], [
            'txtGroupName' => 'Tên nhóm',
            'txtGroupDescribe' => 'Miêu tả nhóm',
            'slGroupStatus' => 'Trạng thái nhóm',

        ]);
        if (!empty($request->role)) {
            $request->validate([
                'role' => 'required'
            ], [
                'role.required' => ':attribute không được bỏ trống'

            ], [
                'role' => 'Phân quyền'
            ]);
            $roleArr = $request->role;
            $roleJson = json_encode($roleArr);
            $group->permissions = $roleJson;
        } else {
            $roleArr = [];
        }

        $group->name = $request->txtGroupName;
        $group->describe = $request->txtGroupDescribe;
        $group->active = $request->slGroupStatus;
        if ($group->save()) {
            return redirect()->route('admin.group.list')->with('msg', 'sửa thành công');
        } else {
            return redirect()->route('admin.group.list')->with('msg', 'Đã xảy ra lỗi');
        }
    }
    public function delete(Group $group)
    {
        $this->authorize('delete', $group);
        $userCount = $group->users->count();
        if ($userCount == 0) {
            Group::destroy($group->id);
            return redirect()->route('admin.group.list')->with('msg', 'Xóa nhóm thành công');
        } else {
            return redirect()->route('admin.group.list')->with('msg', 'Nhóm còn tồn tại người dùng');
        }
    }
}
