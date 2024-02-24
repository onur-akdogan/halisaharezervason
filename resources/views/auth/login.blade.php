<x-guest-layout>
    <div class="flex flex-shrink-0 items-center">

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <div class="d-flex align-items-center" id="hovereffect">
                    <img src="{{asset('logo.png')}}" width="40px" class="mr-2">
                    <div class="logonameyesil ">YEŞİL</div>
                    <div class="logonamesaha">SAHA</div>
                </div>
            </h2>
        </div>
        

    </div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            Şifre

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Beni Hatırla</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            
            <a class="underline pr-3 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('register') }}">
                Kayıt Ol
             </a>
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                   Şifrenizi mi unuttunuz
                </a>
            @endif 
            <x-primary-button class="ml-3">
        Giriş yapılacak
            </x-primary-button>
        </div>
    </form>
    <div class="flex flex-shrink-0 items-center">

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <div class="d-flex align-items-center" id="hovereffect">
                    <a onclick="yakinda()">
                    <img src="{{asset('appstore.png')}}" width="160px" class="mr-2">
                 </a>
                 <a onclick="yakinda()">
                    <img src="{{asset('googleplay.png')}}" width="160px" class="mr-2">

                 </a>
                </div>
            </h2>
        </div>
        

    </div>
 

</x-guest-layout>
<script>
    function yakinda(){
        alert("Çok Yakında");
    }
</script>