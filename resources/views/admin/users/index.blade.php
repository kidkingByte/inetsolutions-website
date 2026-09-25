@extends('layouts.admin')

@section('title', 'Staff & roles')
@section('heading', 'Staff & roles')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-slate-500">People who can sign in to this panel, and what each role is allowed to do.</p>
        <a href="{{ route('admin.users.create') }}" class="btn-primary !py-2.5"><x-site.icon name="users" class="h-4 w-4" /> Add staff member</a>
    </div>

    <div class="card mt-6 !p-0">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[48rem] text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500">
                    <tr><th class="px-5 py-3 font-semibold">Name</th><th class="px-5 py-3 font-semibold">Role</th><th class="px-5 py-3 font-semibold">Status</th><th class="px-5 py-3 font-semibold">Open enquiries</th><th class="px-5 py-3 font-semibold">Last sign-in</th><th class="px-5 py-3"></th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($users as $member)
                        <tr class="{{ $member->is_active ? '' : 'bg-slate-50/70 text-slate-400' }}">
                            <td class="px-5 py-3">
                                <span class="font-semibold {{ $member->is_active ? 'text-ink' : '' }}">{{ $member->name }}</span>
                                @if($member->is(auth()->user()))<span class="ml-1 rounded-full bg-brand-50 px-2 py-0.5 text-xs font-semibold text-brand-700">You</span>@endif
                                <span class="block text-xs text-slate-500">{{ $member->email }}</span>
                            </td>
                            <td class="px-5 py-3"><span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ $member->role_label }}</span></td>
                            <td class="px-5 py-3">
                                @if($member->is_active)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700"><span class="h-2 w-2 rounded-full bg-emerald-500"></span> Active</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500"><span class="h-2 w-2 rounded-full bg-slate-400"></span> Deactivated</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 tabular-nums">{{ $member->open_enquiries_count }}</td>
                            <td class="px-5 py-3 text-slate-500">{{ $member->last_login_at?->diffForHumans() ?? 'Never' }}</td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('admin.users.edit', $member) }}" class="inline-flex min-h-11 items-center px-3 font-semibold text-brand-700 hover:underline">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- What each role can do, straight from config/roles.php --}}
    <section class="card mt-8 !p-0">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="font-bold text-ink">Role permissions</h2>
            <p class="text-sm text-slate-500">Assign the smallest role that lets someone do their job.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[48rem] text-sm">
                <thead class="bg-slate-50 text-xs text-slate-500">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold uppercase tracking-wider">Permission</th>
                        @foreach ($roles as $role)
                            <th class="px-3 py-3 text-center font-semibold" title="{{ $role['description'] }}">{{ $role['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($permissions as $key => $label)
                        <tr>
                            <td class="px-5 py-2.5 text-slate-600">{{ $label }}</td>
                            @foreach ($roles as $role)
                                @php $has = in_array('*', $role['permissions'], true) || in_array($key, $role['permissions'], true); @endphp
                                <td class="px-3 py-2.5 text-center">
                                    @if($has)
                                        <x-site.icon name="check" class="mx-auto h-4 w-4 text-emerald-600" /><span class="sr-only">Yes</span>
                                    @else
                                        <span class="text-slate-300" aria-hidden="true">—</span><span class="sr-only">No</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
