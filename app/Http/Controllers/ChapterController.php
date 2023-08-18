<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Manga;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class ChapterController extends Controller
{
    public function index(Manga $manga)
    {
        $title = 'Danh sách chương';
        $mangaId = $manga->id;

        // $manga = Manga::find(1)->chapter()->where('id', '>', 1)->get();
        $chapters = Chapter::where('manga_id', '=', $mangaId)->get();
        $mangaName = $manga->name;
        // dd($manga);
        // dd($chapter[0]->manga->name);
        return view('backend.chapter.list', compact('title', 'chapters', 'mangaId', 'mangaName'));
    }
    public function getAdd($mangaId)
    {
        if ($mangaId) {
            $title = 'Thêm chương mới';
            return view('backend.chapter.addForm', compact('title', 'mangaId'));
        }
        // return 404 nếu không có mangaId
        // return view('backend.manga.addForm', compact('title'));
    }
    public function getTempChapter(Request $request)
    {
        $chapterId = $request->chapterId;
        $validated = $request->validate;
        $res = [];
        $method = $request->method;
        // $request = Request::capture();
        //! đổ dữ liệu hình khi edit lần đầu. Lấy trực tiếp từ public/chapter
        //! validate fail đổ dữ liệu lại từ temp.
        //! Khi upload sẽ clear public/chapter reupload từ temp

        if ($chapterId) {
            if ($method == 'edit') {
                if (Storage::exists("public/chapter/$chapterId")) {
                    $files = Storage::allFiles("public/chapter/$chapterId");
                }
                if ($validated == 'true') {
                    $files = Storage::allFiles("public/tempChapter/$chapterId");
                }
                // else return false
            } else {
                if (Storage::exists("public/tempChapter/$chapterId")) {
                    $files = Storage::allFiles("public/tempChapter/$chapterId");
                }
            }
            foreach ($files as $file) {
                $res[] = str_replace('public', '/storage',  $file);;
            }
            header('Content-type: application/json');
            echo json_encode($res);
        }
    }
    public function postAdd($mangaId, Request $request)
    {
        //! clean all the file before upload
        // * Store chapter into temp with folder name = Id of chapter
        $image = $request->file('file');
        if ($image) {
            if ($request->chapterId) {
                $chapterId = $request->chapterId;
            } else {
                $chapter = new Chapter();
                $chapter->name = '';
                $chapter->slug = '';
                $chapter->active = 1;
                $chapter->status = 1;
                $chapter->manga_id = $mangaId;
                $chapter->save();
                $chapterId = $chapter->id;
            }
            if (Storage::exists("public/tempChapter/$chapterId")) {
                Storage::deleteDirectory("public/tempChapter/$chapterId");
            }
            foreach ($image as $key => $value) {
                $imageName = $key + 1 . '.' . $value->extension();
                $store = $value->storeAs(
                    //? lastId +1
                    "tempChapter/$chapterId",
                    $imageName,
                    'public'
                );
            }
            echo $chapterId;
        }
        //!example for get last modified time of image
        // $image_path = 'storage/cover/default.jpg';
        // // Convert the relative path to an absolute file path on the server
        // $public_dir = public_path();
        // $file_path = $public_dir . '/' . $image_path;
        // // Get the last modified time of the file
        // $last_modified_timestamp = filemtime($file_path);
        // $last_modified_date = date('Y-m-d H:i:s', $last_modified_timestamp);
        // dd($last_modified_date);
        //!example for get last modified time of image
        if ($request->chapterId && !$image) {
            if ($request->chapterId == null) {
                return redirect()->route('admin.chapter.addForm', $mangaId)->with(
                    'msg',
                    'Vui lòng kiểm tra lại dữ liệu'
                );
            } else {
                $request->validate(
                    [
                        'txtChapterName' => 'required|max:250|unique:chapters,name',
                        'slChapterActive' => 'required',
                        'txtChapterSlug' => 'required|max:250|unique:chapters,slug',
                    ],
                    [
                        'txtChapterName.required' => ':attribute không được bỏ trống',
                        'txtChapterName.max' => ':attribute tối đa :max kí tự',
                        'txtChapterName.unique' => ':attribute đã tồn tại',
                        'slChapterActive.required' => ':attribute không được bỏ trống',
                        'txtChapterSlug.required' => ':attribute không được bỏ trống',
                        'txtChapterSlug.max' => ':attribute tối đa :max kí tự',
                        'txtChapterSlug.unique' => ':attribute đã tồn tại',
                    ],
                    [
                        'txtChapterName' => 'Tên chương truyện',
                        'slChapterActive' => 'Trạng thái chương truyện',
                        'txtChapterSlug' => 'Slug',
                    ]
                );
            }
            $chapter = Chapter::find($request->chapterId);
            $chapter->name = $request->txtChapterName;
            $chapter->active = $request->slChapterActive;
            $chapter->status = 2;
            $chapter->slug = $request->txtChapterSlug;
            $chapter->save();
            if ($chapter->id) {
                //! xử lý của page model
                // $absolutePath = Storage::path("public/tempChapter/$chapter->id");
                $files = Storage::allFiles("public/tempChapter/$chapter->id");
                foreach ($files as $file) {
                    $page = new Page();
                    $page->chapter_id = $chapter->id;
                    $page->name = basename($file);
                    $page->save();
                    if ($page->id) {
                        Storage::move($file, "public/chapter/$chapter->id/" . basename($file));
                    }
                }
                $isDirectoryEmpty = count(Storage::allFiles("public/tempChapter/$chapter->id")) == 0;
                if ($isDirectoryEmpty) {
                    Storage::deleteDirectory("public/tempChapter/$chapter->id");
                }
                return redirect()->route('admin.chapter.list', $mangaId)->with('msg', 'thêm chương mới thành công');
            } else {
                return redirect()->route('admin.chapter.list', $mangaId)->with('msg', 'Đã xảy ra lỗi');
            }
        }
        // // // return 404;
    }
    public function getEdit(Chapter $chapter)
    {
        $title = 'Edit chapter';
        return view('backend.chapter.editForm', compact('title', 'chapter'));
    }
    public function postEdit(Request $request, Chapter $chapter)
    {
        //! clean all the file before upload
        // * Store chapter into temp with folder name = Id of chapter
        $image = $request->file('file');
        if ($image && $request->chapterId) {
            $chapterId = $request->chapterId;
            if (Storage::exists("public/tempChapter/$chapterId")) {
                Storage::deleteDirectory("public/tempChapter/$chapterId");
            }
            foreach ($image as $key => $value) {
                $imageName = $key + 1 . '.' . $value->extension();
                $store = $value->storeAs(
                    //? lastId +1
                    "tempChapter/$chapterId",
                    $imageName,
                    'public'
                );
            }
            echo $chapterId;
        }
        //!example for get last modified time of image
        // $image_path = 'storage/cover/default.jpg';
        // // Convert the relative path to an absolute file path on the server
        // $public_dir = public_path();
        // $file_path = $public_dir . '/' . $image_path;
        // // Get the last modified time of the file
        // $last_modified_timestamp = filemtime($file_path);
        // $last_modified_date = date('Y-m-d H:i:s', $last_modified_timestamp);
        // dd($last_modified_date);
        //!example for get last modified time of image
        if ($request->chapterId && !$image) {
            if ($request->chapterId == null) {
                return redirect()->route('admin.chapter.addForm', $$chapter->manga_id)->with(
                    'msg',
                    'Vui lòng kiểm tra lại dữ liệu'
                );
            } else {
                try {
                    // validate the form data
                    $validatedData = $request->validate([
                        'txtChapterName' => 'required|max:250|unique:chapters,name,' . $request->chapterId,
                        'slChapterActive' => 'required',
                        'txtChapterSlug' => 'required|max:250|unique:chapters,slug,' . $request->chapterId,
                    ], [
                        'txtChapterName.required' => ':attribute không được bỏ trống',
                        'txtChapterName.max' => ':attribute tối đa :max kí tự',
                        'txtChapterName.unique' => ':attribute đã tồn tại',
                        'slChapterActive.required' => ':attribute không được bỏ trống',
                        'txtChapterSlug.required' => ':attribute không được bỏ trống',
                        'txtChapterSlug.max' => ':attribute tối đa :max kí tự',
                        'txtChapterSlug.unique' => ':attribute đã tồn tại',
                    ], [
                        'txtChapterName' => 'Tên chương truyện',
                        'slChapterActive' => 'Trạng thái chương truyện',
                        'txtChapterSlug' => 'Slug',
                    ]);
                    $chapter = Chapter::find($request->chapterId);
                    $chapter->name = $request->txtChapterName;
                    $chapter->active = $request->slChapterActive;
                    $chapter->status = 2;
                    $chapter->slug = $request->txtChapterSlug;
                    $chapter->save();
                    if ($chapter->id) {
                        //! xử lý của page model
                        if ($page = Page::whereIn('chapter_id', [$chapter->id])->delete()) {
                            Storage::deleteDirectory("public/chapter/$chapter->id");
                        };

                        // $absolutePath = Storage::path("public/tempChapter/$chapter->id");
                        $files = Storage::allFiles("public/tempChapter/$chapter->id");
                        foreach ($files as $file) {
                            $page = new Page();
                            $page->chapter_id = $chapter->id;
                            $page->name = basename($file);
                            $page->save();
                            if ($page->id) {
                                Storage::move($file, "public/chapter/$chapter->id/" . basename($file), 'force');
                            }
                        }
                        $isDirectoryEmpty = count(Storage::allFiles("public/tempChapter/$chapter->id")) == 0;
                        if ($isDirectoryEmpty) {
                            Storage::deleteDirectory("public/tempChapter/$chapter->id");
                        }
                        return redirect()->route('admin.chapter.list', $chapter->manga_id)->with('msg', 'thêm chương mới thành công');
                    } else {
                        return redirect()->route('admin.chapter.list', $chapter->manga_id)->with('msg', 'Đã xảy ra lỗi');
                    }
                } catch (\Illuminate\Validation\ValidationException $e) {
                    // if validation fails, set the value of "validated" to true
                    return redirect()->back()->withInput(['validated' => 'true'])->withErrors($e->validator);
                }
            }
        }
    }
    public function delete(Chapter $chapter)
    {
        $mangaId = $chapter->manga_id;
        if ($chapter->exists()) {
            if ($chapter->delete()) {
                Storage::deleteDirectory("public/chapter/$chapter->id");
                return redirect()->route('admin.chapter.list', $mangaId)->with('msg', 'Xóa thành công');
            }
        }
        return redirect()->route('admin.chapter.list', $mangaId)->with('msg', 'Đã có lỗi xảy ra');
    }
}
