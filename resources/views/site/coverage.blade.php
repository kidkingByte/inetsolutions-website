@php
    $services = ['home' => 'Home Internet', 'business' => 'Business Internet', 'enterprise' => 'Enterprise Internet', 'wifi' => 'Wi-Fi Solution'];
@endphp
<x-site-layout>
    <x-slot name="title">Check Coverage — {{ site('company_name') }}</x-slot>
    <x-site.page-banner title="Is INET Available in Your Area?" subtitle="Check whether INET SOLUTIONS LTD internet services are available at your location." />

    <section class="py-20 bg-white">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('site.partials.alerts')

            <form method="POST" action="{{ route('coverage.check') }}" class="mt-4 space-y-4">
                @csrf
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="full_name" value="Full Name" />
                        <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full" :value="old('full_name')" />
                        <x-input-error :messages="$errors->get('full_name')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="phone" value="Phone Number" />
                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                    </div>
                </div>
                <div>
                    <x-input-label for="region" value="Region *" />
                    <x-text-input id="region" name="region" type="text" class="mt-1 block w-full" :value="old('region')" required />
                    <x-input-error :messages="$errors->get('region')" class="mt-1" />
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="district" value="District" />
                        <x-text-input id="district" name="district" type="text" class="mt-1 block w-full" :value="old('district')" />
                    </div>
                    <div>
                        <x-input-label for="ward" value="Ward" />
                        <x-text-input id="ward" name="ward" type="text" class="mt-1 block w-full" :value="old('ward')" />
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="street" value="Street" />
                        <x-text-input id="street" name="street" type="text" class="mt-1 block w-full" :value="old('street')" />
                    </div>
                    <div>
                        <x-input-label for="service_required" value="Service Required" />
                        <select id="service_required" name="service_required" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-600 focus:ring-brand-600">
                            <option value="">Select…</option>
                            @foreach($services as $value => $label)
                                <option value="{{ $value }}" @selected(old('service_required') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="w-full rounded-lg bg-brand-600 px-6 py-3 text-sm font-semibold text-white hover:bg-brand-700">Check Availability</button>
            </form>

            @isset($result)
                @php
                    $styles = [
                        'available' => ['bg-green-50 border-green-300 text-green-800', 'INET is available in your area.'],
                        'coming_soon' => ['bg-amber-50 border-amber-300 text-amber-800', 'INET service is coming soon to your area.'],
                        'not_available' => ['bg-slate-100 border-slate-300 text-slate-700', 'We currently do not provide service in this area.'],
                    ][$result['status']];
                @endphp
                <div class="mt-8 rounded-xl border p-6 {{ $styles[0] }}">
                    <p class="font-semibold">{{ $result['message'] }}</p>
                    @if($result['status'] === 'available')
                        <a href="{{ route('get-connected', ['service' => $result['area']->service_type ?? null]) }}" class="mt-4 inline-flex rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-700">Get Connected</a>
                    @else
                        <form method="POST" action="{{ route('coverage.notify') }}" class="mt-4 space-y-3">
                            @csrf
                            <p class="text-sm">Leave your details and we'll notify you.</p>
                            <div class="grid sm:grid-cols-2 gap-3">
                                <x-text-input name="full_name" type="text" placeholder="Full name" class="block w-full" :value="old('full_name')" />
                                <x-text-input name="phone" type="text" placeholder="Phone" class="block w-full" :value="old('phone')" />
                            </div>
                            <input type="hidden" name="region" value="{{ old('region') }}">
                            <input type="hidden" name="district" value="{{ old('district') }}">
                            <input type="hidden" name="ward" value="{{ old('ward') }}">
                            <button class="rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-700">Notify Me</button>
                        </form>
                    @endif
                </div>
            @endisset
        </div>
    </section>
</x-site-layout>
