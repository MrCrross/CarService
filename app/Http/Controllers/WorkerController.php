<?php

namespace App\Http\Controllers;

use App\Models\Work;
use App\Models\Worker;
use App\Models\Post;
use App\Models\WorkerContract;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WorkerController extends Controller
{
    /**
     * WorkerController constructor.
     */
    function __construct()
    {
        $this->middleware('permission:worker-create', ['only' => ['create']]);
        $this->middleware('permission:worker-edit', ['only' => ['index','update','show','search']]);
        $this->middleware('permission:contract-edit', ['only' => ['download']]);
        $this->middleware('permission:worker-delete', ['only' => ['destroy']]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return $this->answer('', '');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $workers = Worker::where('first_name','like','%'.$request->search.'%')
            ->orWhere('last_name','like','%'.$request->search.'%')
            ->orWhere('father_name','like','%'.$request->search.'%')
            ->orWhere('phone','like','%'.$request->search.'%')
            ->with('post.works.work', 'contracts.post','orders')
            ->get();
        $posts = Post::get();
        return response()->json([
            'workers'=>$workers,
            'posts'=>$posts
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $worker = Worker::where('id',$id)->with('post.works.work', 'contracts.post','allOrders')->get();
        return view('workers.show',['worker'=>$worker]);
    }

    /**
     * @param Request $request
     */
    public function create(Request $request)
    {
        try {
            Worker::create($request->all());
        } catch (QueryException $e) {
            return $this->answer('error', 'Ошибка. Введенные данные некорректные');
        }
        return $this->answer('success', 'Данные добавлены успешно');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function update(Request $request)
    {
        dd($request->file('contract'));
        try {
            Worker::where('id', $request->id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'father_name' => $request->father_name,
                'phone' => $request->phone
            ]);
            if (isset($request->post) && isset($request->contract)) {
                $validator = Validator::make(
                    ['file' => $request->file('contract')],
                    [
                        'file' => 'mimes:docx,doc,pdf |max:15360',
                    ],
                );
                if ($validator->passes()) {
                    $date = Worker::where('id',$request->id)->where('post_id',$request->post)->get();
                    if(count($date)==0){
                        Worker::where('id', $request->id)->update([
                            'post_id' => $request->post
                        ]);
                        $url = Storage::disk('public')->put('contracts', $request->file('contract'));
                        $url = 'storage/' . $url;
                        WorkerContract::create([
                            'worker_id' => $request->id,
                            'post_id' => $request->post,
                            'contract' => $url,
                            'post_change'=>date("Y-m-d")
                        ]);
                    }else{
                        return $this->answer('error', 'Сотрудник уже имеет эту должность');
                    }
                } else if ($validator->failed()) {
                    return $this->answer('error', 'Файл не соответствующего формата');
                }
            } else if (isset($request->post) && !isset($request->contract)) {
                return $this->answer('error', 'Без документа подтверждающего перехода должности, система не может изменить должность сотрудника');
            }
        } catch (QueryException $e) {
            if (strpos($e->errorInfo[2], 'worker.phone')) {
                $this->answer('error', 'Такой номер телефона уже есть в базе');
            } else {
                return $this->answer('error', 'Ошибка занесения в базу новых данных');
            }
        }
        return $this->answer('success', 'Данные обновлены успешно');
    }

    /**
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        try {
            Worker::where('id', $request->id)->delete();
        } catch (QueryException $e) {
            return $this->answer('error', 'Ошибка. Введенные данные некорректные');
        }
        return $this->answer('success', 'Данные удалены успешно');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Request $request){
        return response()->download($request->contract);
    }

    /**
     * @param $res string "success" or "error"
     * @param $message string message for user about results
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public static function answer($res, $message)
    {
        $workers = Worker::with('post.works.work', 'contracts.post','orders')->get();
        $posts = Post::get();
        $works = Work::get();
        if ($res == '' and $message == '') {
            return view('workers.index', ['workers' => $workers, 'posts' => $posts, 'works' => $works]);
        }
        return view('workers.index', ['workers' => $workers, 'posts' => $posts, 'works' => $works, $res => $message]);
    }
}
