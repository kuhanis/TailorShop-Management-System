@extends('layouts.app')

@push('breadcrumb')
<h3 class="content-header-title">Order Details</h3>
<div class="row breadcrumbs-top">
    <div class="breadcrumb-wrapper col-12">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('orders') }}">Orders</a></li>
            <li class="breadcrumb-item active">Order Details</li>
        </ol>
    </div>
</div>
@endpush

@section('content')
<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Order Details</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                        </ul>
                    </div>
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