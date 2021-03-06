<?php

namespace App\Http\Controllers;

use App\Mayorcompra;
use App\Imports\MayorcomprasImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Formatos\Excelmuestreo;
use App\Clases\Almacenamiento;
use App\Clases\Modelosgenerales\Archivo;
use App\Clases\Uso;
use App\Mayorgasto;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MayorcompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Index()
    {
        if(Auth::check()){
            $tipo = 9;
            $tiposubuso = 10;
            $uso_id = 0;
            $idusuario = Auth::user()->id;
            $contadorusomuestreo = DB::table('usos')->where('idusuario','=',$idusuario)->where('idtipo','=',$tipo)->count();
            $contadorarchivos = DB::table('archivos')->where('user_id','=',$idusuario)->count();
            $comprobantes = DB::table('comprobantes')->orderBy('codigo','asc')->get();
            if($contadorusomuestreo > 0)
            {
                $uso = DB::table('usos')
                ->where('idusuario','=',$idusuario)
                ->where('idtipo','=',$tipo)
                ->latest()
                ->first();
    
                $uso_id = $uso->id;
    
                $contadorusocompras = DB::table('usos')->where('uso_id','=',$uso_id)->where('idusuario','=',$idusuario)->where('idtipo','=',$tiposubuso)->count();
    
                if($contadorusocompras>0)
                {
                    $usocompras = DB::table('usos')
                    ->where('idusuario','=',$idusuario)
                    ->where('uso_id','=',$uso_id)
                    ->where('idtipo','=',$tiposubuso)
                    ->latest()
                    ->first();
                    $archivos = DB::table('archivos')->where('uso_id','=',$usocompras->id)->get();
                    return view('modules.muestreo.compras',['archivos'=>$archivos,'uso' => $usocompras,'comprobantes' => $comprobantes]);
                } else {
    
                    $usocompras = new Uso([
                        'idusuario' => $idusuario,
                        'uso_id' => $uso_id,
                        'referencia' => 'Ejemplo de referencia compras',
                        'idtipo' => $tiposubuso,
                    ]);
                    $usocompras->save();
                    $archivos = DB::table('archivos')->where('uso_id','=',$usocompras->id)->get();
                    return view('modules.muestreo.compras',['archivos'=>$archivos,'uso' => $usocompras,'comprobantes' => $comprobantes]);
                }
            } else {
                $uso = new Uso();
                $uso->idusuario = $idusuario;
                $uso->uso_id = 0;
                $uso->referencia = 'Ejemplo de referencia';
                $uso->idtipo = $tipo;
                $uso->save();
    
                $usocompras = new Uso([
                    'idusuario' => $idusuario,
                    'uso_id' => $uso->id,
                    'referencia' => 'Ejemplo de referencia compras sin uso general',
                    'idtipo' => $tiposubuso,
                ]);
                $usocompras->save();
                $archivos = DB::table('archivos')->where('uso_id','=',$usocompras->id)->get();
                return view('modules.muestreo.compras',['archivos'=>$archivos,'uso' => $usocompras,'comprobantes' => $comprobantes]);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Mayorcompra  $mayorcompra
     * @return \Illuminate\Http\Response
     */
    public function show(Mayorcompra $mayorcompra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Mayorcompra  $mayorcompra
     * @return \Illuminate\Http\Response
     */
    public function edit(Mayorcompra $mayorcompra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Mayorcompra  $mayorcompra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mayorcompra $mayorcompra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Mayorcompra  $mayorcompra
     * @return \Illuminate\Http\Response
     */
    public function Destroy(Request $request)
    {
        $id = $request->id_archivo;
        DB::table('mayorcompras')->where('IdArchivo','=',$id)->delete();
        DB::table('archivos')->where('id','=',$id)->delete();

        $iduso = $request->iduso;
        $archivos = DB::table('archivos')->where('id','=',$iduso)->get();
        
        return $archivos;
    }

    public function exportar(Request $request) 
    {
        $json_data = session('datacompras');

        $cell_order_compras = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO");
        
        $columnas = ['Periodo','Correlativo','Orden',
        'FecEmision','FecVenci','TipoComp','NumSerie',
        'AnoDua','NumComp','NumTicket','TipoDoc','NroDoc','cliente',
        'BIAG1','IGVIPM1','BIAG2','IGVIPM2','BIAG3','IGVIPM3',
        'AdqGrava','ISC','Otros','Total','Moneda','TipoCam',
        'FecOrigenMod','TipoCompMod','NumSerieMod',
        'AnoDuaMod','NumSerComOriMod','FecConstDetrac',
        'NumConstDetrac','Retencion','ClasifBi','Contrato',
        'ErrorT1','ErrorT2','ErrorT3','ErrorT4','MedioPago',
        'Estado'];

        $user_id = Auth::user()->id;
        $username = Auth::user()->name;
        
        $ruta = public_path('/assets/files/templatemayorcompra.xlsx');

        //$array_data = json_decode($json_data, true);
        $array_data = $json_data;
        $spreadsheet = IOFactory::load($ruta);
    
        $cont_1 = 6;

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', $request->empresa);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', $request->ruc);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B3', $request->periodo);
    
        for ($f = 0; $f < count($array_data); $f++) {
            $cont_2 = 0;
            $item = $array_data[$f];
            $data = json_decode($item->data);
            for ($i=0; $i < count($columnas); $i++) { 
                $cell_id = $cell_order_compras[$cont_2].$cont_1;
                $cell_value = $data->{$columnas[$i]};
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cell_id, $cell_value);
                $cont_2++;
            }
            //foreach ($item['data'] as $cell_value) {
            //    $cell_id = $cell_order_compras[$cont_2].$cont_1;
            //    $spreadsheet->setActiveSheetIndex(0)->setCellValue($cell_id, $cell_value);
            //    $cont_2++;
            //}
            $cont_1++;
        }

        $spreadsheet->getActiveSheet()->setTitle('Hoja 1');
    
        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reportecompras.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function importar(Request $request)
    {
        $this->validate($request, [
            'myfile' => 'mimes:xls,xlsx'
        ]);

        $user_id = Auth::user()->id;
        $username = Auth::user()->name;
        $useremail = Auth::user()->email;
        $uso_id = $request->input('iduso');
        
        $nombre = $request->nombrearchivo;

        if($request->hasfile('myfile')){
            $ruta = Almacenamiento::guardartemporalmente($username,$request->file('myfile'));
            $archivo = new Archivo();
            $archivo->user_id = $user_id;
            $archivo->uso_id = $uso_id;
            $archivo->ruta = $nombre;
            $archivo->save();
            $id_archivo = $archivo->id;

            Excelmuestreo::aumentarcolumnasdefault($ruta,$uso_id,$id_archivo);
        
            Excel::import(new MayorcomprasImport, $ruta);

            Storage::deleteDirectory('public/'.$useremail.'/temporal', true);
            // sleep 1 second because of race condition with HD
            sleep(1);
            // actually delete the folder itself
            Storage::deleteDirectory('public/'.$useremail.'/temporal');  
            //return response()->json($reporte,200);
            return $archivo;
        }    
    }

    public function filtrar(Request $request)
    {
        $uso_id = $request->input('iduso');
        $id_archivo = $request->input('id_archivo');
        $impMin = $request->input('importeminimo');
        $impMax = $request->input('importemaximo');
        $comparacion = $request->input('comparacion');
        $tipo = $request->input('tipocomprobante');
        $cantidad = $request->input('cantidad');
        
        $reporte = DB::select('call report_xl_compras(?, ?, ?, ?, ?, ?, ?)',[$impMin,$impMax,$cantidad,$comparacion,$tipo,$uso_id,$id_archivo]);
        
        session(['datacompras' => $reporte]);

        return response()->json($reporte,200);                
    }

}
