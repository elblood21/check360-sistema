<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormularioUno;
use Barryvdh\DomPDF\Facade\Pdf;

class FormularioUnoController extends Controller
{
    public $cuestionario = [
        [
            'pregunta' => '¿Tu empresa cuenta con contratos de trabajo firmados y actualizados para todos los trabajadores según lo exige la ley?',
            'respuestas' => [
                '1' => 'Sí, todos nuestros contratos están al día.',
                '2' => 'Algunos contratos necesitan actualización.',
                '3' => 'No estamos seguros.'
            ],
            'warning' => 'Multas, demandas, y formalización forzada de la relación laboral con efectos retroactivos.'
        ],
        [
            'pregunta' => '¿Cumples con el pago íntegro y oportuno de cotizaciones previsionales y de salud?',
            'respuestas' => [
                '1' => 'Sí, siempre.',
                '2' => 'A veces nos atrasamos.',
                '3' => 'No estamos seguros.'
            ],
            'warning' => 'Nulidad del despido (Ley Bustos), demandas judiciales y sanciones de hasta 60 UTM.'
        ],
        [
            'pregunta' => '¿Llevas un registro adecuado de asistencia y control de jornada para cumplir con la normativa laboral vigente?',
            'respuestas' => [
                '1' => 'Sí, contamos con un sistema autorizado.',
                '2' => 'Solo llevamos control en algunos casos.',
                '3' => 'No tenemos un sistema implementado.'
            ],
            'warning' => 'Presunción en contra del empleador en juicios laborales por horas extras o jornada.'
        ],
        [
            'pregunta' => '¿Tu empresa ha recibido reclamos o fiscalizaciones de la Dirección del Trabajo en los últimos 12 meses?',
            'respuestas' => [
                '1' => 'No, nunca hemos tenido problemas.',
                '2' => 'Sí, hemos tenido observaciones menores.',
                '3' => 'Sí, y nos han sancionado.'
            ],
            'warning' => 'Reincidencia en fiscalizaciones puede agravar multas y generar nuevas inspecciones.'
        ],
        [
            'pregunta' => '¿Cuentas con protocolos actualizados para evitar riesgos laborales y cumplir con la Ley de Accidentes del Trabajo?',
            'respuestas' => [
                '1' => 'Sí, están al día y difundidos.',
                '2' => 'Están desactualizados o incompletos.',
                '3' => 'No tenemos protocolos establecidos.'
            ],
            'warning' => 'Responsabilidad civil y penal por accidentes laborales, además de multas.'
        ],
        [
            'pregunta' => '¿Has implementado el Registro Electrónico Laboral obligatorio en el portal MiDT?',
            'respuestas' => [
                '1' => 'Sí, cumplimos con todas las cargas y actualizaciones.',
                '2' => 'Lo tenemos parcialmente implementado.',
                '3' => 'No, aún no hemos iniciado el proceso.'
            ],
            'warning' => 'Sanciones por incumplimiento de Decreto N°14 y pérdida de trazabilidad documental.'
        ],
        [
            'pregunta' => '¿Tu empresa entrega a cada trabajador una copia del Reglamento Interno actualizado?',
            'respuestas' => [
                '1' => 'Sí, cada trabajador tiene su copia firmada.',
                '2' => 'Se entrega parcialmente o está desactualizado.',
                '3' => 'No tenemos reglamento o no lo hemos entregado.'
            ],
            'warning' => 'Multas y vulneración de derechos básicos como seguridad, acoso, y jornadas.'
        ],
        [
            'pregunta' => '¿Las carpetas de personal de tus trabajadores contienen toda la documentación exigida por ley? (contrato, anexos, EPP, licencias, vacaciones, etc.)',
            'respuestas' => [
                '1' => 'Sí, están completas y ordenadas.',
                '2' => 'Algunas carpetas están incompletas.',
                '3' => 'No llevamos una carpeta formal por trabajador.'
            ],
            'warning' => 'Multas, pérdida de evidencia y dificultad para responder ante fiscalización o juicios.'
        ],
        [
            'pregunta' => '¿Tu empresa tiene procedimientos claros y documentados para el término de contrato según la causal invocada?',
            'respuestas' => [
                '1' => 'Sí, seguimos protocolos y entregamos documentación conforme.',
                '2' => 'A veces improvisamos o no documentamos bien.',
                '3' => 'No tenemos protocolos definidos.'
            ],
            'warning' => 'Nulidad del despido, indemnizaciones por despido injustificado o mal uso de causales.'
        ],
        [
            'pregunta' => '¿Cuentas con mecanismos activos de relación con sindicatos u organizaciones de trabajadores?',
            'respuestas' => [
                '1' => 'Sí, mantenemos comunicación fluida y respetuosa.',
                '2' => 'Solo interactuamos cuando hay conflictos.',
                '3' => 'No hay instancias formales ni diálogo activo.'
            ],
            'warning' => 'Conflictos colectivos, denuncias por prácticas antisindicales y fiscalización por relaciones laborales tensas.'
        ],
    ];

    public function create()
    {
        return view('formulario_uno.create', ['cuestionario' => $this->cuestionario]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'razon_social' => 'required|string|max:255',
            'rut' => 'required|string|max:20',
            'correo_electronico' => 'required|email|max:255',
            'tamano_empresa' => 'required|string|max:50',
            'formulario' => 'required|string',
        ]);

        // El formulario ahora es un array de pares pregunta/respuesta
        $respuestas = json_decode($data['formulario'], true);
        // Guardar como JSON
        FormularioUno::create([
            'razon_social' => $data['razon_social'],
            'rut' => $data['rut'],
            'correo_electronico' => $data['correo_electronico'],
            'tamano_empresa' => $data['tamano_empresa'],
            'formulario' => json_encode($respuestas),
        ]);

        return redirect()->route('formulario_uno.index')->with('success', 'Formulario enviado correctamente');
    }

    public function index()
    {
        $formularios = FormularioUno::orderBy('id', 'desc')->get();
        $cuestionario = $this->cuestionario;
        return view('formulario_uno.index', compact('formularios', 'cuestionario'));
    }

    public function show($id)
    {
        $formulario = FormularioUno::findOrFail($id);
        return response()->json($formulario);
    }

    public function informe($id) {
        $id = decrypt($id);
        $formulario = FormularioUno::where('id', $id)->first();

        $data = [
            'formulario'=>$formulario,
            'logo'=>'https://sistema.check360.cl/assets/images/logo/logo_check360.png'
        ];
        $pdf = Pdf::loadView('pdf.evaluacion', $data);

        return $pdf->stream('evaluacion.pdf');
        
    }

}
