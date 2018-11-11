<?php

namespace App\Http\Controllers;

use App\Opportunity;
use App\OpportunityHistory;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function show(Opportunity $opportunity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function edit(Opportunity $opportunity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Opportunity $opportunity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Opportunity $opportunity)
    {
        //
    }

    public function SaveOpportunities(Request $request){
        $a="";
        $b="";
        foreach ($request->input('opportunities') as $opportunity) {
            if ($opportunity['opportunityId']<0){
                $currOpp = new Opportunity;
                $currOpp['accountPlan_id']=1;
                $currOpp->save();
                $opportunity['opportunityId']=$currOpp['id'];
                $currOpp['parameters']=json_encode($opportunity);
                $currOpp->save();
                $oppHistory=new OpportunityHistory;
                $oppHistory['opportunity_id']=$currOpp['id'];
                $oppHistory['to']=$currOpp['parameters'];
                $oppHistory->save();
            }
            else {
                $currOpp=Opportunity::find($opportunity['opportunityId']);
                if ( array_key_exists('changed',$opportunity) && $opportunity['changed']) {
                    unset($opportunity['changed']);
                    $from = $currOpp['parameters'];
                    $currOpp['parameters'] = json_encode($opportunity);
                    $currOpp->save();
                    $oppHistory = new OpportunityHistory;
                    $oppHistory['opportunity_id'] = $currOpp['id'];
                    $oppHistory['from'] = $from;
                    $oppHistory['to'] = $currOpp['parameters'];
                    $oppHistory->save();
                }
            }
        }
        return json_encode($a).'|'.json_encode($b);
    }
}
