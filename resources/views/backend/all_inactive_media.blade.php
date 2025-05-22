@extends('admin.admin_dashboard')
@section('admin')

    <div class="page-content">
        <nav class="page-breadcrumb">
            <ol class="breadcrumb">
            </ol>
        </nav>

        {{-- ALL Inactive Media --}}
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">All Inactive Media ({{ count($inactive_media) }})</h6>

                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Thumbnail</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="mediaTableBody">
                                    @if ($inactive_media && count($inactive_media) > 0)
                                        @foreach ($inactive_media as $key => $item)
                                            <tr data-id="{{ $item->id }}">
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    @php
                                                        function getYoutubeId($url)
                                                        {
                                                            preg_match(
                                                                '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|watch)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/',
                                                                $url,
                                                                $matches,
                                                            );
                                                            return $matches[1] ?? null;
                                                        }

                                                        $videoId = getYoutubeId($item->title);
                                                    @endphp

                                                    @if ($videoId)
                                                        <a href="https://www.youtube.com/watch?v={{ $videoId }}"
                                                            target="_blank">
                                                            <img src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg"
                                                                alt="Thumbnail"
                                                                style="width:100%; max-width:80px; height:50px;">
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Invalid Link</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($videoId)
                                                        <a href="https://www.youtube.com/watch?v={{ $videoId }}"
                                                            target="_blank">
                                                            {{ $item->title ?? 'N/A' }}
                                                        </a>
                                                    @else
                                                        {{ $item->title ?? 'N/A' }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge rounded-pill bg-danger">InActive</span>
                                                </td>
                                                <td>
                                                    <a class="btn toggle-class btn-inverse-danger" title="Status"
                                                        data-id="{{ $item->id }}" data-status="{{ $item->status }}">
                                                        <i data-feather="toggle-right"></i>
                                                    </a>

                                                    <a class="btn btn-inverse-danger delete-btn" title="Delete"
                                                        href="javascript:void(0);" data-id="{{ $item->id }}">
                                                        <i data-feather="trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">No inactive media available</td>
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

    {{-- Change Status --}}
    <script type="text/javascript">
        $(function() {
            // Add CSRF token to all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Toggle media status on button click
            $('.toggle-class').click(function() {
                var $this = $(this);
                var status = $this.attr('data-status');
                var media_id = $this.data('id');

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: '{{ route('media.changeStatus') }}', // Fixed route name
                    data: {
                        'status': status,
                        'media_id': media_id,
                    },
                    success: function(data) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        if (data.success) {
                            Toast.fire({
                                icon: 'success',
                                title: data.message || 'Status Updated Successfully'
                            });

                            // Update the UI
                            var $statusBadge = $this.closest('tr').find('.badge');
                            if (status === 'inactive') {
                                $statusBadge.removeClass('bg-danger').addClass('bg-success')
                                    .text('Active');
                            } else {
                                $statusBadge.removeClass('bg-success').addClass('bg-danger')
                                    .text('Inactive');
                            }

                            $this.attr('data-status', status === 'inactive' ? 'active' :
                                'inactive');
                            feather.replace(); // Re-initialize Feather icons

                            // Reload after 1.5 seconds
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: data.message || 'Error updating status'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while updating status'
                        });
                    }
                });
            });
        });
    </script>

    {{-- Delete Media --}}
    <script type="text/javascript">
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var url = '{{ route('media.delete', ':id') }}';
            url = url.replace(":id", id);

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
                    $.ajax({
                        type: 'DELETE',
                        url: url,
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                );

                                // Remove the deleted row
                                $('#mediaTableBody tr[data-id="' + id + '"]').fadeOut(500,
                                    function() {
                                        $(this).remove();
                                    });

                                toastr.success('Deleted Successfully.');
                            } else {
                                toastr.error(response.message || 'Failed to delete the media.');
                            }
                        },
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON.message ||
                                'An error occurred while deleting the media.');
                            console.log(xhr);
                        }
                    });
                }
            });
        });
    </script>

@endsection
