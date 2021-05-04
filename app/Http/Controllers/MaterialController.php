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
        $this->middleware('permission:material-create', ['only' => ['create']]);
        $this->middleware('permission:material-delete', ['only' => ['destroy']]);
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
            return  OrderController::answer('error','Ошибка. Введенные данные некорректные');
        }
        return OrderController::answer('success','Данные добавлены успешно');
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
            return  OrderController::answer('error','Ошибка. Введенные данные некорректные');
        }
        return OrderController::answer('success','Данные удалены успешно');
    }
}
