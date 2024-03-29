<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\cursosRequest;
//use App\Http\Requests\iapsjuridicoRequest;
use App\regCursosModel;
use App\regIapModel;
use App\regBitacoraModel;
use App\regMesesModel;
use App\regPfiscalesModel;
// Exportar a excel 
//use App\Exports\ExcelExportCatIAPS;
//use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class cursosController extends Controller
{

    public function actionVerCursos(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $estructura   = session()->get('estructura');
        $id_estruc    = session()->get('id_estructura');
        $id_estructura= rtrim($id_estruc," ");
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        $regmeses     = regMesesModel::select('MES_ID', 'MES_DESC')->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get(); 
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->get();

        $regcursos    = regCursosModel::select('CURSO_ID', 'IAP_ID','PERIODO_ID','MES_ID','CURSO_DESC','CURSO_OBJ',
            'CURSO_COSTO','CURSO_THORAS','CURSO_FINICIO','CURSO_FFIN','CURSO_OBS',
            'CURSO_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
            ->orderBy('CURSO_ID','ASC')
            ->paginate(30);
        if($regcursos->count() <= 0){
            toastr()->error('No existen registros de cursos dadas de alta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevoCurso');
        }
        return view('sicinar.cursos.verCursos',compact('nombre','usuario','estructura','id_estructura','regiap','regmeses', 'regperiodos', 'regcursos'));
    }

    public function actionNuevoCurso(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $estructura   = session()->get('estructura');
        $id_estruc    = session()->get('id_estructura');
        $id_estructura= rtrim($id_estruc," ");
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        $regmeses     = regMesesModel::select('MES_ID', 'MES_DESC')->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get(); 
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->get();

        $regcursos    = regCursosModel::select('CURSO_ID', 'IAP_ID','PERIODO_ID','MES_ID','CURSO_DESC','CURSO_OBJ',
            'CURSO_COSTO','CURSO_THORAS','CURSO_FINICIO','CURSO_FFIN','CURSO_OBS',
            'CURSO_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
            ->orderBy('CURSO_ID','ASC')
            ->get();
        //dd($unidades);
        return view('sicinar.cursos.nuevoCurso',compact('regmeses','regperiodos','regiap','regcursos','nombre','usuario','estructura','id_estructura'));
    }

    public function actionAltaNuevoCurso(Request $request){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $estructura   = session()->get('estructura');
        $id_estruc    = session()->get('id_estructura');
        $id_estructura= rtrim($id_estruc," ");
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        /************ Obtenemos la IP ***************************/                
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }        

        /************ ALTA DE IAP *****************************/ 
        $curso_id = regCursosModel::max('CURSO_ID');
        $curso_id = $curso_id+1;

        $nuevocurso = new regCursosModel();
        $nuevocurso->CURSO_ID     = $curso_id;
        $nuevocurso->PERIODO_ID   = $request->periodo_id;
        $nuevocurso->MES_ID       = $request->mes_id;
        $nuevocurso->IAP_ID       = $request->iap_id;
        $nuevocurso->CURSO_DESC   = strtoupper($request->curso_desc);
        $nuevocurso->CURSO_OBJ    = strtoupper($request->curso_obj);
        $nuevocurso->CURSO_COSTO  = $request->curso_costo;
        $nuevocurso->CURSO_THORAS = $request->curso_thoras;
        $nuevocurso->CURSO_FINICIO= date('Y/m/d', strtotime($request->curso_finicio));
        $nuevocurso->CURSO_FFIN   = date('Y/m/d', strtotime($request->curso_ffin));
        $nuevocurso->CURSO_OBS    = strtoupper($request->curso_obs);
        $nuevocurso->IP           = $ip;
        $nuevocurso->LOGIN        = $nombre;         // Usuario ;
        $nuevocurso->save();

        if($nuevocurso->save() == true){
            toastr()->success('El curso dado de alta correctamente.','ok!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
            //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','estructura','id_estructura','rango','preguntas','apartados'));
        }else{
            toastr()->error('Error inesperado al dar de alta el curso. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            //return back();
            //return redirect()->route('nuevoProceso');
        }

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         9;
        $xfuncion_id  =      9004;
        $xtrx_id      =       165;    //Alta de Curso

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $curso_id])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $curso_id;       // Folio    
            $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
            $nuevoregBitacora->IP         = $ip;             // IP
            $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $curso_id])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         

            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $curso_id])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/ 
        return redirect()->route('verCursos');
    }


    public function actionEditarCurso($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $estructura    = session()->get('estructura');
        $id_estruc     = session()->get('id_estructura');
        $id_estructura = rtrim($id_estruc," ");
        $rango         = session()->get('rango');

        $regmeses     = regMesesModel::select('MES_ID', 'MES_DESC')->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get(); 
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->get();

        $regcursos    = regCursosModel::select('CURSO_ID', 'IAP_ID','PERIODO_ID','MES_ID','CURSO_DESC','CURSO_OBJ',
            'CURSO_COSTO','CURSO_THORAS','CURSO_FINICIO','CURSO_FFIN','CURSO_OBS',
            'CURSO_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
            ->where('CURSO_ID',$id)
            ->first();
        if($regcursos->count() <= 0){
            toastr()->error('No existe registros de cursos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevoCurso');
        }
        return view('sicinar.cursos.editarCurso',compact('nombre','usuario','estructura','id_estructura','regiap','regmeses','regperiodos','regcursos'));

    }

    public function actionActualizarCurso(cursosRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $estructura    = session()->get('estructura');
        $id_estruc     = session()->get('id_estructura');
        $id_estructura = rtrim($id_estruc," ");
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         9;
        $xfuncion_id  =      9004;
        $xtrx_id      =       166;    //Actualizar         

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $id;             // Folio    
            $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
            $nuevoregBitacora->IP         = $ip;             // IP
            $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/         

        // **************** actualizar ******************************
        $regcursos = regCursosModel::where('CURSO_ID',$id);
        if($regcursos->count() <= 0)
            toastr()->error('No existe curso.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            // ************ Se actulizan datos del curso *************
            echo "Fecha de inicio : " .'-'. $request->curso_finicio .'-'. "<br><br>"; 
            echo "Fecha de inicio : " .'-'. date('Y/m/d', strtotime($request->curso_finicio)) .'-'. "<br><br>"; 
            echo "Fecha de termino 2: " .'-'. $request->curso_ffin .'-'. "<br><br>"; 
            $regcursos = regCursosModel::where('CURSO_ID',$id)        
            ->update([                
                'IAP_ID'        => $request->iap_id,                
                'PERIODO_ID'    => $request->periodo_id,
                'MES_ID'        => $request->mes_id,                
                'CURSO_DESC'    => strtoupper($request->curso_desc),
                'CURSO_OBJ'     => strtoupper($request->curso_obj),
                'CURSO_COSTO'   => $request->curso_costo,
                'CURSO_THORAS'  => $request->curso_thoras,
                //'CURSO_FINICIO' => trim($request->curso_finicio), //date('Y/m/d', strtotime(trim($request->curso_finicio))), 
                //'CURSO_FFIN'    => trim($request->curso_ffin),   //date('Y/m/d', strtotime(trim($request->curso_ffin))),                 
                'CURSO_OBS'     => strtoupper($request->curso_obs),
                'CURSO_STATUS'  => $request->curso_status,                
                'IP_M'          => $ip,
                'LOGIN_M'       => $nombre,
                'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
            ]);
            toastr()->success('Curso actualizado correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        return redirect()->route('verCursos');
        //return view('sicinar.catalogos.verProceso',compact('nombre','usuario','estructura','id_estructura','regproceso'));
    }


    public function actionBorrarCurso($id){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $estructura   = session()->get('estructura');
        $id_estruc    = session()->get('id_estructura');
        $id_estructura= rtrim($id_estruc," ");
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        //echo 'Ya entre aboorar registro..........';
        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         9;
        $xfuncion_id  =      9004;
        $xtrx_id      =       167;     // Baja 

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $id;             // Folio    
            $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
            $nuevoregBitacora->IP         = $ip;             // IP
            $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         

            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/     
        /************ Elimina curso **************************************/
        $regcursos = regCursosModel::where('CURSO_ID',$id);        
        //                    ->find('RUBRO_ID',$id);
        if($regcursos->count() <= 0)
            toastr()->error('No existe IAP.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regcursos->delete();
            toastr()->success('El curso ha sido eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************* Termina de eliminar  la IAP **********************************/
        return redirect()->route('verCursos');
    }    


}