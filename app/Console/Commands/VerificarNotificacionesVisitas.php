<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Visita;
use App\Models\RespuestaVisita;
use App\Models\EstadoVisita;
use App\Mail\enviarEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class VerificarNotificacionesVisitas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visitas:verificar-notificaciones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica y envía notificaciones de visitas (24h, 2h, post-encuesta)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando verificación de notificaciones de visitas...');
        
        $this->verificarNotificaciones24Horas();
        $this->verificarNotificaciones2Horas();
        $this->verificarVisitasVencidas();
        $this->verificarVisitasNoRealizadas();
        
        $this->info('Verificación completada.');
        
        return Command::SUCCESS;
    }

    /**
     * Verificar visitas que están a 24 horas y no han sido notificadas
     */
    private function verificarNotificaciones24Horas()
    {
        $this->info('Verificando notificaciones de 24 horas...');
        
        $ahora = Carbon::now();
        $en24Horas = $ahora->copy()->addHours(24);
        
        // Buscar visitas pendientes que están entre ahora y 24 horas, y no han sido notificadas
        $visitas = Visita::where('estado_id', 1) // Pendiente
            ->where('notificado_24horas', 0)
            ->whereNull('deleted_at')
            ->get();
        
        $contador = 0;
        foreach ($visitas as $visita) {
            // Combinar fecha y hora
            $fechaHoraVisita = Carbon::parse($visita->fecha_asignacion->format('Y-m-d') . ' ' . $visita->hora_asignacion);
            
            // Si la visita está a menos de 24 horas
            $horasHastaVisita = $ahora->diffInHours($fechaHoraVisita, false);
            
            if ($horasHastaVisita <= 24 && $horasHastaVisita > 0) {
                // Verificar si ya respondió la pre-encuesta
                $tieneRespuestasEntrada = RespuestaVisita::where('visita_id', $visita->id)
                    ->where('encuesta_tipo', 'entrada')
                    ->exists();
                
                if (!$tieneRespuestasEntrada) {
                    // Enviar email
                    $this->enviarEmailNotificacion24h($visita);
                    
                    // Marcar como notificado
                    $visita->notificado_24horas = 1;
                    $visita->notificado_24horas_at = Carbon::now();
                    $visita->save();
                    
                    $contador++;
                }
            }
        }
        
        $this->info("Notificaciones de 24 horas enviadas: {$contador}");
    }

    /**
     * Verificar visitas que están a 2 horas y no han sido notificadas
     */
    private function verificarNotificaciones2Horas()
    {
        $this->info('Verificando notificaciones de 2 horas...');
        
        $ahora = Carbon::now();
        
        // Buscar visitas pendientes que están a menos de 2 horas, ya notificadas 24h pero no 2h
        $visitas = Visita::where('estado_id', 1) // Pendiente
            ->where('notificado_24horas', 1)
            ->where('notificado_2horas', 0)
            ->whereNull('deleted_at')
            ->get();
        
        $contador = 0;
        foreach ($visitas as $visita) {
            // Combinar fecha y hora
            $fechaHoraVisita = Carbon::parse($visita->fecha_asignacion->format('Y-m-d') . ' ' . $visita->hora_asignacion);
            
            // Si la visita está a menos de 2 horas
            $horasHastaVisita = $ahora->diffInHours($fechaHoraVisita, false);
            
            if ($horasHastaVisita <= 2 && $horasHastaVisita > 0) {
                // Verificar si ya respondió la pre-encuesta
                $tieneRespuestasEntrada = RespuestaVisita::where('visita_id', $visita->id)
                    ->where('encuesta_tipo', 'entrada')
                    ->exists();
                
                if (!$tieneRespuestasEntrada) {
                    // Enviar email urgente
                    $this->enviarEmailNotificacion2h($visita);
                    
                    // Marcar como notificado
                    $visita->notificado_2horas = 1;
                    $visita->notificado_2horas_at = Carbon::now();
                    $visita->save();
                    
                    $contador++;
                }
            }
        }
        
        $this->info("Notificaciones de 2 horas enviadas: {$contador}");
    }

    /**
     * Verificar visitas vencidas y rechazar automáticamente si no respondieron pre-encuesta
     */
    private function verificarVisitasVencidas()
    {
        $this->info('Verificando visitas vencidas...');
        
        $ahora = Carbon::now();
        
        // Buscar visitas pendientes cuya fecha/hora ya pasó
        $visitas = Visita::where('estado_id', 1) // Pendiente
            ->whereNull('deleted_at')
            ->get();
        
        $contador = 0;
        foreach ($visitas as $visita) {
            // Combinar fecha y hora
            $fechaHoraVisita = Carbon::parse($visita->fecha_asignacion->format('Y-m-d') . ' ' . $visita->hora_asignacion);
            
            // Si ya pasó la fecha/hora de la visita
            if ($ahora->greaterThan($fechaHoraVisita)) {
                // Verificar si respondió la pre-encuesta
                $tieneRespuestasEntrada = RespuestaVisita::where('visita_id', $visita->id)
                    ->where('encuesta_tipo', 'entrada')
                    ->exists();
                
                if (!$tieneRespuestasEntrada) {
                    // Rechazar automáticamente
                    $visita->estado_id = 6; // Rechazada
                    $visita->motivo_rechazo = 'No respondió la encuesta de expectativas antes de la fecha de la visita';
                    $visita->save();
                    
                    $contador++;
                }
            }
        }
        
        $this->info("Visitas rechazadas automáticamente: {$contador}");
    }

    /**
     * Verificar visitas que no se realizaron (estado 2 o 3 que pasaron 24h sin completar post-encuesta)
     */
    private function verificarVisitasNoRealizadas()
    {
        $this->info('Verificando visitas no realizadas...');
        
        $ahora = Carbon::now();
        
        // Buscar visitas en estado 2 (En espera de visita) o 3 (Visita completada)
        $visitas = Visita::whereIn('estado_id', [2, 3])
            ->whereNull('deleted_at')
            ->get();
        
        $contador = 0;
        foreach ($visitas as $visita) {
            $fechaAsignacionFin = Carbon::parse($visita->fecha_asignacion)->endOfDay();
            
            // Si pasaron más de 24 horas desde el fin del día de la visita
            $horasDesdeVisita = $ahora->diffInHours($fechaAsignacionFin, false);
            
            if ($horasDesdeVisita < -24) {
                // Verificar si respondió la post-encuesta
                $tieneRespuestasSalida = RespuestaVisita::where('visita_id', $visita->id)
                    ->where('encuesta_tipo', 'salida')
                    ->exists();
                
                if (!$tieneRespuestasSalida) {
                    // Marcar como no se realizó
                    $visita->estado_id = 5; // No se realizó
                    $visita->save();
                    
                    $contador++;
                }
            }
        }
        
        $this->info("Visitas marcadas como 'No se realizó': {$contador}");
    }

    /**
     * Enviar email de notificación 24 horas
     */
    private function enviarEmailNotificacion24h($visita)
    {
        try {
            $shopper = $visita->shopper;
            $restaurante = $visita->restaurante;
            
            if (!$shopper || !$restaurante) {
                return;
            }
            
            $data = [
                'vista' => 'mails.visita_notificacion_24h',
                'asunto' => 'Recordatorio: Visita programada para mañana - ' . $restaurante->name,
                'nombre' => $shopper->name,
                'restaurante' => $restaurante->name,
                'fecha' => $visita->fecha_asignacion->format('d/m/Y'),
                'hora' => date('H:i', strtotime($visita->hora_asignacion)),
                'descripcion' => $restaurante->descripcion ?? '',
                'plataforma' => 'https://shopper.check360.cl'
            ];
            
            Mail::to($shopper->email)->send(new enviarEmail($data));
            
            $this->info("Email 24h enviado a: {$shopper->email}");
        } catch (\Exception $e) {
            $this->error("Error al enviar email 24h: " . $e->getMessage());
        }
    }

    /**
     * Enviar email de notificación 2 horas
     */
    private function enviarEmailNotificacion2h($visita)
    {
        try {
            $shopper = $visita->shopper;
            $restaurante = $visita->restaurante;
            
            if (!$shopper || !$restaurante) {
                return;
            }
            
            $data = [
                'vista' => 'mails.visita_notificacion_2h',
                'asunto' => '⚠️ URGENTE: Tu visita es en 2 horas - ' . $restaurante->name,
                'nombre' => $shopper->name,
                'restaurante' => $restaurante->name,
                'fecha' => $visita->fecha_asignacion->format('d/m/Y'),
                'hora' => date('H:i', strtotime($visita->hora_asignacion)),
                'plataforma' => 'https://shopper.check360.cl'
            ];
            
            Mail::to($shopper->email)->send(new enviarEmail($data));
            
            $this->info("Email 2h enviado a: {$shopper->email}");
        } catch (\Exception $e) {
            $this->error("Error al enviar email 2h: " . $e->getMessage());
        }
    }
}
