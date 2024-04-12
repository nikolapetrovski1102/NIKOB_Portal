@extends('layouts.app')

@section('content')
<div class="dark">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 form" id="transaction-status">
                <i class="fa-solid fa-check"></i>
                <h1>{{__("Transaction successfull")}}</h1>
                <p class="success">{{__("Thank you")}}</p>
                <a href="{{route('invoices')}}" class="btn-text">{{__("Go back")}}</a>
            </div>
        </div>
    </div>
</div>
@endsection
