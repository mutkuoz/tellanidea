<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Kpi;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Settings;
use App\User;

class KpiController extends BaseController
{
    public function index() {

        $this->prepareCommonViews();
        $kpis = Kpi::all();
        $kpiIds= $kpis->pluck('id')->toArray();

        return view ('Kpis.index', compact('kpis','kpiIds'));
    }

    public function create(Request $request)
    {
        $this->prepareCommonViews();
        return view ('Kpis.create');
    }

    public function store()
    {
        $rules = array(
            'name'       => 'required',
            'queryWord'      => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::to('kpis/create')
                ->withErrors($validator);
        } else {
            $kpi=new Kpi();
            $kpi['name']=Input::get('name');
            $kpi['queryWord']=Input::get('queryWord');
            $kpi['calculationQuery']=Input::get('calculationQuery');
            $kpi['calculationQueryForGroup']=Input::get('calculationQueryForGroup');
            $kpi['calculationParameters']=Input::get('calculationParameters');
            $kpi['color']=Input::get('color');
            $kpi['showOnMainScreen']=((Input::get('showOnMainScreen')==1) ? 1 : 0);
            $kpi['decimalPlaces']=Input::get('decimalPlaces');
            $kpi['viewMultiplier']=Input::get('viewMultiplier');
            $kpi['preSign']=Input::get('preSign');
            $kpi['postSign']=Input::get('postSign');
            $kpi['link']=Input::get('link');
            $kpi->save();
            Session::flash('message', 'Successfully created nerd!');
            return Redirect::to('kpis');
        }
    }

    public function show(Kpi $kpi)
    {

    }

    public function edit(Kpi $kpi)
    {
        $this->prepareCommonViews();
        return View ('Kpis.edit')->with('kpi',$kpi);
    }

    public function update(Request $request, $id)
    {
        $kpi = Kpi::findOrFail($id);
        $kpi['name']=$request['name'];
        $kpi['queryWord']=$request['queryWord'];
        $kpi['calculationQuery']=$request['calculationQuery'];
        $kpi['calculationQueryForGroup']=$request['calculationQueryForGroup'];
        $kpi['calculationParameters']=$request['calculationParameters'];
        $kpi['color']=$request['color'];
        $kpi['showOnMainScreen']=( ($request['showOnMainScreen']==1) ? 1 : 0);
        $kpi['decimalPlaces']=$request['decimalPlaces'];
        $kpi['viewMultiplier']=$request['viewMultiplier'];
        $kpi['preSign']=$request['preSign'];
        $kpi['postSign']=$request['postSign'];
        $kpi['link']=$request['link'];
        $kpi['toolTipTemplate']=$request['toolTipTemplate'];
        $kpi['toolTipNumbers']=$request['toolTipNumbers'];
        $kpi->save();
        //return view ('test', compact('request'));
        return redirect('/kpis')->with('success', 'Kpi Updated!!');
    }

    public function destroy(Kpi $kpi)
    {
        //
    }

    public function getSettingReplacements($baseText) {
        $settings=Settings::all();
        $settingReplacement=array();
        foreach ($settings as $setting) {
            $settingReplacement[$setting['settingName']]='('.$setting['settingValue'].')';
        }
        $kpis=Kpi::all();
        foreach ($kpis as $x) {
            $settingReplacement['['.$x['name'].']']='kpi_'.$x['id'];
        }
        return strtr($baseText,$settingReplacement);;
    }

    public function runCalculationQuery(Request $request,$id){
        $kpi = Kpi::findOrFail($id);

        $calculationQuery=$kpi['calculationQuery'];
        if (!empty($calculationQuery)) {
            $calculationQuery = str_replace("%COLUMN_NAME%", "kpi_" . $kpi['id'], $calculationQuery);
            $calculationQuery = $this->getSettingReplacements($calculationQuery);
            DB::update($calculationQuery);
        }

        $calculationQueryForGroup=$kpi['calculationQueryForGroup'];
        if (!empty($calculationQueryForGroup)){
            $calculationQueryForGroup=str_replace("%COLUMN_NAME%","kpi_".$kpi['id'],$calculationQueryForGroup);
            $calculationQueryForGroup = $this->getSettingReplacements($calculationQueryForGroup);
            $sqls=explode(';',$calculationQueryForGroup);
            try {
                foreach ($sqls as $sql)
                    DB::Raw($sql);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
            }
        }

        if ($request->isMethod('post')) {
            return response()->json([
                'result' => 'Success',
                'kpi' => $kpi['id']
            ]);
        }
        return Redirect::to('kpis');
    }

    public function compareKPIs()
    {
        $users = User::select('id','name','customerSelectQuery')
            ->where('parent_id',session('actingUserId'))
            ->orWhere('id',session('actingUserId'))
            ->get();

        $kpis=Kpi::select('id','name','queryWord','decimalPlaces','preSign','postSign')->get()->toArray();
        $kpiSelectQuery='';
        $kpiNames=[]; // this is an associative array like 'kpi_1'=>'Revenues_ytd'
        $kpiViewProperties=[];

        foreach ($kpis as $key=>$kpi) {
            $kpi['queryWord']=str_replace("%X%","kpi_".$kpi['id'],$kpi['queryWord']);
            $kpi['queryWord'].=(' as kpi_'.$kpi['id']);
            if ($kpiSelectQuery!='')
                $kpiSelectQuery.=',';
            $kpiSelectQuery=$kpiSelectQuery.$kpi['queryWord'];

            $kpiNames['kpi_'.$kpi['id']]=$kpi['name'];
            $kpiViewProperties['kpi_'.$kpi['id']]=array(
                'decimalPlaces'=>$kpi['decimalPlaces'],
                'preSign'=>$kpi['preSign'],
                'postSign'=>$kpi['postSign']
            );
        };
        $kpiSelectQuery=$this->getSettingReplacements($kpiSelectQuery);


        $results=[];
        foreach ($users as $user){
            $result=[];
            $result['name']=$user['name'];
            $queryResult=Customer::selectRaw($kpiSelectQuery)->whereRaw($user['customerSelectQuery'])->first()->toArray();
            $result=array_merge($result,$queryResult);
            $results[]=$result;
        }

        $this->prepareCommonViews();
        return view ('Kpis.myPeople', compact('results','kpiNames', 'kpiViewProperties'));
    }
}
