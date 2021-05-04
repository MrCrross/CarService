@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="float-start">
            <h2>Форма Заказ-наряд</h2>
            <div id="result" class="alert visually-hidden"></div>
        </div>
    </div>
</div>
{{ Form::open(array('action' => 'App\Http\Controllers\OrderController@create','method'=>'post','id'=>'createOrderForm')) }}
    <table class="table table-borderless">
        <tbody>
        <tr>
            <td>
                <div id="customer" class="input-group">
                    <div class="form-floating w-50">
                        <select name="customer" class="form-select">
                            <option value="0" selected>Выберите клиента</option>
                            @foreach ($customers as $customer)
                                <option value="{{$customer->id}}">{{$customer->first_name." ".$customer->last_name." ".$customer->father_name}}</option>
                            @endforeach
                        </select>
                        {{Form::label('customer','Клиенты')}}
                    </div>
                    @can('customer-create')
                        <span class="customer-create" data-toggle="modal" data-target="#createCustomer"><img class="icon mt-2 ms-1" src="{{asset('image/user-plus.svg')}}" alt="Добавить клиента"></span>
                    @endcan
                    <div class="form-floating input-group w-75 visually-hidden">
                        <select name="car" class="form-select">
                        </select>
                        {{Form::label('car','Автомобили')}}
                        @can('customer-create')
                            <span class="car-create" data-toggle="modal" data-target="#createCar"><img class="icon mt-2 ms-1" src="{{asset('image/auto-plus.svg')}}" alt="Добавить клиента"></span>
                        @endcan
                    </div>
                </div>
            </td>
            <td>
                <div class="input-group">
                    <div class="form-floating w-50">
                        <select name="work[]" class="form-select">
                            <option value="0" selected>Выберите работу</option>
                            @foreach ($works as $work)
                                <option value="{{$work->id}}">{{$work->name}}</option>
                            @endforeach
                        </select>
                        {{Form::label('work','Работа')}}
                    </div>
                    @can('work-create')
                        <span class="work-create" data-toggle="modal" data-target="#createWork"><img class="icon mt-2 ms-1" src="{{asset('image/user-plus.svg')}}" alt="Добавить работу"></span>
                    @endcan
                    <span class="duplicate"><img class="icon duplicate mt-2 ms-1" src="{{asset('image/plus.svg')}}" alt="Добавить"></span>
                    <div class="form-floating input-group w-75 visually-hidden">
                        <select name="worker[]" class="form-select">
                        </select>
                        {{Form::label('worker','Выполнил')}}
                    </div>
                </div>
            </td>
            <td>
                <div class="input-group">
                    <div class="form-floating w-50">
                        <select name="material[]" class="form-select">
                            <option value="0" selected>Выберите материал</option>
                            @foreach ($materials as $material)
                                <option value="{{$material->id}}">{{$material->name}}</option>
                            @endforeach
                        </select>
                        {{Form::label('material','Материал')}}
                    </div>
                    <div class="w-25">
                        <input type="number" class="form-control h-100" placeholder="Количество" min="0" step="1" pattern="^[0-9]+$">
                    </div>
                    @can('material-create')
                        <span class="material-create" data-toggle="modal" data-target="#createMaterial"><img class="icon mt-2 ms-1" src="{{asset('image/wrench.svg')}}" alt="Добавить работу"></span>
                    @endcan
                    <span class="duplicate"><img class="icon duplicate mt-2 ms-1" src="{{asset('image/plus.svg')}}" alt="Добавить"></span>
                </div>
            </td>
        </tr>
        </tbody>
        <tfoot>
            <td class="text-center" colspan="3">{{ Form::submit('Сохранить и распечатать',array('class'=>'btn btn-primary'))}}</td>
        </tfoot>
    </table>
{{ Form::close() }}

<table id="t-client" class="table table-bordered caption-top">
    <caption class="text-center">Заказ-наряд № <span id="order-id" class="border-bottom text-center px-4"></span></caption>
    <thead>
    <th>№</th>
    <th>ФИО клиента</th>
    <th>Марка,модель автомобиля</th>
    <th>Государственный регистрационный <br>номер автомобиля</th>
    </thead>
    <tbody>
    <td id="numClient"></td>
    <td id="nameClient"></td>
    <td id="carClient"></td>
    <td id="stateCarClient"></td>
    </tbody>
</table>
<table id="t-work" class="table table-bordered caption-top">
    <caption>Работы:</caption>
    <thead>
        <th>№</th>
        <th>Наименование работ</th>
        <th>Стоимость (руб.)</th>
    </thead>
    <tbody>
    <tr class="tr-work">
        <td class="numWork"></td>
        <td class="nameWork"></td>
        <td class="priceWork"></td>
    </tr>
    </tbody>
    <tfoot>
        <td class="text-end" colspan="3">Итого по работам (руб.):<span id="total-work" class="border-bottom text-center px-4"></span></td>
    </tfoot>
</table>
<table id="t-material" class="table table-bordered caption-top">
    <caption>Материалы:</caption>
    <thead>
        <th>№</th>
        <th>Наименование материалов</th>
        <th>Стоимость (руб.)</th>
    </thead>
    <tbody>
    <tr class="tr-material">
        <td class="numMaterial"></td>
        <td class="nameMaterial"></td>
        <td class="priceMaterial"></td>
    </tr>
    </tbody>
    <tfoot>
        <td colspan="3">
            <div class="float-end">
                Итого по материалам (руб.):<span id="total-material" class="border-bottom text-center px-4"></span>
                <div>
                    Итого к оплате (руб.):<span id="total" class="border-bottom text-center px-4"></span>
                </div>
            </div>
        </td>
    </tfoot>
</table>

@include('layouts.modal')

<script src="{{ asset('js/order.js') }}"></script>
<script src="{{ asset('js/newCustomer.js') }}"></script>
@endsection
