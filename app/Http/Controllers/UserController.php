<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Redirect;

class UserController extends Controller
{    
    public function index(){
        $users = User::all();
        $roles = Role::select('id','name')->get();
        return view('task',compact('users','roles'));
    }
}
