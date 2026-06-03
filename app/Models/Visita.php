<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Visita extends Model
{
    protected $table = 'visitas';

    protected $fillable = [
        'mistery_shopper_id',
        'restaurante_id',
        'fecha_asignacion',
        'hora_asignacion',
        'estado_id',
        'motivo_rechazo',
        'periodo_mes',
        'periodo_anio',
        'tipo_horario',
        'dia_semana',
        'notificado_24horas',
        'notificado_24horas_at',
        'notificado_2horas',
        'notificado_2horas_at',
        'notificado_post',
        'notificado_post_at',
        'visitado_at',
        'cupon_codigo',
        'total_consumo',
        'total_descuento',
        'total_pagado',
        'cupon_canjeado_at',
    ];

    protected $casts = [
        'fecha_asignacion' => 'date',
        'hora_asignacion' => 'datetime:H:i:s',
        'cupon_canjeado_at' => 'datetime',
    ];

    public function shopper()
    {
        return $this->belongsTo(MisteryShopper::class, 'mistery_shopper_id');
    }

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class, 'restaurante_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoVisita::class, 'estado_id');
    }

    public function respuestas()
    {
        return $this->hasMany(RespuestaVisita::class, 'visita_id');
    }

    /**
     * Scope para visitas en un período específico
     */
    public function scopeEnPeriodo($query, $restauranteId, $fechaAsignacion)
    {
        $diasPorPeriodo = Configuracion::obtenerValor('dias_por_periodo', 60);
        $fechaInicio = Carbon::parse($fechaAsignacion)->subDays($diasPorPeriodo);
        
        return $query->where('restaurante_id', $restauranteId)
            ->where('fecha_asignacion', '>=', $fechaInicio)
            ->where('fecha_asignacion', '<=', $fechaAsignacion);
    }

    /**
     * Scope para visitas por tipo de horario
     */
    public function scopePorTipoHorario($query, $tipoHorario)
    {
        return $query->where('tipo_horario', $tipoHorario);
    }

    /**
     * Scope para visitas por día de la semana
     */
    public function scopePorDiaSemana($query, $diaSemana)
    {
        return $query->where('dia_semana', $diaSemana);
    }

    /**
     * Calcular tipo de horario basado en hora_asignacion
     */
    public static function calcularTipoHorario($hora_asignacion)
    {
        $hora = Carbon::parse($hora_asignacion)->format('H:i');
        $horaInt = (int) str_replace(':', '', $hora);

        // Horas punta: 12:00-14:00 y 19:00-21:00
        if (($horaInt >= 1200 && $horaInt <= 1400) || ($horaInt >= 1900 && $horaInt <= 2100)) {
            return 'punta';
        }
        
        // Horas muy temprano/tarde: antes de 10:00 o después de 22:00
        if ($horaInt < 1000 || $horaInt > 2200) {
            return 'bajo';
        }

        return 'normal';
    }

    public static function expirarVisitasVencidas()
    {
        // Solo expirar si estamos en el subdominio shopper
        if (!\App\Helpers\SubdominioHelper::esTipo('shopper')) {
            return;
        }

        // Encontrar visitas en estado 1 (Pendiente) que tengan más de 24 horas desde creadas sin contestar pre-encuesta
        $visitasVencidas = self::where('estado_id', 1)
            ->where('created_at', '<', Carbon::now()->subHours(24))
            ->get();

        foreach ($visitasVencidas as $visita) {
            $visita->estado_id = 5; // No se realizó
            $visita->save();
        }
    }

    protected $appends = ['id_encrypted'];

    public function getIdEncryptedAttribute()
    {
        return encrypt($this->id);
    }
}

