@extends('layouts.app')

@section('content')

@if (isset($success))
    <div class="alert alert-success">
        <p>{{ $success }}</p>
    </div>
@endif
@if (isset($error))
    <div class="alert alert-danger">
        <p>{{ $error }}</p>
    </div>
@endif
<div class="row">
    <div class="col-lg-12 mb-2">
        <div class="float-start">
            <h2>Автомобили</h2>
        </div>
        <div class="float-end">
            <div class="btn-group">
                @can('firm-create')
                    <button class="btn btn-success" data-toggle="modal" data-target="#addFirm">Добавить фирму</button>
                @endcan
                @can('model-create')
                    <button class="btn btn-success" data-toggle="modal" data-target="#addModel">Добавить модель</button>
                @endcan
                <a class="btn btn-success" href="{{asset('customers')}}">Добавить клиента</a>
            </div>
        </div>
    </div>
</div>
<table class="table table-bordered table-hover">
    <tr>
        <th>№</th>
        <th>Модель</th>
        <th>Клиент</th>
        <th>Государственный номер</th>
    </tr>
    @can('model-create')
    <tr>
        <td></td>
        {{ Form::open(array('action' => 'App\Http\Controllers\CarController@create','method'=>'post')) }}
        <td>
            <div class="input-group">
                <select class="form-select" name="model">
                    @foreach ($models as $model)
                        <option value="{{$model->id}}">{{$model->firm->name.' '.$model->name.' '.$model->year_release}}</option>
                    @endforeach
                </select>
                @can('model-edit')
                    <span class="input-group-text" data-toggle="modal" data-target="#addModel">&#128393;</span>
                @endcan
            </div>
        </td>
        <td>
            <div class="input-group">
                <select class="form-select" name="customer">
                    @foreach ($customers as $customer)
                        <option value="{{$customer->id}}">{{$customer->first_name." ".$customer->last_name." ".$customer->father_name}}</option>
                    @endforeach
                </select>
                @can('customer-list')
                    <a href="{{asset('customers')}}"> <img class="icon" src="{{asset('image/user-plus.svg')}}" alt="Добавить пользователя"></a>
                @endcan
            </div>
        </td>
        <td>
            <input class="form-control" name="state" type="text" pattern="[АВЕКМНОРСТУХ]{1}[0-9]{3}[АВЕКМНОРСТУХ]{2}[0-9]{2}" placeholder="Введите гос номер (С189АН78)" required>
        </td>
        <td>
            {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
        </td>
        {{ Form::close() }}
    </tr>
    @endcan
    @foreach ($cars as $key => $car)
    <tr>
        {{ Form::open(array('action' => 'App\Http\Controllers\CarController@update','method'=>'patch')) }}
        <td>{{ ++$key }}
            <input class="visually-hidden" name="id" value="{{ $car->id }}" readonly>
        </td>
        <td><span>{{ $car->model->firm->name.' '.$car->model->name.' '.$car->model->year_release}}</span>
            @can('model-edit')
            <span class="car-edit">&#128393;</span>
            <div class="input-group visually-hidden">
                <select class="form-select" name="model">
                    @foreach ($models as $model)
                        @if ($model->id == $car->model->id)
                        <option value="{{$model->id}}" selected>{{$model->firm->name.' '.$model->name.' '.$model->year_release}}</option>
                        @else
                        <option value="{{$model->id}}">{{$model->firm->name.' '.$model->name.' '.$model->year_release}}</option>
                        @endif
                    @endforeach
                </select>
                @can('model-create')
                <span class="input-group-text" data-toggle="modal" data-target="#add">&#128393;</span>
                @endcan
            </div>
            @endcan</td>
        <td>
            <span>{{$car->customer->first_name." ".$car->customer->last_name." ".$car->customer->father_name}}</span>
            @can('customer-edit')
            <span class="car-edit">&#128393;</span>
            <div class="input-group visually-hidden">
                <select class="form-select" name="customer">
                    @foreach ($customers as $customer)
                        @if ($customer->id == $car->customer->id)
                        <option value="{{$customer->id}}" selected>{{$customer->first_name." ".$customer->last_name." ".$customer->father_name}}</option>
                        @else
                        <option value="{{$customer->id}}">{{$customer->first_name." ".$customer->last_name." ".$customer->father_name}}</option>
                        @endif
                    @endforeach
                </select>
                @can('customer-edit')
                <a href="{{asset('customers')}}"> <img class="icon" src="{{asset('image/user-plus.svg')}}" alt="Добавить пользователя"></a>
                @endcan
            </div>
            @endcan
        </td>
        <td><span>{{ $car->state_number }}</span>
            @can('customer-edit')
            <span class="car-edit">&#128393;</span>
            <div class="input-group visually-hidden">
                <input class="form-control" name="state" type="text"  value="{{ $car->state_number }}" pattern="[АВЕКМНОРСТУХ]{1}[0-9]{3}[АВЕКМНОРСТУХ]{2}[0-9]{2}">
            </div>
            @endcan</td>
        <td>
            <div class="input-group">
                @can('customer-edit','model-edit')
                    {{Form::submit('&#10003;',array('class'=>'input-group-text btn btn-primary'))}}
                @endcan
                @can('customer-delete')
                    <button class="form-control btn btn-danger car-delete" type="button" data-toggle="modal" data-target="#deleteCar">&times;</button>
                @endcan
            </div>
        </td>
        {{ Form::close() }}
    </tr>
    @endforeach
</table>

@include('layouts.modal')

<script src="{{ asset('js/car.js') }}"></script>
@endsection
