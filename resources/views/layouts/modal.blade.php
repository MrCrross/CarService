@if(isset($models) && isset($firms))
@can('customer-create')
    {{-- Modal add customer + his car--}}
    <div class="modal fade" id="createCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Добавить клиента</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" title="Закрыть окно"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 margin-tb">
                            <div class="float-start">
                                <div class="alert result visually-hidden"></div>
                            </div>
                        </div>
                    </div>
                    <form id="formCreateCustomer">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th scope="col">Фамилия</th>
                            <th scope="col">Имя</th>
                            <th scope="col">Отчество</th>
                            <th scope="col">Телефон</th>
                            <th scope="col">Автомобиль</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="w-15">
                                <input class="form-control" name="first_name" type="text" pattern="^[A-Za-zА-Яа-яЁё]+$" placeholder="Введите имя клиента" required>
                            </td>
                            <td class="w-15">
                                <input class="form-control" name="last_name" type="text" pattern="^[A-Za-zА-Яа-яЁё]+$" placeholder="Введите фамилию клиента" required>
                            </td>
                            <td class="w-15">
                                <input class="form-control" name="father_name" type="text" pattern="^[A-Za-zА-Яа-яЁё]+$" placeholder="Введите отчество клиента" required>
                            </td>
                            <td class="w-15">
                                <input class="form-control" name="phone" type="text" pattern="[0-9]{11}" maxlength="11" minlength="11" placeholder="Введите номер телефона клиента" required>
                            </td>
                            <td class="w-50">
                                <div class="input-group">
                                    <select class="form-select" name="model_id">
                                        <option value="0" selected>Выберите модель автомобиля</option>
                                        @foreach ($models as $model)
                                            <option value="{{$model->id}}">{{$model->firm->name.' '.$model->name.' '.$model->year_release}}</option>
                                        @endforeach
                                    </select>
                                    <span class="visModel" title="Добавить новую модель"><img class="icon" src="{{asset('image/plus.svg')}}" title="Добавить новую модель" alt="Добавить новую модель" ></span>
                                </div>
                                <div id="newModel" class="input-group visually-hidden">
                                    <div class="input-group">
                                        <select class="form-select" name="firm_id">
                                            <option value="0" selected>Выберите фирму автомобиля</option>
                                            @foreach ($firms as $firm)
                                                <option value="{{$firm->id}}">{{$firm->name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="visFirm" title="Добавить новую фирму"><img class="icon" src="{{asset('image/plus.svg')}}" title="Добавить новую фирму" alt="Добавить новую фирму" ></span>
                                    </div>
                                    <div id="newFirm" class="input-group visually-hidden">
                                        <input class="form-control" type="text" name='firm_name' placeholder="Введите название фирмы автомобиля" pattern="[А-Яа-яЁёЕеA-Za-z\.\s]*">
                                        <span class="visFirm" title="Выбрать фирму"><img class="icon" src="{{asset('image/back.svg')}}" title="Выбрать фирму" alt="Выбрать фирму" ></span>
                                    </div>
                                    <input class="form-control" type="text" name='model_name' placeholder="Введите название модели автомобиля" pattern="^[А-Яа-яЁёЕе\x1F-\xBF]*">
                                    <input class="form-control" type="text" name='model_year' placeholder="Введите год выпуска автомобиля" pattern="[0-9]{4}">
                                    <span class="visModel" title="Выбрать модель"><img class="icon" src="{{asset('image/back.svg')}}" title="Выбрать модель" alt="Выбрать модель" ></span>
                                </div>
                                <input class="form-control" name="state" type="text"  value="" pattern="[АВЕКМНОРСТУХ]{1}[0-9]{3}[АВЕКМНОРСТУХ]{2}[0-9]{3}" placeholder="Госномер" required>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Отмена</button>
                        <button class="btn btn-primary" id="submitCreateCustomer" type="submit">Добавить</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endcan
@can('customer-create')
    {{-- Modal add car to customer--}}
    <div class="modal fade" id="createCar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Добавить новый автомобиль клиенту</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" title="Закрыть окно"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 margin-tb">
                            <div class="float-start">
                                <div class="alert result visually-hidden"></div>
                            </div>
                        </div>
                    </div>
                    <form id="formCreateCar">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th scope="col">Клиент</th>
                                <th scope="col">Автомобиль</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="w-15">
                                    <input class="form-control" name="nameCustomer" type="text" data-id="" readonly required>
                                </td>
                                <td class="w-50">
                                    <div class="input-group">
                                        <select class="form-select" name="model_id">
                                            <option value="0" selected>Выберите модель автомобиля</option>
                                            @foreach ($models as $model)
                                                <option value="{{$model->id}}">{{$model->firm->name.' '.$model->name.' '.$model->year_release}}</option>
                                            @endforeach
                                        </select>
                                        <span class="visModel" title="Добавить новую модель"><img class="icon" src="{{asset('image/plus.svg')}}" title="Добавить новую модель" alt="Добавить новую модель" ></span>
                                    </div>
                                    <div id="newModel" class="input-group visually-hidden">
                                        <div class="input-group">
                                            <select class="form-select" name="firm_id">
                                                <option value="0" selected>Выберите фирму автомобиля</option>
                                                @foreach ($firms as $firm)
                                                    <option value="{{$firm->id}}">{{$firm->name}}</option>
                                                @endforeach
                                            </select>
                                            <span class="visFirm" title="Добавить новую фирму"><img class="icon" src="{{asset('image/plus.svg')}}" title="Добавить новую фирму" alt="Добавить новую фирму" ></span>
                                        </div>
                                        <div id="newFirm" class="input-group visually-hidden">
                                            <input class="form-control" type="text" name='firm_name' placeholder="Введите название фирмы автомобиля" pattern="[А-Яа-яЁёЕеA-Za-z\.\s]*">
                                            <span class="visFirm" title="Выбрать фирму"><img class="icon" src="{{asset('image/back.svg')}}" title="Выбрать фирму" alt="Выбрать фирму" ></span>
                                        </div>
                                        <input class="form-control" type="text" name='model_name' placeholder="Введите название модели автомобиля" pattern="^[А-Яа-яЁёЕе\x1F-\xBF]*">
                                        <input class="form-control" type="text" name='model_year' placeholder="Введите год выпуска автомобиля" pattern="[0-9]{4}">
                                        <span class="visModel" title="Выбрать модель"><img class="icon" src="{{asset('image/back.svg')}}" title="Выбрать модель" alt="Выбрать модель" ></span>
                                    </div>
                                    <input class="form-control" name="state" type="text"  value="" pattern="[АВЕКМНОРСТУХ]{1}[0-9]{3}[АВЕКМНОРСТУХ]{2}[0-9]{3}" placeholder="Госномер" required>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Отмена</button>
                            <button class="btn btn-primary" id="submitCreateCar" type="submit">Добавить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endcan
@endif

@if(isset($firms))
    @can('model-create')
        {{-- Modal add model--}}
        <div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Добавить модель автомобиля</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" title="Закрыть окно"></button>
                    </div>
                    <div class="modal-body">
                        {{ Form::open(array('action' => 'App\Http\Controllers\CarController@createModel','method'=>'post')) }}
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Фирма</th>
                                <th>Название модели</th>
                                <th>Год выпуска</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <div class="input-group">
                                        <select class="form-select" name="firm">
                                            @foreach ($firms as $firm)
                                                <option value="{{$firm->id}}">{{$firm->name}}</option>
                                            @endforeach
                                        </select>
                                        <img class="icon" src="{{asset('image/plus.svg')}}" title="Добавить фирму" alt="Добавить фирму" data-toggle="modal" data-target="#addFirm">
                                    </div>
                                </td>
                                <td><input class="form-control" type="text" name='name' placeholder="Введите название модели автомобиля" pattern="^[А-Яа-яЁёЕе\x1F-\xBF]*"></td>
                                <td><input class="form-control" type="text" name='year' placeholder="Введите год выпуска автомобиля" pattern="[0-9]{4}"></td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                            {{Form::submit('Добавить',array('class'=>'btn btn-primary'))}}
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endif

@can('firm-create')
    {{-- Modal add firm--}}
    <div class="modal fade" id="addFirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Добавить фирму автомобиля</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" title="Закрыть окно"></button>
                </div>
                <div class="modal-body">
                    {{ Form::open(array('action' => 'App\Http\Controllers\CarController@createFirm','method'=>'post')) }}
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Название фирмы</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><input class="form-control" type="text" name='name' placeholder="Введите название фирмы автомобиля" pattern="[А-Яа-яЁёЕеA-Za-z\.\s]*"></td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                        {{Form::submit('Добавить',array('class'=>'btn btn-primary'))}}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endcan

@can('customer-delete')
    {{-- Modal delete --}}
    <div class="modal fade" id="deleteCar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Удаление автомобиль</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" title="Закрыть окно"></button>
                </div>
                <div class="modal-body">
                    {{ Form::open(array('action' => 'App\Http\Controllers\CarController@destroy','method'=>'delete','id'=>'car-delete')) }}
                    {{Form::label('delete')}}
                    <input class="visually-hidden" name="id" value="" readonly>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    {{Form::submit('Удалить',array('class'=>'btn btn-danger'))}}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endcan

@can('customer-delete')
    {{-- Modal delete --}}
    <div class="modal fade" id="deleteCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Удаление клиента</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" title="Закрыть окно"></button>
                </div>
                <div class="modal-body">
                    {{ Form::open(array('action' => 'App\Http\Controllers\CustomerController@destroy','method'=>'delete','id'=>'customer-delete')) }}
                    {{Form::label('delete')}}
                    <input class="visually-hidden" name="id" value="" readonly>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    {{Form::submit('Удалить',array('class'=>'btn btn-danger'))}}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endcan

{{-- download --}}
@can('contract-edit')
    {{ Form::open(array('action' => 'App\Http\Controllers\WorkerController@download','method'=>'put','class'=>"visually-hidden",'id'=>'downloadForm')) }}
    <input class="visually-hidden" name="contract" readonly>
    {{Form::submit('',array('class'=>'visually-hidden','id'=>'download'))}}
    {{ Form::close() }}
@endcan

@if(isset($posts) && isset($works))
    {{-- Modal add works-posts--}}
    @can('work-create')
        <div class="modal fade" id="addWorkPost" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Назначить работу должности</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" title="Закрыть окно"></button>
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
                                            <option value="{{$post['id']}}">{{$post['name']}}</option>
                                        @endforeach
                                    </select>
                                <td>
                                    @foreach ($works as $work)
                                        <div class="form-check">
                                            {{ Form::checkbox('works[]', $work['id'],false,['class'=>'form-check-input'])}}
                                            {{ Form::label('works',$work['name'],['class'=>'form-check-label'])}}
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
@endif

{{-- Modal delete worker--}}
@can('worker-delete')
    <div class="modal fade" id="deleteWorker" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Удаление сотрудника</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" title="Закрыть окно"></button>
                </div>
                <div class="modal-body">
                    {{ Form::open(array('action' => 'App\Http\Controllers\WorkerController@destroy','method'=>'delete','id'=>'worker-delete')) }}
                    {{Form::label('delete')}}
                    <input class="visually-hidden" name="id" value="" readonly>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    {{Form::submit('Удалить',array('class'=>'btn btn-danger'))}}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endcan

{{-- Modal delete material--}}
@can('material-delete')
    <div class="modal fade" id="deleteMaterial" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Удаление материала</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" title="Закрыть окно"></button>
                </div>
                <div class="modal-body">
                    {{ Form::open(array('action' => 'App\Http\Controllers\MaterialController@destroy','method'=>'delete','id'=>'material-delete')) }}
                    {{Form::label('delete')}}
                    <input class="visually-hidden" name="id" value="" readonly>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    {{Form::submit('Удалить',array('class'=>'btn btn-danger'))}}
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
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" title="Закрыть окно"></button>
                </div>
                <div class="modal-body">
                    {{ Form::open(array('action' => 'App\Http\Controllers\PostController@destroy','method'=>'delete','id'=>'post-delete')) }}
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

{{-- Modal delete work--}}
@can('work-delete')
    <div class="modal fade" id="deleteWork" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Удаление работу</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" title="Закрыть окно"></button>
                </div>
                <div class="modal-body">
                    {{ Form::open(array('action' => 'App\Http\Controllers\WorkController@destroy','method'=>'delete','id'=>'work-delete')) }}
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

{{-- Modal edit firm--}}
@can('firm-edit')
    <div class="modal fade" id="editFirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Редактировать название фирмы</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" title="Закрыть окно"></button>
                </div>
                {{ Form::open(array('action' => 'App\Http\Controllers\CarController@updateFirm','method'=>'patch','id'=>'form-firm-edit')) }}
                <div class="modal-body">
                    <input class="visually-hidden" name="id" value="" readonly required>
                    <label for="name">Изменить название фирмы</label>
                    <input class="form-control" name="name" value="" required pattern="[А-Яа-яЁёЕеA-Za-z\.\s]*">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    {{ Form::submit('Изменить',array('class'=>'btn btn-primary'))}}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endcan

{{-- Modal edit model--}}
@can('model-edit')
    <div class="modal fade" id="editModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Редактировать модель</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" title="Закрыть окно"></button>
                </div>
                {{ Form::open(array('action' => 'App\Http\Controllers\CarController@updateModel','method'=>'patch','id'=>'form-model-edit')) }}
                <div class="modal-body">
                    <input class="visually-hidden" name="id" value="" readonly required>
                    <input class="visually-hidden" name="firm_id" value="" readonly required>
                    <label for="name">Изменить фирму</label>
                    <div class="input-group">
                        <input class="form-control" name="firm_name" value="" required readonly pattern="[А-Яа-яЁёЕеA-Za-z\.\s]*">
                        <span class="firmModal-edit">&#128393;</span>
                    </div>
                    <label for="name">Изменить название модели</label>
                    <input class="form-control" name="name" value="" required pattern="^[А-Яа-яЁёЕе\x1F-\xBF]*">
                    <label for="year">Изменить год выпуска</label>
                    <input class="form-control" name="year" value="" required pattern="[0-9]{4,}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    {{ Form::submit('Изменить',array('class'=>'btn btn-primary'))}}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endcan

{{-- Modal add post--}}
@can('post-create')
    <div class="modal fade" id="addPost" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Добавить должность</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" title="Закрыть окно"></button>
                </div>
                <div class="modal-body">
                    {{ Form::open(array('action' => 'App\Http\Controllers\PostController@create','method'=>'post')) }}
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
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" title="Закрыть окно"></button>
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
                                       placeholder="Введите название работы" pattern="^[А-Яа-яЕеЁё0-9\s]*"></td>
                            <td><input class="form-control" type="number" name='price'
                                       placeholder="Введите стоимость работы" pattern="^[0-9]+$" min="1" step="1">
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
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" title="Закрыть окно"></button>
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
                            <td>
                                <input class="form-control" type="text" name='name'
                                       placeholder="Введите название работы" pattern="^[А-Яа-яЕеЁё0-9\s]*">
                            </td>
                            <td>
                                <input class="form-control" type="number" name='price'
                                       placeholder="Введите стоимость работы" pattern="^[0-9]+$" min="1" step="1">
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

{{-- Modal create material --}}
@can('material-create')
    <div class="modal fade" id="createMaterial" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Добавить материал</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" title="Закрыть окно"></button>
                </div>
                <div class="modal-body">
                    {{ Form::open(array('action' => 'App\Http\Controllers\MaterialController@createOrder','method'=>'post')) }}
                    <input class="visually-hidden" name="id" readonly>
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Название материала</th>
                            <th>Стоимость</th>
                            <th>Количество материала</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input class="form-control" type="text" name='name'
                                       placeholder="Введите название материала" pattern="^[А-Яа-яЕеЁё0-9\x1F-\xBF]*">
                            </td>
                            <td>
                                <input class="form-control" type="number" name='price'
                                       placeholder="Введите стоимость материала" pattern="^[0-9]+$" min="1">
                            </td>
                            <td>
                                <input class="form-control" type="number" name='count'
                                       placeholder="Введите стоимость материала" pattern="^[0-9]+$" min="1">
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
