@extends('layouts.admin')

@section('title', 'Enquiry #' . $enquiry->id)
@section('heading', 'Enquiry #' . $enquiry->id . ' — ' . $enquiry->type_label)

@section('content')
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-semibold">Details</h2>
                <span class="text-xs text-slate-400">Received {{ $enquiry->created_at->format('d M Y, H:i') }}</span>
            </div>
            @php
                $rows = array_filter([
                    'Full name' => $enquiry->full_name,
                    'Email' => $enquiry->email,
                    'Phone' => $enquiry->phone,
                    'Customer ID' => $enquiry->customer_id,
                    'Service required' => $enquiry->service_required,
                    'Preferred package' => $enquiry->preferred_package,
                    'Preferred install date' => $enquiry->preferred_installation_date,
                    'Region' => $enquiry->region,
                    'District' => $enquiry->district,
                    'Ward' => $enquiry->ward,
                    'Street' => $enquiry->street,
                    'Address' => $enquiry->address,
                    'Subject' => $enquiry->subject,
                    'Problem type' => $enquiry->problem_type,
                    'Source' => $enquiry->source,
                    'IP address' => $enquiry->ip_address,
                ], fn ($v) => $v !== null && $v !== '');
            @endphp
            <dl class="divide-y divide-slate-100">
                @foreach ($rows as $label => $value)
                    <div class="px-5 py-2.5 grid grid-cols-3 gap-3 text-sm">
                        <dt class="text-slate-500">{{ $label }}</dt>
                        <dd class="col-span-2 text-slate-800 break-words">{{ $value }}</dd>
                    </div>
                @endforeach
                @if ($enquiry->message)
                    <div class="px-5 py-3 text-sm">
                        <dt class="text-slate-500 mb-1">Message</dt>
                        <dd class="text-slate-800 whitespace-pre-line">{{ $enquiry->message }}</dd>
                    </div>
                @endif
                @if ($enquiry->attachment)
                    <div class="px-5 py-3 text-sm">
                        <dt class="text-slate-500 mb-1">Attachment</dt>
                        <dd><a class="text-brand-700 hover:underline" href="{{ Storage::url($enquiry->attachment) }}" target="_blank">View file</a></dd>
                    </div>
                @endif
            </dl>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 h-fit">
            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="font-semibold">Manage</h2>
            </div>
            <form method="POST" action="{{ route('admin.enquiries.update', $enquiry) }}" class="p-5 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium">Status</label>
                    <select name="status" class="mt-1 w-full rounded-lg border-slate-300">
                        @foreach (\App\Models\Enquiry::STATUSES as $val)
                            <option value="{{ $val }}" @selected($enquiry->status == $val)>{{ ucfirst(str_replace('_',' ',$val)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Admin notes</label>
                    <textarea name="admin_notes" rows="5" class="mt-1 w-full rounded-lg border-slate-300">{{ old('admin_notes', $enquiry->admin_notes) }}</textarea>
                </div>
                <button class="w-full rounded-lg bg-brand-700 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-800">Save</button>
            </form>
            <div class="px-5 pb-5">
                <a href="{{ route('admin.enquiries.index') }}" class="block text-center rounded-lg border border-slate-300 px-5 py-2 text-sm hover:bg-slate-50">Back to list</a>
            </div>
        </div>
    </div>
@endsection
