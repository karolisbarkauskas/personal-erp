<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') {{ config('app.name', 'Laravel') }}</title>
    <meta name="robots" content="noindex,nofollow">

    <link href="{{ asset('css/dashforge.css?v=1') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('lib/dataTables/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ mix('lib/dataTables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ mix('lib/dataTables/searchBuilder.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/sweetalert2/sweetalert2.css') }}">
    <link rel="stylesheet" href="  https://cdn.datatables.net/datetime/1.1.1/css/dataTables.dateTime.min.css">
    <link href="{{ asset('css/custom.css') }}?a" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('images/favicon/custom.css') }}">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    @stack('styles')
    @livewireStyles
</head>
<body class="mn-ht-100v d-flex flex-column">
<header class="navbar navbar-header navbar-header-fixed">
    <a href="" id="mainMenuOpen" class="burger-menu"><i data-feather="menu"></i></a>
    <div class="navbar-brand">
        <a href="{{ route('home') }}" class="df-logo" style="color: #0057b7 !important;display: grid">
            <img src="{{ asset('images/invoyer.png') }}" alt="invoyer" width="100" />
            @if(auth()->user() && auth()->user()->isRoot())
                <small style="font-size: 13px;letter-spacing: 0" class="tx-black"><em>{{ auth()->user()->company->name }}</em></small>
            @endif
        </a>
    </div>
    <div id="navbarMenu" class="navbar-menu-wrapper">
        <div class="navbar-menu-header">
            <img src="{{ asset('images/invoyer.png') }}" alt="invoyer">
            <a id="mainMenuClose" href=""><i data-feather="x"></i></a>
        </div>
        @auth()
            <ul class="nav navbar-menu">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link">Main</a>
                </li>
                @if(auth()->user()->isRoot())
                    <li class="nav-item with-sub">
                        <a href="" class="nav-link">
                            Expenses
                        </a>
                        <ul class="navbar-menu-sub">
                            <li class="nav-sub-item">
                                <a href="{{ route('recurring-expenses.index') }}" class="nav-sub-link">
                                    Recurring expenses list
                                </a>
                            </li>
                            <li class="nav-sub-item">
                                <a href="{{ route('expenses-categories.index') }}" class="nav-sub-link">
                                    Expenses categories
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item with-sub">
                        <a href="" class="nav-link">
                            Income
                        </a>
                        <ul class="navbar-menu-sub">
                            <li class="nav-sub-item">
                                <a href="{{ route('income.index') }}" class="nav-sub-link">
                                    Income
                                </a>
                            </li>
                            <li class="nav-sub-item">
                                <a href="{{ route('service.index') }}" class="nav-sub-link">
                                    Services
                                </a>
                            </li>
                            <li class="nav-sub-item">
                                <a href="{{ route('recurring-income.index') }}" class="nav-sub-link">
                                    Recuring income
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('clients.index') }}" class="nav-link">Clients</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('payments.index') }}" class="nav-link text-danger">Payments</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('expenses.index') }}" class="nav-link text-danger">
                            Expenses list
                        </a>
                    </li>
                @endif

            </ul>
        @endauth
    </div>
    @auth
        <div class="navbar-right">
            <div class="dropdown dropdown-profile">
                <a href="" class="dropdown-link" data-toggle="dropdown" data-display="static">
                    <div class="avatar avatar-sm">
                        <img
                            src="https://tasks.onesoft.io/proxy.php?proxy=avatar&module=system&v=5.11.23&b=DEV&user_id={{ Auth::user()->collab_user }}&size=256"
                            class="rounded-circle" alt="">
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right tx-13">
                    <h6 class="tx-semibold mg-b-5">{{ Auth::user()->name }}</h6>

                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                          style="display: none;">
                        @csrf
                    </form>

                </div>
            </div>
        </div>
    @endauth
</header>

<div class="content content-fixed">
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container-fluid pd-x-0 pd-lg-x-10 pd-xl-x-0">
        @yield('content')
    </div>
</div>

<script src="{{ asset('lib/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('lib/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('lib/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('js/dashforge.js') }}"></script>
<script src="{{ asset('lib/dataTables/dataTables.dataTables.min.js') }}"></script>
<script src="{{ asset('lib/sweetalert2/sweetalert2.js') }}"></script>
<script src="https://cdn.datatables.net/datetime/1.1.1/js/dataTables.dateTime.min.js"></script>
{{--<script src="{{ asset('lib/dataTables/dataTables.rowReorder.js') }}"></script>--}}
<script src="{{ asset('lib/dataTables/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('lib/dataTables/dataTables.fixedHeader.js') }}"></script>
<script src="{{ asset('lib/dataTables/searchBuilder.dataTables.min.js') }}"></script>
<script src="{{ asset('lib/dataTables/dataTables.searchBuilder.min.js') }}"></script>
<script src="{{ asset('lib/axios/axios.min.js') }}"></script>
@yield('javascript')
@stack('javascript')
@livewireScripts
<script src="{{ asset('lib/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('lib/select2/js/i18n/lt.js') }}"></script>
<script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>

@if(!request()->route()->named('week.edit'))
    <script type="text/javascript">
        $(document).ready(function () {
            $('select').select2({
                language: "lt"
            });
            $('.datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 2
            });
        });
    </script>
@endif
</body>
</html>
