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
        /*入力データを返す*/
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'uniqueid' => $data['uniqueid'],
            'uniquehash' => $data['uniquehash'],
			#'remember_token' => "",
			#'description' => $data['description'],
            'role' => $data['role'],
            'status' => $data['status'],
            'delflag' => $data['delflag'],
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
	
	
    /**
     * Store page to new user
     *
     * @param  Request [ session ]
     * @return view("auth.register_stored")
     */
	protected function registStore(Request $request) {
		
		if ($request->session()->get('newRegistStoreFlag') === true) {
			/* Go! */
		} else {
			return redirect('/');
		}
		
		/* Password hash */
		$passwordHash = bcrypt($request->session()->get("password"));
		
		/* uniqeid */
		$uniqeid = $this->getUniqeid("hoge");
		
		/* Uniqehash */
		$uniquehash = substr(hash('sha512',$request->session()->get("password").env("APP_KEY").date("Ymdist")),0,60);

		/* Save regist data */
        $data = array(
            'name' => $request->session()->get("name"),
            'email' => $request->session()->get("email"),
            'password' => $passwordHash,
            'uniqueid' => $uniqeid,
            'uniquehash' => $uniquehash,
            'role' => 1,
            'status' => 1,
            'delflag' => 0,
        );
		$this->create($data);

		/* Preparation for mail authentication */
		$options = [
			'from' => 'from@example.com',
			'from_jp' => '【 '.env("APP_NAME").'より 会員仮登録のお知らせ 】',
			'to' => $request->session()->get("email"),
			'subject' => env("APP_NAME").'より 会員仮登録のお知らせ',
			'template' => 'mails.regist.temp_welcome',
			"bcc" => env("ADMIN_MAIL"),
		];
		
		/* Parameter for mail auth */
		/* Make access URL */
		$makeURL = env("APP_URL")."/".env("APP_NAME")."/mail_authenticate_user/".$uniquehash;
		/* Get global IP address */
        $userip = \Request::ip();
		/* Create a password of a bullet on a bullet */
		$boundLetteredPassword = $this->boundLettered($request->session()->get("password"),1);
		/* Date time */
        $dt = Carbon::now();
        $registedDate =  $dt->format('Y年m月d日 h:i:s');
		
		$emaildata = [
			"apprication_name" => env("APP_NAME"),
			"user_name" => $request->session()->get("name"),
			'user_email' => $request->session()->get("email"),
			'user_password' => $boundLetteredPassword,
			'uniquehash' => $uniquehash,
			'makeurl' => $makeURL,
			'registed_date' => $registedDate,
			'registed_term' => 24,
			'url' => env("APP_URL"),
		];

		Mail::to($request->session()->get("email"))->send(new BaseMail($options, $emaildata));
		
		/* No reloading */
		$request->session()->forget('newRegistStoreFlag');
		
		return view("auth.register_store");
	}
	
	protected function getUniqeid($salt) {

		$uniqid = implode("-", str_split(strtoupper(uniqid("00")), 3));
		$uniqid = "ID-".$uniqid;
		return $uniqid;
	}
	
	
	public function boundLettered($strings, $fchara=0) {

        $displayChara = substr($strings, 0,$fchara);

        $count = mb_strlen($strings);
        $count = $count - $fchara;
        $boundLettered = "";
        for($i=0; $i<$count; $i++) {
            $boundLettered .= "*";
        }
        if ($fchara > 0) {
            $returnBoundLettered = $displayChara.$boundLettered;
        } else if ($fchara == 0) {
            $returnBoundLettered = $boundLettered;
        }
        return $returnBoundLettered;
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
