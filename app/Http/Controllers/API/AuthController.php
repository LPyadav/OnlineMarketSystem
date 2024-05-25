<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\APIResponse;
use App\Models\User;

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
          return APIResponse::error('User not found', 404);
     }
}
