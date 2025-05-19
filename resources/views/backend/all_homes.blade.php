@extends('admin.admin_dashboard')
@section('admin')
    <div class="page-content">
        @if ($allHomes->isEmpty())
            <nav class="page-breadcrumb">
                <ol class="breadcrumb">
                    <button type="button" class="btn btn-inverse-info" data-bs-toggle="modal" data-bs-target="#addModal">
                        Add Home Content
                    </button>
                </ol>
            </nav>

            <!-- Add Home Modal -->
            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Add Home</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="btn-close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addBrandForm" method="POST" action="" class="forms-sample"
                                onsubmit="event.preventDefault(); StoreHome();">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" id="name">
                                    <span id="name_error" class="text-danger"></span> <!-- Error message placeholder -->
                                </div>
                                <div class="form-group mb-3">
                                    <label for="website" class="form-label">Email</label>
                                    <input type="text" name="email" class="form-control" id="email">
                                    <span id="email_error" class="text-danger"></span> <!-- Error message placeholder -->
                                </div>
                                <div class="form-group mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input class="form-control" name="image" type="file" id="image">
                                    <span id="image_error" class="text-danger"></span>
                                </div>
                                <!-- Image preview -->
                                <div class="form-group">
                                    <img id="showImage" src="#" alt="Image Preview"
                                        style="max-width: 200px; display: none;">
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        {{-- ALL Home --}}
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Home Contents</h6>

                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="brandTableBody">
                                    @if ($allHomes && count($allHomes) > 0)
                                        @foreach ($allHomes as $key => $item)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td><img src="{{ !empty($item->image) ? url($item->image) : url('upload/no_image.jpg') }}"
                                                        style="width:60px; height:40px;"> </td>
                                                <td>
                                                    <button type="button" class="btn btn-inverse-warning"
                                                        data-bs-toggle="modal" data-bs-target="#editModal"
                                                        id="{{ $item->id }}" onclick="brandEdit(this.id)">
                                                        Edit
                                                    </button>

                                                    <a href="javascript:void(0);" class="btn btn-inverse-danger delete-btn"
                                                        data-id="{{ $item->id }}" title="Delete">Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">No data available</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Store Home --}}
    <script type="text/javascript">
        function StoreHome() {
            var formData = new FormData(document.getElementById('addBrandForm'));
            $.ajax({
                type: 'POST',
                url: '{{ route('home.store') }}',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    //console.log(data); // Check the success response in the console

                    if (data.success) {
                        $('#addModal').modal('hide'); // Close modal
                        toastr.success(data.message);
                        setTimeout(function() {
                            window.location.reload(); // Reload the page to see the new brand
                        }, 1500);

                        $('#addBrandForm')[0].reset(); // Reset the correct form
                    } else {
                        for (let field in data.errors) {
                            $('#' + field + '_error').text(data.errors[field][
                                0
                            ]); // Show validation error messages
                        }
                    }
                },
                error: function(xhr) {
                    console.log(xhr); // Log the error for debugging
                    const errors = xhr.responseJSON.errors;
                    for (let field in errors) {
                        $('#' + field + '_error').text(errors[field][0]); // Show validation errors
                    }
                }
            });
        }
    </script>

    <!-- Edit Home Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Update Home</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editHomeForm" method="POST" enctype="multipart/form-data" class="forms-sample"
                        onsubmit="event.preventDefault(); UpdateHome();">
                        @csrf
                        <!-- Simulate PATCH method -->
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="home_id" id= "home_id">

                        <div class="form-group mb-3">
                            <label for="edit_name" class="form-label">Name</label>
                            <input type="text" name="edit_name" class="form-control" id="edit_name">
                            <span id="edit_name_error" class="text-danger"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="text" name="edit_email" class="form-control" id="edit_email">
                            <span id="edit_email_error" class="text-danger"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="edit_image" class="form-label">Image</label>
                            <input class="form-control" name="edit_image" type="file" id="edit_image">
                        </div>

                        <!-- Image preview -->
                        <div class="form-group mb-3">
                            <img id="edit_showImage" class="wd-100 rounded-circle"
                                src="{{ !empty($allHomes->image) ? url('upload/brand/' . $allHomes->image) : url('upload/no_image.jpg') }}"
                                alt="profile" style="width: 90px; height: 60px;">
                        </div>

                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Preview Image --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#edit_image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#edit_showImage').attr('src', e.target.result).css('display', 'block');
                }
                reader.readAsDataURL(e.target.files[0]);
            });
        });
    </script>

    {{-- Edit Home --}}
    <script type="text/javascript">
        function brandEdit(home_id) {
            $.ajax({
                type: 'GET',
                url: '/home/' + home_id + '/edit', // Ensure this is the correct route
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        console.log(data.error);
                    } else {
                        $('#home_id').val(data.id);
                        $('#edit_name').val(data.name);
                        $('#edit_email').val(data.email);
                        var imgSrc = data.image ? data.image : '/upload/no_image.jpg';
                        $('#edit_showImage').attr('src', imgSrc);
                        $('#editModal').modal('show'); // Open modal with data loaded

                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }
    </script>

    {{-- Update Home --}}
    <script type="text/javascript">
        function UpdateHome() {
            var formData = new FormData(document.getElementById('editHomeForm'));
            var homeId = $('#home_id').val(); // Get the brand ID

            $.ajax({
                type: 'POST', // POST method to support _method PATCH
                url: '/home/' + homeId,
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Add CSRF token header
                },
                success: function(data) {
                    if (data.success) {
                        $('#editModal').modal('hide'); // Close the modal
                        toastr.success(data.message); // Show success notification
                        // Optionally refresh the brand list or table here
                        setTimeout(function() {
                            window.location.reload(); // Reload the page to see the new brand
                        }, 1500);
                    } else {
                        for (let field in data.errors) {
                            $('#' + field + '_error').text(data.errors[field][0]); // Show error
                        }
                    }
                },

                error: function(xhr) {
                    console.log(xhr); // Log the error for debugging
                    const errors = xhr.responseJSON.errors;
                    for (let field in errors) {
                        $('#' + field + '_error').text(errors[field][0]); // Show error
                    }
                }


            });
        }
    </script>

    {{-- Delete Home --}}
    <script type="text/javascript">
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            var id = $(this).data('id'); // Get the data-id from the button
            var url = '{{ route('home.destroy', ':id') }}';
            url = url.replace(":id", id); // Replace placeholder with actual ID

            // SweetAlert confirmation popup
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with AJAX request if the user confirms
                    $.ajax({
                        type: 'DELETE',
                        url: url,
                        data: {
                            "_token": "{{ csrf_token() }}" // Include CSRF token for security
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                );

                                toastr.success('Deleted Successfully.');
                                setTimeout(function() {
                                    window.location
                                        .reload(); // Reload the page to see the new brand
                                }, 1500); // Reload the page after successful deletion

                                // Delay the reload to show the message
                            } else {
                                toastr.error('Failed to delete the brand.');
                            }
                        },
                        error: function(xhr) {
                            toastr.error('An error occurred while deleting the item.');
                            console.log(xhr); // Log the error for debugging
                        }
                    });
                }
            });
        });
    </script>
@endsection
