<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PostController extends Controller
{

    /**
     * WorkController constructor.
     */
    function __construct()
    {
        $this->middleware('permission:post-create', ['only' => ['create']]);
        $this->middleware('permission:post-delete', ['only' => ['destroy']]);
    }

    /**
    * @param Request $request
    * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    */
    public function create(Request $request)
    {
        try{
            Post::create($request->all());
        }catch(QueryException $e){
            return  WorkerController::answer('error','Ошибка. Введенные данные некорректные');
        }
        return WorkerController::answer('success','Данные добавлены успешно');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function destroy(Request $request)
    {
        try {
            Post::where('id', $request->id)->delete();
        } catch (QueryException $e) {
            return  WorkerController::answer('error','Ошибка. Введенные данные некорректные');
        }
        return WorkerController::answer('success','Данные обновлены успешно');
    }
}
