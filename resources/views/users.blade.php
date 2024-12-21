@extends('layouts.app')
@section('title', 'Users')

@push('page-css')

<style>
    .modal {
        padding-left: 260px; /* Half of sidebar width for perfect centering */
    }
    
    .modal-dialog {
        max-width: 500px;
        margin: 1.75rem auto;
    }

    @media (max-width: 768px) {
        .modal {
            padding-left: 0;
        }
    }
</style>

@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3" style="background: white; padding: 15px 25px; margin: -15px -25px 25px;">
    <div class="d-flex align-items-center">
        <h3 class="mb-0 mr-3">Users</h3>
        <div class="breadcrumb mb-0" style="background: none;">
            <a href="{{ route('home') }}" class="breadcrumb-item">Home</a>
            <a href="#" class="breadcrumb-item">Users</a>
            <span class="breadcrumb-item active">All Users</span>
        </div>
    </div>
    <button class="btn btn-primary" data-toggle="modal" data-target="#add-user">
        ADD USER
    </button>
</div>

<!-- HTML5 export buttons table -->
<section id="html5">
	<div class="row">
		<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Users List</h4>
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
				
				<table class="table table-striped table-bordered">
					<thead>
					<tr>
						<th>#</th>
						<th>Username</th>
						<th>FullName</th>
						<th>Email</th>
						<th>Role</th>
						<th>Action</th>
					</tr>
					</thead>
					<tbody>
					@if (!empty($users->count()))
					@foreach ($users as $index => $user)
						<tr>
							<td>{{$index + 1}}</td>
							<td>{{$user->username}}</td>
							<td>{{$user->name}}</td>
							<td>{{$user->email}}</td>
							<td>{{ $user->role }}</td>
							<td>
								<a href="#" class="float-md-right" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
								<div class="dropdown-menu">
									<a href="javascript:void(0)" 
									data-id="{{$user->id}}"
									data-name="{{$user->name}}"
									data-username="{{$user->username}}"
									data-email="{{$user->email}}"
									data-role="{{$user->role}}"
									class="dropdown-item editbtn">
										<i class="la la-edit"></i>Edit
									</a>
									<div class="dropdown-divider"></div>
									<a data-id="{{$user->id}}" href="javascript:void(0)" class="dropdown-item deletebtn">
										<i class="la la-trash"></i>Delete
									</a>
								</div>
							</td>
						</tr>
					@endforeach

						<x-modals.delete :route="'user.destroy'" :title="'User'" />
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

<!-- add user modal starts here -->
<div class="modal wobble text-left" id="add-user" tabindex="-1" role="dialog" aria-labelledby="AddCustomer" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<label class="modal-title text-text-bold-600" id="myModalLabel33">Add User</label>
				<button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" enctype="multipart/form-data" action="{{route('add-user')}}">
				@csrf
				<div class="modal-body">
					<label>FullName: </label>
					<div class="form-group">
						<input type="text" placeholder="Enter firstname" name="name" class="form-control">
					</div>
					
					<label>UserName: </label>
					<div class="form-group">
						<input type="text" placeholder="Enter username" name="username" class="form-control">
					</div>

					<label>Email: </label>
					<div class="form-group">
						<input type="email" required placeholder="Enter email address" name="email" class="form-control">
					</div>

					<label>Password: </label>
					<div class="form-group">
						<input type="password" placeholder="Enter password" name="password" class="form-control">
					</div>

					<label>Confirm Password: </label>
					<div class="form-group">
						<input type="password" placeholder="repeat password" name="password_confirmation" class="form-control">
					</div>

					

				<div class="modal-footer">
					<button type="reset" class="btn btn-outline-secondary btn-lg" data-dismiss="modal">Close</button>						
					<button type="submit" name="add_user" class="btn btn-outline-primary btn-lg">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- add user modal ends here -->
                   
@endsection

<div class="modal wobble text-left" id="credentials-display" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modal-title text-text-bold-600">New User Credentials</label>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
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
                            <button class="btn btn-primary copy-btn" onclick="copyPassword()">
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
<!-- edit user modal starts here -->
<div class="modal wobble text-left" id="edit-user" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modal-title text-text-bold-600">Edit User</label>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{route('users.update')}}">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body">
                    <label>FullName: </label>
                    <div class="form-group">
                        <input type="text" id="edit_name" name="name" class="form-control">
                    </div>
                    
                    <label>UserName: </label>
                    <div class="form-group">
                        <input type="text" id="edit_username" name="username" class="form-control">
                    </div>

                    <label>Email: </label>
                    <div class="form-group">
                        <input type="email" id="edit_email" name="email" class="form-control">
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
<!-- edit user modal ends here -->


@push('page-js')
<script>
$(document).ready(function() {
	$('#add-user form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(response) {
                $('#new-username').text(response.username);
                $('#new-password').text(response.password);
                $('#credentials-display').modal('show');
                $('#add-user').modal('hide');
                $('form')[0].reset();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $(`input[name="${key}"]`).after(`<span class="text-danger">${value[0]}</span>`);
                    });
                }
            }
        });
    });
		
	$('#credentials-display').on('hidden.bs.modal', function () {
		location.reload();
	});

	$('.editbtn').on('click', function() {
        $('#edit-user').modal('show');
        let id = $(this).data('id');
        let name = $(this).data('name');
        let username = $(this).data('username');
        let email = $(this).data('email');
        let role = $(this).data('role');

        $('#edit_id').val(id);
        $('#edit_name').val(name);
        $('#edit_username').val(username);
        $('#edit_email').val(email);
        $('#edit_role').val(role);
    });
	// Clear error messages when modal is closed
    $('#add-user').on('hidden.bs.modal', function() {
        $('.text-danger').remove();
    });

    // Clear previous error messages when typing
    $('input').on('keyup', function() {
        $(this).next('.text-danger').remove();
    });

    // Handle close button click
    $('#credentials-display .close, #credentials-display button[data-dismiss="modal"]').on('click', function() {
        $('#credentials-display').modal('hide');
    });
});

function copyPassword() {
    const password = document.getElementById('new-password').textContent;
    const tempInput = document.createElement('textarea');
    tempInput.value = password;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
    
    $('.copy-btn').text('Copied!');
    setTimeout(() => $('.copy-btn').html('<i class="la la-copy"></i> Copy'), 2000);
}
</script>
@endpush



@push('page-js')

@endpush