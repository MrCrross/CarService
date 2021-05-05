<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerPostRequest;
use App\Models\Car;
use App\Models\CarFirm;
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
        $this->middleware('permission:customer-list', ['only' => ['getCar']]);
        $this->middleware('permission:customer-create', ['only' => ['create','jsonCreate']]);
        $this->middleware('permission:customer-edit', ['only' => ['index','update','search']]);
        $this->middleware('permission:customer-delete', ['only' => ['destroy']]);
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function search(Request $request)
    {
        $customers = Customer::where('first_name','like','%'.$request->search.'%')
            ->orWhere('last_name','like','%'.$request->search.'%')
            ->orWhere('father_name','like','%'.$request->search.'%')
            ->orWhere('phone','like','%'.$request->search.'%')
            ->with('cars.model.firm')
            ->get();
        $models= CarModel::with('firm')->get();
        return response()->json(['customers'=>$customers,'models'=>$models]);
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
            foreach ($request->car as $key=>$car){
                Car::where('id',$car)->update([
                    'model_id'=>$request->model[$key],
                    'state_number'=>$request->state[$key]
                ]);
            }
        }catch(QueryException $e){
            dd($e);
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
                Car::create([
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

    /**
     * @param $data
     */
    private function createCar($data)
    {
        Car::create($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public  function getCar(Request $request){
        $cars = Customer::where('id',$request->id)->with('cars.model.firm')->get();
        return response()->json($cars);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public  function jsonCreate(Request $request){
        $customer = Customer::create([
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'father_name'=>$request->father_name,
            'phone'=>$request->phone
        ]);
        if(isset($request->model_id)){
            $this->createCar([
                'model_id' => $request->model_id,
                'customer_id' => $customer->id,
                'state_number' => $request->state
            ]);
        };
        if(isset($request->firm_id)){
           $model= CarModel::create([
                'firm_id'=>$request->firm_id,
                'name'=>$request->model_name,
                'year_release'=>$request->model_year
            ]);
            $this->createCar([
                'model_id' => $model->id,
                'customer_id' => $customer->id,
                'state_number' => $request->state
            ]);
        };
        if(isset($request->firm_name)){
            $firm =CarFirm::create([
                'name'=>$request->firm_name
            ]);
            $model= CarModel::create([
                'firm_id'=>$firm->id,
                'name'=>$request->model_name,
                'year_release'=>$request->model_year
            ]);
            $this->createCar([
                'model_id' => $model->id,
                'customer_id' => $customer->id,
                'state_number' => $request->state
            ]);
        };
        return response()->json('Пользователь добавлен');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function destroy(Request $request){
        try{
            Customer::where('id',$request->id)->delete();
        }catch(QueryException $e){
            $customers = Customer::with('cars.model.firm')->get();
            $models= CarModel::with('firm')->get();
            return view('customers.index',['customers' => $customers,'models'=>$models,'error'=>'Ошибка удаления данных']);
        }
        $customers = Customer::with('cars.model.firm')->get();
        $models= CarModel::with('firm')->get();
        return view('customers.index',['customers' => $customers,'models'=>$models,'success'=>'Данные удалены успешно!']);
    }
}
