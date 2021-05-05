<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CarFirm;
use App\Models\CarModel;
use App\Models\Customer;
use App\Models\Material;
use App\Models\Order;
use App\Models\OrderComposition;
use App\Models\OrderMaterial;
use App\Models\Work;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * OrderController constructor.
     */
    function __construct()
    {
        $this->middleware('permission:order-list', ['only' => ['index','show']]);
        $this->middleware('permission:order-edit', ['only' => ['indexEdit']]);
        $this->middleware('permission:order-create', ['only' => ['create']]);
        $this->middleware('permission:order-delete', ['only' => ['destroy']]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(){
        return $this->answer('','');
    }

    public function show($id){
       $order = Order::where('id',$id)->with('car.model.firm','car.customer','compositions.worker','compositions.work','materials.material')->get();
       return view('orders.show',['order'=>$order]);

    }
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request){
        try{
            $order=Order::create([
                'car_id'=>$request->car,
                'price'=>$request->total,
                'registration'=>date("Y-m-d"),
                'completed'=>date("Y-m-d")
            ]);
            foreach ($request->work as $key=> $work){
                OrderComposition::create([
                    'order_id'=>$order->id,
                    'worker_id'=>$request->worker[$key],
                    'work_id'=>$work,
                ]);
            }
            foreach ($request->material as $key=> $material){
                OrderMaterial::create([
                    'order_id'=>$order->id,
                    'material_id'=>$material,
                    'count'=>$request->count[$key],
                ]);
                $count = Material::where('id',$material)->get();
                Material::where('id',$material)->update([
                    'count'=> ($count[0]->count - $request->count[$key])
                ]);
            }
        }
        catch(QueryException $e){
            return $this->answer('error','Произошла ошибка системы');
        }
        return $this->answer('success','Данные добавлены');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function destroy(Request $request)
    {
        try{
            Order::where('id',$request->id)->delete();
        }catch(QueryException $e){
            return $this->answer('error','Залупа получается');
        }
        return $this->answer('success','Данные удалены');
    }

    /**
     * @param $res
     * @param $message
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public static function answer($res,$message){
        $customers = Customer::all();
        $materials = Material::where('count','>=','1')->get();
        $works = Work::all();
        $models = CarModel::with('firm')->get();
        $firms = CarFirm::all();
        $num = Order::latest()->first();
        if($num === null) {$num=0;}
        if ($res == '' and $message == '') {
            return view('orders.index',['customers'=>$customers,'materials'=>$materials,'works'=>$works,'models'=>$models,'firms'=>$firms,'num'=>$num]);
        }
        return view('orders.index',['customers'=>$customers,'materials'=>$materials,'works'=>$works,'models'=>$models,'firms'=>$firms,'num'=>$num,$res => $message]);

    }
}
