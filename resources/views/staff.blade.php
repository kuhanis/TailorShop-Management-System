@extends('layouts.app')

@push('page-css')
<script>
$(document).ready(function() {
    $('#add-staff form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(response) {
                // Reload the page after successful submission
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

    // Add edit button functionality
    $('.editbtn').on('click', function() {
        let id = $(this).data('id');
        
        // Fetch staff data
        $.get(`/staff/${id}/edit`, function(data) {
            $('#edit-staff').modal('show');
            $('#edit_id').val(id);
            $('#edit_fullname').val(data.fullname);
            $('#edit_address').val(data.address);
            $('#edit_phone').val(data.phone);
            $('#edit_gender').val(data.gender);
            $('#edit_designation').val(data.designation_id);
            $('#edit_salary').val(data.salary);
        });
    });
});
</script>
@endpush

@push('breadcrumb')
<h3 class="content-header-title">Staff</h3>
<div class="row breadcrumbs-top">
	<div class="breadcrumb-wrapper col-12">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a>
			</li>
			<li class="breadcrumb-item"><a href="javascript:void(0)">Staff</a>
			</li>
			<li class="breadcrumb-item active">All Staff
			</li>
		</ol>
	</div>
</div>
@endpush

@push('breadcrumb-button')
<x-buttons.primary :text="'Add Staff'" :target="'#add-staff'" />
@endpush

@section('content')
	
<!-- HTML5 export buttons table -->
<section id="html5">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Designations List</h4>
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
                        <th>Designation</th>
						<th>FullName</th>
						<th>Address</th>
						<th>Phone Number</th>
						<th>Gender</th>
						<th>Salary</th>
						<th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (!empty($staff->count()))
                        @foreach ($staff as $staff)
                          <tr>
							<td>{{$staff->designation->title}}</td>
                            <td>{{$staff->fullname}}</td>
							<td>{{$staff->address}}</td>
							<td>{{$staff->phone}}</td>
							<td>{{$staff->gender}}</td>
							<td>{{$staff->salary}}</td>
                            <td>
                              <a href="#" class="float-md-right" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                              <div class="dropdown-menu">
							  <a href="javascript:void(0)" 
							  <a href="javascript:void(0)" 
								data-id="{{$staff->id}}" 
								data-fullname="{{$staff->fullname}}"
								data-address="{{$staff->address}}"
								data-phone="{{$staff->phone}}"
								data-gender="{{$staff->gender}}"
								data-designation="{{$staff->designation_id}}"
								data-salary="{{$staff->salary}}"
								class="dropdown-item editbtn">
								<i class="la la-edit"></i>Edit
                            </a>
                                  <div class="dropdown-divider"></div>
                                  <a data-id="{{$staff->id}}" href="javascript:void(0)" aria-haspopup="true" data-toggle="modal"  aria-expanded="true" class="dropdown-item deletebtn">
                                  <i class="la la-trash"></i>Delete
                                </a>
                              </div>
                            </td>
                          </tr>
                        @endforeach
                        <x-modals.delete :route="'staff.destroy'" :title="'Staff'" />
                       
                    @endif                    
                  </tbody>
                  
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--/ HTML5 export buttons table -->

  <div class="modal wobble text-left" id="add-staff" tabindex="-1" role="dialog" aria-labelledby="AddCustomer" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modal-title text-text-bold-600" id="myModalLabel33">Add Staff</label>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" action="{{route('staff')}}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Designation</label>
                        <select name="designation" class="select2 form-control" required>
                            @if (!empty($designations->count()))
                                @foreach ($designations as $designation)
                                    <option value="{{$designation->id}}">{{$designation->title}}</option>                     
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Full Name: </label>
                        <div class="position-relative has-icon-left">
                            <input type="text" class="form-control" name="fullname" required>
                            <div class="form-control-position">
                                <i class="ft-user"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <div class="position-relative has-icon-left">
                            <input type="text" class="form-control" name="address" required>
                            <div class="form-control-position">
                                <i class="la la-map-marker"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <select name="gender" class="select2 form-control" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <div class="position-relative has-icon-left">
                            <input type="text" class="form-control" name="phone" required>
                            <div class="form-control-position">
                                <i class="ft-phone"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Salary</label>
                        <div class="input-group mt-0">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="number" class="form-control" name="salary" required>
                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-outline-secondary btn-lg" data-dismiss="modal">Close</button>                     
                    <button type="submit" name="add_staff" class="btn btn-outline-primary btn-lg">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
  <div class="modal wobble text-left" id="edit-staff" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modal-title text-text-bold-600">Edit Staff</label>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" id="edit_staff_form">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Designation</label>
                        <select name="designation" id="edit_designation" class="select2 form-control">
                            @if (!empty($designations->count()))
                                @foreach ($designations as $designation)
                                    <option value="{{$designation->id}}">{{$designation->title}}</option>                     
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Full Name: </label>
                        <div class="position-relative has-icon-left">
                            <input type="text" id="edit_fullname" class="form-control" name="fullname">
                            <div class="form-control-position">
                                <i class="ft-user"></i>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="address">Address</label>
                        <div class="position-relative has-icon-left">
                            <input type="text" id="edit_address" class="form-control" name="address">
                            <div class="form-control-position">
                                <i class="la la-map-marker"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <select name="gender" id="edit_gender" class="select2 form-control">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <div class="position-relative has-icon-left">
                            <input type="text" id="edit_phone" class="form-control" name="phone">
                            <div class="form-control-position">
                                <i class="ft-phone"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
						<label>Salary</label>
						<div class="input-group mt-0">
							<div class="input-group-prepend">
								<span class="input-group-text">RM</span>
							</div>
							<input type="number" class="form-control" name="salary" required>
							<div class="input-group-append">
								<span class="input-group-text">.00</span>
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
<!-- edit staff modal starts here -->
<div class="modal wobble text-left" id="edit-staff" tabindex="-1" role="dialog" aria-labelledby="EditStaff" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modal-title text-text-bold-600">Edit Staff</label>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{route('staff')}}">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Designation</label>
                        <select name="designation" id="edit_designation" class="select2 form-control" required>
                            @if (!empty($designations->count()))
                                @foreach ($designations as $designation)
                                    <option value="{{$designation->id}}">{{$designation->title}}</option>                     
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Full Name: </label>
                        <div class="position-relative has-icon-left">
                            <input type="text" id="edit_fullname" class="form-control" name="fullname" required>
                            <div class="form-control-position">
                                <i class="ft-user"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <div class="position-relative has-icon-left">
                            <input type="text" id="edit_address" class="form-control" name="address" required>
                            <div class="form-control-position">
                                <i class="la la-map-marker"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <select name="gender" id="edit_gender" class="select2 form-control" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <div class="position-relative has-icon-left">
                            <input type="text" id="edit_phone" class="form-control" name="phone" required>
                            <div class="form-control-position">
                                <i class="ft-phone"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Salary</label>
                        <div class="input-group mt-0">
                            <div class="input-group-prepend">
                                <span class="input-group-text">RM</span>
                            </div>
                            <input type="number" id="edit_salary" class="form-control" name="salary" required>
                            <div class="input-group-append">
                                <i class="input-group-text">.00</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-outline-secondary btn-lg" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-primary btn-lg">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- edit staff modal ends here -->
@endsection


@push('page-js')
<script>
$(document).ready(function() {
	$('#add-staff form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(response) {
			$('#add-staff').modal('hide');
			location.reload();
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
    // Edit button click handler
    $('.editbtn').on('click', function() {
        $('#edit-staff').modal('show');
        let id = $(this).data('id');
        let fullname = $(this).data('fullname');
        let address = $(this).data('address');
        let phone = $(this).data('phone');
        let gender = $(this).data('gender');
        let designation = $(this).data('designation');
        let salary = $(this).data('salary');

        $('#edit_id').val(id);
        $('#edit_fullname').val(fullname);
        $('#edit_address').val(address);
        $('#edit_phone').val(phone);
        $('#edit_gender').val(gender);
        $('#edit_designation').val(designation);
        $('#edit_salary').val(salary);
    });
});
</script>
@endpush


