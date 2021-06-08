@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="float-start">
            <h2>
                Форма Заказ-наряд
                @can('order-print')<a onclick="print()" class="btn" title="Распечатать"><img class="icon-sm" src="{{asset('image/print.svg')}}" title="Распечатать" alt="Распечатать"></a>@endcan
            </h2>
            <div id="result" class="alert visually-hidden"></div>
        </div>
        <div class="float-end">
            <a class="btn btn-outline-primary" href="{{ route('orders.view') }}">История</a>
        </div>
    </div>
</div>
<datalist id="customers">
    @foreach ($customers as $customer)
        <option data-value="{{$customer->id}}" value="{{$customer->last_name." ".$customer->first_name." ".$customer->father_name}}" >
    @endforeach
</datalist>
<datalist id="works">
    @foreach ($works as $work)
        <option data-value="{{$work->id}}" data-price="{{$work->price}}" value="{{$work->name}}">
    @endforeach
</datalist>
<datalist id="materials">
    @foreach ($materials as $material)
        <option data-value="{{$material->id}}" data-price="{{$material->price}}" data-count="{{$material->count}}" value="{{$material->name}}">
    @endforeach
</datalist>
<div id="print">
    {{ Form::open(array('action' => 'App\Http\Controllers\OrderController@create','method'=>'post','id'=>'createOrderForm')) }}
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
            <th>Марка, модель автомобиля</th>
            <th>Государственный регистрационный <br>номер автомобиля</th>
        </thead>
        <tbody>
            <tr>
                <td class="num">1</td>
                <td class="name">
                    <div class="input-group noPrint">
                        <input name="customer" type="text" class="formData visually-hidden">
                        <input list="customers" class="form-select" required>
                        @can('customer-create')
                            <span class=" customer-create" data-toggle="modal" data-target="#createCustomer" title="Добавить клиента"><img class="icon mt-2 ms-1" src="{{asset('image/user-plus.svg')}}" title="Добавить клиента" alt="Добавить клиента"></span>
                        @endcan
                    </div>
                    <p class="print visually-hidden"></p>
                </td>
                <td class="car">
                    <div class="input-group v-h visually-hidden noPrint">
                        <select name="car" class="form-select" required>
                        </select>
                        @can('customer-create')
                            <span class="noPrint car-create" data-toggle="modal" data-target="#createCar" title="Добавить автомобиль"><img class="icon car-create mt-2 ms-1" src="{{asset('image/auto-plus.svg')}}" title="Добавить автомобиль" alt="Добавить автомобиль"></span>
                        @endcan
                    </div>
                    <p class="print visually-hidden"></p>
                </td>
                <td class="state"></td>
            </tr>
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
            <tr class="clone visually-hidden">
                <td class="num"></td>
                <td class="work">
                    <input type="text" class="formData inWork visually-hidden">
                    <input list="works" class="form-select noPrint">
                    <p class="print visually-hidden"></p>
                </td>
                <td class="worker">
                    <div class="input-group v-h visually-hidden noPrint">
                        <select name="worker[]" class="form-select">
                        </select>
                    </div>
                    <p class="print visually-hidden"></p>
                </td>
                <td class="priceWork"></td>
                <td class="noPrint">
                    <span class="remove" title="Удалить строку"><img class="icon remove" src="{{asset('image/minus.svg')}}" title="Удалить строку" alt="Удалить"></span>
                </td>
            </tr>
            <tr>
                <td class="num">1</td>
                <td class="work">
                    <input name="work[]" type="text" class="formData visually-hidden">
                    <input list="works" class="form-select noPrint" required>
                    <p class="print visually-hidden"></p>
                </td>
                <td class="worker">
                    <div class="input-group v-h visually-hidden noPrint">
                        <select name="worker[]" class="form-select">
                        </select>
                    </div>
                    <p class="print visually-hidden"></p>
                </td>
                <td class="priceWork"></td>
                <td class="noPrint">
                    <span class="duplicate" title="Добавить строку"><img class="icon duplicate" src="{{asset('image/plus.svg')}}" title="Добавить строку" alt="Добавить"></span>
                </td>
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
            <th>Стоимость за ед.(руб.)</th>
            <th>Общая сумма(руб.)</th>
        </thead>
        <tbody>
            <tr class="clone visually-hidden">
                <td class="num"></td>
                <td class="name">
                    <div class="input-group noPrint">
                        <input type="text" class="formData inMat visually-hidden">
                        <input list="materials" class="form-select">
                        @can('material-create')
                            <span class="material-create noPrint" data-toggle="modal" data-target="#createMaterial"><img class="icon mt-2 ms-1" src="{{asset('image/wrench.svg')}}" alt="Добавить работу"></span>
                        @endcan
                    </div>
                    <p class="print visually-hidden"></p>
                </td>
                <td class="count">
                    <input name="count[]" type="number" class="form-control h-100 noPrint" placeholder="Количество" min="1" step="1" value="1" pattern="^[0-9]+$">
                    <p class="print visually-hidden"></p>
                </td>
                <td class="priceMat"></td>
                <td class="amountMat"></td>
                <td class="noPrint"><span class="remove" title="Удалить строку"><img class="icon remove" src="{{asset('image/minus.svg')}}" title="Удалить строку" alt="Удалить"></span></td>
            </tr>
            <tr>
                <td class="num">1</td>
                <td class="name">
                    <div class="input-group noPrint">
                        <input name="material[]" type="text" class="formData visually-hidden">
                        <input list="materials" class="form-select" required>
                        @can('material-create')
                            <span class="material-create noPrint" data-toggle="modal" data-target="#createMaterial"><img class="icon mt-2 ms-1" src="{{asset('image/wrench.svg')}}" alt="Добавить работу"></span>
                        @endcan
                    </div>
                    <p class="print visually-hidden"></p>
                </td>
                <td class="count">
                    <input name="count[]" type="number" class="form-control h-100 noPrint" placeholder="Количество" min="1" step="1" value="1" pattern="^[0-9]+$" required>
                    <p class="print visually-hidden"></p>
                </td>
                <td class="priceMat"></td>
                <td class="amountMat"></td>
                <td class="noPrint"><span class="duplicate" title="Добавить строку"><img class="icon duplicate" src="{{asset('image/plus.svg')}}" title="Добавить строку" alt="Добавить"></span></td>
            </tr>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="5">
                <div class="float-end">
                    Итого по материалам (руб.):<span id="total-material" class="border-bottom text-center px-4"></span>
                    <div>
                        Итого к оплате (руб.):<span id="total" class="border-bottom text-center px-4"></span>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <div class="float-start">
                    Заказчик:<span class="border-bottom text-center px-4"></span>/<span id="signClient" class="border-bottom text-center px-4"></span>
                </div>
                <div class="float-end">
                    Кассир:<span class="border-bottom text-center px-4"></span>/<span id="signWorker" class="border-bottom text-center px-4"></span>
                </div>
            </td>
        </tr>
        <tr class="noPrint">
            <td class="text-center" colspan="5">
            {{ Form::submit('Сохранить и распечатать',array('class'=>'btn btn-primary'))}}
            </td>
        </tr>
        </tfoot>
    </table>
    {{ Form::close() }}
</div>
@include('layouts.modal')

<script src="{{ asset('js/print.js') }}"></script>
<script src="{{ asset('js/order.js') }}"></script>
<script src="{{ asset('js/newCustomer.js') }}"></script>
@endsection
