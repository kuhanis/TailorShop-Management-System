@extends('layouts.app')

@push('breadcrumb')
<h3 class="content-header-title">Retention Orders</h3>
<div class="row breadcrumbs-top">
    <div class="breadcrumb-wrapper col-12">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Retention List</li>
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
                    <h4 class="card-title">Retention List</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <table class="table table-striped table-bordered dataex-html5-export">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Order Link</th>
                                    <th>Link Expires In</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($retentions as $retention)
                                    <tr data-customer-id="{{ $retention->order->customer_id }}">
                                        <td>{{$retention->order->customer ? $retention->order->customer->fullname : '-'}}</td>
                                        <td class="text-center" style="min-width: 160px; padding: 8px;">
                                            @if($retention->order->access_token)
                                                <div class="d-flex align-items-center justify-content-center" style="gap: 4px;">
                                                    <a href="{{ $retention->order->order_link }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-info"
                                                       style="min-width: 70px; font-size: 12px; padding: 4px 8px;">
                                                        <i class="la la-link"></i> View
                                                    </a>
                                                    <button class="btn btn-sm btn-secondary copy-link"
                                                            data-link="{{ $retention->order->order_link }}"
                                                            style="min-width: 70px; font-size: 12px; padding: 4px 8px;">
                                                        <i class="la la-copy"></i> Copy
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $daysLeft = now()->diffInDays($retention->link_expire, false);
                                            @endphp
                                            @if($daysLeft > 0)
                                                {{ $daysLeft }} days
                                            @else
                                                <span class="text-danger">Expired</span>
                                            @endif
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
    // Listen for a custom event that will be triggered when an order is added
    $(document).on('orderAdded', function(e, customerId) {
        // Find and remove the row with the matching customer ID
        $('table tbody tr').each(function() {
            let row = $(this);
            if (row.data('customer-id') == customerId) {
                row.fadeOut(400, function() {
                    row.remove();
                });
            }
        });
    });

    // Copy link functionality
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