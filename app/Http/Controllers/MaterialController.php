<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * MaterialController constructor.
     */
    function __construct()
    {
        $this->middleware('permission:material-edit', ['only' => ['index','create']]);
        $this->middleware('permission:material-create', ['only' => ['create']]);
        $this->middleware('permission:material-delete', ['only' => ['destroy']]);
    }

    public function index(){
        return $this->answer('','');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createOrder(Request $request)
    {
        try{
            Material::create($request->all());
        }catch(QueryException $e){
            return OrderController::answer('error','Ошибка. Введенные данные некорректные');
        }
        return OrderController::answer('success','Данные добавлены успешно');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        try{
            Material::create($request->all());
        }catch(QueryException $e){
            return $this->answer('error','Ошибка. Введенные данные некорректные');
        }
        return $this->answer('success','Данные добавлены успешно');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function update(Request $request)
    {
        try{
            Material::where('id',$request->id)->update([
                'name'=>$request->name,
                'price'=>$request->price,
                'count'=>$request->count
            ]);
        }catch(QueryException $e){
            return $this->answer('error','Ошибка. Введенные данные некорректные');
        }
        return $this->answer('success','Данные изменены успешно');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function destroy(Request $request)
    {
        try{
            Material::where('id',$request->id)->delete();
        }catch(QueryException $e){
            return $this->answer('error','Ошибка. Введенные данные некорректные');
        }
        return $this->answer('success','Данные удалены успешно');
    }

    /**
     * @param $res
     * @param $message
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function answer($res,$message)
    {
        $materials = Material::all();
        if ($res == '' and $message == '') {
            return view('materials.index', ['materials' => $materials]);
        }
        return view('materials.index', ['materials' => $materials, $res => $message]);
    }
}
