<?php

namespace App\Imports;

use App\Mayorgasto;
use Maatwebsite\Excel\Concerns\ToModel;

class MayorgastosImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
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
            'TipoDoc2'=> $row[9],
            'NumSerie'=> $row[11],
            'NumComp'=> $row[12],
            'FecEmision'=> $row[13],
            'FecVenci'=> $row[14],
            'FecOperacion'=> $row[15],
            'Glosa1'=> $row[16],
            'Glosa2'=> $row[17],
            'Debe'=> $row[18],
            'Haber'=> $row[19],
            'RefenciaCompraVenta'=> $row[20],
            'IndOP'=> $row[21],
            'Diferenciar'=> $row[22], 
        ]);
    }
}
