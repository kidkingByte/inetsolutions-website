@props(['compact' => false, 'region' => null, 'district' => null, 'ward' => null, 'idPrefix' => 'loc'])
@php
    // Region → district → ward dropdowns. Regions are the official Tanzania list; districts and
    // wards come from the coverage areas admins record. "Not listed" switches to free text so
    // visitors outside known areas can still ask (their request becomes a coverage lead).
    // A level switches to free text only once its parent is chosen and has nothing listed.
    $tree = \App\Models\CoverageArea::locationTree();
    $served = \App\Models\CoverageArea::servedRegions();
    $others = array_diff(\App\Models\CoverageArea::TANZANIA_REGIONS, $served);
    $otherGroups = array_filter([
        'Zanzibar' => array_values(array_intersect(\App\Models\CoverageArea::ZANZIBAR_REGIONS, $others)),
        'Tanzania Mainland' => array_values(array_diff($others, \App\Models\CoverageArea::ZANZIBAR_REGIONS)),
    ]);
    $initial = [
        'region' => \App\Models\CoverageArea::canonicalRegion((string) old('region', $region)),
        'district' => (string) old('district', $district),
        'ward' => (string) old('ward', $ward),
    ];
    $labelClass = $compact ? 'sr-only' : 'field-label';
@endphp
<div x-data="{
        tree: @js((object) $tree),
        region: @js($initial['region']),
        district: '', districtText: '', ward: '', wardText: '',
        get districts() { return Object.keys(this.tree[this.region] || {}) },
        get wards() { return (this.tree[this.region] || {})[this.district] || [] },
        get districtValue() { return this.district === '__other' ? this.districtText : this.district },
        get wardValue() { return this.ward === '__other' ? this.wardText : this.ward },
        pick(value, list, parent) { return value ? (list.includes(value) ? value : '__other') : (parent && ! list.length ? '__other' : '') },
        init() {
            this.district = this.pick(@js($initial['district']), this.districts, this.region);
            if (this.district === '__other') this.districtText = @js($initial['district']);
            this.ward = this.pick(@js($initial['ward']), this.wards, this.district);
            if (this.ward === '__other') this.wardText = @js($initial['ward']);
            this.$watch('region', () => { this.district = this.pick('', this.districts, this.region); this.districtText = ''; this.resetWard(); });
            this.$watch('district', () => this.resetWard());
        },
        resetWard() { this.ward = this.pick('', this.wards, this.district); this.wardText = ''; },
     }"
     {{ $attributes->merge(['class' => $compact ? 'space-y-3' : 'space-y-5']) }}>

    <div>
        <label for="{{ $idPrefix }}-region" class="{{ $labelClass }}">Region<span class="text-brand-600"> *</span></label>
        <select id="{{ $idPrefix }}-region" name="region" x-model="region" required class="field">
            <option value="">Select your region…</option>
            @if($served)
                <optgroup label="Where we operate">
                    @foreach($served as $r)
                        <option value="{{ $r }}" @selected($initial['region'] === $r)>{{ \App\Models\CoverageArea::regionLabel($r) }}</option>
                    @endforeach
                </optgroup>
            @endif
            @foreach($otherGroups as $group => $regions)
                <optgroup label="{{ $group }}">
                    @foreach($regions as $r)
                        <option value="{{ $r }}" @selected($initial['region'] === $r)>{{ \App\Models\CoverageArea::regionLabel($r) }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-2 gap-3 {{ $compact ? '' : 'sm:gap-5' }}">
        {{-- District --}}
        <div>
            <label :for="district === '__other' ? '{{ $idPrefix }}-district-text' : '{{ $idPrefix }}-district'" class="{{ $labelClass }}">District</label>
            <input type="hidden" name="district" :value="districtValue">
            <select id="{{ $idPrefix }}-district" x-model="district" x-show="district !== '__other'" :disabled="! region" class="field disabled:opacity-40">
                <option value="" x-text="region ? 'Select district…' : 'Select region first'"></option>
                <template x-for="d in districts" :key="d"><option :value="d" x-text="d"></option></template>
                <option value="__other">Not listed — type it</option>
            </select>
            <div x-show="district === '__other'" x-cloak class="relative">
                <input id="{{ $idPrefix }}-district-text" x-model="districtText" placeholder="Type your district" class="field pr-10">
                <button type="button" x-show="districts.length" @click="district = ''" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-brand-700" aria-label="Back to district list">
                    <x-site.icon name="x" class="h-4 w-4" />
                </button>
            </div>
        </div>

        {{-- Ward --}}
        <div>
            <label :for="ward === '__other' ? '{{ $idPrefix }}-ward-text' : '{{ $idPrefix }}-ward'" class="{{ $labelClass }}">Ward</label>
            <input type="hidden" name="ward" :value="wardValue">
            <select id="{{ $idPrefix }}-ward" x-model="ward" x-show="ward !== '__other'" :disabled="! districtValue" class="field disabled:opacity-40">
                <option value="" x-text="districtValue ? 'Select ward…' : 'Select district first'"></option>
                <template x-for="w in wards" :key="w"><option :value="w" x-text="w"></option></template>
                <option value="__other">Not listed — type it</option>
            </select>
            <div x-show="ward === '__other'" x-cloak class="relative">
                <input id="{{ $idPrefix }}-ward-text" x-model="wardText" placeholder="Type your ward" class="field pr-10">
                <button type="button" x-show="wards.length" @click="ward = ''" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-brand-700" aria-label="Back to ward list">
                    <x-site.icon name="x" class="h-4 w-4" />
                </button>
            </div>
        </div>
    </div>
</div>
