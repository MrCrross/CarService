@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12 ">
        <div class="float-start">
            <h2>Заказ-наряды
                @can('order-print')<a onclick="print()" class="btn"><img class="icon-sm" src="{{asset('image/print.svg')}}" alt="Распечатать"></a>@endcan
            </h2>
        </div>
        <div class="float-end">
            <div class="input-group">
                <input class="form-control" type="search" placeholder="Поиск">
                <button id="btnSearch" class="input-group-text"><img src="{{asset('image/search.svg')}}" alt="Найти" class="icon-sm"></button>
            </div>
        </div>
    </div>
    <div class="alert alert-success visually-hidden">
        <p></p>
    </div>
</div>
<div class="container d-flex justify-content-center">
    {{Form::open(['action'=>'App\Http\Controllers\OrderController@getCalc','method'=>'POST','id'=>'getOrder','class'=>'d-flex flex-row justify-content-center col-10'])}}
        <div class="col-3 form-floating me-2">
            <input class="form-control form-control-sm" type="date" id="startDate" min="{{$min}}" max="{{$max}}" required>
            <label for="registration">Начальная дата</label>
        </div>
        <div class="col-3 form-floating mx-2">
            <input class="form-control form-control-sm" type="date" id="endDate" min="{{$min}}" max="{{$max}}" required>
            <label for="completed">Конечная дата</label>
        </div>
        <input class="btn btn-sm btn-outline-primary" type="submit" value="Найти">
    {{Form::close()}}
</div>
<div class="total-content visually-hidden row">
    <div class="col-lg-12">
        <div class="float-end">
            <caption class="text-center">Общая сумма(руб.): <span class="total border-bottom text-center px-4"></span></caption>
        </div>
    </div>
</div>
<div id="clone" class="visually-hidden">
<div class="row">
    <div class="d-flex justify-content-between">
        <table class="income table table-bordered caption-top me-3">
            <caption  class="text-center">Доходы</caption>
            <thead>
            <th>№</th>
            <th class="w-15">Клиент</th>
            <th>Выполнил</th>
            <th>Работа</th>
            <th>Стоимость(руб.)</th>
            </thead>
            <tbody>
            <tr class="tr-work">
                <td class="numOrder"></td>
                <td class="nameCustomer"></td>
                <td class="td-work" colspan="3">
                    <div class="works row">
                        <div>
                            <div class="float-start">
                                <div class="nameWorker"></div>
                                <span class="nameWork"></span>
                            </div>
                            <div class="float-end">
                                <div> </div>
                                <span class="priceWork"></span>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
            <tfoot>
            <td class="text-end" colspan="5">Сумма(руб.): <span class="total-work border-bottom text-center px-4"></span></td>
            </tfoot>
        </table>
        <table class="costs table table-bordered caption-top ms-3">
            <caption  class="text-center">Израсходовано материалов</caption>
            <thead>
            <th class="w-75">Материалы</th>
            <th>Количество</th>
            <th>Стоимость(руб.)</th>
            <th>Всего(руб.)</th>
            </thead>
            <tbody>
            <tr class="tr-material">
                <td class="nameMat"></td>
                <td class="countMat"></td>
                <td class="priceMat"></td>
                <td class="totalMat"></td>
            </tr>
            </tbody>
            <tfoot>
            <td class="text-end" colspan="4">Сумма(руб.): <span class="total-material border-bottom text-center px-4"></span></td>
            </tfoot>
        </table>
    </div>
</div>
</div>
<div id="content">

</div>
<div class="total-content visually-hidden row">
    <div class="col-lg-12">
        <div class="float-end">
            <caption class="text-center">Общая сумма(руб.): <span class="total border-bottom text-center px-4"></span></caption>
        </div>
    </div>
</div>

<script src="{{ asset('js/print.js') }}"></script>
<script src="{{ asset('js/calc.js') }}"></script>
@endsection
