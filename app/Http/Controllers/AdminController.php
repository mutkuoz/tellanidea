<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\customer;
use App\User;
use App\Kpi;
use Redirect;
use DB;

class AdminController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function redirectIfNotAdmin(){
        if (!session('adminRights'))
            return Redirect::to('home');
    }

    public function index()
    {
        $this->redirectIfNotAdmin();
        $this->prepareCommonViews();
        return view ('Admin.index');
    }




    public function calculateKPIs(){
        $this->redirectIfNotAdmin();

        $affected = DB::update('UPDATE customers C left outer join
(select customer_id, sum(revenues) as revenues from monthly_datas where product_id=0 and yearMonth>=201700 and yearMonth<=201712 group by customer_id) M
on C.id=M.customer_id
SET C.revenues_ytd_eop=M.revenues');

        return view ('Admin.index', compact('affected'));
    }
}
