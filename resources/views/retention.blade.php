@extends('layouts.app')

@push('breadcrumb')
<h3 class="content-header-title">Orders</h3>
<div class="row breadcrumbs-top">
    <div class="breadcrumb-wrapper col-12">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Orders</li>
            <li class="breadcrumb-item active">Retention List</li>
        </ol>
    </div>
</div>
@endpush

@section('content')
<!-- Same content as orders.blade.php but with static Paid status -->
<section id="html5">
    <!-- ... rest of the retention view content ... -->
</section>
@endsection 