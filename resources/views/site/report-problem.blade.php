@php
    $problemTypes = ['No Internet','Slow Internet','Router Problem','Wi-Fi Problem','Payment Problem','Package Problem','Installation','Other'];
@endphp
<x-site-layout>
    <x-slot name="title">Report a Problem — {{ site('company_name') }}</x-slot>
    <x-site.page-banner title="Report a Problem" subtitle="Tell us what's happening and our support team will assist you." />

    <section class="py-20 bg-white">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('site.partials.alerts')
            <form method="POST" action="{{ route('support.report.store') }}" enctype="multipart/form-data" class="mt-4 space-y-5">
                @csrf
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="full_name" value="Customer Name *" />
                        <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full" :value="old('full_name')" required />
                        <x-input-error :messages="$errors->get('full_name')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="customer_id" value="Customer ID" />
                        <x-text-input id="customer_id" name="customer_id" type="text" class="mt-1 block w-full" :value="old('customer_id')" />
                    </div>
                    <div>
                        <x-input-label for="phone" value="Phone Number *" />
                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" required />
                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="problem_type" value="Problem Type *" />
                        <select id="problem_type" name="problem_type" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-600 focus:ring-brand-600" required>
                            <option value="">Select…</option>
                            @foreach($problemTypes as $pt)
                                <option value="{{ $pt }}" @selected(old('problem_type') === $pt)>{{ $pt }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('problem_type')" class="mt-1" />
                    </div>
                </div>
                <div>
                    <x-input-label for="address" value="Location" />
                    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address')" />
                </div>
                <div>
                    <x-input-label for="message" value="Description *" />
                    <textarea id="message" name="message" rows="5" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-600 focus:ring-brand-600" required>{{ old('message') }}</textarea>
                    <x-input-error :messages="$errors->get('message')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="attachment" value="Attachment (optional)" />
                    <input id="attachment" name="attachment" type="file" class="mt-1 block w-full text-sm text-slate-600 file:mr-3 file:rounded-md file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-brand-700 hover:file:bg-brand-100" />
                    <x-input-error :messages="$errors->get('attachment')" class="mt-1" />
                </div>
                <button type="submit" class="w-full rounded-lg bg-brand-600 px-6 py-3 text-sm font-semibold text-white hover:bg-brand-700">Submit Support Request</button>
            </form>
        </div>
    </section>
</x-site-layout>
