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
    //
    function __construct()
    {
        $this->middleware('permission:customer-list', ['only' => ['index']]);
        $this->middleware('permission:customer-create', ['only' => ['index','create']]);
        $this->middleware('permission:customer-edit', ['only' => ['index','update']]);
        $this->middleware('permission:customer-delete', ['only' => ['index','destroy']]);
    }

    public function index()
    {
        $cars = Car::with('customer','model.firm')->get();
        $models= CarModel::with('firm')->get();
        $customers = Customer::get();
        $firms = CarFirm::get();
        return view('cars.index',['cars' => $cars,'models'=>$models,'customers'=>$customers,'firms'=>$firms]);
    }

    /**
     * edit data
     **/
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

    public function createFirm(Request $request){
        CarFirm::create($request->all());
        $cars = Car::with('customer','model.firm')->get();
        $models= CarModel::with('firm')->get();
        $customers = Customer::get();
        $firms = CarFirm::get();
        return view('cars.index',['cars' => $cars,'models'=>$models,'customers'=>$customers,'firms'=>$firms,'success'=>'Данные созданы успешно!']);
    }

    public function destroy(Request $request){
        Car::where('id',$request->id)->delete();
        $cars = Car::with('customer','model.firm')->get();
        $models= CarModel::with('firm')->get();
        $customers = Customer::get();
        $firms = CarFirm::get();
        return view('cars.index',['cars' => $cars,'models'=>$models,'customers'=>$customers,'firms'=>$firms,'success'=>'Данные удалены успешно!']);
    }
}
