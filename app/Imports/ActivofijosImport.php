<?php

namespace App\Imports;

use App\Clases\Activos\Activofijo;
use App\Formatos\Excelmuestreo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ActivofijosImport implements ToModel, WithStartRow
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
    {   echo $row[17].','.$row[18].','.$row[33];
        $fecha1 = date_format(Date::excelToDateTimeObject($row[17]), 'd-m-Y');
        $fecha2 = date_format(Date::excelToDateTimeObject($row[18]), 'd-m-Y');
        $fecha3 = date_format(Date::excelToDateTimeObject($row[33]), 'd-m-Y');

        return new ActivoFijo([
            'IdUso'=> $row[0],
            'IdArchivo'=> $row[1],
            'Codigo'=> $row[2],
            'CuentaContable'=> $row[3],
            'Descipcion'=> $row[4],
            'Marca'=> $row[5],
            'Modelo'=> $row[6],
            'NumeroSeriePlaca'=> $row[7],
            'CostoFin'=> $row[8],
            'Adquisicion'=> $row[9],
            'Mejoras'=> $row[10],
            'RetirosBajas'=> $row[11],
            'Otros'=> $row[12],
            'ValorHistorico'=> $row[13],
            'AjusteInflacion'=> $row[14],
            'ValorAjustado'=> $row[15],
            'CostoNetoIni'=> $row[16],
            'FecAdquisicion'=> $fecha1,
            'FecInicio'=> $fecha2,
            'Metodo'=> $row[19],
            'NroDoc'=> $row[20],
            'PorcDepreciacion'=> $row[21],
            'DepreAcumulada'=> $row[22],
            'DepreEjercicio'=> $row[23],
            'DepreRelacionada'=> $row[24],
            'DepreOtros'=> $row[25],
            'DepreHistorico'=> $row[26],
            'DepreAjusInflacion'=> $row[27],
            'DepreAcuInflacion'=> $row[28],
            'CostoHistorico'=> $row[29],
            'DepreAcuTributaria'=> $row[30],
            'CostoNetoIniTributaria'=> $row[31],
            'DepreEjercicioTributaria'=> $row[32],
            'FecBaja'=> $fecha3,
        ]);
    }
}
