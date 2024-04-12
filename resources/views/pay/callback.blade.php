<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NIKOB Customer Service') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <!-- <script src="{{asset('js/jquery.mask.min.js')}}"></script> -->

    @vite([ 'resources/js/app.js','resources/sass/app.scss'])

</head>
<body>
    <div id="app" style="height: 100vh">
        <main>
            <div id="payment_status">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-4 form" id="transaction-status">
                            <i class="fa-solid fa-spinner fa-spin-pulse no-border"></i>
                            <h4>{{__("Transaction is processing")}}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <script>
            setTimeout(() => {
                window.parent.location.href = "{{$redirect}}"; 
            }, 3000);
        </script>
    </div>
</body>
