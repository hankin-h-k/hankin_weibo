<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Mail;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
        ]);
    }
    //注册页面
   	public function create()
   	{	
   		return view('users.create');
   	}

   	public function index(Request $request)
   	{
      $users = User::all();
      return view('users.index', compact('users'));
   	}

   	public function show(User $user)
   	{
         $statuses = $user->statuses()->orderBy('created_at', 'desc')->paginate(10);
         return view('users.show', compact('user', 'statuses'));

   	}

   	public function store(Request $request)
   	{
   		//检测输入的数据
   		$this->validate($request, [
   			'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
   		]);
   		// dd($request->all());
   		//创建user
   		$user = User::create([
   			'name'=>$request->name,
   			'email'=>$request->email,
   			'password'=>bcrypt($request->password),
   		]);
   		//邮件通知
   		$this->sendEmailConfirmationTo($user);
   		//结果
   		session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
   		//返回
   		return redirect('/');
   	}
   	
   	//确认邮件通知
    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $to = $user->email;
        $subject = "感谢注册 Weibo 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

   	public function edit(User $user)
   	{
      $current_user = auth()->user();
      $reuslt = $current_user->can('update', $user);
      if (!$reuslt) {
        session()->flash("danger", '账号没有权限');
      }
      return view('users.edit', compact('user'));
   	}

   	public function update(User $user, Request $request)
   	{
      //验证账号是否一致
      $current_user = auth()->user();
      $reuslt = $current_user->can('update', $user);
      if (!$reuslt) {
        session()->flash("danger", '账号没有权限');
        return view('users.edit', compact('user'));
      }
      //验证参数
      $this->validate($request, [
        'name' => 'required|max:50',
        'password' => 'nullable|confirm|min:6',
      ]);
      //修改参数
      $data = [];
      $data['name'] = $request->name;
      if ($request->password) {
        $data['password'] = bcrypt($request->password);
      }
      $user->update($data);
      //提示
      session()->flash('success', '個人資料修改成功');
      //返回
      return redirect()->route('users.show', $user);
   	}

    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }

    public function confirmEmail($token)
    {
      $user = User::where('activation_token', $token)->first();
      $user->activated = 1;
      $user->email_verified_at = date('Y-m-d H:i:s');
      $user->save();
      Auth::login($user);
      session()->flash('success', '欢迎回来！');
      return redirect()->route('users.show', $user);
    }
}
