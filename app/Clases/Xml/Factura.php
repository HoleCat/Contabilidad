<?php

namespace App\Clases\Xml;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = ['codigo_doc', 'serie','numero','ruc_proveedor','razon_social_proveedor','ruc_cliente','razon_social_cliente', 'ubigeo', 'igv', 'valor_venta', 'total', 'descripcion'];
}
