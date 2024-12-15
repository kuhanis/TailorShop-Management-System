@extends('layouts.app')

@push('page-css')
<script>
$(document).ready(function() {
    $('.editbtn').on('click', function() {
        let id = $(this).data('id');

		$.get(`/orders/${id}/edit`, function(data) {
            $('#edit-order').modal('show');
            $('#edit_id').val(id);
            $('#edit_customer').val(data.customer_id);
            $('#edit_description').val(data.description);
            $('#edit_received_date').val(data.received_on);
            $('#edit_amount').val(data.amount_charged);
        });
    });
});
</script> 
@endpush

@push('breadcrumb')
<h3 class="content-header-title">Orders</h3>
<div class="row breadcrumbs-top">
	<div class="breadcrumb-wrapper col-12">
		<ol class="breadcrumb">
			<li class="breadcrumb-item active">Orders List
			</li>
		</ol>
	</div>
</div>
@endpush

@push('breadcrumb-button')
<x-buttons.primary :text="'Add Order'" :target="'#add-order'" />
@endpush

@section('content')
<section id="html5">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
			<h4 class="card-title">Orders List</h4>
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
                <table class="table table-striped table-bordered dataex-html5-export">
                  <thead>
                    <tr>
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
                        @foreach ($orders as $order)
                          <tr>
                            <td>{{$order->customer ? $order->customer->fullname : '-'}}</td>
                            <td>{{$order->description}}</td>
							<td>{{$order->received_on}}</td>
							<td>RM {{number_format($order->amount_charged, 2)}}</td>
                            <td class="text-center" style="min-width: 100px; padding: 8px;">
                                <div class="d-flex flex-column align-items-center" style="gap: 4px;">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-success status-btn"
                                            data-status="paid"
                                            data-order-id="{{ $order->id }}"
                                            style="min-width: 90px; font-size: 12px; padding: 4px 8px;">
                                        Paid
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-warning status-btn"
                                            data-status="to_collect"
                                            data-order-id="{{ $order->id }}"
                                            style="min-width: 90px; font-size: 12px; padding: 4px 8px;">
                                        <i class="la la-clock"></i> To Collect
                                    </button>
                                </div>
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
						<x-modals.delete :route="'order.destroy'" :title="'Customer Order'" />
                    @endif                    
                  </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>

<!-- Add Order Modal -->
<div class="modal fade text-left" id="add-order" tabindex="-1" role="dialog" aria-labelledby="AddOrder" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<label class="modal-title text-text-bold-600" id="myModalLabel33">Add Order</label>
				<button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" action="{{route('orders')}}">
				@csrf
				<div class="modal-body">
					<label>Select Customer: </label>
					<div class="form-group">
						<div class="position-relative has-icon-left">
							<select name="customer" title="select customer" class="select2 form-control" required>
								<optgroup>								 
									@if (!empty($customers->count()))
										@foreach ($customers as $customer)
										<option value="{{$customer->id}}">{{$customer->fullname}}</option>
										@endforeach
									@endif
								</optgroup>
							</select>
							<div class="form-control-position">
								<i class="la la-user"></i>
							</div>
						</div>
					</div>

					<label>Description: </label>
					<div class="form-group">
						<div class="position-relative has-icon-left">
							<textarea class="form-control" placeholder="Enter description here" name="description" required></textarea>
							<div class="form-control-position">
								<i class="la la-comment"></i>
							</div>
						</div>
					</div>

					<label>Date Ordered: </label>
					<div class="form-group">
						<div class="position-relative has-icon-left">
							<input type="date" name="received_on" class="form-control" required>
							<div class="form-control-position">
								<i class="la la-calendar"></i>
							</div>
						</div>
					</div>

					<label>Amount: </label>
					<div class="form-group">
						<div class="input-group mt-0">
							<div class="input-group-prepend">
								<span class="input-group-text">RM</span>
							</div>
							<input type="number" step="0.01" class="form-control" name="amount_charged" placeholder="Enter amount" required>
							<div class="input-group-append">
								<span class="input-group-text">.00</span>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="reset" class="btn btn-outline-secondary btn-lg" data-dismiss="modal" value="Close">
					<button class="btn btn-outline-primary btn-lg" type="submit">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Edit Order Modal -->
<div class="modal fade text-left" id="edit-order" tabindex="-1" role="dialog" aria-labelledby="EditOrder" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<label class="modal-title text-text-bold-600">Edit Order</label>
				<button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" action="{{route('orders')}}">
				@csrf
				@method('PUT')
				<input type="hidden" name="id" id="edit_id">
				<div class="modal-body">
					<label>Select Customer: </label>
					<div class="form-group">
						<div class="position-relative has-icon-left">
							<select name="customer" id="edit_customer" title="select customer" class="select2 form-control" required>
								<optgroup>								 
									@if (!empty($customers->count()))
										@foreach ($customers as $customer)
										<option value="{{$customer->id}}">{{$customer->fullname}}</option>
										@endforeach
									@endif
								</optgroup>
							</select>
							<div class="form-control-position">
								<i class="la la-user"></i>
							</div>
						</div>
					</div>

					<label>Description: </label>
					<div class="form-group">
						<div class="position-relative has-icon-left">
							<textarea class="form-control" id="edit_description" placeholder="Enter description here" name="description" required></textarea>
							<div class="form-control-position">
								<i class="la la-comment"></i>
							</div>
						</div>
					</div>

					<label>Date Ordered: </label>
					<div class="form-group">
						<div class="position-relative has-icon-left">
							<input type="date" id="edit_received_date" name="received_on" class="form-control" required>
							<div class="form-control-position">
								<i class="la la-calendar"></i>
							</div>
						</div>
					</div>

					<label>Amount: </label>
					<div class="form-group">
						<div class="input-group mt-0">
							<div class="input-group-prepend">
								<span class="input-group-text">RM</span>
							</div>
							<input type="number" step="0.01" id="edit_amount" class="form-control" name="amount_charged" placeholder="Enter amount" required>
							<div class="input-group-append">
								<span class="input-group-text">.00</span>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary btn-lg" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-outline-primary btn-lg">Update</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('page-js')
<script>
$(document).ready(function() {
    $('.editbtn').on('click', function() {
        let id = $(this).data('id');
        let customerId = $(this).data('customer');
        let description = $(this).data('description');
        let receivedDate = $(this).data('received-date');
        let amount = $(this).data('amount');

        $('#edit-order').modal('show');
        $('#edit_id').val(id);
        $('#edit_customer').val(customerId).trigger('change');
        $('#edit_description').val(description);
        $('#edit_received_date').val(receivedDate);
        $('#edit_amount').val(amount);
    });

    $('.deletebtn').on('click', function() {
        $('#delete-modal').modal('show');
        $('#delete-id').val($(this).data('id'));
    });

    // Add new copy link functionality
    $('.copy-link').on('click', function() {
        const link = $(this).data('link');
        navigator.clipboard.writeText(link).then(function() {
            // Optional: Show success message
            toastr.success('Link copied to clipboard!');
        }).catch(function(err) {
            // Optional: Show error message
            toastr.error('Failed to copy link');
            console.error('Failed to copy link: ', err);
        });
    });

    // Add status button functionality
    $('.status-btn').on('click', function() {
        const button = $(this);
        const orderId = button.data('order-id');
        const status = button.data('status');
        
        // Send AJAX request to update status
        $.ajax({
            url: `/orders/${orderId}/status`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function(response) {
                // Toggle active class on just this button
                button.toggleClass('active font-weight-bold');
                toastr.success('Status updated successfully');
            },
            error: function(xhr) {
                toastr.error('Failed to update status');
            }
        });
    });
});
</script>
@endpush


