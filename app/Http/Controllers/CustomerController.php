<?php

namespace App\Http\Controllers;

use App\Customer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CustomerController extends BaseController
{
    private function convertNumbersToFriendlyText ($pList, $elementNames){
        foreach ($pList as $item)
            foreach ($elementNames as $element) {
                $item[$element.'_text']='';
                switch (true){
                    case  ($item[$element] >= 1000000) :
                        $item[$element.'_text'] = number_format($item[$element]/1000000,1,'.',',').'m';
                        break;
                    case ($item[$element] <  1000000 && $item[$element] >=1000):
                        $item[$element.'_text'] = number_format($item[$element]/1000,1,'.',',').'k';
                        break;
                    case ($item[$element]>-1000):
                        $item[$element.'_text'] = number_format($item[$element],1,'.',',');
                        break;
                    case ($item[$element]>-1000000):
                        $item[$element.'_text'] = number_format($item[$element]/1000,1,'.',',').'k';
                        break;
                    default:
                        $item[$element.'_text'] = number_format($item[$element]/1000000,1,'.',',').'m';
                        break;
                }
            }
        return $pList;
    }

    public function index(Request $request){

        $searchQuery=$request->input('searchQuery');
        $searchMethod=$request->input('searchMethod');

        $customers=Customer::with('group')->whereRaw(Session('customerSelectQuery'));

        if (!empty($searchQuery))
        {
            if ($searchMethod=='exactMatch') {
                $customers = $customers->where(function ($query) use ($searchQuery) {
                    $query->where('customers.id', '=', $searchQuery)
                        ->orWhere('customers.name', '=', $searchQuery)
                        ->orWhereHas('group', function ($q) use ($searchQuery) {
                            $q->where('name', '=',$searchQuery );
                        });
                });
            }
            else
            $customers=$customers->where(function ($query) use ($searchQuery){
                $query->where('customers.id','like','%'.$searchQuery.'%')
                    ->orWhere('customers.name','like','%'.$searchQuery.'%')
                    ->orWhereHas('group', function($q) use ($searchQuery) {
                        $q->where('name', 'like', '%'.$searchQuery.'%');
                    });
            });
        }
        $customers=$customers->with(array('accountPlans'=>function($query){
            $query->where('account_plans.active',1);
        }))->paginate(20);
        $this->prepareCommonViews();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Customer $customer)
    {
        //
    }

    public function edit(Customer $customer)
    {
        //
    }

    public function update(Request $request, Customer $customer)
    {
        //
    }

    public function destroy(Customer $customer)
    {
        //
    }
}
