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
            <h2>Клиенты
                @can('customer-print')
                    <button class="btn" onclick="print()"><img class="icon-sm" src="{{asset('image/print.svg')}}" alt="Распечатать"></button>
                @endcan
            </h2>
        </div>
        <div class="float-end">
            <div class="input-group">
                <input class="form-control" type="search" placeholder="Поиск">
                <button id="btnSearch" class="input-group-text"><img src="{{asset('image/search.svg')}}" alt="Найти" class="icon-sm"></button>
                <a href="{{asset('cars')}}" class="btn btn-success ms-2">Добавить автомобиль</a>
            </div>
        </div>
    </div>
</div>
<div id="print">
<table id="t-customer" class="table table-bordered table-hover">
    <tr>
        <th scope="col">№</th>
        <th scope="col">Фамилия</th>
        <th scope="col">Имя</th>
        <th scope="col">Отчество</th>
        <th scope="col">Телефон</th>
        <th scope="col">Автомобиль</th>
    </tr>
    <tr id="clone" class="visually-hidden">
        <th scope="row">
            <span></span>
            <input class="visually-hidden" name="id" value="" readonly required>
        </th>
        <td>
            <span class="lastName"></span>
            @can('customer-edit')
                <span class="customer-edit">&#128393;</span>
                <div class="input-group visually-hidden">
                    <input class="form-control" name="last_name" type="text" value="" pattern="^[A-Za-zА-Яа-яЁё]+$">
                    {{Form::submit('&#10003;',array('class'=>'input-group-text btn btn-primary'))}}
                </div>
            @endcan
        </td>
        <td>
            <span class="firstName"></span>
            @can('customer-edit')
                <span class="customer-edit">&#128393;</span>
                <div class="input-group visually-hidden">
                    <input class="form-control" name="first_name" type="text" value="" pattern="^[A-Za-zА-Яа-яЁё]+$">
                    {{Form::submit('&#10003;',array('class'=>'input-group-text btn btn-primary'))}}
                </div>
            @endcan</td>
        <td>
            <span class="fatherName"></span>
            @can('customer-edit')
                <span class="customer-edit">&#128393;</span>
                <div class="input-group visually-hidden">
                    <input class="form-control " name="father_name" type="text" value="" pattern="^[A-Za-zА-Яа-яЁё]+$">
                    {{Form::submit('&#10003;',array('class'=>'input-group-text btn btn-primary'))}}
                </div>
            @endcan</td>
        <td>
            <span class="phone"></span>
            @can('customer-edit')
                <span class="customer-edit">&#128393;</span>
                <div class="input-group visually-hidden">
                    <input class="form-control" name="phone" type="text" value="" pattern="^[0-9]+{11,}">
                    {{Form::submit('&#10003;',array('class'=>'input-group-text btn btn-primary'))}}
                </div>
            @endcan</td>
        <td class="w-25">
            <div id="customerCars" class="input-group">
                <input class="visually-hidden" type="text" name="car[]" value="" readonly>
                <span class="car form-control form-control-sm"></span>
                @can('customer-edit')
                    <span class="input-group-text customer-edit">&#128393;</span>
                    <div class="input-group visually-hidden">
                        <select class="form-select" name="model[]">
                        </select>
                        <input class="form-control" name="state[]" type="text"  value="" pattern="[АВЕКМНОРСТУХ]{1}[0-9]{3}[АВЕКМНОРСТУХ]{2}[0-9]{2}" placeholder="Госномер">
                        {{Form::submit('&#10003;',array('class'=>'input-group-text btn btn-primary model'))}}
                    </div>
                @endcan
            </div>
        </td>
        <td class="btns">
            <div class="btn-group">
                {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                @can('customer-delete')
                    <button class="btn btn-danger customer-delete" type="button" data-toggle="modal" data-target="#deleteCustomer">&times;</button>
                @endcan
            </div>
        </td>
    </tr>
    @can('customer-create')
    <tr id="create">
        <th scope="row"></th>
        {{ Form::open(array('action' => 'App\Http\Controllers\CustomerController@create','method'=>'post')) }}
        <td>
            <input class="form-control" name="last_name" type="text" pattern="^[A-Za-zА-Яа-яЁё]+$" placeholder="Введите фамилию клиента" required>
        </td>
        <td>
            <input class="form-control" name="first_name" type="text" pattern="^[A-Za-zА-Яа-яЁё]+$" placeholder="Введите имя клиента" required>
        </td>
        <td>
            <input class="form-control" name="father_name" type="text" pattern="^[A-Za-zА-Яа-яЁё]+$" placeholder="Введите отчество клиента" required>
        </td>
        <td>
            <input class="form-control" name="phone" type="text" pattern="^[0-9]+{11,}" placeholder="Введите номер телефона клиента" required>
        </td>
        <td>
            <div class="input-group">
                <select class="form-select" name="model">
                    @foreach ($models as $model)
                        <option value="{{$model->id}}">{{$model->firm->name.' '.$model->name.' '.$model->year_release}}</option>
                    @endforeach
                </select>
                <input class="form-control" name="state" type="text"  value="" pattern="[АВЕКМНОРСТУХ]{1}[0-9]{3}[АВЕКМНОРСТУХ]{2}[0-9]{2}" placeholder="Госномер">
            </div>
        </td>
        <td>
            {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
        </td>
        {{ Form::close() }}
    </tr>
    @endcan
    @foreach ($customers as $key => $customer)
    <tr class="tr-customer">
        {{ Form::open(array('action' => 'App\Http\Controllers\CustomerController@update','method'=>'patch')) }}
        <th scope="row">{{ ++$key }}
            <input class="visually-hidden" name="id" value="{{ $customer->id }}" readonly>
        </th>
        <td>{{ $customer->last_name }}
            @can('customer-edit')
                <span class="customer-edit">&#128393;</span>
                <div class="input-group visually-hidden">
                    <input class="form-control" name="last_name" type="text"  value="{{ $customer->last_name }}" pattern="^[A-Za-zА-Яа-яЁё]+$">
                    {{Form::submit('&#10003;',array('class'=>'input-group-text btn btn-primary'))}}
                </div>
            @endcan</td>
        <td>
            {{ $customer->first_name }}
            @can('customer-edit')
                <span class="customer-edit">&#128393;</span>
                <div class="input-group visually-hidden">
                    <input class="form-control" name="first_name" type="text"  value="{{ $customer->first_name }}" pattern="^[A-Za-zА-Яа-яЁё]+$">
                    {{Form::submit('&#10003;',array('class'=>'input-group-text btn btn-primary'))}}
                </div>
            @endcan</td>
        <td>{{ $customer->father_name }}
            @can('customer-edit')
            <span class="customer-edit">&#128393;</span>
            <div class="input-group visually-hidden">
                <input class="form-control " name="father_name" type="text"  value="{{ $customer->father_name }}" pattern="^[A-Za-zА-Яа-яЁё]+$">
                {{Form::submit('&#10003;',array('class'=>'input-group-text btn btn-primary'))}}
            </div>
            @endcan</td>
        <td>{{ $customer->phone }}
            @can('customer-edit')
            <span class="customer-edit">&#128393;</span>
            <div class="input-group visually-hidden">
                <input class="form-control" name="phone" type="text"  value="{{ $customer->phone }}" pattern="^[0-9]+{11,}">
                {{Form::submit('&#10003;',array('class'=>'input-group-text btn btn-primary'))}}
            </div>
            @endcan</td>
        <td class="w-25">
            @foreach ($customer->cars as $car)
                <div class="input-group">
                    <input class="visually-hidden" type="text" name="car[]" value="{{$car->id}}" readonly>
                    <span class="form-control form-control-sm">{{$car->model->firm->name.' '.$car->model->name.' '.$car->model->year_release.' '.$car->state_number}}</span>
                    @can('customer-edit')
                        <span class="input-group-text customer-edit">&#128393;</span>
                        <div class="input-group visually-hidden">
                            <select class="form-select" name="model[]">
                                @foreach ($models as $model)
                                    @if($model->id === $car->model->id)
                                        <option value="{{$model->id}}" selected>{{$model->firm->name.' '.$model->name.' '.$model->year_release}}</option>
                                    @else
                                        <option value="{{$model->id}}">{{$model->firm->name.' '.$model->name.' '.$model->year_release}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <input class="form-control" name="state[]" type="text"  value="{{$car->state_number}}" pattern="[АВЕКМНОРСТУХ]{1}[0-9]{3}[АВЕКМНОРСТУХ]{2}[0-9]{2}" placeholder="Госномер">
                            {{Form::submit('&#10003;',array('class'=>'input-group-text btn btn-primary model'))}}
                        </div>
                    @endcan
                </div>
            @endforeach
        </td>
        <td class="btns">
            <div class="btn-group">
                {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                @can('customer-delete')
                    <button class="btn btn-danger customer-delete" type="button" data-toggle="modal" data-target="#deleteCustomer">&times;</button>
                @endcan
            </div>
        </td>
        {{ Form::close() }}
    </tr>
    @endforeach
</table>
</div>
@include('layouts.modal')
<script src="{{ asset('js/print.js') }}"></script>
<script src="{{ asset('js/customer.js') }}"></script>
@endsection
