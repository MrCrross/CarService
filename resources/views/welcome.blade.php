@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center"><h1>{{ 'Добро пожаловать в '.config('app.name', 'Автосервис') }}</h1></div>
                <div class="card-body">
                    <h2>Ваших прав достаточно, чтобы посетить следующие страницы:</h2>
                    @can('order-list')
                        <h3><a class="nav-link" href="{{ route('orders.index') }}">Выполнить заказ</a></h3>
                    @endcan
                    @can('customer-list')
                        <h3><a class="nav-link" href="{{ route('customers.index') }}">Управлять клиентской базой</a></h3>
                    @endcan
                    @can('worker-list')
                        <h3><a class="nav-link" href="{{ route('workers.index') }}">Управлять сотрудниками</a></h3>
                    @endcan
                    @can('role-list')
                        <h3><a class="nav-link" href="{{ route('roles.index') }}">Управлять ролями</a></h3>
                    @endcan
                    @can('user-list')
                        <h3><a class="nav-link" href="{{ route('users.index') }}">Управлять пользователями</a></h3>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
