@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('backend.block.navbar')
            <div class="col-md-10">
                <div class="card ">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span class="align-baseline-middle">{{ $title }}</span>
                        <a href="{{ route('admin.chapter.getAdd', $mangaId) }}" class="btn btn-primary btn-md">
                            Thêm chương
                        </a>
                    </div>

                    <div class="card-body">
                        @if (session('msg'))
                            <div class="alert alert-success text-center">{{ session('msg') }}</div>
                        @endif
                        <table class="table">
                            <thead>
                                <div class="d-flex justify-content-center py-2">
                                    <span style="font-size: 3rem;">{{ $mangaName }}</span>
                                </div>
                                <tr>
                                    <th scope="col">STT</th>
                                    <th scope="col">Tên</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($chapters)
                                    @foreach ($chapters as $key => $item)
                                        <tr>
                                            <td class='align-middle'>{{ $key + 1 }}</td>
                                            <td class='align-middle'>
                                                {{ $item->name }}
                                            </td>
                                            <td class='align-middle'>{!! $item->active == 1
                                                ? '<button class="btn btn-sm btn-danger">Inactive</button>'
                                                : '<button class="btn btn-sm btn-success">Active</button>' !!}
                                            </td>
                                            <td class='align-middle'>
                                                <a href="{{ route('admin.chapter.getEdit', $item->id) }}"><button
                                                        class="btn btn-sm btn-danger ">Sửa</button></a>
                                                <a href="{{ route('admin.chapter.delete', $item->id) }}"
                                                    onclick="return confirm('bạn có chắsc muốn xóa')"><button
                                                        class="btn btn-sm btn-danger ">Xóa</button></a>

                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
