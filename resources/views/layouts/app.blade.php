<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NIKOB Customer Service') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <!-- <script src="{{asset('js/validate.min.js')}}"></script> -->

    @vite([ 'resources/js/app.js','resources/sass/app.scss'])

</head>
<body>
    <div id="app">
        <div class="header-top second-header d-none d-md-block">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-8 d-none  d-md-block">
                        <div class="header-cta">
                            <ul>
                                <li>
                                    <i class="fa-regular fa-envelope"></i>
                                    <span>nikob@nikob.com.mk</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-phone-flip"></i>
                                    <span>02 / 3088 - 500</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 text-right d-none d-lg-block">
                        <div class="header-cta">
                            <ul>
                                <li>
                                    <i class="fa-regular fa-clock"></i>
                                    <span>{{ __('Mon - Fri') }} 8:00 - 16:00</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 d-none d-md-block">
                        <a href="https://nikob.com.mk/kontakt/?page_id=19" class="top-btn" target="_blank">{{ __("Contact") }}</a>
                    </div>
                </div>
            </div>
        </div>

        <div id="header-sticky" class="menu-area">
            <div class="container d-flex align-items-center">
                <div class="second-menu">
                    <div class="row align-items-center">
                        <div class="col-xl-3 col-lg-3">
                            <div class="logo">
                                <a href="https://www.nikob.com.mk/" class="navbar-brand logo-black">
                                    <img src="{{asset('images/logo_'.Config::get('app.locale').'.png')}}" alt="Никоб" title="Обезбедување">
                                </a>
                            </div>
                        </div>
                    </div>
               </div>
               <div class="flex justify-center pt-8 sm:justify-start sm:pt-0" id="lang-switcher">
                    <a class="ml-1 underline ml-2 mr-2" href="/language/en">EN</a>
                    <a class="ml-1 underline ml-2 mr-2" href="/language/mk">MK</a>
                    <a class="ml-1 underline ml-2 mr-2" href="/language/sq">SQ</a>
                </div>
                <div>
                    @if(isset($menu))
                    <nav class="navbar navbar-light bg-light toggle-menu">
                        <div class="container-fluid">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#mobile-menu" aria-controls="mobile-menu"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fas fa-bars"></i>
                            </button>
                        </div>
                    </nav>
                    @endif
                </div>
             </div>
          </div>

        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
