<?php

namespace App\Imports;

use App\Mayorcompra;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MayorcomprasImport implements ToModel, WithStartRow
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
        return new Mayorcompra([
            'IdUso'=>$row[0],
            'IdArchivo'=>$row[1],
            'Periodo'=> $row[2],
            'Correlativo'=> $row[3],
            'FecEmision'=> $row[4],
            'FecVenci'=> $row[5],
            'TipoComp'=> $row[6],
            'NumSerie'=> $row[7],
            'AnoDua'=> $row[8],
            'NumComp'=> $row[9],
            'NumTicket'=> $row[10],
            'TipoDoc'=> $row[11],
            'NroDoc'=> $row[12],
            'Nombre'=> $row[13],
            'BIAG1'=> $row[14],
            'IGVIPM1'=> $row[15],
            'BIAG2'=> $row[16],
            'IGVIPM2'=> $row[17],
            'BIAG3'=> $row[18],
            'IGVIPM3'=> $row[19],
            'AdqGrava'=> $row[20],
            'ISC'=> $row[21],
            'Otros'=> $row[22],
            'Total'=> $row[23],
            'Moneda'=> $row[24],
            'TipoCam'=> $row[25],
            'FecOrigenMod'=> $row[26],
            'TipoCompMod'=> $row[27],
            'NumSerieMod'=> $row[28],
            'AnoDuaMod'=> $row[29],
            'NumSerComOriMod'=> $row[30],
            'FecConstDetrac'=> $row[31],
            'NumConstDetrac'=> $row[32],
            'Retencion'=> $row[33],
            'ClasifBi'=> $row[34],
            'Contrato'=> $row[35],
            'ErrorT1'=> $row[36],
            'ErrorT2'=> $row[37],
            'ErrorT3'=> $row[38],
            'ErrorT4'=> $row[39],
            'MedioPago'=> $row[40],
            'Estado'=> $row[41]
        ]);
    }
}
