<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Автосервис') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel bg-menu">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Автосервис') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto"></ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @auth
                            @can('order-list')
                                <li><a class="nav-link" href="{{ route('orders.index') }}">Заказы</a></li>
                            @endcan
                            @canany(['customer-edit','worker-edit','material-edit'])
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        База <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        @can('customer-edit')
                                            <li><a class="nav-link" href="{{ route('customers.index') }}">Клиенты</a></li>
                                        @endcan
                                        @can('worker-edit')
                                            <li><a class="nav-link" href="{{ route('workers.index') }}">Сотрудники</a></li>
                                        @endcan
                                        @can('work-edit')
                                            <li><a class="nav-link" href="{{ route('workers.works') }}">Работы</a></li>
                                        @endcan
                                        @can('material-edit')
                                            <li><a class="nav-link" href="{{ route('materials.index') }}">Материалы</a></li>
                                        @endcan
                                        @can('order-edit')
                                            <li><a class="nav-link" href="{{ route('orders.calc') }}">Доходы</a></li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            @canany(['user-list','role-list'])
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Пользователи <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    @can('role-list')
                                        <li><a class="dropdown-item nav-link" href="{{ route('roles.index') }}">Роли</a></li>
                                    @endcan
                                    @can('user-list')
                                        <li><a class="dropdown-item nav-link" href="{{ route('users.index') }}">Пользователи</a></li>
                                    @endcan
                                </ul>
                            </li>
                            @endcanany

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Выйти') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            <div class="container">
            @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
