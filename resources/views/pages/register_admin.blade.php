@extends('layouts.app')

@section('content')
<body class="hold-transition register-page">
<div class="d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="register-box">
            <div class="card">
                <div class="card-body register-card-body">
                    <p class="login-box-msg">New Admin Registration</p>

                    <form method="post" action="{{ route('create_admin') }}" id="form_register">
                        @csrf

                        <div class="input-group mb-3">
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                placeholder="Full name">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-user"></span></div>
                            </div>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                            </div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-lock"></span></div>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Retype password">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-lock"></span></div>
                            </div>
                        </div>

                        <div class="row">
                         
                            <!-- /.col -->
                            <div class="col-12">
                                <button type="button" class="btn btn-primary btn-block btn_submit_registration">Create Admin</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                </div>
                <!-- /.form-box -->
            </div><!-- /.card -->

            <!-- /.form-box -->
        </div>
        </div>
        <!-- /.register-box -->
    </body>
    <script>
        $(document).ready(function(){
            var csrf= $('#logout-form').find('input[name="_token"]').val();
            $(this).on('click','.btn_submit_registration', function(){
                var formData =new FormData($('#form_register')[0]);
                if (!validateForm()) {
                return false;
            }
                Swal.fire({
                        title: "Are you sure you want to create this as admin?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Confirm"
                    }).then((result) => {
                        if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('create_admin') }}",
                            type: 'post',
                            headers: {
                                'X-CSRF-TOKEN': csrf
                                },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: "saved",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                setTimeout(function () {
                                    location.reload();
                                }, 2000);
                            },
                            error: function(error) {
                                // Handle the error response
                                console.error('Error uploading file:', error);
                            }
                            });
                        
                        }
                    });
            });
            function validateForm() {
            var name = $('input[name="name"]').val();
            var email = $('input[name="email"]').val();
            var password = $('input[name="password"]').val();
            var confirmPassword = $('input[name="password_confirmation"]').val();
            var txt_error ="";
            if (name.trim() === '' || email.trim() === '' || password.trim() === '' || confirmPassword.trim() === '') {
                
                txt_error = txt_error+ "All fields are required<br>";
            }
            if(password.trim() != confirmPassword.trim())
            {
                txt_error = txt_error+ "Password did not match<br>";
            }
            if(txt_error.trim()!="")
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: txt_error,
                });
                return false;
            }

        

            return true;
        }
        });
    </script>
@endsection