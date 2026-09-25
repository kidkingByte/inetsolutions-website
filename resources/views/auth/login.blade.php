<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="field-label">Email</label>
            <input id="email" class="field" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
            @error('email')<p id="email-error" class="field-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="field-label">Password</label>
                @if (Route::has('password.request'))
                    <a class="mb-1.5 text-sm font-medium text-brand-700 hover:underline" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>
            <input id="password" class="field" type="password" name="password" required autocomplete="current-password" @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
            @error('password')<p id="password-error" class="field-error">{{ $message }}</p>@enderror
        </div>

        <label for="remember_me" class="flex min-h-11 items-center gap-3 text-sm text-slate-600">
            <input id="remember_me" type="checkbox" class="h-5 w-5 rounded border-slate-300 text-brand-600 focus:ring-brand-600" name="remember">
            Keep me signed in on this device
        </label>

        <button type="submit" class="btn-primary w-full py-3.5">Sign in</button>
    </form>
</x-guest-layout>
