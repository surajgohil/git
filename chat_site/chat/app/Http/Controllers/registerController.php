<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Userdata;
use Illuminate\Support\Facades\Session;

class registerController extends Controller
{
    public function login_view()
    {
        return view('register_view');
    }
    public function register_view()
    {
        return view('login_view');
    }
    public function register_user(Request $request)
    {
        $validatedData = $request->validate([
            'uname' => 'required',
            'email' => 'required',
            'pass'  => 'required|confirmed',
            'cpass' => 'required',
        ],
        [
            'uname.required' => 'The user name is required.',
            'pass.required'  => 'The password is required.',
            'pass.confirmed' => 'The confirm password not match with password.',
            'cpass.required' => 'The confirm password is required.',
        ]);
        
        $userData = new Userdata();
        $userData->user_name = $request->uname;
        $userData->email     = $request->email;
        $userData->password  = $password = Hash::make($request->pass);

        if($userData->save()){
            return view('login_view');
        }else{
            return redirect()->back();
        }
    }
    public function login_user(Request $request)
    {
        $validatedData = $request->validate([
            'uname' => 'required',
            'pass'  => 'required',
        ],
        [
            'uname.required' => 'The user name field is required.',
            'pass.required'  => 'The password field is required.',
        ]);

        // $userData = Userdata::where('user_name',$request->uname)->where('password',$request->pass)->first();

        $userData   = '';
        $user_list  = array();
        $user_list1 = array();
        $user_list1 = Userdata::get();
        $user_list  = $user_list1->toArray();

        if(!empty($user_list))
        {
            foreach($user_list as $udata)
            {
                if($udata['user_name'] == $request->uname && $udata['password'] == $request->pass)
                {
                    $userData = 'login';
                    session()->put('name', $udata['user_name']);
                    session()->put('id', $udata['id']);
                }
            }
        }

        if($userData == 'login'){
            $data['user_list'] = $user_list;
            return view('dashboard',$data);
        }else{
            return view('login_view');
        }
    }
}


