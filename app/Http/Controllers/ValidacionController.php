<?php

namespace App\Http\Controllers;

use App\Clases\Uso;
use App\Formatos\Validacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidacionController extends Controller
{
    public function Validacion(Request $request)
    {
        $data = Validacion::importar($request->file('myfile'),'|');
        return $data;
    }

    public function Index()
    {
        $tipo = 20;
        
        if(Auth::check()){
            $user = Auth::user();
            $count = Uso::where('idtipo','=',$tipo)->count();
            if($count>0){
                $uso = Uso::where('idusuario','=',$user->id)
                ->where('idtipo','=',$tipo)
                ->latest()
                ->first();
                return view('modules.validador.validador',['uso'=>$uso]);
            } else {
                $uso = new Uso([
                    'idusuario' => $user->id,
                    'uso_id' => 0,
                    'referencia' => 'Ejemplo de referencia validador',
                    'idtipo' => $tipo,
                ]);
                $uso->save();
                return view('modules.validador.validador',['uso'=>$uso]);
            }
        }
    }

    public function Nuevo()
    {
        $tipo = 20;
        if(Auth::check())
        {
            $user = Auth::user();
            $uso = new Uso([
                'idusuario' => $user->id,
                'uso_id' => 0,
                'referencia' => 'Ejemplo de referencia validador',
                'idtipo' => $tipo,
            ]);
            $uso->save();
            return view('modules.validador.validador',['uso'=>$uso]);
        }
    }

}
