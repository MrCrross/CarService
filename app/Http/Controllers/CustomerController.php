<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerPostRequest;
use App\Models\Car;
use App\Models\Customer;
use App\Models\CarModel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * CustomerController constructor.
     */
    function __construct()
    {
        $this->middleware('permission:customer-list', ['only' => ['index']]);
        $this->middleware('permission:customer-create', ['only' => ['index','create']]);
        $this->middleware('permission:customer-edit', ['only' => ['index','update']]);
        $this->middleware('permission:customer-delete', ['only' => ['index','destroy']]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $customers = Customer::with('cars.model.firm')->get();
        $models= CarModel::with('firm')->get();
        return view('customers.index',['customers' => $customers,'models'=>$models]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function update(Request $request){
        try{
            Customer::where('id',$request->id)->update([
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'father_name'=>$request->father_name,
                'phone'=>$request->phone
            ]);
            if(isset($request->state) && isset($request->model)){
                $this->createCar($request->model,$request->id,$request->state);
            };
        }catch(QueryException $e){
            $customers = Customer::with('cars.model.firm')->get();
            $models= CarModel::with('firm')->get();
            if(strpos($e->errorInfo[2],'customer.phone')){
                return view('customers.index',['customers' => $customers,'models'=>$models,'error'=>'Такой номер телефона уже есть в базе']);
            } else{
                return view('customers.index',['customers' => $customers,'models'=>$models,'error'=>'Ошибка занесения в базу новых данных']);
            }
        }
        $customers = Customer::with('cars.model.firm')->get();
        $models= CarModel::with('firm')->get();
        return view('customers.index',['customers' => $customers,'models'=>$models,'success'=>'Данные изменены успешно!']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(CustomerPostRequest $request){
        try{
            $customer = Customer::create($request->all());
            if(isset($request->state) && isset($request->model)){
                $this->createCar([
                    'model_id' => $request->model,
                    'customer_id' => $customer->id,
                    'state_number' => $request->state,
                ]);
            };
        }catch(QueryException $e){
            $customers = Customer::with('cars.model.firm')->get();
            $models= CarModel::with('firm')->get();
            if(strpos($e->errorInfo[2],'customer.phone')){
                return view('customers.index',['customers' => $customers,'models'=>$models,'error'=>'Такой номер телефона уже есть в базе']);
            } else{
                return view('customers.index',['customers' => $customers,'models'=>$models,'error'=>'Ошибка занесения в базу новых данных']);
            }
        }
        $customers = Customer::with('cars.model.firm')->get();
        $models= CarModel::with('firm')->get();
        return view('customers.index',['customers' => $customers,'models'=>$models,'success'=>'Данные добавлены успешно!']);
    }

    private function createCar($data)
    {
        Car::create($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function destroy(Request $request){
        try{
            Customer::with('cars')->where('customers.id',$request->id)->delete();
        }catch(QueryException $e){
            $customers = Customer::with('cars.model.firm')->get();
            $models= CarModel::with('firm')->get();
            return view('customers.index',['customers' => $customers,'models'=>$models,'error'=>'Ошибка занесения в базу новых данных']);
        }
        $customers = Customer::with('cars.model.firm')->get();
        $models= CarModel::with('firm')->get();
        return view('customers.index',['customers' => $customers,'models'=>$models,'success'=>'Данные удалены успешно!']);
    }
}
