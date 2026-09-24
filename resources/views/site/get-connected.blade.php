@php
    $services = ['home' => 'Home Internet', 'business' => 'Business Internet', 'enterprise' => 'Enterprise Internet', 'wifi' => 'Wi-Fi Solution', 'network' => 'Network Installation', 'managed' => 'Managed Connectivity'];
    $type = match ($service) {
        'business' => 'business_inquiry',
        'enterprise', 'managed' => 'enterprise_inquiry',
        default => 'connection_request',
    };
@endphp
<x-site-layout>
    <x-slot name="title">Get Connected — {{ site('company_name') }}</x-slot>
    <x-site.page-banner title="Get Connected Today" subtitle="Ready to get reliable internet? Submit your connection request and our team will contact you." />

    <section class="py-20 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('site.partials.alerts')

            <form method="POST" action="{{ route('get-connected.store') }}" enctype="multipart/form-data" class="mt-4 space-y-5">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="full_name" value="Full Name *" />
                        <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full" :value="old('full_name')" required />
                        <x-input-error :messages="$errors->get('full_name')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="phone" value="Phone Number *" />
                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" required />
                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="service_required" value="Service Required" />
                        <select id="service_required" name="service_required" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-600 focus:ring-brand-600">
                            <option value="">Select…</option>
                            @foreach($services as $value => $label)
                                <option value="{{ $value }}" @selected(old('service_required', $service) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="region" value="Region *" />
                        <x-text-input id="region" name="region" type="text" class="mt-1 block w-full" :value="old('region')" required />
                        <x-input-error :messages="$errors->get('region')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="district" value="District" />
                        <x-text-input id="district" name="district" type="text" class="mt-1 block w-full" :value="old('district')" />
                    </div>
                    <div>
                        <x-input-label for="ward" value="Ward" />
                        <x-text-input id="ward" name="ward" type="text" class="mt-1 block w-full" :value="old('ward')" />
                    </div>
                    <div>
                        <x-input-label for="street" value="Street" />
                        <x-text-input id="street" name="street" type="text" class="mt-1 block w-full" :value="old('street')" />
                    </div>
                </div>

                <div>
                    <x-input-label for="address" value="Installation Address" />
                    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address')" />
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="preferred_package" value="Preferred Package" />
                        <select id="preferred_package" name="preferred_package" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-600 focus:ring-brand-600">
                            <option value="">No preference</option>
                            @foreach($packages as $p)
                                <option value="{{ $p->name }}" @selected(old('preferred_package', $package) === $p->name)>{{ $p->name }} ({{ ucfirst($p->category) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="preferred_installation_date" value="Preferred Installation Date" />
                        <x-text-input id="preferred_installation_date" name="preferred_installation_date" type="date" class="mt-1 block w-full" :value="old('preferred_installation_date')" />
                    </div>
                </div>

                <div>
                    <x-input-label for="message" value="Additional Message" />
                    <textarea id="message" name="message" rows="4" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-600 focus:ring-brand-600">{{ old('message') }}</textarea>
                    <x-input-error :messages="$errors->get('message')" class="mt-1" />
                </div>

                <button type="submit" class="w-full rounded-lg bg-brand-600 px-6 py-3 text-sm font-semibold text-white hover:bg-brand-700">Submit Connection Request</button>
            </form>
        </div>
    </section>
</x-site-layout>
