<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::paginate());
    }

    public function store()
    {
        request()->validate(['name' => 'required|string', 'email' => 'required|email', 'password' => 'required', 'profile' => 'required|exists:profile,name']);
        $user = User::create(request()->all());
    }

    public function update(User $user)
    {

    }
}
