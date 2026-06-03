<?php

namespace App\Http\Controllers;

use App\Models\DimensionEncuesta;
use App\Helpers\SubdominioHelper;
use Illuminate\Http\Request;

class DimensionController extends Controller
{
    private function verificarSubdominio()
    {
        if (!SubdominioHelper::esTipo('sistema')) {
            abort(403, 'Acceso no autorizado');
        }
    }

    public function index()
    {
        $this->verificarSubdominio();
        return view('dimensiones.lista');
    }

    public function getData(Request $request)
    {
        $this->verificarSubdominio();
        $filtros = $request->filtros ?? [];

        $lista = DimensionEncuesta::orderBy('nombre', 'ASC')
            ->when(!empty($filtros['nombre']), function ($q) use ($filtros) {
                return $q->where('nombre', 'LIKE', '%' . $filtros['nombre'] . '%');
            })
            ->paginate(10);

        return $lista;
    }

    public function store(Request $request)
    {
        $this->verificarSubdominio();
        $request->validate([
            'nombre' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'icono' => 'nullable|string|max:255',
        ]);

        $new = new DimensionEncuesta();
        $new->nombre = $request->nombre;
        $new->color = $request->color;
        $new->icono = $request->icono;
        $new->save();

        return response()->json(['estado' => 200, 'mensaje' => 'Dimensión creada correctamente']);
    }

    public function update(Request $request)
    {
        $this->verificarSubdominio();
        $id = decrypt($request->id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'icono' => 'nullable|string|max:255',
        ]);

        $update = DimensionEncuesta::find($id);
        
        if (!$update) {
            return response()->json(['estado' => 404, 'mensaje' => 'Dimensión no encontrada']);
        }

        $update->nombre = $request->nombre;
        $update->color = $request->color;
        $update->icono = $request->icono;
        $update->save();

        return response()->json(['estado' => 200, 'mensaje' => 'Dimensión actualizada correctamente']);
    }

    public function eliminar(Request $request)
    {
        $this->verificarSubdominio();
        $id = decrypt($request->id);
        $dimension = DimensionEncuesta::find($id);
        
        if (!$dimension) {
            return response()->json(['estado' => 404, 'mensaje' => 'Dimensión no encontrada']);
        }

        $dimension->delete();

        return response()->json(['estado' => 200, 'mensaje' => 'Dimensión eliminada correctamente']);
    }
}
