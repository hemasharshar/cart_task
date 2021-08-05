<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\LoginUserAPIRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Auth;
use JWTAuth;

class AuthAPIController extends AppBaseController
{

    /**
     * @var userService
     */
    protected $userService;

    /**
     * authController Constructor
     *
     * @param UserService $userService
     *
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(LoginUserAPIRequest $request)
    {
        try {

            $credentials = $request->only('email', 'password');
            try {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return $this->sendApiError('Invalid email or password.', 500);
                }
            } catch (JWTException $e) {
                return $this->sendApiError($e->getMessage(), 500);
            }

            $user = $this->userService->getById((Auth::user()->id));


            $response = array(
                'user' => $user,
                'token' => JWTAuth::fromUser($user)
            );
            return $this->sendApiResponse($response, 'User authenticated successfully.');
        } catch (\Exception $e) {
            return $this->sendApiError('Something went wrong!', 500);
        }
    }
}
