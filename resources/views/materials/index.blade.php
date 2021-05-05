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
                <h2>Материалы
                    @can('material-print')
                        <button class="btn" onclick="print()"><img class="icon-sm" src="{{asset('image/print.svg')}}" alt="Распечатать"></button>
                    @endcan
                </h2>
            </div>
            <div class="float-end">
                <div class="input-group">
                    <input class="form-control" type="search" placeholder="Поиск">
                    <button id="btnSearch" class="input-group-text"><img src="{{asset('image/search.svg')}}" alt="Найти" class="icon-sm"></button>
                </div>
            </div>
        </div>
    </div>
    <div id="print">
        <table id="t-material" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th scope="col">№</th>
                <th scope="col">Название материала</th>
                <th scope="col">Стоимость</th>
                <th scope="col">Количество</th>
            </tr>
            </thead>
            <tbody>
            <tr id="clone" class="visually-hidden">
                <th>
                    <span></span>
                    <input class="visually-hidden" type="text" name="id" value="" readonly required>
                </th>
                <td>
                    <span class="nameMaterial"></span>
                    <span class="material-edit">&#128393;</span>
                    <div class="input-group visually-hidden">
                        <input class="form-control" name="name" type="text" value="" pattern="^[А-Яа-яЕеЁё0-9\x1F-\xBF]*" placeholder="Введите название материала" required>
                        {{Form::submit('&#10003;',array('class'=>'input-group-text btn btn-primary'))}}
                    </div>
                </td>
                <td>
                    <span class="priceMaterial"></span>
                    <span class="material-edit">&#128393;</span>
                    <div class="input-group visually-hidden">
                        <input class="form-control" name="price" type="number" placeholder="Введите стоимость материала" value="" min="1" step="1" pattern="^[0-9]+$" required>
                        {{Form::submit('&#10003;',array('class'=>'input-group-text btn btn-primary'))}}
                    </div>
                </td>
                <td>
                    <span class="countMat"></span>
                    <span class="material-edit">&#128393;</span>
                    <div class="input-group visually-hidden">
                        <input class="form-control" name="count" type="number" placeholder="Введите количество материала" value="" min="0" step="1" pattern="^[0-9]+$" required>
                        {{Form::submit('&#10003;',array('class'=>'input-group-text btn btn-primary'))}}
                    </div>
                </td>
                <td class="btns">
                    {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                    @can('material-delete')
                        <button class="btn btn-danger material-delete" type="button" data-toggle="modal"
                                data-target="#deleteMaterial">&times;
                        </button>
                    @endcan
                </td>
            </tr>
            @can('material-create')
                <tr id="create">
                    {{ Form::open(array('action' => 'App\Http\Controllers\MaterialController@create','method'=>'post')) }}
                    <th scope="row">
                    </th>
                    <td>
                        <input class="form-control" name="name" type="text" pattern="^[А-Яа-яЕеЁё0-9\x1F-\xBF]*" placeholder="Введите название материала" required>
                    </td>
                    <td>
                        <input name="price" type="number" class="form-control h-100" placeholder="Введите стоимость материала" min="1" step="1" pattern="^[0-9]+$" required>
                    </td>
                    <td>
                        <input name="count" type="number" class="form-control h-100" placeholder="Введите количество материала" min="1" step="1" pattern="^[0-9]+$" required>
                    </td>
                    <td>
                        {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                    </td>
                    {{ Form::close() }}
                </tr>
            @endcan
            @foreach($materials as $key => $material)
                <tr class="tr-material">
                    {{ Form::open(array('action' => 'App\Http\Controllers\MaterialController@update','method'=>'patch')) }}
                    <th scope="row">
                        {{++$key}}
                        <input class="visually-hidden" type="text" name="id" value="{{$material->id}}" readonly required>
                    </th>
                    <td>
                        <span class="nameMaterial">{{$material->name}}</span>
                        <span class="material-edit">&#128393;</span>
                        <div class="input-group visually-hidden">
                            <input class="form-control" name="name" type="text" value="{{$material->name}}" pattern="^[А-Яа-яЕеЁё0-9\x1F-\xBF]*" placeholder="Введите название материала" required>
                            {{Form::submit('&#10003;',array('class'=>'input-group-text btn btn-primary'))}}
                        </div>
                    </td>
                    <td>
                        <span class="priceMaterial">{{$material->price}}</span>
                        <span class="material-edit">&#128393;</span>
                        <div class="input-group visually-hidden">
                            <input class="form-control" name="price" type="number" placeholder="Введите стоимость материала" value="{{$material->price}}" min="1" step="1" pattern="^[0-9]+$" required>
                            {{Form::submit('&#10003;',array('class'=>'input-group-text btn btn-primary'))}}
                        </div>
                    </td>
                    <td>
                        <span class="countMat">{{$material->count}}</span>
                        <span class="material-edit">&#128393;</span>
                        <div class="input-group visually-hidden">
                            <input class="form-control" name="count" type="number" placeholder="Введите количество материала" value="{{$material->count}}" min="0" step="1" pattern="^[0-9]+$" required>
                            {{Form::submit('&#10003;',array('class'=>'input-group-text btn btn-primary'))}}
                        </div>
                    </td>
                    <td class="btns">
                        {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                        @can('material-delete')
                            <button class="btn btn-danger material-delete" type="button" data-toggle="modal"
                                    data-target="#deleteMaterial">&times;
                            </button>
                        @endcan
                    </td>
                    {{ Form::close() }}
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @include('layouts.modal')
    <script src="{{ asset('js/print.js') }}"></script>
    <script src="{{ asset('js/material.js') }}"></script>
@endsection
