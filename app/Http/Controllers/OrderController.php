<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    //
    function __construct()
    {
        //  $this->middleware('permission:order-list|order-create|order-edit|order-delete', ['only' => ['index','store']]);
         $this->middleware('permission:order-list', ['only' => ['index']]);
        //  $this->middleware('permission:order-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:order-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:order-delete', ['only' => ['destroy']]);
    }

    public function index(){
        return view('orders.index');
    }
}
