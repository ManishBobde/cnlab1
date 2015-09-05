<?php
/**
 * Created by PhpStorm.
 * User: Hash
 * Date: 13-08-2015
 * Time: 21:49
 */

namespace App\CN\Repositories;


use App\CN\CNRoles\RoleEnum;
use App\CN\Interfaces\CustomModel;
use Illuminate\Support\Facades\Input;
use App\CN\CNAccessTokens\AccessToken;
use App\Events\UserRegistered;
use App\Http\Requests\Request;
use CN\Users\UserInterface;
use App\CN\CNUsers\User;
use Illuminate\Http\Response;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Mockery\Exception;
use Tymon\JWTAuth\JWTAuth;
use Unirest;


abstract class BaseRepository {

    /**
     * Method for creating genric record for registration in DB
     * @param CustomModel $model
     * @return mixed
     */
    protected function createGenericRecord(CustomModel $model){

        if (Input::get('password') && Input::get('roleId') == RoleEnum::Principal) {

            $passwd = $this->generatePassword();

            $model->password = bcrypt($passwd);
        }

        $model->firstName = Input::get('firstName');

        $model->lastName = Input::get('lastName');

        $model->email = Input::get('email');

        $model->roleId = Input::get('roleId');

        $model->collegeId = Input::get('collegeId');

        $model->deptId = Input::get('deptId');

        $model->slug = $model->firstName."_".$model->lastName;

        $model->mobileNumber = Input::get('mobileNumber');

        $this->verifyMobileNumber(Input::get('mobileNumber'));

        if ( Input::hasFile('avatar')) {

            $file = Input::file('avatar');
            $name = time().'-'.$file->getClientOriginalName();
            $file = $file->move('uploads/', $name);
            $model->avatarUrl = $name;
            //dd( $model->avatarUrl);
        }

        /*$user->fill(Input::all());*/
        try {
            $model->save();

            event(new UserRegistered($model, $passwd));

        } catch (Exception $e) {

            return response()->json(['error' => 'User already exists.'], HttpResponse::HTTP_CONFLICT);

        }

        $token = $this->auth->fromUser($model);

        $idealExpirationTime = $this->auth->getPayload($token)->get('exp');

        return response()->json(array(['token' => $token, 'isActive' => '1'
            ,'idealTimeExpirationDuration'=>$idealExpirationTime]),200);


    }

    /**
     *generate six word random password
     */
    private function generatePassword($l = 6)
    {

        return substr(md5(uniqid(mt_rand(), true)), 0, $l);

    }

    private function verifyMobileNumber($mobileNo)
    {
        Unirest\Request::verifyPeer(false);
        $response = Unirest\Request::get("https://webaroo-mobile-verification.p.mashape.com/mobileVerification?phone=$mobileNo",
            array(
                "X-Mashape-Key" => "C87Hhc3Q8omshFfTyCiSb1F3yJmCp1Km8Gvjsn1pzv8E4jRL4b",
                "Accept" => "application/json"
            )
        );
        //dd($response);
       /* $res= null;
        foreach($res as $response){

            echo $res;
        }*/
    }



}