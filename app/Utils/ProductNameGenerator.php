<?php

namespace App\Utils;

class ProductNameGenerator
{
    private static $productTypes = [
        'Laptop' => [
            'prefixes' => ['MacBook', 'ThinkPad', 'Zenbook', 'Surface', 'Latitude'],
            'brands' => ['Pro', 'Air', 'Elite', 'Premium', 'Ultra'],
            'descriptions' => [
                'Potente {name}',
                'Elegante {name}',
                '{name} de última generación',
                'Innovador {name}',
                'Versátil {name}'
            ],
            'description' => [
                'con rendimiento excepcional',
                'para profesionales exigentes',
                'con tecnología avanzada',
                'con diseño premium',
                'ideal para trabajo y entretenimiento',
                'con características únicas',
                'de calidad superior'
            ]
        ],
        'Smartphone' => [
            'prefixes' => ['iPhone', 'Galaxy', 'Pixel', 'Xiaomi', 'OnePlus'],
            'brands' => ['15', '23', 'Ultra', 'Pro', 'Plus'],
            'descriptions' => [
                'Increíble {name}',
                'Revolucionario {name}',
                '{name} inteligente',
                'Potente {name}',
                'Elegante {name}'
            ],
            'description' => [
                'con cámara profesional',
                'con batería de larga duración',
                'con pantalla de alta resolución',
                'con procesador de última generación',
                'con diseño minimalista',
                'con conectividad 5G',
                'resistente al agua'
            ]
        ],
        'Tablet' => [
            'prefixes' => ['iPad', 'Galaxy Tab', 'Surface', 'MatePad', 'Yoga'],
            'brands' => ['Pro', 'Air', 'Plus', 'Ultra', 'Premium'],
            'descriptions' => [
                'Ligera {name}',
                'Versátil {name}',
                '{name} premium',
                'Potente {name}',
                'Moderna {name}'
            ],
            'description' => [
                'perfecta para creativos',
                'para todo tipo de tareas',
                'con pantalla inmersiva',
                'para máxima productividad',
                'con diseño elegante',
                'con lápiz digital incluido',
                'con modo profesional'
            ]
        ],
    ];

    public static function generate(): array
    {
        $productType = array_rand(self::$productTypes);
        $category = self::$productTypes[$productType];
        
        $prefix = $category['prefixes'][array_rand($category['prefixes'])];
        $brand = $category['brands'][array_rand($category['brands'])];
        
        $name = $prefix . ' ' . $brand;
        $description = $category['description'][array_rand($category['description'])];

        return [
            'name' => $name,
            'description' => $description
        ];
    }
}