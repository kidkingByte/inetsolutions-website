<x-site-layout>
    <x-slot name="title">Acceptable Use Policy — {{ site('company_name') }}</x-slot>
    <x-site.page-banner title="Acceptable Use Policy" />
    <section class="py-16 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-slate-700 leading-relaxed space-y-5">
            <p class="text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg p-4">This policy is a template and must be reviewed and finalised by {{ site('company_name') }} before publication.</p>
            <p>This Acceptable Use Policy (AUP) sets out how you may use INET internet and network services.</p>
            <h2 class="text-xl font-bold text-brand-950">Prohibited Uses</h2>
            <ul class="list-disc list-inside space-y-1">
                <li>Unlawful, fraudulent or harmful use of the service.</li>
                <li>Sharing your connection or credentials without authorisation.</li>
                <li>Transmitting malware or attempting to compromise networks.</li>
                <li>Excessive use that degrades service for other customers (see Fair Usage Policy).</li>
            </ul>
            <h2 class="text-xl font-bold text-brand-950">Security</h2>
            <p>You are responsible for the security of your devices and network. INET takes network and customer data security seriously.</p>
            <h2 class="text-xl font-bold text-brand-950">Enforcement</h2>
            <p>INET may suspend or terminate service that breaches this policy or applicable law.</p>
        </div>
    </section>
</x-site-layout>
