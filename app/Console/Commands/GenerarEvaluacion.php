<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Carpeta;
use App\Models\CarpetaRespuestas;
use App\Models\CarpetaInforme;
use App\Models\Requerimiento;
use Illuminate\Support\Facades\Http;
use App\Models\FormularioUno;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\enviarEmail;

class GenerarEvaluacion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generar-evaluacion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private function prompt($preguntas,$cliente) {
        $prompt = "
        INSTRUCCIÓN PRINCIPAL  
        Quiero que actúes como la mejor asistente legal de un estudio jurídico en Chile y generes una  **evaluación inicial de cumplimiento laboral** profesional, visual y persuasivo.  
        El informe debe tener una estructura legal sólida, análisis normativo detallado, orientación comercial sutil, diseño gráfico atractivo y uso correcto de los colores corporativos.  
        Utiliza un lenguaje formal, técnico y alineado con la normativa laboral chilena vigente.

        NIVEL DE REDACCIÓN  
        Cada sección debe estar redactada con claridad, profundidad y precisión jurídica.  
        Evita frases genéricas. Usa lenguaje preventivo, analítico y útil para la toma de decisiones.

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

        ---

        ### CUESTIONARIO DE EVALUACIÓN

        Documento Pregunta           | Respuesta";

        foreach($preguntas as $pregunta) {
            $prompt .= "
            {$pregunta['pregunta']}       | {$pregunta['respuesta']}";
        }

        $prompt .= "
        
        ---

        ### ESTRUCTURA DEL INFORME (CON ICONOS Y ESTILO VISUAL)

        1. Ficha de Identificación  
        2. Recomendaciones Legales Prioritarias  
        3. Fundamento Normativo  
        4. Llamado a la Acción Comercial  
        5. Conclusión Ejecutiva  

        ---

        ### OBJETIVO FINAL

        Este documento debe cumplir una doble función:

        1. Ser un diagnóstico técnico legal confiable y visualmente atractivo.  
        2. Ser una herramienta de generación de leads para **Check 360**.

        ### IMPORTANTE: SALIDA

        La respuesta debe ser un **JSON** estructurado con las siguientes claves exactas:
        {
        'ficha_identificacion': 'Texto...',
        'recomendaciones_legales': 'Texto...',
        'fundamento_normativo': 'Texto...',
        'llamado_a_accion': 'Texto...',
        'conclusion': 'Texto...'
        }

        - no incluir titulo en texto de json
        - en el valor de cada elemento ejemplo conclusion SOLO TEXTO para colocar dentro de un <p> (no incluir *** ni el <p> dentro del json ... )
        - NO NEGRITA
        - NO ENCABEZADO
        - NO MARKDOWN SOLO JSON
        ---
    ";
    return $prompt;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $formularios = FormularioUno::where('estado', 1)->get();

        foreach($formularios as $formulario) {
            $fuController = new \App\Http\Controllers\FormularioUnoController();

            $decoded = [];
            try {
                $decoded = json_decode($formulario->formulario, true) ?: [];
            } catch (\Throwable $e) {
                $decoded = [];
            }

            $mappedPreguntas = [];
            foreach ($decoded as $pair) {
                $qIdx = isset($pair['pregunta']) ? (int)$pair['pregunta'] : null;
                $rIdx = isset($pair['respuesta']) ? (string)$pair['respuesta'] : null;
                $pregText = isset($fuController->cuestionario[$qIdx]) ? $fuController->cuestionario[$qIdx]['pregunta'] : '';
                $respText = (isset($fuController->cuestionario[$qIdx]) && $rIdx !== null && isset($fuController->cuestionario[$qIdx]['respuestas'][$rIdx])) ? $fuController->cuestionario[$qIdx]['respuestas'][$rIdx] : '';
                $mappedPreguntas[] = [
                    'pregunta' => $pregText,
                    'respuesta' => $respText,
                ];
            }

            $contentGPT = $this->prompt($mappedPreguntas, $formulario);

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
    
            $recomendaciones_legales = $preinforme['recomendaciones_legales'] ?? '';
            $fundamento_normativo = $preinforme['fundamento_normativo'] ?? '';
            $llamado_a_accion = $preinforme['llamado_a_accion'] ?? '';
            $conclusion = $preinforme['conclusion'] ?? '';

            $formulario->recomendaciones_legales = $recomendaciones_legales;
            $formulario->fundamento_normativo = $fundamento_normativo;
            $formulario->llamado_a_accion = $llamado_a_accion;
            $formulario->conclusion = $conclusion;
            $formulario->estado = 0;
            $formulario->save();
            
            $dataEmail = [
                'correo_electronico'=>$formulario->correo_electronico,
                'titulo'=>'Evaluación Check 360',
                'vista'=>'mails.evaluacion',
                'nombre'=>$formulario->razon_social
            ];
            
            $dataForm = [
                'formulario'=>$formulario,
                'logo'=>'https://sistema.check360.cl/assets/images/logo/logo_check360.png'
            ];
            $pdf = Pdf::loadView('pdf.evaluacion', $dataForm);
            $pdfContent = $pdf->output();
    
            try {
                \Mail::to($formulario->correo_electronico)
                ->send(new enviarEmail($dataEmail, $pdfContent));
            } catch (\Exception $e) {
                $this->error("Error enviando email de evaluacion: " . $e->getMessage());
            }

        }

    }
}
