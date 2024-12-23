@extends('layouts.app')
@section('title', 'Orders')

@push('page-css')
<script>
$(document).ready(function() {
    $('.editbtn').on('click', function() {
        let id = $(this).data('id');
        
        // Fetch order data via AJAX
        $.get(`/orders/${id}/edit`, function(data) {
            console.log('Order data:', data); // For debugging
            
            $('#edit-order').modal('show');
            $('#edit_id').val(data.id);
            
            // Set customer name and ID
            if (data.customer) {
                $('#edit_customer_name').val(data.customer.fullname);
                $('#edit_customer').val(data.customer.id);
            }
            
            // Fill in other order details
            $('#edit_description').val(data.description);
            $('#edit_received_date').val(data.received_on);
            $('#edit_amount').val(data.amount_charged);
            
            // Update image preview if exists
            const previewContainer = $('#edit-image-preview');
            const imageLabel = $('#edit-order .custom-file-label');
            
            if (data.image_path) {
                previewContainer.find('img').attr('src', `/storage/${data.image_path}`);
                previewContainer.show();
                imageLabel.text(data.image_path.split('/').pop());
            } else {
                previewContainer.hide();
                imageLabel.text('Choose file');
            }
        });
    });
});
</script> 
@endpush

@push('breadcrumb-button')
<x-buttons.primary :text="'Add Order'" :target="'#add-order'" />
@endpush

@section('content')
<section id="html5">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <!-- Title and Add Order button -->
                <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #ddd;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="font-size: 1.1rem; margin: 0;">Orders List</h4>
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-order">
                            <i class="la la-plus"></i> Add Order
                        </button>
                    </div>
                </div>

                <!-- Search box and table -->
                <div class="card-header">
                    <div style="display: flex; justify-content: flex-end; align-items: center; width: 100%;">
                        <div style="width: 250px;">
                            <input type="text" id="search-input" class="form-control form-control-sm" placeholder="Search...">
                        </div>
                    </div>
                </div>

                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer</th>
                                        <th>Description</th>
                                        <th>Image</th>
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
                                                <td class="text-center">
                                                    @if($order->image_path)
                                                        <img src="{{ asset('storage/' . $order->image_path) }}" 
                                                             alt="Order Image" 
                                                             class="img-thumbnail order-image"
                                                             style="max-width: 100px; max-height: 100px; cursor: pointer"
                                                             onclick="showImageModal('{{ asset('storage/' . $order->image_path) }}', '{{ $order->description }}')"
                                                        >
                                                    @else
                                                        <span class="text-muted">No image</span>
                                                    @endif
                                                </td>
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
                                                        @if($order->is_ready_to_collect)
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-success status-btn disabled"
                                                                    style="min-width: 90px; font-size: 12px; padding: 4px 8px; opacity: 1;"
                                                                    disabled>
                                                                <i class="la la-check"></i> Ready
                                                            </button>
                                                        @else
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-outline-warning status-btn"
                                                                    data-status="to_collect"
                                                                    data-order-id="{{ $order->id }}"
                                                                    style="min-width: 90px; font-size: 12px; padding: 4px 8px;">
                                                                <i class="la la-clock"></i> To Collect
                                                            </button>
                                                        @endif
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
			<form method="post" action="{{route('orders')}}" id="add-order-form" enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<label>Select Customer: </label>
					<div class="form-group">
						<div class="position-relative has-icon-left">
							<select name="customer" title="select customer" class="select2 form-control" required>
								<optgroup label="New Customers">                             
									@foreach ($newCustomers as $customer)
										<option value="{{$customer->id}}">{{$customer->fullname}}</option>
									@endforeach
								</optgroup>
								<optgroup label="Existing Customers">
									@foreach ($retentionCustomers as $customer)
										<option value="{{$customer->id}}">{{$customer->fullname}}</option>
									@endforeach
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

					<label>Upload Image: </label>
					<div class="form-group">
						<div class="position-relative">
							<div class="custom-file">
								<input type="file" class="custom-file-input" id="order-image" name="image" accept="image/*">
								<label class="custom-file-label" for="order-image">Choose file</label>
							</div>
							<div id="image-preview" class="mt-2" style="display: none;">
								<img src="" alt="Preview" style="max-width: 200px; max-height: 200px;">
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
					<button type="button" class="btn btn-outline-secondary btn-lg" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-outline-primary btn-lg">Submit</button>
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
			<form method="post" action="{{route('orders')}}" enctype="multipart/form-data">
				@csrf
				@method('PUT')
				<input type="hidden" name="id" id="edit_id">
				<div class="modal-body">
					<label>Customer: </label>
					<div class="form-group">
						<div class="position-relative has-icon-left">
							<input type="text" 
								   id="edit_customer_name" 
								   class="form-control" 
								   readonly 
								   style="background-color: #f8f9fa; cursor: not-allowed;">
							<input type="hidden" name="customer" id="edit_customer">
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

					<label>Image: </label>
					<div class="form-group">
						<div class="position-relative">
							<div class="custom-file">
								<input type="file" class="custom-file-input" id="edit-order-image" name="image" accept="image/*">
								<label class="custom-file-label" for="edit-order-image">Choose file</label>
							</div>
							<div id="edit-image-preview" class="mt-2" style="display: none;">
								<img src="" alt="Preview" style="max-width: 200px; max-height: 200px;">
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

<!-- Image Preview Modal -->
<div class="modal fade" id="orderImageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Image</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center p-2">
                <div class="image-container">
                    <img src="" alt="Order Image" class="modal-image img-fluid">
                </div>
                <div class="mt-2">
                    <p class="order-description mb-0"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-white" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Confirmation Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="confirmation-message">Are you sure you want to mark as paid?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-white" data-dismiss="modal">CANCEL</button>
                <button type="button" class="btn btn-danger text-white confirm-payment">PAID</button>
            </div>
        </div>
    </div>
</div>

<!-- To Collect Confirmation Modal -->
<div class="modal fade" id="collectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">To Collect Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="confirmation-message">Are you sure you want to mark as ready to collect?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-white" data-dismiss="modal">CANCEL</button>
                <button type="button" class="btn btn-warning text-white confirm-collect">TO COLLECT</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('page-js')
<script>
// Define showImageModal function globally
function showImageModal(imageUrl, description) {
    const modal = $('#orderImageModal');
    modal.find('.modal-body img').attr('src', imageUrl);
    modal.find('.order-description').text(description);
    modal.modal('show');
}

$(document).ready(function() {
    $('.deletebtn').on('click', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the order! This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('order.destroy') }}",
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'Order has been deleted.',
                            'success'
                        ).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'Something went wrong.',
                            'error'
                        );
                    }
                });
            }
        });
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

    // Update the status button functionality
    $('.status-btn').on('click', function() {
        const button = $(this);
        const orderId = button.data('order-id');
        const status = button.data('status');
        
        if (status === 'paid') {
            $('#paymentModal').data('orderButton', button);
            $('#paymentModal').modal('show');
        } else if (status === 'to_collect') {
            $('#collectModal').data('orderButton', button);
            $('#collectModal').modal('show');
        }
    });

    // Add payment confirmation handler
    $('.confirm-payment').on('click', function() {
        const modal = $('#paymentModal');
        const button = modal.data('orderButton');
        const orderId = button.data('order-id');
        const status = button.data('status');
        
        modal.modal('hide');
        updateOrderStatus(button, orderId, status);
    });

    // Add collect confirmation handler
    $('.confirm-collect').on('click', function() {
        const modal = $('#collectModal');
        const button = modal.data('orderButton');
        const orderId = button.data('order-id');
        const status = button.data('status');
        
        modal.modal('hide');
        updateOrderStatus(button, orderId, status);
    });

    // Keep the updateOrderStatus function as is
    function updateOrderStatus(button, orderId, status) {
        button.prop('disabled', true);
        
        $.ajax({
            url: `/orders/${orderId}/status`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                status: status
            },
            success: function(response) {
                if (response.success) {
                    if (status === 'paid') {
                        button.closest('tr').fadeOut(400, function() {
                            $(this).remove();
                        });
                        toastr.success('Order moved to retention');
                    } else {
                        // Replace the "To Collect" button with "Ready" button
                        const newButton = $(`
                            <button type="button" 
                                    class="btn btn-sm btn-success status-btn disabled"
                                    style="min-width: 90px; font-size: 12px; padding: 4px 8px; opacity: 1;"
                                    disabled>
                                <i class="la la-check"></i> Ready
                            </button>
                        `);
                        button.replaceWith(newButton);
                        toastr.success('Status updated successfully');
                    }
                } else {
                    toastr.error(response.message || 'Failed to update status');
                    button.prop('disabled', false);
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                toastr.error('Failed to update status. Please try again.');
                button.prop('disabled', false);
            }
        });
    }

    document.getElementById('order-image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('image-preview');
        const previewImg = preview.querySelector('img');
        const label = document.querySelector('.custom-file-label');

        if (file) {
            label.textContent = file.name;
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            label.textContent = 'Choose file';
            preview.style.display = 'none';
        }
    });

    // Add image preview handler for edit form
    document.getElementById('edit-order-image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('edit-image-preview');
        const previewImg = preview.querySelector('img');
        const label = document.querySelector('#edit-order .custom-file-label');

        if (file) {
            label.textContent = file.name;
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            label.textContent = 'Choose file';
            preview.style.display = 'none';
        }
    });

    // Add this edit button handler
    $(document).on('click', '.editbtn', function() {
        let id = $(this).data('id');
        console.log('Edit button clicked, ID:', id); // Debug log
        
        $.ajax({
            url: `/orders/${id}/edit`,
            type: 'GET',
            success: function(data) {
                console.log('Received data:', data); // Debug log
                
                $('#edit-order').modal('show');
                $('#edit_id').val(data.id);
                
                // Set customer name and ID
                if (data.customer) {
                    $('#edit_customer_name').val(data.customer.fullname);
                    $('#edit_customer').val(data.customer.id);
                }
                
                // Fill in other order details
                $('#edit_description').val(data.description);
                $('#edit_received_date').val(data.received_on);
                $('#edit_amount').val(data.amount_charged);
                
                // Update image preview if exists
                const previewContainer = $('#edit-image-preview');
                const imageLabel = $('#edit-order .custom-file-label');
                
                if (data.image_path) {
                    previewContainer.find('img').attr('src', `/storage/${data.image_path}`);
                    previewContainer.show();
                    imageLabel.text(data.image_path.split('/').pop());
                } else {
                    previewContainer.hide();
                    imageLabel.text('Choose file');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching order:', error);
                toastr.error('Failed to load order details');
            }
        });
    });

    // Initialize DataTable
    var table = $('.table').DataTable({
        dom: 'rt<"bottom"p><"clear">',
        ordering: true,
        searching: true,
        pageLength: 10,
        language: {
            paginate: {
                previous: "&lt;",
                next: "&gt;"
            }
        }
    });

    // Custom search box functionality
    $('#search-input').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>
@endpush

<style>
    /* Card and Header styles */
    .card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #ddd;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .card-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    /* Header elements styles */
    .heading-elements {
        display: flex;
        align-items: center;
    }
    
    .heading-elements .btn-primary {
        margin-right: 15px;
        padding: 0.6rem 1rem;
        font-size: 0.975rem;
    }
    
    .heading-elements .btn-primary i {
        margin-right: 5px;
    }

    /* Search input styles */
    #search-input {
        height: 32px;
        padding: 0.5rem;
        border-radius: 4px;
        border: 1px solid #ddd;
        box-shadow: none;
    }
    
    #search-input:focus {
        border-color: #7367f0;
        box-shadow: none;
    }

    /* Table styles */
    .card-body.card-dashboard {
        padding: 1rem;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table {
        width: 100%;
        margin-bottom: 0;
    }

    .table th, .table td {
        padding: 0.5rem;
        vertical-align: middle;
        white-space: nowrap;
        font-size: 0.9rem;
    }

    /* Rest of your existing styles... */

    /* Add these new styles */
    #orderImageModal .modal-dialog {
        max-width: 500px;
        margin: 1.75rem auto;
    }

    #orderImageModal .modal-body {
        padding: 1rem;
        max-height: calc(100vh - 210px);
        overflow: hidden;
    }

    #orderImageModal .image-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #orderImageModal .modal-image {
        max-width: 100%;
        max-height: calc(100vh - 250px);
        object-fit: contain;
    }

    #orderImageModal .modal-content {
        border-radius: 8px;
    }

    #orderImageModal .modal-header {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #dee2e6;
    }

    #orderImageModal .order-description {
        font-size: 0.9rem;
        color: #666;
    }

    /* Confirmation modal styles */
    .confirmation-message {
        font-size: 1rem;
        color: #333;
        text-align: center;
        margin: 1rem 0;
    }

    #paymentModal .modal-content,
    #collectModal .modal-content {
        border-radius: 8px;
    }

    #paymentModal .btn,
    #collectModal .btn {
        font-weight: 500;
        padding: 8px 20px;
    }

    .btn-warning.text-white {
        color: #ffffff !important;
    }

    .btn-danger.text-white {
        color: #ffffff !important;
    }

    .btn-secondary.text-white {
        color: #ffffff !important;
    }
</style>


