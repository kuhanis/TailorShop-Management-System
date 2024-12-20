@extends('layouts.app')

@section('content')
<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Order Details</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Customer:</strong>
                                    <p>{{ $order->customer->fullname }}</p>
                                </div>
                                <div class="form-group">
                                    <strong>Description:</strong>
                                    <p>{{ $order->description }}</p>
                                </div>
                                <div class="form-group">
                                    <strong>Date Ordered:</strong>
                                    <p>{{ $order->received_on }}</p>
                                </div>
                                <div class="form-group">
                                    <strong>Amount:</strong>
                                    <p>RM {{ number_format($order->amount_charged, 2) }}</p>
                                </div>
                                <div class="form-group">
                                    <strong>Status:</strong>
                                    <div class="mt-1">
                                        @if($order->status === 'to_collect')
                                            @if($order->is_ready_to_collect)
                                                <span class="badge badge-success" style="font-size: 14px; padding: 8px 16px;">
                                                    Ready to Collect
                                                </span>
                                            @else
                                                <span class="badge badge-warning" style="font-size: 14px; padding: 8px 16px;">
                                                    In Progress
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Add your order image/content here -->
                                <div class="order-image">
                                    <img src="{{ asset('path/to/your/image.jpg') }}" alt="Order Image" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 