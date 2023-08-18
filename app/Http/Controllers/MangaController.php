<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
//! nhớ validate name có unique chưa xử lý
class MangaController extends Controller
{
    public function index()
    {
        if (Auth::user()->group_id) {
            //check nếu là supperadmin thì xem được tất cả
        }
        $title = 'Danh sách truyện';
        $mangas = Manga::where('user_id', '=', Auth::id())->get();
        return view('backend.manga.list', compact('title', 'mangas'));
    }
    public function getCreate()
    {
        $title = 'Thêm truyện';
        return view('backend.manga.addForm', compact('title'));
    }
    public function postCreate(Request $request)
    {
        $request->validate(
            [
                'txtMangaName' => 'required|max:30|unique:mangas,name',
                'txtMangaAuthor' => 'required|max:40',
                'txtMangaAnotherName' => 'required|max:30|',
                'txtMangaDescribe' => 'required|max:250|',
                'slMangaCategories' => 'required',
                'slMangaStatus' => 'required',
                'txtMangaSlug' => 'required|unique:mangas,slug',
                'imgCover' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            ],
            [
                'txtMangaName.required' => ':attribute không được bỏ trống',
                'txtMangaName.unique' => ':attribute đã tồn tại',
                'txtMangaName.max' => ':attribute dài tối đa :max kí tự',
                'txtMangaAuthor.required' => ':attribute không được bỏ trống',
                'txtMangaAuthor.max' => ':attribute dài tối đa :max kí tự',
                'txtMangaAnotherName.required' => ':attribute không được bỏ trống',
                'txtMangaAnotherName.max' => ':attribute dài tối đa :max kí tự',
                'txtMangaDescribe.required' => ':attribute không được bỏ trống',
                'txtMangaDescribe.max' => ':attribute dài tối đa :max kí tự',
                'slMangaStatus.required' => 'Vui lòng chọng :attribute',
                'txtMangaSlug.required' => ':attribute không được bỏ trống',
                'txtMangaSlug.unique' => ':attribute đã tồn tại',
                'imgCover.required' => ':attribute không được bỏ trống',
                'imgCover.image' => ':attribute không đúng định dạng',
                'imgCover.size' => ':attribute vượt quá dung lượng cho phép',
            ],
            [
                'txtMangaName' => 'Tên truyện',
                'txtMangaAnotherName' => 'Tên khác',
                'txtMangaAuthor' => 'Tên tác giả',
                'txtMangaDescribe' => 'Miêu tả',
                'slMangaStatus' => 'Trạng thái',
                'slMangaCategories' => 'Nhóm truyện',
                'txtMangaSlug' => 'Slug',
                'imgCover' => 'Hình bìa',
            ]
        );
        $categories = implode(',', $request->slMangaCategories);
        $store = $request->file('imgCover')->store('cover', 'public');
        if ($store) {
            $imgName = basename($store);
        } else {
            $imgName = 'default.jpg';
        }
        // dd(basename($store));
        $manga = new Manga();
        $manga->name = $request->txtMangaName;
        $manga->another_name = $request->txtMangaAnotherName;
        $manga->author  = $request->txtMangaAuthor;
        $manga->describe = $request->txtMangaDescribe;
        $manga->categories = $categories;
        $manga->user_id = Auth::user()->id;
        $manga->active = $request->slMangaStatus;
        $manga->is_finished = 1;
        $manga->slug = $request->txtMangaSlug;
        $manga->image_cover = $imgName;
        $manga->save();
        if ($manga->id) {
            return redirect()->route('admin.manga.list')->with('msg', 'thêm truyện thành công');
        } else {
            return redirect()->route('admin.manga.list')->with('msg', 'Đã xảy ra lỗi');
        }
    }
    public function getEdit(Manga $manga)
    {
        $title = 'Thêm truyện';
        return view('backend.manga.editForm', compact('title', 'manga'));
    }
    public function postEdit(Manga $manga, Request $request)
    {
        $request->validate(
            [
                'txtMangaName' => 'required|max:30|unique:mangas,name,' . $manga->id,
                'txtMangaAuthor' => 'required|max:40',
                'txtMangaAnotherName' => 'required|max:30|',
                'txtMangaDescribe' => 'required|max:250|',
                'slMangaCategories' => 'required',
                'slMangaStatus' => 'required',
                'txtMangaSlug' => 'required|unique:mangas,slug,' . $manga->id,
                'imgCover' => 'image|mimes:jpg,png,jpeg|max:2048',
            ],
            [
                'txtMangaName.required' => ':attribute không được bỏ trống',
                'txtMangaName.unique' => ':attribute đã tồn tại',
                'txtMangaName.max' => ':attribute dài tối đa :max kí tự',
                'txtMangaAuthor.required' => ':attribute không được bỏ trống',
                'txtMangaAuthor.max' => ':attribute dài tối đa :max kí tự',
                'txtMangaAnotherName.required' => ':attribute không được bỏ trống',
                'txtMangaAnotherName.max' => ':attribute dài tối đa :max kí tự',
                'txtMangaDescribe.required' => ':attribute không được bỏ trống',
                'txtMangaDescribe.max' => ':attribute dài tối đa :max kí tự',
                'slMangaCategories.required' => ':attribute không được bỏ trống',
                'slMangaStatus.required' => 'Vui lòng chọng :attribute',
                'txtMangaSlug.required' => ':attribute không được bỏ trống',
                'txtMangaSlug.unique' => ':attribute đã tồn tại',

                // 'imgCover.required' => ':attribute không được bỏ trống',
                'imgCover.image' => ':attribute không đúng định dạng',
                'imgCover.size' => ':attribute vượt quá dung lượng cho phép',
            ],
            [
                'txtMangaName' => 'Tên truyện',
                'txtMangaAnotherName' => 'Tên khác',
                'txtMangaAuthor' => 'Tên tác giả',
                'txtMangaDescribe' => 'Miêu tả',
                'slMangaStatus' => 'Trạng thái',
                'slMangaCategories' => 'Nhóm truyện',
                'txtMangaSlug' => 'Slug',
                'imgCover' => 'Hình bìa',
            ]
        );
        $categories = implode(',', $request->slMangaCategories);
        if (!empty($request->imgCover)) {
            $store = $request->file('imgCover')->store('cover', 'public');
            if ($store) {
                $imgName = basename($store);
            } else {
                $imgName = 'default.jpg';
            }
        }
        $manga->name = $request->txtMangaName;
        $manga->another_name = $request->txtMangaAnotherName;
        $manga->author  = $request->txtMangaAuthor;
        $manga->describe = $request->txtMangaDescribe;
        $manga->categories = $categories;
        $manga->active = $request->slMangaStatus;
        $manga->slug = $request->txtMangaSlug;
        if (isset($imgName)) {
            $manga->image_cover = $imgName;
        }
        $manga->save();
        // dd($category->id);
        if ($manga->id) {
            return redirect()->route('admin.manga.list')->with('msg', 'sửa thành công');
        } else {
            return redirect()->route('admin.manga.list')->with('msg', 'Đã xảy ra lỗi');
        }
    }
    public function delete(Manga $manga)
    {
        if ($manga) {
            // dd($files = Storage::allFiles('public/cover'));
            // dd($manga->image_cover);
            if (Storage::exists('public/cover/' . $manga->image_cover) && $manga->image_cover != 'default.jpg') {
                Storage::delete('public/cover/' . $manga->image_cover);
            }
            if ($manga->delete()) {
                return redirect()->route('admin.manga.list')->with('msg', 'xóa thành công');
            }
        }
        return redirect()->route('admin.manga.list')->with('msg', 'xóa thất bại');
    }
}
