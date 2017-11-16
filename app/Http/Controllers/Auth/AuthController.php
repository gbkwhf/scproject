<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
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
    	$mobile=trim($request->name);
    	$password=trim($request->password);
    	//验证经销商和供应商验证登录
    	if($request->role_type==1){//经销商
    		$had=\App\AgencyModel::where('mobile',$mobile)->where('password',md5($password))->first();
    		if($had){   
    			if($had->state<1){
    				return back()->withErrors([
    						$this->loginUsername() => '账号禁用',
    						]);
    			}    			 			
    			Auth::loginUsingId(2);
    			\Session::put('role_userid', $had->id);
    			\Session::put('role', 2);
    			return redirect('agencyadmin');
    		}else{
    			return back()->withErrors([
    					$this->loginUsername() => '账号或密码错误',
    					]);
    		}
    	}elseif($request->role_type==2){//供应商
    		$had=\App\SupplierModel::where('mobile',$mobile)->where('password',md5($password))->first();
    		if($had){
    			if($had->state<1){
    				return back()->withErrors([
    						$this->loginUsername() => '账号禁用',
    						]);    				
    			}
    			Auth::loginUsingId(3);
    			\Session::put('role_userid', $had->id);
    			\Session::put('role', 3);
    			return redirect('supplieradmin');
    		}else{
    			return back()->withErrors([
    					$this->loginUsername() => '账号或密码错误',
    					]);
    		}    		
    	}
    	    	
    	$this->validate($request, [
    			$this->loginUsername() => 'required', 'password' => 'required',
    			]);
    	\Session::put('role', 1);
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
    	$phrase = new PhraseBuilder;
        // 设置验证码位数
        $code = $phrase->build(4);
        // 生成验证码图片的Builder对象，配置相应属性
        $builder = new CaptchaBuilder($code, $phrase);
        
        
        $builder->setBackgroundColor(220, 210, 230);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(2);
        $builder->setMaxFrontLines(2);
        

        
        
    	//可以设置图片宽高及字体
    	$builder->build($width = 117, $height = 35, $font = null);
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
