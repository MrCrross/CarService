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
                <h2>Должностные работы
                    @can('worker-print')
                        <button class="btn" onclick="print()"><img class="icon-sm" src="{{asset('image/print.svg')}}" alt="Распечатать"></button>
                    @endcan
                </h2>
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
<div id="print">
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
                                    <span> ({{$work->price}} руб.)</span>
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
                <td class="btns">
                    <div class="btn-group">
                        {{Form::submit('&#10003;',array('class'=>'btn btn-primary'))}}
                        @can('post-delete')
                            <button class="btn btn-danger post-delete" type="button" data-toggle="modal"
                                    data-target="#deletePost">&times;
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
