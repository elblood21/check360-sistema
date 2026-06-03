<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposCocinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipos = [
            'Chilena' => [
                'icon' => 'icofont-soup-bowl',
                'color_primary' => '#E31A1A',
                'color_secondary' => '#1A5FE3'
            ],
            'Italiana' => [
                'icon' => 'icofont-pizza-slice',
                'color_primary' => '#1D8E3B',
                'color_secondary' => '#E2231A'
            ],
            'Japonesa / Sushi' => [
                'icon' => 'icofont-sushi',
                'color_primary' => '#FF4B2B',
                'color_secondary' => '#FF416C'
            ],
            'China' => [
                'icon' => 'icofont-rice-bowl',
                'color_primary' => '#FFD200',
                'color_secondary' => '#F7971E'
            ],
            'Peruana' => [
                'icon' => 'icofont-fish',
                'color_primary' => '#00c6ff',
                'color_secondary' => '#0072ff'
            ],
            'Mexicana' => [
                'icon' => 'icofont-taco',
                'color_primary' => '#56ab2f',
                'color_secondary' => '#a8e063'
            ],
            'Comida Rápida / Fast Food' => [
                'icon' => 'icofont-fast-food',
                'color_primary' => '#f857a6',
                'color_secondary' => '#ff5858'
            ],
            'Pizzería' => [
                'icon' => 'icofont-pizza',
                'color_primary' => '#FF9900',
                'color_secondary' => '#FF5E62'
            ],
            'Hamburguesería' => [
                'icon' => 'icofont-burger',
                'color_primary' => '#F39C12',
                'color_secondary' => '#D35400'
            ],
            'Cafetería / Salón de Té' => [
                'icon' => 'icofont-coffee-mug',
                'color_primary' => '#4e342e',
                'color_secondary' => '#8d6e63'
            ],
            'Fusión' => [
                'icon' => 'icofont-restaurant-menu',
                'color_primary' => '#8E2DE2',
                'color_secondary' => '#4A00E0'
            ],
            'Mariscos / Pescadería' => [
                'icon' => 'icofont-lobster',
                'color_primary' => '#00B4DB',
                'color_secondary' => '#0083B0'
            ],
            'Carnes / Parrillada' => [
                'icon' => 'icofont-meat',
                'color_primary' => '#c0392b',
                'color_secondary' => '#8e44ad'
            ],
            'Vegetariana / Vegana' => [
                'icon' => 'icofont-leaf',
                'color_primary' => '#11998e',
                'color_secondary' => '#38ef7d'
            ],
            'Árabe / Medio Oriente' => [
                'icon' => 'icofont-restaurant',
                'color_primary' => '#d39e00',
                'color_secondary' => '#7a5802'
            ],
            'Española' => [
                'icon' => 'icofont-restaurant',
                'color_primary' => '#FF5733',
                'color_secondary' => '#C70039'
            ],
            'Francesa' => [
                'icon' => 'icofont-restaurant-menu',
                'color_primary' => '#36D1DC',
                'color_secondary' => '#5B86E5'
            ],
            'Argentina' => [
                'icon' => 'icofont-meat',
                'color_primary' => '#7F00FF',
                'color_secondary' => '#E100FF'
            ],
            'Coreana' => [
                'icon' => 'icofont-bowl',
                'color_primary' => '#f12711',
                'color_secondary' => '#f5af19'
            ],
            'Internacional' => [
                'icon' => 'icofont-globe',
                'color_primary' => '#3a7bd5',
                'color_secondary' => '#3a6073'
            ],
            'Heladería' => [
                'icon' => 'icofont-ice-cream',
                'color_primary' => '#fc67fa',
                'color_secondary' => '#f7c6ff'
            ],
            'Pastelería / Repostería' => [
                'icon' => 'icofont-cake',
                'color_primary' => '#ff758c',
                'color_secondary' => '#ff7eb3'
            ],
            'Pub / Bar' => [
                'icon' => 'icofont-beer',
                'color_primary' => '#F7971E',
                'color_secondary' => '#FFD200'
            ],
            'Otro' => [
                'icon' => 'icofont-food-cart',
                'color_primary' => '#607D8B',
                'color_secondary' => '#455A64'
            ]
        ];

        foreach ($tipos as $name => $attrs) {
            DB::table('tipos_cocinas')->updateOrInsert(
                ['name' => $name],
                [
                    'name' => $name,
                    'icon' => $attrs['icon'],
                    'color_primary' => $attrs['color_primary'],
                    'color_secondary' => $attrs['color_secondary'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}
