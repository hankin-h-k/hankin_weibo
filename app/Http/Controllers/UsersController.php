<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Mail;
class UsersController extends Controller
{
    //注册页面
   	public function create()
   	{	
   		return view('users.create');
   	}

   	public function index(Request $request)
   	{

   	}

   	public function show(User $user)
   	{
         // $statuses = $user->statuses()->orderBy('created_at', 'desc')->paginate(10);
         return view('users.show');
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
   	public function sendEmailConfirmationTo($user)
   	{
   		$view = 'emails.confirm';
   		$data = compact($user);
   		$to = $user->email;
   		$subject = '感谢注册 WeiBo 应用！请确认你的邮箱';

   		Mail::send($view, $data, function($message) use($to, $subject) {
   			$message->to($to)->subject($subject);
   		});
   		return ;
   	}

   	public function edit(User $user)
   	{

   	}

   	public function update(User $user, Request $request)
   	{

   	}

    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }
}
