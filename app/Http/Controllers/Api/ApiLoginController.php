<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6.6.18.
 * Time: 12.17
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLogin;
use App\Http\Resources\User as UserResource;
use Validator;
use JWTFactory;
use JWTAuth;

/**
 * Class ApiLoginController
 * @package App\Http\Controllers\Api
 */
class ApiLoginController extends Controller
{
    /**
     * User authentication with validation and setting a token
     * @param UserLogin $request
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function login(UserLogin $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        JWTAuth::setToken($token);
        $user = JWTAuth::authenticate();
        $parties = [];
        if ($user->parties != null) {
            foreach ($user->parties as $party) {
                $parties[] = $party->id;
            }
        }
        return (new UserResource($user))->additional(['token' => $token, 'parties' => $parties]);
    }

    /**
     * User logout and invalidating token
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['success' => 'Logged out successfully'], 200);
    }
}
