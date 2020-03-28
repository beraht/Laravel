<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{

    //Auth 中间件提供的 guest 选项，用于指定一些只允许未登录用户访问的动作
    public function __construct()
    {
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }


    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request){

        $credentials = $this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials,$request->has('remember'))) {
            // 该用户存在于数据库，且邮箱和密码相符合
            session()->flash('success', '欢迎回来！');
          //  return redirect()->route('users.show', [Auth::user()]);
          //该方法可将页面重定向到上一次请求尝试访问的页面上，并接收一个默认跳转地址参数，当上一次请求记录为空时，跳转到默认地址上。
          $fallback = route('users.show', Auth::user());
          return redirect()->intended($fallback);
        }else{
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }

        return;

    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }
}
