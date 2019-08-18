<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Encargo;
use App\Calendario;
use App\Subtag;
use App\Imposto;
use App\ImpostoEncargo;
use Illuminate\Http\Request;

use PDF;
use Auth;
use App\Historico;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;


class EncargoExport implements FromCollection
{
    public function collection()
    {

       if(isset($_GET['filtro'])){
            $filtro = $_GET['filtro'];
        }else{
            $filtro = "";
        }
         $onlyday = date('d');
         $onlymonth = date('m');
         $onlyyear = date('Y');
         $daysinmonth = cal_days_in_month(CAL_GREGORIAN, $onlymonth, $onlyyear); 
         $dayformat = date('Y-m-d');
         $day = date('Y-m-d');
         $month = date('Y-m');

         if($filtro == "diario"){
            return Encargo::where('data','LIKE', '%'.$day.'%')->get();
         }
         if($filtro == "semanal"){
             if($onlyday >= 1 && $onlyday <= 7){
                return Encargo::whereBetween('data'  ,[$month.'-01',$month.'-07'])->get();
             }
             if($onlyday >= 8 && $onlyday <= 14){
                return Encargo::whereBetween('data'  ,[$month.'-08',$month.'-14'])->get();
             } 
             if($onlyday >= 15 && $onlyday <= 21){
                return Encargo::whereBetween('data'  ,[$month.'-15',$month.'-21'])->get();
             } 
             if($onlyday >= 22 && $onlyday <= $daysinmonth){
                return Encargo::whereBetween('data'  ,[$month.'-22',$month.'-'.$daysinmonth])->get();
             } 
         }
         if($filtro == "mensal"){
            return Encargo::where('data','LIKE', '%'.$month.'%')->get();
         }
         if($filtro == "manual"){
             $from = $_GET['de'];
       
             $to = $_GET['ate'];

            return  Encargo::whereBetween('data'  ,[$from,$to])->get();
         }
         
         if(empty($filtro)){
            return Encargo::all();
         }
    }
}

class EncargoController extends Controller
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
        if(isset($_GET['filtro'])){
            $filtro = $_GET['filtro'];
        }else{
            $filtro = "";
        }
       
        if (!empty($keyword)) {
            $encargo = Encargo::where('titulo', 'LIKE', "%$keyword%")
                 ->orWhere('fornecedor', 'LIKE', "%$keyword%")
                 ->orWhere('descricao', 'LIKE', "%$keyword%")
                 ->orWhere('data', 'LIKE', "%$keyword%")
                 ->orWhere('montante', 'LIKE', "%$keyword%")
                 ->get();
         } else {
           
             $onlyday = date('d');
             $onlymonth = date('m');
             $onlyyear = date('Y');
             $daysinmonth = cal_days_in_month(CAL_GREGORIAN, $onlymonth, $onlyyear); 
             $dayformat = date('Y-m-d');
             $day = date('Y-m-d');
             $month = date('Y-m');
 
             if($filtro == "diario"){
                 $encargo = Encargo::where('data','LIKE', '%'.$day.'%')->get();
             }
             if($filtro == "semanal"){
                 if($onlyday >= 1 && $onlyday <= 7){
                     $encargo = Encargo::whereBetween('data'  ,[$month.'-01',$month.'-07'])->get();
                 }
                 if($onlyday >= 8 && $onlyday <= 14){
                     $encargo = Encargo::whereBetween('data'  ,[$month.'-08',$month.'-14'])->get();
                 } 
                 if($onlyday >= 15 && $onlyday <= 21){
                     $encargo = Encargo::whereBetween('data'  ,[$month.'-15',$month.'-21'])->get();
                 } 
                 if($onlyday >= 22 && $onlyday <= $daysinmonth){
                     $encargo = Encargo::whereBetween('data'  ,[$month.'-22',$month.'-'.$daysinmonth])->get();
                 } 
             }
             if($filtro == "mensal"){
                 $encargo = Encargo::where('data','LIKE', '%'.$month.'%')->get();
             }
             if($filtro == "manual"){
                 $from = $_GET['de'];
           
                 $to = $_GET['ate'];

                 $encargo =  Encargo::whereBetween('data'  ,[$from,$to])->get();
             }
             
             if(empty($filtro)){
                 $encargo = Encargo::all();
             }
         }

        return view('encargo.index', compact('encargo','filtro'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('encargo.create');
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

        $evento = $request->evento;
        $statusiva = $request->statusiva;
        $iva = $request->tipoiva;
        $montante = $request->montante;

        $subtag = Subtag::where('id',$iva)->first();
        $perc = $subtag->taxa;
        $percentagem = $perc / 100;

        $valoriva = $montante * $percentagem;



        if($statusiva == "1"){
            $total = $montante;
            //$total = $montante - $iva;
        }else{
            $total = $montante;
        }
        
        $add = Encargo::create([
            'titulo' => $request->titulo,
            'fornecedor' => $request->fornecedor,
            'descricao' => $request->descricao,
            'data' => $request->data,
            'montante' => $total
        ]);


        if($statusiva == "1"){
            $iva = ImpostoEncargo::create([
                'descricao' => "IVA do encargo nº ".$add->id,
                'percentagem' => $perc,
                'data' => $request->data,
                'encargo' => $add->id,
                'montante' => $valoriva
            ]);
        }
        

        // Guardando Histórico
        $ip = request()->ip();
        $log = Historico::create([
            'utilizador' => Auth::user()->id,
            'tipo' => "encargo",
            'acao' => "criou",
            'descricao' => "O utilizador '".Auth::user()->name."' criou o(a) encargo  nº '".$add->id."'.",
            'date' => date('Y-m-d'),
            'ip' => $ip
        ]);
        // Guardando Histórico

        return redirect('encargo')->with('flash_message', 'Encargo added!');
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
        $encargo = Encargo::findOrFail($id);

        return view('encargo.show', compact('encargo'));
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
        $encargo = Encargo::where('id',$id)->first();

        return view('encargo.edit', compact('encargo'));
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
        
        $encargo = Encargo::findOrFail($id);
        $encargo->update($requestData);

        // Guardando Histórico
        $ip = request()->ip();
        $log = Historico::create([
            'utilizador' => Auth::user()->id,
            'acao' => "alterou",
            'tipo' => "encargo",
            'descricao' => "O utilizador '".Auth::user()->name."' alterou o(a) encargo  nº '".$id."'.",
            'date' => date('Y-m-d'),
            'ip' => $ip
        ]);
        // Guardando Histórico

        return redirect('encargo')->with('flash_message', 'Encargo updated!');
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
        Encargo::destroy($id);

        // Guardando Histórico
        $ip = request()->ip();
        $log = Historico::create([
            'utilizador' => Auth::user()->id,
            'tipo' => "encargo",
            'acao' => "eliminou",
            'descricao' => "O utilizador '".Auth::user()->name."' eliminou o(a) encargo  nº '".$id."'.",
            'date' => date('Y-m-d'),
            'ip' => $ip
        ]);
        // Guardando Histórico

        return redirect('encargo')->with('flash_message', 'Encargo deleted!');
    }
    public function report(Request $request)
    {    
        if(isset($_GET['filtro'])){
            $filtro = $_GET['filtro'];
        }else{
            $filtro = "";
        }
         $onlyday = date('d');
         $onlymonth = date('m');
         $onlyyear = date('Y');
         $daysinmonth = cal_days_in_month(CAL_GREGORIAN, $onlymonth, $onlyyear); 
         $dayformat = date('Y-m-d');
         $day = date('Y-m-d');
         $month = date('Y-m');

         if($filtro == "diario"){
             $encargo = Encargo::where('data','LIKE', '%'.$day.'%')->get();
             $soma = Encargo::where('data','LIKE', '%'.$day.'%')->sum('montante');
             $iva = ImpostoEncargo::where('data','LIKE', '%'.$day.'%')->sum('montante');
         }
         if($filtro == "semanal"){
             if($onlyday >= 1 && $onlyday <= 7){
                 $encargo = Encargo::whereBetween('data'  ,[$month.'-01',$month.'-07'])->get();
                 $soma = Encargo::whereBetween('data'  ,[$month.'-01',$month.'-07'])->sum('montante');
                 $iva = ImpostoEncargo::whereBetween('data'  ,[$month.'-01',$month.'-07'])->sum('montante');
             }
             if($onlyday >= 8 && $onlyday <= 14){
                 $encargo = Encargo::whereBetween('data'  ,[$month.'-08',$month.'-14'])->get();
                 $soma = Encargo::whereBetween('data'  ,[$month.'-08',$month.'-14'])->sum('montante');
                 $iva = ImpostoEncargo::whereBetween('data'  ,[$month.'-08',$month.'-14'])->sum('montante');
             } 
             if($onlyday >= 15 && $onlyday <= 21){
                 $encargo = Encargo::whereBetween('data'  ,[$month.'-15',$month.'-21'])->get();
                 $soma = Encargo::whereBetween('data'  ,[$month.'-15',$month.'-21'])->sum('montante');
                 $iva = ImpostoEncargo::whereBetween('data'  ,[$month.'-15',$month.'-21'])->sum('montante');
             } 
             if($onlyday >= 22 && $onlyday <= $daysinmonth){
                 $encargo = Encargo::whereBetween('data'  ,[$month.'-22',$month.'-'.$daysinmonth])->get();
                 $soma = Encargo::whereBetween('data'  ,[$month.'-22',$month.'-'.$daysinmonth])->sum('montante');
                 $iva = ImpostoEncargo::whereBetween('data'  ,[$month.'-22',$month.'-'.$daysinmonth])->sum('montante');
             } 
         }
         if($filtro == "mensal"){
             $encargo = Encargo::where('data','LIKE', '%'.$month.'%')->get();
             $soma = Encargo::where('data','LIKE', '%'.$month.'%')->sum('montante');
             $iva = ImpostoEncargo::where('data','LIKE', '%'.$month.'%')->sum('montante');
         }
         if($filtro == "manual"){
             $from = $_GET['de'];
       
             $to = $_GET['ate'];

             $encargo =  Encargo::whereBetween('data'  ,[$from,$to])->get();
             $soma =  Encargo::whereBetween('data'  ,[$from,$to])->sum('montante');
             $iva = ImpostoEncargo::whereBetween('data'  ,[$from,$to])->sum('montante');
         }
         
         if(empty($filtro)){
             $encargo = Encargo::all();
             $soma = Encargo::all()->sum('montante');;
             $iva = ImpostoEncargo::all()->sum('montante');;
         }

        $pdf = PDF::loadView('encargo.pdf',compact('encargo','soma','iva'));

		return $pdf->stream();
    }
    public function export() 
    {
        @$filtro = $_GET['filtro'];
        @$day = date('d_m_Y');
        @$month = date('m_Y');

        if($filtro == "diario"){
            return Excel::download(new EncargoExport, 'despesa_diario_'.$day.'.xlsx');
        }
        if($filtro == "semanal"){
            return Excel::download(new EncargoExport, 'despesa_semanal_'.$day.'.xlsx');
        }
        if($filtro == "mensal"){
            return Excel::download(new EncargoExport, 'despesa_mensal_'.$day.'.xlsx');
        }
        if($filtro == "manual"){
            return Excel::download(new EncargoExport, 'despesa_manual_'.$day.'.xlsx');
        }if(!isset($filtro)){
            return Excel::download(new EncargoExport, 'despesa_tudo_'.$day.'.xlsx');
        }
    }
}
