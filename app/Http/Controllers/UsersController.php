<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    /**
     * 注册
     */
    public function reg(){

        return view('users.reg');

    }

    public function show(User $user)
    {
        //将用户对象 $user 通过 compact 方法转化为一个关联数组
        return view('users.show', compact('user'));
    }


    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);
        return;
    }
}
