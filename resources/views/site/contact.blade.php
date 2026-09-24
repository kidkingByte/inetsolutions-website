<x-site-layout>
    <x-slot name="title">Contact Us — {{ site('company_name') }}</x-slot>
    <x-site.page-banner title="Get in Touch With INET" :subtitle="'Talk to us about connectivity for your home, business or organization.'" />

    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-3 gap-12">
            <div class="lg:col-span-1 space-y-6">
                <div>
                    <h3 class="font-bold text-brand-950">{{ site('company_name') }}</h3>
                    <p class="mt-1 text-sm text-slate-600">{{ site('address') }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Phone</p>
                    <a href="tel:{{ preg_replace('/\s+/','', site('phone')) }}" class="font-semibold text-brand-700">{{ site('phone') }}</a>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Email</p>
                    <a href="mailto:{{ site('email') }}" class="font-semibold text-brand-700">{{ site('email') }}</a>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Working Hours</p>
                    <p class="text-sm text-slate-700 whitespace-pre-line">{!! nl2br(e(site('working_hours'))) !!}</p>
                </div>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/','', site('whatsapp','')) }}" target="_blank" rel="noopener" class="inline-flex rounded-lg bg-green-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-600">Chat on WhatsApp</a>
            </div>

            <div class="lg:col-span-2">
                @include('site.partials.alerts')
                <form method="POST" action="{{ route('contact.store') }}" class="mt-4 space-y-4">
                    @csrf
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="full_name" value="Full Name *" />
                            <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full" :value="old('full_name')" required />
                            <x-input-error :messages="$errors->get('full_name')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="phone" value="Phone *" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" required />
                            <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                        </div>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" />
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="subject" value="Subject *" />
                            <x-text-input id="subject" name="subject" type="text" class="mt-1 block w-full" :value="old('subject')" required />
                            <x-input-error :messages="$errors->get('subject')" class="mt-1" />
                        </div>
                    </div>
                    <div>
                        <x-input-label for="message" value="Message *" />
                        <textarea id="message" name="message" rows="5" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-600 focus:ring-brand-600" required>{{ old('message') }}</textarea>
                        <x-input-error :messages="$errors->get('message')" class="mt-1" />
                    </div>
                    <button type="submit" class="rounded-lg bg-brand-600 px-6 py-3 text-sm font-semibold text-white hover:bg-brand-700">Send Message</button>
                </form>
            </div>
        </div>
    </section>
</x-site-layout>
