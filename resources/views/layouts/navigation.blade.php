
<nav x-data="{ open: false }" class="bg-light border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <style>
        .logonameyesil {
           font-weight: bold;
           color: #55B230 !important;
            
        }
    
        .logonamesaha {
            font-weight: bold;

            color: #55B230 !important;
        }
        #hovereffect{
         width: 200px;
         font-size: 20px;

     }
        #hovereffect:hover{
         
            font-size: 30px;

        }
        .logonameyesil:hover{
            font-weight: bold;
           color: #55B230 !important;
       }
       .logonamesaha:hover{
        font-weight: bold;

        color: #55B230 !important;
       }
    </style>
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->

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
                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link class="text-decoration-none customcolor" :href="route('halisaha.index')" :active="request()->routeIs('halisaha.index')">
                        Sahalar
                    </x-nav-link>
                    <x-nav-link class="text-decoration-none customcolor" :href="route('halisaha.allindex')" :active="request()->routeIs('halisaha.allindex')">
                        Saha Yönetim
                    </x-nav-link>

                    <x-nav-link class="text-decoration-none customcolor" :href="route('halisaha.addpage')" :active="request()->routeIs('halisaha.addpage')">
                        Saha Ekle
                    </x-nav-link>
                    @if (Auth::user()->type == 2)
                        <x-nav-link class="text-decoration-none customcolor" :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                            Kullanıcılar

                        </x-nav-link>
                        <x-nav-link class="text-decoration-none customcolor" :href="route('admin.banks')" :active="request()->routeIs('admin.banks')">
                            Bankalar

                        </x-nav-link>
                    @endif

                    <x-nav-link class="text-decoration-none customcolor" :href="route('user.musteriler')" :active="request()->routeIs('user.musteriler')">
                        Müşteriler

                    </x-nav-link>
                    <x-nav-link class="text-decoration-none customcolor" :href="route('user.musterileriptal')" :active="request()->routeIs('user.musterileriptal')">
                        İptal Edilenler

                    </x-nav-link>
                    <x-nav-link class="text-decoration-none customcolor" :href="route('user.aboneler')" :active="request()->routeIs('user.aboneler')">
                        Aboneler

                    </x-nav-link>
                </div>

            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center text-sm font-medium text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ml-1">
                                    <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Profil
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    Çıkış Yap
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 underline dark:text-gray-500">Giriş Yap</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="ml-4 text-sm text-gray-700 underline dark:text-gray-500">Kayıt Ol</a>
                    @endif
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="flex items-center -mr-2 sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex justify-center items-center p-2 text-gray-400 rounded-md transition duration-150 ease-in-out hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500">
                    <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>


                    <x-responsive-nav-link :href="route('halisaha.index')">

                        Sahalar
                        </x-nav-link>
                        <x-responsive-nav-link :href="route('halisaha.addpage')">

                            Saha Ekle
                            </x-nav-link>

                            @if (Auth::user()->type == 2)
                                <x-responsive-nav-link :href="route('admin.users')">

                                    Kullanıcılar
                                    </x-nav-link>
                                    <x-responsive-nav-link :href="route('admin.banks')">

                                        Bankalar 
                                        </x-nav-link>        
                            @endif


                            <x-responsive-nav-link :href="route('user.musteriler')">

                                Müşteriler
                                </x-nav-link>


                                <x-responsive-nav-link :href="route('user.musterileriptal')">

                                    İptal Edilenler
                                    </x-nav-link>


                                    <x-responsive-nav-link :href="route('user.aboneler')">

                                        Aboneler
                                        </x-nav-link>




                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf

                                            <x-responsive-nav-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                                Çıkış Yap
                                            </x-responsive-nav-link>
                                        </form>
                </div>
            @else
                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Login') }}
                    </x-responsive-nav-link>
                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')">
                            {{ __('Register') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>
