@extends('layouts.app')

@section('content')
    <div>
        <div class="container m-5">
            <div class="p-3">
                <div class="row">
                    <div class="col-md-12">
                        <iframe src="/pay/form/{{ $transactionID }}" style="height:100%; min-height:450px; width: 100%;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
