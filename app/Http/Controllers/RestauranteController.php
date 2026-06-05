<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use App\Models\RestauranteOpciones;
use App\Models\RestauranteUser;
use App\Models\TiposCocina;
use App\Models\Region;
use App\Models\Ciudad;
use Illuminate\Http\Request;

class RestauranteController extends Controller
{
    public function index()
    {
        if (\App\Helpers\SubdominioHelper::esTipo('restaurante')) {
            return redirect()->route('dashboard');
        }
        return view('restaurantes.lista');
    }

    public function getData(Request $request)
    {
        $filtros = $request->filtros ?? [];

        $lista = Restaurante::orderBy('id', 'DESC')
            ->when(!empty($filtros['nombre']), function ($q) use ($filtros) {
                return $q->where('name', 'LIKE', '%' . $filtros['nombre'] . '%');
            })
            ->when(!empty($filtros['email']), function ($q) use ($filtros) {
                return $q->where('email', 'LIKE', '%' . $filtros['email'] . '%');
            })
            ->whereNull('deleted_at')
            ->with(['tipoCocina', 'admin'])
            ->paginate(10);

        return $lista;
    }

    public function create()
    {
        $tipos_cocina = TiposCocina::whereNull('deleted_at')->orderBy('name')->get();
        $regiones = Region::orderBy('nombre')->get();

        return view('restaurantes.nuevo')->with([
            'restaurante' => null,
            'tipos_cocina' => $tipos_cocina,
            'regiones' => $regiones,
            'ciudades' => collect([]),
            'opciones' => [],
        ]);
    }

    public function store(Request $request)
    {
        $nombre = $request->nombre;

        if (!$nombre || trim($nombre) === '') {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe completar el campo nombre']);
        }

        $adminEmail = $request->admin_email;
        $adminName = $request->admin_name;

        if (!$adminEmail || !$adminName) {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe completar los datos del usuario administrador']);
        }

        // Validar que el email no exista en restaurante_users
        $existe = RestauranteUser::where('email', $adminEmail)->whereNull('deleted_at')->exists();
        if ($existe) {
            return response()->json(['estado' => 500, 'mensaje' => 'El correo electrónico del administrador ya se encuentra registrado']);
        }

        $new = new Restaurante();
        $new->name = $nombre;
        $new->direccion = $request->direccion;
        $new->ciudad_id = $request->ciudad_id;
        $new->telefono = $request->telefono;
        $new->email = $request->email;
        $new->tipo_cocina_id = $request->tipo_cocina_id;
        $new->rango_ticket_promedio = $request->rango_ticket_promedio;
        $new->capacidad_restaurante = $request->capacidad_restaurante;
        $new->save();

        $this->guardarOpciones($new->id, $request->opciones ?? []);

        // Crear usuario para el restaurante
        $this->crearUsuarioRestaurante($new->id, $adminName, $adminEmail);

        return response()->json(['estado' => 200]);
    }

    public function edit($id)
    {
        $id = decrypt($id);

        $restaurante = Restaurante::where('id', $id)->whereNull('deleted_at')->with('ciudad')->first();
        if (!$restaurante) {
            return redirect()->route('restaurantes.lista');
        }

        $tipos_cocina = TiposCocina::whereNull('deleted_at')->orderBy('name')->get();
        $regiones = Region::orderBy('nombre')->get();
        
        // Obtener ciudades de la región del restaurante si tiene ciudad
        $ciudades = collect([]);
        if ($restaurante->ciudad_id && $restaurante->ciudad) {
            $ciudades = Ciudad::where('region_id', $restaurante->ciudad->region_id)->orderBy('nombre')->get();
        }
        
        $opciones = RestauranteOpciones::where('restaurante_id', $restaurante->id)->get()
            ->mapWithKeys(function ($o) {
                return [$o->clave => ['valor_json' => $o->valor_json, 'valor_texto' => $o->valor_texto]];
            })->toArray();

        return view('restaurantes.nuevo')->with([
            'restaurante' => $restaurante,
            'tipos_cocina' => $tipos_cocina,
            'regiones' => $regiones,
            'ciudades' => $ciudades,
            'opciones' => $opciones,
        ]);
    }

    public function show($id)
    {
        $id = decrypt($id);

        $restaurante = Restaurante::where('id', $id)
            ->whereNull('deleted_at')
            ->with(['tipoCocina', 'ciudad.region', 'admin'])
            ->first();
            
        if (!$restaurante) {
            return redirect()->route('restaurantes.lista');
        }

        $opciones = RestauranteOpciones::where('restaurante_id', $restaurante->id)->get()
            ->mapWithKeys(function ($o) {
                return [$o->clave => ['valor_json' => $o->valor_json, 'valor_texto' => $o->valor_texto]];
            })->toArray();

        return view('restaurantes.ver')->with([
            'restaurante' => $restaurante,
            'opciones' => $opciones
        ]);
    }

    public function update(Request $request)
    {
        $id = decrypt($request->id);

        $update = Restaurante::find($id);
        if (!$update || $update->deleted_at) {
            return response()->json(['estado' => 404, 'mensaje' => 'Restaurante no encontrado']);
        }

        $nombre = $request->nombre;
        if (!$nombre || trim($nombre) === '') {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe completar el campo nombre']);
        }

        $update->name = $nombre;
        $update->direccion = $request->direccion;
        $update->ciudad_id = $request->ciudad_id;
        $update->telefono = $request->telefono;
        $update->email = $request->email;
        $update->tipo_cocina_id = $request->tipo_cocina_id;
        $update->rango_ticket_promedio = $request->rango_ticket_promedio;
        $update->capacidad_restaurante = $request->capacidad_restaurante;
        $update->save();

        $this->guardarOpciones($update->id, $request->opciones ?? []);

        return response()->json(['estado' => 200]);
    }

    public function eliminar(Request $request)
    {
        $id = decrypt($request->id);

        $update = Restaurante::find($id);
        if (!$update || $update->deleted_at) {
            return response()->json(['estado' => 404, 'mensaje' => 'Restaurante no encontrado']);
        }

        $update->deleted_at = date('Y-m-d H:i:s');
        $update->save();

        return response()->json(['estado' => 200]);
    }

    public function aprobar(Request $request)
    {
        $id = decrypt($request->id);

        $restaurante = Restaurante::find($id);
        if (!$restaurante || $restaurante->deleted_at) {
            return response()->json(['estado' => 404, 'mensaje' => 'Restaurante no encontrado']);
        }

        $restaurante->aprobado = 1;
        $restaurante->aprobado_por = \Auth::id();
        $restaurante->aprobado_at = now();
        $restaurante->estado = 1;
        $restaurante->save();

        // Podríamos enviar un email avisando que fue aprobado si se requiere
        
        return response()->json(['estado' => 200, 'mensaje' => 'Restaurante aprobado correctamente']);
    }

    public function rechazar(Request $request)
    {
        $id = decrypt($request->id);

        $restaurante = Restaurante::find($id);
        if (!$restaurante || $restaurante->deleted_at) {
            return response()->json(['estado' => 404, 'mensaje' => 'Restaurante no encontrado']);
        }

        $restaurante->aprobado = 2; // O simplemente eliminarlo
        $restaurante->deleted_at = now();
        $restaurante->save();

        return response()->json(['estado' => 200, 'mensaje' => 'Restaurante rechazado']);
    }

    public function estado(Request $request)
    {
        $id = decrypt($request->id);

        $restaurante = Restaurante::find($id);
        if (!$restaurante || $restaurante->deleted_at) {
            return response()->json(['estado' => 404, 'mensaje' => 'Restaurante no encontrado']);
        }

        $restaurante->estado = $request->estado;
        $restaurante->save();

        // Update admin user state as well
        if ($restaurante->admin) {
            $restaurante->admin->estado = $request->estado;
            $restaurante->admin->save();
        }

        return response()->json(['estado' => 200]);
    }

    private function guardarOpciones(int $restaurante_id, array $opciones): void
    {
        $keys = [
            'edad_promedio_clientes',
            'perfil_socioeconomico_predominante',
            'lugar_residencia_principal_clientes',
            'motivos_visita_restaurante',
            'motivos_visita_restaurante_otros',
            'estilo_vida_clientes',
            'comportamiento_habitual_clientes',
            'ambiente_estilo',
            'puntos_fuertes',
            'puntos_fuertes_otros',
            'horarios_evaluacion',
            'protocolos_internos',
            'observaciones_adicionales',
        ];

        foreach ($keys as $clave) {
            $valor = $opciones[$clave] ?? null;

            $payload = [
                'restaurante_id' => $restaurante_id,
                'clave' => $clave,
            ];

            // Separar texto libre para campos *_otros y observaciones
            if (in_array($clave, ['motivos_visita_restaurante_otros', 'puntos_fuertes_otros', 'observaciones_adicionales'], true)) {
                $payload['valor_texto'] = $valor ? (string) $valor : null;
                $payload['valor_json'] = null;
            } else {
                $payload['valor_json'] = $valor;
                $payload['valor_texto'] = null;
            }

            RestauranteOpciones::updateOrCreate(
                ['restaurante_id' => $restaurante_id, 'clave' => $clave],
                $payload
            );
        }
    }

    private function crearUsuarioRestaurante(int $restaurante_id, string $nombre, string $email): void
    {
        // Verificar si ya existe un usuario para este restaurante (doble check)
        $usuarioExistente = RestauranteUser::where('restaurante_id', $restaurante_id)
            ->whereNull('deleted_at')
            ->first();

        if ($usuarioExistente) {
            return;
        }

        // Generar password temporal
        $pass = \Str::random(8);

        $newUser = new RestauranteUser();
        $newUser->restaurante_id = $restaurante_id;
        $newUser->name = $nombre;
        $newUser->email = $email;
        $newUser->password = \Hash::make($pass);
        $newUser->estado = 1;
        $newUser->save();

        // Enviar email con credenciales
        try {
            $dataEmail = [
                'correo_electronico' => $email,
                'titulo' => 'Bienvenido a Check 360 - Tus credenciales',
                'p1' => 'Hola ' . $nombre . ', se ha creado una cuenta para tu restaurante en nuestra plataforma.',
                'p2' => 'Estas son tus credenciales de acceso:',
                'user' => $email,
                'pass' => $pass,
                'link' => 'https://restaurante.check360.cl',
                'vista' => 'mails.newuser'
            ];
            \Mail::to($email)->send(new \App\Mail\enviarEmail($dataEmail));
        } catch (\Throwable $e) {
            \Log::error("Error enviando email de bienvenida: " . $e->getMessage() . " - Line: " . $e->getLine());
        }
    }

    public function getCiudades(Request $request)
    {
        $region_id = $request->region_id;
        
        if (!$region_id) {
            return response()->json(['estado' => 500, 'mensaje' => 'Region ID requerido']);
        }

        $ciudades = Ciudad::where('region_id', $region_id)
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        return response()->json([
            'estado' => 200,
            'ciudades' => $ciudades
        ]);
    }

    /**
     * Listado de usuarios administradores de restaurantes
     */
    public function usuariosAdminIndex()
    {
        return view('restaurantes.usuarios_admin_lista');
    }

    /**
     * Obtener datos para el listado de usuarios admin
     */
    public function usuariosAdminGetData(Request $request)
    {
        $filtros = $request->filtros ?? [];

        $tipo = \App\Helpers\SubdominioHelper::obtenerTipo();
        $restaurante_id = null;
        if ($tipo === 'restaurante') {
            $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
            $user = \Auth::guard($guard)->user();
            $restaurante_id = $user->restaurante_id;
        }

        $lista = RestauranteUser::with(['restaurante'])
            ->orderBy('id', 'DESC')
            ->when($restaurante_id, function($q) use ($restaurante_id) {
                return $q->where('restaurante_id', $restaurante_id);
            })
            ->when(!$restaurante_id && !empty($filtros['restaurante_id']), function ($q) use ($filtros) {
                return $q->where('restaurante_id', $filtros['restaurante_id']);
            })
            ->when(!empty($filtros['nombre']), function ($q) use ($filtros) {
                return $q->where('name', 'LIKE', '%' . $filtros['nombre'] . '%');
            })
            ->when(!empty($filtros['email']), function ($q) use ($filtros) {
                return $q->where('email', 'LIKE', '%' . $filtros['email'] . '%');
            })
            ->whereNull('deleted_at')
            ->paginate(10);

        return $lista;
    }

    /**
     * Vista para crear un nuevo usuario admin
     */
    public function usuariosAdminCreate()
    {
        $tipo = \App\Helpers\SubdominioHelper::obtenerTipo();
        $restaurante_id = null;
        if ($tipo === 'restaurante') {
            $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
            $user = \Auth::guard($guard)->user();
            $restaurante_id = $user->restaurante_id;
        }

        $restaurantes = $restaurante_id ? 
            Restaurante::where('id', $restaurante_id)->get() : 
            Restaurante::whereNull('deleted_at')->orderBy('name')->get();

        return view('restaurantes.usuarios_admin_nuevo')->with([
            'usuario' => null,
            'restaurantes' => $restaurantes,
            'restaurante_id_default' => $restaurante_id
        ]);
    }

    /**
     * Guardar nuevo usuario admin
     */
    public function usuariosAdminStore(Request $request)
    {
        $nombre = $request->nombre;
        $email = $request->email;
        $restaurante_id = $request->restaurante_id;

        $tipo = \App\Helpers\SubdominioHelper::obtenerTipo();
        if ($tipo === 'restaurante') {
            $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
            $user = \Auth::guard($guard)->user();
            $restaurante_id = $user->restaurante_id; // Forzar el ID del restaurante del usuario
        }

        if (!$nombre || !$email || !$restaurante_id) {
            return response()->json(['estado' => 500, 'mensaje' => 'Todos los campos son obligatorios']);
        }

        // Verificar si el email ya existe
        $existe = RestauranteUser::where('email', $email)->whereNull('deleted_at')->exists();
        if ($existe) {
            return response()->json(['estado' => 500, 'mensaje' => 'El correo electrónico ya está registrado']);
        }

        $pass = \Str::random(8);

        $new = new RestauranteUser();
        $new->name = $nombre;
        $new->email = $email;
        $new->restaurante_id = $restaurante_id;
        $new->password = \Hash::make($pass);
        $new->estado = 1;
        $new->save();

        // Enviar email
        try {
            $dataEmail = [
                'correo_electronico' => $email,
                'titulo' => 'Bienvenido a Check 360 - Admin Restaurante',
                'vista' => 'mails.newuser',
                'nombre' => $nombre,
                'pass' => $pass,
                'plataforma' => 'https://restaurante.check360.cl'
            ];
            \Mail::to($email)->send(new \App\Mail\enviarEmail($dataEmail));
        } catch (\Throwable $e) {
            \Log::error("Error enviando email: " . $e->getMessage() . " - Line: " . $e->getLine());
        }

        return response()->json(['estado' => 200]);
    }

    /**
     * Vista para editar usuario admin
     */
    public function usuariosAdminEdit($id)
    {
        $id = decrypt($id);
        $usuario = RestauranteUser::find($id);

        $tipo = \App\Helpers\SubdominioHelper::obtenerTipo();
        if ($tipo === 'restaurante') {
            $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
            $user = \Auth::guard($guard)->user();
            if ($usuario && $usuario->restaurante_id != $user->restaurante_id) {
                return redirect()->route('restaurantes.usuarios.lista');
            }
        }

        if (!$usuario || $usuario->deleted_at) {
            return redirect()->route('restaurantes.usuarios.lista');
        }

        $restaurante_id_locked = ($tipo === 'restaurante') ? $usuario->restaurante_id : null;
        $restaurantes = $restaurante_id_locked ? 
            Restaurante::where('id', $restaurante_id_locked)->get() : 
            Restaurante::whereNull('deleted_at')->orderBy('name')->get();

        return view('restaurantes.usuarios_admin_nuevo')->with([
            'usuario' => $usuario,
            'restaurantes' => $restaurantes
        ]);
    }

    /**
     * Actualizar usuario admin
     */
    public function usuariosAdminUpdate(Request $request)
    {
        $id = decrypt($request->id);
        $update = RestauranteUser::find($id);
        if (!$update || $update->deleted_at) {
            return response()->json(['estado' => 404, 'mensaje' => 'Usuario no encontrado']);
        }

        $tipo = \App\Helpers\SubdominioHelper::obtenerTipo();
        if ($tipo === 'restaurante') {
            $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
            $user = \Auth::guard($guard)->user();
            if ($update->restaurante_id != $user->restaurante_id) {
                return response()->json(['estado' => 403, 'mensaje' => 'No autorizado']);
            }
            $restaurante_id = $user->restaurante_id;
        } else {
            $restaurante_id = $request->restaurante_id;
        }

        $update->name = $request->nombre;
        $update->email = $request->email;
        $update->restaurante_id = $restaurante_id;
        $update->save();

        return response()->json(['estado' => 200]);
    }

    /**
     * Renders the public restaurant registration form.
     */
    public function registroPublico()
    {
        $tipos_cocina = TiposCocina::whereNull('deleted_at')->orderBy('name')->get();
        $regiones = Region::orderBy('nombre')->get();

        return view('auth.restaurante_register')->with([
            'tipos_cocina' => $tipos_cocina,
            'regiones' => $regiones,
            'ciudades' => collect([]),
            'opciones' => [],
        ]);
    }

    /**
     * Handles the public restaurant registration post request.
     */
    public function registroPublicoPost(Request $request)
    {
        $nombre = $request->nombre;
        if (!$nombre || trim($nombre) === '') {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe completar el campo nombre del restaurante']);
        }

        $adminEmail = $request->admin_email;
        $adminName = $request->admin_name;
        if (!$adminEmail || !$adminName) {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe completar los datos del usuario administrador']);
        }

        // Validar que el email no exista en restaurante_users
        $existe = RestauranteUser::where('email', $adminEmail)->whereNull('deleted_at')->exists();
        if ($existe) {
            return response()->json(['estado' => 500, 'mensaje' => 'El correo electrónico del administrador ya se encuentra registrado']);
        }

        $porcentaje = intval($request->porcentaje_descuento);
        if ($porcentaje < 50 || $porcentaje > 100) {
            return response()->json(['estado' => 500, 'mensaje' => 'El porcentaje de descuento debe estar entre 50% y 100%']);
        }

        $direccion = $request->direccion;
        if (!$direccion || trim($direccion) === '') {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe ingresar la dirección del restaurante']);
        }

        $ciudad_id = $request->ciudad_id;
        if (!$ciudad_id) {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe seleccionar la ciudad/comuna']);
        }

        // Validar imágenes del restaurante (al menos 1)
        if (!$request->hasFile('imagenes_files') || count($request->file('imagenes_files')) === 0) {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe subir al menos 1 imagen real del restaurante']);
        }

        // Validar carta
        $cartaTipo = $request->carta_tipo;
        if ($cartaTipo === 'url' && (!$request->carta_url || trim($request->carta_url) === '')) {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe ingresar la URL de la carta digital']);
        } elseif ($cartaTipo === 'imagenes' && (!$request->hasFile('carta_files') || count($request->file('carta_files')) === 0)) {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe subir al menos 1 imagen de la carta']);
        }

        // Crear directorios de subida si no existen
        $dirs = ['uploads/logos', 'uploads/imagenes', 'uploads/cartas'];
        foreach ($dirs as $d) {
            $path = public_path($d);
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
        }

        // Subir logo
        $logoPath = null;
        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            $logoPath = '/uploads/logos/' . time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '', $file->getClientOriginalName());
            $file->move(public_path('uploads/logos'), basename($logoPath));
        }

        // Subir imágenes del restaurante
        $imagenesPaths = [];
        if ($request->hasFile('imagenes_files')) {
            foreach ($request->file('imagenes_files') as $index => $file) {
                $path = '/uploads/imagenes/' . time() . '_' . $index . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '', $file->getClientOriginalName());
                $file->move(public_path('uploads/imagenes'), basename($path));
                $imagenesPaths[] = $path;
            }
        }

        // Subir imágenes de la carta
        $cartaImagenesPaths = [];
        if ($cartaTipo === 'imagenes' && $request->hasFile('carta_files')) {
            foreach ($request->file('carta_files') as $index => $file) {
                $path = '/uploads/cartas/' . time() . '_' . $index . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '', $file->getClientOriginalName());
                $file->move(public_path('uploads/cartas'), basename($path));
                $cartaImagenesPaths[] = $path;
            }
        }

        // Procesar horario de atención y peak
        $horarioPeak = [];
        $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
        foreach ($dias as $dia) {
            $horarioPeak[$dia] = [
                'apertura' => $request->input("horario_{$dia}_apertura") ?? '',
                'cierre' => $request->input("horario_{$dia}_cierre") ?? '',
                'desde' => $request->input("peak_{$dia}_desde") ?? '',
                'hasta' => $request->input("peak_{$dia}_hasta") ?? '',
                'ocupa_90' => $request->input("peak_{$dia}_ocupa_90") == '1',
            ];
        }

        // Crear el restaurante
        $new = new Restaurante();
        $new->name = $nombre;
        $new->direccion = $request->direccion;
        $new->ciudad_id = $request->ciudad_id;
        $new->telefono = $request->telefono;
        $new->email = $request->email;
        $new->tipo_cocina_id = $request->tipo_cocina_id;
        $new->rango_ticket_promedio = $request->rango_ticket_promedio;
        $new->capacidad_restaurante = $request->capacidad_restaurante;
        
        // Campos nuevos del plan y auto-registro
        $new->plan_activo = true;
        $new->plan_inicio = date('Y-m-d');
        $new->plan_fin = date('Y-m-d', strtotime('+6 months'));
        $new->periodo_inicio = date('Y-m-d');
        $new->periodo_fin = date('Y-m-d', strtotime('+60 days'));
        
        $new->porcentaje_descuento = $porcentaje;
        $new->carta_tipo = $cartaTipo;
        $new->carta_url = $request->carta_url;
        $new->carta_imagenes = $cartaImagenesPaths;
        $new->logo = $logoPath;
        $new->imagenes = $imagenesPaths;
        $new->social_facebook = $request->social_facebook;
        $new->social_instagram = $request->social_instagram;
        $new->social_tiktok = $request->social_tiktok;
        $new->horario_peak = $horarioPeak;
        $new->save();

        // Guardar opciones vacías/por defecto para retrocompatibilidad
        $this->guardarOpciones($new->id, $request->opciones ?? []);

        // Crear usuario para el restaurante
        $this->crearUsuarioRestaurante($new->id, $adminName, $adminEmail);

        return response()->json(['estado' => 200, 'mensaje' => '¡Restaurante registrado con éxito!']);
    }

    /**
     * Canje de cupones index
     */
    public function canjeIndex()
    {
        if (!\App\Helpers\SubdominioHelper::esTipo('restaurante')) {
            return redirect()->route('dashboard');
        }

        return view('restaurantes.canje');
    }

    /**
     * Validar cupón ingresado por caja
     */
    public function canjeValidar(Request $request)
    {
        $codigo = $request->codigo;
        if (!$codigo) {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe ingresar un código de cupón']);
        }

        $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
        $user = \Auth::guard($guard)->user();
        if (!$user) {
            return response()->json(['estado' => 403, 'mensaje' => 'No autorizado']);
        }

        // Buscar visita que corresponda al cupón
        $visita = \App\Models\Visita::where('cupon_codigo', $codigo)
            ->where('restaurante_id', $user->restaurante_id)
            ->whereNull('cupon_canjeado_at')
            ->whereNull('deleted_at')
            ->with(['shopper', 'restaurante'])
            ->first();

        if (!$visita) {
            return response()->json(['estado' => 404, 'mensaje' => 'El código de cupón no es válido, ya fue canjeado o no corresponde a su restaurante']);
        }

        // Validar tiempo: hasta 24 horas después de responder encuesta de salida
        $ultimaRespuesta = \App\Models\RespuestaVisita::where('visita_id', $visita->id)
            ->where('encuesta_tipo', 'salida')
            ->orderBy('created_at', 'DESC')
            ->first();

        if (!$ultimaRespuesta) {
            return response()->json(['estado' => 500, 'mensaje' => 'La encuesta de salida no ha sido completada para este cupón']);
        }

        $ahora = \Carbon\Carbon::now();
        $limite = $ultimaRespuesta->created_at->addHours(24);

        if ($ahora->gt($limite)) {
            return response()->json(['estado' => 500, 'mensaje' => 'El cupón ha expirado (más de 24 horas desde la finalización de la encuesta)']);
        }

        return response()->json([
            'estado' => 200,
            'visita_id' => encrypt($visita->id),
            'shopper_name' => $visita->shopper->name,
            'porcentaje_descuento' => $visita->restaurante->porcentaje_descuento ?? 50,
        ]);
    }

    /**
     * Confirmar el canje, registrar montos y recalcular
     */
    public function canjeConfirmar(Request $request)
    {
        $visita_id = decrypt($request->visita_id);
        
        // Limpiar formato de miles (.) para procesar como número
        $totalConsumoRaw = str_replace('.', '', $request->total_consumo);
        $totalConsumo = floatval($totalConsumoRaw);

        if ($totalConsumo <= 0) {
            return response()->json(['estado' => 500, 'mensaje' => 'El monto de consumo total debe ser mayor a 0']);
        }

        $visita = \App\Models\Visita::with(['restaurante'])->find($visita_id);
        if (!$visita || $visita->cupon_canjeado_at) {
            return response()->json(['estado' => 500, 'mensaje' => 'La visita no es válida o el cupón ya fue canjeado']);
        }

        $porcentaje = $visita->restaurante->porcentaje_descuento ?? 50;
        $descuento = $totalConsumo * ($porcentaje / 100.0);
        $pagado = $totalConsumo - $descuento;

        // Registrar montos y canje
        $visita->total_consumo = $totalConsumo;
        $visita->total_descuento = $descuento;
        $visita->total_pagado = $pagado;
        $visita->cupon_canjeado_at = date('Y-m-d H:i:s');

        // Documento tributario (opcional)
        if ($request->guardar_documento == '1') {
            $visita->documento_tipo = $request->documento_tipo;
            $visita->documento_numero = $request->documento_numero;
        }

        $visita->save();

        // Evaluar progreso del plan y posible reinicio de periodo
        $restaurante = $visita->restaurante;
        if ($restaurante) {
            $restaurante->checkAndResetPeriod();
        }

        return response()->json([
            'estado' => 200,
            'mensaje' => '¡Canje realizado con éxito!',
            'descuento' => number_format($descuento, 0, ',', '.'),
            'pagado' => number_format($pagado, 0, ',', '.'),
        ]);
    }
}


