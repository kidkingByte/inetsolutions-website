<x-site-layout>
    <x-slot name="title">Privacy Policy — {{ site('company_name') }}</x-slot>
    <x-site.page-banner title="Privacy Policy" />
    <section class="py-16 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-slate-700 leading-relaxed space-y-5">
            <p class="text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg p-4">This policy is a template and must be reviewed and finalised by {{ site('company_name') }} before publication.</p>
            <p>{{ site('company_name') }} ("INET", "we", "us") is committed to protecting your privacy. This policy explains how we collect, use and safeguard information when you use our website and services.</p>
            <h2 class="text-xl font-bold text-brand-950">Information We Collect</h2>
            <p>We collect information you provide when you request a connection, check coverage, contact us or open a support ticket — such as your name, phone number, email and location. We may also collect technical data such as IP address and usage information.</p>
            <h2 class="text-xl font-bold text-brand-950">How We Use Your Information</h2>
            <p>We use your information to provide and improve our services, respond to enquiries, manage accounts, process payments and comply with legal obligations.</p>
            <h2 class="text-xl font-bold text-brand-950">Data Sharing</h2>
            <p>We do not sell your personal data. We share it only with service providers who help us operate, and where required by law.</p>
            <h2 class="text-xl font-bold text-brand-950">Contact</h2>
            <p>Questions about this policy? Email <a href="mailto:{{ site('email') }}" class="text-brand-700 font-semibold">{{ site('email') }}</a>.</p>
        </div>
    </section>
</x-site-layout>
