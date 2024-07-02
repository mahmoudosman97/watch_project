@extends('layouts.main')

@section('content')

<section class="container mt-5 py-3">
    <div class="text-center mt-5 ">

            <h3 class="text-danger">Payment</h3>

            @if (Session::has('order_id') && Session::get('order_id') !=null)
                <h4 class="text-bold -rotate-90 text-warning">Total = $ {{Session::get('total')}}</h4>
            @endif
    </div>
</section>
@endsection