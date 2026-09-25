@if (session('status'))
    <div role="status" class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700">
        <x-site.icon name="check" class="mt-0.5 h-5 w-5 shrink-0 text-emerald-700" />
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div role="alert" class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
        <p class="flex items-center gap-2 font-semibold"><x-site.icon name="alert" class="h-5 w-5" /> Please correct the following:</p>
        <ul class="mt-2 list-inside list-disc space-y-0.5 pl-7">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
