<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Work;
use App\Models\WorkHasPost;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    /**
     * WorkController constructor.
     */
    function __construct()
    {
        $this->middleware('permission:work-list', ['only' => ['index']]);
        $this->middleware('permission:work-create', ['only' => ['create']]);
        $this->middleware('permission:work-edit', ['only' => ['update','updateWorkPost','index']]);
        $this->middleware('permission:work-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){
        $posts = Post::with('works.work')->get()->toArray();
        $works = Work::all();
        return view('workers.works', ['posts' => $posts,'works'=>$works]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createWorkPost(Request $request)
    {
        try{
            WorkHasPost::where('post_id',$request->post_id)->delete();
            if(isset($request->works)){
                foreach ($request->works as $work){
                    WorkHasPost::create([
                        'post_id'=>$request->post_id,
                        'work_id'=>$work
                    ]);
                }
            }
        }catch(QueryException $e){
            return  WorkerController::answer('error','Ошибка. Введенные данные некорректные');
        }
        return  WorkerController::answer('success','Данные добавлены успешно');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        try{
            Work::create($request->all());
        }catch(QueryException $e){
            return  WorkerController::answer('error','Ошибка. Введенные данные некорректные');
        }
        return WorkerController::answer('success','Данные добавлены успешно');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function update(Request $request)
    {
        try{
            Work::where('id',$request->id)->update([
                'name'=>$request->name,
                'price'=>$request->price
            ]);
        }catch(QueryException $e){
            return  WorkerController::answer('error','Ошибка. Введенные данные некорректные');
        }
        return WorkerController::answer('success','Данные обновлены успешно');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function updateWorkPost(Request $request)
    {
        try{
            $works= WorkHasPost::where('post_id',$request->id)->get()->toArray();
            $reqwork=$request->works;
            foreach ($works as $work){
                if(array_search($work['work_id'],$reqwork) === false){
                    WorkHasPost::where('post_id',$request->id)
                        ->where('work_id',$work['work_id'])
                        ->delete();
                }else{
                    array_splice($reqwork,array_search($work['work_id'],$reqwork),1);
                }
            }
            foreach ($reqwork as $work){
                WorkHasPost::create([
                    'post_id'=>$request->id,
                    'work_id'=>$work
                ]);
            }
        }catch(QueryException $e){
            return  WorkerController::answer('error','Ошибка. Введенные данные некорректные');
        }
        return WorkerController::answer('success','Данные обновлены успешно');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function destroy(Request $request)
    {
        try {
            Work::where('id', $request->id)->delete();
        } catch (QueryException $e) {
            return  WorkerController::answer('error','Ошибка. Введенные данные некорректные');
        }
        return WorkerController::answer('success','Данные обновлены успешно');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public  function getWorker(Request $request){
        $workers = Work::where('id',$request->id)->with('posts.post.workers')->get();
        return response()->json($workers);
    }
}
