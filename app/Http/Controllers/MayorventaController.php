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

    public function import(Request $request)
    {
        $this->validate($request, [
            'myfile' => 'mimes:xls,xlsx'
        ]);

        $user_id = Auth::user()->id;
        $username = Auth::user()->name;
        $uso_id = $request->input('iduso');
        if($request->input('flag') == 0){

            $json_data = session('dataventas');

            $id_archivo = $request->input('idarchivo');
            $impMin = $request->input('importeminimo');
            $impMax = $request->input('importemaximo');
            $comparacion = $request->input('comparacion');
            $tipo = $request->input('tipocomprobante');
            //$reporte = DB::select('call report_xl_compras(?, ?, ?, ?, ?, ?)',[$impMin,$impMax,$comparacion,$tipo,$id_archivo,$uso_id]);
            return response()->json($json_data,200);
        } else {
            if($request->hasfile('myfile')){

                $ruta = Almacenamiento::guardarmuestrasventas($username,$request->file('myfile'));
                $archivo = new Archivo();
                $archivo->user_id = $user_id;
                $archivo->uso_id = $uso_id;
                $archivo->ruta = $ruta;
                $archivo->save();
                $id_archivo = $archivo->id;
    
                Excelmuestreo::aumentarcolumnasdefault($ruta,$uso_id,$id_archivo);
            
                Excel::import(new MayorventasImport, $ruta);
                
                //$data = DB::table('Mayorcompras')->get();
                //report_xl_compras`(_impMin int , _impMax int, _equi int, _tipoDoc varchar(9))
                $impMin = $request->input('importeminimo');
                $impMax = $request->input('importemaximo');
                $comparacion = $request->input('comparacion');
                $tipo = $request->input('tipocomprobante');
                $cantidad = $request->input('cantidad');
                $reporte = DB::select('call report_xl_ventas(?, ?, ?,?, ?, ?, ?)',[$impMin,$impMax,$cantidad,$comparacion,$tipo,$uso_id,$id_archivo]);
                
                session(['dataventas' => $reporte]);

                return response()->json($reporte,200);
            } else {
                $reporte = 'Debe adjuntar un archivo';
                return response()->json($reporte,200);
            }
        }
    }
}
