@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('backend.block.navbar')
            <div class="col-md-10">
                <div class="card">
                    {{-- <div class="card-header text-center d-flex justify-content-start">
                    </div> --}}
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span class="align-baseline-middle">{{ $title }}</span>

                        <a href="{{ route('admin.user.getAdd') }}" class="btn btn-primary btn-md">
                            Thêm người dùng
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('msg'))
                            <div class="alert alert-success text-center">{{ session('msg') }}</div>
                        @endif
                        <form class="d-flex">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Tên</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Vai trò</th>
                                    <th scope="col">Trang thái</th>
                                    <th scope="col">Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($list)
                                    @foreach ($list as $key => $item)
                                        <tr>
                                            <td class="align-middle">{{ $key + 1 }}</td>
                                            <td class="align-middle">{{ $item->name }}</td>
                                            <td class="align-middle">{{ $item->email }}</td>

                                            <td class="align-middle">{{ $item->group->name ?? '' }}</td>
                                            <td class="align-middle">{!! $item->active == 1
                                                ? '<button class="btn btn-sm btn-success">Active</button>'
                                                : '<button class="btn btn-sm btn-danger">Inactive</button>' !!}
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('admin.user.getEdit', $item->id) }}"><button
                                                        class="btn btn-sm btn-danger ">Sửa</button></a>
                                                @if (Auth::user()->id != $item->id)
                                                    <a href="{{ route('admin.user.delete', $item->id) }}"
                                                        onclick="return confirm('bạn có chắc muốn xóa')"><button
                                                            class="btn btn-sm btn-danger ">Xóa</button></a>
                                                @endif

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
