<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Encuesta;
use App\Models\PreguntaEncuesta;

class PreguntasEncuestasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $encuestaEntrada = Encuesta::where('tipo', 'entrada')->first();
        $encuestaSalida = Encuesta::where('tipo', 'salida')->first();

        if (!$encuestaEntrada || !$encuestaSalida) {
            $this->command->error('Las encuestas de entrada y salida deben existir primero. Ejecute EncuestasSeeder.');
            return;
        }

        // Preguntas de ENTRADA (Expectativas)
        $preguntasEntrada = $this->getPreguntasEntrada();
        $this->crearPreguntas($encuestaEntrada->id, $preguntasEntrada);

        // Preguntas de SALIDA (Experiencia Real)
        $preguntasSalida = $this->getPreguntasSalida();
        $this->crearPreguntas($encuestaSalida->id, $preguntasSalida);
    }

    private function crearPreguntas($encuestaId, $preguntas)
    {
        foreach ($preguntas as $index => $pregunta) {
            PreguntaEncuesta::updateOrCreate(
                [
                    'encuesta_id' => $encuestaId,
                    'orden' => $index + 1,
                ],
                [
                    'texto' => $pregunta['texto'],
                    'tipo_respuesta' => $pregunta['tipo_respuesta'],
                    'dimension' => $pregunta['dimension'],
                    'opciones' => $pregunta['opciones'] ?? null,
                ]
            );
        }
    }

    private function getPreguntasEntrada()
    {
        return [
            // Dimensión 1: Calidad de las comidas (6 preguntas)
            ['texto' => '¿Qué calificación espera darle al sabor de los platos principales que probará?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 1, 'opciones' => ['1: Muy mal sabor', '2', '3', '4', '5: Excelente sabor']],
            ['texto' => '¿Considera que las porciones de comida serán adecuadas en relación con el precio?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 1, 'opciones' => ['1: Porción muy pequeña', '2', '3', '4', '5: Porción muy generosa']],
            ['texto' => '¿Qué opinión espera tener sobre la presentación visual de los platos que recibirá?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 1, 'opciones' => ['1: Presentación poco atractiva', '2', '3', '4', '5: Presentación muy atractiva']],
            ['texto' => '¿Cree que los platos que le servirán estarán a la temperatura adecuada para su disfrute?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 1, 'opciones' => ['1: Demasiado fríos o calientes', '2', '3', '4', '5: Temperatura perfecta']],
            ['texto' => '¿Cómo calificaría la variedad de opciones de platos principales que espera encontrar en nuestro menú?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 1, 'opciones' => ['1: Muy limitada variedad', '2', '3', '4', '5: Muy amplia variedad']],
            ['texto' => 'En general, ¿cómo calificaría la calidad del servicio que espera recibir en este restaurante?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 1, 'opciones' => ['1: Muy Insatisfecho', '2', '3', '4', '5: Muy Satisfecho']],

            // Dimensión 2: Calidad de las bebidas (6 preguntas)
            ['texto' => '¿Qué calificación espera darle al sabor de las bebidas que ordenará?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 2, 'opciones' => ['1: Mal sabor', '2', '3', '4', '5: Excelente sabor']],
            ['texto' => '¿Considera que las porciones de bebidas serán adecuadas en relación con el precio?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 2, 'opciones' => ['1: Porción muy pequeña', '2', '3', '4', '5: Porción muy generosa']],
            ['texto' => '¿Qué opinión espera tener sobre la presentación visual de las bebidas que recibirá?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 2, 'opciones' => ['1: Presentación poco atractiva', '2', '3', '4', '5: Presentación muy atractiva']],
            ['texto' => '¿Cree que las bebidas que le servirán estarán a la temperatura adecuada para su disfrute?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 2, 'opciones' => ['1: Demasiado frías o calientes', '2', '3', '4', '5: Temperatura perfecta']],
            ['texto' => '¿Cómo calificaría la variedad de opciones de bebidas que espera encontrar en nuestro menú?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 2, 'opciones' => ['1: Muy limitada variedad', '2', '3', '4', '5: Muy amplia variedad']],
            ['texto' => 'Si solo tuviera que evaluar las bebidas, ¿consideraría volver al restaurante?', 'tipo_respuesta' => 'si_no', 'dimension' => 2],

            // Dimensión 3: Atención del personal (6 preguntas)
            ['texto' => '¿Cómo calificaría la amabilidad y cortesía del personal que espera encontrar durante su visita?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 3, 'opciones' => ['1: Poco amable', '2', '3', '4', '5: Muy amable']],
            ['texto' => '¿Cree que el personal estará disponible para atender sus necesidades en todo momento?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 3, 'opciones' => ['1: Rara vez disponible', '2', '3', '4', '5: Siempre disponible']],
            ['texto' => '¿Cómo espera que sea la rapidez y eficiencia del personal al tomar su orden?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 3, 'opciones' => ['1: Muy lento y desorganizado', '2', '3', '4', '5: Muy rápido y eficiente']],
            ['texto' => '¿Considera que el personal tendrá suficiente conocimiento sobre los platos del menú y podrá responder sus preguntas?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 3, 'opciones' => ['1: Poco conocimiento', '2', '3', '4', '5: Mucho conocimiento']],
            ['texto' => '¿Espera recibir una despedida amigable y cortés al finalizar su visita?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 3, 'opciones' => ['1: Despedida poco amable', '2', '3', '4', '5: Despedida muy amable']],
            ['texto' => '¿Cree que la atención que recibirá será consistente durante toda su visita?', 'tipo_respuesta' => 'si_no', 'dimension' => 3],

            // Dimensión 4: Ambiente del restaurante (6 preguntas)
            ['texto' => '¿Cómo calificaría el ambiente general del restaurante en términos de confort y comodidad que espera encontrar?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 4, 'opciones' => ['1: Poco confortable', '2', '3', '4', '5: Muy confortable']],
            ['texto' => '¿Considera que la iluminación del restaurante será adecuada para crear una atmósfera agradable?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 4, 'opciones' => ['1: Muy tenue o brillante', '2', '3', '4', '5: Perfecta iluminación']],
            ['texto' => '¿Qué opinión espera tener sobre la música ambiental y su contribución al ambiente del restaurante?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 4, 'opciones' => ['1: Inadecuada o molesta', '2', '3', '4', '5: Agradable y armoniosa']],
            ['texto' => '¿Cree que el nivel de ruido en el restaurante será aceptable para mantener una conversación tranquila?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 4, 'opciones' => ['1: Demasiado ruidoso', '2', '3', '4', '5: Agradablemente tranquilo']],
            ['texto' => '¿Cómo calificaría la limpieza y el orden que espera encontrar en el restaurante?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 4, 'opciones' => ['1: Sucio y desordenado', '2', '3', '4', '5: Impecablemente limpio y ordenado']],
            ['texto' => 'Si solo evaluara el ambiente del restaurante, ¿esperaría recomendar este lugar?', 'tipo_respuesta' => 'si_no', 'dimension' => 4],

            // Dimensión 5: Tiempo de espera (5 preguntas)
            ['texto' => '¿Qué tan razonable espera que sea el tiempo de espera para que tomen su orden?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 5, 'opciones' => ['1: Muy Malo', '2', '3', '4', '5: Excelente']],
            ['texto' => '¿Qué tan razonable espera que sea el tiempo de espera para recibir su comida?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 5, 'opciones' => ['1: Muy Malo', '2', '3', '4', '5: Excelente']],
            ['texto' => '¿Qué tan rápido espera que sea el servicio del personal durante toda su experiencia?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 5, 'opciones' => ['1: Muy Lento', '2', '3', '4', '5: Muy Rápido']],
            ['texto' => '¿Cuánto tiempo espera esperar desde que llegue hasta que le entreguen su comida?', 'tipo_respuesta' => 'opciones', 'dimension' => 5, 'opciones' => ['Menos de 10 minutos', 'Entre 10 y 20 minutos', 'Más de 20 minutos']],
            ['texto' => 'En general, ¿cree que el tiempo de espera afectará su experiencia?', 'tipo_respuesta' => 'si_no', 'dimension' => 5],

            // Dimensión 6: Relación precio-calidad (4 preguntas)
            ['texto' => '¿Qué tan adecuado espera que sea el precio en relación a la calidad de la comida?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 6, 'opciones' => ['1: Muy Malo', '2', '3', '4', '5: Excelente']],
            ['texto' => '¿Qué tan justo espera que sea el precio en relación con la experiencia global del restaurante?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 6, 'opciones' => ['1: Muy Injusto', '2', '3', '4', '5: Muy Justo']],
            ['texto' => '¿Cree que el costo total de su cuenta estará acorde a lo que espera pagar?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 6, 'opciones' => ['1: Totalmente en desacuerdo', '2', '3', '4', '5: Totalmente de acuerdo']],
            ['texto' => 'Si solo considerara el precio, ¿volvería al restaurante?', 'tipo_respuesta' => 'si_no', 'dimension' => 6],

            // Dimensión 7: Factores adicionales (10 preguntas)
            ['texto' => '¿Cómo calificaría la disponibilidad y comodidad del estacionamiento en las cercanías del restaurante?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Muy limitado', '2', '3', '4', '5: Muy amplio y conveniente']],
            ['texto' => '¿Qué opinión espera tener sobre la facilidad para hacer una reserva en nuestro restaurante?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Muy difícil', '2', '3', '4', '5: Muy fácil y conveniente']],
            ['texto' => '¿Cree que el acceso al restaurante será accesible para personas con movilidad reducida o discapacidades?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Poco accesible', '2', '3', '4', '5: Muy accesible']],
            ['texto' => '¿Qué opinión espera tener sobre el entorno exterior del restaurante, como la vista y la arquitectura?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Poco atractivo', '2', '3', '4', '5: Muy atractivo']],
            ['texto' => '¿Cree que la ubicación del restaurante se ajustará a sus preferencias en términos de cercanía y conveniencia?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Mala ubicación', '2', '3', '4', '5: Excelente ubicación']],
            ['texto' => '¿Qué tan fácil espera que sea llegar al restaurante utilizando diferentes opciones de transporte público o privado?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Muy difícil', '2', '3', '4', '5: Muy fácil']],
            ['texto' => '¿Qué opinión espera tener sobre las instalaciones disponibles para niños, como áreas de juego o menús especiales?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Insuficientes', '2', '3', '4', '5: Muy adecuadas']],
            ['texto' => '¿Cree que el restaurante ofrecerá un ambiente adecuado para celebrar eventos especiales o reuniones?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Inadecuado', '2', '3', '4', '5: Muy adecuado']],
            ['texto' => '¿Cómo calificaría el nivel de seguridad y protección que espera recibir durante su visita al restaurante?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Inseguro', '2', '3', '4', '5: Muy seguro']],
            ['texto' => '¿Qué opinión espera tener sobre la disponibilidad y calidad de servicios adicionales, como Wi-Fi gratuito, servicio de valet, entre otros?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Insuficientes', '2', '3', '4', '5: Muy satisfactorios']],
        ];
    }

    private function getPreguntasSalida()
    {
        return [
            // Dimensión 1: Calidad de las comidas (6 preguntas)
            ['texto' => '¿Qué calificación le daría al sabor de los platos principales que probó?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 1, 'opciones' => ['1: Muy mal sabor', '2', '3', '4', '5: Excelente sabor']],
            ['texto' => '¿Considera que las porciones de comida fueron adecuadas en relación con el precio?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 1, 'opciones' => ['1: Porción muy pequeña', '2', '3', '4', '5: Porción muy generosa']],
            ['texto' => '¿Qué opinión tiene sobre la presentación visual de los platos que recibió?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 1, 'opciones' => ['1: Presentación poco atractiva', '2', '3', '4', '5: Presentación muy atractiva']],
            ['texto' => '¿Los platos servidos estaban a la temperatura adecuada para su disfrute?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 1, 'opciones' => ['1: Demasiado fríos o calientes', '2', '3', '4', '5: Temperatura perfecta']],
            ['texto' => '¿Cómo calificaría la variedad de opciones de platos principales en nuestro menú?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 1, 'opciones' => ['1: Muy limitada variedad', '2', '3', '4', '5: Muy amplia variedad']],
            ['texto' => 'En general, ¿cómo calificaría la calidad del servicio que recibió en este restaurante?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 1, 'opciones' => ['1: Muy Insatisfecho', '2', '3', '4', '5: Muy Satisfecho']],

            // Dimensión 2: Calidad de las bebidas (6 preguntas)
            ['texto' => '¿Qué calificación le daría al sabor de las bebidas que ordenó?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 2, 'opciones' => ['1: Mal sabor', '2', '3', '4', '5: Excelente sabor']],
            ['texto' => '¿Considera que las porciones de bebidas fueron adecuadas en relación con el precio?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 2, 'opciones' => ['1: Porción muy pequeña', '2', '3', '4', '5: Porción muy generosa']],
            ['texto' => '¿Qué opinión tiene sobre la presentación visual de las bebidas que recibió?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 2, 'opciones' => ['1: Presentación poco atractiva', '2', '3', '4', '5: Presentación muy atractiva']],
            ['texto' => '¿Las bebidas servidas estaban a la temperatura adecuada para su disfrute?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 2, 'opciones' => ['1: Demasiado frías o calientes', '2', '3', '4', '5: Temperatura perfecta']],
            ['texto' => '¿Cómo calificaría la variedad de opciones de bebidas en nuestro menú?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 2, 'opciones' => ['1: Muy limitada variedad', '2', '3', '4', '5: Muy amplia variedad']],
            ['texto' => 'Si solo tuviera que evaluar las bebidas, ¿consideraría volver al restaurante?', 'tipo_respuesta' => 'si_no', 'dimension' => 2],

            // Dimensión 3: Atención del personal (6 preguntas)
            ['texto' => '¿Cómo calificaría la amabilidad y cortesía del personal durante su visita?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 3, 'opciones' => ['1: Poco amable', '2', '3', '4', '5: Muy amable']],
            ['texto' => '¿El personal estuvo disponible para atender sus necesidades en todo momento?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 3, 'opciones' => ['1: Rara vez disponible', '2', '3', '4', '5: Siempre disponible']],
            ['texto' => '¿Cómo evaluaría la rapidez y eficiencia del personal en tomar su orden?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 3, 'opciones' => ['1: Muy lento y desorganizado', '2', '3', '4', '5: Muy rápido y eficiente']],
            ['texto' => '¿El personal mostró conocimiento sobre los platos del menú y fue capaz de responder sus preguntas?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 3, 'opciones' => ['1: Poco conocimiento', '2', '3', '4', '5: Muy conocimiento']],
            ['texto' => '¿Recibió una despedida amigable y cortés al finalizar su visita?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 3, 'opciones' => ['1: Despedida poco amable', '2', '3', '4', '5: Despedida muy amable']],
            ['texto' => '¿Considera que la atención que recibió fue consistente durante toda su visita?', 'tipo_respuesta' => 'si_no', 'dimension' => 3],

            // Dimensión 4: Ambiente del restaurante (6 preguntas)
            ['texto' => '¿Cómo calificaría el ambiente general del restaurante en términos de confort y comodidad?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 4, 'opciones' => ['1: Poco confortable', '2', '3', '4', '5: Muy confortable']],
            ['texto' => '¿La iluminación del restaurante fue adecuada para crear una atmósfera agradable?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 4, 'opciones' => ['1: Muy tenue o brillante', '2', '3', '4', '5: Perfecta iluminación']],
            ['texto' => '¿Qué opinión tiene sobre la música ambiental y su contribución al ambiente del restaurante?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 4, 'opciones' => ['1: Inadecuada o molesta', '2', '3', '4', '5: Agradable y armoniosa']],
            ['texto' => '¿El nivel de ruido en el restaurante fue aceptable para mantener una conversación tranquila?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 4, 'opciones' => ['1: Demasiado ruidoso', '2', '3', '4', '5: Agradablemente tranquilo']],
            ['texto' => '¿Cómo calificaría la limpieza y orden del restaurante en general?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 4, 'opciones' => ['1: Sucio y desordenado', '2', '3', '4', '5: Impecablemente limpio y ordenado']],
            ['texto' => 'Si solo evaluara el ambiente del restaurante, ¿recomendaría este lugar?', 'tipo_respuesta' => 'si_no', 'dimension' => 4],

            // Dimensión 5: Tiempo de espera (5 preguntas)
            ['texto' => '¿Qué tan razonable le pareció el tiempo de espera para que tomaran su orden?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 5, 'opciones' => ['1: Muy Malo', '2', '3', '4', '5: Excelente']],
            ['texto' => '¿Qué tan razonable fue el tiempo de espera para recibir su comida?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 5, 'opciones' => ['1: Muy Malo', '2', '3', '4', '5: Excelente']],
            ['texto' => '¿Qué tan rápido fue el servicio del personal durante toda su experiencia?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 5, 'opciones' => ['1: Muy Lento', '2', '3', '4', '5: Muy Rápido']],
            ['texto' => '¿Cuánto tiempo aproximadamente esperó desde que llegó hasta que le entregaron su comida?', 'tipo_respuesta' => 'opciones', 'dimension' => 5, 'opciones' => ['Menos de 10 minutos', 'Entre 10 y 20 minutos', 'Más de 20 minutos']],
            ['texto' => 'En general, ¿el tiempo de espera afectó su experiencia?', 'tipo_respuesta' => 'si_no', 'dimension' => 5],

            // Dimensión 6: Relación precio-calidad (4 preguntas)
            ['texto' => '¿Qué tan adecuado le pareció el precio en relación a la calidad de la comida?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 6, 'opciones' => ['1: Muy Malo', '2', '3', '4', '5: Excelente']],
            ['texto' => '¿Qué tan justo le pareció el precio en relación con la experiencia global del restaurante?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 6, 'opciones' => ['1: Muy Injusto', '2', '3', '4', '5: Muy Justo']],
            ['texto' => '¿El costo total de su cuenta estuvo acorde a lo que esperaba pagar?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 6, 'opciones' => ['1: Totalmente en desacuerdo', '2', '3', '4', '5: Totalmente de acuerdo']],
            ['texto' => 'Si solo considerara el precio, ¿volvería al restaurante?', 'tipo_respuesta' => 'si_no', 'dimension' => 6],

            // Dimensión 7: Factores adicionales (10 preguntas)
            ['texto' => '¿Cómo calificaría la disponibilidad y comodidad del estacionamiento en las cercanías del restaurante?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Muy limitado', '2', '3', '4', '5: Muy amplio y conveniente']],
            ['texto' => '¿Qué opinión tiene sobre la facilidad para hacer una reserva en nuestro restaurante?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Muy difícil', '2', '3', '4', '5: Muy fácil y conveniente']],
            ['texto' => '¿Considera que el acceso al restaurante es accesible para personas con movilidad reducida o discapacidades?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Poco accesible', '2', '3', '4', '5: Muy accesible']],
            ['texto' => '¿Qué opinión tiene sobre el entorno exterior del restaurante, como la vista y la arquitectura?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Poco atractivo', '2', '3', '4', '5: Muy atractivo']],
            ['texto' => '¿La ubicación del restaurante se ajusta a sus preferencias en términos de cercanía y conveniencia?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Mala ubicación', '2', '3', '4', '5: Excelente ubicación']],
            ['texto' => '¿Qué tan fácil fue llegar al restaurante utilizando diferentes opciones de transporte público o privado?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Muy difícil', '2', '3', '4', '5: Muy fácil']],
            ['texto' => '¿Qué opinión tiene sobre las instalaciones disponibles para niños, como áreas de juego o menús especiales?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Insuficientes', '2', '3', '4', '5: Muy adecuadas']],
            ['texto' => '¿Considera que el restaurante ofrece un ambiente adecuado para celebrar eventos especiales o reuniones?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Inadecuado', '2', '3', '4', '5: Muy adecuado']],
            ['texto' => '¿Cómo calificaría el nivel de seguridad y protección ofrecido durante su visita al restaurante?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Inseguro', '2', '3', '4', '5: Muy seguro']],
            ['texto' => '¿Qué opinión tiene sobre la disponibilidad y calidad de servicios adicionales, como Wi-Fi gratuito, servicio de valet, entre otros?', 'tipo_respuesta' => 'escala_1_5', 'dimension' => 7, 'opciones' => ['1: Insuficientes', '2', '3', '4', '5: Muy satisfactorios']],
            
            // Pregunta adicional de sugerencias (solo en salida)
            ['texto' => '¿Hay alguna sugerencia que desees hacer en relación a la experiencia vivida?', 'tipo_respuesta' => 'texto_libre', 'dimension' => 7],
        ];
    }
}




