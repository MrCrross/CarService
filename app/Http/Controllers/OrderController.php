<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CarFirm;
use App\Models\CarModel;
use App\Models\Customer;
use App\Models\Material;
use App\Models\Order;
use App\Models\OrderComposition;
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
        $this->middleware('permission:order-list|order-print', ['only' => ['index']]);
        $this->middleware('permission:order-print', ['only' => ['show']]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(){
        return $this->answer('','');
    }

    public function create(Request $request){
        $order=Order::create([
            'registration'=>date("Y-m-d"),
            'completed'=>date("Y-m-d")
        ]);
//        $data = $request;
        OrderComposition::create([
           'order_id'=>$order->id,
        ]);
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
    public static function answer($res,$message){
        $customers = Customer::all();
        $materials = Material::all();
        $works = Work::all();
        $models = CarModel::with('firm')->get();
        $firms = CarFirm::all();
        if ($res == '' and $message == '') {
            return view('orders.index',['customers'=>$customers,'materials'=>$materials,'works'=>$works,'models'=>$models,'firms'=>$firms]);
        }
        return view('orders.index',['customers'=>$customers,'materials'=>$materials,'works'=>$works,'models'=>$models,'firms'=>$firms,$res => $message]);

    }
    private function print($data){

    }
}
