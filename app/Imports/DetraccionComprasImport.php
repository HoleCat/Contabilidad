<?php

namespace App\Imports;

use App\Clases\Reporte\DetraccionCompras;
use App\Formatos\Validacion;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class DetraccionComprasImport implements ToModel, WithStartRow, WithCalculatedFormulas
{   
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    
    public function startRow(): int
    {
        return 2;
    }
    
    public function model(array $row)
    {   
        try {
            $fecha1 = date_format(Date::excelToDateTimeObject($row[12]), 'd-m-Y');
        } catch (\Throwable $th) {
            $fecha1 = $row[12];
        }
        
        return new DetraccionCompras([
            'IdUso'=>$row[0],
            'IdArchivo'=>$row[1],
            'Cuo'=>$row[2],
            'TipoCuenta'=>$row[3],
            'NumeroCuenta'=>$row[4],
            'NumeroConstancia'=>$row[5],
            'PeriodoTributario'=>$row[6],
            'RucProveedor'=>$row[7],
            'NombreProveedor'=>$row[8],
            'TipoDocumentoAdquiriente'=>$row[9],
            'NumeroDocumentoAdquiriente'=>$row[10],
            'RazonSocialAdquiriente'=>$row[11],
            'FechaPago'=>$fecha1,
            'MontoDeposito'=>$row[13],
            'TipoBien'=>Validacion::Completarcomprobante($row[14],3),
            'TipoOperacion'=>$row[15],
            'TipoComprobante'=>$row[16],
            'SerieComprobante'=>$row[17],
            'NumeroComprobante'=>Validacion::Completarcomprobante($row[18],7),
            'NumeroPagoDetraciones'=>$row[19],
            'ValidacionPorcentual'=>$row[20],
            'Base'=>$row[21],
            'ValidacionBase'=>$row[22],
            'TipoServicio'=>$row[23],
        ]);
    }
}
