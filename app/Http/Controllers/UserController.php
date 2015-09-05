<?php namespace App\Http\Controllers;

use App\CN\CNUsers\UsersRepository;
use App\Events\ForgotPassword;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use App\CN\CNUsers\User;
use CN\Users\UserInterface ;
use App\Http\Controllers\ApiController;
use Tymon\JWTAuth\JWTAuth;

class UserController extends ApiController {



	protected $cnuser,$request;

	//, $auth ,$request,$middleware;
	/**
	 * @var Validator
	 */
	//private $validator;

	/**
	 * @param Guard $auth
	 * @param UsersRepository|CNUserInterface|CNUsersRepository $cnuser
	 */
	public function __construct(UsersRepository $cnuser,Request $request){

		$this->request=$request;
		//$this->auth = $auth;
		$this->cnuser = $cnuser ;
		//$this->middleware('jwt.auth', ['except' => ['loginUser','registerUser']]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	/*public function registerUser()
	{
        $password =Hash::make(Input::get('password'));
        $firstName =Input::get('firstname');
        $lastName = Input::get('lastname');
        $email =Input::get('email');
        $gender =Input::get('gender');


        User::create(
			array("first_name"=>$firstName,"last_name"=>$lastName,"email"=>$email,"password"=>$password)
		);

		return "done";//$this->setStatusCode(Response::HTTP_OK)->respond('User Created!');
	}*/

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	/*public function show($id)
	{
		$user = $this->cnuser->getUser($id);

		return $this->setStatusCode(200)->respond($user);

	}*/

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	/*public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	/*public function destroy($id)
	{
		//
	}*/



	/**
	 * Show the application registration form.
	 * for web app
	 * @return Response
	 */
	public function getRegister()
	{
		return view('auth.register');
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param RegisterRequest $registerRequest
	 * @return Response
	 * @internal param RegisterRequest $request
	 */
	public function registerUser(RegisterRequest $registerRequest)
	{
		/*$validator = $this->validator($request->all());

		if ($validator->fails()) {
			$this->throwValidationException(
				$request, $validator
			);
		}*/

		return $this->cnuser->createUser();

		///return "User has been successfully created";
	}

	public function verifyOtp(Request $request)
	{
		/*$validator = $this->validator($request->all());

		if ($validator->fails()) {
			$this->throwValidationException(
				$request, $validator
			);
		}*/

		return $this->cnuser->verifyOtp($request->get('code'),$request->get('mobileNumber'));

		///return "User has been successfully created";
	}

	/**
	 * Show the application login form.
	 * for Web App
	 * @return Response
	 */
	public function getLogin()
	{
		return view('auth.login');
	}

	/**
	 * Handle a login request to the application.
	 *
	 * @param LoginRequest $loginRequest
	 * @return Response
	 * @internal param LoginRequest $request
	 */
	public function loginUser(LoginRequest $loginRequest)
	{
		//dd($this->request->only('email','password'));
		$credentials = $this->getCredentials($loginRequest);
		//dd($credentials);
		//$this->auth,Input::get('email'), Input::get('password')
		return $this->cnuser->authenticate($credentials);
		/*return redirect('/login')->withErrors([
			'email' => 'The credentials you entered did not match our records. Try again?',
		]);*/
	}

	/**
	 * Get the needed authorization credentials from the request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	protected function getCredentials(Request $request)
	{
		return $request->all();
	}

	/**
	 * Log the user out of the application.
	 *
	 * @return Response
	 */
	public function logoutUser()
	{
		return $this->cnuser->logout($this->request->header('Authorization'));

		//return "logged Out!";
	}

	public function getOtherUserDetails($slug){

		return $this->cnuser->getOtherUserDetails($this->request->header('Authorization'),$slug);
	}

	/**
	 *
	 */
	public function getUserSpecificFeatures(){

		return $this->cnuser->getUserFeatures($this->request->header('Authorization'));
	}

	public function getUserProfile(){

		return $this->cnuser->getUserProfile($this->request->header('Authorization'));
	}

	public function changePassword(Request $request){

		$ttoken = $this->retrieveTokenFromHeader($this->request->header('Authorization'));

		$id = $this->getUserIdFromToken($ttoken);

		$oldpassword = $request->get('oldPasswd');

		$user = User::find($id);

		if($user->get('password')==$oldpassword) {

			$newpassword = $request->get('newPasswd');

			$user->password = $newpassword;

			$user->save();
		}
		/*PasswordRequest {
			old_password (string, optional): Old password to validate change during a session.,
new_password (string, optional): New password to be set.,
email (string, optional): User's email to be used with code to validate email confirmation.,
code (string, optional): Code required with new_password when using email confirmation.*/
	}

	public function getAllUsersWithinTenant($collegeId){

		return $this->cnuser->getAllUsersWithinTenant($collegeId,$this->request->header('Authorization'));

	}

	public function forgotPassword(){

		event(new ForgotPassword());

		return "Mail was sent successfully";

	}


}
