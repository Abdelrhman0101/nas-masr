<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //

    public function getUserProfile()
    {

        $user = Auth()->user();
        $getProfile = User::where('phone', $user->phone)->first();
        return response([
            'Message'=>'Profile Fetched successfully',
            'data'=>$getProfile
        ], 200);

    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $user->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Successfully logged out from API'
        ], 200);
    }

}
