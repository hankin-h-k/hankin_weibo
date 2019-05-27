<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
class StatusesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
    public function store(Request $request)
    {
    	$this->validate($request, [
    		'content' => 'required|max:140'
    	]);
    	\Auth::user()->statuses()->create([
    		'content'=>$request->content
    	]);
    	session()->flash('success', '发布成功！');
    	return redirect()->back();
    }

    public function destroy(Status $status)
    {
        if(auth()->user()->can("destroy", $status))
        {
            $status->delete();
            session()->flash('success', '成功删除微博！');
        }
        return redirect()->back();
    }
}