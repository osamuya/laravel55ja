<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

/* Mail authentication  */
use Illuminate\Http\Request;
/* Datetime package "Carbon" for laravel */
use Carbon\Carbon;
/* Mail */
use Mail;
use App\Mail\BaseMail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
	
	
    /**
     * Confirm page to create a new user
     *
     * @param  Request [ name, email, password ]
     * @return view("auth.register_confirm")
     */
	protected function registConfirm(Request $request) {
		
        /* Validation */
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:base_users',
            'password' => 'required|string|min:6|confirmed',
        ]);
		
        /* Request data */
        $requestData = array(
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        );
		
        /* Session flash keep */
        $request->session()->flash("name", $request->input('name'));
        $request->session()->flash("email", $request->input('email'));
        $request->session()->flash("password", $request->input('password'));
		
		/* Reload prevention request and session */
        $request->session()->flash('newRegistStoreFlag',true);
		
		return view("auth.register_confirm")->with($requestData);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
