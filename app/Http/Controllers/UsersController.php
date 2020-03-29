<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
class UsersController extends Controller
{
    /**
     * 中间件
     */
    public function __construct()
    {
        $this->middleware('auth', [    
            //处指定的动作以外，所有其他动作都必须登录用户才能访问        
            'except' => ['login','reg']
        ]);
       // 只让未登录用户访问注册页面：
        $this->middleware('guest', [
            'only' => ['login']
        ]);
    }

    /**
     * 
     */
    public function index(){
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);

        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');

        return redirect()->route('users.show', [$user->id]);

    }

    public function edit(User $user)
    {
        //用户控制器中使用 authorize 方法来验证用户授权策略
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(User $user,Request $request){
        //用户控制器中使用 authorize 方法来验证用户授权策略
        $this->authorize('update', $user);

        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $user->update([
            'name' => $request->name,
            'password' => bcrypt($request->password),
        ]);


        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', $user->id);

    }

}
