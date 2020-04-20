<?php

namespace App\Formatos;

use App\Clases\Almacenamiento;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Excelmuestreo extends Model
{
    static function aumentarcolumnasdefault($ruta,$id_muestreo,$id_archivo) {
        $spreadsheet = IOFactory::load($ruta);

        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->insertNewColumnBefore('A',2);

        $init = 2;
        $empty_cell = 0;
        $res = true;
        while(1) {
            $aux = $spreadsheet->getActiveSheet()->getCell('C'.$init)->getValue();
            $aux = trim($aux);

            if($empty_cell == 1){
                if ($aux == "" || $aux == null) {
                    break;
                }else{
                    $res = false;
                }
            }

            if($aux == "" || $aux == null) {
                $empty_cell++;
            }

            if($empty_cell == 0) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$init, $id_muestreo);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$init, $id_archivo);
            }
            $init++;
        }
        if($res){
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'IdMuestreo');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', 'IdArchivo');
            
            $writer = new Xlsx($spreadsheet);
            $writer->save($ruta);
        }else{
            echo "ERROR.";
        }
    }

    static function downloadExcel($json_data,$cell_order,$template_path) {
        //$json_data = '[{"NroDoc":"20510743271","cliente":"E.V. GRAFICA E.I.R.L.","IdUso":"1","IdArchivo":"41","Periodo":"20200100","Correlativo":"M2020001","FecEmision":"03.01.2020","FecVenci":null,"TipoComp":"01","NumSerie":"F001","AnoDua":"0","NumComp":"8000000002","NumTicket":"3833","TipoDoc":"6","BIAG1":"991.5","IGVIPM1":"178.47","BIAG2":"0","IGVIPM2":"0","BIAG3":"0","IGVIPM3":"0","AdqGrava":"0","ISC":"0","Otros":"0","Total":"1169.97","Moneda":"USD","TipoCam":"3.305","FecOrigenMod":null,"TipoCompMod":null,"NumSerieMod":null,"AnoDuaMod":null,"NumSerComOriMod":null,"FecConstDetrac":null,"NumConstDetrac":null,"Retencion":null,"ClasifBi":"1","Contrato":null,"ErrorT1":null,"ErrorT2":null,"ErrorT3":null,"ErrorT4":null,"MedioPago":null,"Estado":null},{"NroDoc":"20600667280","cliente":"BIZALAB S.A.C","IdUso":"1","IdArchivo":"41","Periodo":"20200100","Correlativo":"M2020006","FecEmision":"03.01.2020","FecVenci":null,"TipoComp":"01","NumSerie":"E001","AnoDua":"0","NumComp":"8000000017","NumTicket":"1128","TipoDoc":"6","BIAG1":"918.79","IGVIPM1":"165.38","BIAG2":"0","IGVIPM2":"0","BIAG3":"0","IGVIPM3":"0","AdqGrava":"0","ISC":"0","Otros":"0","Total":"1084.17","Moneda":"USD","TipoCam":"3.305","FecOrigenMod":null,"TipoCompMod":null,"NumSerieMod":null,"AnoDuaMod":null,"NumSerComOriMod":null,"FecConstDetrac":null,"NumConstDetrac":null,"Retencion":null,"ClasifBi":"5","Contrato":null,"ErrorT1":null,"ErrorT2":null,"ErrorT3":null,"ErrorT4":null,"MedioPago":null,"Estado":null},{"NroDoc":"20100281245","cliente":"ANDERS PERU S.A.C.","IdUso":"1","IdArchivo":"41","Periodo":"20200100","Correlativo":"M2020001","FecEmision":"06.01.2020","FecVenci":null,"TipoComp":"01","NumSerie":"F001","AnoDua":"0","NumComp":"8000000021","NumTicket":"32515","TipoDoc":"6","BIAG1":"1026.41","IGVIPM1":"184.75","BIAG2":"0","IGVIPM2":"0","BIAG3":"0","IGVIPM3":"0","AdqGrava":"0","ISC":"0","Otros":"0","Total":"1211.16","Moneda":"USD","TipoCam":"3.311","FecOrigenMod":null,"TipoCompMod":null,"NumSerieMod":null,"AnoDuaMod":null,"NumSerComOriMod":null,"FecConstDetrac":null,"NumConstDetrac":null,"Retencion":null,"ClasifBi":"1","Contrato":null,"ErrorT1":null,"ErrorT2":null,"ErrorT3":null,"ErrorT4":null,"MedioPago":null,"Estado":null},{"NroDoc":"20101052771","cliente":"IMPRESSO GRAFICA S A","IdUso":"1","IdArchivo":"41","Periodo":"20200100","Correlativo":"M2020001","FecEmision":"13.01.2020","FecVenci":null,"TipoComp":"01","NumSerie":"F001","AnoDua":"0","NumComp":"8000000189","NumTicket":"16832","TipoDoc":"6","BIAG1":"1150","IGVIPM1":"207","BIAG2":"0","IGVIPM2":"0","BIAG3":"0","IGVIPM3":"0","AdqGrava":"0","ISC":"0","Otros":"0","Total":"1357.00","Moneda":"PEN","TipoCam":"1","FecOrigenMod":null,"TipoCompMod":null,"NumSerieMod":null,"AnoDuaMod":null,"NumSerComOriMod":null,"FecConstDetrac":null,"NumConstDetrac":null,"Retencion":null,"ClasifBi":"1","Contrato":null,"ErrorT1":null,"ErrorT2":null,"ErrorT3":null,"ErrorT4":null,"MedioPago":null,"Estado":null}]';
        
        $array_data = json_decode($json_data, true);
        $spreadsheet = IOFactory::load($template_path);
        
        $cont_1 = 2;
    
        foreach ($array_data as $item) {
            $cont_2 = 0;
            foreach ($item as $cell_value) {
                $cell_id = $cell_order[$cont_2].$cont_1;
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($cell_id, $cell_value);
                $cont_2++;
            }
            $cont_1++;
        }

        $spreadsheet->getActiveSheet()->setTitle('Hoja 1');
    
        $spreadsheet->setActiveSheetIndex(0);
    /*
        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="TEST_FILE.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    */
        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();

        $username = Auth::user()->name;

        //Almacenamiento::guardarreportemuestrascompras($username,$content);
        $fruta = Storage::disk('public')->put('/muestreo/compras/'.$username.'/'.time().'_'.'mayorcompras/'.'reporte/', $content);
        return $fruta;
    }


}
