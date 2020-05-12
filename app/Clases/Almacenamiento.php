<?php

namespace App\Clases;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Almacenamiento extends Model
{
    static function guardartemporalmente($username,$file) {
        $useremail = Auth::user()->email;
        $filenamewithext = $file->getClientOriginalName();
        $filename = pathinfo($filenamewithext, PATHINFO_FILENAME);
        $ext = $file->getClientOriginalExtension();
        $filenametostore = $filename.'_'.time().'.'.$ext;
        $ruta = $file->move('storage/'.$useremail.'/'.'temporal/', $filenametostore);
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
