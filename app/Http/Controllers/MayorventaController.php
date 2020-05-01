<?php

namespace App\Http\Controllers;

use App\Clases\Almacenamiento;
use App\Clases\Modelosgenerales\Archivo;
use App\Formatos\Excelmuestreo;
use App\Imports\MayorventasImport;
use App\Mayorventa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MayorventaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Mayorventa  $mayorventa
     * @return \Illuminate\Http\Response
     */
    public function show(Mayorventa $mayorventa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Mayorventa  $mayorventa
     * @return \Illuminate\Http\Response
     */
    public function edit(Mayorventa $mayorventa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Mayorventa  $mayorventa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mayorventa $mayorventa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Mayorventa  $mayorventa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mayorventa $mayorventa)
    {
        //
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
        
        if($request->hasfile('myfile')){
            $ruta = Almacenamiento::guardarmuestrascompras($username,$request->file('myfile'));
            $archivo = new Archivo();
            $archivo->user_id = $user_id;
            $archivo->uso_id = $uso_id;
            $archivo->ruta = $ruta;
            $archivo->save();
            $id_archivo = $archivo->id;
            
            Excelmuestreo::aumentarcolumnasdefault($ruta,$uso_id,$id_archivo);
        
            Excel::import(new MayorventasImport, $ruta);

            Storage::deleteDirectory('public/'.$useremail.'/temporal', true);
            // sleep 1 second because of race condition with HD
            sleep(1);
            // actually delete the folder itself
            Storage::deleteDirectory('public/'.$useremail.'/temporal');  
            //return response()->json($reporte,200);
            return $archivo;
        }  
    }

    public function filtrar(Request $request) {
        $uso_id = $request->input('iduso');
        $id_archivo = $request->input('id_archivo');
        $impMin = $request->input('importeminimo');
        $impMax = $request->input('importemaximo');
        $comparacion = $request->input('comparacion');
        $tipo = $request->input('tipocomprobante');
        $cantidad = $request->input('cantidad');
        return [$impMin,$impMax,$cantidad,$comparacion,$tipo,$uso_id,$id_archivo];
        $reporte = DB::select('call report_xl_ventas(?, ?, ?,?, ?, ?, ?)',[$impMin,$impMax,$cantidad,$comparacion,$tipo,$uso_id,$id_archivo]);
        
        session(['dataventas' => $reporte]);

        return response()->json($reporte,200);
    }
}
