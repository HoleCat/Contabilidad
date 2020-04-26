<?php

namespace App\Http\Controllers;

use App\Mayorcompra;
use App\Imports\MayorcomprasImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Formatos\Excelmuestreo;
use App\Clases\Almacenamiento;
use App\Clases\Modelosgenerales\Archivo;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MayorcompraController extends Controller
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
     * @param  \App\Mayorcompra  $mayorcompra
     * @return \Illuminate\Http\Response
     */
    public function show(Mayorcompra $mayorcompra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Mayorcompra  $mayorcompra
     * @return \Illuminate\Http\Response
     */
    public function edit(Mayorcompra $mayorcompra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Mayorcompra  $mayorcompra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mayorcompra $mayorcompra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Mayorcompra  $mayorcompra
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mayorcompra $mayorcompra)
    {
        //
    }

    public function export() {
        $json_data = session('datacompras');

        $cell_order_compras = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP");

        $user_id = Auth::user()->id;
        $username = Auth::user()->name;
        
        $ruta = public_path('/assets/files/templatemayorcompra.xlsx');

        //$array_data = json_decode($json_data, true);
        $array_data = $json_data;
        $spreadsheet = IOFactory::load($ruta);
    
        $cont_1 = 2;
    
        foreach ($array_data as $item) {
            $cont_2 = 0;
            foreach ($item as $cell_value) {
                $cell_id = $cell_order_compras[$cont_2].$cont_1;
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cell_id, $cell_value);
                $cont_2++;
            }
            $cont_1++;
        }

        $spreadsheet->getActiveSheet()->setTitle('Hoja 1');
    
        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reportecompras.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
        
        //$writer = new Xlsx($spreadsheet);
        //ob_start();
        //$writer->save('php://output');
        //$content = ob_get_contents();
        //ob_end_clean();

        //$fruta = Excelmuestreo::downloadExcel($request->data,$cell_order_compras,$ruta);
        //return $request->input('data');
        //return url('assets/files/resultado.xlsx');
        //return $fruta;
    }

    public function import(Request $request)
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
        
            Excel::import(new MayorcomprasImport, $ruta);

            Storage::deleteDirectory('public/'.$useremail.'/temporal', true);
            // sleep 1 second because of race condition with HD
            sleep(1);
            // actually delete the folder itself
            Storage::deleteDirectory('public/'.$useremail.'/temporal');  
            
            //rmdir(storage_path('app/public/'.$useremail.'/temporal'));
            //$data = DB::table('Mayorcompras')->get();
            //report_xl_compras`(_impMin int , _impMax int, _equi int, _tipoDoc varchar(9))
            //$impMin = $request->input('importeminimo');
            //$impMax = $request->input('importemaximo');
            //$comparacion = $request->input('comparacion');
            //$tipo = $request->input('tipocomprobante');
            //$cantidad = $request->input('cantidad');
            //$reporte = DB::select('call report_xl_compras(?, ?, ?, ?, ?, ?, ?)',[$impMin,$impMax,$cantidad,$comparacion,$tipo,$uso_id,$id_archivo]);
            //session(['datacompras' => $reporte]);
            //return response()->json($reporte,200);
            return $archivo;
        } else {
            //$reporte = 'Debe adjuntar un archivo';
            //return response()->json($reporte,200);
        }        
    }

    public function filtrar(Request $request)
    {
        $this->validate($request, [
            'myfile' => 'mimes:xls,xlsx'
        ]);

        $uso_id = $request->input('iduso');
        
        $id_archivo = $request->input('id_archivo');
        $impMin = $request->input('importeminimo');
        $impMax = $request->input('importemaximo');
        $comparacion = $request->input('comparacion');
        $tipo = $request->input('tipocomprobante');
        $cantidad = $request->input('cantidad');

        $reporte = DB::select('call report_xl_compras(?, ?, ?, ?, ?, ?, ?)',[$impMin,$impMax,$cantidad,$comparacion,$tipo,$uso_id,$id_archivo]);
        
        session(['datacompras' => $reporte]);

        return response()->json($reporte,200);                
    }

}
