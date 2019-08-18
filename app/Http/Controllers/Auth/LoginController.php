<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Auth;
use App\Historico;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        if(Auth::user()->permission != "admin"){
            return ("logout");
        }
        // Guardando Histórico
        $ip = request()->ip();
        $log = Historico::create([
            'utilizador' => Auth::user()->id,
            'tipo' => "autenticacao",
            'acao' => "entrou",
            'descricao' => "O utilizador '".Auth::user()->name."' entrou no sistema",
            'date' => date('Y-m-d'),
            'ip' => $ip
        ]);
        // Guardando Histórico

        return ("calendario");
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function loginpage()
    {
        return view('auth.login');
    }
    public function registerpage()
    {
        return view('auth.register');
    }
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function logout(Request $request){

        // Guardando Histórico
        $ip = request()->ip();
        $log = Historico::create([
            'utilizador' => Auth::user()->id,
            'tipo' => "autenticacao",
            'acao' => "saiu",
            'descricao' => "O utilizador '".Auth::user()->name."' saiu do sistema",
            'date' => date('Y-m-d'),
            'ip' => $ip
        ]);
        // Guardando Histórico

        $this->guard()->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect('../../'); 
    }
}
