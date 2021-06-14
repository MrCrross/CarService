@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header text-center"><h1>{{ 'Добро пожаловать в '.config('app.name', 'Автосервис') }}</h1></div>
        <div class="card-body">
            <h3>Ваших прав достаточно, чтобы выполнить следующее:</h3>
            <div class="btn-group w-100">
                @can('order-list')
                    <a class="btn btn-outline-primary" href="{{ route('orders.index') }}">Выполнить заказ</a>
                @endcan
                @can('customer-edit')
                    <a class="btn btn-outline-primary" href="{{ route('customers.index') }}">Управлять клиентской базой</a>
                @endcan
                @can('worker-edit')
                    <a class="btn btn-outline-primary" href="{{ route('workers.index') }}">Управлять сотрудниками</a>
                @endcan
                @can('material-edit')
                    <a class="btn btn-outline-primary" href="{{ route('materials.index') }}">Управлять материалами</a>
                @endcan
                @can('role-list')
                    <a class="btn btn-outline-primary" href="{{ route('roles.index') }}">Управлять ролями</a>
                @endcan
                @can('user-list')
                    <a class="btn btn-outline-primary" href="{{ route('users.index') }}">Управлять пользователями</a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
