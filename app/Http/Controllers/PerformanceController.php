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
            ->whereRaw('permissao_sistema.co_sistema=1 and permissao_sistema.in_ativo= "S" ')
            ->orderBy('cao_usuario.no_usuario', 'ASC')
            ->get();

        $data = ['consultores'   => $consultores,
        ];

        return view('performance.index', $data);
    }

    public function calc_show(Request $data,$submit_type,$send_selected,$fecha_i,$fecha_f)
    {

        /*if ($send_selected=='')
        {
        return redirect()->back()->withInput()->withErrors($validador->errors());
        }*/



        $res='empty';

        // create sql part for IN condition by imploding comma after each id
        $in = explode(',', $send_selected);

        $usuarios['co_usuario'] = $in;

        $contador=count($in);

        $aux='';

        foreach ($in as $iterador) {

            $contador--;
            
            $aux.="'".$iterador."'";

            if ($contador>0) { $aux.=','; }
        }

        $in=$aux;

        $usuario_no_data['users']='';
        $usuario_no_data['status']=false;

        if ($submit_type=='relatorio') {

            $consultores = DB::table('cao_fatura')
            ->join('cao_os', 'cao_fatura.co_os', '=', 'cao_os.co_os')
            ->join('cao_usuario', 'cao_usuario.co_usuario', '=', 'cao_os.co_usuario')
            ->join('cao_salario', 'cao_salario.co_usuario', '=', 'cao_os.co_usuario')
            ->select(DB::raw('cao_fatura.co_os, cao_fatura.data_emissao, cao_fatura.valor, cao_fatura.total_imp_inc, cao_fatura.comissao_cn, cao_os.co_usuario, cao_usuario.no_usuario, cao_salario.brut_salario, (cao_fatura.valor - (cao_fatura.valor  * (cao_fatura.total_imp_inc /100) ) ) * (cao_fatura.comissao_cn / 100) as comision'))
            ->whereRaw('cao_fatura.data_emissao BETWEEN "'.$fecha_i.'" AND "'.$fecha_f.'" and cao_usuario.co_usuario IN ( '.$in.')' )
            ->orderBy('cao_fatura.data_emissao','cao_usuario.no_usuario', 'DEC')
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


            $res=$struct;
            
        }else{ //grafico, pizza

            $aux_users='';

                foreach ($usuarios['co_usuario'] as $iterador) {

                    $aux_sql = DB::table('cao_usuario')->select('cao_usuario.no_usuario')->where('cao_usuario.co_usuario','=',$iterador)->get();

                    #dd($aux_sql[0]->no_usuario);
                    #dd($aux_sql->items->no_usuario);                    

                    $consultores = DB::table('cao_fatura')
                    ->join('cao_os', 'cao_fatura.co_os', '=', 'cao_os.co_os')
                    ->join('cao_usuario', 'cao_usuario.co_usuario', '=', 'cao_os.co_usuario')
                    ->join('cao_salario', 'cao_salario.co_usuario', '=', 'cao_os.co_usuario')

                    ->select(DB::raw('SUM(cao_fatura.valor) - SUM(cao_fatura.valor * (cao_fatura.total_imp_inc /100) ) AS ganancia'))
                    ->whereRaw('cao_fatura.data_emissao BETWEEN "'.$fecha_i.'" AND "'.$fecha_f.'" and cao_usuario.co_usuario = "'.$iterador.'"' )
                    ->groupBy('cao_usuario.no_usuario')
                    ->get();

                   
                        
                        if ( is_numeric($consultores[0]->ganancia) ) {
                        $aux_users[$iterador]['ganancia']=round($consultores[0]->ganancia,2);
                        $aux_users[$iterador]['co_usuario']=$iterador;
                        $aux_users[$iterador]['no_usuario']=$aux_sql[0]->no_usuario;
                        }

                        else{

                            $usuario_no_data['users'].=$aux_sql[0]->no_usuario.', ';
                            $usuario_no_data['status']=true;
                        }

                    

                }

                $res=$aux_users;

        }

        return  json_encode($res);

    }#fin function calc show



    public function calc_view(Request $datos)
    {   #dd($datos);

        $validador = \Validator::make($datos->all(), [
        'selected' => 'required',
            ]);

        if ($validador->fails())
        {
        return redirect()->back()->withInput()->withErrors($validador->errors());
        }
        
        $send_selected = $datos->selected;
        $submit_type = $datos->submit_type;
        $fecha_i = $datos->fecha_inicio;
        $fecha_f = $datos->fecha_final;


        $res='empty';

        // create sql part for IN condition by imploding comma after each id
        $in = explode(',', $send_selected);

        $usuarios['co_usuario'] = $in;

        $contador=count($in);

        $aux='';

        foreach ($in as $iterador) {

            $contador--;
            
            $aux.="'".$iterador."'";

            if ($contador>0) { $aux.=','; }
        }

        #$in = implode(',', $in)

        $in=$aux;

        $usuario_no_data['users']='';
        $usuario_no_data['status']=false;

        if ($submit_type=='relatorio') {

            $consultores = DB::table('cao_fatura')
            ->join('cao_os', 'cao_fatura.co_os', '=', 'cao_os.co_os')
            ->join('cao_usuario', 'cao_usuario.co_usuario', '=', 'cao_os.co_usuario')
            ->join('cao_salario', 'cao_salario.co_usuario', '=', 'cao_os.co_usuario')
            ->select(DB::raw('cao_fatura.co_os, cao_fatura.data_emissao, cao_fatura.valor, cao_fatura.total_imp_inc, cao_fatura.comissao_cn, cao_os.co_usuario, cao_usuario.no_usuario, cao_salario.brut_salario, (cao_fatura.valor - (cao_fatura.valor  * (cao_fatura.total_imp_inc /100) ) ) * (cao_fatura.comissao_cn / 100) as comision'))
            ->whereRaw('cao_fatura.data_emissao BETWEEN "'.$fecha_i.'" AND "'.$fecha_f.'" and cao_usuario.co_usuario IN ( '.$in.')' )
            ->orderBy('cao_fatura.data_emissao','cao_usuario.no_usuario', 'DEC')
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


            $res=$struct;
            
        }else{ //grafico, pizza

            $aux_users='';
            $usuario_no_data['users']='';
            $usuario_no_data['status']=false;

                foreach ($usuarios['co_usuario'] as $iterador) {

                    $aux_sql = DB::table('cao_usuario')->select('cao_usuario.no_usuario')->where('cao_usuario.co_usuario','=',$iterador)->get();

                    #dd($aux_sql[0]->no_usuario);
                    #dd($aux_sql->items->no_usuario);                    

                    $consultores = DB::table('cao_fatura')
                    ->join('cao_os', 'cao_fatura.co_os', '=', 'cao_os.co_os')
                    ->join('cao_usuario', 'cao_usuario.co_usuario', '=', 'cao_os.co_usuario')
                    ->join('cao_salario', 'cao_salario.co_usuario', '=', 'cao_os.co_usuario')

                    ->select(DB::raw('SUM(cao_fatura.valor) - SUM(cao_fatura.valor * (cao_fatura.total_imp_inc /100) ) AS ganancia'))
                    ->whereRaw('cao_fatura.data_emissao BETWEEN "'.$fecha_i.'" AND "'.$fecha_f.'" and cao_usuario.co_usuario = "'.$iterador.'"' )
                    ->groupBy('cao_usuario.no_usuario')
                    ->get();

                    if ( isset($aux_users[$iterador]['co_usuario']) ) {
                        $aux_users[$iterador]['ganancia']=round($consultores[0]->ganancia,2);
                        $aux_users[$iterador]['co_usuario']=$iterador;
                        $aux_users[$iterador]['no_usuario']=$aux_sql[0]->no_usuario;
                    }

                    else{

                        $usuario_no_data['users'].=$aux_sql[0]->no_usuario.', ';
                        $usuario_no_data['status']=true;
                    }
                    

                }

                $res=$aux_users;

        }

        $consultores = DB::table('cao_usuario')
            ->join('permissao_sistema', 'cao_usuario.co_usuario', '=', 'permissao_sistema.co_usuario')
            ->select('cao_usuario.co_usuario','cao_usuario.no_usuario','cao_usuario.nu_telefone','cao_usuario.msn', 'permissao_sistema.*')
            ->whereRaw('permissao_sistema.co_sistema=1 and permissao_sistema.in_ativo= "S" ')
            ->orderBy('cao_usuario.no_usuario', 'ASC')
            ->get();


        $data = ['consultores' => $consultores,'datos'=>$datos,'res'=>$res,'submit_type'=>$submit_type, 'usuario_no_data'=>$usuario_no_data ];

        $GLOBALS["res"]=$res;
        $GLOBALS["consultores"]=$consultores;
        $GLOBALS["submit_type"]=$submit_type;
        $GLOBALS["usuario_no_data"]=$usuario_no_data;

        return \View::make('performance.index')->nest('performance.partials','performance.partials.'.$submit_type, compact($data) ) ;

    }#fin function calc_view

    
}
