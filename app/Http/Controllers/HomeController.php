<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\User;
use DB;
use App\Kpi;
use Auth;
use App\Settings;
use Illuminate\Support\Facades\Session;

class HomeController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $selectedUsers=User::descendantsAndSelf( session('actingUserId'),['id']);

        $selectedUsers->transform(function ($item, $key) {
            return $item['id'];
        });

        $settings=Settings::all();
        $settingReplacement=array();
        foreach ($settings as $setting) {
            $settingReplacement[$setting['settingName']]=$setting['settingValue'];
        }

        $kpis=Kpi::where('showOnMainScreen',1)->get();

        if (count($kpis)>0) {
            $queryWord = array();
            foreach ($kpis as $kpi) {
                $queryWord[] = str_replace('%X%', 'kpi_' . $kpi['id'], $kpi['queryWord']) . ' as kpi_' . $kpi['id'];
                if (!empty($kpi['toolTipNumbers'])) {
                    $ttParams= explode(',',$kpi['toolTipNumbers']);
                    $i=1;
                    foreach ($ttParams as $ttParam) {
                        $queryWord[] = $ttParam. ' as kpi_' . $kpi['id'].'_param'.$i;
                        $i++;
                    }
                }
            }
            $electPhrase = implode(',', $queryWord);
            $electPhrase = strtr($electPhrase,$settingReplacement);

            $query = Customer::select(DB::raw($electPhrase));

            $query =$query->whereRaw(Session('customerSelectQuery'));

            $result = $query->first();
        }

        $this->prepareCommonViews();
        return view('home', compact ('result','kpis'));
    }

    public function logout()
    {
        Auth::logout();
    }

    public function createNewUser()
    {
        return view('createNewUser');
    }

    public function getCustomers()
    {
        $customerList=customer::all();
        return view ('home.customers', compact('customerList'));
    }

    public function changeActingUser(Request $request)
    {
        $actingUserId=$request->input('actingUserId');

        session(['actingUserId'=>$actingUserId]);
        $user=User::find($actingUserId);

        $actingUserName=$user['name'];
        session(['actingUserName' => $actingUserName]);

        session(['customerSelectQuery'=>$user['customerSelectQuery']]);

        $visibleLobs=(json_decode($user->extraProperties,true))['visibleLobs'];
        if (!isset($visibleLobs))
            $visibleLobs=array();
        session(['visibleLobs'=> $visibleLobs]);
        return redirect()->back();
    }
}
