@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="float-start">
            <h2>Форма Заказ-наряд
                @can('order-print')<a onclick="print()" class="btn"><img class="icon-sm" src="{{asset('image/print.svg')}}" alt="Распечатать"></a>@endcan
            </h2>
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
                        <select name="customer" class="form-select" required>
                            <option value="" selected>Выберите клиента</option>
                            @foreach ($customers as $customer)
                                <option value="{{$customer->id}}">{{$customer->last_name." ".$customer->first_name." ".$customer->father_name}}</option>
                            @endforeach
                        </select>
                        {{Form::label('customer','Клиенты')}}
                    </div>
                    @can('customer-create')
                        <span class="customer-create" data-toggle="modal" data-target="#createCustomer"><img class="icon mt-2 ms-1" src="{{asset('image/user-plus.svg')}}" alt="Добавить клиента"></span>
                    @endcan
                    <div class="form-floating input-group w-75 visually-hidden">
                        <select name="car" class="form-select" required>
                        </select>
                        {{Form::label('car','Автомобили')}}
                        @can('customer-create')
                            <span class="car-create" data-toggle="modal" data-target="#createCar"><img class="icon car-create mt-2 ms-1" src="{{asset('image/auto-plus.svg')}}" alt="Добавить клиента"></span>
                        @endcan
                    </div>
                </div>
            </td>
            <td id="work">
                <div class="input-group works">
                    <div class="form-floating w-50">
                        <select name="work[]" class="form-select" required>
                            <option value="" selected>Выберите работу</option>
                            @foreach ($works as $work)
                                <option value="{{$work->id}}" data-price="{{$work->price}}">{{$work->name}}</option>
                            @endforeach
                        </select>
                        {{Form::label('work','Работа')}}
                    </div>
                    <span class="duplicate"><img class="icon duplicate mt-2 ms-1" src="{{asset('image/plus.svg')}}" alt="Добавить"></span>
                    <div class="form-floating input-group w-75 visually-hidden">
                        <select name="worker[]" class="form-select" required>
                        </select>
                        {{Form::label('worker','Выполнил')}}
                    </div>
                </div>
            </td>
            <td id="material">
                <div class="input-group materials">
                    <div class="form-floating w-50">
                        <select name="material[]" class="form-select" required>
                            <option value="" selected>Выберите материал</option>
                            @foreach ($materials as $material)
                                <option value="{{$material->id}}" data-price="{{$material->price}}" data-count="{{$material->count}}">{{$material->name}}</option>
                            @endforeach
                        </select>
                        {{Form::label('material','Материал')}}
                    </div>
                    <div class="w-25">
                        <input name="count[]" type="number" class="form-control h-100" placeholder="Количество" min="1" step="1" pattern="^[0-9]+$" required>
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
            <td class="text-center" colspan="3">
                {{ Form::submit('Сохранить и распечатать',array('class'=>'btn btn-primary'))}}
            </td>
        </tfoot>
    </table>
{{ Form::close() }}
<div id="print">
    <table id="t-date" class="table table-bordered caption-top">
        <caption class="text-center">Заказ-наряд №
            <span id="order-id" class="border-bottom text-center px-4">
                @if($num===0)
                    {{$num+1}}
                @else
                    {{$num->id+1}}
                @endif
            </span>
        </caption>
        <thead>
        <th>Дата приема заказа:</th>
        <th>Дата выполнение заказа:</th>
        </thead>
        <tbody>
        <td id="dateReg"></td>
        <td id="dateComp"></td>
        </tbody>
    </table>
{{--    Таблица для печати данных клиента--}}
<table id="t-client" class="table table-bordered caption-top">
    <caption>Заказчик:</caption>
    <thead>
    <th>№</th>
    <th>ФИО заказчика</th>
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
{{--    Таблица для печати данных о работах--}}
<table id="t-work" class="table table-bordered caption-top">
    <caption>Работы:</caption>
    <thead>
        <th>№</th>
        <th>Наименование работ</th>
        <th>Выполнил</th>
        <th>Стоимость (руб.)</th>
    </thead>
    <tbody>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    </tbody>
    <tfoot>
        <td class="text-end" colspan="4">Итого по работам (руб.):<span id="total-work" class="border-bottom text-center px-4"></span></td>
    </tfoot>
</table>
{{--    Таблица для печати данных о материалах--}}
<table id="t-material" class="table table-bordered caption-top">
    <caption>Материалы:</caption>
    <thead>
        <th>№</th>
        <th>Наименование материалов</th>
        <th>Количество (шт.)</th>
        <th>Стоимость (руб.)</th>
    </thead>
    <tbody>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
        <td colspan="4">
            <div class="float-end">
                Итого по материалам (руб.):<span id="total-material" class="border-bottom text-center px-4"></span>
                <div>
                    Итого к оплате (руб.):<span id="total" class="border-bottom text-center px-4"></span>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <div class="float-start">
                Заказчик:<span class="border-bottom text-center px-4"></span>/<span id="signClient" class="border-bottom text-center px-4"></span>
            </div>
            <div class="float-end">
                Кассир:<span class="border-bottom text-center px-4"></span>/<span id="signWorker" class="border-bottom text-center px-4"></span>
            </div>
        </td>
    </tr>
    </tfoot>
</table>
</div>
@include('layouts.modal')

<script src="{{ asset('js/print.js') }}"></script>
<script src="{{ asset('js/order.js') }}"></script>
<script src="{{ asset('js/newCustomer.js') }}"></script>
@endsection
