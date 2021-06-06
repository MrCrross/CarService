@extends('layouts.app')

@section('content')
    @can('order-print')<a onclick="print()" class="btn" title="Распечатать"><img class="icon-sm" src="{{asset('image/print.svg')}}" title="Распечатать" alt="Распечатать"></a>@endcan
    <div id="print">
        <table class="table table-bordered caption-top">
            <caption class="text-center">(КОПИЯ) Заказ-наряд №
                <span class="border-bottom text-center px-4">{{$order[0]->id}}</span>
            </caption>
            <thead>
            <th>Дата приема заказа:</th>
            <th>Дата выполнение заказа:</th>
            </thead>
            <tbody>
                <td>{{$order[0]->registration}}</td>
                <td>{{$order[0]->completed}}</td>
            </tbody>
        </table>
        {{--    Таблица для печати данных клиента--}}
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
                    <td>1</td>
                    <td>{{$order[0]->car->customer->first_name." ".$order[0]->car->customer->last_name." ".$order[0]->car->customer->father_name}}</td>
                    <td>{{$order[0]->car->model->firm->name." ".$order[0]->car->model->name." ".$order[0]->car->model->year_release}}</td>
                    <td>{{$order[0]->car->state_number}}</td>
                </tr>
            </tbody>
        </table>
        {{--    Таблица для печати данных о работах--}}
        <table class="table table-bordered caption-top">
            <caption>Работы:</caption>
            <thead>
            <th>№</th>
            <th>Наименование работ</th>
            <th>Выполнил</th>
            <th>Стоимость (руб.)</th>
            </thead>
            <tbody>
            @foreach($order[0]->compositions as $key=> $composition)
                <tr>
                    <td>{{++$key}}</td>
                    <td>{{$composition->work->name}}</td>
                    <td>{{$composition->worker->last_name." ".$composition->worker->first_name." ".$composition->worker->father_name}}</td>
                    <td class="priceWork">{{$composition->work->price}}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <td class="text-end" colspan="4">Итого по работам (руб.):<span id="total-work" class="border-bottom text-center px-4"></span></td>
            </tfoot>
        </table>
        {{--    Таблица для печати данных о материалах--}}
        <table class="table table-bordered caption-top">
            <caption>Материалы:</caption>
            <thead>
            <th>№</th>
            <th>Наименование материалов</th>
            <th>Количество (шт.)</th>
            <th>Стоимость (руб.)</th>
            </thead>
            <tbody>
            @foreach($order[0]->materials as $key =>$material)
                <tr>
                    <td>{{++$key}}</td>
                    <td>{{$material->material->name}}</td>
                    <td class="countMat">{{$material->count}}</td>
                    <td class="priceMat">{{$material->material->price}}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4">
                    <div class="float-end">
                        Итого по материалам (руб.):<span id="total-material" class="border-bottom text-center px-4"></span>
                        <div>
                            Итого к оплате (руб.):<span id="total" class="border-bottom text-center px-4"> {{$order[0]->price}}</span>
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
    <script src="{{asset('js/print.js')}}"></script>
    <script src="{{asset('js/orderShow.js')}}"></script>
@endsection
