<?php

namespace App\Http\Controllers;

use App\Clases\Activos\Activofijo;
use App\Clases\Almacenamiento;
use App\Clases\Modelosgenerales\Archivo;
use App\Formatos\Excelmuestreo;
use App\Imports\ActivofijosImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ActivofijoController extends Controller
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
     * @param  \App\Activofijo  $activofijo
     * @return \Illuminate\Http\Response
     */
    public function show(Activofijo $activofijo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Activofijo  $activofijo
     * @return \Illuminate\Http\Response
     */
    public function edit(Activofijo $activofijo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Activofijo  $activofijo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activofijo $activofijo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Activofijo  $activofijo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activofijo $activofijo)
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

            $json_data = session('dataactivos');

            $id_archivo = $request->input('idarchivo');
            //$impMin = $request->input('importeminimo');
            $impMax = $request->input('importemaximo');
            $comparacion = $request->input('comparacion');
            $tipo = $request->input('tipocomprobante');
            //$reporte = DB::select('call report_xl_compras(?, ?, ?, ?, ?, ?)',[$impMin,$impMax,$comparacion,$tipo,$id_archivo,$uso_id]);
            return response()->json($json_data,200);
        } else {
            if($request->hasfile('myfile')){

                $ruta = Almacenamiento::guardaractivos($username,$request->file('myfile'));
                $archivo = new Archivo();
                $archivo->user_id = $user_id;
                $archivo->uso_id = $uso_id;
                $archivo->ruta = $ruta;
                $archivo->save();
                $id_archivo = $archivo->id;
    
                Excelmuestreo::aumentarcolumnasdefault($ruta,$uso_id,$id_archivo);
            
                Excel::import(new ActivofijosImport, $ruta);
                
                $vchk = $request->input('check');
                //report_xl_compras`(_impMin int , _impMax int, _equi int, _tipoDoc varchar(9))
                $vcant = $request->input('cantidad');
                $vdateIn = $request->input('fechainicial');
                $vdateEnd = $request->input('fechafin');
                
                $reporte = DB::select('call calculos_activofijo(?, ?, ?, ?)',[$vchk,$vcant,$vdateIn,$vdateEnd]);
                
                session(['dataactivos' => $reporte]);

                return response()->json($reporte,200);

            } else {

                $reporte = 'Debe adjuntar un archivo';
                return response()->json($reporte,200);
                
            }
        }
    }
}
