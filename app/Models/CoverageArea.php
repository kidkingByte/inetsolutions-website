<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoverageArea extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'region', 'district', 'ward', 'street', 'service_type',
        'technology', 'status', 'installation_available', 'notes',
    ];

    protected $casts = ['installation_available' => 'boolean'];

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

    public function scopeActive($q)
    {
        return $q->whereIn('status', ['available', 'coming_soon', 'under_expansion']);
    }
}
