<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PDF;
use Auth;
use App\Historico;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;


class UserExport implements FromCollection
{
    public function collection()
    {
        return User::all();
    }
}

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
           $user = User::where('name', 'LIKE', "%$keyword%")
                ->orWhere('email', 'LIKE', "%$keyword%")
                ->orWhere('password', 'LIKE', "%$keyword%")
                ->orWhere('remember_token', 'LIKE', "%$keyword%")
                ->orWhere('email_verified_at', 'LIKE', "%$keyword%")
                ->orWhere('type', 'LIKE', "%$keyword%")
                ->orWhere('contact', 'LIKE', "%$keyword%")
                ->get();
        } else {
            $user = User::whereNull('permission')->get();
        }

        return view('user.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $requestData = $request->all();

        
            $add = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'contact' => $request['contact'],
                'type' => $request['type'],
                'password' => $request['password']
            ]);
      
        
        

        // Guardando Histórico
        $ip = request()->ip();
        $log = Historico::create([
            'utilizador' => Auth::user()->id,
            'tipo' => "user",
            'acao' => "criou",
            'descricao' => "O utilizador '".Auth::user()->name."' criou o(a) user  nº '".$add->id."'.",
            'date' => date('Y-m-d'),
            'ip' => $ip
        ]);
        // Guardando Histórico

        return redirect('user')->with('flash_message', 'User added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $id = $_GET['id'];
        $user = User::where('id',$id)->first();

        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        
        $requestData = $request->all();
        
        $user = User::findOrFail($id);
        
        $newpassword = $request->newpassword;
        if(!empty($newpassword)){
            $user = User::where('id',$id)->first();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->contact = $request->contact;
            $user->password = Hash::make($request->newpassword);
            $user->type = $request->type;
            $user->save();
        }else{
            $user = User::where('id',$id)->first();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->contact = $request->contact;
            $user->type = $request->type;
            $user->save();
        }

        // Guardando Histórico
        $ip = request()->ip();
        $log = Historico::create([
            'utilizador' => Auth::user()->id,
            'acao' => "alterou",
            'tipo' => "user",
            'descricao' => "O utilizador '".Auth::user()->name."' alterou o(a) user  nº '".$id."'.",
            'date' => date('Y-m-d'),
            'ip' => $ip
        ]);
        // Guardando Histórico

        return redirect('user')->with('flash_message', 'User updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        User::destroy($id);

        // Guardando Histórico
        $ip = request()->ip();
        $log = Historico::create([
            'utilizador' => Auth::user()->id,
            'tipo' => "user",
            'acao' => "eliminou",
            'descricao' => "O utilizador '".Auth::user()->name."' eliminou o(a) user  nº '".$id."'.",
            'date' => date('Y-m-d'),
            'ip' => $ip
        ]);
        // Guardando Histórico

        return redirect('user')->with('flash_message', 'User deleted!');
    }
    public function report(Request $request)
    {    
        @$dati = $request->datai;
        @$datf = $request->dataf;
        @$datai = date('Y-m-d H:i:s',strtotime($dati));
        @$dataf = date('Y-m-d H:i:s',strtotime($datf));
        if(empty($dati)){
            $user = User::all();
        }else{
            $user = User::whereBetween('created_at', array($datai,$dataf))->get();
        }

        $pdf = PDF::loadView('user.pdf',compact('user'));

		return $pdf->stream();
    }
    public function export() 
    {
        return Excel::download(new UserExport, 'users.xlsx');
    }
}
