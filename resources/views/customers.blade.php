@extends('layouts.app')

@push('page-js')
<script>
$(document).ready(function() {
    // Clear form when modal is closed
    $('#add-customer').on('hidden.bs.modal', function () {
          $(this).find('form')[0].reset();
      });
    // AJAX form submission for adding customer
    $('#add-customer form').on('submit', function(e) {
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

    // Edit button functionality
    $('.editbtn').on('click', function() {
        let id = $(this).data('id');
        
        // Since we don't have a separate edit endpoint like in staff, 
        // we'll use the data attributes directly
        $('#edit-customer').modal('show');
        $('#edit_id').val(id);
        $('#edit_fullname').val($(this).data('fullname'));
        $('#edit_phone').val($(this).data('phone'));
        $('#edit_email').val($(this).data('email'));
        $('#edit_address').val($(this).data('address'));
    });

    // Edit form submission
    $('#edit-customer form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(response) {
                // Reload the page after successful update
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
    // Clear edit form when modal is closed
    $('#edit-customer').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
    });
});
</script>
@endpush

@push('breadcrumb-button')
<x-buttons.primary :text="'Add Customer'" :target="'#add-customer'" />
@endpush

@section('content')
<section id="html5">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Customers List</h4>
          </div>
          <div class="card-content collapse show">
            <div class="card-body card-dashboard">
                <table class="table table-striped table-bordered dataex-html5-export">
                  <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($customers as $customer)
                      <tr>
                        <td>{{$customer->fullname}}</td>
                        <td>{{$customer->phone}}</td>
                        <td>{{$customer->email}}</td>
                        <td>{{$customer->address}}</td>
                        <td>
                          <a href="#" class="float-md-right" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                          </a>
                          <div class="dropdown-menu">
                              <a href="javascript:void(0)" 
                                 data-id="{{$customer->id}}"
                                 data-fullname="{{$customer->fullname}}"
                                 data-phone="{{$customer->phone}}"
                                 data-email="{{$customer->email}}"
                                 data-address="{{$customer->address}}"
                                 class="dropdown-item editbtn">
                                <i class="la la-edit"></i>Edit
                              </a>
                              <div class="dropdown-divider"></div>
                              <a data-id="{{$customer->id}}" href="javascript:void(0)" class="dropdown-item deletebtn">
                                <i class="la la-trash"></i>Delete
                              </a>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                    <x-modals.delete :route="'customer.destroy'" :title="'Customer'" />
                  </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>

<!-- Add Customer Modal -->
<div class="modal wobble text-left" id="add-customer" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-text-bold-600">Add Customer</h3>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{route('customers')}}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Full Name: </label>
                        <div class="position-relative has-icon-left">
                            <input type="text" placeholder="Enter customer full name" name="fullname" class="form-control" required>
                            <div class="form-control-position">
                                <i class="ft-user"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Phone Number: </label>
                        <div class="position-relative has-icon-left">
                            <input type="text" placeholder="Enter customer phone number" name="phone" class="form-control" required>
                            <div class="form-control-position">
                                <i class="ft-phone"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email: </label>
                        <div class="position-relative has-icon-left">
                            <input type="email" placeholder="Enter customer email" name="email" class="form-control" required>
                            <div class="form-control-position">
                                <i class="la la-envelope"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Address: </label>
                        <div class="position-relative has-icon-left">
                            <input type="text" placeholder="Enter customer address" name="address" class="form-control" required>
                            <div class="form-control-position">
                                <i class="la la-map-marker"></i>
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

<!-- Edit Customer Modal -->
<div class="modal wobble text-left" id="edit-customer" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-text-bold-600">Edit Customer</h3>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{route('customers.update')}}">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Full Name: </label>
                        <div class="position-relative has-icon-left">
                            <input type="text" id="edit_fullname" name="fullname" class="form-control" required>
                            <div class="form-control-position">
                                <i class="ft-user"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Phone Number: </label>
                        <div class="position-relative has-icon-left">
                            <input type="text" id="edit_phone" name="phone" class="form-control" required>
                            <div class="form-control-position">
                                <i class="ft-phone"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email: </label>
                        <div class="position-relative has-icon-left">
                            <input type="email" id="edit_email" name="email" class="form-control" required>
                            <div class="form-control-position">
                                <i class="la la-envelope"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Address: </label>
                        <div class="position-relative has-icon-left">
                            <input type="text" id="edit_address" name="address" class="form-control" required>
                            <div class="form-control-position">
                                <i class="la la-map-marker"></i>
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
    // Edit button functionality
    $('.editbtn').on('click', function() {
        $('#edit-customer').modal('show');
        $('#edit_id').val($(this).data('id'));
        $('#edit_fullname').val($(this).data('fullname'));
        $('#edit_phone').val($(this).data('phone'));
        $('#edit_email').val($(this).data('email'));
        $('#edit_address').val($(this).data('address'));
    });
});
</script>
@endpush
