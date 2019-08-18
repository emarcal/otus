<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Calendario;
use Illuminate\Http\Request;

use PDF;
use Auth;
use Session;
use App\Historico;
use App\Motoristum;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;


class CalendarioExport implements FromCollection
{
    public function collection()
    {
            @$filtro = $_GET['filtro'];
            @$moto = $_GET['motorista'];
            @$from = $_GET['de'];
            @$to = $_GET['ate'];
            $onlyday = date('d');
            $onlymonth = date('m');
            $onlyyear = date('Y');
            $daysinmonth = cal_days_in_month(CAL_GREGORIAN, $onlymonth, $onlyyear); 
            $dayformat = date('Y-m-dTH:i');
            $day = date('Y-m-d');
            $month = date('Y-m');

            if(empty($moto)){
                $motorista = "";
            }else{
                $motorista = $moto;
            }
            if($filtro == "diario"){
                if($motorista == ""){
                    return Calendario::where('fim','LIKE', '%'.$day.'%')->get();
                }else{
                    return Calendario::where('fim','LIKE', '%'.$day.'%')->where('item',$motorista)->get();
                } 
                
            }
            if($filtro == "semanal"){
                if($onlyday >= 1 && $onlyday <= 7){
                    if($motorista == ""){
                        return Calendario::whereBetween('fim'  ,[$month.'-01T00:00',$month.'-07T00:00'])->get();
                    }else{
                        return Calendario::whereBetween('fim'  ,[$month.'-01T00:00',$month.'-07T00:00'])->where('item',$motorista)->get();
                    } 
                    
                }
                if($onlyday >= 8 && $onlyday <= 14){
                    if($motorista == ""){
                        return Calendario::whereBetween('fim'  ,[$month.'-08T00:00',$month.'-14T00:00'])->get();
                    }else{
                        return Calendario::whereBetween('fim'  ,[$month.'-08T00:00',$month.'-14T00:00'])->where('item',$motorista)->get();
                    } 
                    
                    
                } 
                if($onlyday >= 15 && $onlyday <= 21){
                    if($motorista == ""){
                        return Calendario::whereBetween('fim'  ,[$month.'-15T00:00',$month.'-21T00:00'])->get();
                    }else{
                        return Calendario::whereBetween('fim'  ,[$month.'-15T00:00',$month.'-21T00:00'])->where('item',$motorista)->get();
                    } 
                    
                } 
                if($onlyday >= 22 && $onlyday <= $daysinmonth){
                    if($motorista == ""){
                        return Calendario::whereBetween('fim'  ,[$month.'-22T00:00',$month.'-'.$daysinmonth.'T00:00'])->get();
                    }else{
                        return Calendario::whereBetween('fim'  ,[$month.'-22T00:00',$month.'-'.$daysinmonth.'T00:00'])->where('item',$motorista)->get();
                    } 
                    
                } 
            }
            if($filtro == "mensal"){
                if($motorista == ""){
                    return Calendario::where('fim','LIKE', '%'.$month.'%')->get();
                }else{
                    return Calendario::where('fim','LIKE', '%'.$month.'%')->where('item',$motorista)->get();
                } 
                
            }
            if($filtro == "manual"){
                $from = $_GET['de'];
                $to = $_GET['ate'];

                if($motorista == ""){
                    return  Calendario::whereBetween('fim'  ,[$from.'T00:00',$to.'T00:00'])->get();
                }else{
                    return  Calendario::whereBetween('fim'  ,[$from.'T00:00',$to.'T00:00'])->where('item',$motorista)->get();
                } 
               
            }
            
            if(!isset($filtro)){
                if($motorista == ""){
                    return Calendario::all();
                }else{
                    return Calendario::where('item',$motorista)->get();
                } 
                
            }
      
    }
}

class CalendarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        if(isset($_GET['filtro'])){
            $filtro = $_GET['filtro'];
        }else{
            $filtro = "";
        }
        if(isset($_GET['motorista'])){
            $motorista = $_GET['motorista'];
            $newmotorista = Session::put('motorista', $motorista);
        }else{
            $motorista = "";

        }

            if(@$_GET['motorista'] != "all"){
                $getmotorista = Session::get('motorista');
            }else{
                $getmotorista = Session::forget('motorista');
            }
   
        
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
           $calendario = Calendario::where('inicio', 'LIKE', "%$keyword%")
                ->orWhere('fim', 'LIKE', "%$keyword%")
                ->orWhere('titulo', 'LIKE', "%$keyword%")
                ->orWhere('descricao', 'LIKE', "%$keyword%")
                ->orWhere('item', 'LIKE', "%$keyword%")
                ->orWhere('app', 'LIKE', "%$keyword%")
                ->get();
        } else {
            $onlyday = date('d');
            $onlymonth = date('m');
            $onlyyear = date('Y');
            $daysinmonth = cal_days_in_month(CAL_GREGORIAN, $onlymonth, $onlyyear); 
            $dayformat = date('Y-m-dTH:i');
            $day = date('Y-m-d');
            $month = date('Y-m');

            if($filtro == "diario"){
                if($getmotorista == ""){
                    $calendario = Calendario::where('fim','LIKE', '%'.$day.'%')->get();
                }else{
                    $calendario = Calendario::where('fim','LIKE', '%'.$day.'%')->where('item',$getmotorista)->get();
                } 
            }
            if($filtro == "semanal"){
                if($onlyday >= 1 && $onlyday <= 7){
                    if($getmotorista == ""){
                        $calendario = Calendario::whereBetween('fim'  ,[$month.'-01T00:00',$month.'-07T00:00'])->get();
                    }else{
                        $calendario = Calendario::whereBetween('fim'  ,[$month.'-01T00:00',$month.'-07T00:00'])->where('item',$getmotorista)->get();
                    }  
                }
                if($onlyday >= 8 && $onlyday <= 14){
                    if($getmotorista == ""){
                        $calendario = Calendario::whereBetween('fim'  ,[$month.'-08T00:00',$month.'-14T00:00']);
                    }else{
                        $calendario = Calendario::whereBetween('fim'  ,[$month.'-08T00:00',$month.'-14T00:00'])->where('item',$getmotorista)->get();
                    } 
                    
                } 
                if($onlyday >= 15 && $onlyday <= 21){
                    if($getmotorista == ""){
                        $calendario = Calendario::whereBetween('fim'  ,[$month.'-15T00:00',$month.'-21T00:00'])->get();
                    }else{
                        $calendario = Calendario::whereBetween('fim'  ,[$month.'-15T00:00',$month.'-21T00:00'])->where('item',$getmotorista)->get();
                    } 
                    
                } 
                if($onlyday >= 22 && $onlyday <= $daysinmonth){
                    if($getmotorista == ""){
                        $calendario = Calendario::whereBetween('fim'  ,[$month.'-22T00:00',$month.'-'.$daysinmonth.'T00:00'])->get();
                    }else{
                        $calendario = Calendario::whereBetween('fim'  ,[$month.'-22T00:00',$month.'-'.$daysinmonth.'T00:00'])->where('item',$getmotorista)->get();
                    } 
                    
                } 
            }
            if($filtro == "mensal"){
                if($getmotorista == ""){
                    $calendario = Calendario::where('fim','LIKE', '%'.$month.'%')->get();
                }else{
                    $calendario = Calendario::where('fim','LIKE', '%'.$month.'%')->where('item',$getmotorista)->get();
                } 
                
            }
            if($filtro == "manual"){
                $from = $_GET['de'];
                $to = $_GET['ate'];
                if($getmotorista == ""){
                    $calendario =  Calendario::whereBetween('fim'  ,[$from.'T00:00',$to.'T00:00'])->get();
                }else{
                    $calendario =  Calendario::whereBetween('fim'  ,[$from.'T00:00',$to.'T00:00'])->where('item',$getmotorista)->get();
                } 
                
            }
            
            if(empty($filtro)){
                if($getmotorista == ""){
                    $calendario = Calendario::all();
                }else{
                    $calendario = Calendario::where('item',$getmotorista)->get();
                }
                
            }
        }
        $motoristas = Motoristum::all();

        return view('calendario.index', compact('calendario','filtro','motoristas','getmotorista'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('calendario.create');
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
        
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
     

        $hora_inicial = $request->hora_inicial;
        $hora_final = $request->hora_final;

        

        $add = Calendario::create([
            'titulo' => $request->titulo,
            'inicio' => $data_inicial."T".$hora_inicial,
            'fim' => $data_final."T".$hora_final,
            'item' => $request->item,
            'app' => $request->app,
            'cliente' => $request->cliente,
            'descricao' => $request->descricao
        ]);

        // Guardando Histórico
        $ip = request()->ip();
        $log = Historico::create([
            'utilizador' => Auth::user()->id,
            'tipo' => "calendario",
            'acao' => "criou",
            'descricao' => "O utilizador '".Auth::user()->name."' criou o(a) calendario  nº '".$add->id."'.",
            'date' => date('Y-m-d'),
            'ip' => $ip
        ]);
        // Guardando Histórico

        return redirect('eventos');
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
        Calendario::destroy($id);

        // Guardando Histórico
        $ip = request()->ip();
        $log = Historico::create([
            'utilizador' => Auth::user()->id,
            'tipo' => "calendario",
            'acao' => "eliminou",
            'descricao' => "O utilizador '".Auth::user()->name."' eliminou o(a) calendario  nº '".$id."'.",
            'date' => date('Y-m-d'),
            'ip' => $ip
        ]);
        // Guardando Histórico

        return redirect('eventos')->with('flash_message', 'Calendario deleted!');

 
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
        $calendario = Calendario::where('id',$id)->first();

        return view('calendario.edit', compact('calendario'));
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
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        

        $hora_inicial = $request->hora_inicial;
        $hora_final = $request->hora_final;

        
        $calendario = Calendario::where('id',$id)->first();
        $calendario->titulo = $request->titulo;
        $calendario->inicio = $data_inicial."T".$hora_inicial;
        $calendario->fim = $data_final."T".$hora_final;
        $calendario->item = $request->item;
        $calendario->app = $request->app;
        $calendario->cliente = $request->cliente;
        $calendario->descricao = $request->descricao;
        $calendario->save();

        // Guardando Histórico
        $ip = request()->ip();
        $log = Historico::create([
            'utilizador' => Auth::user()->id,
            'acao' => "alterou",
            'tipo' => "calendario",
            'descricao' => "O utilizador '".Auth::user()->name."' alterou o(a) calendario  nº '".$id."'.",
            'date' => date('Y-m-d'),
            'ip' => $ip
        ]);
        // Guardando Histórico

        return redirect('eventos');
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
        Calendario::destroy($id);

        // Guardando Histórico
        $ip = request()->ip();
        $log = Historico::create([
            'utilizador' => Auth::user()->id,
            'tipo' => "calendario",
            'acao' => "eliminou",
            'descricao' => "O utilizador '".Auth::user()->name."' eliminou o(a) calendario  nº '".$id."'.",
            'date' => date('Y-m-d'),
            'ip' => $ip
        ]);
        // Guardando Histórico

        return redirect('eventos')->with('flash_message', 'Calendario deleted!');
    }

    public function report(Request $request)
    {    
        @$filtro = $_GET['filtro'];
        @$from = $_GET['de'];
        @$to = $_GET['ate'];
        @$moto = $_GET['motorista'];
        $onlyday = date('d');
        $onlymonth = date('m');
        $onlyyear = date('Y');
        $daysinmonth = cal_days_in_month(CAL_GREGORIAN, $onlymonth, $onlyyear); 
        $dayformat = date('Y-m-dTH:i');
        $day = date('Y-m-d');
        $month = date('Y-m');

        if(empty($moto)){
            $motorista = "";
        }else{
            $motorista = $moto;
        }
        if($filtro == "diario"){
            if($motorista == ""){
                $calendario = Calendario::where('fim','LIKE', '%'.$day.'%')->get();
            }else{
                $calendario = Calendario::where('fim','LIKE', '%'.$day.'%')->where('item',$motorista)->get();
            } 
            
        }
        if($filtro == "semanal"){
            if($onlyday >= 1 && $onlyday <= 7){
                if($motorista == ""){
                    $calendario = Calendario::whereBetween('fim'  ,[$month.'-01T00:00',$month.'-07T00:00'])->get();
                }else{
                    $calendario = Calendario::whereBetween('fim'  ,[$month.'-01T00:00',$month.'-07T00:00'])->where('item',$motorista)->get();
                } 
                
            }
            if($onlyday >= 8 && $onlyday <= 14){
                if($motorista == ""){
                    $calendario = Calendario::whereBetween('fim'  ,[$month.'-08T00:00',$month.'-14T00:00'])->get();
                }else{
                    $calendario = Calendario::whereBetween('fim'  ,[$month.'-08T00:00',$month.'-14T00:00'])->where('item',$motorista)->get();
                } 
                
                
            } 
            if($onlyday >= 15 && $onlyday <= 21){
                if($motorista == ""){
                    $calendario = Calendario::whereBetween('fim'  ,[$month.'-15T00:00',$month.'-21T00:00'])->get();
                }else{
                    $calendario = Calendario::whereBetween('fim'  ,[$month.'-15T00:00',$month.'-21T00:00'])->where('item',$motorista)->get();
                } 
                
            } 
            if($onlyday >= 22 && $onlyday <= $daysinmonth){
                if($motorista == ""){
                    $calendario = Calendario::whereBetween('fim'  ,[$month.'-22T00:00',$month.'-'.$daysinmonth.'T00:00'])->get();
                }else{
                    $calendario = Calendario::whereBetween('fim'  ,[$month.'-22T00:00',$month.'-'.$daysinmonth.'T00:00'])->where('item',$motorista)->get();
                } 
                
            } 
        }
        if($filtro == "mensal"){
            if($motorista == ""){
                $calendario = Calendario::where('fim','LIKE', '%'.$month.'%')->get();
            }else{
                $calendario = Calendario::where('fim','LIKE', '%'.$month.'%')->where('item',$motorista)->get();
            } 
            
        }
        if($filtro == "manual"){
            $from = $_GET['de'];
            $to = $_GET['ate'];

            if($motorista == ""){
                $calendario =  Calendario::whereBetween('fim'  ,[$from.'T00:00',$to.'T00:00'])->get();
            }else{
                $calendario =  Calendario::whereBetween('fim'  ,[$from.'T00:00',$to.'T00:00'])->where('item',$motorista)->get();
            } 
           
        }
        
        if(empty($filtro)){
            if($motorista == ""){
                $calendario = Calendario::all();
            }else{
                $calendario = Calendario::where('item',$motorista)->get();
            } 
            
        }

        $pdf = PDF::loadView('calendario.pdf',compact('calendario'));

		return $pdf->stream();
    }
    public function export() 
    {
        @$filtro = $_GET['filtro'];
        @$day = date('d_m_Y');
        @$month = date('m_Y');

        if($filtro == "diario"){
            return Excel::download(new CalendarioExport, 'evento_diario_'.$day.'.xlsx');
        }
        if($filtro == "semanal"){
            return Excel::download(new CalendarioExport, 'evento_semanal_'.$day.'.xlsx');
        }
        if($filtro == "mensal"){
            return Excel::download(new CalendarioExport, 'evento_mensal_'.$day.'.xlsx');
        }
        if($filtro == "manual"){
            return Excel::download(new CalendarioExport, 'evento_manual_'.$day.'.xlsx');
        }if(!isset($filtro)){
            return Excel::download(new CalendarioExport, 'evento_tudo_'.$day.'.xlsx');
        }
    }
     public function agenda()
    {
        $calendario = Calendario::all();

        return view('calendario.agenda', compact('calendario'));
    }
     public function newevent(Request $request)
    {
        $titulo = $request->titulo;
        $item = $request->item;
        $cliente = $request->cliente;
        $inicio_data = $request->inicio_data;
        $inicio_hora = $request->inicio_hora;
        $fim_data = $request->fim_data;
        $fim_hora = $request->fim_hora;
        $descricao = $request->descricao;

        $new = Calendario::create([
            'titulo' => $titulo,
            'item' => $item,
            'descricao' => $descricao,
            'cliente' => $cliente,
            'app' => 'aceito',
            'inicio' => $inicio_data."T".$inicio_hora,
            'fim' => $fim_data."T".$fim_hora
        ]);
        // Guardando Histórico
        $ip = request()->ip();
        $log = Historico::create([
            'utilizador' => Auth::user()->id,
            'tipo' => "evento",
            'acao' => "criou",
            'descricao' => "O utilizador '".Auth::user()->name."' criou o evento  nº '".$new->id."'.",
            'date' => date('Y-m-d'),
            'ip' => $ip
        ]);
        // Guardando Histórico

        return back();
    }
    public function deleteevent(Request $request)
    {
        $id = $request->id;
        Calendario::destroy($id);

        // Guardando Histórico
        $ip = request()->ip();
        $log = Historico::create([
            'utilizador' => Auth::user()->id,
            'tipo' => "evento",
            'acao' => "eliminou",
            'descricao' => "O utilizador '".Auth::user()->name."' eliminou o evento  nº '".$id."'.",
            'date' => date('Y-m-d'),
            'ip' => $ip
        ]);
        // Guardando Histórico

        return back();
    }

}
