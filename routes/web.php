<?php
use App\Clases\Caja\Aprobador;
use App\Clases\Caja\LiquidacionDetalle;
use App\clases\modelosgenerales\Centrocosto;
use App\clases\modelosgenerales\Codigocontable;
use App\clases\modelosgenerales\Dni;
use App\Clases\Modelosgenerales\Moneda;
use App\clases\modelosgenerales\Pais;
use App\Clases\Modelosgenerales\Tipouso;
use App\Clases\Uso;
use App\Userdata;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/Admin', function () {
    return view('admin');
});
Route::match(['get', 'post'], '/Admin/Empresa', 'AdminController@empresa');
Route::match(['get', 'post'], '/Admin/Sistemacontable', 'AdminController@sistemacontable');

Auth::routes();

Route::match(['get', 'post'],'/Seguimiento', function () {
    
    $user = Auth::user();
    $userdatacount = Userdata::where('user_id','=',$user->id)->count();
    if($userdatacount>0)
    {
        $userdata = Userdata::firstWhere('user_id','=',$user->id);
        return view('layouts.seguimiento',['user'=>$user,'userdata'=>$userdata]);
    } else {
        $userdata = new Userdata();
        $userdata->user_id = Auth::user()->id;
        $userdata->save();
        return view('layouts.seguimiento',['user'=>$user,'userdata'=>$userdata]);
    }
});
Route::match(['get', 'post'],'/Seguimiento/Data', 'SeguimientoController@data');

Route::match(['get', 'post'],'/Userdata', function () {

    $user = Auth::user();
    $userdatacount = Userdata::where('user_id','=',$user->id)->count();
    if($userdatacount>0)
    {
        $userdata = Userdata::firstWhere('user_id','=',$user->id);
        return view('layouts.userdata',['user'=>$user,'userdata'=>$userdata]);
    } else {
        $userdata = new Userdata();
        $userdata->user_id = Auth::user()->id;
        $userdata->save();
        return view('layouts.userdata',['user'=>$user,'userdata'=>$userdata]);
    }
});
Route::match(['get', 'post'],'/Userdata/Perfil', 'UserdataController@perfil');
Route::match(['get', 'post'],'/Userdata/Empresa', 'UserdataController@empresa');

Route::post('/Userdata/Data', 'UserdataController@index' );
Route::post('/Userdata/Editar', 'UserdataController@store' );

Route::get('/home', 'HomeController@index')->name('home');

Route::match(['get', 'post'],'/Caja', function () {
    if(Auth::check()){
        $tipo = 13;
        $tiposubuso = 14;
        $uso_id = 0;
        $idusuario = Auth::user()->id;
        $contadorusocaja = DB::table('usos')->where('idusuario','=',$idusuario)->where('idtipo','=',$tipo)->count();
        $contadorarchivos = DB::table('archivos')->where('user_id','=',$idusuario)->count();
        
        $aprobadores = DB::table('aprobadors')->where('user_id','=',Auth::user()->id)->get();
        
        if($contadorusocaja > 0)
        {
            $uso = DB::table('usos')
            ->where('idusuario','=',$idusuario)
            ->where('idtipo','=',$tipo)
            ->latest()
            ->first();

            $uso_id = $uso->id;

            $contadorusoliquidacion = DB::table('usos')->where('uso_id','=',$uso_id)->where('idusuario','=',$idusuario)->where('idtipo','=',$tiposubuso)->count();

            if($contadorusoliquidacion>0)
            {   
                $usoliquidacion = DB::table('usos')
                ->where('idusuario','=',$idusuario)
                ->where('uso_id','=',$uso_id)
                ->where('idtipo','=',$tiposubuso)
                ->latest()
                ->first();

                $contadorusoliquidaciondetalle = DB::table('liquidacion_detalles')->where('uso_id','=',$usoliquidacion->id)->count();
                if($contadorusoliquidaciondetalle > 0) {
                    $liquidacion = LiquidacionDetalle::where('uso_id', $usoliquidacion->id)->first();
                    
                    $tiposdocumento = DB::table('comprobantes')->get();
                    $codigocontable = DB::table('codigocontables')->get();
                    $monedas = DB::table('monedas')->get();
                    $centrocostos = DB::table('centrocostos')->get();
                    
                    if($liquidacion->servicio == 'cajachica'){
                        return view('modules.caja.cajachica',['centrocostos'=>$centrocostos,'monedas'=>$monedas,'uso'=>$usoliquidacion,'liquidacion'=>$liquidacion,'documentos'=>$tiposdocumento,'codigocontable'=>$codigocontable]);
                    }
                    if($liquidacion->servicio == 'rendirpago'){
                        return view('modules.caja.rendirpago',['centrocostos'=>$centrocostos,'monedas'=>$monedas,'uso'=>$usoliquidacion,'liquidacion'=>$liquidacion,'documentos'=>$tiposdocumento,'codigocontable'=>$codigocontable]);
                    }
                    
                    
                } else {
                    return view('modules.caja.liquidacion',['uso' => $usoliquidacion,'aprobadores' => $aprobadores]);
                }
                
            } else {

                $usoliquidacion = new Uso([
                    'idusuario' => $idusuario,
                    'uso_id' => $uso_id,
                    'referencia' => 'Ejemplo de referencia compras',
                    'idtipo' => $tiposubuso,
                ]);
                $usoliquidacion->save();

                return view('modules.caja.liquidacion',['uso' => $usoliquidacion,'aprobadores' => $aprobadores]);
            }
            
        } else {
            $uso = new Uso();
            $uso->idusuario = $idusuario;
            $uso->uso_id = 0;
            $uso->referencia = 'Ejemplo de referencia';
            $uso->idtipo = $tipo;
            $uso->save();

            $usoliquidacion = new Uso([
                'idusuario' => $idusuario,
                'uso_id' => $uso->id,
                'referencia' => 'Ejemplo de referencia liquidacion sin uso general',
                'idtipo' => $tiposubuso,
            ]);
            $usoliquidacion->save();

            return view('modules.caja.liquidacion',['uso' => $usoliquidacion,'aprobadores' => $aprobadores]);
        }
    }
})->name('View.Caja');
Route::post('/Caja/Liquidacion', 'CajaController@liquidacion' );
Route::match(['get', 'post'],'/Caja/Nuevo', 'CajaController@nuevaliquidacion' );
Route::match(['get', 'post'],'/Caja/Totales', 'CajaController@obtenertotales' );

Route::post('/Caja/Cajachica', 'CajaController@cajachica' );
Route::post('/Caja/Cajachica/Adicion', 'CajaController@cajachicainsert' );
Route::post('/Caja/Cajachica/Info', 'CajaController@cajachicainfo');
Route::match(['get', 'post'], '/Caja/Cajachica/Exportar', 'CajaController@cajachicaexportar');

Route::post('/Caja/Rendirpago', 'CajaController@rendirpago' );
Route::post('/Caja/Rendirpago/Adicion', 'CajaController@rendirpagoinsert' );
Route::post('/Caja/Rendirpago/Info', 'CajaController@rendirpagoinfo');
Route::match(['get', 'post'], '/Caja/Rendirpago/Exportar', 'CajaController@cajachicaexportar');

Route::get('/Caja/Parametros', function () { return view('modules.caja.parametros'); })->name('View.Parametros');


Route::get('/Muestreo', function () { return view('modules.muestreo.muestreo'); })->name('View.Muestreo');
Route::get('/Muestreo/Compras', function () {
    if(Auth::check()){
        $tipo = 9;
        $tiposubuso = 10;
        $uso_id = 0;
        $idusuario = Auth::user()->id;
        $contadorusomuestreo = DB::table('usos')->where('idusuario','=',$idusuario)->where('idtipo','=',$tipo)->count();
        $contadorarchivos = DB::table('archivos')->where('user_id','=',$idusuario)->count();
        $comprobantes = DB::table('comprobantes')->orderBy('codigo','asc')->get();
        if($contadorusomuestreo > 0)
        {
            $uso = DB::table('usos')
            ->where('idusuario','=',$idusuario)
            ->where('idtipo','=',$tipo)
            ->latest()
            ->first();

            $uso_id = $uso->id;

            $contadorusocompras = DB::table('usos')->where('uso_id','=',$uso_id)->where('idusuario','=',$idusuario)->where('idtipo','=',$tiposubuso)->count();

            if($contadorusocompras>0)
            {
                $usocompras = DB::table('usos')
                ->where('idusuario','=',$idusuario)
                ->where('uso_id','=',$uso_id)
                ->where('idtipo','=',$tiposubuso)
                ->latest()
                ->first();

                return view('modules.muestreo.compras',['uso' => $usocompras,'comprobantes' => $comprobantes]);
            } else {

                $usocompras = new Uso([
                    'idusuario' => $idusuario,
                    'uso_id' => $uso_id,
                    'referencia' => 'Ejemplo de referencia compras',
                    'idtipo' => $tiposubuso,
                ]);
                $usocompras->save();

                return view('modules.muestreo.compras',['uso' => $usocompras,'comprobantes' => $comprobantes]);
            }
            
        } else {
            $uso = new Uso();
            $uso->idusuario = $idusuario;
            $uso->uso_id = 0;
            $uso->referencia = 'Ejemplo de referencia';
            $uso->idtipo = $tipo;
            $uso->save();

            $usocompras = new Uso([
                'idusuario' => $idusuario,
                'uso_id' => $uso->id,
                'referencia' => 'Ejemplo de referencia compras sin uso general',
                'idtipo' => $tiposubuso,
            ]);
            $usocompras->save();

            return view('modules.muestreo.compras',['uso' => $usocompras,'comprobantes' => $comprobantes]);
        }
    }
})->name('View.Compras');
Route::get('/Muestreo/Gastos', function () {

    if(Auth::check()){
        $tipo = 9;
        $tiposubuso = 11;
        $uso_id = 0;
        $idusuario = Auth::user()->id;
        $contadorusomuestreo = DB::table('usos')->where('idusuario','=',$idusuario)->where('idtipo','=',$tipo)->count();
        $contadorarchivos = DB::table('archivos')->where('user_id','=',$idusuario)->count();
        $comprobantes = DB::table('comprobantes')->orderBy('codigo','asc')->get();
        if($contadorusomuestreo > 0)
        {
            $uso = DB::table('usos')
            ->where('idusuario','=',$idusuario)
            ->where('idtipo','=',$tipo)
            ->latest()
            ->first();

            $uso_id = $uso->id;

            $contadorusocompras = DB::table('usos')->where('uso_id','=',$uso_id)->where('idusuario','=',$idusuario)->where('idtipo','=',$tiposubuso)->count();

            if($contadorusocompras>0)
            {
                $usocompras = DB::table('usos')
                ->where('idusuario','=',$idusuario)
                ->where('uso_id','=',$uso_id)
                ->where('idtipo','=',$tiposubuso)
                ->latest()
                ->first();

                return view('modules.muestreo.gastos',['uso' => $usocompras,'comprobantes' => $comprobantes]);
            } else {

                $usocompras = new Uso([
                    'idusuario' => $idusuario,
                    'uso_id' => $uso_id,
                    'referencia' => 'Ejemplo de referencia compras',
                    'idtipo' => $tiposubuso,
                ]);
                $usocompras->save();

                return view('modules.muestreo.gastos',['uso' => $usocompras,'comprobantes' => $comprobantes]);
            }
            
        } else {
            $uso = new Uso();
            $uso->idusuario = $idusuario;
            $uso->uso_id = 0;
            $uso->referencia = 'Ejemplo de referencia';
            $uso->idtipo = $tipo;
            $uso->save();

            $usocompras = new Uso([
                'idusuario' => $idusuario,
                'uso_id' => $uso->id,
                'referencia' => 'Ejemplo de referencia compras sin uso general',
                'idtipo' => $tiposubuso,
            ]);
            $usocompras->save();

            return view('modules.muestreo.gastos',['uso' => $usocompras,'comprobantes' => $comprobantes]);
        }
    }

})->name('View.Gastos');
Route::get('/Muestreo/Ventas', function () {

    if(Auth::check()){
        $tipo = 9;
        $tiposubuso = 12;
        $uso_id = 0;
        $idusuario = Auth::user()->id;
        $contadorusomuestreo = DB::table('usos')->where('idusuario','=',$idusuario)->where('idtipo','=',$tipo)->count();
        $contadorarchivos = DB::table('archivos')->where('user_id','=',$idusuario)->count();
        $comprobantes = DB::table('comprobantes')->orderBy('codigo','asc')->get();
        if($contadorusomuestreo > 0)
        {
            $uso = DB::table('usos')
            ->where('idusuario','=',$idusuario)
            ->where('idtipo','=',$tipo)
            ->latest()
            ->first();

            $uso_id = $uso->id;

            $contadorusocompras = DB::table('usos')->where('uso_id','=',$uso_id)->where('idusuario','=',$idusuario)->where('idtipo','=',$tiposubuso)->count();

            if($contadorusocompras>0)
            {
                $usocompras = DB::table('usos')
                ->where('idusuario','=',$idusuario)
                ->where('uso_id','=',$uso_id)
                ->where('idtipo','=',$tiposubuso)
                ->latest()
                ->first();

                return view('modules.muestreo.ventas',['uso' => $usocompras,'comprobantes' => $comprobantes]);
            } else {

                $usocompras = new Uso([
                    'idusuario' => $idusuario,
                    'uso_id' => $uso_id,
                    'referencia' => 'Ejemplo de referencia compras',
                    'idtipo' => $tiposubuso,
                ]);
                $usocompras->save();

                return view('modules.muestreo.ventas',['uso' => $usocompras,'comprobantes' => $comprobantes]);
            }
            
        } else {
            $uso = new Uso();
            $uso->idusuario = $idusuario;
            $uso->uso_id = 0;
            $uso->referencia = 'Ejemplo de referencia';
            $uso->idtipo = $tipo;
            $uso->save();

            $usocompras = new Uso([
                'idusuario' => $idusuario,
                'uso_id' => $uso->id,
                'referencia' => 'Ejemplo de referencia compras sin uso general',
                'idtipo' => $tiposubuso,
            ]);
            $usocompras->save();

            return view('modules.muestreo.ventas',['uso' => $usocompras,'comprobantes' => $comprobantes]);
        }
    }

})->name('View.Ventas');

Route::match(['get', 'post'], '/ImportarExcelCompra', 'MayorcompraController@importar');
Route::match(['get', 'post'], '/ExportarExcelCompra', 'MayorcompraController@exportar');
Route::match(['get', 'post'], '/FiltrarExcelCompra', 'MayorcompraController@filtrar');
Route::match(['get', 'post'], '/ImportExcelVentas', 'MayorventaController@import');
Route::match(['get', 'post'], '/ImportExcelGasto', 'MayorgastoController@import');
Route::match(['get', 'post'], '/ImportExcelActivo', 'ActivofijoController@import');
Auth::routes();


Route::get('/Caja/Parametros', function () { return view('modules.caja.parametros'); })->name('View.Parametros');

Route::get('/Activos', function () {

    if(Auth::check()){
        $tipo = 17;
        $uso_id = 0;
        $idusuario = Auth::user()->id;
        $contadorusoactivos = DB::table('usos')->where('idusuario','=',$idusuario)->where('idtipo','=',$tipo)->count();
        
        $aprobadores = DB::table('aprobadors')->where('user_id','=',Auth::user()->id)->get();
        
        if($contadorusoactivos > 0)
        {
            $uso = DB::table('usos')
            ->where('idusuario','=',$idusuario)
            ->where('idtipo','=',$tipo)
            ->latest()
            ->first();

            return view('modules.activos.activos',['uso' => $uso,'aprobadores' => $aprobadores]);
            
        } else {
            $uso = new Uso();
            $uso->idusuario = $idusuario;
            $uso->uso_id = 0;
            $uso->referencia = 'Ejemplo de referencia activos';
            $uso->idtipo = $tipo;
            $uso->save();

            return view('modules.activos.activos',['uso' => $uso,'aprobadores' => $aprobadores]);
        }
    }
    
return view('modules.activos.activos');
})->name('View.Activos');
Route::match(['get', 'post'], '/Activos/Importar', 'ActivofijoController@import');

Route::get('/Balance', function () {

    if(Auth::check()){
        $tipo = 18;
        $uso_id = 0;
        $idusuario = Auth::user()->id;
        $contadorusoactivos = DB::table('usos')->where('idusuario','=',$idusuario)->where('idtipo','=',$tipo)->count();
        
        $aprobadores = DB::table('aprobadors')->where('user_id','=',Auth::user()->id)->get();
        
        if($contadorusoactivos > 0)
        {
            $uso = DB::table('usos')
            ->where('idusuario','=',$idusuario)
            ->where('idtipo','=',$tipo)
            ->latest()
            ->first();

            return view('modules.balance.balance',['uso' => $uso,'aprobadores' => $aprobadores]);
            
        } else {
            $uso = new Uso();
            $uso->idusuario = $idusuario;
            $uso->uso_id = 0;
            $uso->referencia = 'Ejemplo de referencia balance';
            $uso->idtipo = $tipo;
            $uso->save();

            return view('modules.balance.balance',['uso' => $uso,'aprobadores' => $aprobadores]);
        }
    }
    

return view('modules.balance.balance');

})->name('View.Balance');

Route::match(['get', 'post'], '/Balance/Importar', 'BalanceController@importar');
Route::match(['get', 'post'], '/Balance/Exportar', 'BalanceController@exportar');

Route::get('/Centrocosto', function () { 
    
    $Centrocosto = new Centrocosto([
        'codigo' => '49448',
        'descripcion' => 'Centro costo 1'
    ]);
    $Centrocosto->save();

    $Centrocostos = DB::table('Centrocostos')->get();
    return $Centrocostos; });
Route::get('/Activos/Tipouso', function () { 
    
    $tipo = new Tipouso([
        'descripcion' => 'Activos'
    ]);
    $tipo->save();

    $activos = DB::table('tipousos')->get();
    return $activos; });

Route::get('/Codigocontable', function () { 
    
    $Codigocontable = new Codigocontable([
        'codigo' => '79988',
        'descripcion' => 'Contable 1'
    ]);
    $Codigocontable->save();

    $Codigocontable2 = new Codigocontable([
        'codigo' => '79999',
        'descripcion' => 'Contable 2',
        'idpais' => 1
    ]);
    $Codigocontable2->save();

    $codigocontables = DB::table('codigocontables')->get();
    return $codigocontables; });

Route::get('/Monedas', function () { 
    
    $pais = new Pais([
        'codigo' => '01',
        'descripcion' => 'Perú'
    ]);
    $pais->save();

    $moneda = new Moneda([
        'codigo' => '01',
        'descripcion' => 'Nuevo sol',
        'idpais' => 1
    ]);
    $moneda->save();

    $pais = new Pais([
        'codigo' => '02',
        'descripcion' => 'EEUU'
    ]);
    $pais->save();

    $moneda = new Moneda([
        'codigo' => '02',
        'descripcion' => 'DOLAR',
        'idpais' => 2
    ]);
    $moneda->save();

    $pais = DB::table('pais')->get();
    $monedas = DB::table('monedas')->get();
    return ['monedas'=>$monedas,'dnis'=>$pais]; });

Route::get('/Dni', function () { 
    
    $dni = new Dni([
        'codigo' => '01',
        'descripcion' => 'DNI'
    ]);
    $dni->save();

    $dni2 = new Dni([
        'codigo' => '02',
        'descripcion' => 'PASSAPORTE'
    ]);
    $dni2->save();

    $dni3 = new Dni([
        'codigo' => '03',
        'descripcion' => 'OTROS'
    ]);
    $dni3->save();

    $dnis = DB::table('dnis')->get();
    return $dnis; });

Route::get('/Muestreo/TipoUso', function () { 
    
    $tipo = new Tipouso([
        'descripcion' => 'Balance'
    ]);
    $tipo->save();


    $tipos = DB::table('tipousos')->get();
    return $tipos; });


    Route::get('/Muestreo/Aprobadores', function () { 
    
        $aprobador = new Aprobador([
            'nombre' => 'Jorge',
            'apellido' => 'Hospinal',
            'dni' => '72733291',
            'telefono' => '966153268',
            'correo' => 'jorge.hospinal@yahoo.com',
            'user_id' => Auth::user()->id
        ]);
        $aprobador->save();
    
        $aprobador = new Aprobador([
            'nombre' => 'Axel',
            'apellido' => 'Davis',
            'dni' => '72755591',
            'telefono' => '966222268',
            'correo' => 'example@yahoo.com',
            'user_id' => Auth::user()->id
        ]);
        $aprobador->save();
    
        $aprobadores = DB::table('aprobadors')->get();
        return $aprobadores; });