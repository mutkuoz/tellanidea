<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use View;

class BaseController extends Controller
{
    public function prepareCommonViews($params=null){
        //view::share('possibleActingUsers', User::descendantsAndSelf(Auth::id(), ['id','name']));

        view::share('possibleActingUsers', User::select('id','name')
            ->where('parent_id',Auth::id())
            ->orWhere('id',Auth::id())
            ->orWhere('id',session('actingUserId'))
            ->orWhere('parent_id',session('actingUserId'))
            ->get()
        );


        view::share('actingUserId',session('actingUserId'));
        view::share('actingUserName',session('actingUserName'));

        $showChangeUserButton=false;
        if(isset($params['showChangeUserButton']))
            $showChangeUserButton=$params['showChangeUserButton'];
        view::share('showChangeUserButton',$showChangeUserButton);

        $showHomeButton=false;
        if(isset($params['showHomeButton']))
            $showHomeButton=$params['showHomeButton'];
        view::share('showHomeButton',$showHomeButton);

        $showSettingsButton=false;
        if(isset($params['showSettingsButton']))
            $showSettingsButton=$params['showSettingsButton'];
        view::share('showSettingsButton',$showSettingsButton);

        $showLogoutButton=false;
        if(isset($params['showLogoutButton']))
            $showLogoutButton=$params['showLogoutButton'];
        view::share('showLogoutButton',$showLogoutButton);

        $title='';
        if(isset($params['title']))
            $title=$params['title'];
        view::share('title',$title);

        $showBackButton=false;
        if(isset($params['showBackButton']))
            $showBackButton=$params['showBackButton'];
        view::share('showBackButton',$showBackButton);


    }

}