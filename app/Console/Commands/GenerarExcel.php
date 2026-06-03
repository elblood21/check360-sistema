<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Storage;
use App\Models\Carpeta;
use App\Models\CarpetaRespuestas;
use App\Models\Requerimiento;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class GenerarExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generar-excel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $carpetas = Carpeta::where('estado_id', 1)
        ->orderBy('id','DESC')
        ->get();

        foreach($carpetas as $carpeta) {
            $titulos_seccion = [];
            $titulos_requerimientos = [];
            $preguntas_seccion = [];
            $promedios_seccion = [];
            $resultado_seccion = [];
            $data = [
                1=> [ 0=>'', 1=>'', 2=>'',3=>''],
                2=> [ 0=>'', 1=>'Requisitos Normativos', 2=>'',3=>''],
                3=> [ 0=>'',1=> '1. INDICADOR DE GESTIÓN DOCUMENTAL', 2=>'',3=>''],
            ];

            $requerimientos = CarpetaRespuestas::where('carpeta_respuestas.carpeta_id', $carpeta->id)
            ->select('requerimientos.id','requerimientos.step','requerimientos.name')
            ->join('requerimientos','requerimientos.id','=','requerimiento_id')
            ->orderBy('requerimientos.step','ASC')
            ->groupBy('requerimientos.id','requerimientos.name','requerimientos.step')->get();

            $promedios = [];

            foreach($requerimientos as $kk=>$requerimiento) {
                $promedio_requerimiento = [];
                $steps = CarpetaRespuestas::where('carpeta_id', $carpeta->id)
                ->select('requerimiento_steps.step','requerimiento_steps.name','requerimiento_steps.step')
                ->where('carpeta_respuestas.requerimiento_id', $requerimiento->id)
                ->join('requerimiento_steps','requerimiento_steps.step','=','carpeta_respuestas.step')
                ->orderBy('carpeta_respuestas.step','ASC')
                ->groupBy('requerimiento_steps.step','requerimiento_steps.name','requerimiento_steps.step')->get();

                if($kk > 0)
                    $data[] = [0=>'',1=>'',2=>'',3=>''];
                $data[] = [0=>'',1=>$requerimiento->step.' '.$requerimiento->name,2=>'',3=>''];
                $titulos_requerimientos[] = COUNT($data);
                $data[] = [0=>'',1=>'',2=>'',3=>''];

                $first_step_row = null;
                foreach($steps as $kkk=>$step) {
                    $promedio_subseccion = [];
                    if($kkk > 0)
                        $data[] = [0=>'',1=>'',2=>'',3=>''];
                    else {
                        $first_step_row = COUNT($data)+1;
                    }
                    $data[] = [0=>'',1=>$step->step.' '.$step->name,2=>'Calificación',3=>'Observacion'];
                    $titulos_seccion[] = COUNT($data);
                    $preguntas = CarpetaRespuestas::where('carpeta_id', $carpeta->id)
                    ->where('requerimiento_id', $requerimiento->id)
                    ->where('step', $step->step)
                    ->orderBy('pregunta_id','ASC')->get();

                    foreach($preguntas as $pregunta) {
                        if($pregunta->respuesta) {
                            $promedio_subseccion[] = $pregunta->respuesta;
                            $promedio_requerimiento[] = $pregunta->respuesta;
                        }
                        $data[] = [0=>'',1=>$pregunta->pregunta,2=>$pregunta->respuesta."%",3=>$pregunta->observacion];
                        $preguntas_seccion[] = COUNT($data);
                    }
                    if($promedio_subseccion && count($promedio_subseccion) > 0)
                        $promedio_subseccion = number_format(array_sum($promedio_subseccion) / count($promedio_subseccion), 0) . "%";
                    else
                        $promedio_subseccion = '';
                    $data[] = [0=>'',1=>'Promedio cumplimiento subsección',2=>$promedio_subseccion, 3=>''];
                    $promedios_seccion[] = COUNT($data);
                }
                if($promedio_requerimiento && count($promedio_requerimiento) > 0)
                    $promedio_requerimiento = (array_sum($promedio_requerimiento) / count($promedio_requerimiento))."%";
                else
                    $promedio_requerimiento = '';
                $promedios[$requerimiento->id] = $promedio_requerimiento;
                $data[$first_step_row] = [...$data[$first_step_row], 4=>'',5=>'Cumplimiento sección',6 => 'Optimo', 7=>'Brecha'];
                $data[$first_step_row+1] = [...$data[$first_step_row+1], 4=>'',5=>number_format(intval($promedio_requerimiento),0)."%",6 => "100%", 7=>100-intval($promedio_requerimiento)."%"];
                $resultado_seccion[] = $first_step_row;
            }
            $collection = collect($data);

            $export = new class($collection,$titulos_seccion, $titulos_requerimientos,$preguntas_seccion, $promedios_seccion, $resultado_seccion) implements FromCollection, WithStyles {
                protected $data;
                protected $titulos_seccion;
                protected $titulos_requerimientos;
                protected $preguntas_seccion;
                protected $promedios_seccion;
                protected $resultado_seccion;
                public function __construct(Collection $data,$titulos_seccion, $titulos_requerimientos, $preguntas_seccion, $promedios_seccion, $resultado_seccion) {
                    $this->data = $data;
                    $this->titulos_seccion = $titulos_seccion;
                    $this->titulos_requerimientos = $titulos_requerimientos;
                    $this->preguntas_seccion = $preguntas_seccion;
                    $this->promedios_seccion = $promedios_seccion;
                    $this->resultado_seccion = $resultado_seccion;
                }

                public function collection() {
                    return $this->data;
                }

                public function styles(Worksheet $sheet)
                {
                    $sheet->getDefaultRowDimension()->setRowHeight(-1);
                    $sheet->setShowGridlines(false);
                    $sheet->getColumnDimension('A')->setWidth(5);
                    $sheet->getColumnDimension('B')->setWidth(30);
                    $sheet->getColumnDimension('C')->setWidth(15);
                    $sheet->getColumnDimension('D')->setWidth(40);
                    $sheet->getColumnDimension('E')->setWidth(11);
                    $sheet->getColumnDimension('F')->setWidth(20);
                    $sheet->getColumnDimension('G')->setWidth(12);
                    $sheet->getColumnDimension('H')->setWidth(12);

                    $sheet->getStyle('B2')->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 16,
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                    ]);

                    $sheet->getStyle('B3')->applyFromArray([
                        'font' => [
                            'size' => 13,
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_BOTTOM
                        ],
                    ]);

                    foreach($this->titulos_requerimientos as $c) {
                        $sheet->getStyle('B'.$c)->applyFromArray([
                            'font' => [
                                'size' => 13,
                                'bold' => true
                            ],
                            'alignment' => [
                                'vertical' => Alignment::VERTICAL_BOTTOM
                            ],
                        ]);
                    }

                    foreach($this->titulos_seccion as $c) {
                        $sheet->getStyle('B'.$c.':D'.$c)->applyFromArray([
                            'font' => [
                                'size' => 11,
                                'color' => ['rgb' => '843C0B'],
                                'bold' => true
                            ],
                            'alignment' => [
                                'vertical' => Alignment::VERTICAL_BOTTOM
                            ],
                        ]);
                    }

                    foreach($this->preguntas_seccion as $k=>$c) {
                        $esPar = ($k % 2 == 0) ? true : false;

                        $texto = $sheet->getCell("B{$c}")->getValue();
                        $length = strlen($texto);

                        if ($length <= 35) {
                            $height = 13;
                        } elseif ($length <= 70) {
                            $height = 26;
                        } elseif ($length <= 105) {
                            $height = 39;
                        } elseif ($length <= 140) {
                            $height = 52;
                        } else if($length <= 175) {
                            $height = 65;
                        } else if($length <= 210) {
                            $height = 78;
                        } else {
                            $height = 90;
                        }

                        $sheet->getRowDimension($c)->setRowHeight($height);

                        $sheet->getStyle('B'.$c.':D'.$c)->applyFromArray([
                            'font' => [
                                'size' => 10,

                            ],
                            'alignment' => [
                                'vertical' => Alignment::VERTICAL_CENTER,
                                'wrapText' => true,
                                'horizontal' => Alignment::HORIZONTAL_LEFT
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => [
                                    'rgb' => $esPar ? 'F2F2F2' : 'DEEBF7',
                                ],
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['rgb' => '000000'], // negro
                                ],
                            ],
                        ]);

                        $sheet->getStyle('C'.$c)->applyFromArray([
                            'font' => [
                                'size' => 10,
                            ],
                            'alignment' => [
                                'vertical' => Alignment::VERTICAL_CENTER,
                                'wrapText' => true,
                                'horizontal' => Alignment::HORIZONTAL_RIGHT
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => [
                                    'rgb' => 'C5E0B4'
                                ],
                            ],
                        ]);
                    }

                    foreach($this->promedios_seccion as $k=>$c) {
                        $sheet->getRowDimension($c)->setRowHeight(20);
                        $esPar = ($k % 2 == 0) ? true : false;
                        $sheet->getStyle('B'.$c.':D'.$c)->applyFromArray([
                            'font' => [
                                'size' => 10,
                                'bold' => true,
                                'color' => ['rgb' => '000000']
                            ],
                            'alignment' => [
                                'vertical' => Alignment::VERTICAL_CENTER,
                                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                                'wrapText' => true
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => [
                                    'rgb' => $esPar ? 'F2F2F2' : 'DEEBF7',
                                ],
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['rgb' => '000000'],
                                ],
                            ],
                        ]);

                        $sheet->getStyle('C'.$c)->applyFromArray([
                            'font' => [
                                'size' => 10,
                                'bold' => true
                            ],
                            'alignment' => [
                                'vertical' => Alignment::VERTICAL_CENTER,
                                'wrapText' => true
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => [
                                    'rgb' => 'C5E0B4'
                                ],
                            ],
                        ]);
                    }

                    foreach($this->resultado_seccion as $k=>$c) {
                        $sheet->getStyle('F'.$c.':H'.$c)->applyFromArray([
                            'font' => [
                                'size' => 10,
                                'bold' => true,
                                'color' => ['rgb' => '843C0B']
                            ],
                            'alignment' => [
                                'vertical' => Alignment::VERTICAL_CENTER,
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                'wrapText' => true
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => [
                                    'rgb' => 'C5E0B4',
                                ],
                            ]
                        ]);

                        $sheet->getStyle('F'.($c+1).':H'.($c+1))->applyFromArray([
                            'font' => [
                                'size' => 10,
                                'color' => ['rgb' => 'black']
                            ],
                            'alignment' => [
                                'vertical' => Alignment::VERTICAL_CENTER,
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                'wrapText' => true
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => [
                                    'rgb' => 'C5E0B4',
                                ],
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['rgb' => '000000'],
                                ],
                            ],
                        ]);
                    }

                    return [];
                }

            };

            $excelStream = Excel::raw($export, ExcelFormat::XLSX);

            Storage::put('excel/output.xlsx', $excelStream);

           // $carpeta->excel = 'excel/carpeta_'.$carpeta->id.'.xlsx';
            //$carpeta->save();
        }
    }
}
