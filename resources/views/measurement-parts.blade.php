@extends('layouts.app')
@section('title', 'Body Measurements')

@push('page-css')
<style>
    .card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #ddd;
    }
    
    .card-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
    }
    
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
    
    .dataTables_filter {
        display: none;
    }
    
    .dataTables_length {
        display: none;
    }

    /* Add these new styles for the table */
    .card-body.card-dashboard {
        padding: 1rem;
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

    /* Make the table scrollable horizontally if needed */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Wrap the table in a responsive container */
    .card-body.card-dashboard .table-responsive {
        margin: 0 -1rem;  /* Compensate for card padding */
        padding: 0 1rem;  /* Add back padding for spacing */
    }
</style>
@endpush

@push('breadcrumb')
<h3 class="content-header-title">Measurement Parts</h3>
<div class="row breadcrumbs-top">
	<div class="breadcrumb-wrapper col-12">
		<ol class="breadcrumb">
			<li class="breadcrumb-item active">All Measurement Parts
			</li>
		</ol>
	</div>
</div>
@endpush

@push('breadcrumb-button')
<x-buttons.primary :text="'Add Body'" :target="'#add-measurement-part'" />
@endpush

@section('content')
<div class="content-wrapper" style="margin: 0 -1rem;">
  <div class="content-body">
    <!-- HTML5 export buttons table -->
    <section id="html5">
        <div class="row">
          <div class="col-12">
            <div class="card" style="margin: 0 1rem;">
              <div class="card-header">
                <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                    <h4 class="card-title mb-0">Measurement Parts List</h4>
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
                  
                  <!-- Add this right above the table -->
                  <div class="alert alert-info" style="margin-bottom: 1rem;">
                      <i class="la la-info-circle"></i> All measurements are in inches
                  </div>
                  
                  <!-- Wrap table in responsive container -->
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 40px;">#</th>
                          <th style="width: 80px;">Customer</th>
                          <th style="width: 80px;">Body</th>
                          <th style="width: 65px;">Shoulder</th>
                          <th style="width: 65px;">Chest</th>
                          <th style="width: 65px;">Waist</th>
                          <th style="width: 65px;">Hips</th>
                          <th style="width: 65px;">Dress L.</th>
                          <th style="width: 65px;">Wrist</th>
                          <th style="width: 65px;">Skirt L.</th>
                          <th style="width: 65px;">Armpit</th>
                          <th style="width: 60px;">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php $counter = 1; @endphp
                        @foreach($measurements as $customerId => $customerMeasurements)
                          @php
                            $rowCount = count($customerMeasurements);
                          @endphp
                          @php $firstRow = true; @endphp
                          @foreach($customerMeasurements as $index => $measurement)
                            <tr>
                              @if($index === 0)
                                <td rowspan="{{ $rowCount }}" class="text-center">{{$loop->parent->iteration}}</td>
                                <td rowspan="{{ $rowCount }}" class="align-middle" style="font-size: 13px;">
                                  {{ $measurement->customer->fullname }}
                                </td>
                              @endif
                              <td style="font-size: 13px;">
                                {{ $measurement->body_name }}
                              </td>
                              <td class="text-center p-1">{{ $measurement->shoulder }}</td>
                              <td class="text-center p-1">{{ $measurement->chest }}</td>
                              <td class="text-center p-1">{{ $measurement->waist }}</td>
                              <td class="text-center p-1">{{ $measurement->hips }}</td>
                              <td class="text-center p-1">{{ $measurement->dress_length }}</td>
                              <td class="text-center p-1">{{ $measurement->wrist }}</td>
                              <td class="text-center p-1">{{ $measurement->skirt_length }}</td>
                              <td class="text-center p-1">{{ $measurement->armpit }}</td>
                              <td class="text-center">
                                <a href="#" class="float-md-right" data-toggle="dropdown">
                                  <i class="material-icons">more_vert</i>
                                </a>
                                <div class="dropdown-menu">
                                  <a href="javascript:void(0)" 
                                     data-id="{{ $measurement->id }}"
                                     data-body-name="{{ $measurement->body_name }}"
                                     data-shoulder="{{ $measurement->shoulder }}"
                                     data-chest="{{ $measurement->chest }}"
                                     data-waist="{{ $measurement->waist }}"
                                     data-hips="{{ $measurement->hips }}"
                                     data-dress-length="{{ $measurement->dress_length }}"
                                     data-wrist="{{ $measurement->wrist }}"
                                     data-skirt-length="{{ $measurement->skirt_length }}"
                                     data-armpit="{{ $measurement->armpit }}"
                                     class="dropdown-item editbtn">
                                    <i class="la la-edit"></i>Edit
                                  </a>
                                  <div class="dropdown-divider"></div>
                                  <a href="javascript:void(0)"
                                     data-id="{{ $measurement->id }}"
                                     class="dropdown-item deletebtn text-danger">
                                    <i class="la la-trash"></i>Delete
                                  </a>
                                </div>
                              </td>
                            </tr>
                          @endforeach
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
    <!--/ HTML5 export buttons table -->
  </div>
</div>
@endsection

<!-- add set measurement modal starts here -->
<div class="modal zoomIn text-left" id="add-measurement-part" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<label class="modal-title text-text-bold-600" id="myModalLabel33">Add Body Measurement</label>
				<button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" action="{{route('body.measurements.store')}}">
				@csrf
				<div class="modal-body">
					<div class="form-body">
						<div class="form-group">
							<label>Customer Name</label>
							<select class="form-control" name="customer_id" required>
								<option value="">Select Customer</option>
								@foreach($measurements->keys() as $customerId)
									@php
										$customerName = $measurements[$customerId]->first()->customer->fullname;
									@endphp
									<option value="{{ $customerId }}">{{ $customerName }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label>Body Name</label>
							<input type="text" class="form-control" placeholder="Enter body name" name="body_name" required>
						</div>
						<div class="form-group">
							<label>Shoulder</label>
							<div class="input-group">
								<input type="number" step="0.01" class="form-control" placeholder="Enter shoulder measurement" name="shoulder" required>
								<div class="input-group-append">
									<span class="input-group-text">inch</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Chest</label>
							<div class="input-group">
								<input type="number" step="0.01" class="form-control" placeholder="Enter chest measurement" name="chest" required>
								<div class="input-group-append">
									<span class="input-group-text">inch</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Waist</label>
							<div class="input-group">
								<input type="number" step="0.01" class="form-control" placeholder="Enter waist measurement" name="waist" required>
								<div class="input-group-append">
									<span class="input-group-text">inch</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Hip</label>
							<div class="input-group">
								<input type="number" step="0.01" class="form-control" placeholder="Enter hip measurement" name="hips" required>
								<div class="input-group-append">
									<span class="input-group-text">inch</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Dress Length</label>
							<div class="input-group">
								<input type="number" step="0.01" class="form-control" placeholder="Enter dress length" name="dress_length" required>
								<div class="input-group-append">
									<span class="input-group-text">inch</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Wrist</label>
							<div class="input-group">
								<input type="number" step="0.01" class="form-control" placeholder="Enter wrist measurement" name="wrist" required>
								<div class="input-group-append">
									<span class="input-group-text">inch</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Skirt Length</label>
							<div class="input-group">
								<input type="number" step="0.01" class="form-control" placeholder="Enter skirt length" name="skirt_length" required>
								<div class="input-group-append">
									<span class="input-group-text">inch</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Armpit</label>
							<div class="input-group">
								<input type="number" step="0.01" class="form-control" placeholder="Enter armpit measurement" name="armpit" required>
								<div class="input-group-append">
									<span class="input-group-text">inch</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="reset" class="btn btn-outline-secondary btn-lg" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-outline-primary btn-lg">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- add cloth types modal ends here -->

<!-- Add Edit Measurement Modal -->
<div class="modal wobble text-left" id="edit-measurement" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title">Edit Body Measurements</h3>
				<button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="edit-measurement-form" method="post" action="{{ route('measurements.update') }}">
				@csrf
				@method('PUT')
				<input type="hidden" name="id" id="edit_measurement_id">
				<div class="modal-body">
					<div class="form-group">
						<label>Body Name:</label>
						<input type="text" name="body_name" id="edit_body_name" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Shoulder:</label>
						<div class="input-group">
							<input type="number" step="0.01" name="shoulder" id="edit_shoulder" class="form-control" required>
							<div class="input-group-append">
								<span class="input-group-text">inch</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Chest:</label>
						<div class="input-group">
							<input type="number" step="0.01" name="chest" id="edit_chest" class="form-control" required>
							<div class="input-group-append">
								<span class="input-group-text">inch</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Waist:</label>
						<div class="input-group">
							<input type="number" step="0.01" name="waist" id="edit_waist" class="form-control" required>
							<div class="input-group-append">
								<span class="input-group-text">inch</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Hips:</label>
						<div class="input-group">
							<input type="number" step="0.01" name="hips" id="edit_hips" class="form-control" required>
							<div class="input-group-append">
								<span class="input-group-text">inch</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Dress Length:</label>
						<div class="input-group">
							<input type="number" step="0.01" name="dress_length" id="edit_dress_length" class="form-control" required>
							<div class="input-group-append">
								<span class="input-group-text">inch</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Wrist:</label>
						<div class="input-group">
							<input type="number" step="0.01" name="wrist" id="edit_wrist" class="form-control" required>
							<div class="input-group-append">
								<span class="input-group-text">inch</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Skirt Length:</label>
						<div class="input-group">
							<input type="number" step="0.01" name="skirt_length" id="edit_skirt_length" class="form-control" required>
							<div class="input-group-append">
								<span class="input-group-text">inch</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Armpit:</label>
						<div class="input-group">
							<input type="number" step="0.01" name="armpit" id="edit_armpit" class="form-control" required>
							<div class="input-group-append">
								<span class="input-group-text">inch</span>
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

@push('page-js')
<script>
$(document).ready(function() {
	// Simple search functionality
	$('#search-input').on('keyup', function() {
		var searchText = $(this).val().toLowerCase();
		
		// First hide all rows
		var allRows = $('.table tbody tr');
		allRows.hide();
		
		// Show rows that match the search
		allRows.each(function() {
			var row = $(this);
			var customerCell = row.find('td:eq(1)');
			
			// If this row has a customer cell (rowspan), get all related rows
			if (customerCell.attr('rowspan')) {
				var customerName = customerCell.text().toLowerCase();
				var nextRows = row.nextAll().slice(0, parseInt(customerCell.attr('rowspan')) - 1);
				var allGroupRows = row.add(nextRows);
				
				// Check all cells in this group for matches
				var groupText = allGroupRows.text().toLowerCase();
				if (groupText.indexOf(searchText) > -1) {
					allGroupRows.show();
				}
			}
		});
		
		// If search is empty, show all rows
		if (searchText === '') {
			allRows.show();
		}
	});

	// Edit button click handler
	$('.editbtn').on('click', function() {
		$('#edit-measurement').modal('show');
		
		// Set form values
		$('#edit_measurement_id').val($(this).data('id'));
		$('#edit_body_name').val($(this).data('body-name'));
		$('#edit_shoulder').val($(this).data('shoulder'));
		$('#edit_chest').val($(this).data('chest'));
		$('#edit_waist').val($(this).data('waist'));
		$('#edit_hips').val($(this).data('hips'));
		$('#edit_dress_length').val($(this).data('dress-length'));
		$('#edit_wrist').val($(this).data('wrist'));
		$('#edit_skirt_length').val($(this).data('skirt-length'));
		$('#edit_armpit').val($(this).data('armpit'));
	});

	// Edit form submission
	$('#edit-measurement-form').on('submit', function(e) {
		e.preventDefault();
		
		$.ajax({
			url: $(this).attr('action'),
			method: 'POST',
			data: new FormData(this),
			processData: false,
			contentType: false,
			success: function(response) {
				toastr.success('Measurement updated successfully!');
				$('#edit-measurement').modal('hide');
				window.location.reload();
			},
			error: function(xhr) {
				if (xhr.status === 422) {
					let errors = xhr.responseJSON.errors;
					Object.keys(errors).forEach(function(key) {
						toastr.error(errors[key][0]);
					});
				}
			}
		});
	});

	// Add measurement form submission
	$('#add-measurement-part form').on('submit', function(e) {
		e.preventDefault();
		
		$.ajax({
			url: $(this).attr('action'),
			method: 'POST',
			data: new FormData(this),
			processData: false,
			contentType: false,
			success: function(response) {
				toastr.success('Body measurement added successfully!');
				$('#add-measurement-part').modal('hide');
				window.location.reload();
			},
			error: function(xhr) {
				if (xhr.status === 422) {
					let errors = xhr.responseJSON.errors;
					Object.keys(errors).forEach(function(key) {
						toastr.error(errors[key][0]);
					});
				}
			}
		});
	});

	// Delete button click handler
	$('.deletebtn').on('click', function() {
		let id = $(this).data('id');
		
		if(confirm('Are you sure you want to delete this measurement?')) {
			$.ajax({
				url: "{{ route('measurements.destroy') }}",
				method: 'DELETE',
				data: {
					_token: '{{ csrf_token() }}',
					id: id
				},
				success: function(response) {
					toastr.success('Measurement deleted successfully!');
					window.location.reload();
				},
				error: function(xhr) {
					toastr.error('An error occurred while deleting the measurement.');
				}
			});
		}
	});
});
</script>
@endpush