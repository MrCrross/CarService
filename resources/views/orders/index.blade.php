@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Заказ-наряд</h2>
        </div>
    </div>
</div>


<table class="table table-bordered caption-top">
    <caption>Форма заказ-наряда</caption>
    <tr>
      <th>No</th>
      <th>Name</th>
      <th>Email</th>
      <th>Roles</th>
      <th width="280px">Action</th>
    </tr>
</table>

<script src="{{ asset('js/order.js') }}"></script>
@endsection
