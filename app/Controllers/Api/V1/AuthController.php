<?php

namespace App\Controllers\Api\V1;

use App\Auth\DoctrineUserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Shared\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Get the DoctrineUserProvider
        $provider = Auth::getProvider();

        // Retrieve the user by credentials
        $user = $provider->retrieveByCredentials($credentials);

        if ($user && $provider->validateCredentials($user, $credentials)) {
            // Rehash the password if needed
            $provider->rehashPasswordIfRequired($user, $credentials);

            // Generate JWT token
            $token = JWTAuth::fromUser($user);
            
            return $this->respondWithToken($token);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function me()
    {
    	$user = auth()->user();

	    return response()->json([
	        'id' => $user->getId(),
	        'firstName' => $user->getFirstName(),
	        'lastName' => $user->getLastName(),
	        'email' => $user->getEmail(),
	        'role' => $user->getRole()?->getName(),
	        'phone' => $user->getPhone(),
	    ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}