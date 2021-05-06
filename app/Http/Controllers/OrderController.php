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
use Spatie\Permission\Models\Permission;

class OrderController extends Controller
{
    /**
     * OrderController constructor.
     */
    function __construct()
    {
        date_default_timezone_set ('Asia/Irkutsk');
        $this->middleware('permission:order-list', ['only' => ['index','show']]);
        $this->middleware('permission:order-edit', ['only' => ['calc','getCalc','search']]);
        $this->middleware('permission:order-create', ['only' => ['create']]);
        $this->middleware('permission:order-delete', ['only' => ['destroy']]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(){
        return $this->answer('','');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function calc(){
        $min=Order::min('registration');
        $max=Order::max('registration');
        return view('orders.calc',['min'=>$min,'max'=>$max]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request){
        $compositions = OrderComposition::join('workers','workers.id','=','order_compositions.worker_id')
            ->join('orders','orders.id','=','order_compositions.order_id')
            ->join('works','works.id','=','order_compositions.work_id')
            ->join('posts','posts.id','=','workers.post_id')
            ->where('registration','>=',$request->start)
            ->where('registration','<=',$request->end)
            ->where('works.name','like','%'.$request->search.'%')
            ->orWhere('works.price','like','%'.$request->search.'%')
            ->orWhere('posts.name','like','%'.$request->search.'%')
            ->orWhere('posts.salary','like','%'.$request->search.'%')
            ->orWhere('works.name','like','%'.$request->search.'%')
            ->orWhere('workers.first_name','like','%'.$request->search.'%')
            ->orWhere('workers.last_name','like','%'.$request->search.'%')
            ->orWhere('workers.father_name','like','%'.$request->search.'%')
            ->orWhere('workers.phone','like','%'.$request->search.'%')
            ->select('order_id')
            ->groupBy('order_id')
            ->get()->toArray();
        $materials = OrderMaterial::join('orders','orders.id','=','order_materials.order_id')
            ->join('materials','materials.id','=','order_materials.material_id')
            ->where('registration','>=',$request->start)
            ->where('registration','<=',$request->end)
            ->where('materials.name','like','%'.$request->search.'%')
            ->orWhere('materials.price','like','%'.$request->search.'%')
            ->orWhere('materials.count','like','%'.$request->search.'%')
            ->orWhere('order_materials.count','like','%'.$request->search.'%')
            ->select('order_id')
            ->groupBy('order_id')
            ->get()->toArray();
        $unions = array_merge($compositions,$materials);
        $arr=[];
        foreach ($unions as $union){
            array_push($arr,$union['order_id']);
        }
        $order = Order::join('cars','cars.id','=','orders.car_id')
            ->join('car_models','car_models.id','=','cars.model_id')
            ->join('car_firms','car_firms.id','=','car_models.firm_id')
            ->join('customers','customers.id','=','cars.customer_id')
            ->where('registration','>=',$request->start)
            ->where('registration','<=',$request->end)
            ->where('cars.state_number','like','%'.$request->search.'%')
            ->orWhere('car_models.name','like','%'.$request->search.'%')
            ->orWhere('car_models.year_release','like','%'.$request->search.'%')
            ->orWhere('car_firms.name','like','%'.$request->search.'%')
            ->orWhere('customers.first_name','like','%'.$request->search.'%')
            ->orWhere('customers.last_name','like','%'.$request->search.'%')
            ->orWhere('customers.father_name','like','%'.$request->search.'%')
            ->orWhere('customers.phone','like','%'.$request->search.'%')
            ->orwhereIn('orders.id',$arr)
            ->with('car.model.firm','car.customer','compositions.worker.post','compositions.work','materials.material')
            ->get();
        return response()->json($order);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCalc(Request $request){
        $order = Order::where('registration','>=',$request->start)
            ->where('registration','<=',$request->end)
            ->with('car.model.firm','car.customer','compositions.worker.post','compositions.work','materials.material')
            ->get();
        return response()->json($order);
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
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
