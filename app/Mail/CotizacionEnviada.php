<?php

namespace App\Mail;

use App\Models\Cotizacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CotizacionEnviada extends Mailable
{
    use Queueable, SerializesModels;

    public Cotizacion $cotizacion;

    public function __construct(Cotizacion $cotizacion)
    {
        $this->cotizacion = $cotizacion;
    }

    public function build()
    {
        $urlAceptar = route('cotizaciones.aceptar', $this->cotizacion->id);
        $urlRechazar = route('cotizaciones.rechazar', $this->cotizacion->id);

        return $this->subject('Cotización '.$this->cotizacion->numero)
            ->view('mails.cotizacion_enviada')
            ->with([
                'cotizacion' => $this->cotizacion,
                'urlAceptar' => $urlAceptar,
                'urlRechazar' => $urlRechazar,
            ]);
    }
}

