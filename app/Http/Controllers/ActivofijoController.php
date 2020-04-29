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
use Illuminate\Support\Facades\Storage;
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
        
            Excel::import(new ActivofijosImport, $ruta);

            Storage::deleteDirectory('public/'.$useremail.'/temporal', true);
            // sleep 1 second because of race condition with HD
            sleep(1);
            // actually delete the folder itself
            Storage::deleteDirectory('public/'.$useremail.'/temporal');  
            
            return $archivo;
        }    
    }

    public function filtrar(Request $request)
    {
        $uso_id = $request->input('iduso');
        $id_archivo = $request->input('id_archivo');
        $vchk = $request->input('check');
        $vcant = $request->input('cantidad');
        $vdateEnd = $request->input('fechafin');
        
        $reporte = DB::select('call calculos_activofijo(?, ?, ?)',[$vchk,$vcant,$vdateEnd]);
        
        session(['dataactivos' => $reporte]);

        return $reporte; 
    }
    
}
