<?php

namespace App\Helpers;

class BidangKeahlianMatcher
{
    private static $kategori = [
        'UI/UX Design' => ['UI/UX Design'],
        'Web Development' => ['Web Development', 'Mobile App Development'],
        'Mobile App Development' => ['Mobile App Development', 'Web Development'],
        'Game Development' => ['Game Development', 'Artificial Intelligence'],
        'Artificial Intelligence' => ['Artificial Intelligence', 'Machine Learning'],
        'Machine Learning' => ['Machine Learning', 'Artificial Intelligence'],
        'DevOps' => ['DevOps', 'Cloud Computing'],
        'Cybersecurity' => ['Cybersecurity'],
        'Cloud Computing' => ['Cloud Computing'],
        'Data Science' => ['Data Science', 'Machine Learning'],
        'Kepenulisan' => ['Kepenulisan'],
        'Olahraga' => ['Olahraga'],
        'Lainnya' => ['Lainnya'],
    ];

    public static function getSkor(string $kategoriLomba, string $kategoriItem): float
    {
        if ($kategoriLomba === $kategoriItem) {
            return 1.0;
        }

        $related = self::$kategori[$kategoriLomba] ?? [];
        if (in_array($kategoriItem, $related)) {
            return 0.5;
        }

        $semigrup = [
            'UI/UX Design',
            'Web Development',
            'Mobile App Development',
            'Game Development',
            'AI',
            'ML',
            'Cybersecurity',
            'DevOps',
            'Cloud'
        ];
        if (
            in_array($kategoriLomba, $semigrup) &&
            in_array($kategoriItem, $semigrup)
        ) {
            return 0.2;
        }

        return 0.0;
    }
}
