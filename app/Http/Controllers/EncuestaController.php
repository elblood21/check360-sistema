<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\PreguntaEncuesta;
use Illuminate\Http\Request;

class EncuestaController extends Controller
{
    public function index()
    {
        if (\App\Helpers\SubdominioHelper::esTipo('restaurante')) {
            return redirect()->route('dashboard');
        }
        $encuestas = Encuesta::whereNull('deleted_at')->orderBy('tipo')->get();
        return view('encuestas.lista')->with(['encuestas' => $encuestas]);
    }

    public function verPreguntas($id)
    {
        $id = decrypt($id);
        $encuesta = Encuesta::where('id', $id)->whereNull('deleted_at')
            ->with('preguntas')
            ->first();
        
        if (!$encuesta) {
            return redirect()->route('encuestas.lista');
        }

        return view('encuestas.ver_preguntas')->with(['encuesta' => $encuesta]);
    }

    public function crearPregunta($id)
    {
        $id = decrypt($id);
        $encuesta = Encuesta::where('id', $id)->whereNull('deleted_at')->first();
        
        if (!$encuesta) {
            return redirect()->route('encuestas.lista');
        }

        return view('encuestas.nueva_pregunta')->with([
            'encuesta' => $encuesta,
            'pregunta' => null,
        ]);
    }

    public function guardarPregunta(Request $request)
    {
        $encuesta_id = decrypt($request->encuesta_id);
        $texto = $request->texto;
        $tipo_respuesta = $request->tipo_respuesta;
        $orden = $request->orden;
        $dimension = $request->dimension;

        if (!$texto || !$tipo_respuesta || !$orden) {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe completar todos los campos requeridos']);
        }

        $new = new PreguntaEncuesta();
        $new->encuesta_id = $encuesta_id;
        $new->texto = $texto;
        $new->tipo_respuesta = $tipo_respuesta;
        $new->orden = $orden;
        $new->dimension = $dimension;
        if ($request->opciones) {
            $opciones = is_string($request->opciones) ? json_decode($request->opciones, true) : $request->opciones;
            $new->opciones = $opciones;
        } else {
            $new->opciones = null;
        }
        $new->save();

        return response()->json(['estado' => 200]);
    }

    public function editarPregunta($id)
    {
        $id = decrypt($id);
        $pregunta = PreguntaEncuesta::where('id', $id)->whereNull('deleted_at')
            ->with('encuesta')
            ->first();
        
        if (!$pregunta) {
            return redirect()->route('encuestas.lista');
        }

        return view('encuestas.nueva_pregunta')->with([
            'encuesta' => $pregunta->encuesta,
            'pregunta' => $pregunta,
        ]);
    }

    public function getPregunta($id)
    {
        try {
            // El ID ya viene decodificado por Laravel si viene de la ruta
            $id = decrypt($id);
        } catch (\Exception $e) {
            \Log::error('Error al desencriptar ID de pregunta: ' . $e->getMessage(), ['id' => $id]);
            return response()->json([
                'estado' => 400, 
                'mensaje' => 'ID de pregunta inválido'
            ], 400);
        }
        
        $pregunta = PreguntaEncuesta::find($id);
        
        if (!$pregunta) {
            return response()->json([
                'estado' => 404, 
                'mensaje' => 'Pregunta no encontrada'
            ], 404);
        }

        return response()->json([
            'estado' => 200,
            'pregunta' => [
                'id' => encrypt($pregunta->id),
                'texto' => $pregunta->texto,
                'tipo_respuesta' => $pregunta->tipo_respuesta,
                'orden' => $pregunta->orden,
                'dimension' => $pregunta->dimension,
                'opciones' => $pregunta->opciones
            ]
        ]);
    }

    public function actualizarPregunta(Request $request)
    {
        $id = decrypt($request->id);
        $pregunta = PreguntaEncuesta::find($id);
        
        if (!$pregunta) {
            return response()->json(['estado' => 404, 'mensaje' => 'Pregunta no encontrada']);
        }

        $texto = $request->texto;
        $tipo_respuesta = $request->tipo_respuesta;
        $dimension = $request->dimension;

        if (!$texto || !$tipo_respuesta) {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe completar todos los campos requeridos']);
        }

        $pregunta->texto = $texto;
        $pregunta->tipo_respuesta = $tipo_respuesta;
        $pregunta->dimension = $dimension;
        // No modificar el orden al editar, se mantiene el actual (se modifica con drag and drop)
        if ($request->opciones) {
            $opciones = is_string($request->opciones) ? json_decode($request->opciones, true) : $request->opciones;
            $pregunta->opciones = $opciones;
        } else {
            $pregunta->opciones = null;
        }
        $pregunta->save();

        return response()->json(['estado' => 200]);
    }

    public function eliminarPregunta(Request $request)
    {
        $id = decrypt($request->id);
        $pregunta = PreguntaEncuesta::find($id);
        
        if (!$pregunta) {
            return response()->json(['estado' => 404, 'mensaje' => 'Pregunta no encontrada']);
        }

        $pregunta->delete();

        return response()->json(['estado' => 200]);
    }

    public function actualizarOrden(Request $request)
    {
        $ordenes = $request->ordenes;
        
        if (!$ordenes || !is_array($ordenes)) {
            return response()->json(['estado' => 500, 'mensaje' => 'Datos inválidos']);
        }

        try {
            \DB::beginTransaction();
            
            foreach ($ordenes as $item) {
                $pregunta = PreguntaEncuesta::find($item['id']);
                if ($pregunta) {
                    $pregunta->orden = $item['orden'];
                    $pregunta->save();
                }
            }
            
            \DB::commit();
            return response()->json(['estado' => 200]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['estado' => 500, 'mensaje' => 'Error al actualizar el orden: ' . $e->getMessage()]);
        }
    }
}




