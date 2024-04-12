@extends('layouts.app')

@section('content')
    <div>
        <div class="container m-5 einvoicecontainer">
            <div class="p-3">
                <div class="row">
                    <div class="col-md-1 col-xs-2 form-inline text-right">
                        <div style="width:32px"></div>
                    </div>
                    <div class="col-md-10 col-xs-9">
                        <a href="#" class="go-back-btn" onclick="history.back()"><i class="fa-sharp fa-solid fa-chevron-left"></i> {{__("Go Back")}}</a>
                        <br><br>
                        <h2>
                            {{ __('Повеќе информации') }}
                        </h2>
                        <br>
                        {{ __(' НИКОБ порталот нуди интегрирани онлјан услуги за своите клиенти за лесна достава и online плаќање на фактурите.') }}
                        <br><br>
                        <ul class="mt-2">
                            <li>Брзо и едноставно плаќање на фактура без регистрација на профил и без провизија:</li>
                            <li>Пријава за добивање на фактури по електронски пат</li>
                            <li>Услуги со регистрација на НИКОБ кориснички портал: </li>
                            <li>
                                Услуги со регистрација на НИКОБ кориснички портал:
                                <ul class="mt-2">
                                    <li>преглед на фактури</li>
                                    <li> преземање на фактура во електронски PDF формат</li>
                                    <li> плаќање на фактури без провизија</li>
                                    <li> регистрација на повеќе клиенти на еден профил</li>
                                    <li> плаќање на повеќе фактури одеднаш
                                    <li> преглед на историја на плаќања
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
