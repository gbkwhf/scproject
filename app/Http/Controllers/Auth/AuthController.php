<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Gregwar\Captcha\CaptchaBuilder;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class AuthController extends Controller
{
	protected $username = 'name';
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
    
    
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function postLogin(Request $request)
    {
    
    	$userInput = $request->get('captcha');
    
    	if (Session::get('milkcaptcha') != $userInput) {
    		return back()->withErrors([
    				$this->loginUsername() => '验证码错误',
    				]);
    
    	}
    	$this->validate($request, [
    			$this->loginUsername() => 'required', 'password' => 'required',
    			]);
    
    	// If the class is using the ThrottlesLogins trait, we can automatically throttle
    	// the login attempts for this application. We'll key this by the username and
    	// the IP address of the client making these requests into this application.
    	$throttles = $this->isUsingThrottlesLoginsTrait();
    
    	if ($throttles && $this->hasTooManyLoginAttempts($request)) {
    		return $this->sendLockoutResponse($request);
    	}
    
    	$credentials = $this->getCredentials($request);
    
    	if (Auth::attempt($credentials, $request->has('remember'))) {
    		return $this->handleUserWasAuthenticated($request, $throttles);
    	}
    
    	// If the login attempt was unsuccessful we will increment the number of attempts
    	// to login and redirect the user back to the login form. Of course, when this
    	// user surpasses their maximum number of attempts they will get locked out.
    	if ($throttles) {
    		$this->incrementLoginAttempts($request);
    	}
    
    	return redirect($this->loginPath())
    	->withInput($request->only($this->loginUsername(), 'remember'))
    	->withErrors([
    			$this->loginUsername() => $this->getFailedLoginMessage(),
    			]);
    }
    public function captcha($tmp)
    {
    	//生成验证码图片的Builder对象，配置相应属性
    	$builder = new CaptchaBuilder;
    	//可以设置图片宽高及字体
    	$builder->build($width = 100, $height = 40, $font = null);
    	//获取验证码的内容
    	$phrase = $builder->getPhrase();
    
    	//把内容存入session
    	Session::flash('milkcaptcha', $phrase);
    	//生成图片
    	header("Cache-Control: no-cache, must-revalidate");
    	header('Content-Type: image/jpeg');
    	$builder->output();
    }   
    
}
