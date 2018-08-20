<?php

namespace App\Http\Controllers;

use App\AccountPlan;
use App\Customer;
use App\User;
use App\Opportunity;
use Illuminate\Http\Request;
use App\Product;
use Auth;
use App\AnalyticsModelOutput;
use App\MonthlyData;
use Illuminate\Support\Facades\DB;

class AccountPlanController extends BaseController
{

    private function multiExplode ($delimiters,$string) {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }

    public function addActionsWithAnalytics(Request $request){
        $this->prepareCommonViews();
        $minProbabilityThreshold=floatval($request->input('minProbabilityThreshold'));
        $customers=AnalyticsModelOutput::where('parameters->probability','>',$minProbabilityThreshold)->get();
        $errors='';
        $success=0;
        foreach ($customers as $customer) {
            $accountPlan=AccountPlan::where('active',1)->where('customer_id',$customer['customer_id'])->first();
            if (empty($accountPlan)){
                $errors=$errors.'No Account Plan Found For Customer '.$customer.PHP_EOL;
                continue;
            }
            $opp=new Opportunity();
            $opp['accountPlan_id']=$accountPlan['id'];

            $parameters=[
                'type'=>'penetration',
                'status'=>'Recommended with AA',
                'productId'=>1,
                'productName'=>'Product',
                'actingUserId'=>session('actingUserId'),
                'actingUserName'=>session('actingUserName'),
                'userId'=>Auth::id(),
                'usaerName'=>Auth::user()->name,
                'customerId'=>$customer['customer_id'],
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

    public function countOfActionsWithAnalytics(Request $request){
        $minProbabilityThreshold=floatval($request->input('minProbabilityThreshold'));
        $customerCount=AnalyticsModelOutput::where('parameters->probability','>',$minProbabilityThreshold)
            ->where('product_id','=',$request->input('productSelectionInput'))
            ->count();
        return $customerCount;
    }

    public function minThresholdWithAnalytics(Request $request){
        $numberOfCustomersTargeted=floatval($request->input('numberOfCustomersTargeted'));
        $nthCustomer=AnalyticsModelOutput::where('product_id','=',$request->input('productSelectionInput'))
            ->orderByRaw('parameters->\'$.probability\' desc')
            ->skip($numberOfCustomersTargeted-1)
            ->limit(1)->get();
        return json_decode($nthCustomer[0]['parameters'],true)['probability'];
    }

    public function addManualActions (Request $request){
        $customers=$this->multiExplode(array(PHP_EOL,',',' ',),$request->input('customers'));
        $product=Product::find($request->input('productId'));
        $actionType=$request->input('actionType');
        $volumeTarget=floatval($request->input('targetVolume'));
        $spreadTarget=floatval($request->input('targetSpread'));
        $errors=[];
        $success=0;
        foreach ($customers as $customer){
            $accountPlan=AccountPlan::where('active',1)->where('customer_id',$customer)->first();
            // check if there is an account plan for the customer.
            if (empty($accountPlan)){
                $errors[]='No Account Plan Found For Customer'.$customer; continue;
            }

            $md=MonthlyData::where(['customer_id'=>$customer,'product_id'=>$product['id'],'yearMonth'=>201712])->first();
            if ($actionType=='penetration' && $md['ownership']==1) {
                $errors[]=$customer.' already has this product';continue;
            }
            if ($actionType=='volume' && $md['volume_eop']>=$volumeTarget) {
                $errors[]=$customer.' has higher volume (Current Vol:'.$md['volume_eop'].' Target Vol:'.$volumeTarget.')';continue;
            }

            if ($actionType=='spread' && $md['volume_avg']>0 && (($md['revenues']/$md['volume_avg'])*12)>=$spreadTarget) {
                $errors[]=$customer.' has higher spread (Current Spread:'.($md['revenues']/$md['volume_avg']*12).' Target Spread:'.$spreadTarget.')';continue;
            }

            $target=null;
            if ($actionType=='volume') $target=$volumeTarget;
            if ($actionType=='spread') $target=$spreadTarget;

            $opp=new Opportunity();
            $opp['accountPlan_id']=$accountPlan['id'];

            $parameters=[
                'type'=>$actionType,
                'target'=>$target,
                'status'=>'Recommended',
                'productId'=>$product['id'],
                'productName'=>$product['name'],
                'actingUserId'=>session('actingUserId'),
                'actingUserName'=>session('actingUserName'),
                'userId'=>Auth::id(),
                'usaerName'=>Auth::user()->name,
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
        $this->prepareCommonViews();
        return view('Accountplans.addActionsResult',compact('errors','success'));

    }

    public function addActions(Request $request){
        $this->prepareCommonViews();
        return view('Accountplans.addActions');
    }

    public function showActionFunnel(Request $request){

        $customer_id=$request->query('customerId',0);
        $numberOfRecommendedActions=0;

        if ($customer_id>0) {
            $accountPlanId=AccountPlan::where('customer_id','=',$customer_id)->where('active','=',1)->first()['id'];
            $numberOfRecommendedActions=Opportunity::where('accountPlan_id','=',$accountPlanId)->count();
        }
        else {
            $customerSelectQuery=session('customerSelectQuery');
            $numberOfActions=DB::select('
Select 
sum(case when Opp.parameters->\'$.status\'="Recommended" then 1 else 0 end) as numberOfRecommendedActions,
sum(case when Opp.parameters->\'$.status\'="Planned" then 1 else 0 end) as numberOfPlannedActions,
sum(case when Opp.parameters->\'$.status\'="TalkedToClient" then 1 else 0 end) as numberOfTalkedToClientActions,
sum(case when Opp.parameters->\'$.status\'="Realized" then 1 else 0 end) as numberOfRealizedActions,
sum(case when Opp.parameters->\'$.status\'="Dropped (After Recommended)" then 1 else 0 end) as numberOfDroppedActionsAfterRecommended,
sum(case when Opp.parameters->\'$.status\'="Dropped (After Planned)" then 1 else 0 end) as numberOfDroppedActionsAfterPlanned,
sum(case when Opp.parameters->\'$.status\'="Dropped (After TalkedToClient)" then 1 else 0 end) as numberOfDroppedActionsAfterTalkedToClient
from 
(select id from customers where '.$customerSelectQuery.') cust
inner join 
(select id, customer_id from account_Plans where active=1) AP on (Cust.id=AP.Customer_id)
inner join
(select id, parameters, accountPlan_id from opportunities) OPP on (OPP.accountPlan_id=AP.id)

            ')[0];
        }

        $this->prepareCommonViews();
        return view('Accountplans.actionFunnel', compact('numberOfActions'));
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
