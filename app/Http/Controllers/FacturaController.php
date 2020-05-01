<?php

namespace App\Http\Controllers;

use App\Clase\Modelosgenerales\Sistemacontable;
use App\Clases\Caja\Aprobador;
use App\Clases\Modelosgenerales\Archivo;
use App\Clases\Uso;
use App\Clases\Xml\Factura;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Nuevo() {
        $user = Auth::user();
        $user_id = $user->id;
        $tipo = 19;
        
        $uso = new Uso([
            'idusuario' => $user_id,
            'uso_id' => 0,
            'referencia' => 'Ejemplo de referencia compras',
            'idtipo' => $tipo,
        ]);
        $uso->save();

        return view("modules.xml.xml",['uso'=>$uso]);
    }

    public function Index() {
        $user = Auth::user();
        $user_id = $user->id;
        $tipo = 19;
        
        $conteousos = Uso::where('idusuario','=',$user_id)->where('idtipo','=',$tipo)->count();

        if($conteousos > 0)
        {
            $uso = DB::table('usos')
            ->where('idusuario','=',$user_id)
            ->where('idtipo','=',$tipo)
            ->latest()
            ->first();

            return view("modules.xml.xml",['uso'=>$uso]);
        } else {
            $uso = new Uso([
                'idusuario' => $user_id,
                'uso_id' => 0,
                'referencia' => 'Ejemplo de referencia compras',
                'idtipo' => $tipo,
            ]);
            $uso->save();

            return view("modules.xml.xml",['uso'=>$uso]);
        }
        
        
    }

    public function eliminar(Request $request) {
        DB::delete('delete from facturas where id = ?', [$request->id]);
        return Factura::get();
    }

    public function GetData(Request $request) {
        

        $files = $request->file('myfile');
        $nro = count($files);
        for ($i=0; $i < $nro; $i++) { 
            $filenamewithext = $files[$i]->getClientOriginalName();
            $filename = pathinfo($filenamewithext, PATHINFO_FILENAME);
            $ext = $files[$i]->getClientOriginalExtension();
            $filenametostore = $filename.'_'.time().'.'.$ext;
            $ruta = $files[$i]->move('storage/xml/', $filenametostore);
            $ruta = public_path($ruta);
            $xml = simplexml_load_file($ruta);
            $ns = $xml->getNamespaces(true);
            $xml->registerXPathNamespace('cac',$ns['cac']);
            $xml->registerXPathNamespace('cbc',$ns['cbc']);
            
            $ids = array();
            

            $uso_id                    =    $request->uso_id;
            $usuario_id                =    Auth::user()->id;
            $codigo_doc                =    (string)$xml->xpath('//cbc:InvoiceTypeCode')[0];
            try {
                $ruc_cliente               =    (string)$xml->xpath('//cac:AccountingCustomerParty//cac:Party//cac:PartyIdentification//cbc:ID')[0];
            } catch (\Exception $e) {
                $ruc_cliente               =    (string)$xml->xpath('//cac:AccountingCustomerParty//cbc:CustomerAssignedAccountID')[0];
            }

            $razon_social_cliente          =    (string)$xml->xpath('//cac:AccountingCustomerParty//cac:Party//cac:PartyLegalEntity//cbc:RegistrationName')[0];

            try {
                $ruc_proveedor             =    (string)$xml->xpath('//cac:AccountingSupplierParty//cac:Party//cac:PartyIdentification//cbc:ID')[0];
            } catch (\Throwable $th) {
                $ruc_proveedor             =    (string)$xml->xpath('//cac:AccountingSupplierParty//cbc:CustomerAssignedAccountID')[0];
            }
                                            
            $razon_social_proveedor        =    (string)$xml->xpath('//cac:AccountingSupplierParty//cac:Party//cac:PartyLegalEntity//cbc:RegistrationName')[0];

            try {
                $ubigeo                    =    (string)$xml->xpath('//cac:AccountingCustomerParty//cac:Party//cac:PartyLegalEntity//cac:RegistrationAddress//cbc:ID')[0];
            } catch (\Throwable $th) {
                $ubigeo                    =    "";
            }
            
            $igv                           =    $xml->xpath('//cac:TaxTotal//cac:TaxSubtotal//cbc:TaxAmount')[0];

            $total                         =    $xml->xpath('//cac:LegalMonetaryTotal//cbc:PayableAmount')[0];

            try {
                $valor_venta               =    $xml->xpath('//cac:TaxTotal//cac:TaxSubtotal//cbc:TaxableAmount')[0];
            } catch (\Throwable $th) {
                $valor_venta               =   $total - $igv;
            }

            $descripcion                   =    (string)$xml->xpath('//cac:InvoiceLine//cac:Item//cbc:Description')[0];

            $array_ids                     =  $xml->xpath('//cbc:ID');
            
            foreach ($array_ids as $key => $value) {
                if (strpos((string)$value, "-", 1) > 0 && strpos((string)$value, "F") == 0 && strpos((string)$value, "B") == 0) {
                    $pos = strpos((string)$value, "-", 1);
                    $serie = substr($value, 0, $pos);
                    $numero = substr($value, $pos + 1);
                    break;
                    //return $value;
                }
            }
            
            //return 'No se encontró Coincidencias';
            
            //initializar read xml
            $factura = new Factura();
            $factura->uso_id = $uso_id;
            $factura->usuario_id = $usuario_id;
            $factura->codigo_doc = $codigo_doc;
            $factura->serie = $serie;
            $factura->numero = $numero;
            $factura->ruc_proveedor = $ruc_proveedor;
            $factura->razon_social_proveedor = $razon_social_proveedor;
            $factura->ruc_cliente = $ruc_cliente;
            $factura->razon_social_cliente = $razon_social_cliente;
            $factura->ubigeo = $ubigeo;
            $factura->igv = $igv;
            $factura->valor_venta = $valor_venta;
            $factura->total = $total;
            $factura->descripcion = $descripcion;
            $factura->save();
        }

        $db = DB::table("facturas")->where('usuario_id','=',$usuario_id)->where('uso_id','=',$uso_id)->get();
        return $db;
    }

    public function Exportar(Request $request) {

        $xml = Uso::firstWhere('id','=',$request->uso_id);
        
        $correo = $request->correo;
        $asunto = $request->asunto;
        
        $user = Auth::user();
        $date = Carbon::now()->format('d-m-Y');
        
        $numeracion = $request->codigo;

        $data = DB::table('facturas')->where('uso_id','=',$xml->id)->get();

        $template_path = public_path('/assets/files/xmltemplate.xlsx');
        $spreadsheet = IOFactory::load($template_path);

        $i = 2;

        foreach ($data as $reg) {
            
                $cellA = 'A'.$i;
                $cellB = 'B'.$i;
                $cellC = 'C'.$i;
                $cellD = 'D'.$i;
                $cellE = 'E'.$i;
                $cellF = 'G'.$i;
                $cellG = 'G'.$i;
                $cellH = 'H'.$i;
                $cellI = 'I'.$i;
                $cellJ = 'J'.$i;
                $cellK = 'K'.$i;
                $cellL = 'L'.$i;
                
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellA, $reg->codigo_doc);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellB, $reg->serie);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellC, $reg->numero);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellD, $reg->ruc_proveedor);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellE, $reg->razon_social_proveedor);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellF, $reg->ruc_cliente);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellG, $reg->razon_social_cliente);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellH, $reg->ubigeo);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellI, $reg->descripcion);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellJ, $reg->igv);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellK, $reg->valor_venta);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cellL, $reg->total);
            $i++;
        }
        
        if($request->mail)
        {
            //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            //header('Content-Disposition: attachment;filename="REPORTE.xlsx"');
            //header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            //header('Cache-Control: max-age=1');
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            ob_start();
            $writer->save('php://output');
            $content = ob_get_contents();
            ob_end_clean();
            $filename = 'report';
            $exe = '.xlsx';
            $unique_name = $filename.time().$exe;
            $ruta = Storage::put('public/Xml/'.$user->name.'/'.$unique_name,$content);

            $ruta = public_path('Storage/Xml/'.$user->name.'/');
            
            $ruta = $ruta.$unique_name;

            $archivo = new Archivo();
            $archivo->user_id = $user->id;
            $archivo->uso_id = $xml->id;
            $archivo->ruta = $ruta;
            $archivo->save();
            $id_archivo = $archivo->id;

            $info = array(
                'nombre' => $user->name,
                'telefono' => $user->telefono,
                'correo' => $user->mail,
                'fecha' => $date,
                'ruta' => $ruta
            );

            Mail::send('modules.caja.mail',$info,function($message){
                $message->from('201602035x@gmail.com','Contadorapp');
                $message->to('jorge.hospinal@yahoo.com')->subject(request()->input('asunto'));
                $message->to(request()->input('correo'))->subject(request()->input('asunto'));
            });

        } else {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="REPORTE.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            
            $writer->save('php://output');
        }
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
     * @param  \App\Clases\Xml\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function show(Factura $factura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Clases\Xml\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function edit(Factura $factura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Clases\Xml\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Factura $factura)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Clases\Xml\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function destroy(Factura $factura)
    {
        //
    }
}
