<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Redirect;

use DB;
#use View;
use App\Models\CAO_USUARIO;
use App\Models\PERMISSAO_SISTEMA;
use App\Models\CAO_FATURA;
use App\Models\CAO_OS;
#use Illuminate\Support\Facades\View;

class PerformanceController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $consultores = DB::table('cao_usuario')
            ->join('permissao_sistema', 'cao_usuario.co_usuario', '=', 'permissao_sistema.co_usuario')
            ->select('cao_usuario.co_usuario','cao_usuario.no_usuario','cao_usuario.nu_telefone','cao_usuario.msn', 'permissao_sistema.*')
            ->whereRaw('permissao_sistema.co_sistema=1 and permissao_sistema.in_ativo= "S" and permissao_sistema.co_tipo_usuario in (0,1,2) and cao_usuario.co_usuario != "agence.ms"')
            ->orderBy('cao_usuario.no_usuario', 'ASC')
            ->get();
            /*->pluck('cao_usuario.co_usuario','cao_usuario.co_usuario');*/

        return view('performance.index', ['consultores'   => $consultores]);
    }


    public function grafico(Request $data)
    {

        $send_selected= $data->send_selected;
        $fecha_i=$data->fecha_inicio;
        $fecha_f=$data->fecha_final;


        $fecha_i=substr($fecha_i, 0, -3 ).'-01';
        $fecha_f=substr($fecha_f, 0, -3 ).'-31';

        $res='empty';

        $in=$send_selected;

        $contador=count($in);

        $aux='';

        foreach ($in as $iterador) {

            $contador--;
            
            $aux.="'".$iterador."'";

            if ($contador>0) { $aux.=','; }
        }

        $in=$aux;


            $check=array();
            $costo_promedio=array();
            $users_data=array();
            $datos=array();


                $consultores = DB::table('cao_fatura')
                ->join('cao_os', 'cao_fatura.co_os', '=', 'cao_os.co_os')
                ->join('cao_usuario', 'cao_usuario.co_usuario', '=', 'cao_os.co_usuario')
                ->join('cao_salario', 'cao_salario.co_usuario', '=', 'cao_os.co_usuario')
                ->select('cao_usuario.no_usuario','cao_salario.brut_salario','cao_usuario.co_usuario',
                'cao_fatura.data_emissao',
                DB::raw('SUM( cao_fatura.valor - (cao_fatura.valor * (cao_fatura.total_imp_inc / 100)) ) AS ganancia'),
                DB::raw('SUM( (cao_fatura.valor - (cao_fatura.valor * (cao_fatura.total_imp_inc / 100))) * (cao_fatura.comissao_cn / 100) ) AS comissao')
                )
                ->whereRaw('cao_fatura.data_emissao BETWEEN "'.$fecha_i.'" AND "'.$fecha_f.'" and cao_usuario.co_usuario IN ( '.$in.')' )
                ->groupBy('cao_usuario.no_usuario', 'cao_fatura.data_emissao', 'cao_salario.brut_salario', 'cao_usuario.co_usuario')
                ->get();


                foreach ($consultores as $iterador) {

                    $anyoMes=substr($iterador->data_emissao, 0, -3 );

                    $temp_year=substr($iterador->data_emissao, 0, -6 );


                    $costo_promedio[$iterador->no_usuario]['costo_fijo']=round($iterador->brut_salario,2);

                    $users_data[$iterador->no_usuario][$anyoMes][]=$iterador->ganancia;


                    if(!isset($check['fecha'][$anyoMes])){

                        $check['fecha'][$anyoMes]=0;

                        $date = \Carbon\Carbon::parse($anyoMes.'-01');

                        #$date->format('F'));    // March

                        $datos['datos']['fecha_data'][$anyoMes][]=$date->format('F').' '.$temp_year;                        
                    }

                }#fin foreach consultores

                //promedio costo fijo

                $promedio=0;

                foreach ($costo_promedio as $iterador) {
                    $promedio+=$iterador['costo_fijo'];
                }

                if ($promedio>0) {
                    

                    $promedio/=count($costo_promedio);


                    foreach ($datos['datos']['fecha_data'] as $key => $value) {


                        array_push($datos['datos']['fecha_data'][$key], $promedio);

                        foreach ($users_data as $key2 => $value2) {

                            if (!array_key_exists($key, $value2)) {
                                //rellena usuarios con cero

                                $users_data[$key2][$key][]=0;
                            }
                        }

                    }


                    $datos['datos']['usuario'][]='Custo Fixo Medio';

                    foreach ($users_data as $key => $value) {

                        $aux=0.0;

                        foreach ($value as $key2 => $value2) {

                            $aux=array_sum($value2);

                            array_push($datos['datos']['fecha_data'][$key2], round($aux,2));

                        }
                       
                       //usuarios
                        $datos['datos']['usuario'][]= $key;

                    }   

                $res=$datos;

                }#fin if promedio = 0 (sin datos)

        return response()->json($res);

    }#fin function grafico



    public function pizza(Request $data)
    {

        $send_selected= $data->send_selected;
        $fecha_i=$data->fecha_inicio;
        $fecha_f=$data->fecha_final;


        $fecha_i=substr($fecha_i, 0, -3 ).'-01';
        $fecha_f=substr($fecha_f, 0, -3 ).'-31';

        $res='empty';

                #$aux_users=array();

                foreach ($send_selected as $iterador) {

                    $aux_sql = DB::table('cao_usuario')->select('cao_usuario.no_usuario')->where('cao_usuario.co_usuario','=',$iterador)->get();

                    $consultores = DB::table('cao_fatura')
                    ->join('cao_os', 'cao_fatura.co_os', '=', 'cao_os.co_os')
                    ->join('cao_usuario', 'cao_usuario.co_usuario', '=', 'cao_os.co_usuario')
                    ->join('cao_salario', 'cao_salario.co_usuario', '=', 'cao_os.co_usuario')

                    ->select(DB::raw('SUM(cao_fatura.valor) - SUM(cao_fatura.valor * (cao_fatura.total_imp_inc /100) ) AS ganancia'))
                    ->whereRaw('cao_fatura.data_emissao BETWEEN "'.$fecha_i.'" AND "'.$fecha_f.'" and cao_usuario.co_usuario = "'.$iterador.'"' )
                    ->groupBy('cao_usuario.no_usuario')
                    ->get();


                    if (isset($consultores[0]->ganancia)) {
                    
                        $aux_users[$iterador]['ganancia']=round($consultores[0]->ganancia,2);
                        $aux_users[$iterador]['co_usuario']=$iterador;
                        $aux_users[$iterador]['no_usuario']=$aux_sql[0]->no_usuario;
                        
                    }

                }

                if (isset($aux_users)) {
                    $res=$aux_users;
                }

        return response()->json($res);

    }#fin function pizza


    public function relatorio(Request $datos)
    {   #dd($datos);

        $send_selected = $datos->send_selected;
        $fecha_i = $datos->fecha_inicio;
        $fecha_f = $datos->fecha_final;

        $fecha_i=substr($fecha_i, 0, -3 ).'-01';
        $fecha_f=substr($fecha_f, 0, -3 ).'-31';


        $res='empty';

        // create sql part for IN condition by imploding comma after each id
        //$in = explode(',', $send_selected);

        $in=$send_selected;



        $usuarios['co_usuario'] = $in;

        $contador=count($in);

        $aux='';

        foreach ($in as $iterador) {

            $contador--;
            
            $aux.="'".$iterador."'";

            if ($contador>0) { $aux.=','; }
        }

        $in=$aux;

        #$struct=array();

                $consultores = DB::table('cao_fatura')
                ->join('cao_os', 'cao_fatura.co_os', '=', 'cao_os.co_os')
                ->join('cao_usuario', 'cao_usuario.co_usuario', '=', 'cao_os.co_usuario')
                ->join('cao_salario', 'cao_salario.co_usuario', '=', 'cao_os.co_usuario')
                ->select(DB::raw('cao_fatura.co_os, cao_fatura.data_emissao, cao_fatura.valor, cao_fatura.total_imp_inc, cao_fatura.comissao_cn, cao_os.co_usuario, cao_usuario.no_usuario, cao_salario.brut_salario, (cao_fatura.valor - (cao_fatura.valor  * (cao_fatura.total_imp_inc /100) ) ) * (cao_fatura.comissao_cn / 100) as comision'))
                ->whereRaw('cao_fatura.data_emissao BETWEEN "'.$fecha_i.'" AND "'.$fecha_f.'" and cao_usuario.co_usuario IN ( '.$in.')' )
                ->orderBy('cao_fatura.data_emissao','cao_usuario.no_usuario', 'ASC')
                ->get();


                foreach ($consultores as $iterador) {
                    
                    $struct[$iterador->co_usuario ]['nombre']= $iterador->no_usuario;
                    $struct[$iterador->co_usuario ]['codigo_usuario']= $iterador->co_usuario;
                    $struct[$iterador->co_usuario ]['salario']= round($iterador->brut_salario,2);

                    $anyoMes=substr($iterador->data_emissao, 0, -3 );

                    if (!isset($struct[$iterador->co_usuario]['fecha'][ $anyoMes ]['liquida'])) {
                        
                        $struct[$iterador->co_usuario]['fecha'][ $anyoMes ]['liquida'] = round($iterador->valor,2);

                    }else{
                        $struct[$iterador->co_usuario]['fecha'][ $anyoMes ]['liquida'] += round($iterador->valor,2);
                    }


                    if (!isset($struct[$iterador->co_usuario]['fecha'][ $anyoMes ]['comision'])) {
                        
                        $struct[$iterador->co_usuario]['fecha'][ $anyoMes ]['comision'] = round($iterador->comision,2);

                    }else{
                        $struct[$iterador->co_usuario]['fecha'][ $anyoMes ]['comision'] += round($iterador->comision,2);
                    }


                    if (!isset($struct[$iterador->co_usuario]['fecha'][ $anyoMes ]['lucro'])) {
                        
                        $struct[$iterador->co_usuario]['fecha'][ $anyoMes ]['lucro'] = round($iterador->valor - ($iterador->brut_salario + $iterador->comision ),2);

                    }else{
                        $struct[$iterador->co_usuario]['fecha'][ $anyoMes ]['lucro'] += round($iterador->valor - ($iterador->brut_salario + $iterador->comision ),2);
                    }

                }


                if ( !isset( $struct) ) {
                    $struct='empty';
                }

                $res=$struct;


        return view('performance.partials.relatorio', ['res'=>$res])->render();


    }#fin function relatorio


    
}
