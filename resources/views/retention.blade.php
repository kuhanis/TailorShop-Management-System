@extends('layouts.app')

@push('breadcrumb')
<h3 class="content-header-title">Orders</h3>
<div class="row breadcrumbs-top">
    <div class="breadcrumb-wrapper col-12">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Orders</li>
            <li class="breadcrumb-item active">Link Retention</li>
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
            <h4 class="card-title">Link Management</h4>
          </div>
          <div class="card-content collapse show">
            <div class="card-body card-dashboard">
                <table class="table table-striped table-bordered dataex-html5-export">
                  <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Link Status</th>
                        <th>Order Link</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($orders as $order)
                      <tr>
                        <td>{{$order->customer ? $order->customer->fullname : '-'}}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ $order->isLinkExpired() ? 'danger' : 'success' }}">
                                @if($order->isLinkExpired())
                                    Expired
                                @else
                                    Active ({{ $order->getDaysUntilExpiry() }} days left)
                                @endif
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center" style="gap: 4px;">
                                <a href="{{ $order->order_link }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-info">
                                    <i class="la la-link"></i> View
                                </a>
                                <button class="btn btn-sm btn-secondary copy-link"
                                        data-link="{{ $order->order_link }}">
                                    <i class="la la-copy"></i> Copy
                                </button>
                            </div>
                        </td>
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

@push('page-js')
<script>
$(document).ready(function() {
    $('.copy-link').on('click', function() {
        const link = $(this).data('link');
        navigator.clipboard.writeText(link).then(function() {
            toastr.success('Link copied to clipboard!');
        }).catch(function(err) {
            toastr.error('Failed to copy link');
        });
    });
});
</script>
@endpush 