@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
    @php
        $cards = [
            ['label' => 'New Leads',            'value' => $stats['new_leads'],            'hint' => 'Status: new'],
            ['label' => 'Connection Requests',  'value' => $stats['connection_requests'],  'hint' => 'Get connected form'],
            ['label' => 'Coverage Requests',    'value' => $stats['coverage_requests'],    'hint' => 'Checks + notify'],
            ['label' => 'Support Tickets',      'value' => $stats['support_tickets'],      'hint' => 'Report a problem'],
            ['label' => 'Contact Messages',     'value' => $stats['contact_messages'],     'hint' => 'Contact form'],
            ['label' => 'Converted Leads',      'value' => $stats['converted_leads'],      'hint' => 'Became customers'],
            ['label' => 'Active Packages',      'value' => $stats['active_packages'],      'hint' => 'Published'],
            ['label' => 'Live Promotions',      'value' => $stats['active_promotions'],    'hint' => 'Currently active'],
            ['label' => 'Blog Posts',           'value' => $stats['blog_posts'],           'hint' => 'News & articles'],
            ['label' => 'Testimonials',         'value' => $stats['testimonials'],         'hint' => 'Customer reviews'],
            ['label' => 'Coverage Areas',       'value' => $stats['coverage_areas'],       'hint' => 'Mapped locations'],
        ];
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach ($cards as $card)
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="text-2xl font-bold text-brand-700">{{ number_format($card['value']) }}</div>
                <div class="text-sm font-medium text-slate-700 mt-1">{{ $card['label'] }}</div>
                <div class="text-xs text-slate-400">{{ $card['hint'] }}</div>
            </div>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-3 gap-6 mt-6">
        {{-- Recent enquiries --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-semibold">Recent Enquiries</h2>
                <a href="{{ route('admin.enquiries.index') }}" class="text-sm text-brand-700 hover:underline">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-left text-slate-500 bg-slate-50">
                        <tr>
                            <th class="px-5 py-2 font-medium">Name</th>
                            <th class="px-5 py-2 font-medium">Type</th>
                            <th class="px-5 py-2 font-medium">Status</th>
                            <th class="px-5 py-2 font-medium">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentEnquiries as $enquiry)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3">
                                    <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="font-medium text-brand-700 hover:underline">{{ $enquiry->full_name }}</a>
                                </td>
                                <td class="px-5 py-3">{{ $enquiry->type_label }}</td>
                                <td class="px-5 py-3"><span class="inline-block rounded-full bg-slate-100 px-2 py-0.5 text-xs capitalize">{{ $enquiry->status }}</span></td>
                                <td class="px-5 py-3 text-slate-500">{{ $enquiry->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-6 text-center text-slate-400">No enquiries yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Leads by type --}}
        <div class="bg-white rounded-xl border border-slate-200">
            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="font-semibold">Leads by Type</h2>
            </div>
            <div class="p-5 space-y-3">
                @forelse ($leadsByType as $type => $total)
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">{{ \App\Models\Enquiry::TYPES[$type] ?? ucfirst(str_replace('_',' ',$type)) }}</span>
                            <span class="font-semibold">{{ $total }}</span>
                        </div>
                        <div class="mt-1 h-2 rounded bg-slate-100">
                            <div class="h-2 rounded bg-accent" style="width: {{ min(100, $total * 10) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">No data yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
