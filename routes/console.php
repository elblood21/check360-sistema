<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('app:generar-pre-informe')->everyThirtyMinutes();
Schedule::command('app:generar-evaluacion')->hourly();
Schedule::command('visitas:verificar-notificaciones')->everyTenMinutes();