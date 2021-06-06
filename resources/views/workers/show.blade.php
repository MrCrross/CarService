@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 mb-2">
            <div class="float-start">
                <h2>Сотрудник {{ $worker[0]->last_name." ".$worker[0]->first_name." ".$worker[0]->father_name}}
                    @can('worker-print')
                        <button class="btn" onclick="print()" title="Распечатать"><img class="icon-sm" src="{{asset('image/print.svg')}}" title="Распечатать" alt="Распечатать"></button>
                    @endcan
                </h2>
            </div>
        </div>
    </div>
    <div id="print">
        <table class="table table-bordered table-hover">
            <tr>
                <th scope="col">№</th>
                <th scope="col">ФИО</th>
                <th scope="col">Телефон</th>
                @can('post-list')
                    <th scope="col">Должность</th>
                @endcan
                @can('work-list')
                    <th scope="col">Должностные работы</th>
                @endcan
                @can('contract-list')
                    <th scope="col">Должностная история</th>
                @endcan
                @can('order-list')
                    <th scope="col">Выполненные заказы</th>
                @endcan
            </tr>
                <tr>
                    <th scope="row">{{ $worker[0]->id }}</th>
                    <td>{{ $worker[0]->last_name." ".$worker[0]->first_name." ".$worker[0]->father_name}}</td>
                    <td>{{ $worker[0]->phone }}</td>
                    @can('post-list')
                        <td>
                            {{$worker[0]->post->name}}
                        </td>
                    @endcan
                    @can('work-list')
                        <td>
                            @foreach ($worker[0]->post->works as $work)
                                <div data-id="{{$work->work->id}}" data-price="{{$work->work->price}}">{{$work->work->name}}</div>
                            @endforeach
                        </td>
                    @endcan
                    @can('contract-list')
                        <td>
                            @foreach ($worker[0]->contracts as $contract)
                                <div class="btn-group">
                                    {{$contract->post->name}}:
                                    {{$contract->post_change}}
                                    @can('contract-edit')
                                        <span class="download" data-contract="{{$contract->contract}}" title="Скачать документ о переводе">
                                        <img class="icon-sm" src="{{asset('image/download.svg')}}" title="Скачать документ о переводе" alt="Скачать документ о переводе">
                                    </span>
                                    @endcan
                                </div>
                            @endforeach
                        </td>
                    @endcan
                    @can('order-list')
                        <td>
                            @foreach($worker[0]->orders as $order)
                                <a class="badge bg-success" href="/orders/{{$order->order_id}}">№ {{$order->order_id}}</a>
                            @endforeach
                        </td>
                    @endcan
                </tr>
        </table>
    </div>
    @include('layouts.modal')
    <script src="{{ asset('js/print.js') }}"></script>
    <script src="{{ asset('js/worker.js') }}"></script>
@endsection

