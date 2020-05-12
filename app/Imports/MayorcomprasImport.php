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
            'Orden'=> $row[4],
            'FecEmision'=> $row[5],
            'FecVenci'=> $row[6],
            'TipoComp'=> $row[7],
            'NumSerie'=> $row[8],
            'AnoDua'=> $row[9],
            'NumComp'=> $row[10],
            'NumTicket'=> $row[11],
            'TipoDoc'=> $row[12],
            'NroDoc'=> $row[13],
            'Nombre'=> $row[14],
            'BIAG1'=> $row[15],
            'IGVIPM1'=> $row[16],
            'BIAG2'=> $row[17],
            'IGVIPM2'=> $row[18],
            'BIAG3'=> $row[19],
            'IGVIPM3'=> $row[20],
            'AdqGrava'=> $row[21],
            'ISC'=> $row[22],
            'Otros'=> $row[23],
            'Total'=> $row[24],
            'Moneda'=> $row[25],
            'TipoCam'=> $row[26],
            'FecOrigenMod'=> $row[27],
            'TipoCompMod'=> $row[28],
            'NumSerieMod'=> $row[29],
            'AnoDuaMod'=> $row[30],
            'NumSerComOriMod'=> $row[31],
            'FecConstDetrac'=> $row[32],
            'NumConstDetrac'=> $row[33],
            'Retencion'=> $row[34],
            'ClasifBi'=> $row[35],
            'Contrato'=> $row[36],
            'ErrorT1'=> $row[37],
            'ErrorT2'=> $row[38],
            'ErrorT3'=> $row[39],
            'ErrorT4'=> $row[40],
            'MedioPago'=> $row[41],
            'Estado'=> $row[42]
        ]);
    }
}
