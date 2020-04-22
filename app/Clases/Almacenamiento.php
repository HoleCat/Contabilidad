<?php

namespace App\Clases;

use Illuminate\Database\Eloquent\Model;

class Almacenamiento extends Model
{
    static function guardarmuestrascompras($username,$file) {
        $filenamewithext = $file->getClientOriginalName();
        $filename = pathinfo($filenamewithext, PATHINFO_FILENAME);
        $ext = $file->getClientOriginalExtension();
        $filenametostore = $filename.'_'.time().'.'.$ext;
        $ruta = $file->move('storage/muestreo/compras/'.$username.'/'.time().'_'.$filenametostore.'/archivo/', $filenametostore);
        return $ruta;
    }

    static function guardarmuestrasventas($username,$file) {
        $filenamewithext = $file->getClientOriginalName();
        $filename = pathinfo($filenamewithext, PATHINFO_FILENAME);
        $ext = $file->getClientOriginalExtension();
        $filenametostore = $filename.'_'.time().'.'.$ext;
        $ruta = $file->move('storage/muestreo/ventas/'.$username.'/'.time().'_'.$filenametostore.'/archivo/', $filenametostore);
        return $ruta;
    }

    static function guardaractivos($username,$file) {
        $filenamewithext = $file->getClientOriginalName();
        $filename = pathinfo($filenamewithext, PATHINFO_FILENAME);
        $ext = $file->getClientOriginalExtension();
        $filenametostore = $filename.'_'.time().'.'.$ext;
        $ruta = $file->move('storage/activos/'.$username.'/'.time().'_'.$filenametostore.'/archivo/', $filenametostore);
        return $ruta;
    }
}
