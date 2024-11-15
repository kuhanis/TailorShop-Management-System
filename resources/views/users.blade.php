@extends('layouts.app')

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

@push('breadcrumb')
<h3 class="content-header-title">Users</h3>
<div class="row breadcrumbs-top">
    <div class="breadcrumb-wrapper col-12">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a>
            </li>
            <li class="breadcrumb-item"><a href="#">Users</a>
            </li>
            <li class="breadcrumb-item active">All Users
            </li>
        </ol>
    </div>
</div>
@endpush

@push('breadcrumb-button')
<x-buttons.primary :text="'Add User'" :target="'#add-user'" />
@endpush

@section('content')

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
				
				<table class="table table-striped table-bordered dataex-html5-export">
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
						@foreach ($users as $user)
							<tr>
							<td>{{$user->id}}</td>
							<td>{{$user->username}}</td>
							<td>{{$user->name}}</td>
							<td>{{$user->email}}</td>
							<td>{{ $user->role }}</td>
							<td>
								<a href="#" class="float-md-right" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
								<div class="dropdown-menu">
								  	<a href="javascript:void(0)" data-id="{{$user->id}}" class="dropdown-item">
										<i class="la la-edit"></i>Edit
									</a>
								 	 <div class="dropdown-divider"></div>
								  	<a data-id="{{$user->id}}" href="javascript:void(0)" aria-haspopup="true" data-toggle="modal" aria-expanded="true" class="dropdown-item deletebtn" data-id="{{$user->id}}">
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

					<label>Avatar: </label>
					<div class="form-group">
						<input type="file" name="avatar" class="form-control">
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


@push('page-js')
<script>
$(document).ready(function() {
    $('form').on('submit', function(e) {
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
            }
        });
    });

    // Handle close button click
    $('#credentials-display .close, #credentials-display button[data-dismiss="modal"]').on('click', function() {
        $('#credentials-display').modal('hide');
    });
});

function copyPassword() {
    const password = document.getElementById('new-password').textContent;
    navigator.clipboard.writeText(password);
    $('.copy-btn').text('Copied!');
    setTimeout(() => $('.copy-btn').html('<i class="la la-copy"></i> Copy'), 2000);
}
</script>
@endpush



@push('page-js')

@endpush