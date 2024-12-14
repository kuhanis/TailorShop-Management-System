@extends('layouts.app')

@push('page-css')

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
<div class="content-wrapper">
  <div class="content-body">
    <!-- HTML5 export buttons table -->
    <section id="html5">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Measurement Parts List</h4>
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
                        <th>Customer Name</th>
                        <th>Body Name</th>
                        <th>Shoulder</th>
                        <th>Chest</th>
                        <th>Waist</th>
                        <th>Hip</th>
                        <th>Dress length</th>
                        <th>Wrist</th>
                        <th>Skirt length</th>
                        <th>Armpit</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($measurements as $customerId => $customerMeasurements)
                        @php
                          $rowCount = count($customerMeasurements);
                        @endphp
                        @foreach($customerMeasurements as $index => $measurement)
                          <tr>
                            @if($index === 0)
                              <td rowspan="{{ $rowCount }}">{{ $measurement->customer->fullname }}</td>
                            @endif
                            <td>{{ $measurement->body_name }}</td>
                            <td>{{ $measurement->shoulder }}</td>
                            <td>{{ $measurement->chest }}</td>
                            <td>{{ $measurement->waist }}</td>
                            <td>{{ $measurement->hips }}</td>
                            <td>{{ $measurement->dress_length }}</td>
                            <td>{{ $measurement->wrist }}</td>
                            <td>{{ $measurement->skirt_length }}</td>
                            <td>{{ $measurement->armpit }}</td>
                            <td>
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
                                <a href="javascript:void(0)" 
                                   data-customer-id="{{ $customerId }}" 
                                   class="dropdown-item add-measurement">
                                  <i class="la la-plus"></i>Add Measurement
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
							<input type="number" step="0.01" class="form-control" placeholder="Enter shoulder measurement" name="shoulder" required>
						</div>
						<div class="form-group">
							<label>Chest</label>
							<input type="number" step="0.01" class="form-control" placeholder="Enter chest measurement" name="chest" required>
						</div>
						<div class="form-group">
							<label>Waist</label>
							<input type="number" step="0.01" class="form-control" placeholder="Enter waist measurement" name="waist" required>
						</div>
						<div class="form-group">
							<label>Hip</label>
							<input type="number" step="0.01" class="form-control" placeholder="Enter hip measurement" name="hips" required>
						</div>
						<div class="form-group">
							<label>Dress Length</label>
							<input type="number" step="0.01" class="form-control" placeholder="Enter dress length" name="dress_length" required>
						</div>
						<div class="form-group">
							<label>Wrist</label>
							<input type="number" step="0.01" class="form-control" placeholder="Enter wrist measurement" name="wrist" required>
						</div>
						<div class="form-group">
							<label>Skirt Length</label>
							<input type="number" step="0.01" class="form-control" placeholder="Enter skirt length" name="skirt_length" required>
						</div>
						<div class="form-group">
							<label>Armpit</label>
							<input type="number" step="0.01" class="form-control" placeholder="Enter armpit measurement" name="armpit" required>
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
						<input type="number" step="0.01" name="shoulder" id="edit_shoulder" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Chest:</label>
						<input type="number" step="0.01" name="chest" id="edit_chest" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Waist:</label>
						<input type="number" step="0.01" name="waist" id="edit_waist" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Hips:</label>
						<input type="number" step="0.01" name="hips" id="edit_hips" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Dress Length:</label>
						<input type="number" step="0.01" name="dress_length" id="edit_dress_length" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Wrist:</label>
						<input type="number" step="0.01" name="wrist" id="edit_wrist" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Skirt Length:</label>
						<input type="number" step="0.01" name="skirt_length" id="edit_skirt_length" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Armpit:</label>
						<input type="number" step="0.01" name="armpit" id="edit_armpit" class="form-control" required>
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
});
</script>
@endpush