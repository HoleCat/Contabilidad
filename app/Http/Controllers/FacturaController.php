<?php

namespace App\Http\Controllers;

use App\Clases\Uso;
use App\Clases\Xml\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            $ruc_cliente               =    (string)$xml->xpath('//cac:AccountingCustomerParty//cac:Party//cac:PartyIdentification//cbc:ID')[0];
            $razon_social_cliente      =    (string)$xml->xpath('//cac:AccountingCustomerParty//cac:Party//cac:PartyLegalEntity//cbc:RegistrationName')[0];

            $ruc_proveedor             =    (string)$xml->xpath('//cac:AccountingSupplierParty//cac:Party//cac:PartyIdentification//cbc:ID')[0];
            $razon_social_proveedor    =    (string)$xml->xpath('//cac:AccountingSupplierParty//cac:Party//cac:PartyLegalEntity//cbc:RegistrationName')[0];

            $ubigeo                    =    (string)$xml->xpath('//cac:AccountingCustomerParty//cac:Party//cac:PartyLegalEntity//cac:RegistrationAddress//cbc:ID')[0];
            $igv                       =    (string)$xml->xpath('//cac:TaxTotal//cac:TaxSubtotal//cbc:TaxAmount')[0];
            $valor_venta               =    (string)$xml->xpath('//cac:TaxTotal//cac:TaxSubtotal//cbc:TaxableAmount')[0];
            $total                     =    (string)$xml->xpath('//cac:LegalMonetaryTotal//cbc:PayableAmount')[0];
            $descripcion               =    (string)$xml->xpath('//cac:InvoiceLine//cac:Item//cbc:Description')[0];

            $array_ids         =  $xml->xpath('//cbc:ID');
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

    public function Exportar() {
        
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
