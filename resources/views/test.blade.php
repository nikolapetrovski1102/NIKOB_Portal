@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Test Dynamics OData Model - Panel 1') }}</div>

                    <div class="card-body">
                        <a href="/customers">Get Customer</a>
                        <div id="data">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
