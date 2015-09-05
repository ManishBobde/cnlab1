<?php
/**
 *
 * User: MVB
 * Date: 25-06-2015
 * Time: 16:12
 */

namespace App\CN\CNUsers;


use App\CN\CNAccessTokens\AccessToken;
use App\CN\CNColleges\College;
use App\CN\CNDepartments\Department;
use App\CN\Repositories\BaseRepository;
use App\CN\Repositories\CustomModel;
use App\CN\Transformers\UserTransformer;
use App\Events\UserRegistered;
use App\Exceptions\ErrorCodes;
use App\Http\Requests\Request;
use CN\Users\UserInterface;
use App\CN\CNUsers\User;
use Illuminate\Http\Response;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Mockery\Exception;
use Tymon\JWTAuth\JWTAuth;
use Unirest;

class UsersRepository extends BaseRepository implements UserInterface
{
    /**
     *Method to retrieve cn user
     * @param JWTAuth $auth
     * @internal param $id
     */
    protected $auth, $mailer, $userTransformer, $codes;//,$request;

    //protected $salt = "c2150$#@Mani";

    /**
     * @param JWTAuth $auth
     * @param UserTransformer $userTransformer
     * @param ErrorCodes $codes
     */
    public function __construct(JWTAuth $auth, UserTransformer $userTransformer, ErrorCodes $codes)
    {

        $this->auth = $auth;
        $this->userTransformer = $userTransformer;
        $this->codes = $codes;
        // $this->request=$request;
    }


    public function getUserDetails($id)
    {

        return User::findorFail($id);
    }


    public function getOtherUserDetails($token, $slug)
    {
        $ttoken = $this->retrieveTokenFromHeader($token);

        $id = $this->getUserIdFromToken($ttoken);

        $users = DB::table('users')
            ->join('departments', 'users.deptId', '=', 'departments.deptId')
            ->join('roles', 'users.roleId', '=', 'roles.roleId')
            ->select('users.userId','users.firstName','users.lastName','users.email','users.avatarUrl','users.dob','departments.deptYear', 'departments.deptName','roles.roleType')
            ->where('users.slug',$slug)
            ->get();
        //$userDetailsRes = $this->userTransformer->transformCollections($user,$dept);

        return response()->json($users, 200);

        if (!$user) {
            return $this->codes->respondNotFound("Not Found", "Title");
        }

        Album::where('artist', '=', 'Matt Nathanson')
            ->update(array('artist' => 'Dayle Rees'));
    }


    /**
     * Create User
     */
    public function createUser()
    {
        return parent::createGenericRecord(new User());
    }

    private function verifyOtpFromMobile($code, $mobileNo)
    {

        $response = Unirest\Request::get("https://webaroo-mobile-verification.p.mashape.com/mobileVerification?code=$code&phone=$mobileNo",
            array(
                "X-Mashape-Key" => "C87Hhc3Q8omshFfTyCiSb1F3yJmCp1Km8Gvjsn1pzv8E4jRL4b",
                "Accept" => "application/json"
            )
        );

    }

    public function getUserFeatures($token)
    {



    }

    /**
     * @param $credentials
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param $auth
     * @internal param $request
     */
    public function  authenticate(array $credentials)
    {

        try {

            if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']
                , 'isActive' => '1'])
            ) {

                $onlyCredentials = array('email' => $credentials['email'], 'password' => $credentials['password']);
                //dd($onlyCred);

                // verify the credentials and create a token for the user
                if (!$token = $this->auth->attempt($onlyCredentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 401);

                }
                // dd($this->auth->getPayload(true));

                $idealExpirationTime = $this->auth->getPayload($token)->get('exp');

                $accessToken = AccessToken::firstOrCreate(['accessToken' => $token, 'deviceType' => $credentials['Device'],
                    'mediaType' => $credentials['MediaType'], 'osName' => $credentials['OS'], 'userId' => "1"
                    , 'idealTimeExpirationDuration' => $idealExpirationTime]);
                return response()->json(array(['token' => $token, 'isActive' => '1', 'idealTimeExpirationDuration' => $idealExpirationTime]));
            } else {

                return response()->json(array(['message' => "User does not exist"]));

            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }


    }

    public function getAll()
    {
        return User::all();
    }

    /*public function findByUserNameOrCreate($userData)
    {
        if ($fromFB) {
            $users = $userData->user;
            return CNUsersModel::firstOrCreate([
                'first_name' => $users['first_name'],
                'last_name' => $users['last_name'],
                'email' => $users['email']
            ]);
        } else {

            $users = $userData->name;
            $emailarr =$userData->emails;
            $email =$emailarr->value;
            return SalonUsersModel::firstOrCreate([
                'first_name' => $users['familyName'],
                'last_name' => $users['givenName'],
                'email' => $email
            ]);
        }
    }*/

    /**
     *Generate access token
     * @return
     */
//    public function generateAccessToken(){
//
//        return bcrypt(str_random(60));
//
//    }

    /**
     * @return bool
     */
    public function logout($token)
    {

        // dd(Request::header());
        $ttoken = retrieveTokenFromHeader($token);

        $id = $this->getUserIdFromToken($ttoken);

        $this->auth->invalidate($ttoken);

        $accessToken = AccessToken::where('userId', $id)->first();

        $accessToken->delete();

        return response()->json([
            'resultCode' => "",
            'resultTitle' => "",
            'resultMessage' => ""

        ], 200);
    }

    public function retrieveTokenFromHeader($token)
    {

        $index = 1;

        $ttoken = explode(" ", $token);

        return $ttoken[$index];

    }

    public function getUserIdFromToken($ttoken)
    {

        $id = $this->auth->getPayload($ttoken)->get('sub');

        return $id;

    }

    public function getAllUsersWithinTenant($collegeId,$token)
    {
        $ttoken = $this->retrieveTokenFromHeader($token);

        $id = $this->getUserIdFromToken($ttoken);

        $id = $this->getUserIdFromToken($ttoken);

        $college = College::findorFail($collegeId);

        $users = $college->users()->get(array('users.firstName','users.lastName','users.userId','users.roleId','users.avatarUrl'));

        return response()->json(array(
            'totalItems'=>$users->count(),
            'items' => $users,
        ),
            200
        );

    }


}

