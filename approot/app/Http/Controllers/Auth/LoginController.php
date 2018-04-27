<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }
	
    /*
    |--------------------------------------------------------------------------
    | Changing login conditions
    |--------------------------------------------------------------------------
    |
    | We judge by adding conditions of role and status in addition
	| to normal e-mail and password login conditions
    | + role = (int)1
	| + status = (int)1
    */
    public function credentials(Request $request)
    {
        /**
         * 通常のメンバーログインはrole + statusを条件に加えて判定
         */
        $authConditionsOrigin = $request->only($this->username(), 'password');

		/**
		 * Super user
		 * Superuser (root privilege) can log in to the account of
		 * the input user's email address in the case of a request from
		 * the specified designated password and the specified IP address.
		 */
//		$super_admin_ips = $this->superUserAllowIPToArray();
//		$request_ip = \Request::ip();
//		$key = array_search($request_ip, $super_admin_ips);
//
//		/* Processing in case of Superuser */
//		if (
//			$authConditionsOrigin["password"] == env("SUPER_ADMIN_PASSWORD") &&
//			(!empty($key) || $key === 0)
//		) {
//			$authConditionsCustom = array_merge(
//				$authConditionsOrigin,
//				['role'=> 1],
//				['status'=> 1]
//			);
//			return $authConditionsCustom;
//		}

		
        $authConditionsCustom = array_merge(
            $authConditionsOrigin,
			['role'=> 1],
            ['status'=> 1]
        );
        
        return $authConditionsCustom;
    }
	
	public function superUserAllowIPToArray() {
		$super_admin_ips = env("SUPER_ADMIN_IPS");
		$ip_list = explode(",", $super_admin_ips);
		$ip_list_tmp = [];
		foreach($ip_list as $ip) {
			array_push($ip_list_tmp, trim($ip));
		}
		
		return $ip_list_tmp;
	}
	
	
	
}













































