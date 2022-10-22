<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required|min:6'
            ]);
            if ($validate->fails()) {
                // return response()->json($validate->errors()->all());
                $notify[] = ['error', $validate->errors()->all()];
                return redirect()->back()
                    ->withNotify($notify)
                    ->withInput();
            }

            $email = $request->email;
            $password = $request->password;

            $credential = [
                'email' => $email,
                'password' => $password
            ];

            if (Auth::attempt($credential)) {
                $user = auth()->user();
                setUserMenu($user);
                return self::redirectUser($user);
            }

            $notify[] = ['error', "Email or Password doesn't match"];
            return redirect()
                ->back()
                ->withNotify($notify)
                ->withInput();
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    /**
     * Function to create redirection after login based on role
     * @param object user
     * @return render
     */
    private function redirectUser($user)
    {
        $user_role = getUserRole($user);
        $url = getUrlRedirect($user_role);

        return redirect()->route($url);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
