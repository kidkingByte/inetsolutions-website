<x-site-layout>
    <x-slot name="title">Terms & Conditions — {{ site('company_name') }}</x-slot>
    <x-site.page-banner eyebrow="Legal" title="Terms & Conditions" />
    <section class="section">
        <div class="container-x max-w-3xl prose-dark">
            <p class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700">These terms are a template and must be reviewed and finalised by {{ site('company_name') }} before publication.</p>
            <p>By using the {{ site('company_name') }} website and services, you agree to the following terms.</p>
            <h2>Services</h2>
            <p>INET provides internet and ICT connectivity solutions for homes, businesses and organizations. Package details, pricing and availability are subject to change and confirmation.</p>
            <h2>Subscriptions & Payment</h2>
            <p>Connections are subject to coverage, a valid request and acceptance of our service terms. Payment terms and any applicable installation fees are confirmed at sign-up.</p>
            <h2>Acceptable Use</h2>
            <p>You agree to use our services lawfully and in accordance with our Acceptable Use Policy.</p>
            <h2>Liability</h2>
            <p>While we strive for reliable service, INET is not liable for interruptions caused by factors beyond its reasonable control.</p>
        </div>
    </section>
</x-site-layout>
