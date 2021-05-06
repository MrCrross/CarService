<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarFirm;
use App\Models\CarModel;
use App\Models\Customer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * CarController constructor.
     */
    function __construct()
    {
        $this->middleware('permission:customer-create', ['only' => ['create','jsonCreate']]);
        $this->middleware('permission:customer-edit', ['only' => ['index','update']]);
        $this->middleware('permission:firm-create', ['only' => ['createFirm']]);
        $this->middleware('permission:model-create', ['only' => ['createModel']]);
        $this->middleware('permission:firm-edit', ['only' => ['updateFirm']]);
        $this->middleware('permission:model-edit', ['only' => ['updateModel']]);
        $this->middleware('permission:customer-delete', ['only' => ['destroy']]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $cars = Car::with('customer','model.firm')->get();
        $models= CarModel::with('firm')->get();
        $customers = Customer::get();
        $firms = CarFirm::get();
        return view('cars.index',['cars' => $cars,'models'=>$models,'customers'=>$customers,'firms'=>$firms]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function update(Request $request){
        try{
            Car::where('id',$request->id)->update([
                'model_id'=>$request->model,
                'customer_id'=>$request->customer,
                'state_number'=>$request->state
            ]);
        }catch(QueryException $e){
            $cars = Car::with('customer','model.firm')->get();
            $models= CarModel::with('firm')->get();
            $customers = Customer::get();
            $firms = CarFirm::get();
            if(strpos($e->errorInfo[2],'car.state_number')){
                return view('cars.index',['cars' => $cars,'models'=>$models,'customers'=>$customers,'firms'=>$firms,'error'=>'Такой госномер уже есть в базе']);
            } else{
                return view('cars.index',['cars' => $cars,'models'=>$models,'customers'=>$customers,'firms'=>$firms,'error'=>'Ошибка занесения в базу новых данных']);
            }
        }
        $cars = Car::with('customer','model.firm')->get();
        $models= CarModel::with('firm')->get();
        $customers = Customer::get();
        $firms = CarFirm::get();
        return view('cars.index',['cars' => $cars,'models'=>$models,'customers'=>$customers,'firms'=>$firms,'success'=>'Данные изменены успешно!']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request){
        try{
            Car::create([
                'model_id'=>$request->model,
                'customer_id'=>$request->customer,
                'state_number'=>$request->state
            ]);
        }catch(QueryException $e){
            $cars = Car::with('customer','model.firm')->get();
            $models= CarModel::with('firm')->get();
            $customers = Customer::get();
            $firms = CarFirm::get();
            if(strpos($e->errorInfo[2],'car.state_number')){
                return view('cars.index',['cars' => $cars,'models'=>$models,'customers'=>$customers,'firms'=>$firms,'error'=>'Такой госномер уже есть в базе']);
            } else{
                return view('cars.index',['cars' => $cars,'models'=>$models,'customers'=>$customers,'firms'=>$firms,'error'=>'Ошибка занесения в базу новых данных']);
            }
        }
        $cars = Car::with('customer','model.firm')->get();
        $models= CarModel::with('firm')->get();
        $customers = Customer::get();
        $firms = CarFirm::get();
        return view('cars.index',['cars' => $cars,'models'=>$models,'customers'=>$customers,'firms'=>$firms,'success'=>'Данные созданы успешно!']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createModel(Request $request){
        CarModel::create([
            'firm_id'=>$request->firm,
            'name'=>$request->name,
            'year_release'=>$request->year
        ]);
        $cars = Car::with('customer','model.firm')->get();
        $models= CarModel::with('firm')->get();
        $customers = Customer::get();
        $firms = CarFirm::get();
        return view('cars.index',['cars' => $cars,'models'=>$models,'customers'=>$customers,'firms'=>$firms,'success'=>'Данные созданы успешно!']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function updateModel(Request $request){
        CarFirm::where('id',$request->firm_id)->update([
            'name'=>$request->firm_name
        ]);
        CarModel::where('id',$request->id)->update([
            'firm_id'=>$request->firm_id,
            'name'=>$request->name,
            'year_release'=>$request->year
        ]);
        $cars = Car::with('customer','model.firm')->get();
        $models= CarModel::with('firm')->get();
        $customers = Customer::get();
        $firms = CarFirm::get();
        return view('cars.index',['cars' => $cars,'models'=>$models,'customers'=>$customers,'firms'=>$firms,'success'=>'Данные изменены успешно!']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createFirm(Request $request){
        CarFirm::create($request->all());
        $cars = Car::with('customer','model.firm')->get();
        $models= CarModel::with('firm')->get();
        $customers = Customer::get();
        $firms = CarFirm::get();
        return view('cars.index',['cars' => $cars,'models'=>$models,'customers'=>$customers,'firms'=>$firms,'success'=>'Данные созданы успешно!']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function updateFirm(Request $request){
        CarFirm::where('id',$request->id)->update([
            'name'=>$request->name
        ]);
        $cars = Car::with('customer','model.firm')->get();
        $models= CarModel::with('firm')->get();
        $customers = Customer::get();
        $firms = CarFirm::get();
        return view('cars.index',['cars' => $cars,'models'=>$models,'customers'=>$customers,'firms'=>$firms,'success'=>'Данные изменены успешно!']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public  function jsonCreate(Request $request){
        if(isset($request->model_id)){
            Car::create([
                'model_id' => $request->model_id,
                'customer_id' => $request->customer_id,
                'state_number' => $request->state
            ]);
        };
        if(isset($request->firm_id)){
            $model= CarModel::create([
                'firm_id'=>$request->firm_id,
                'name'=>$request->model_name,
                'year_release'=>$request->model_year
            ]);
            Car::create([
                'model_id' => $model->id,
                'customer_id' => $request->customer_id,
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
            Car::create([
                'model_id' => $model->id,
                'customer_id' => $request->customer_id,
                'state_number' => $request->state
            ]);
        };
        return response()->json('Автомобиль добавлен');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function destroy(Request $request){
        Car::where('id',$request->id)->delete();
        $cars = Car::with('customer','model.firm')->get();
        $models= CarModel::with('firm')->get();
        $customers = Customer::get();
        $firms = CarFirm::get();
        return view('cars.index',['cars' => $cars,'models'=>$models,'customers'=>$customers,'firms'=>$firms,'success'=>'Данные удалены успешно!']);
    }
}
