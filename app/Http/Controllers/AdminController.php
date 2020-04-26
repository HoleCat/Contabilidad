<?php

namespace App\Http\Controllers;

use App\Clase\Modelosgenerales\Sistemacontable;
use App\Clases\Modelosgenerales\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function empresa(Request $request) {

        $empresas = DB::table('empresas')->get();

        $empresa                = new Empresa();
        $empresa->user_id       = Auth::user()->id;
        $empresa->nombre        = $request->input('nombre');
        $empresa->razonsocial   = $request->input('razonsocial');
        $empresa->ruc           = $request->input('ruc');
        $empresa->codigo        = $request->input('codigo');
        $empresa->telefono      = $request->input('telefono');
        $empresa->direccion     = $request->input('direccion');
        $empresa->pagina        = $request->input('pagina');

        $nombre = $empresa->ruc;

        
        $ruta = '';
        $filenametostore = '';
        
        if($request->hasfile('foto')){
            $filenamewithext = $request->file('foto')->getClientOriginalName();
            $filename = pathinfo($filenamewithext, PATHINFO_FILENAME);
            $ext = $request->file('foto')->getClientOriginalExtension();
            $filenametostore = $filename.'_'.time().'.'.$ext;
            $ruta = $request->file('foto')->move('storage/empresas/'.$nombre.'/', $filenametostore);
        } else {
            $ruta = public_path('/assets/img/noimage.png');
        }

        $empresa->foto = $ruta;
        
        $empresa->save();

        return view('herramientas.mantenedores.empresa', ['empresa'=>$empresa,'empresas'=>$empresas]);
    }

    public function sistemacontable(Request $request) {
        $sistemacontable = new Sistemacontable();
        $sistemacontable->codigo = Auth::user()->id;
        $sistemacontable->user_id = $request->input('nombre');
        $sistemacontable->MANDANTE = $request->input('MANDANTE');
        $sistemacontable->INTERFAZ = $request->input('INTERFAZ');
        $sistemacontable->CORRELAT = $request->input('CORRELAT');
        $sistemacontable->NITEM = $request->input('NITEM');
        $sistemacontable->BUKRS = $request->input('BUKRS');
        $sistemacontable->BUPLA = $request->input('BUPLA');
        $sistemacontable->NEWBS = $request->input('NEWBS');
        $sistemacontable->NEWUM = $request->input('NEWUM');
        $sistemacontable->NEWBK = $request->input('NEWBK');
        $sistemacontable->FWBAS = $request->input('FWBAS');
        $sistemacontable->MWSKZ = $request->input('MWSKZ');
        $sistemacontable->GSBER = $request->input('GSBER');
        $sistemacontable->AUFNR = $request->input('AUFNR');
        $sistemacontable->ZTERM = $request->input('ZTERM');
        $sistemacontable->VBUND = $request->input('VBUND');
        $sistemacontable->XREF1 = $request->input('XREF1');
        $sistemacontable->XREF2 = $request->input('XREF2');
        $sistemacontable->XREF3 = $request->input('XREF3');
        $sistemacontable->VALUT = $request->input('VALUT');
        $sistemacontable->XMWST = $request->input('XMWST');
        $sistemacontable->ZLSPR = $request->input('ZLSPR');
        $sistemacontable->ZFBDT = $request->input('ZFBDT');
        $sistemacontable->save();

        return view('herramientas.mantenedores.sistemacontable', ['empresa'=>$sistemacontable]);
    }

}
