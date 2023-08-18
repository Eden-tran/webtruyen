<nav class="navbar navbar-expand-lg navbar-light shadow py-2 py-sm-0">
    <a class="navbar-brand" href="#">
        <h5>Manga Man</h5>
    </a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="container-fluid">
            <div class="row py-3">
                <div class="col-lg-6 col-sm-12 mb-3 mb-sm-0">
                    <ul class="navbar-nav mr-auto">
                        <!-- always use single word for li -->
                        <li class="nav-item active">
                            <a class="nav-link" href="#">Home<span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">New</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Populer</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Browse
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col">
                    <form class="form-inline search">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Type Title, auther or genre"
                                aria-label="Type Title, auther or genre">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button"><i
                                        class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="profile float-right">
        <div class="saved">
            <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <i class="fa fa-bookmark fa-2x"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#">
                    <div class="row">
                        <div class="col"><img src="{{ asset('FE') }}/img/cover1.jpg" width="50"></div>
                        <div class="col">
                            <h5>One piece 1</h5>
                            <small>Lastest <span>VOL. 11</span></small>
                        </div>
                    </div>
                </a>
                <a class="dropdown-item" href="#">
                    <div class="row">
                        <div class="col"><img src="{{ asset('FE') }}/img/cover1.jpg" width="50"></div>
                        <div class="col">
                            <h5>One piece 1</h5>
                            <small>Lastest <span>VOL. 11</span></small>
                        </div>
                    </div>
                </a>
                <hr>
                <a class="dropdown-item" href="#">View all saved mangas (14)</a>
            </div>
        </div>
        <div class="account">
            <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <i class="fa fa-user-circle fa-2x"></i><i class="fa fa-angle-down"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#">My account</a>
                <a class="dropdown-item" href="#">logout</a>
            </div>
        </div>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
</nav>
