<?php

namespace App\Http\Controllers;

use App\Clases\Almacenamiento;
use App\Clases\Modelosgenerales\Archivo;
use App\Clases\Reporte\DetraccionCompras;
use App\Clases\Reporte\ReporteCompras;
use App\Clases\Reporte\ValidacionReporteCompras;
use App\Clases\Uso;
use App\Formatos\Excelmuestreo;
use App\Formatos\Txtexportaciones;
use App\Formatos\Validacion;
use App\Imports\DetraccionComprasImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class ReporteComprasController extends Controller
{

    public function index()
    {
        $tipo = 21;
        $tipocompras = 22;
        if(Auth::check()){
            $idusuario = Auth::user()->id;
            $countreportes = Uso::where('idusuario','=',$idusuario)->where('idtipo','=',$tipo)->count();
            if($countreportes>0)
            {
                $uso = DB::table('usos')
                ->where('idusuario','=',$idusuario)
                ->where('idtipo','=',$tipo)
                ->latest()
                ->first();
                $countreportescompras = Uso::where('idusuario','=',$idusuario)->where('uso_id','=',$uso->id)->where('idtipo','=',$tipocompras)->count();
                if($countreportescompras>0)
                {
                    $reportecompras = DB::table('usos')
                    ->where('idusuario','=',$idusuario)
                    ->where('uso_id','=',$uso->id)
                    ->where('idtipo','=',$tipocompras)
                    ->latest()
                    ->first();

                    return view('modules.reporte.reportecompras',['uso'=>$reportecompras]);
                }
                else
                {
                    $reportecompras = new Uso();
                    $reportecompras->idusuario = $idusuario;
                    $reportecompras->uso_id = $uso->id;
                    $reportecompras->idtipo = $tipocompras;
                    $reportecompras->referencia = 'referencia de ejemplo reporte de compras';
                    $reportecompras->save();

                    return view('modules.reporte.reportecompras',['uso'=>$reportecompras]);
                }
            }
            else
            {
                $uso = new Uso();
                $uso->idusuario = $idusuario;
                $uso->uso_id = 0;
                $uso->idtipo = $tipo;
                $uso->referencia = 'referencia de ejemplo reporte';
                $uso->save();

                $reportecompras = new Uso();
                $reportecompras->idusuario = $idusuario;
                $reportecompras->uso_id = $uso->id;
                $reportecompras->idtipo = $tipocompras;
                $reportecompras->referencia = 'referencia de ejemplo reporte de compras';
                $reportecompras->save();

                return view('modules.reporte.reportecompras',['uso'=>$reportecompras]);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Importar(Request $request)
    {
        if($request->hasFile('myfile'))
        {
            $uso_id = $request->iduso;
            $nombre = $request->nombrearchivo;
            $user_id = Auth::user()->id;
            $tipo = 'registro de compras';

            $archivo = new Archivo();
            $archivo->user_id = $user_id;
            $archivo->uso_id = $uso_id;
            $archivo->ruta = $nombre;
            $archivo->tipo = $tipo;
            
            $archivo->save();

            $id_archivo = $archivo->id;
            
            $data = Txtexportaciones::csv_to_array($request->file('myfile'),'|');
            for ($i=0; $i < count($data); $i++) { 
                $row = $data[$i];
                $registro = new ValidacionReporteCompras([
                    'Liberar'=> 'no',
                    'Status'=> 'no',
                    'IdUso'=>$uso_id,
                    'IdArchivo'=>$id_archivo,
                    'Periodo'=> $row[0],
                    'Correlativo'=> $row[1],
                    'Orden'=> $row[2],
                    'FecEmision'=> $row[3],
                    'FecVenci'=> $row[4],
                    'TipoComp'=> $row[5],
                    'NumSerie'=> $row[6],
                    'AnoDua'=> $row[7],
                    'NumComp'=> Validacion::Completarcomprobante($row[8],7),
                    'NumTicket'=> $row[9],
                    'TipoDoc'=> $row[10],
                    'NroDoc'=> $row[11],
                    'Nombre'=> $row[12],
                    'BIAG1'=> $row[13],
                    'IGVIPM1'=> $row[14],
                    'BIAG2'=> $row[15],
                    'IGVIPM2'=> $row[16],
                    'BIAG3'=> $row[17],
                    'IGVIPM3'=> $row[18],
                    'AdqGrava'=> $row[19],
                    'ISC'=> $row[20],
                    'Otros'=> $row[21],
                    'Total'=> $row[22],
                    'Moneda'=> $row[23],
                    'TipoCam'=> $row[24],
                    'FecOrigenMod'=> $row[25],
                    'TipoCompMod'=> $row[26],
                    'NumSerieMod'=> $row[27],
                    'AnoDuaMod'=> $row[28],
                    'NumSerComOriMod'=> $row[29],
                    'FecConstDetrac'=> $row[30],
                    'NumConstDetrac'=> $row[31],
                    'Retencion'=> $row[32],
                    'ClasifBi'=> $row[33],
                    'Contrato'=> $row[34],
                    'ErrorT1'=> $row[35],
                    'ErrorT2'=> $row[36],
                    'ErrorT3'=> $row[37],
                    'ErrorT4'=> $row[38],
                    'MedioPago'=> $row[39],
                    'Estado'=> $row[40]
                ]);
                $registro->save();
            }

            $validacion = DB::table('validacion_reporte_compras')
            ->leftjoin('detraccion_compras', 'detraccion_compras.NumeroComprobante', '=', 'validacion_reporte_compras.Numcomp')
            ->leftjoin('d_t_r_s', 'detraccion_compras.TipoBien', '=', 'd_t_r_s.COD')
            ->select('validacion_reporte_compras.id as IdCool','validacion_reporte_compras.*','detraccion_compras.*','d_t_r_s.*')
            ->where('validacion_reporte_compras.iduso','=',$request->iduso)->get();

            $compras = Archivo::where('uso_id','=',$uso_id)->where('tipo','=','registro de compras')->get();

            $detracciones = Archivo::where('uso_id','=',$uso_id)->where('tipo','=','detraccion')->get();

            return ['detracciones'=>$detracciones,'compras'=>$compras,'validacion'=>$validacion];
        } else {
            return ['error'=>'Debes enviar un archivo'];
        }
    }

    public function ImportarDetraccion(Request $request)
    {
        if($request->hasFile('myfile'))
        {
            if($request->csv && $request->excel)
            {
                return ['error'=>"DEBES SELECCIONAR SOLO UNA OPCION"];
            }
            if($request->csv)
            {
                $uso_id = $request->iduso;
                $nombre = $request->nombrearchivo;
                $user = Auth::user();
                $user_id = $user->id;
                $username = $user->name;
                $useremail = $user->email;

                $archivo = new Archivo();
                $archivo->user_id = $user_id;
                $archivo->uso_id = $uso_id;
                $archivo->ruta = $nombre;
                $archivo->tipo = 'detraccion';
                $archivo->save();

                $id_archivo = $archivo->id;

                $data = Txtexportaciones::csv_to_array($request->file('myfile'),'|');
                for ($i=0; $i < count($data); $i++) { 
                    $row = $data[$i];
                    $registro = new ValidacionReporteCompras([
                        'IdUso'=>$row[0],
                        'IdArchivo'=>$row[1],
                        'Cuo'=>$row[2],
                        'TipoCuenta'=>$row[3],
                        'NumeroCuenta'=>$row[4],
                        'NumeroConstancia'=>$row[5],
                        'PeriodoTributario'=>$row[6],
                        'RucProveedor'=>$row[7],
                        'NombreProveedor'=>$row[8],
                        'TipoDocumentoAdquiriente'=>$row[9],
                        'NumeroDocumentoAdquiriente'=>$row[10],
                        'RazonSocialAdquiriente'=>$row[11],
                        'FechaPago'=>$row[12],
                        'MontoDeposito'=>$row[13],
                        'TipoBien'=>$row[14],
                        'TipoOperacion'=>$row[15],
                        'TipoComprobante'=>$row[16],
                        'SerieComprobante'=>$row[17],
                        'NumeroComprobante'=>Validacion::Completarcomprobante($row[18],7),
                        'NumeroPagoDetraciones'=>$row[19],
                        'ValidacionPorcentual'=>$row[20],
                        'Base'=>$row[21],
                        'ValidacionBase'=>$row[22],
                        'TipoServicio'=>$row[23],
                    ]);
                    $registro->save();
                }
            }

            if($request->excel)
            {
                $uso_id = $request->iduso;
                $nombre = $request->nombrearchivo;
                $user = Auth::user();
                $user_id = $user->id;
                $username = $user->name;
                $useremail = $user->email;

                $archivo = new Archivo();
                $archivo->user_id = $user_id;
                $archivo->uso_id = $uso_id;
                $archivo->ruta = $nombre;
                $archivo->tipo = 'detraccion';
                $archivo->save();

                $id_archivo = $archivo->id;
                
                $ruta = Almacenamiento::guardartemporalmente($username,$request->file('myfile'));
                
                Excelmuestreo::aumentarcolumnasdefault($ruta,$uso_id,$id_archivo);
                
                Excel::import(new DetraccionComprasImport, $ruta);

                //$spreadsheet = IOFactory::load($ruta);

                Storage::deleteDirectory('public/'.$useremail.'/temporal', true);
                // sleep 1 second because of race condition with HD
                sleep(1);
                // actually delete the folder itself
                Storage::deleteDirectory('public/'.$useremail.'/temporal');
            }

            $compras = Archivo::where('uso_id','=',$uso_id)->where('tipo','=','detraccion')->get();
            $detracciones = Archivo::where('uso_id','=',$uso_id)->where('tipo','=','detraccion')->get();

            $dtr = DB::table('detraccion_compras')
            ->leftjoin('d_t_r_s', 'detraccion_compras.TipoBien', '=', 'd_t_r_s.COD')
            ->select('detraccion_compras.*', 'd_t_r_s.*')
            ->where('iduso','=',$request->iduso)->get();

        if(count($dtr)>0)
        {
            return ['detracciones'=>$detracciones,'compras'=>$compras,'dtr'=>$dtr];
        }
        else 
        {
            $dtr = DB::table('detraccion_compras')
            ->leftjoin('d_t_r_s', 'detraccion_compras.TipoBien', '=', 'd_t_r_s.MCOD')
            ->select('detraccion_compras.*', 'd_t_r_s.*')
            ->where('iduso','=',$request->iduso)->get();

            return ['detracciones'=>$detracciones,'compras'=>$compras,'dtr'=>$dtr];
        }      
            
        }
        else 
        {
            return ['error'=>"DEBES ENVIAR UN ARCHIVO"];
        }
    }

    public function Liberar(Request $request)
    {
        $registro = ValidacionReporteCompras::find($request->id);
        return $registro;
        if($registro->Liberar == 'no'){
            $registro = DB::table('validacion_reporte_compras')
              ->where('id', $request->id)
              ->update(['Liberar' => 'si']);
            return $registro;
        }
        else
        {
            $registro = DB::table('validacion_reporte_compras')
              ->where('id', $request->id)
              ->update(['Liberar' => 'no']);
            return $registro;
        }
    }

    public function Status(Request $request)
    {
        $registro = ValidacionReporteCompras::find($request->id);
        if($registro->Status == 'no'){
            $registro = DB::table('validacion_reporte_compras')
              ->where('id', $request->id)
              ->update(['Status' => 'si']);
            return $registro;
        }
        else
        {
            $registro = DB::table('validacion_reporte_compras')
              ->where('id', $request->id)
              ->update(['Status' => 'no']);
            return $registro;
        }
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
     * @param  \App\Clases\Reporte\ReporteCompras  $reporteCompras
     * @return \Illuminate\Http\Response
     */
    public function Data(Request $request)
    {
        $compras = DB::table('archivos')->where('uso_id','=',$request->iduso)->where('tipo','=','registro de compras')->get();
        $detracciones = Archivo::where('uso_id','=',$request->iduso)->where('tipo','=','detraccion')->get();

        $dtr = DB::table('detraccion_compras')
        ->leftjoin('d_t_r_s', 'detraccion_compras.TipoBien', '=', 'd_t_r_s.COD')
        ->select('detraccion_compras.*', 'd_t_r_s.*')
        ->where('iduso','=',$request->iduso)->get();

        if(count($dtr)>0)
        {
            $validacion = DB::table('validacion_reporte_compras')
            ->leftjoin('detraccion_compras', 'detraccion_compras.NumeroComprobante', '=', 'validacion_reporte_compras.Numcomp')
            ->leftjoin('d_t_r_s', 'detraccion_compras.TipoBien', '=', 'd_t_r_s.COD')
            ->select('validacion_reporte_compras.id as IdCool','validacion_reporte_compras.*','detraccion_compras.*','d_t_r_s.*')
            ->where('validacion_reporte_compras.iduso','=',$request->iduso)->get();
            return ['detracciones'=>$detracciones,'compras'=>$compras,'dtr'=>$dtr,'validacion'=>$validacion];
        }
        else 
        {
            $dtr = DB::table('detraccion_compras')
            ->leftjoin('d_t_r_s', 'detraccion_compras.TipoBien', '=', 'd_t_r_s.MCOD')
            ->select('detraccion_compras.*', 'd_t_r_s.*')
            ->where('iduso','=',$request->iduso)->get();

            $validacion = DB::table('validacion_reporte_compras')
            ->leftjoin('detraccion_compras', 'detraccion_compras.NumeroComprobante', '=', 'validacion_reporte_compras.Numcomp')
            ->leftjoin('d_t_r_s', 'detraccion_compras.TipoBien', '=', 'd_t_r_s.COD')
            ->select('validacion_reporte_compras.id as IdCool','validacion_reporte_compras.*','detraccion_compras.*','d_t_r_s.*')
            ->where('validacion_reporte_compras.iduso','=',$request->iduso)->get();
            return ['detracciones'=>$detracciones,'compras'=>$compras,'dtr'=>$dtr,'validacion'=>$validacion];
        }      


        if(count($dtr)<0)
        {
            return ['error'=>'Muerte sangre desastre matenme'];
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Clases\Reporte\ReporteCompras  $reporteCompras
     * @return \Illuminate\Http\Response
     */
    public function edit(ReporteCompras $reporteCompras)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Clases\Reporte\ReporteCompras  $reporteCompras
     * @return \Illuminate\Http\Response
     */
    
    public function Txtconsultaruc(Request $request)
    {
        //1. CONTENIDO
        $user = Auth::user();
        
        $data = DB::table('validacion_reporte_compras')
            ->leftjoin('detraccion_compras', 'detraccion_compras.NumeroComprobante', '=', 'validacion_reporte_compras.Numcomp')
            ->leftjoin('d_t_r_s', 'detraccion_compras.TipoBien', '=', 'd_t_r_s.COD')
            ->select('validacion_reporte_compras.id as IdCool','validacion_reporte_compras.*','detraccion_compras.*','d_t_r_s.*')
            ->where('validacion_reporte_compras.iduso','=',$request->iduso)
            ->get();

        $content = "";
        
        //2. ESPACIO

        $path = public_path('storage\\ZIP\\'.$user->email.'\\temporal\\');

        if(!File::isDirectory($path)){

            File::makeDirectory($path, 0777, true, true);

        } else 
        {
            File::deleteDirectory($path);
            File::makeDirectory($path, 0777, true, true);
        }

        //3. ARCHIVOS
        $guia = 0;
        $guiazip = 0;
        $archivos = [];
        $cantidad = count($data);
        
        foreach ($data as $element) {
            $guia = $guia + 1;
            if($guia%100==0)
            {
                $content = $content.$element->NroDoc."|".$element->TipoComp."|".$element->NumSerie."|".$element->NumComp."|".$element->FecEmision."|".$element->Total."\n";
                array_push($archivos,$content);
                $content = "";
            }
            else if($cantidad==$guia)
            {
                if($guia<100)
                {
                    $content = $content.$element->NroDoc."|".$element->TipoComp."|".$element->NumSerie."|".$element->NumComp."|".$element->FecEmision."|".$element->Total."\n";
                    array_push($archivos,$content);
                    $content = "";
                }
                if($guia>100)
                {
                    $content = $content.$element->NroDoc."|".$element->TipoComp."|".$element->NumSerie."|".$element->NumComp."|".$element->FecEmision."|".$element->Total."\n";
                    array_push($archivos,$content);
                    $content = "";
                }
            }
            else
            {
                $content = $content.$element->NroDoc."|".$element->TipoComp."|".$element->NumSerie."|".$element->NumComp."|".$element->FecEmision."|".$element->Total."\n";
            }
        }
        
        $rutas = [];
        for ($i=0; $i < count($archivos); $i++) { 
            $guiazip = $guiazip + 1;
            $content = $archivos[$i];
            File::makeDirectory($path."zip".$guiazip."\\", 0777, true, true);
            File::put($path."zip".$guiazip."\\zip".$guiazip.".txt", $content);
            $ruta = $path."zip".$guiazip."\\";
            array_push($rutas,$ruta);
        }
        
        //return $rutas;
        //3. ZIPEO
        $pathzip = public_path("storage\\ZIP\\".$user->email."\\zips\\");

        if(!File::isDirectory($pathzip)){

            File::makeDirectory($pathzip, 0777, true, true);

        } else 
        {
            File::deleteDirectory($pathzip);
            File::makeDirectory($pathzip, 0777, true, true);
        }

        $resultado = [];
        
        for ($i=0; $i < count($rutas); $i++) { 
            $pathdir = $rutas[$i];  
            $zipcreated = public_path("storage\\ZIP\\".$user->email."\\zips\\zipruc".$i.".zip"); 
            $ziptoken = Storage::url("ZIP/".$user->email."\\zips\\zipruc".$i.".zip");
            $zip = new ZipArchive; 

            if($zip -> open($zipcreated, ZipArchive::CREATE ) === TRUE) { 
        
                // Store the path into the variable 
                $dir = opendir($pathdir); 
                
                while($file = readdir($dir)) { 
                    if(is_file($pathdir.$file)) { 
                        $zip -> addFile($pathdir.$file, $file); 
                    } 
                } 
                $zip ->close(); 
            }
            $ruta = (object) array("ruta" => $ziptoken);
            array_push($resultado,$ruta);
        }
        return view('herramientas.mantenedores.descargables',['resultados'=>$resultado]);
        //return $resultado;
    }

    public function openzipruc() {
        $user = Auth::user();

        $zipcreated = base_path('ZIP\\'.$user->email.'\\zipruc.zip'); 

        $zip = new ZipArchive; 
        
        $zip->open($zipcreated); 
  
        // Extracts to current directory 
        $zip->extractTo('./'); 
        
        $zip->close();  
    }
    
    public function Txtcomprobantes(Request $request)
    {
        //config
        $user = Auth::user();
        $path = public_path('storage\\ZIP\\'.$user->email.'\\comprobantes\\');
        $data = ValidacionReporteCompras::where('IdUso','=',$request->iduso)->where('Liberar','=','no')->get();
        if(!File::isDirectory($path)){

            File::makeDirectory($path, 0777, true, true);

        } else 
        {
            File::deleteDirectory($path);
            File::makeDirectory($path, 0777, true, true);
        }

        $content = "";
        $guia = 0;
        $guiazip = 0;
        $archivos = [];
        $cantidad = count($data);
        
        foreach ($data as $element) {
            $guia = $guia + 1;
            if($guia%100==0)
            {
                $content = $content.$element->NroDoc."|"."\n";
                array_push($archivos,$content);
                $content = "";
            }
            else if($cantidad==$guia)
            {
                if($guia<100)
                {
                    $content = $content.$element->NroDoc."|"."\n";
                    array_push($archivos,$content);
                    $content = "";
                }
                if($guia>100)
                {
                    $content = $content.$element->NroDoc."|"."\n";
                    array_push($archivos,$content);
                    $content = "";
                }
            }
            else
            {
                $content = $content.$element->NroDoc."|"."\n";
            }
        }

        $resultado = [];
        for ($i=0; $i < count($archivos); $i++) { 
            $guiazip = $guiazip + 1;
            $content = $archivos[$i];
            //File::makeDirectory($path."zip".$guiazip."\\", 0777, true, true);
            File::put($path."\\zip".$guiazip.".txt", $content);
            $filetoken = Storage::url("ZIP/".$user->email."\\comprobantes\\zip".$guiazip.".txt");
            $ruta = (object) array("ruta" => $filetoken);
            array_push($resultado,$ruta);
        }

        return view('herramientas.mantenedores.descargables',['resultados'=>$resultado]);

    }
    
    public function Destroy(ReporteCompras $reporteCompras, Request $request)
    {
        DB::table('validacion_reporte_compras')->delete()->where('iduso','=',$request->iduso);
    }
}