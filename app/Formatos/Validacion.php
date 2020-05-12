<?php

namespace App\Formatos;

use App\Clases\Modelosgenerales\Comprobante;
use Illuminate\Database\Eloquent\Model;

class Validacion extends Model
{
    public static function Importar($filename, $delimiter=',',$rules) {
        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;
    
        $data = array();
        $data2 = array();
        $regex = "";
        $regex_numeric = "/^[d\.]+$/";
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
                        $mensaje = "DEBE CONTENER SOLO NUMEROS";
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
                    
                    if(preg_match($regex,$row[$orden]))
                    {
                        if($maximo!='' && $minimo!='')
                        {
                            if(strlen($row[$orden])>=$minimo && strlen($row[$orden])<=$maximo){
                                $row[$orden] = $row[$orden];
                            } else {
                                $row[$orden] = 'ERROR EN EL LARGO DEL CONTENIDO DEBE SER MAYOR A '.$minimo.'Y MENOR A '.$maximo;    
                            }
                        }
                        else if($minimo!='' && $maximo=='')
                        {
                            if(strlen($row[$orden])>=$minimo){
                                $row[$orden] = $row[$orden];
                            } else {
                                $row[$orden] = 'ERROR EN EL LARGO DEL CONTENIDO DEBE SER MAYOR A '.$minimo;
                            }
                        }
                        else if($estatico!='')
                        {
                            if(strlen($row[$orden])==$estatico){
                                $row[$orden] = $row[$orden];
                            } else {
                                $row[$orden] = 'ERROR EN EL LARGO DEL CONTENIDO DEBE SER '.$estatico;
                            }
                        }
                        
                    } else {
                        $row[$orden] = $mensaje;
                    }
                }
                array_push($data, $row);
            }
            fclose($handle);
        }

        return $data;
    }

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
}
