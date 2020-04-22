<?php

namespace App\Imports;

use App\Mayorventa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MayorventasImport implements ToModel, WithStartRow
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
        return new Mayorventa([
            'IdUso'=>$row[0],
            'IdArchivo'=>$row[1],
            'Periodo'=> $row[2],
            'Correlativo'=> $row[3],
            'Ordenado'=> $row[4],
            'FecEmision'=> $row[5],
            'FecVenci'=> $row[6],
            'TipoComp'=> $row[7],
            'NumSerie'=> $row[8],
            'NumComp'=> $row[9],
            'NumTicket'=> $row[10],
            'TipoDoc'=> $row[11],
            'NroDoc'=> $row[12],
            'Nombre'=> $row[13],
            'Export'=> $row[14],
            'BI'=> $row[15],
            'Desci'=> $row[16],
            'IGVIPMBI'=> $row[17],
            'IGVIPMDesc'=> $row[18],
            'ImporteExo'=> $row[19],
            'ImporteIna'=> $row[20],
            'ISC'=> $row[21],
            'BIIGVAP'=> $row[22],
            'IGVAP'=> $row[23],
            'Otros'=> $row[24],
            'Total'=> $row[25],
            'Moneda'=> $row[26],
            'TipoCam'=> $row[27],
            'FecOrigenMod'=> $row[28],
            'TipoCompMod'=> $row[29],
            'NumSerieMod'=> $row[30],
            'NumDocMod'=> $row[31],
            'Contrato'=> $row[32],
            'ErrorT1'=> $row[33],
            'MedioPago'=> $row[34],
            'Estado'=> $row[35],
        ]);
    }
}
