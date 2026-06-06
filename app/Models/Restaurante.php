<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurante extends Model
{
    use SoftDeletes;

    protected $table = 'restaurantes';

    protected $fillable = [
        'name',
        'direccion',
        'ciudad_id',
        'telefono',
        'email',
        'tipo_cocina_id',
        'rango_ticket_promedio',
        'capacidad_restaurante',
        'plan_activo',
        'plan_inicio',
        'plan_fin',
        'periodo_inicio',
        'periodo_fin',
        'porcentaje_descuento',
        'carta_tipo',
        'carta_url',
        'carta_imagenes',
        'logo',
        'imagenes',
        'social_facebook',
        'social_instagram',
        'social_tiktok',
        'horario_peak',
        'estado',
        'aprobado',
        'aprobado_por',
        'aprobado_at',
    ];

    protected $casts = [
        'plan_activo' => 'boolean',
        'carta_imagenes' => 'array',
        'imagenes' => 'array',
        'horario_peak' => 'array',
    ];

    public function tipoCocina()
    {
        return $this->belongsTo(TiposCocina::class, 'tipo_cocina_id');
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id');
    }

    public function opciones()
    {
        return $this->hasMany(RestauranteOpciones::class, 'restaurante_id');
    }

    public function admin()
    {
        return $this->hasOne(RestauranteUser::class, 'restaurante_id')->whereNull('deleted_at')->orderBy('id', 'ASC');
    }

    protected $appends = ['id_encrypted', 'visitas_periodo_count'];

    public function getIdEncryptedAttribute()
    {
        return encrypt($this->id);
    }

    public function getVisitasPeriodoCountAttribute()
    {
        if (!$this->plan_activo || !$this->periodo_inicio || !$this->periodo_fin) {
            return 0;
        }

        // Auto-corrección para evitar discrepancias de zona horaria (UTC vs America/Santiago) en el primer período
        if ($this->periodo_inicio === $this->plan_inicio) {
            $primerDia = \Carbon\Carbon::parse($this->periodo_inicio)->subDay()->toDateString();
            $existeVisitaMismoPeriodo = \App\Models\Visita::where('restaurante_id', $this->id)
                ->whereIn('estado_id', [1, 2, 3, 4])
                ->where('fecha_asignacion', $primerDia)
                ->whereNull('deleted_at')
                ->exists();

            if ($existeVisitaMismoPeriodo) {
                // Actualizar localmente y persistir en BD para futuros cálculos
                $this->periodo_inicio = $primerDia;
                $this->plan_inicio = $primerDia;
                $this->save();
            }
        }

        return \App\Models\Visita::where('restaurante_id', $this->id)
            ->whereIn('estado_id', [1, 2, 3, 4]) // Pendientes y finalizadas
            ->where('fecha_asignacion', '>=', $this->periodo_inicio)
            ->where('fecha_asignacion', '<=', $this->periodo_fin)
            ->whereNull('deleted_at')
            ->count();
    }

    /**
     * Evalúa y reinicia el periodo de evaluación de 60 días o 12 visitas si corresponde.
     */
    public function checkAndResetPeriod()
    {
        if (!$this->plan_activo) {
            return;
        }

        $hoy = \Carbon\Carbon::today('America/Santiago');

        // Si el plan general ya expiró (6 meses)
        if ($this->plan_fin && $hoy->greaterThan(\Carbon\Carbon::parse($this->plan_fin)->endOfDay())) {
            $this->plan_activo = false;
            $this->save();
            return;
        }

        // Si no hay periodo actual inicializado, inicializarlo
        if (!$this->periodo_inicio || !$this->periodo_fin) {
            $this->periodo_inicio = $this->plan_inicio ?? $hoy->toDateString();
            $this->periodo_fin = \Carbon\Carbon::parse($this->periodo_inicio)->addDays(60)->toDateString();
            $this->save();
        }

        // Contar visitas pendientes y finalizadas (estado_id 1, 2, 3, 4) en el periodo actual
        $visitasCount = \App\Models\Visita::where('restaurante_id', $this->id)
            ->whereIn('estado_id', [1, 2, 3, 4]) // Pendientes y finalizadas
            ->where('fecha_asignacion', '>=', $this->periodo_inicio)
            ->where('fecha_asignacion', '<=', $this->periodo_fin)
            ->whereNull('deleted_at')
            ->count();

        $diasExpirados = $hoy->greaterThan(\Carbon\Carbon::parse($this->periodo_fin));

        if ($visitasCount >= 12 || $diasExpirados) {
            // Reiniciar periodo de 60 días
            $this->periodo_inicio = $hoy->toDateString();
            $this->periodo_fin = $hoy->copy()->addDays(60)->toDateString();
            $this->save();
        }
    }
}



