@extends('welcome', [
    'contentPart' => 'homepage'
    ])

@section('content-part')
    <h1 class="nikob-title">{{__("Homepage")}}</h1>
    <div id="homepage_info">
        <p id="welcome">Добредојде, <b>{{Auth::user()->email}}</b> на Никоб корисничкиот портал.</p>
        <p>{{ $time }}</p>
        <p>{{ $timezone }}</p>
        <p>{{ $mailStatus }}</p>
        <p>Овде можеш да ги платиш фактурите брзо, лесно и безбедно.<br> Преку Никоб корисничкиот портал можете да направите:</p>
        <ul>
            <li><i class="fa-solid fa-star"></i> Проверка на фактури</li>
            <li><i class="fa-solid fa-star"></i> Плаќање на фактури без провизија</li>
            <li><i class="fa-solid fa-star"></i> Плаќање на фактури одеднаш</li>
            <li><i class="fa-solid fa-star"></i> Преземање на фактури од последните 12 месеци</li>
            <li><i class="fa-solid fa-star"></i> Увид во историја на плаќања реализирани преку Никоб Online Плаќање</li>
            <li><i class="fa-solid fa-star"></i> Пријавување на електрнска фактура</li>
            <li><i class="fa-solid fa-star"></i> Пријавување на состојба</li>
        </ul>
    </div>
@endsection
