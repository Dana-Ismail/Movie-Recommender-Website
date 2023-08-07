<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->route('profile');
        } else {
            return redirect()->back()->withErrors(['error' => 'Invalid credentials']);
        }
    }

    public function showSignupForm()
    {
        return view('signup');
    }

    public function signup(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return redirect()->route('profile');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

   
   
}
