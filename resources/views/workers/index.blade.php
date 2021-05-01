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
                                    data-target="#delete">&times;
                            </button>
                        @endcan
                    </div>
                </td>
            </tr>
        @endforeach
        {{ Form::close() }}
    </table>
    {{-- download --}}
    @can('contract-edit')
        {{ Form::open(array('action' => 'App\Http\Controllers\WorkerController@download','method'=>'put','class'=>"visually-hidden",'id'=>'downloadForm')) }}
            <input class="visually-hidden" name="contract" readonly>
            {{Form::submit('',array('class'=>'visually-hidden','id'=>'download'))}}
        {{ Form::close() }}
    @endcan
    {{-- Modal add post--}}
    @can('post-create')
        <div class="modal fade" id="addPost" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Добавить должность</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ Form::open(array('action' => 'App\Http\Controllers\WorkController@createPost','method'=>'post')) }}
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Название должность</th>
                                <th>Оклад должности</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input class="form-control" type="text" name='name'
                                           placeholder="Введите название должности" pattern="^[А-Яа-яЕеЁё\s]+$"></td>
                                <td><input class="form-control" type="number" name='salary'
                                           placeholder="Введите оклад должности" pattern="^[0-9]+$" min=0 step="100">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена
                            </button>
                            {{Form::submit('Добавить',array('class'=>'btn btn-primary'))}}
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    @endcan

    {{-- Modal add post--}}
    @can('work-create')
        <div class="modal fade" id="addWork" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Добавить работу</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ Form::open(array('action' => 'App\Http\Controllers\WorkController@create','method'=>'post')) }}
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Название работы</th>
                                <th>Стоимость</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input class="form-control" type="text" name='name'
                                           placeholder="Введите название работы" pattern="^[А-Яа-я0-9\s]*"></td>
                                <td><input class="form-control" type="number" name='price'
                                           placeholder="Введите стоимость работы" pattern="^[0-9]+$" min=0 step="100">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена
                            </button>
                            {{Form::submit('Добавить',array('class'=>'btn btn-primary'))}}
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    @endcan

    {{-- Modal edit work--}}
    @can('work-edit')
        <div class="modal fade" id="editWork" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Изменить работу</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ Form::open(array('action' => 'App\Http\Controllers\WorkController@updateWorkPost','method'=>'patch','id'=>'work-edit')) }}
                        <input class="visually-hidden" name="id" readonly>
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Название работы</th>
                                <th>Стоимость</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input class="form-control" type="text" name='name'
                                           placeholder="Введите название работы" pattern="^[А-Яа-я0-9\s]*">
                                </td>
                                <td><input class="form-control" type="number" name='price'
                                           placeholder="Введите стоимость работы" pattern="^[0-9]+$" min=0 step="100">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена
                            </button>
                            {{Form::submit('Добавить',array('class'=>'btn btn-primary'))}}
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    @endcan

    {{-- Modal add works-posts--}}
    @can('work-create')
        <div class="modal fade" id="addWorkPost" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Назначить работу должности</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ Form::open(array('action' => 'App\Http\Controllers\WorkController@createWorkPost','method'=>'post')) }}
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Должность</th>
                                <th>Работа</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <select class="form-select" name="post_id">
                                        @foreach ($posts as $post)
                                            <option value="{{$post->id}}">{{$post->name}}</option>
                                        @endforeach
                                    </select>
                                <td>
                                    @foreach ($works as $work)
                                        <div class="form-check">
                                            {{ Form::checkbox('works[]', $work->id,false,['class'=>'form-check-input'])}}
                                            {{ Form::label('works',$work->name,['class'=>'form-check-label'])}}
                                        </div>
                                    @endforeach
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена
                            </button>
                            {{Form::submit('Добавить',array('class'=>'btn btn-primary'))}}
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    @endcan

    {{-- Modal delete --}}
    @can('worker-delete')
        <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Удаление сотрудника</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ Form::open(array('action' => 'App\Http\Controllers\WorkerController@destroy','method'=>'delete','id'=>'worker-delete')) }}
                        {{Form::label('delete')}}
                        <input id="delete-id" class="visually-hidden" name="id" value="" readonly>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                        {{Form::submit('Удалить',array('class'=>'btn btn-danger'))}}
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    @endcan
    {{-- Modal delete work--}}
    @can('work-delete')
        <div class="modal fade" id="deleteWork" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Удаление работу</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ Form::open(array('action' => 'App\Http\Controllers\WorkController@destroyWork','method'=>'delete','id'=>'work-delete')) }}
                        {{ Form::label('delete')}}
                        <input class="visually-hidden" name="id" value="" readonly>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                        {{ Form::submit('Удалить',array('class'=>'btn btn-danger'))}}
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    @endcan
    <script src="{{ asset('js/worker.js') }}"></script>
@endsection
