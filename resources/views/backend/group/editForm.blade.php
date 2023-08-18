@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('backend.block.navbar')
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ $title }}</div>
                    <div class="card-body">
                        @if (session('msg'))
                            <div class="alert alert-success text-center">{{ session('msg') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger text-center">Dữ liệu không hợp lệ vui lòng nhập lại</div>
                        @endif
                        <form action="{{ route('admin.group.postEdit', $group->id) }}" method="POST">
                            <div class="mb-3">
                                <label for="txtGroupName" class="form-label">Tên</label>`
                                <input type="text" class="form-control" name="txtGroupName" id="txtGroupName"
                                    value='{{ old('txtGroupName', $group->name) }}'>
                                @error('txtGroupName')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="txtGroupDescrbie" class="form-label">Miêu tả</label>
                                <input type="text" class="form-control" name="txtGroupDescribe" id="txtGroupDescribe"
                                    value='{{ old('txtGroupDescribe', $group->describe) }}'>
                                @error('txtGroupDescribe')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="slGroupStatus" class="form-label">Trạng Thái</label>
                                <select class="form-select" name="slGroupStatus">
                                    <option selected disabled>Hãy chọn trạng thái</option>
                                    <option value=1 {{ old('slGroupStatus', $group->active) == 1 ? 'selected' : false }}>
                                        không kích
                                        hoạt
                                    </option>
                                    <option value=2 {{ old('slGroupStatus', $group->active) == 2 ? 'selected' : false }}>
                                        kích
                                        hoạt
                                    </option>
                                </select>
                                @error('slGroupStatus')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            @can('group.decentralize')
                                <div class="mb-3">
                                    @if ($module->count() > 0)
                                        <table class="table">
                                            <tr>
                                                <th>Tên module</th>
                                                <th>Action</th>
                                            </tr>
                                            @foreach ($module as $item)
                                                <tr>
                                                    <td>
                                                        {{ $item->title }}
                                                    </td>
                                                    <td>
                                                        @if (!empty($roleArr))
                                                            @foreach ($roleArr as $roleName => $roleLabel)
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="role{{ $item->name . $roleName }}"
                                                                        name="role[{{ $item->name }}][]"
                                                                        value="{{ $roleName }}"
                                                                        {{ isRole(old('role', $groupRoleArr), $item->name, $roleName) ? 'checked' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="role{{ $item->name . $roleName }}">{{ $roleLabel }}</label>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                        @if ($item->name == 'Group')
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="role{{ $item->name }}Decentralize"
                                                                    name="role[{{ $item->name }}][]" value="Decentralize"
                                                                    {{ isRole(old('role', $groupRoleArr), $item->name, 'Decentralize') ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="role{{ $item->name }}Decentralize">Phân
                                                                    Quyền</label>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>

                                        @error('role')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    @endif
                                </div>
                            @endcan

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                @csrf
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
