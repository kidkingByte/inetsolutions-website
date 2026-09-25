<x-site-layout>
    <x-slot name="title">Acceptable Use Policy — {{ site('company_name') }}</x-slot>
    <x-site.page-banner eyebrow="Legal" title="Acceptable Use Policy" />
    <section class="section">
        <div class="container-x max-w-3xl prose-dark">
            <p class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700">This policy is a template and must be reviewed and finalised by {{ site('company_name') }} before publication.</p>
            <p>This Acceptable Use Policy (AUP) sets out how you may use INET internet and network services.</p>
            <h2>Prohibited Uses</h2>
            <ul>
                <li>Unlawful, fraudulent or harmful use of the service.</li>
                <li>Sharing your connection or credentials without authorisation.</li>
                <li>Transmitting malware or attempting to compromise networks.</li>
                <li>Excessive use that degrades service for other customers (see Fair Usage Policy).</li>
            </ul>
            <h2>Security</h2>
            <p>You are responsible for the security of your devices and network. INET takes network and customer data security seriously.</p>
            <h2>Enforcement</h2>
            <p>INET may suspend or terminate service that breaches this policy or applicable law.</p>
        </div>
    </section>
</x-site-layout>
