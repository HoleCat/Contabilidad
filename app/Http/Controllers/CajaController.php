<?php

namespace App\Http\Controllers;

use App\Clases\Almacenamiento;
use App\Clases\Caja\Aprobador;
use App\Clases\Caja\Cajachica;
use App\Clases\Caja\LiquidacionDetalle;
use App\Clases\Modelosgenerales\Archivo;
use App\Clases\Uso;
use App\Rendirpago;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use DateTime;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

class CajaController extends Controller
{
    public function nuevaliquidacion() {
        if(Auth::check()){

            $tipo = 13;
            $tiposubuso = 14;
            $idusuario = Auth::user()->id;
            $contadorusocaja = DB::table('usos')->where('idusuario','=',$idusuario)->where('idtipo','=',$tipo)->count();

            if($contadorusocaja > 0){
                $uso = new Uso();
                $uso->idusuario = $idusuario;
                $uso->uso_id = 0;
                $uso->referencia = 'Ejemplo de referencia  liquidacion nueva';
                $uso->idtipo = $tipo;
                $uso->save();

                $uso_id = $uso->id;

                $aprobadores = DB::table('aprobadors')->where('user_id','=',Auth::user()->id)->get();

                $usoliquidacion = new Uso([
                    'idusuario' => $idusuario,
                    'uso_id' => $uso_id,
                    'referencia' => 'Ejemplo de referencia liquidacion nueva',
                    'idtipo' => $tiposubuso,
                ]);
                $usoliquidacion->save();

                return view('modules.caja.liquidacion',['uso' => $usoliquidacion,'aprobadores' => $aprobadores]);

            }
            else {
                $uso = new Uso();
                $uso->idusuario = $idusuario;
                $uso->uso_id = 0;
                $uso->referencia = 'Ejemplo de referencia  liquidacion nueva';
                $uso->idtipo = $tipo;
                $uso->save();

                $usoliquidacion = new Uso([
                    'idusuario' => $idusuario,
                    'uso_id' => $uso->id,
                    'referencia' => 'Ejemplo de referencia liquidacion sin uso general',
                    'idtipo' => $tiposubuso,
                ]);
                $usoliquidacion->save();

                return view('modules.caja.liquidacion',['uso' => $usoliquidacion,'aprobadores' => $aprobadores]);
            }
            
        }
    }

    public function liquidacion(Request $request) {
        $liquidacion = new LiquidacionDetalle();
        $liquidacion->uso_id = $request->input('iduso');
        $liquidacion->servicio = $request->input('servicio');
        $liquidacion->user_id = Auth::user()->id;
        $liquidacion->aprobador_id = $request->input('aprobador_id');
        $liquidacion->motivo = $request->input('motivo');
        $liquidacion->detalle = $request->input('detalle');
        $liquidacion->monto = $request->input('monto');
        $liquidacion->multimoneda = $request->input('multimoneda');
        $liquidacion->tiempo = $request->input('tiempo');
        $liquidacion->liquidacion = $request->input('liquidacion');
        $liquidacion->neto = $request->input('neto');
        $liquidacion->save();

        $uso = Uso::find($request->input('iduso'));
        $tiposdocumento = DB::table('comprobantes')->get();
        $codigocontable = DB::table('codigocontables')->get();
        $monedas = DB::table('monedas')->get();
        $centrocostos = DB::table('centrocostos')->get();
        if($request->input('servicio') == "cajachica")
        {
            return view('modules.caja.cajachica',['centrocostos'=>$centrocostos,'monedas'=>$monedas,'uso'=>$uso,'liquidacion'=>$liquidacion,'documentos'=>$tiposdocumento,'codigocontable'=>$codigocontable]);
        }
        else {
            return view('modules.caja.rendirpago',['centrocostos'=>$centrocostos,'monedas'=>$monedas,'uso'=>$uso,'liquidacion'=>$liquidacion,'documentos'=>$tiposdocumento,'codigocontable'=>$codigocontable]);
        }
    }

    public function cajachica(Request $request) {
        $tiposdocumento = DB::table('comprobantes')->get();
        $codigocontable = DB::table('codigocontables')->get();
        $monedas = DB::table('monedas')->get();
        $centrocostos = DB::table('centrocostos')->get();
        $id = $request->id;
        $liquidacion = LiquidacionDetalle::firstWhere('uso_id', $id);
        $iduso = $liquidacion->uso_id;
        $uso = Uso::firstWhere('id', $iduso);
        return view('modules.caja.cajachica',['centrocostos'=>$centrocostos,'monedas'=>$monedas,'uso'=>$uso,'liquidacion'=>$liquidacion,'documentos'=>$tiposdocumento,'codigocontable'=>$codigocontable]);
    }

    public function cajachicainsert(Request $request) {
        $cajachicha = new Cajachica();
        $cajachicha->liquidacion_id = $request->input('liquidaciondetalle_id');
        $cajachicha->ruc = $request->input('ruc');
        $cajachicha->tipoDocumento = $request->input('tipodocumento');
        $cajachicha->codigodocumento = $request->input('codigodocumento');
        $cajachicha->documento = $request->input('documento');
        $cajachicha->fecha = $request->input('fecha');
        $cajachicha->moneda = $request->input('moneda');
        $cajachicha->concepto = $request->input('concepto');
        $cajachicha->contabilidad = $request->input('contabilidad');
        $cajachicha->centrocosto = $request->input('centrocosto');
        $cajachicha->monto = $request->input('monto');
        
        if($request->input('igv')==0){
            $cajachicha->igv = $cajachicha->monto*0.18;
        } else {
            $cajachicha->igv = 0.0;
        }
        $cajachicha->base = $cajachicha->monto-$cajachicha->igv;
        
        $cajachicha->save();

        $data = DB::table('cajachicas')->where('liquidacion_id','=',$request->input('liquidaciondetalle_id'))->get();
        
        $total = DB::table('cajachicas')->where('liquidacion_id','=',$request->input('liquidaciondetalle_id'))->sum('monto');
        $liqui = LiquidacionDetalle::find($request->input('liquidaciondetalle_id'));
        
        $monto = $liqui->monto;
        $liqui->liquidacion = $total;
        $liqui->neto = $monto - $total;
        $liqui->save();

        return $data;
    }

    public function obtenertotales(Request $request) {
        $liqui = LiquidacionDetalle::find($request->input('id'));
        
        $montoentregado = $liqui->monto;
        $totalusado = $liqui->liquidacion;
        $neto = $liqui->neto;

        return view('modules.caja.totales',['montoentregado'=>$montoentregado,'totalusado'=>$totalusado,'neto'=>$neto]);
    }

    public function cajachicainfo(Request $request) {
        $id = $request->input('liquidacion_id');
        $data = DB::table('cajachicas')->where('liquidacion_id','=',$id)->get();
        return $data;
    }

    public function rendirpago(Request $request) {
        $tiposdocumento = DB::table('comprobantes')->get();
        $codigocontable = DB::table('codigocontables')->get();
        $monedas = DB::table('monedas')->get();
        $centrocostos = DB::table('centrocostos')->get();
        $id = $request->id;
        $liquidacion = LiquidacionDetalle::firstWhere('uso_id', $id);
        $iduso = $liquidacion->uso_id;
        $uso = Uso::firstWhere('id', $iduso);
        return view('modules.caja.rendirpago',['centrocostos'=>$centrocostos,'monedas'=>$monedas,'uso'=>$uso,'liquidacion'=>$liquidacion,'documentos'=>$tiposdocumento,'codigocontable'=>$codigocontable]);
    }

    public function rendirpagoinsert(Request $request) {
        $Rendirpago = new Rendirpago();
        $Rendirpago->liquidacion_id = $request->input('liquidaciondetalle_id');
        $Rendirpago->ruc = $request->input('ruc');
        $Rendirpago->tipoDocumento = $request->input('tipodocumento');
        $Rendirpago->codigodocumento = $request->input('codigodocumento');
        $Rendirpago->documento = $request->input('documento');
        $Rendirpago->fecha = $request->input('fecha');
        $Rendirpago->moneda = $request->input('moneda');
        $Rendirpago->concepto = $request->input('concepto');
        $Rendirpago->contabilidad = $request->input('contabilidad');
        $Rendirpago->centrocosto = $request->input('centrocosto');
        $Rendirpago->monto = $request->input('monto');
        
        if($request->input('igv')==0){
            $Rendirpago->igv = $Rendirpago->monto*0.18;
        } else {
            $Rendirpago->igv = 0.0;
        }
        $Rendirpago->base = $Rendirpago->monto-$Rendirpago->igv;
        
        $Rendirpago->save();

        $data = DB::table('rendirpagos')->where('liquidacion_id','=',$request->input('liquidaciondetalle_id'))->get();
        
        $total = DB::table('rendirpagos')->where('liquidacion_id','=',$request->input('liquidaciondetalle_id'))->sum('monto');
        $liqui = LiquidacionDetalle::find($request->input('liquidaciondetalle_id'));
        
        $monto = $liqui->monto;
        $liqui->liquidacion = $total;
        $liqui->neto = $monto - $total;
        $liqui->save();

        return $data;
    }

    public function rendirpagoinfo(Request $request) {
        $id = $request->input('liquidacion_id');
        $data = DB::table('rendirpagos')->where('liquidacion_id','=',$id)->get();
        return $data;
    }
    
    public function cajachicaexportar(Request $request) {

        $liquidacion = Uso::firstWhere('id','=',$request->uso_id);
        
        $liquidaciondetalle = LiquidacionDetalle::firstWhere('uso_id','=',$liquidacion->id);
        
        
        $correo = $request->correo;
        $asunto = $request->asunto;
        
        $user = Auth::user();
        $date = Carbon::now()->format('d-m-Y');
        $aprobador = Aprobador::firstWhere('id','=',$liquidaciondetalle->aprobador_id);

        $tipo = $liquidaciondetalle->servicio;
        if($tipo=='rendirpago'){
            $data = DB::table('rendirpagos')->where('liquidacion_id','=',$liquidaciondetalle->id)->get();
        } else if($tipo=='cajachica') {
            $data = DB::table('cajachicas')->where('liquidacion_id','=',$liquidaciondetalle->id)->get();
        }
        
        $pdf = PDF::loadView('modules.caja.pdfliquidacion', ['correo'=>$correo,'asunto'=>$asunto, 'aprobador'=>$aprobador,'liquidaciondetalle'=>$liquidaciondetalle, 'date'=>$date,'user'=>$user,'data' => $data]); 

        if($request->mail)
        {
            $content = $pdf->download()->getOriginalContent();
            $filename = 'report';
            $exe = '.pdf';
            $unique_name = $filename.time().$exe;
            $ruta = Storage::put('public/caja/'.$user->name.'/'.$unique_name,$content);
            //$ruta = Storage::putFile('photos', new File('/public/caja/'.$user->name.'/'));

            $ruta = public_path('Storage/caja/'.$user->name.'/');
            
            $ruta = $ruta.$unique_name;
            
            $archivo = new Archivo();
            $archivo->user_id = $user->id;
            $archivo->uso_id = $liquidacion->id;
            $archivo->ruta = $ruta;
            $archivo->save();
            $id_archivo = $archivo->id;

            $info = array(
                'aprobador' => $aprobador->name.' '.$aprobador->apellido,
                'nombre' => $user->name,
                'telefono' => $user->telefono,
                'correo' => $user->mail,
                'fecha' => $date,
                'ruta' => $ruta
            );
            
            Mail::send('modules.caja.mail',$info,function($message){
                $message->from('201602035x@gmail.com','Contadorapp');
                $message->to('jorge.hospinal@yahoo.com')->subject('Reporte de caja');
                $message->to(request()->input('correo'))->subject('Reporte de caja');
            });

        } else {

        }
        
        return $pdf->download('medium.pdf');
    }
}
