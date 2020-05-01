<?php

namespace App\Formatos;

use Illuminate\Database\Eloquent\Model;

class Validacion extends Model
{
    public static function Importar($filename, $delimiter=',',$rules) {
        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;
    
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
            {
                $regex_numeric = "/^[1-9\.]+$/";
                $regex_alfanumeric = "/^[a-zA-Z\s\d]+$/";
                $regex_alfa = "/^[a-zA-Z\s]+$/";
                $regex_date = "/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/";
                for ($i=0; $i < $rules; $i++) { 
                    $orden = $rules[$i]['orden'];
                    $tipo = $rules[$i]['tipo'];
                    $minimo = $rules[$i]['minimo'];
                    $maximo = $rules[$i]['maximo'];
                    $estatico = $rules[$i]['estatico'];
                    $mensaje = $rules[$i]['contenido'];

                    $regex = "";
                    if($tipo == "NUMERICO") {
                        $regex = $regex_numeric;
                        $mensaje = "DEBE CONTENER SOLO NUMEROS";
                    } else if($tipo == "ALFANUMERICO")
                    {
                        $regex = $regex_alfanumeric;
                        $mensaje = "DEBE CONTENER SOLO NUMEROS (SIN COMAS)";
                    } else if($tipo == "ALFABETICO")
                    {
                        $regex = $regex_alfa;
                        $mensaje = "DEBE CONTENER SOLO LETRAS";
                    } else if($tipo == "FECHA")
                    {
                        $regex = $regex_date;
                        $mensaje = "FORMATO DE FECHA ESPERADO DD/MM/YYYY";
                    }
                    
                    if(preg_match($regex,$row[$orden]))
                    {
                        if(strlen($row[$orden])>=$minimo && strlen($row[$orden])<=$maximo){
                            $row[$orden] = $row[$orden];
                        } else {
                            if($maximo!='')
                            {
                                $row[$orden] = 'ERROR EN EL LARGO DEL CONTENIDO DEBE SER MAYOR A '.$minimo.'Y MENOR A '.$maximo;
                            }
                            else if($minimo!='')
                            {
                                $row[$orden] = 'ERROR EN EL LARGO DEL CONTENIDO DEBE SER MAYOR A '.$minimo;
                            }
                            else if($estatico!='')
                            {
                                $row[$orden] = 'ERROR EN EL LARGO DEL CONTENIDO DEBE SER '.$estatico;
                            }
                        }
                    } else {
                        $row[0] = $mensaje;
                    }
                }
                array_push($data, $row);
            }
            fclose($handle);
        }
        return $data;
    }
}
