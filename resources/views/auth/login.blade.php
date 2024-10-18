<x-guest-layout>
    <!-- Session Status -->
    {{-- <x-auth-session-status class="mb-4"
                           :status="session('status')" /> --}}

    <form class="select-none"
          method="POST"
          action="{{ route('login') }}">
        @csrf

        <div>
            <x-voidoflimbo.input.label class="text-white"
                                       :value="__('Email')">
                <x-voidoflimbo.input.textbox class="block w-full pl-2"
                                             id="email"
                                             name="email"
                                             type="email"
                                             placeholder="email@example.com"
                                             :value="old('email')"
                                             required
                                             autofocus
                                             autocomplete="email">
                    <x-voidoflimbo.svg icon="at" />
                </x-voidoflimbo.input.textbox>
            </x-voidoflimbo.input.label>
            <x-voidoflimbo.input.error class="mt-2"
                                       :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="mt-4">

            <x-voidoflimbo.input.label class="text-white"
                                       :value="__('Password')">
                <x-voidoflimbo.input.textbox class="block w-full pl-2"
                                             id="password"
                                             name="password"
                                             type="password"
                                             placeholder="▪▪▪▪▪▪▪▪▪▪▪▪▪▪▪"
                                             required
                                             autocomplete="current-password">
                    <x-voidoflimbo.svg icon="key" />

                </x-voidoflimbo.input.textbox>
            </x-voidoflimbo.input.label>

            <x-voidoflimbo.input.error class="mt-2"
                                       :messages="$errors->get('password')" />
        </div>

        <div class="mt-8 block select-none">
            <label class="inline-flex cursor-pointer items-center"
                   for="remember_me">
                <input class="cursor-pointer peer rounded border-gray-300 text-lime-600 shadow-sm focus:ring-indigo-500"
                       id="remember_me"
                       name="remember"
                       type="checkbox">
                <div class="ml-2 text-sm  peer-checked:text-lime-500 peer-checked:block text-white">
                    {{ __('Remember me') }}</div>
            </label>
        </div>

        <div class="mt-2 flex items-center justify-between">
            <div>

                @if (Route::has('password.request'))
                    <a class="rounded-md text-sm text-amber-600 underline hover:text-yellow-500 focus:outline-none"
                       href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <x-voidoflimbo.input.button class="font-extrabold px-2 py-1 bg-indigo-600 hover:bg-indigo-500"
                                        type="submit"
                                        color="primary"
                                        textWhite>
                {{ __('Log in') }}
            </x-voidoflimbo.input.button>
        </div>
    </form>
</x-guest-layout>
