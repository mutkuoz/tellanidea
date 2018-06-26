<?php

namespace App\Http\Controllers;

use App\AccountPlan;
use App\Customer;
use App\User;
use App\Opportunity;
use Illuminate\Http\Request;
use App\Product;

class AccountPlanController extends BaseController
{

    private function multiExplode ($delimiters,$string) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }

    public function addActions(Request $request){
        $this->prepareCommonViews();
        if ($request->isMethod('post')) {
            $customers=$this->multiExplode(array(PHP_EOL,',',' ',),$request->input('customers'));
            $product=Product::find($request->input('productSelectionInput'));
            $errors='';
            $success=0;
            foreach ($customers as $customer){
                $accountPlan=AccountPlan::where('active',1)->where('customer_id',$customer)->first();
                if (empty($accountPlan)){
                    $errors=$errors.'No Account Plan Found For Customer '.$customer.PHP_EOL;
                    continue;
                }
                $opp=new Opportunity();
                $opp['accountPlan_id']=$accountPlan['id'];

                $parameters=[
                    'status'=>'Recommended',
                    'productId'=>$product['id'],
                    'productName'=>$product['name'],
                    'actingUserId'=>session('actingUserId'),
                    'actingUserName'=>session('actingUserName'),
                    'userId'=>session('userId'),
                    'usaerName'=>session('userName'),
                    'customerId'=>$customer,
                    'plannedDate'=>null
                ];
                $opp['parameters']=json_encode($parameters);
                $opp->save();
                $parameters['opportunityId']=$opp['id'];
                $opp['parameters']=json_encode($parameters);
                $opp->save();
                $success+=1;
            }

            return view('Accountplans.addActionsResult',compact('errors','success'));
        }
        return view('Accountplans.addActions');
    }


    public function index()
    {
        //
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        //
    }
    public function show(AccountPlan $accountPlan)
    {
        //
    }

    public function edit(AccountPlan $accountPlan)
    {
        //
    }
    public function update(Request $request, AccountPlan $accountPlan)
    {
        //
    }

    public function destroy(AccountPlan $accountPlan)
    {
        //
    }

    public function showPlanOf( $customerId){
        $selectedUsers=User::descendantsAndSelf( session('actingUserId'),['id']);
        $customerList=Customer::find( $customerId);
        $accountPlan=AccountPlan::where('active', 1)->where('customer_id',$customerId)->first();
        $opportunities=Opportunity::where('accountPlan_id','=',$accountPlan['id'])->get();
        $this->prepareCommonViews();
        return view('AccountPlans.show', compact('customerList','selectedUsers', 'customerId','accountPlan','opportunities'));
    }
}
