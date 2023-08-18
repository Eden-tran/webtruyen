@extends('layouts.frontend')
@section('title')
    {{ $title }}
@endsection
@section('content')
    @include('frontend.block.slider')

    <div class="lastest container mt-4 mt-sm-5">
        <div class="row">
            <div class="col-lg-6">
                <h2 class="font-weight-bolder float-left">Lastest Manga Updates</h2>
            </div>
            <div class="col-lg-6">
                <ul class="calendar list-unstyled list-inline float-right font-weight-bold mt-3 mt-sm-0">
                    <li class="list-inline-item active">Today</li>
                    <li class="list-inline-item">Yesterday</li>
                    <li class="list-inline-item">Sun</li>
                    <li class="list-inline-item">Fri</li>
                    <li class="list-inline-item">Thur</li>
                    <li class="list-inline-item">Wed</li>
                </ul>
            </div>
        </div>

        <div class="posts row">
            <div class="col-lg-2 col-md-3 col-sm-4">
                <div class="card mb-3">
                    <a href="details.html"><img src="{{ asset('FE') }}/img/cover1.jpg" class="card-img-top"
                            alt=""></a>
                    <div class="over text-center">
                        <div class="head text-left">
                            <h6>One Piece</h6>
                        </div>
                        <div class="about-list">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th scope="row">Genre:</th>
                                        <td>Sport</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Artist:</th>
                                        <td>Jacob ZFCon</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Update:</th>
                                        <td>VOL. 125</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="about text-muted">
                            efficitur eu tortor. Nam et odio aliquet.
                        </p>
                        <a class="reading btn" href="details.html">Start reading VOL. 1</a>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><a href="details.html">One Piece</a></h5>
                        <p class="card-text">CH. 2</p>
                        <p class="card-text"><small class="text-muted text-uppercase">Update 1 Hour ago</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
