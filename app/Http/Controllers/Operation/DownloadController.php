<?php

namespace App\Http\Controllers\Operation;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VlvReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DownloadController extends Controller
{
    public function rpt_paloteo(){
        $dateinit = date('Y-m-d H:i');
        $dateend = date('Y-m-d H:i');
        $users = User::where('team_id',1)
                        ->get();
        return view('download.menu',[
            'dateinit'  => $dateinit,
            'dateend'   => $dateend,
            'users'     => $users
        ]);
    }

    public function rpt_paloteo_post(Request $request){
        $request->validate([
            'user_id'   => 'required',
            'dateinit'  => 'required',
            'dateend'   => 'required',
        ]);
        $di = Carbon::parse($request->dateinit)->format('Y-m-d H:i:00');
        $de = Carbon::parse($request->dateend)->format('Y-m-d H:i:59');
        $result = VlvReport::where(function($query) use ($request){
                                if($request->user_id != 0){
                                    $query->whereCreatedBy($request->user_id);
                                }
                            })
                            ->whereBetween('created_at',["$di","$de"])
                            #->where(function($query) use ($request){
                            #    $query->whereDate('created_at','<=',$de);  
                            #    $query->whereDate('created_at','>=',$di);  
                            #})
                            ->get();
        $filename = 'paloteo_' . date("_Ymd_His");
        return response()->streamDownload(function () use ($result,$request) {
            $spreadsheet = new Spreadsheet;
            $sheet       = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'PARAM=> '.$request->dateinit.' hasta '.$request->dateend .' / user('.$request->user_id.')');
            $sheet->setCellValue('A2', 'FECHA');
            $sheet->setCellValue('B2', 'HORA');
            $sheet->setCellValue('C2', 'KEY');
            $sheet->setCellValue('D2', 'ASESOR');
            $sheet->setCellValue('E2', 'USUARIO ASESOR');
            $sheet->setCellValue('F2', 'DNI ASESOR');
            $sheet->setCellValue('G2', 'CAMPAÑA');
            $sheet->setCellValue('H2', 'NODO');
            $sheet->setCellValue('I2', 'CLIENTE');
            $sheet->setCellValue('J2', 'NUMERO ENTRADA');
            $sheet->setCellValue('K2', 'INCIDENCIA');
            $sheet->setCellValue('L2', 'TIPO INCIDENCIA');
            $sheet->setCellValue('M2', 'MES QUE AFECTA');
            $sheet->setCellValue('N2', 'COMENTARIO');
            $sheet->setCellValue('O2', 'MOTIVO');
            $sheet->setCellValue('P2', 'SUB-MOTIVO');
            $sheet->setCellValue('Q2', 'PROGRAMA');
            $sheet->getStyle('A2:Q2')->applyFromArray(['font' => ['bold' => true]]);
            $sheet->getStyle('A2:Q2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('dcdcdc');
            $key=2;
            foreach($result as $item){
                $key++;
                $sheet->setCellValue("A$key", Carbon::parse($item->created_at)->format('d/m/Y'));
                $sheet->setCellValueExplicit("B$key", Carbon::parse($item->created_at)->format('H:i:s'),\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                
                $sheet->setCellValue("C$key", '');
                $sheet->setCellValue("D$key", $item->lastname);
                $sheet->setCellValue("E$key", $item->name);
                $sheet->setCellValue("F$key", $item->documentno2);
                $sheet->setCellValue("G$key", $item->program);
                $sheet->setCellValueExplicit("H$key", $item->nodo,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("I$key", $item->documentno,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("J$key", $item->did,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue("K$key", ($item->isincidencia == 'Y' ? 'SI' : 'NO'));
                switch($item->incidencia_id){
                    case 1:$m = 'Emisión Incorrecta de recibos';break;
                    case 2:$m = 'Suspensión por error de sistema';break;
                    case 3:$m = 'Masivo declarado caída de nodo';break;
                    case 4:$m = 'Masivo declarado caída de SLA';break;
                    case 5:$m = 'Caída de CRM Experiencia';break;
                    default:$m='';
                }
                $sheet->setCellValue("L$key", $m);
                switch($item->month){
                    case '01':$m = 'ENE';break;
                    case '02':$m = 'FEB';break;
                    case '03':$m = 'MAR';break;
                    case '04':$m = 'ABR';break;
                    case '05':$m = 'MAY';break;
                    case '06':$m = 'JUN';break;
                    case '07':$m = 'JUL';break;
                    case '08':$m = 'AGO';break;
                    case '09':$m = 'SET';break;
                    case '10':$m = 'OCT';break;
                    case '11':$m = 'NOV';break;
                    case '12':$m = 'DIC';break;
                    default:$m='';
                }
                $sheet->setCellValue("M$key", $m);
                $sheet->setCellValue("N$key", $item->comment);
                $sheet->setCellValue("O$key", $item->reason);
                $sheet->setCellValue("P$key", $item->subreason);
                $sheet->setCellValue("Q$key", $item->program);
            }
            $cols = explode(',','B,C,D,E,F,G');
            foreach($cols as $col){
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            (new Xlsx($spreadsheet))->save('php://output');
        }, "{$filename}.xlsx");
    }
}
