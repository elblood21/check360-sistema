<?php

namespace App\Http\Controllers;

use App\Models\TiposCocina;
use App\Helpers\SubdominioHelper;
use Illuminate\Http\Request;

class TiposCocinaController extends Controller
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
        return view('tipos_cocina.lista');
    }

    public function getData(Request $request)
    {
        $this->verificarSubdominio();
        $filtros = $request->filtros ?? [];

        $lista = TiposCocina::orderBy('name', 'ASC')
            ->when(!empty($filtros['nombre']), function ($q) use ($filtros) {
                return $q->where('name', 'LIKE', '%' . $filtros['nombre'] . '%');
            })
            ->whereNull('deleted_at')
            ->paginate(10);

        return $lista;
    }

    public function create()
    {
        $this->verificarSubdominio();
        return view('tipos_cocina.nuevo')->with([
            'tipo_cocina' => null,
        ]);
    }

    public function store(Request $request)
    {
        $this->verificarSubdominio();
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'color_primary' => 'nullable|string|max:7',
            'color_secondary' => 'nullable|string|max:7',
        ]);

        $new = new TiposCocina();
        $new->name = $request->name;
        $new->icon = $request->icon;
        $new->color_primary = $request->color_primary;
        $new->color_secondary = $request->color_secondary;
        $new->save();

        return response()->json(['estado' => 200, 'mensaje' => 'Tipo de cocina creado correctamente']);
    }

    public function edit($id)
    {
        $this->verificarSubdominio();
        $tipo_cocina = TiposCocina::where('id', $id)->whereNull('deleted_at')->first();
        
        if (!$tipo_cocina) {
            return redirect()->route('tipos_cocina.lista')->with('error', 'Tipo de cocina no encontrado');
        }

        return view('tipos_cocina.nuevo')->with([
            'tipo_cocina' => $tipo_cocina,
        ]);
    }

    public function update(Request $request)
    {
        $this->verificarSubdominio();
        $request->validate([
            'id' => 'required|exists:tipos_cocinas,id',
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'color_primary' => 'nullable|string|max:7',
            'color_secondary' => 'nullable|string|max:7',
        ]);

        $update = TiposCocina::where('id', $request->id)->whereNull('deleted_at')->first();
        
        if (!$update) {
            return response()->json(['estado' => 404, 'mensaje' => 'Tipo de cocina no encontrado']);
        }

        $update->name = $request->name;
        $update->icon = $request->icon;
        $update->color_primary = $request->color_primary;
        $update->color_secondary = $request->color_secondary;
        $update->save();

        return response()->json(['estado' => 200, 'mensaje' => 'Tipo de cocina actualizado correctamente']);
    }

    public function eliminar(Request $request)
    {
        $this->verificarSubdominio();
        $id = $request->id;
        $tipo_cocina = TiposCocina::where('id', $id)->whereNull('deleted_at')->first();
        
        if (!$tipo_cocina) {
            return response()->json(['estado' => 404, 'mensaje' => 'Tipo de cocina no encontrado']);
        }

        $tipo_cocina->delete();

        return response()->json(['estado' => 200, 'mensaje' => 'Tipo de cocina eliminado correctamente']);
    }
}
