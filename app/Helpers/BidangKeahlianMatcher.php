<?php

namespace App\Helpers;

class BidangKeahlianMatcher
{
    private static $kategori = [
        'ui/ux design' => ['ui/ux design', 'web development', 'mobile app development'],
        'web development' => ['web development', 'mobile app development'],
        'mobile app development' => ['mobile app development', 'web development'],
        'game development' => ['game development', 'artificial intelligence'],
        'artificial intelligence' => ['artificial intelligence', 'machine learning'],
        'machine learning' => ['machine learning', 'artificial intelligence'],
        'devops' => ['devops', 'cloud computing'],
        'cybersecurity' => ['cybersecurity'],
        'cloud computing' => ['cloud computing'],
        'data science' => ['data science', 'machine learning'],
        'kepenulisan' => ['kepenulisan'],
        'olahraga' => ['olahraga'],
        'lainnya' => ['lainnya'],
    ];

    private static $semigrup = [
        'ui/ux design',
        'web development',
        'mobile app development',
        'game development',
        'artificial intelligence',
        'machine learning',
        'cybersecurity',
        'devops',
        'cloud computing',
        'data science',
    ];

    public static function getSkor(string $kategoriLomba, string $kategoriItem): float
    {

        $kategoriLomba = strtolower(trim($kategoriLomba));
        $kategoriItem = strtolower(trim($kategoriItem));

        // \Log::info('Matching kategori', [
        //     'kategori_lomba' => $kategoriLomba,
        //     'kategori_item' => $kategoriItem,
        // ]);


        // Sama persis
        if ($kategoriLomba === $kategoriItem) {
            return 1.0;
        }

        // Termasuk kategori yang relevan
        $related = self::$kategori[$kategoriLomba] ?? [];
        if (in_array($kategoriItem, $related)) {
            return 0.5;
        }

        // Termasuk dalam semigrup bidang teknologi
        if (
            in_array($kategoriLomba, self::$semigrup) &&
            in_array($kategoriItem, self::$semigrup)
        ) {
            // \Log::info('Keduanya termasuk semigrup', [
            //     'kategori_lomba' => $kategoriLomba,
            //     'kategori_item' => $kategoriItem,
            // ]);
            return 0.2;
        }

        // Tidak cocok sama sekali
        return 0.0;
    }
}
