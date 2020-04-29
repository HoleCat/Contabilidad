<?php

namespace App\Http\Controllers;

use App\Clase\Modelosgenerales\Sistemacontable;
use App\Clases\Almacenamiento;
use App\Clases\Caja\Aprobador;
use App\Clases\Caja\Cajachica;
use App\Clases\Caja\LiquidacionDetalle;
use App\Clases\Modelosgenerales\Archivo;
use App\clases\modelosgenerales\Codigocontable;
use App\Clases\Modelosgenerales\Comprobante;
use App\clases\modelosgenerales\Dni;
use App\Clases\Modelosgenerales\Moneda;
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
        $sistemas = DB::table('sistemacontables')->get();
        if($request->input('servicio') == "cajachica")
        {
            return view('modules.caja.cajachica',['sistemas'=>$sistemas,'centrocostos'=>$centrocostos,'monedas'=>$monedas,'uso'=>$uso,'liquidacion'=>$liquidacion,'documentos'=>$tiposdocumento,'codigocontable'=>$codigocontable]);
        }
        else {
            return view('modules.caja.rendirpago',['sistemas'=>$sistemas,'centrocostos'=>$centrocostos,'monedas'=>$monedas,'uso'=>$uso,'liquidacion'=>$liquidacion,'documentos'=>$tiposdocumento,'codigocontable'=>$codigocontable]);
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
        $sistemas = DB::table('sistemacontables')->get();
        return view('modules.caja.cajachica',['sistemas'=>$sistemas,'centrocostos'=>$centrocostos,'monedas'=>$monedas,'uso'=>$uso,'liquidacion'=>$liquidacion,'documentos'=>$tiposdocumento,'codigocontable'=>$codigocontable]);
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
        $sistemas = DB::table('sistemacontables')->get();
        return view('modules.caja.rendirpago',['sistemas'=>$sistemas,'centrocostos'=>$centrocostos,'monedas'=>$monedas,'uso'=>$uso,'liquidacion'=>$liquidacion,'documentos'=>$tiposdocumento,'codigocontable'=>$codigocontable]);
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
        $numeracion = $request->codigo;

        $tipo = $liquidaciondetalle->servicio;
        if($tipo=='rendirpago'){
            $data = DB::table('rendirpagos')->where('liquidacion_id','=',$liquidaciondetalle->id)->get();
        } else if($tipo=='cajachica') {
            $data = DB::table('cajachicas')->where('liquidacion_id','=',$liquidaciondetalle->id)->get();
        }
        
        $pdf = PDF::loadView('modules.caja.pdfliquidacion', ['numeracion'=>$numeracion,'correo'=>$correo,'asunto'=>$asunto, 'aprobador'=>$aprobador,'liquidaciondetalle'=>$liquidaciondetalle, 'date'=>$date,'user'=>$user,'data' => $data]); 

        $template_path = public_path('/assets/files/cajatemplate.xlsx');
        $spreadsheet = IOFactory::load($template_path);

        $cont_1 = 2;


        $sistema = Sistemacontable::firstWhere('id','=',$request->sistema);

        foreach ($data as $reg) {
            $comprobante = Comprobante::firstWhere('id','=',$reg->tipodocumento);
            $moneda = Moneda::firstWhere('id','=',$reg->moneda);
            $contabilidad = Codigocontable::firstWhere('id','=',$reg->contabilidad);
            $i = 9;
                $cellA = 'A'.$i;
                $cellB = 'B'.$i;
                $cellC = 'C'.$i;
                $cellD = 'D'.$i;
                $cellE = 'E'.$i;
                $cellF = 'G'.$i;
                $cellG = 'G'.$i;
                $cellH = 'H'.$i;
                $cellI = 'I'.$i;
                $cellJ = 'J'.$i;
                $cellK = 'K'.$i;
                $cellL = 'L'.$i;
                $cellM = 'M'.$i;
                $cellN = 'N'.$i;
                $cellO = 'O'.$i;
                $cellP = 'P'.$i;
                $cellQ = 'Q'.$i;
                $cellR = 'R'.$i;
                $cellS = 'S'.$i;
                $cellT = 'T'.$i;
                $cellU = 'U'.$i;
                $cellV = 'V'.$i;
                $cellW = 'W'.$i;
                $cellX = 'X'.$i;
                $cellY = 'Y'.$i;
                $cellZ = 'Z'.$i;
                $cellAA = 'AA'.$i;
                $cellAB = 'AB'.$i;
                $cellAC = 'AC'.$i;
                $cellAD = 'AD'.$i;
                $cellAE = 'AE'.$i;
                $cellAG = 'AG'.$i;
                $cellAF = 'AF'.$i;
                $cellAH = 'AH'.$i;
                $cellAI = 'AI'.$i;
                $cellAJ = 'AJ'.$i;
                $cellAK = 'AK'.$i;
                $cellAL = 'AL'.$i;
                $cellAM = 'AM'.$i;
                $cellAN = 'AN'.$i;
                $cellAO = 'AO'.$i;
                
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellA, $sistema->MANDANTE);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellB, $sistema->INTERFAZ);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellC, $reg->fecha);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellD, $sistema->CORRELAT);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellE, $sistema->NITEM);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellF, $sistema->BUKRS);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellG, $comprobante->tipodocumento);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellH, $moneda->descripcion);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellI, $reg->fecha);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellJ, $reg->fecha);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellK, $reg->fecha);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellL, $reg->ruc);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellM, $liquidaciondetalle->concepto);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellN, $sistema->BUPLA);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellO, $sistema->NEWBS_ORIGEN);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellP, $reg->ruc);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellQ, $sistema->NEWUM);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellR, $sistema->NEWBK);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellS, $reg->monto*10);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellT, $sistema->FWBAS);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellU, $sistema->MWSKZ);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellV, $sistema->GSBER);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellW, $sistema->KOSTL);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellX, $sistema->AUFNR);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellY, $sistema->ZTERM);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellZ, $liquidaciondetalle->concepto);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellAA, $liquidaciondetalle->concepto);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellAB, $sistema->VBUND);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellAC, $sistema->XREF1);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellAD, $sistema->XREF2);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellAE, $sistema->XREF3);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellAF, $sistema->VALUT);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellAG, $sistema->XMWST);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellAH, $sistema->ZLSPR);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellAI, $sistema->ZFBDT);
            $i++;

            $cellA = 'A'.$i;
                $cellB = 'B'.$i;
                $cellC = 'C'.$i;
                $cellD = 'D'.$i;
                $cellE = 'E'.$i;
                $cellF = 'G'.$i;
                $cellG = 'G'.$i;
                $cellH = 'H'.$i;
                $cellI = 'I'.$i;
                $cellJ = 'J'.$i;
                $cellK = 'K'.$i;
                $cellL = 'L'.$i;
                $cellM = 'M'.$i;
                $cellN = 'N'.$i;
                $cellO = 'O'.$i;
                $cellP = 'P'.$i;
                $cellQ = 'Q'.$i;
                $cellR = 'R'.$i;
                $cellS = 'S'.$i;
                $cellT = 'T'.$i;
                $cellU = 'U'.$i;
                $cellV = 'V'.$i;
                $cellW = 'W'.$i;
                $cellX = 'X'.$i;
                $cellY = 'Y'.$i;
                $cellZ = 'Z'.$i;
                $cellAA = 'AA'.$i;
                $cellAB = 'AB'.$i;
                $cellAC = 'AC'.$i;
                $cellAD = 'AD'.$i;
                $cellAE = 'AE'.$i;
                $cellAG = 'AG'.$i;
                $cellAF = 'AF'.$i;
                $cellAH = 'AH'.$i;
                $cellAI = 'AI'.$i;
                $cellAJ = 'AJ'.$i;
                $cellAK = 'AK'.$i;
                $cellAL = 'AL'.$i;
                $cellAM = 'AM'.$i;
                $cellAN = 'AN'.$i;
                $cellAO = 'AO'.$i;

                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellA, $sistema->MANDANTE);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellB, $sistema->INTERFAZ);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellC, $reg->fecha);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellD, $sistema->CORRELAT);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellE, $sistema->NITEM);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellF, $sistema->BUKRS);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellG, $comprobante->tipodocumento);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellH, $moneda->descripcion);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellI, $reg->fecha);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellJ, $reg->fecha);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellK, $reg->fecha);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellL, $reg->ruc);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellM, $liquidaciondetalle->concepto);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellN, $sistema->BUPLA);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellO, $sistema->NEWBS_PROV);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellP, $contabilidad->codigo);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellQ, $sistema->NEWUM);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellR, $sistema->NEWBK);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellS, $reg->monto*10);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellT, $sistema->FWBAS);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellU, $sistema->MWSKZ);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellV, $sistema->GSBER);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellW, $sistema->KOSTL);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellX, $sistema->AUFNR);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellY, $sistema->ZTERM);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellZ, $liquidaciondetalle->concepto);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellAA, $liquidaciondetalle->concepto);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellAB, $sistema->VBUND);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellAC, $sistema->XREF1);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellAD, $sistema->XREF2);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellAE, $sistema->XREF3);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellAF, $sistema->VALUT);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellAG, $sistema->XMWST);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellAH, $sistema->ZLSPR);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellAI, $sistema->ZFBDT);
            $i++;
        }
        
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
           
            //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            //header('Content-Disposition: attachment;filename="REPORTE.xlsx"');
            //header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            //header('Cache-Control: max-age=1');
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            ob_start();
            $writer->save('php://output');
            $content = ob_get_contents();
            ob_end_clean();
            $filename = 'report';
            $exe = '.xlsx';
            $unique_name = $filename.time().$exe;
            $ruta2 = Storage::put('public/caja/'.$user->name.'/'.$unique_name,$content);

            $ruta2 = public_path('Storage/caja/'.$user->name.'/');
            
            $ruta2 = $ruta2.$unique_name;

            $info = array(
                'aprobador' => $aprobador->name.' '.$aprobador->apellido,
                'nombre' => $user->name,
                'telefono' => $user->telefono,
                'correo' => $user->mail,
                'fecha' => $date,
                'ruta' => $ruta,
                'ruta2' => $ruta2,
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
