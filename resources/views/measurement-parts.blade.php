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
<x-buttons.primary :text="'Set Measurement Part'" :target="'#add-measurement-part'" />
@endpush

@section('content')
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
						</tr>
					</thead>
					<tbody>
						@foreach($measurements as $customerId => $customerMeasurements)
							<tr>
								<td>{{ $customerMeasurements->first()->customer->fullname }}</td>
								<td>
									<table class="table table-bordered mb-0">
										<thead>
											<tr>
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
											@foreach($customerMeasurements as $measurement)
												<tr>
													<td>{{ $measurement->body_name }}</td>
													<td>{{ $measurement->shoulder }}</td>
													<td>{{ $measurement->chest }}</td>
													<td>{{ $measurement->waist }}</td>
													<td>{{ $measurement->hips }}</td>
													<td>{{ $measurement->dress_length }}</td>
													<td>{{ $measurement->wrist }}</td>
													<td>{{ $measurement->skirt_length }}</td>
													<td>{{ $measurement->armpit }}</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</td>
								<td>
									<a href="#" class="float-md-right" data-toggle="dropdown">
										<i class="material-icons">more_vert</i>
									</a>
									<div class="dropdown-menu">
										<a href="javascript:void(0)" 
										data-customer-id="{{ $customerId }}" 
										class="dropdown-item add-measurement">
											<i class="la la-plus"></i>Add Measurement
										</a>
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
  <!--/ HTML5 export buttons table -->	

<!-- add set measurement modal starts here -->
<div class="modal zoomIn text-left" id="add-measurement-part" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<label class="modal-title text-text-bold-600" id="myModalLabel33">Set Measurement Parts</label>
				<button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" action="{{route('measurement-parts')}}">
				@csrf
				<div class="modal-body">
					<div class="form-body">
						<div class="form-group">
							<label>Measurement Name</label>
							<div class="position-relative">
								<input type="text" class="form-control" placeholder="measurement name" name="name">
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
@endsection


@push('page-js')
<script>
  $(document).ready(function (){
	$('.editbtn').on('click',function (){
		$('#edit-measurement-part').modal('show');
		var id = $(this).data('id');
		var name = $(this).data('name');
		$('#edit_id').val(id);
		$('.edit_name').val(name);
	})
	$('.deletebtn').on('click', function() {
		var id = $(this).data('id');
		// Your delete logic here
	});

  })
</script>
@endpush