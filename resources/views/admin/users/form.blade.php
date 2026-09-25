@extends('layouts.admin')

@php $isEdit = $user->exists; $isSelf = $isEdit && $user->is(auth()->user()); @endphp

@section('title', $isEdit ? 'Edit staff member' : 'Add staff member')
@section('heading', $isEdit ? 'Edit '.$user->name : 'Add staff member')

@section('content')
    <a href="{{ route('admin.users.index') }}" class="link-arrow !min-h-0">← Staff & roles</a>

    <div class="mt-4 grid gap-6 xl:grid-cols-3">
        <form method="POST" action="{{ $isEdit ? route('admin.users.update', $user) : route('admin.users.store') }}" class="card space-y-5 xl:col-span-2">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="field-label" for="name">Full name</label>
                    <input id="name" name="name" value="{{ old('name', $user->name) }}" required class="field">
                </div>
                <div>
                    <label class="field-label" for="email">Email (used to sign in)</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="field">
                </div>
                <div>
                    <label class="field-label" for="phone">Phone</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" class="field">
                </div>
                <div>
                    <label class="field-label" for="password">{{ $isEdit ? 'New password' : 'Password' }}</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" class="field" aria-describedby="password-hint">
                    <p id="password-hint" class="mt-1.5 text-xs text-slate-500">{{ $isEdit ? 'Leave blank to keep the current password.' : 'Leave blank to generate a temporary password (shown once).' }}</p>
                </div>
            </div>

            <fieldset>
                <legend class="field-label">Role</legend>
                @if($isSelf)<p class="mb-2 text-xs text-slate-500">You can't change your own role.</p>@endif
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach ($roles as $key => $role)
                        <label class="flex cursor-pointer gap-3 rounded-2xl border p-4 transition has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50 {{ $isSelf && $user->role !== $key ? 'opacity-50' : 'border-slate-200 hover:border-brand-300' }}">
                            <input type="radio" name="role" value="{{ $key }}" class="mt-1 text-brand-600 focus:ring-brand-600" @checked(old('role', $user->role) === $key) @disabled($isSelf && $user->role !== $key)>
                            <span>
                                <span class="block font-semibold text-ink">{{ $role['label'] }}</span>
                                <span class="block text-xs leading-relaxed text-slate-500">{{ $role['description'] }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </fieldset>

            <label class="flex min-h-11 items-center gap-3 text-sm">
                <input type="hidden" name="is_active" value="{{ $isSelf ? 1 : 0 }}">
                <input type="checkbox" name="is_active" value="1" class="h-5 w-5 rounded border-slate-300 text-brand-600 focus:ring-brand-600" @checked(old('is_active', $user->is_active ?? true)) @disabled($isSelf)>
                <span><span class="font-semibold text-ink">Active</span> <span class="text-slate-500">— deactivated staff can't sign in; their history is kept.</span></span>
            </label>

            <div class="flex gap-3 border-t border-slate-100 pt-5">
                <button class="btn-primary">{{ $isEdit ? 'Save changes' : 'Create account' }}</button>
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>

        @if($isEdit && ! $isSelf)
            <aside class="space-y-4">
                <div class="card text-sm text-slate-600">
                    <p><span class="font-semibold text-ink">Last sign-in:</span> {{ $user->last_login_at?->format('d M Y, H:i') ?? 'Never' }}</p>
                    <p class="mt-1"><span class="font-semibold text-ink">Account created:</span> {{ $user->created_at?->format('d M Y') }}</p>
                </div>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete {{ addslashes($user->name) }}? Prefer deactivating to keep their name on past activity.')">
                    @csrf @method('DELETE')
                    <button class="btn w-full border border-red-200 bg-white text-red-700 hover:bg-red-50">Delete account</button>
                </form>
            </aside>
        @endif
    </div>
@endsection
