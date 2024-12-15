@extends('layouts.app')

@push('breadcrumb')
<h3 class="content-header-title">Orders</h3>
<div class="row breadcrumbs-top">
    <div class="breadcrumb-wrapper col-12">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Orders</li>
            <li class="breadcrumb-item active">Order History</li>
        </ol>
    </div>
</div>
@endpush

@section('content')
<section id="html5">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Order History</h4>
          </div>
          <div class="card-content collapse show">
            <div class="card-body card-dashboard">
                <table class="table table-striped table-bordered dataex-html5-export">
                  <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Date Ordered</th>
                        <th>Amount</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Processed By</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($orders as $order)
                      <tr>
                        <td>{{$order->customer ? $order->customer->fullname : '-'}}</td>
                        <td>{{$order->received_on}}</td>
                        <td>RM {{number_format($order->amount_charged, 2)}}</td>
                        <td class="text-center" style="width: 100px;">
                            <span class="badge badge-success" style="display: inline-flex; align-items: center; justify-content: center; min-width: 60px; height: 24px;">Paid</span>
                        </td>
                        <td class="text-center">{{$order->staff ? $order->staff->name : 'Admin'}}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection 