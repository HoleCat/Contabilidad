<?php
use App\Clases\Caja\Aprobador;
use App\Clases\Caja\LiquidacionDetalle;
use App\clases\modelosgenerales\Centrocosto;
use App\clases\modelosgenerales\Codigocontable;
use App\clases\modelosgenerales\Dni;
use App\Clases\Modelosgenerales\Empresa;
use App\Clases\Modelosgenerales\Moneda;
use App\clases\modelosgenerales\Pais;
use App\Clases\Modelosgenerales\Tipouso;
use App\Clases\Reporte\DTR;
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
Route::post('/borrarcochinada', function () {
    DB::table('activofijos')->delete();
});

Route::get('/', function () {
    return view('welcome');
});
Route::get('/Admin', function () {
    return view('admin');
});
Route::match(['get', 'post'], '/Admin/Empresa', 'AdminController@empresa');
Route::match(['get', 'post'], '/Admin/Sistemacontable', 'AdminController@sistemacontable');

Auth::routes();

Route::match(['get', 'post'],'/Reporte',
['uses' => 'ReporteComprasController@Index']
);
Route::match(['get', 'post'],'/Reporte/Compras/Importar',
['uses' => 'ReporteComprasController@Importar']
);
Route::match(['get', 'post'],'/Reporte/Compras/Liberar',
['uses' => 'ReporteComprasController@Liberar']
);
Route::match(['get', 'post'],'/Reporte/Compras/Status',
['uses' => 'ReporteComprasController@Status']
);
Route::match(['get', 'post'],'/Reporte/Compras/Data',
['uses' => 'ReporteComprasController@Data']
);
Route::match(['get', 'post'],'/Reporte/Compras/Txtconsultaruc',
['uses' => 'ReporteComprasController@Txtconsultaruc']
);
Route::match(['get', 'post'],'/Reporte/Compras/Txtcomprobantes',
['uses' => 'ReporteComprasController@Txtcomprobantes']
);
Route::match(['get', 'post'],'/Reporte/Compras/Detraccion',
['uses' => 'ReporteComprasController@ImportarDetraccion']
);

Route::match(['get', 'post'],'/Validador',
['uses' => 'ValidacionController@Index']
);
Route::match(['get', 'post'],'/Validador/Importar',
['uses' => 'ValidacionController@Importar']
);

Route::match(['get', 'post'],'/Xml',
['uses' => 'FacturaController@Index']
);
Route::match(['get', 'post'],'/Xml/Nuevo',
['uses' => 'FacturaController@Nuevo']
);
Route::match(['get', 'post'],'/Xml/Exportar',
['uses' => 'FacturaController@Exportar']
);
Route::match(['get', 'post'],'/Xml/Show',
['uses' => 'FacturaController@show']
);
Route::match(['get', 'post'],'/upload',[
    'uses' => 'FacturaController@GetData'
]);
Route::match(['get', 'post'],'/Factura/Eliminar',[
    'uses' => 'FacturaController@eliminar'
]);

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
    $empresas = Empresa::get();

    if($userdatacount>0)
    {
        $userdata = Userdata::firstWhere('user_id','=',$user->id);
        $empresa = Empresa::where('id','=', $userdata->empresa);
        return view('layouts.userdata',['empresa'=>$empresa,'user'=>$user,'userdata'=>$userdata,'empresas'=>$empresas]);
    } else {
        $userdata = new Userdata();
        $userdata->user_id = Auth::user()->id;
        $userdata->save();
        return view('layouts.userdata',['user'=>$user,'userdata'=>$userdata,'empresas'=>$empresas]);
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
        $sistemas = DB::table('sistemacontables')->get();
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
                        return view('modules.caja.cajachica',['sistemas'=>$sistemas,'centrocostos'=>$centrocostos,'monedas'=>$monedas,'uso'=>$usoliquidacion,'liquidacion'=>$liquidacion,'documentos'=>$tiposdocumento,'codigocontable'=>$codigocontable]);
                    }
                    if($liquidacion->servicio == 'rendirpago'){
                        return view('modules.caja.rendirpago',['sistemas'=>$sistemas,'centrocostos'=>$centrocostos,'monedas'=>$monedas,'uso'=>$usoliquidacion,'liquidacion'=>$liquidacion,'documentos'=>$tiposdocumento,'codigocontable'=>$codigocontable]);
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

Route::get('/Muestreo/Compras', 'MayorcompraController@Index');
Route::get('/Muestreo/Gastos', 'MayorgastoController@Index');
Route::get('/Muestreo/Ventas', 'MayorventaController@Index');

Route::match(['get', 'post'],'/Muestreo/Compras/Destroy', 'MayorcompraController@Destroy');
Route::match(['get', 'post'],'/Muestreo/Gastos/Destroy', 'MayorgastoController@Destroy');
Route::match(['get', 'post'],'/Muestreo/Ventas/Destroy', 'MayorventaController@Destroy');

Route::match(['get', 'post'], '/ImportarExcelCompra', 'MayorcompraController@importar');
Route::match(['get', 'post'], '/ExportarExcelCompra', 'MayorcompraController@exportar');
Route::match(['get', 'post'], '/FiltrarExcelCompra', 'MayorcompraController@filtrar');

Route::match(['get', 'post'], '/ImportarExcelVentas', 'MayorventaController@importar');
Route::match(['get', 'post'], '/ExportarExcelVentas', 'MayorventaController@exportar');
Route::match(['get', 'post'], '/FiltrarExcelVentas', 'MayorventaController@filtrar');

Route::match(['get', 'post'], '/ImportarExcelGastos', 'MayorgastoController@importar');
Route::match(['get', 'post'], '/ExportarExcelGastos', 'MayorgastoController@exportar');
Route::match(['get', 'post'], '/FiltrarExcelGastos', 'MayorgastoController@filtrar');

Route::match(['get', 'post'], '/ImportExcelActivo', 'ActivofijoController@import');

Auth::routes();

Route::get('/Caja/Parametros', function () { return view('modules.caja.parametros'); })->name('View.Parametros');

Route::get('/Activos', 'ActivofijoController@Index');
Route::match(['get', 'post'], '/Activos/Importar', 'ActivofijoController@importar');
Route::match(['get', 'post'], '/Activos/Filtrar', 'ActivofijoController@filtrar');
Route::match(['get', 'post'], '/Activos/Exportar', 'ActivofijoController@exportar');
Route::match(['get', 'post'], '/Activos/Destroy', 'ActivofijoController@Destroy');

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
    return $Centrocostos;
});
Route::get('/Activos/Tipouso', function () { 
    
    $tipo = new Tipouso([
        'descripcion' => 'Validacion'
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

Route::get('/DTR', function () { 

    $dtr1 = new DTR(['COD' => '008','MCOD'=>'8','Porcentaje'=>0.04,'Denominacion'=>'Madera']);
    $dtr1->save();
    $dtr1 = new DTR(['COD'=>'010','MCOD'=>'10','Porcentaje'=>0.15,'Denominacion'=>'Residuos, subproductos, desechos.']);
    $dtr1->save();
    $dtr1 = new DTR(['COD'=>'012','MCOD'=>'12','Porcentaje'=>0.12,'Denominacion'=>'Intermed. Laboral y Tercerización']);
    $dtr1->save();
    $dtr1 = new DTR(['COD'=>'019','MCOD'=>'19','Porcentaje'=>0.10,'Denominacion'=>'Arrendamiento de muebles']);
    $dtr1->save();
    $dtr1 = new DTR(['COD'=>'020','MCOD'=>'20','Porcentaje'=>0.12,'Denominacion'=>'Manten. / Reparación bienes muebles']);
    $dtr1->save();
    $dtr1 = new DTR(['COD'=>'021','MCOD'=>'21','Porcentaje'=>0.10,'Denominacion'=>'Movimiento de carga']);
    $dtr1->save();
    $dtr1 = new DTR(['COD'=>'022','MCOD'=>'22','Porcentaje'=>0.12,'Denominacion'=>'Otros servicios empresariales']);
    $dtr1->save();
    $dtr1 = new DTR(['COD'=>'024','MCOD'=>'24','Porcentaje'=>0.10,'Denominacion'=>'Comisión Mercantil']);
    $dtr1->save();
    $dtr1 = new DTR(['COD'=>'025','MCOD'=>'25','Porcentaje'=>0.10,'Denominacion'=>'Fabricación de bienes por encargo']);
    $dtr1->save();
    $dtr1 = new DTR(['COD'=>'026','MCOD'=>'26','Porcentaje'=>0.10,'Denominacion'=>'Transporte de personas']);
    $dtr1->save();
    $dtr1 = new DTR(['COD'=>'027','MCOD'=>'27','Porcentaje'=>0.04,'Denominacion'=>'ransporte de bienes']);
    $dtr1->save();
    $dtr1 = new DTR(['COD'=>'030','MCOD'=>'30','Porcentaje'=>0.04,'Denominacion'=>'ontrato de construcción']);
    $dtr1->save();
    $dtr1 = new DTR(['COD'=>'037','MCOD'=>'37','Porcentaje'=>0.12,'Denominacion'=>'Demás servicios gravados con el IGV']);
    $dtr1->save();
    $dtr1 = new DTR(['COD'=>'039','MCOD'=>'39','Porcentaje'=>0.10,'Denominacion'=>'Minerales no metálicos']);
    $dtr1->save();
    $dtr1 = new DTR(['COD'=>'099','MCOD'=>'99','Porcentaje'=>0.08,'Denominacion'=>'ey N° 30737']);
    $dtr1->save();

    $data = DB::table('d_t_r_s')->get();
    return $data;
});

Route::get('/TipoUso', function () { 
    
    $tipo = new Tipouso([
        'descripcion' => 'Reporte'
    ]);
    
    $tipo = new Tipouso([
        'descripcion' => 'Reporte de Compras'
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

    