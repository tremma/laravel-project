@extends('layout')

@section('title')
{{ $user->name }} | Proile
@endsection

@section('styles')
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="/css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="/css/app.bundle.css">
    <link id="myskin" rel="stylesheet" media="screen, print" href="/css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="/css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="/css/fa-brands.css">
    <link rel="stylesheet" media="screen, print" href="/css/fa-regular.css">
@endsection

@section('content')
<body class="mod-bg-1 mod-nav-link">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">
            <a class="navbar-brand d-flex align-items-center fw-500" href="{{ route('home') }}"><img alt="logo" class="d-inline-block align-top mr-2" src="/img/logo.png"> Учебный проект</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarColor02">

                <ul class="navbar-nav mr-auto">
                    <li class="nav-item ">
                        <a class="nav-link" href="{{ route('home') }}">Главная</a>
                    </li>
                </ul>


                <ul class="navbar-nav ml-auto">
                     @if(auth()->check())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile') . '/' . auth()->user()->id }} ">Вы вошли как {{ auth()->user()->name }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}">Выйти</a>
                        </li>

                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Войти</a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
        <main id="js-page-content" role="main" class="page-content mt-3">
            <div class="subheader">
                <h1 class="subheader-title">
                    <i class='subheader-icon fal fa-user'></i> {{ $user->name }}
                </h1>
            </div>
            <div class="row">
              <div class="col-lg-6 col-xl-6 m-auto">
                    <!-- profile summary -->
                    <div class="card mb-g rounded-top">
                        <div class="row no-gutters row-grid">
                            <div class="col-12">
                                <div class="d-flex flex-column align-items-center justify-content-center p-4">
                                    <img src="/{{ $user->avatar }}" class="rounded-circle shadow-2 img-thumbnail" alt="">
                                    <h5 class="mb-0 fw-700 text-center mt-3">
                                    {{ $user->name }} 
                                        <small class="text-muted mb-0">{{ $user->job_title }}</small>
                                    </h5>
                                    <div class="mt-4 text-center demo">
                                        <a href="https://instagram.com/{{ $user->instagram }}" class="fs-xl" style="color:#C13584">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                        <a href="https://vk.com/{{ $user->vk }}" class="fs-xl" style="color:#4680C2">
                                            <i class="fab fa-vk"></i>
                                        </a>
                                        <a href="https://telegram.com/{{ $user->telegram }}" class="fs-xl" style="color:#0088cc">
                                            <i class="fab fa-telegram"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3 text-center">
                                    <a href="tel:{{ $user->phone }}" class="mt-1 d-block fs-sm fw-400 text-dark">
                                        <i class="fas fa-mobile-alt text-muted mr-2"></i> {{ $user->phone }}</a>
                                    <a href="mailto:{{ $user->email }}" class="mt-1 d-block fs-sm fw-400 text-dark">
                                        <i class="fas fa-mouse-pointer text-muted mr-2"></i> {{ $user->email }}</a>
                                    <address class="fs-sm fw-400 mt-4 text-muted">
                                        <i class="fas fa-map-pin mr-2"></i> {{ $user->address }}
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
               </div>
            </div>
        </main>
    </body>

    <script src="/js/vendors.bundle.js"></script>
    <script src="/js/app.bundle.js"></script>
    <script>
        $(document).ready(function()
        {
        });
    </script>
@endsection