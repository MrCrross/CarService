@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="float-start">
            <h2>Управление пользователями</h2>
        </div>
        <div class="float-end">
            <a class="btn btn-success" href="{{ route('users.create') }}">Зарегистрировать пользователя</a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif

<table class="table table-bordered">
 <tr>
   <th>#</th>
   <th>Имя пользователя</th>
   <th>Почта</th>
   <th>Роли</th>
   <th width="280px">Действия</th>
 </tr>
 @foreach ($data as $key => $user)
  <tr>
    <td>{{ ++$i }}</td>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>
      @if(!empty($user->getRoleNames()))
        @foreach($user->getRoleNames() as $v)
           <label class="badge bg-success">{{ $v }}</label>
        @endforeach
      @endif
    </td>
    <td>
       <a class="btn btn-info" href="{{ route('users.show',$user->id) }}">Посмотреть</a>
       <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}" title="Редактировать пользователя">&#128393;</a>
        {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline','title'=>'Удалить пользователя']) !!}
            {!! Form::submit('&times;', ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!}
    </td>
  </tr>
 @endforeach
</table>
{!! $data->render() !!}
@endsection
