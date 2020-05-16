<?php

namespace App\Formatos;

use App\Clases\Modelosgenerales\Comprobante;
use Illuminate\Database\Eloquent\Model;

class Validacion extends Model
{
    public static function Completarcomprobante($comprobante,$val){
        if(strlen($comprobante)<$val)
        {
            $faltantes = $val - strlen($comprobante);
            for ($i=0; $i < $faltantes; $i++) { 
                $comprobante = '0'.$comprobante;
            }
        }
        return $comprobante;
    }

    public static function Importar($filename, $delimiter=',',$rules) {
        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;
    
        $data = array();
        $data2 = array();
        $regex = "";
        $regex_numeric = "#[^0-9]#";
        $regex_alfanumeric = "/^[a-zA-Z\s\d]+$/";
        $regex_alfa = "/^[a-zA-Z\s]+$/";
        $regex_date = "/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/";
        
        $rules = json_decode($rules);
        //return $rules[0]->tipo;
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
            {
                array_push($data2, $row); 
                for ($i=0; $i < count($rules); $i++) { 
                    $orden = $rules[$i]->orden;
                    $tipo = $rules[$i]->tipo;
                    $minimo = $rules[$i]->minimo;
                    $maximo = $rules[$i]->maximo;
                    $estatico = $rules[$i]->estatico;
                    $mensaje = "";

                    //$regex = "";
                    if($tipo == "NUMERICO") {
                        $regex = $regex_numeric;
                        $bum = $row[$orden];
                        $flag = Util::Validarnumero($bum);
                        if(is_integer($flag))
                        {
                            $flag = Util::Validarcantidad($flag,$minimo,$maximo,$estatico);
                        }
                        else
                        {
                            $row[$orden] = $flag;
                        }
                    }
                    if($tipo == "ALFANUMERICO")
                    {
                        $regex = $regex_alfanumeric;
                        $mensaje = "DEBE CONTENER SOLO NUMEROS (SIN COMAS)";
                    }
                    if($tipo == "ALFABETICO")
                    {
                        $regex = $regex_alfa;
                        $mensaje = "DEBE CONTENER SOLO LETRAS";
                    }
                    if($tipo == "FECHA")
                    {
                        $regex = $regex_date;
                        $mensaje = "FORMATO DE FECHA ESPERADO DD/MM/YYYY";
                    }
                    
                }
                array_push($data, $row);
            }
            fclose($handle);
        }

        return ['val'=>$data,'data'=>$data2];
    }

}
