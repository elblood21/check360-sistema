<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Carpeta;
use App\Models\CarpetaRespuestas;
use App\Models\CarpetaInforme;
use App\Models\Requerimiento;
use Illuminate\Support\Facades\Http;

class GenerarPreInforme extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generar-pre-informe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private function prompt($requerimientos,$trabajador,$cliente) {
        $prompt = "
        INSTRUCCIÓN PRINCIPAL  
        Quiero que actúes como la mejor asistente legal de un estudio jurídico en Chile y generes un **informe de cumplimiento laboral** profesional, visual y persuasivo, basado en un archivo Excel de evaluación.  
        El informe debe tener una estructura legal sólida, análisis normativo detallado, orientación comercial sutil, diseño gráfico atractivo y uso correcto de los colores corporativos.  
        Utiliza un lenguaje formal, técnico y alineado con la normativa laboral chilena vigente.

        NIVEL DE REDACCIÓN  
        Cada sección debe estar redactada con claridad, profundidad y precisión jurídica.  
        Evita frases genéricas. Usa lenguaje preventivo, analítico y útil para la toma de decisiones.

        ### Resumen Ejecutivo debe incluir:
        - El objetivo del informe
        - El enfoque normativo utilizado
        - El resultado general del cumplimiento
        - Los riesgos jurídicos derivados de las brechas

        ### Recomendaciones deben fundamentarse en:
        - Artículos del Código del Trabajo (Art. 9, 10, 33, 54, 58, etc.)
        - Dictámenes de la Dirección del Trabajo
        - Ley N° 21.561 (Ley Bustos)
        - Decreto Supremo N° 14/2023 (Libro Electrónico de Remuneraciones)

        ---

        ### PARÁMETROS GENERALES DEL INFORME

        1. **Título**: INFORME LEGAL DE CUMPLIMIENTO LABORAL  
        2. **Formato**: Word (.docx) y opcionalmente PDF  
        3. **Colores corporativos**:
        - Dorado suave: #E2D3AF
        - Azul oscuro: #0C2340  
        4. **Tipografía y estilo**:
        - Títulos: Cambria 14 pt, negrita
        - Texto: Calibri 11 pt
        - Márgenes: 2,5 cm por lado  
        5. **Logo corporativo**:
        - Inserta el logo en el encabezado (esquina superior izquierda)
        - Asegura proporción y fondo blanco  
        6. **Diseño visual y profesional**:
        - Gráficos con colores institucionales, etiquetas y leyendas claras
        - Iconos ilustrativos por sección (ej: 📄, ⚖️, 📊)
        - Secciones separadas por líneas, banners o bloques visuales
        - Cajas destacadas para recomendaciones o frases clave

        ---

        ### FICHA DE IDENTIFICACIÓN (CON DISEÑO)

        Incluye al inicio del informe una tabla con diseño visual (celda izquierda dorado suave con texto azul oscuro) con los siguientes datos:

        **Empresa Evaluada**:
        - Nombre: {$cliente->razon_social}
        - RUT: {$cliente->rut}
        - Giro: {$cliente->giro}
        - Domicilio: {$cliente->direccion}
        - Representante Legal: {$cliente->representante_nombre}
        - Correo electrónico: {$cliente->representante_email}

        **Trabajador Evaluado (si corresponde)**:
        - Nombre completo: {$trabajador['nombre']}
        - RUT: {$trabajador['rut']}
        - Cargo: {$trabajador['cargo']}
        - Fecha de ingreso: {$trabajador['fecha_ingreso']}
        - Tipo de contrato: {$trabajador['tipo_contrato']}

        ---

        ### DATOS Y CÁLCULOS

        Documento Evaluado           | Valor";

        foreach($requerimientos as $requerimiento) {
            $prompt .= "
            {$requerimiento['nombre']}       | {$requerimiento['promedio']}";
        }

        $prompt .= "
        
        - Calcula el cumplimiento promedio general.
        - Calcula la brecha normativa como 100% - cumplimiento.
        - Excluye valores vacíos o inválidos con justificación.

        ---

        ### ESTRUCTURA DEL INFORME (CON ICONOS Y ESTILO VISUAL)

        1. Ficha de Identificación  
        2. Resumen Ejecutivo  
        3. Porcentaje de Cumplimiento General  
        4. Gráfico de Barras (por documento)  
        5. Análisis de Brechas y Riesgos Detectados  
        6. Recomendaciones Legales Prioritarias  
        7. Fundamento Normativo  
        8. Llamado a la Acción Comercial  
        9. Conclusión Ejecutiva  

        ---

        ### OBJETIVO FINAL

        Este documento debe cumplir una doble función:

        1. Ser un diagnóstico técnico legal confiable y visualmente atractivo.  
        2. Ser una herramienta de generación de leads para **Check 360**.

        ---

        ### IMPORTANTE: SALIDA

        La respuesta debe ser un **JSON** estructurado con las siguientes claves exactas:
        {
        'ficha_identificacion': 'Texto...',
        'resumen_ejecutivo': 'Texto...',
        'porcentaje_cumplimiento_general': 'Texto...',
        'grafico_barras': {
            'cumplimiento_general': 0, // e porcentaje del 100%
            'brecha': 0 // el restante de 100% de cumplimiento
        },
        'analisis_brechas_riesgos': 'Texto...',
        'recomendaciones_legales': 'Texto...',
        'fundamento_normativo': 'Texto...',
        'llamado_a_accion': 'Texto...',
        'conclusion': 'Texto...'
        }
    ";
    return $prompt;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $carpetas = Carpeta::where('estado_id', 2)
        ->with(['respuestas','trabajador','cliente'])
        ->orderBy('id','DESC')
        ->get();

        foreach($carpetas as $carpeta) {

            $carpeta->estado_id = 3;
            $carpeta->save();

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
            }
            
            $promedios_requerimientos = [];
            foreach($promedios as $k=>$p) {
                $promedios_requerimientos[$k] = ['promedio'=>$p, 'requerimiento_id'=>$k, 'nombre' => Requerimiento::find($k)->name];
            }

            $contentGPT = $this->prompt(
                $promedios_requerimientos,
                [
                    'nombre'=> $carpeta->trabajador->name ?? '',
                    'rut'=> $carpeta->trabajador->rut ?? '',
                    'cargo'=> $carpeta->trabajador->cargo ?? '',
                    'fecha_ingreso'=> isset($carpeta->trabajador->fecha_ingreso) ? date('Y-m-d',strtotime($carpeta->trabajador->fecha_ingreso)) : '',
                    'tipo_contrato'=> $carpeta->trabajador->tipo_contrato ?? '',
                ],
                $carpeta->cliente
            );

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type'  => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $contentGPT
                    ]
                ],
                'temperature' => 0.7,
            ]);
    
            $contenido = $response->json()['choices'][0]['message']['content'];
            $contenido = trim($contenido);
            $contenido = preg_replace('/^```json|```$/', '', $contenido);
            $preinforme = json_decode($contenido, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                continue;
            }
            $resumen_ejecutivo = $preinforme['resumen_ejecutivo'] ?? '';
            $grafico1 = json_encode(isset($preinforme['grafico_barras']) ? $preinforme['grafico_barras'] : []);
            $analisis_brechas_riesgos = $preinforme['analisis_brechas_riesgos'] ?? '';
            $recomendaciones_legales = $preinforme['recomendaciones_legales'] ?? '';
            $fundamento_normativo = $preinforme['fundamento_normativo'] ?? '';
            $llamado_a_accion = $preinforme['llamado_a_accion'] ?? '';
            $conclusion = $preinforme['conclusion'] ?? '';
            $porcentaje_cumplimiento_general = $preinforme['porcentaje_cumplimiento_general'] ?? '';

            $nuevo_informe = new CarpetaInforme;
            $nuevo_informe->carpeta_id = $carpeta->id;
            $nuevo_informe->grafico_1 = $grafico1;
            $nuevo_informe->data1 = $resumen_ejecutivo;
            $nuevo_informe->data2 = $porcentaje_cumplimiento_general;
            $nuevo_informe->data3 = $analisis_brechas_riesgos;
            $nuevo_informe->data4 = $recomendaciones_legales;
            $nuevo_informe->data5 = $fundamento_normativo;
            $nuevo_informe->data6 = $llamado_a_accion;
            $nuevo_informe->data7 = $conclusion;
            $nuevo_informe->save();


            $carpeta->estado_id = 4;
            $carpeta->save();

        }

    }
}
