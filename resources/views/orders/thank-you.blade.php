@extends('layouts.public')

@section('title', 'Order Success')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body text-center">
            <h1 class="display-4 text-success mb-4">Thank You, {{ $order->customer->fullname }}!</h1>
            <p class="lead">Thank you for your order with Nadimah Tailor!</p>
            
            <div class="mt-4 mb-4">
                <h4>Order Details</h4>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered">
                        <tr>
                            <th>Order Date</th>
                            <td>{{ $order->received_on }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $order->description }}</td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td>RM {{ number_format($order->amount_charged, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <p>We appreciate your trust in our services!</p>
        </div>
    </div>
</div>
@endsection