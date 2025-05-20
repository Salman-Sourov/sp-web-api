@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

    <div class="page-content">
        <div class="row profile-body">
            <!-- middle wrapper start -->
            <div class="col-md-12 col-xl-12 middle-wrapper">
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">About Section</h6>
                            @php
                                $isEdit = isset($about);
                            @endphp

                            <form id="myForm" method="POST"
                                action="{{ $isEdit ? route('about.update', $about->id) : route('about.update', 0) }}"
                                class="forms-sample" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group mb-3">
                                    <label class="form-label">About Text</label>
                                    <textarea name="about" class="form-control" rows="8">{{ $about->about ?? '' }}</textarea>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-5">
                                        <label class="form-label">Upload Image</label>
                                        <input type="file" name="image" id="logoInput" class="form-control"
                                            accept="image/*">
                                        <br>
                                        <img id="showLogo" class="wd-80"
                                            src="{{ !empty($about->image) ? asset($about->image) : url('upload/no_image.jpg') }}"
                                            alt="logo" width="80">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary me-2">Save Changes</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- middle wrapper end -->
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#logoInput').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showLogo').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files[0]);
            });

            $('#bannerInput').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showBanner').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files[0]);
            });
        });
    </script>
@endsection
