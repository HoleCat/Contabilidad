<?php

namespace App\Imports;

use App\Mayorgasto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MayorgastosImport implements ToModel, WithStartRow
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
        return new Mayorgasto([
            'IdUso'=>$row[0],
            'IdArchivo'=>$row[1],
            'Periodo'=> $row[2],
            'CUO'=> $row[3],
            'AMC'=> $row[4],
            'Cuenta'=> $row[5],
            'Unid_Econ'=> $row[6],
            'CentroCosto'=> $row[7],
            'Moneda'=> $row[8],
            'TipoDoc1'=> $row[9],
            'Numero'=> $row[10],
            'TipoDoc2'=> $row[11],
            'NumSerie'=> $row[12],
            'NumComp'=> $row[13],
            'FecEmision'=> $row[14],
            'FecVenci'=> $row[15],
            'FecOperacion'=> $row[16],
            'Glosa1'=> $row[17],
            'Glosa2'=> $row[18],
            'Debe'=> $row[19],
            'Haber'=> $row[20],
            'RefenciaCompraVenta'=> $row[21],
            'IndOP'=> $row[22],
            'Diferenciar'=> $row[23]
        ]);
    }
}
