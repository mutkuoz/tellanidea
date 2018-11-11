<?php

namespace App\Http\Controllers;

use App\User;
use App\Lob;
use Illuminate\Http\Request;

class UserController extends BaseController
{

    public function index()
    {
        $this->prepareCommonViews();
        $users=User::paginate(20);
        return view ('Users.index', compact('users'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->prepareCommonViews();
        $lobs=Lob::all();
        return View ('Users.edit',compact('user','lobs'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user['extraProperties']=json_encode(array('visibleLobs'=>$request['visibleLobs']));
        $user['password']= bcrypt($request['password']);
        $user->save();
        return redirect('/users')->with('success', 'User Updated!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
