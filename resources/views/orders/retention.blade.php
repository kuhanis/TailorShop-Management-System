@extends('layouts.app')
@section('title', 'Order Retention')

@section('content')
<section id="html5">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Retention Orders</h4>
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
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Description</th>
                        <th>Date Ordered</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Order Link</th>
                        <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (!empty($orders->count()))
                        @foreach ($orders as $index => $order)
                          <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$order->customer ? $order->customer->fullname : '-'}}</td>
                            <td>{{$order->description}}</td>
                            <td>{{$order->received_on}}</td>
                            <td>RM {{number_format($order->amount_charged, 2)}}</td>
                            <td class="text-center" style="min-width: 100px; padding: 8px;">
                                <span class="badge badge-success" style="font-size: 12px; padding: 6px 12px;">
                                    Paid
                                </span>
                            </td>
                            <td class="text-center" style="min-width: 160px; padding: 8px;">
                                @if($order->access_token)
                                    <div class="d-flex align-items-center justify-content-center" style="gap: 4px;">
                                        <a href="{{ $order->order_link }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-info"
                                           style="min-width: 70px; font-size: 12px; padding: 4px 8px;">
                                            <i class="la la-link"></i> View
                                        </a>
                                        <button class="btn btn-sm btn-secondary copy-link"
                                                data-link="{{ $order->order_link }}"
                                                style="min-width: 70px; font-size: 12px; padding: 4px 8px;">
                                            <i class="la la-copy"></i> Copy
                                        </button>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <a href="#" class="float-md-right" data-toggle="dropdown" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="javascript:void(0)" 
                                        data-id="{{$order->id}}"
                                        data-customer="{{$order->customer_id}}"
                                        data-description="{{$order->description}}"
                                        data-received-date="{{$order->received_on}}"
                                        data-amount="{{$order->amount_charged}}"
                                        class="dropdown-item editbtn">
                                        <i class="la la-edit"></i>Edit
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a data-id="{{$order->id}}" href="javascript:void(0)" class="dropdown-item deletebtn">
                                        <i class="la la-trash"></i>Delete
                                    </a>
                                </div>
                            </td>
                          </tr>
                        @endforeach
                    @endif                    
                  </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection

@push('page-js')
<script>
$(document).ready(function() {
    $('.copy-link').on('click', function() {
        const link = $(this).data('link');
        navigator.clipboard.writeText(link).then(function() {
            toastr.success('Link copied to clipboard!');
        }).catch(function(err) {
            toastr.error('Failed to copy link');
            console.error('Failed to copy link: ', err);
        });
    });
});
</script>
@endpush 