<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\APIResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
     /**
      * Handle the user registration process.
      *
      * @param \Illuminate\Http\Request $request The HTTP request instance containing user registration data.
      * 
      * @return \Illuminate\Http\JsonResponse The JSON response indicating the error or success.
      */
     public function UserRegister(Request $request)
     {
          // Validate the request data
          $validator = Validator::make($request->all(), [
               'name' => 'required|string|max:255',
               'email' => 'required|string|email|max:255|unique:users',
               'role' => 'required|string|in:user,seller',
               'password' => 'required|string|min:8|confirmed',
          ]);

          // If validation fails, return a detailed error response
          if ($validator->fails()) {
               return APIResponse::error('Validation error', 422, $validator->errors());
          }

          try {
               // Create a new user
               $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'role' => $request->role,
                    'password' => Hash::make($request->password),
               ]);
               return APIResponse::success($user, 'User registered successfully', 201);
          } catch (\Exception $e) {
               return APIResponse::error('User registration failed', 500);
          }
     }


     /**
      * Handle the user login process.
      *
      * This method authenticates a user and returns an API token if the credentials
      * are valid. If the user already has a valid token, it returns the existing token.
      * Otherwise, it creates a new token.
      *
      * @param \Illuminate\Http\Request $request The HTTP request instance containing user login data.
      * 
      * @return \Illuminate\Http\JsonResponse The JSON response containing the API token.
      */
     public function UserLogin(Request $request)
     {

          // Validate the login request data
          $request->validate([
               'email' => 'required|string|email',
               'password' => 'required|string',

          ]);

          // Attempt to authenticate the user
          $credentials = $request->only('email', 'password');
          if (!Auth::attempt($credentials)) {
               return APIResponse::error('Invalid credentials', 401);
          }

          $user = Auth::user();
          // Check if the user already has a valid token
          $existingToken = $request->bearerToken();
          if ($existingToken) {
               $personalAccessToken = PersonalAccessToken::findToken($existingToken);
               if (isset($personalAccessToken->tokenable->id) && $personalAccessToken->tokenable->id == $user->id) {
                    $user->token = $existingToken;

                    return APIResponse::success(['user' => $user], 'User logged in successfully');
               }
          }

          // Create a new token for the user
          $token = $user->createToken('auth_token')->plainTextToken;
          $user->token = $token;

          return APIResponse::success(['user' => $user], 'User logged in successfully');
     }


     /**
      * Handle the user logout process.
      *
      * This method revokes the current user's token, effectively logging them out.
      *
      * @param \Illuminate\Http\Request $request The HTTP request instance.
      * 
      * @return \Illuminate\Http\JsonResponse The JSON response confirming the logout.
      */
     public function Logout(Request $request)
     {
          $request->validate([
               'user_id' => 'required|integer|exists:users,id',
          ]);

          $user = User::find($request->user_id);
          if (!$user) {
               return APIResponse::error('Invalid user ID', 404);
          }
          $existingToken = $request->bearerToken();
          $personalAccessToken = PersonalAccessToken::findToken($existingToken);
          if (isset($personalAccessToken->tokenable->id) && $personalAccessToken->tokenable->id == $user->id) {
               $personalAccessToken->delete();
               return APIResponse::success([], 'User logged out successfully');
          } else {
               return APIResponse::error('Invalid User Token', 401);
          }
     }

     /**
      * Logout from all devices.
      *
      * This method revokes all access tokens for the authenticated user,
      * effectively logging them out from all devices.
      *
      * @param \Illuminate\Http\Request $request
      * @return \Illuminate\Http\JsonResponse
      */
     public function LogoutFromAllDevices(Request $request)
     {
          $request->validate([
               'user_id' => 'required|integer|exists:users,id',
          ]);
          $user = User::find($request->user_id);
          if (!$user) {
               return APIResponse::error('Invalid user ID', 404);
          }
          // Revoke all access tokens for the authenticated user
          $user->tokens()->delete();
          return APIResponse::success([], 'Logged out from all devices');
     }
}
