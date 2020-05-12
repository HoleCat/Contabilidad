<?php

namespace App\Http\Controllers;

use App\Clases\Uso;
use App\Formatos\Validacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidacionController extends Controller
{
    public function Importar(Request $request)
    {
        $rules =
        '[{"orden":"0","minimo":"","maximo":"","estatico":"8","tipo":"NUMERICO"},
        {"orden":"1","minimo":"1","maximo":"40","estatico":"","tipo":"ALFABETICO"},
        {"orden":"2","minimo":"2","maximo":"10","estatico":"","tipo":"ALFANUMERICO"},
        {"orden":"3","minimo":"1","maximo":"24","estatico":"","tipo":"NUMERICO"},
        {"orden":"4","minimo":"1","maximo":"24","estatico":"","tipo":"ALFANUMERICO"},
        {"orden":"5","minimo":"1","maximo":"24","estatico":"","tipo":"ALFANUMERICO"},
        {"orden":"6","minimo":"","maximo":"","estatico":"3","tipo":"ALFANUMERICO"},
        {"orden":"7","minimo":"","maximo":"","estatico":"1","tipo":"ALFANUMERICO"},
        {"orden":"8","minimo":"1","maximo":"15","estatico":"","tipo":"ALFANUMERICO"},
        {"orden":"9","minimo":"","maximo":"","estatico":"2","tipo":"NUMERICO"},
        {"orden":"10","minimo":"1","maximo":"20","estatico":"","tipo":"ALFANUMERICO"},
        {"orden":"11","minimo":"1","maximo":"20","estatico":"","tipo":"ALFANUMERICO"},
        {"orden":"12","minimo":"","maximo":"","estatico":"10","tipo":"FECHA"},
        {"orden":"13","minimo":"","maximo":"","estatico":"10","tipo":"FECHA"},
        {"orden":"14","minimo":"","maximo":"","estatico":"10","tipo":"FECHA"},
        {"orden":"15","minimo":"0","maximo":"200","estatico":"","tipo":"ALFABETICO"},
        {"orden":"16","minimo":"0","maximo":"200","estatico":"","tipo":"ALFABETICO"},
        {"orden":"17","minimo":"","maximo":"","estatico":"15","tipo":"NUMERICO"},
        {"orden":"18","minimo":"","maximo":"","estatico":"15","tipo":"NUMERICO"},
        {"orden":"19","minimo":"","maximo":"","estatico":"92","tipo":"ALFABETICO"},
        {"orden":"20","minimo":"","maximo":"","estatico":"1","tipo":"NUMERICO"}]';

        //$rules2 = '[{"name":"Jonathan Suh","gender":"male"},{"name":"William Philbin","gender":"male"},{"name":"Allison McKinnery","gender":"female"}]';
        //$rules2 = json_decode($rules2);
        //return $rules2[0]->name; 
        $data = Validacion::importar($request->file('myfile'),'|',$rules);
       
        session(['datavalidada' => $data]);

        return $data;
        //return $data;
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
