<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\User;

class LoginController extends Controller
{
    public function loginUser(Request $req)
    {
        Log::debug('ejemplo!!!');
        $test = array('test'=> $req->input('username'));
        return $test;
    }
}
