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
                <h2>Сотрудники</h2>
            </div>
            <div class="float-end">
                @can('post-create')
                    <button class="btn btn-success" data-toggle="modal" data-target="#addPost">Добавить должность
                    </button>
                @endcan
                @can('work-create')
                    <button class="btn btn-success" data-toggle="modal" data-target="#addWork">Добавить работу</button>
                    <button class="btn btn-success" data-toggle="modal" data-target="#addWorkPost">Назначить работу
                        должности
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <table class="table table-bordered table-hover">
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
        </tr>
        @can('worker-create')
            <tr>
                <th scope="row"></th>
                {{ Form::open(array('action' => 'App\Http\Controllers\WorkerController@create','method'=>'post')) }}
                <td>
                    <input class="form-control" name="first_name" type="text" pattern="^[A-Za-zА-Яа-яЁё]+$"
                           placeholder="Введите имя сотрудника" required>
                </td>
                <td>
                    <input class="form-control" name="last_name" type="text" pattern="^[A-Za-zА-Яа-яЁё]+$"
                           placeholder="Введите фамилию сотрудника" required>
                </td>
                <td>
                    <input class="form-control" name="father_name" type="text" pattern="^[A-Za-zА-Яа-яЁё]+$"
                           placeholder="Введите отчество сотрудника" required>
                </td>
                <td>
                    <input class="form-control" name="phone" type="text" pattern="[0-9]{11}"
                           placeholder="Введите номер телефона сотрудника" required>
                </td>
                <td colspan="3">
                    <div class="input-group">
                        <select class="form-select me-2" name="post_id">
                            @foreach ($posts as $post)
                                <option value="{{$post->id}}">{{$post->name}}</option>
                            @endforeach
                        </select>
                        @can('post-edit')
                            <a class="input-group-prepend work-edit" href="{{route('workers.works')}}">
                                <img class="icon" src="{{asset('image/edit.svg')}}" alt="Переназначить работу должности">
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
        @foreach ($workers as $key => $worker)
            <tr>
                {{ Form::open(array('action' => 'App\Http\Controllers\WorkerController@update','method'=>'patch','enctype'=>'multipart/form-data')) }}
                <th scope="row">{{ ++$key }}
                    <input class="visually-hidden" name="id" value="{{ $worker->id }}" readonly>
                </th>
                <td>{{ $worker->first_name }}
                    @can('worker-edit')
                        <span class="worker-edit">&#128393;</span>
                        <div class="btn-group visually-hidden">
                            <input class="form-control" name="first_name" type="text" value="{{ $worker->first_name }}"
                                   pattern="^[A-Za-zА-Яа-яЁё]+$">
                            {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                        </div>
                    @endcan</td>
                <td>{{ $worker->last_name }}
                    @can('worker-edit')
                        <span class="worker-edit">&#128393;</span>
                        <div class="btn-group visually-hidden">
                            <input class="form-control" name="last_name" type="text" value="{{ $worker->last_name }}"
                                   pattern="^[A-Za-zА-Яа-яЁё]+$">
                            {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                        </div>
                    @endcan</td>
                <td>{{ $worker->father_name }}
                    @can('worker-edit')
                        <span class="worker-edit">&#128393;</span>
                        <div class="btn-group visually-hidden">
                            <input class="form-control " name="father_name" type="text"
                                   value="{{ $worker->father_name }}" pattern="^[A-Za-zА-Яа-яЁё]+$">
                            {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                        </div>
                    @endcan</td>
                <td>{{ $worker->phone }}
                    @can('worker-edit')
                        <span class="worker-edit">&#128393;</span>
                        <div class="btn-group visually-hidden">
                            <input class="form-control" name="phone" type="text" value="{{ $worker->phone }}"
                                   pattern="^[0-9]+$">
                            {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                        </div>
                    @endcan</td>
                @can('post-list')
                    <td>
                        {{$worker->post->name}}
                        @can('post-edit')
                            <span class="worker-edit">&#128393;</span>
                            <div class="input-group visually-hidden">
                                <select class="form-select" name="post">
                                    @foreach ($posts as $post)
                                        @if ($post->id == $worker->post->id)
                                            <option value="{{$post->id}}" selected>{{$post->name}}</option>
                                        @else
                                            <option value="{{$post->id}}">{{$post->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                {{Form::file('contract',['class'=>'form-control','accept'=>'.pdf,.doc,.docx'])}}
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
                                          data-target="#editWork">&#128393;</span>
                                @endcan
                                @can('work-delete')
                                    <span class="work-delete" type="button" data-toggle="modal"
                                          data-target="#deleteWork">&times;
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
                                    <span class="download" data-contract="{{$contract->contract}}">
                                        <img class="icon-sm" src="{{asset('image/download.svg')}}" alt="Скачать документ о переводе">
                                    </span>
                                @endcan
                            </div>
                        @endforeach
                    </td>
                @endcan
                <td>
                    <div class="btn-group">
                        {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                        @can('worker-delete')
                            <button class="btn btn-danger worker-delete" type="button" data-toggle="modal"
                                    data-target="#deleteWorker">&times;
                            </button>
                        @endcan
                    </div>
                </td>
            </tr>
        @endforeach
        {{ Form::close() }}
    </table>

    @include('layouts.modal')

    <script src="{{ asset('js/worker.js') }}"></script>
@endsection