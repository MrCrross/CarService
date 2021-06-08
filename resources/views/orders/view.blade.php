@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12 ">
        <div class="float-start">
            <h2>Заказ-наряды
                @can('order-print')<a onclick="print()" class="btn" title="Распечатать"><img class="icon-sm" src="{{asset('image/print.svg')}}" title="Распечатать" alt="Распечатать"></a>@endcan
            </h2>
        </div>
    </div>
</div>
<div class="container d-flex justify-content-center">
    {{Form::open(['method'=>'POST','id'=>'getOrder','class'=>'d-flex flex-column justify-content-center col-10 '])}}
        <div class="flex-row d-flex my-2 justify-content-between">
            <div class="col-3 form-floating">
                <input name="customer" type="text" class="formData visually-hidden">
                <input list="customers" class="form-select">
                <datalist id="customers">
                    @foreach ($customers as $customer)
                        <option data-value="{{$customer->id}}" value="{{$customer->last_name." ".$customer->first_name." ".$customer->father_name}}" >
                    @endforeach
                </datalist>
                <label for="customer">Заказчик</label>
            </div>
            <div class="col-3 form-floating">
                <input name="worker" type="text" class="formData visually-hidden">
                <input list="workers" class="form-select">
                <datalist id="workers">
                    @foreach ($workers as $worker)
                        <option data-value="{{$worker->id}}" value="{{$worker->last_name." ".$worker->first_name." ".$worker->father_name}}">
                    @endforeach
                </datalist>
                <label for="worker">Выполнял заказ</label>
            </div>
            <div class="col-3 form-floating">
                <input name="work" type="text" class="formData visually-hidden">
                <input list="works" class="form-select">
                <datalist id="works">
                    @foreach ($works as $work)
                        <option data-value="{{$work->id}}" data-price="{{$work->price}}" value="{{$work->name}}">
                    @endforeach
                </datalist>
                <label for="work">Работы</label>
            </div>
        </div>
        <div class="flex-row d-flex my-2 justify-content-between">
            <div class="col-3 form-floating">
                <input class="form-control form-control-sm " type="number" name="id" min="0" max="{{$last}}" step="1">
                <label for="id">Номер заказ-наряда</label>
            </div>
            <div class="col-3 form-floating">
                <input class="form-control form-control-sm" type="date" name="start" min="{{$min}}" max="{{$max}}">
                <label for="start">Начальная дата</label>
            </div>
            <div class="col-3 form-floating">
                <input class="form-control form-control-sm" type="date" name="end" min="{{$min}}" max="{{$max}}">
                <label for="end">Конечная дата</label>
            </div>
        </div>
        <input class="btn btn-sm btn-outline-primary" type="submit" value="Найти" title="Найти">
    {{Form::close()}}
</div>
<div id="clone" class="visually-hidden">
<div class="row">
        <table class="table table-bordered caption-top">
            <caption class="text-center">Заказ-наряд №
                <span class="border-bottom text-center px-4 numOrder"></span>
            </caption>
            <thead>
                <th>Дата приема заказа:</th>
                <th>Дата выполнение заказа:</th>
            </thead>
            <tbody>
                <td class="dateReg"></td>
                <td class="dateComp"></td>
            </tbody>
        </table>
        <table class="table table-bordered caption-top">
            <caption>Заказчик:</caption>
            <thead>
                <th>№</th>
                <th>ФИО заказчика</th>
                <th>Марка,модель автомобиля</th>
                <th>Государственный регистрационный <br>номер автомобиля</th>
            </thead>
            <tbody>
                <tr>
                    <td class="numCustomer">1</td>
                    <td class="customer"></td>
                    <td class="car"></td>
                    <td class="state"></td>
                </tr>
            </tbody>
        </table>
        <table class="table table-bordered caption-top">
            <caption>Работы:</caption>
            <thead>
            <th>№</th>
            <th>Наименование работ</th>
            <th>Выполнил</th>
            <th>Стоимость (руб.)</th>
            </thead>
            <tbody class="bodyWork">
                <tr class="trWork">
                    <td class="numWork"></td>
                    <td class="work"></td>
                    <td class="worker"></td>
                    <td class="priceWork"></td>
                </tr>
            </tbody>
            <tfoot>
                <td class="text-end" colspan="4">Итого по работам (руб.):<span class="total-work border-bottom text-center px-4"></span></td>
            </tfoot>
        </table>
        {{--    Таблица для печати данных о материалах--}}
        <table class="table table-bordered caption-top">
            <caption>Материалы:</caption>
            <thead>
            <th>№</th>
            <th>Наименование материалов</th>
            <th>Количество (шт.)</th>
            <th>Стоимость за ед.(руб.)</th>
            <th>Общая сумма(руб.)</th>
            </thead>
            <tbody class="bodyMat">
                <tr class="trMat">
                    <td class="numMat"></td>
                    <td class="material"></td>
                    <td class="countMat"></td>
                    <td class="priceMat"></td>
                    <td class="amountMat"></td>
                </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5">
                    <div class="float-end">
                        Итого по материалам (руб.):<span class="total-material border-bottom text-center px-4"></span>
                        <div>
                            Итого к оплате (руб.):<span class="total border-bottom text-center px-4"></span>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <div class="float-start">
                        Заказчик:<span class="border-bottom text-center px-4"></span>/<span class="border-bottom text-center px-4"></span>
                    </div>
                    <div class="float-end">
                        Кассир:<span class="border-bottom text-center px-4"></span>/<span class="border-bottom text-center px-4"></span>
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
<div id="content">

</div>

<script src="{{ asset('js/print.js') }}"></script>
<script src="{{ asset('js/orderView.js') }}"></script>
@endsection
