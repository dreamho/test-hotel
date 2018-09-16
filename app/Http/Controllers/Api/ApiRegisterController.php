<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6.6.18.
 * Time: 12.16
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegister;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Role as RoleResource;
use App\Model\Role;
use App\Model\User;
use JWTFactory;
use JWTAuth;
use Validator;
use Response;

/**
 * Class ApiRegisterController
 * @package App\Http\Controllers\Api
 */
class ApiRegisterController extends Controller
{
    /**
     * User registration with validation and setting a token
     * @param UserRegister $request
     * @return UserResource
     */
    public function register(UserRegister $request)
    {
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);
        $user->roles()->attach(Role::where('id', $request->get('role'))->first());
        $token = JWTAuth::fromUser($user);
        return (new UserResource($user))->additional(['token' => $token]);
    }

    /**
     * Get all user roles
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getRoles()
    {
        $roles = Role::all();
        return RoleResource::collection($roles);
    }
}
