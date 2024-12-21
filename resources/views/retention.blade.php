@extends('layouts.app')

@push('page-css')
<style>
    /* Force cache refresh with comment */
    .card-header {
        padding: 1rem 1.5rem !important;
        border-bottom: 1px solid #ddd !important;
    }
    
    .card-title {
        font-size: 1.2rem !important;
        font-weight: 600 !important;
        color: #333 !important;
        margin: 0 !important;
    }
    
    #search-input {
        height: 32px !important;
        padding: 0.5rem !important;
        border-radius: 4px !important;
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
    
    #search-input:focus {
        border-color: #7367f0 !important;
        box-shadow: none !important;
    }
</style>
@endpush

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
                    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                        <h4 class="card-title mb-0">Link Management</h4>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <!-- Search box above table -->
                        <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
                            <div style="width: 250px;">
                                <input type="text" id="search-input" class="form-control form-control-sm" placeholder="Search...">
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
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
    </div>
</section>
@endsection

@push('page-js')
<script>
$(document).ready(function() {
    // Simple search functionality
    $('#search-input').on('keyup', function() {
        var searchText = $(this).val().toLowerCase();
        
        $('.table tbody tr').each(function() {
            var text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(searchText) > -1);
        });
    });

    // Copy link functionality
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