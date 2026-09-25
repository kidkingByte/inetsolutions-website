@extends('layouts.admin')

@section('title', 'My profile')
@section('heading', 'My profile')

@php
    $granted = config("roles.roles.{$user->role}.permissions", []);
    $permissions = collect(config('roles.permissions'))->filter(fn ($label, $key) => in_array('*', $granted, true) || in_array($key, $granted, true));
@endphp

@section('content')
    <div class="grid gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            {{-- Profile information --}}
            <section class="card">
                <h2 class="text-lg font-bold text-ink">Profile information</h2>
                <p class="mt-1 text-sm text-slate-500">Your name and the email address you use to sign in.</p>

                <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
                    @csrf
                    @method('PATCH')
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="field-label" for="name">Full name</label>
                            <input id="name" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" class="field" @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                            @error('name')<p id="name-error" class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="field-label" for="email">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="field" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                            @error('email')<p id="email-error" class="field-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <button class="btn-primary">Save profile</button>
                        @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" role="status" class="flex items-center gap-1.5 text-sm font-medium text-emerald-700"><x-site.icon name="check" class="h-4 w-4" /> Saved</p>
                        @endif
                    </div>
                </form>
            </section>

            {{-- Password --}}
            <section class="card">
                <h2 class="text-lg font-bold text-ink">Change password</h2>
                <p class="mt-1 text-sm text-slate-500">Use a long password you don't use anywhere else.</p>

                <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-5">
                    @csrf
                    @method('PUT')
                    <div class="max-w-md">
                        <label class="field-label" for="current_password">Current password</label>
                        <input id="current_password" name="current_password" type="password" autocomplete="current-password" class="field" @if($errors->updatePassword->has('current_password')) aria-invalid="true" aria-describedby="current_password-error" @endif>
                        @if($errors->updatePassword->has('current_password'))<p id="current_password-error" class="field-error">{{ $errors->updatePassword->first('current_password') }}</p>@endif
                    </div>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="field-label" for="password">New password</label>
                            <input id="password" name="password" type="password" autocomplete="new-password" class="field" @if($errors->updatePassword->has('password')) aria-invalid="true" aria-describedby="password-error" @endif>
                            @if($errors->updatePassword->has('password'))<p id="password-error" class="field-error">{{ $errors->updatePassword->first('password') }}</p>@endif
                        </div>
                        <div>
                            <label class="field-label" for="password_confirmation">Confirm new password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="field">
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <button class="btn-primary">Update password</button>
                        @if (session('status') === 'password-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" role="status" class="flex items-center gap-1.5 text-sm font-medium text-emerald-700"><x-site.icon name="check" class="h-4 w-4" /> Password updated</p>
                        @endif
                    </div>
                </form>
            </section>
        </div>

        {{-- Account summary --}}
        <aside class="space-y-6">
            <section class="card">
                <div class="flex items-center gap-4">
                    <span class="flex h-14 w-14 items-center justify-center rounded-full bg-signal text-xl font-bold text-white">{{ Str::upper(Str::substr($user->name, 0, 1)) }}</span>
                    <div>
                        <p class="font-bold text-ink">{{ $user->name }}</p>
                        <p class="text-sm text-slate-500">{{ $user->role_label }}</p>
                    </div>
                </div>
                <dl class="mt-6 space-y-3 text-sm">
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Last sign-in</dt><dd class="font-medium text-ink">{{ $user->last_login_at?->format('d M Y, H:i') ?? '—' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Member since</dt><dd class="font-medium text-ink">{{ $user->created_at?->format('d M Y') }}</dd></div>
                </dl>
            </section>

            <section class="card">
                <h2 class="font-bold text-ink">What you can do</h2>
                <p class="mt-1 text-xs text-slate-500">Set by your role. Ask an Administrator if you need more access.</p>
                <ul class="mt-4 space-y-2 text-sm">
                    @foreach ($permissions as $label)
                        <li class="flex gap-2 text-slate-600"><x-site.icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" /> {{ $label }}</li>
                    @endforeach
                </ul>
                <p class="mt-5 border-t border-slate-100 pt-4 text-xs text-slate-500">Leaving the team? An Administrator deactivates or removes staff accounts from <span class="font-medium text-slate-700">Staff &amp; roles</span>.</p>
            </section>
        </aside>
    </div>
@endsection
