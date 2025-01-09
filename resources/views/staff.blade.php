@extends('layouts.app')
@section('title', 'Staff')

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
            //$('#edit_gender').val(data.gender);
          //  $('#edit_designation').val(data.designation_id);
            $('#edit_salary').val(data.salary);
        });
    });
});
</script>
@endpush

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
    
    .btn-primary {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .dataTables_filter {
        display: none;
    }
    
    .dataTables_length {
        display: none;
    }
</style>
@endpush

@section('content')
	
<!-- HTML5 export buttons table -->
<section id="html5">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                <h4 class="card-title mb-0">Staff List</h4>
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-staff">
                    <i class="ft-plus"></i> Add Staff
                </button>
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
                
                <!-- Table headers -->
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            
                         
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Salary</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($staff as $index => $staffMember)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $staffMember->user->username }}</td>
                            <td>{{ $staffMember->user->name }}</td>
                            <td>{{ $staffMember->user->email }}</td>
                            
                            
                            <td>{{ $staffMember->phone }}</td>
                            <td>{{ $staffMember->address }}</td>
                            <td>{{ $staffMember->salary }}</td>
                            <td>
                                <a href="#" class="float-md-right" data-toggle="dropdown" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="javascript:void(0)" 
                                    data-id="{{$staffMember->id}}"
                                    data-name="{{$staffMember->user->name}}"
                                    data-username="{{$staffMember->user->username}}"
                                    data-email="{{$staffMember->user->email}}"
                                    data-address="{{$staffMember->address}}"
                                    data-phone="{{$staffMember->phone}}"
                                    data-salary="{{$staffMember->salary}}"
                                    class="dropdown-item editbtn">
                                        <i class="la la-edit"></i>Edit
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a data-id="{{$staffMember->id}}" href="javascript:void(0)" class="dropdown-item deletebtn">
                                        <i class="la la-trash"></i>Delete
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

<!-- First Step Modal -->
<div class="modal wobble text-left" id="add-staff" tabindex="-1" role="dialog" aria-labelledby="AddCustomer" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modal-title text-text-bold-600">Step 1: Create Staff Account</label>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="user-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label>FullName: </label>
                        <div class="position-relative has-icon-left">
                            <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
                            <div class="form-control-position">
                                <i class="ft-user"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>UserName: </label>
                        <div class="position-relative has-icon-left">
                            <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                            <div class="form-control-position">
                                <i class="la la-user"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email: </label>
                        <div class="position-relative has-icon-left">
                            <input type="email" name="email" class="form-control" placeholder="Enter email address" required>
                            <div class="form-control-position">
                                <i class="la la-envelope"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Password: </label>
                        <div class="position-relative has-icon-left">
                            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                            <div class="form-control-position">
                                <i class="la la-lock"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Confirm Password: </label>
                        <div class="position-relative has-icon-left">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                            <div class="form-control-position">
                                <i class="la la-lock"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-lg" data-dismiss="modal">Close</button>
                    <button type="button" id="next-step" class="btn btn-outline-primary btn-lg">Next</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Second Step Modal -->
<div class="modal wobble text-left" id="add-staff-details" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modal-title text-text-bold-600" id="myModalLabel33">Step 2: Additional Information</label>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('staff') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="selected_user_id">
                    
                    <div class="form-group">
                        <label>Address: </label>
                        <div class="position-relative has-icon-left">
                            <input type="text" class="form-control" name="address" placeholder="Enter full address" required>
                            <div class="form-control-position">
                                <i class="la la-map-marker"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Phone Number: </label>
                        <div class="position-relative has-icon-left">
                            <input type="text" class="form-control" name="phone" placeholder="Enter phone number" required>
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
                            <input type="number" class="form-control" name="salary" placeholder="Enter salary amount" required>
                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-lg" onclick="$('#add-staff-details').modal('hide'); $('#add-staff').modal('show');">Back</button>
                    <button type="submit" class="btn btn-outline-primary btn-lg">Submit</button>
                </div>

            </form>
        </div>
    </div>
</div>
<div class="modal wobble text-left" id="credentials-display" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                    <label>Username: </label>
                    <div class="form-control" id="new-username"></div>
                </div>
                <div class="form-group">
                    <label>Password: </label>
                    <div class="input-group">
                        <div class="form-control" id="new-password"></div>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary copy-btn">
                                <i class="la la-copy"></i> Copy
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-lg" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- edit staff modal starts here -->
<div class="modal wobble text-left" id="edit-staff" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <label>Full Name: </label>
                        <div class="position-relative has-icon-left">
                            <input type="text" id="edit_fullname" class="form-control" name="name" required>
                            <div class="form-control-position">
                                <i class="ft-user"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Username: </label>
                        <div class="position-relative has-icon-left">
                            <input type="text" id="edit_username" class="form-control" name="username" required>
                            <div class="form-control-position">
                                <i class="la la-user"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email: </label>
                        <div class="position-relative has-icon-left">
                            <input type="email" id="edit_email" class="form-control" name="email" required>
                            <div class="form-control-position">
                                <i class="la la-envelope"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Address: </label>
                        <div class="position-relative has-icon-left">
                            <input type="text" id="edit_address" class="form-control" name="address" required>
                            <div class="form-control-position">
                                <i class="la la-map-marker"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Phone Number: </label>
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

<!-- edit staff modal ends here -->
@endsection


@push('page-js')
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Clear form when modal is closed
    $('#add-staff').on('hidden.bs.modal', function () {
        $('#user-form')[0].reset();
    });

    // Handle next button click
    $('#next-step').click(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{route('add-user')}}",
            method: 'POST',
            data: $('#user-form').serialize(),
            success: function(response) {
                console.log('Success:', response);
                $('#add-staff').modal('hide');
                $('#add-staff-details').modal('show');
                
                // Store user_id for the next step
                $('#selected_user_id').val(response.user_id);
                
                // Store credentials for later use
                window.tempCredentials = {
                    username: response.username,
                    password: response.password
                };
            },
            error: function(xhr) {
                console.log('Error:', xhr);
            
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    console.log('Validation Errors:', errors);
                    
                    if (errors.email) {
                        console.log('Email Error:', errors.email[0]);
                        toastr.warning(errors.email[0], 'Email Error');
                    } 
                    
                    if (errors.username) {
                        console.log('Username Error:', errors.username[0]);
                        toastr.warning(errors.username[0], 'Username Error');
                    }

                    // Add password validation error handling
                    if (errors.password) {
                        let passwordError = errors.password[0];
                        if (passwordError.includes('confirmed')) {
                            toastr.warning('Password and Confirm Password must match', 'Password Error');
                        } else if (passwordError.includes('min')) {
                            toastr.warning('Password must be at least 8 characters', 'Password Error');
                        } else if (passwordError.includes('format') || passwordError.includes('invalid') || passwordError.includes('The password format is invalid')) {
                            toastr.warning('Password must contain at least one uppercase letter, one lowercase letter, one number and one special character', 'Password Error');
                        } else {
                            toastr.warning(passwordError, 'Password Error');
                        }
                    }
                }
            }
        });
    });


    // Clear second form when closed
    $('#add-staff-details').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
    });



    // Add copy password function
    $('.copy-btn').on('click', function() {
        const password = document.getElementById('new-password').textContent;
        const tempInput = document.createElement('input');
        tempInput.value = password;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        
        toastr.success('Password copied to clipboard!');
        $('.copy-btn').text('Copied!');
        setTimeout(() => $('.copy-btn').html('<i class="la la-copy"></i> Copy'), 2000);
    });

    // Handle staff form submission
    $('#add-staff-details form').submit(function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        $.ajax({
            url: "{{ route('staff') }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#add-staff-details').modal('hide');
                $('#new-username').text(window.tempCredentials.username);
                $('#new-password').text(window.tempCredentials.password);
                $('#credentials-display').modal('show');
                toastr.success('Staff added successfully');
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

    $('#credentials-display').on('hidden.bs.modal', function () {
        location.reload();
    });

    // Edit button functionality
    $('.editbtn').on('click', function() {
        $('#edit-staff').modal('show');
        $('#edit_id').val($(this).data('id'));
        $('#edit_fullname').val($(this).data('name'));
        $('#edit_username').val($(this).data('username'));
        $('#edit_email').val($(this).data('email'));
        $('#edit_address').val($(this).data('address'));
        $('#edit_phone').val($(this).data('phone'));
        $('#edit_salary').val($(this).data('salary'));
    });

    // Initialize DataTable
    var table = $('.table').DataTable({
        dom: 'rt<"bottom"p><"clear">',  // Only show table and pagination
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
        table.search($(this).val()).draw();
    });

    // Replace the delete button handler
    $(document).on('click', '.deletebtn', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the staff member! This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('staff.destroy') }}",
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'Something went wrong while deleting the staff member.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>


@endpush



