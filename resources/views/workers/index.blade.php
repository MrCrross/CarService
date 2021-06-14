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
                <h2>Сотрудники
                    @can('worker-print')
                        <button class="btn" onclick="print()" title="Распечатать"><img class="icon-sm" src="{{asset('image/print.svg')}}" title="Распечатать" alt="Распечатать"></button>
                    @endcan
                </h2>
            </div>
            <div class="float-end">
                <div class="input-group">
                    <input class="form-control" type="search" placeholder="Поиск">
                    <button id="btnSearch" class="input-group-text" title="Найти"><img src="{{asset('image/search.svg')}}" title="Найти" alt="Найти" class="icon-sm"></button>
                    @can('work-create')
                        <a class="btn btn-success" href="{{ route('workers.works') }}">Работы</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
<div id="print">
    <table id="t-worker" class="table table-bordered table-hover">
        <tr>
            <th scope="col">№</th>
            <th scope="col">Фамилия</th>
            <th scope="col">Имя</th>
            <th scope="col">Отчество</th>
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
{{--        Строка для заполнения при поиске--}}
        <tr id="clone" class="visually-hidden">
            <th scope="row">
                <span></span>
                <input class="visually-hidden" name="id" value="" readonly>
            </th>
            <td>
                <span class="lastName"></span>
                @can('worker-edit')
                    <span class="worker-edit" title="Редактировать фамилию">&#128393;</span>
                    <div class="input-group visually-hidden">
                        <input class="form-control" name="last_name" type="text" value=""
                               pattern="^[A-Za-zА-Яа-яЁё]+$">
                        {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                    </div>
                @endcan
            </td>
            <td>
                <span class="firstName"></span>
                @can('worker-edit')
                    <span class="worker-edit" title="Редактировать имя">&#128393;</span>
                    <div class="input-group visually-hidden">
                        <input class="form-control" name="first_name" type="text" value=""
                               pattern="^[A-Za-zА-Яа-яЁё]+$">
                        {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                    </div>
                @endcan
            </td>
            <td>
                <span class="fatherName"></span>
                @can('worker-edit')
                    <span class="worker-edit" title="Редактировать отчество">&#128393;</span>
                    <div class="input-group visually-hidden">
                        <input class="form-control " name="father_name" type="text"
                               value="" pattern="^[A-Za-zА-Яа-яЁё]+$">
                        {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                    </div>
                @endcan</td>
            <td>
                <span class="phone"></span>
                @can('worker-edit')
                    <span class="worker-edit" title="Редактировать номер телефона">&#128393;</span>
                    <div class="input-group visually-hidden">
                        <input class="form-control" name="phone" type="text" value=""
                               pattern="[0-9]{11}" maxlength="11" minlength="11">
                        {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                    </div>
                @endcan</td>
            @can('post-list')
                <td>
                    <span class="post"></span>
                    @can('post-edit')
                        <span class="worker-edit" title="Редактировать должность" >&#128393;</span>
                        <div class="input-group visually-hidden">
                            <select class="form-select" name="post">
                            </select>
                            {{Form::file('contract',['class'=>'form-control','accept'=>'.pdf,.doc,.docx'])}}
                        </div>
                    @endcan
                </td>
            @endcan
            @can('work-list')
                <td class="td-work">
                    <div class="works btn-group mb-2">
                        <label for="works" type="text"
                               data-id="" data-price=""></label>
                        @can('work-edit')
                            <span class="work-edit" data-toggle="modal"
                                  data-target="#editWork" title="Редактировать работу">&#128393;</span>
                        @endcan
                        @can('work-delete')
                            <span class="work-delete" type="button" data-toggle="modal"
                                  data-target="#deleteWork" title="Удалить работу">&times;
                                </span>
                        @endcan
                    </div>
                </td>
            @endcan
            @can('contract-list')
                <td class="td-contract">
                    <div class="contracts btn-group">
                        <span class="contract"></span>
                        @can('contract-edit')
                            <span class="download" data-contract="" title="Скачать документ о переводе">
                                <img class="icon-sm" src="{{asset('image/download.svg')}}" title="Скачать документ о переводе" alt="Скачать документ о переводе">
                            </span>
                        @endcan
                    </div>
                </td>
            @endcan
            @can('order-list')
                <td class="td-order">
                    <a class="btn-sm link-dark orderShow" href="">№</a>
                    <a class="btn-sm link-dark workerShow" href="">...</a>
                </td>
            @endcan
            <td class="btns">
                <div class="btn-group">
                    {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                    @can('worker-delete')
                        <button class="btn btn-danger worker-delete" type="button" data-toggle="modal"
                                data-target="#deleteWorker" title="Удалить работника">&times;
                        </button>
                    @endcan
                </div>
            </td>
        </tr>
{{--        Форма нового сотрудника--}}
        @can('worker-create')
            <tr id="create">
                <th scope="row"></th>
                {{ Form::open(array('action' => 'App\Http\Controllers\WorkerController@create','method'=>'post')) }}
                <td>
                    <input class="form-control" name="last_name" type="text" pattern="^[A-Za-zА-Яа-яЁё]+$"
                           placeholder="Введите фамилию сотрудника" required>
                </td>
                <td>
                    <input class="form-control" name="first_name" type="text" pattern="^[A-Za-zА-Яа-яЁё]+$"
                           placeholder="Введите имя сотрудника" required>
                </td>
                <td>
                    <input class="form-control" name="father_name" type="text" pattern="^[A-Za-zА-Яа-яЁё]+$"
                           placeholder="Введите отчество сотрудника" required>
                </td>
                <td colspan="2">
                    <input class="form-control" name="phone" type="text" pattern="[0-9]{11}"
                           placeholder="Введите номер телефона сотрудника" minlength="11" maxlength="11" required>
                </td>
                <td colspan="3">
                    <div class="input-group">
                        <select class="form-select me-2" name="post_id">
                            @foreach ($posts as $post)
                                <option value="{{$post->id}}">{{$post->name}}</option>
                            @endforeach
                        </select>
                        @can('post-edit')
                            <a class="input-group-prepend work-edit" href="{{route('workers.works')}}" title="Переназначить работу должности">
                                <img class="icon" src="{{asset('image/edit.svg')}}" title="Переназначить работу должности" alt="Переназначить работу должности">
                            </a>
                        @endcan
                    </div>

                </td>
                <td>
                    {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                </td>
                {{ Form::close() }}
            </tr>
        @endcan
{{--        Вывод данных о сотрудниках--}}
        @foreach ($workers as $key => $worker)
            <tr class="tr-worker">
                {{ Form::open(array('action' => 'App\Http\Controllers\WorkerController@update','method'=>'patch','enctype'=>'multipart/form-data')) }}
                <th scope="row">{{ ++$key }}
                    <input class="visually-hidden" name="id" value="{{ $worker->id }}" readonly>
                </th>
                <td>
                    {{ $worker->last_name }}
                    @can('worker-edit')
                        <span class="worker-edit" title="Редактировать фамилию">&#128393;</span>
                        <div class="input-group visually-hidden">
                            <input class="form-control" name="last_name" type="text" value="{{ $worker->last_name }}"
                                   pattern="^[A-Za-zА-Яа-яЁё]+$">
                            {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                        </div>
                    @endcan
                    </td>
                <td>
                    {{ $worker->first_name }}
                    @can('worker-edit')
                        <span class="worker-edit" title="Редактировать имя">&#128393;</span>
                        <div class="input-group visually-hidden">
                            <input class="form-control" name="first_name" type="text" value="{{ $worker->first_name }}"
                                   pattern="^[A-Za-zА-Яа-яЁё]+$">
                            {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                        </div>
                    @endcan
                </td>
                <td>{{ $worker->father_name }}
                    @can('worker-edit')
                        <span class="worker-edit" title="Редактировать отчество">&#128393;</span>
                        <div class="input-group visually-hidden">
                            <input class="form-control " name="father_name" type="text"
                                   value="{{ $worker->father_name }}" pattern="^[A-Za-zА-Яа-яЁё]+$">
                            {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                        </div>
                    @endcan</td>
                <td>{{ $worker->phone }}
                    @can('worker-edit')
                        <span class="worker-edit" title="Редактировать номер телефона">&#128393;</span>
                        <div class="input-group visually-hidden">
                            <input class="form-control" name="phone" type="text" value="{{ $worker->phone }}"
                                   pattern="[0-9]{11}" maxlength="11" minlength="11">
                            {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                        </div>
                    @endcan</td>
                @can('post-list')
                    <td>
                        {{$worker->post->name}}
                        @can('post-edit')
                            <span class="worker-edit" title="Переназначить должность">&#128393;</span>
                            <div class="input-group visually-hidden">
                                <select class="form-select" name="post">
                                    <option value="" selected>Выберите должность</option>
                                    @foreach ($posts as $post)
                                        <option value="{{$post->id}}">{{$post->name}}</option>
                                    @endforeach
                                </select>
                                {!! Form::file('contract',['class'=>'form-control','accept'=>'.pdf,.doc,.docx']) !!}
                            </div>
                        @endcan
                    </td>
                @endcan
                @can('work-list')
                    <td>
                        @foreach ($worker->post->works as $work)
                            <div class="btn-group mb-2">
                                <label for="works" type="text"
                                      data-id="{{$work->work->id}}" data-price="{{$work->work->price}}">{{$work->work->name}}</label>
                                @can('work-edit')
                                    <span class="work-edit" data-toggle="modal"
                                          data-target="#editWork" title="Редактировать работу">&#128393;</span>
                                @endcan
                                @can('work-delete')
                                    <span class="work-delete" type="button" data-toggle="modal"
                                          data-target="#deleteWork" title="Удалить работу">&times;
                                    </span>
                                @endcan
                            </div>
                        @endforeach
                    </td>
                @endcan
                @can('contract-list')
                    <td>
                        @foreach ($worker->contracts as $contract)
                            <div class="btn-group">
                                {{$contract->post->name}}:
                                {{$contract->post_change}}
                                @can('contract-edit')
                                    <span class="download" data-contract="{{$contract->contract}}" title="Скачать документ о переводе">
                                        <img class="icon-sm download" src="{{asset('image/download.svg')}}" title="Скачать документ о переводе" alt="Скачать документ о переводе">
                                    </span>
                                @endcan
                            </div>
                        @endforeach
                    </td>
                @endcan
                @can('order-list')
                    <td>
                        @foreach($worker->orders as $key=>$order)
                            @if($key<=4)
                                <a class="btn-sm link-dark" href="/orders/{{$order->order_id}}">№ {{$order->order_id}}</a>
                            @else
                                @break
                            @endif
                        @endforeach
                        <a class="btn-sm link-dark"  href="/workers/{{$worker->id}}">...</a>
                    </td>
                @endcan
                <td class="btns">
                    <div class="btn-group">
                        {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                        @can('worker-delete')
                            <button class="btn btn-danger worker-delete" type="button" data-toggle="modal"
                                    data-target="#deleteWorker" title="Удалить работника">&times;
                            </button>
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
    <script src="{{ asset('js/worker.js') }}"></script>
@endsection
