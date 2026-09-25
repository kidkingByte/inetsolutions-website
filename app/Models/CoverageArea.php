<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoverageArea extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'region', 'district', 'ward', 'street', 'latitude', 'longitude', 'service_type',
        'technology', 'status', 'installation_available', 'notes',
    ];

    protected $casts = ['installation_available' => 'boolean', 'latitude' => 'float', 'longitude' => 'float'];

    /**
     * Approximate map centre per region, used when an area has no exact coordinates.
     * Zanzibar regions use the middle of the region; mainland regions their regional headquarters.
     */
    public const REGION_CENTRES = [
        'Mjini Magharibi' => [-6.18, 39.24],
        'Kaskazini Unguja' => [-5.88, 39.28],
        'Kusini Unguja' => [-6.28, 39.46],
        'Kaskazini Pemba' => [-5.03, 39.77],
        'Kusini Pemba' => [-5.30, 39.74],
        'Arusha' => [-3.3869, 36.6830], 'Dar es Salaam' => [-6.7924, 39.2083], 'Dodoma' => [-6.1630, 35.7516],
        'Geita' => [-2.8725, 32.2300], 'Iringa' => [-7.7700, 35.6900], 'Kagera' => [-1.3317, 31.8122],
        'Katavi' => [-6.3432, 31.0697], 'Kigoma' => [-4.8769, 29.6267], 'Kilimanjaro' => [-3.3349, 37.3404],
        'Lindi' => [-9.9971, 39.7165], 'Manyara' => [-4.2117, 35.7475], 'Mara' => [-1.5000, 33.8000],
        'Mbeya' => [-8.9094, 33.4608], 'Morogoro' => [-6.8211, 37.6612], 'Mtwara' => [-10.2736, 40.1828],
        'Mwanza' => [-2.5164, 32.9175], 'Njombe' => [-9.3333, 34.7667], 'Pwani' => [-6.7667, 38.9167],
        'Rukwa' => [-7.9667, 31.6167], 'Ruvuma' => [-10.6833, 35.6500], 'Shinyanga' => [-3.6619, 33.4232],
        'Simiyu' => [-2.8000, 33.9833], 'Singida' => [-4.8167, 34.7500], 'Songwe' => [-9.1100, 32.9300],
        'Tabora' => [-5.0167, 32.8000], 'Tanga' => [-5.0689, 39.0988],
    ];

    /** Official administrative regions of Tanzania (26 mainland + 5 Zanzibar). */
    public const TANZANIA_REGIONS = [
        'Arusha', 'Dar es Salaam', 'Dodoma', 'Geita', 'Iringa', 'Kagera', 'Katavi', 'Kigoma',
        'Kilimanjaro', 'Lindi', 'Manyara', 'Mara', 'Mbeya', 'Morogoro', 'Mtwara', 'Mwanza',
        'Njombe', 'Pwani', 'Rukwa', 'Ruvuma', 'Shinyanga', 'Simiyu', 'Singida', 'Songwe',
        'Tabora', 'Tanga', 'Kaskazini Pemba', 'Kusini Pemba', 'Kaskazini Unguja', 'Kusini Unguja',
        'Mjini Magharibi',
    ];

    public const ZANZIBAR_REGIONS = ['Mjini Magharibi', 'Kaskazini Unguja', 'Kusini Unguja', 'Kaskazini Pemba', 'Kusini Pemba'];

    /** Display name for dropdowns; Mjini Magharibi gets its island since the name alone doesn't say it. */
    public static function regionLabel(string $region): string
    {
        return $region === 'Mjini Magharibi' ? 'Mjini Magharibi (Unguja)' : $region;
    }

    /**
     * Region => district => wards, built from the areas admins have recorded.
     * Drives the coverage checker's location dropdowns.
     *
     * @return array<string, array<string, list<string>>>
     */
    public static function locationTree(): array
    {
        $tree = [];

        foreach (static::query()->orderBy('region')->orderBy('district')->orderBy('ward')->get(['region', 'district', 'ward']) as $area) {
            $region = static::canonicalRegion($area->region);
            $tree[$region] ??= [];

            if ($district = trim((string) $area->district)) {
                $tree[$region][$district] ??= [];
                $ward = trim((string) $area->ward);
                if ($ward !== '' && ! in_array($ward, $tree[$region][$district], true)) {
                    $tree[$region][$district][] = $ward;
                }
            }
        }

        return $tree;
    }

    /** Match a typed region to the official spelling so "dar es salaam" and "Dar es Salaam" group together. */
    public static function canonicalRegion(string $region): string
    {
        $region = trim($region);

        foreach (self::TANZANIA_REGIONS as $official) {
            if (strcasecmp($official, $region) === 0) {
                return $official;
            }
        }

        return $region;
    }

    /**
     * Markers for the public coverage map. Areas with exact coordinates get their own marker;
     * the rest are grouped into one marker per region at the region's centre.
     *
     * @return list<array{lat: float, lng: float, title: string, region: string, status: string, places: list<string>}>
     */
    public static function mapPoints(): array
    {
        $points = [];
        $byRegion = [];

        foreach (static::query()->active()->orderBy('region')->orderBy('district')->orderBy('ward')->get() as $area) {
            $region = static::canonicalRegion($area->region);
            $status = $area->status === 'available' ? 'available' : 'coming_soon';

            if ($area->latitude !== null && $area->longitude !== null) {
                $points[] = [
                    'lat' => $area->latitude, 'lng' => $area->longitude,
                    'title' => $area->ward ?: ($area->district ?: static::regionLabel($region)),
                    'region' => $region, 'status' => $status,
                    'places' => array_values(array_filter([$area->district, $area->street])),
                ];

                continue;
            }

            if (! isset(self::REGION_CENTRES[$region])) {
                continue;
            }

            // A region reads "available" as soon as any of its areas is available.
            $byRegion[$region] ??= ['status' => $status, 'places' => []];
            if ($status === 'available') {
                $byRegion[$region]['status'] = 'available';
            }
            $place = collect([$area->ward, $area->district])->filter()->implode(', ');
            if ($place !== '' && ! in_array($place, $byRegion[$region]['places'], true)) {
                $byRegion[$region]['places'][] = $place;
            }
        }

        foreach ($byRegion as $region => $group) {
            [$lat, $lng] = self::REGION_CENTRES[$region];
            $points[] = [
                'lat' => $lat, 'lng' => $lng, 'title' => static::regionLabel($region),
                'region' => $region, 'status' => $group['status'], 'places' => array_slice($group['places'], 0, 8),
            ];
        }

        return $points;
    }

    public function scopeActive($q)
    {
        return $q->whereIn('status', ['available', 'coming_soon', 'under_expansion']);
    }
}
