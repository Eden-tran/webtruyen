<!DOCTYPE html>
<html lang="en">
@include('frontend.block.header')

<body>
    <!-- start navbar -->
    @include('frontend.block.navbar')
    <!-- end navbar-->

    <!-- start slider -->
    <!-- end slider -->

    <!-- start lastest -->
    @yield('content')
    <!-- end lastest -->

    <!-- start footer -->
    @include('frontend.block.footer')
    <!-- end footer -->

    <!-- js files -->
    @include('frontend.block.js')
</body>

</html>
