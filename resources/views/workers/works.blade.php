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
                <h2>Должности работы</h2>
            </div>
            <div class="float-end">
                @can('post-create')
                    <button class="btn btn-success" data-toggle="modal" data-target="#addPost">Добавить должность
                    </button>
                @endcan
                @can('work-create')
                    <button class="btn btn-success" data-toggle="modal" data-target="#addWork">Добавить работу</button>
                @endcan
            </div>
        </div>
    </div>

    <table class="table table-bordered table-hover">
        <tr>
            <th scope="col">№</th>
            <th scope="col">Название должности</th>
            <th scope="col">Должностные работы</th>
        </tr>
        @foreach ($posts as $key => $post)
            <tr>
                {{ Form::open(array('action' => 'App\Http\Controllers\WorkController@updateWorkPost','method'=>'patch')) }}
                <th scope="row">{{ ++$key }}
                    <input class="visually-hidden" name="id" value="{{ $post['id'] }}" readonly>
                </th>
                <td>
                    <span class="name">{{$post['name']}}</span>
                </td>
                @can('work-list')
                    <td>
                        @foreach($works as $work)
                            <div class="form-check mb-2">
                                @if (array_search($work->id, array_column(array_column($post['works'], 'work'), 'id')) === false)
                                    {{ Form::checkbox('works[]', $work->id,false,['class'=>'form-check-input'])}}
                                @else
                                    {{ Form::checkbox('works[]', $work->id,true,['class'=>'form-check-input'])}}
                                @endif
                                {{ Form::label('works',$work->name,['class'=>'form-check-label','data-id'=>$work->id,'data-price'=>$work->price])}}
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
                <td>
                    <div class="btn-group">
                        {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                        @can('post-delete')
                            <button class="btn btn-danger post-delete" type="button" data-toggle="modal"
                                    data-target="#deletePost">&times;
                            </button>
                        @endcan
                    </div>
                </td>
            </tr>
        @endforeach
        {{ Form::close() }}
    </table>

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

    {{-- Modal add work--}}
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
    {{-- Modal delete post--}}
    @can('post-delete')
        <div class="modal fade" id="deletePost" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Удаление должности</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ Form::open(array('action' => 'App\Http\Controllers\WorkController@destroy','method'=>'delete','id'=>'post-delete')) }}
                            {{ Form::label('delete')}}
                            <input id="delete-id" class="visually-hidden" name="id" value="" readonly>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                            {{ Form::submit('Удалить',array('class'=>'btn btn-danger'))}}
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
