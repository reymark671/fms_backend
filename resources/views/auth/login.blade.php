<x-laravel-ui-adminlte::adminlte-layout>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <body class="hold-transition login-page">
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="login-box">
            <div class="login-logo">
                <a href="{{ url('/home') }}"><b>{{ config('app.name') }}</b></a>
            </div>
            <!-- /.login-logo -->

            <!-- /.login-box-body -->
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign in</p>

                    <form method="post" action="{{ url('/login') }}">
                        @csrf

                        <div class="input-group mb-3">
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email"
                                class="email_user form-control @error('email') is-invalid @enderror">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                            </div>
                            @error('email')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <input type="password" name="password" placeholder="Password"
                                class="pass_user form-control @error('password') is-invalid @enderror">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            @error('password')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror

                        </div>

                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="remember">
                                    <label for="remember">Remember Me</label>
                                </div>
                            </div>

                            <div class="col-4">
                                <button type="button" class="btn btn-primary btn-block btn_submit">Sign In</button>
                            </div>

                        </div>
                    </form>

                    <p class="mb-1">
                        <a href="{{ route('password.request') }}">I forgot my password</a>
                    </p>
               
                </div>
                <!-- /.login-card-body -->
            </div>

        </div>
        <!-- /.login-box -->
        <div id="otpModal" class="modal" style="display: none;">
            <div class="modal-overlay"></div>
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Enter OTP</h2>
                <form method="post" >
                    @csrf
                    <!-- <div class="form-group">
                
                    <input type="number" name="otp" placeholder="Enter OTP" class="form-control otp">
                    </div> -->
                    <div class="container">
                        <div id="inputs" class="inputs">
                            <input class="input" type="text"
                                inputmode="numeric" maxlength="1" />
                            <input class="input" type="text"
                                inputmode="numeric" maxlength="1" />
                            <input class="input" type="text"
                                inputmode="numeric" maxlength="1" />
                            <input class="input" type="text"
                                inputmode="numeric" maxlength="1" />
                            <input class="input" type="text"
                                inputmode="numeric" maxlength="1" />
                            <input class="input" type="text"
                                inputmode="numeric" maxlength="1" />
                        </div>
                    </div>
                    <button type="button" class="form-control btn btn-primary btn_verify">Verify OTP</button>
                </form>
                
            </div>
        </div>
    </body>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
			  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
			  crossorigin="anonymous"></script>
              <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
              <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
              
    <script>
    
   
      $(document).ready(function () {
        
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $(this).on('click','.btn_submit', function(){
           if($('.email_user').val() =="" || $('.pass_user').val()=="")
           {
            return alertify.error('Required Fields');
           }
           $("#inputs input").each(function() {
                $(this).val(""); 
            });
           Swal.fire({
                title: 'Sending OTP',
                html: 'Please wait...',
                allowOutsideClick: false,
                showConfirmButton: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('login_verify') }}", 
                method: 'POST',
                data: { 
                    email: $('.email_user').val(),
                    password:  $('.pass_user').val()
                 },
                headers: {
                'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    Swal.close();
                    if(response.success) $('#otpModal').css('display', 'block');
                    else alertify.error('Invalid Credentials');
                },
                error: function(error) {
                    Swal.close(); 
                    alertify.error('Invalid Credentials');
                }
            });
        });
        $(this).on('click','.btn_verify', function(){
            var inputs_data = $("#inputs input");
            var valuesString = inputs_data.map(function() {
                return $(this).val();
            }).get().join('');
            if(valuesString.length<6) return false;
            $.ajax({
                url: "{{ route('otp_verify') }}", 
                method: 'POST',
                data: { 
                    email: $('.email_user').val(),
                    password:  $('.pass_user').val(),
                    otp:  valuesString,
                 },
                headers: {
                'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if(response.success)window.location.href = "{{ route('home') }}";
                    else  alertify.error('Invalid OTP');

                },
                error: function(error) {
                   return alertify.error('Invalid OTP');
                }
            });

        })

        $(this).on('click', '.close', function () {
            $('#otpModal').css('display', 'none');
        });

        const inputs = $("#inputs input");
 
        inputs.on("input", function(e) {
            const target = $(e.target);
            const val = target.val();

            if (isNaN(val)) {
                target.val("");
                return;
            }

            if (val !== "") {
                const next = target.next();
                if (next.length) {
                    next.focus();
                }
            }
        });

        inputs.on("keyup", function(e) {
            const target = $(e.target);
            const key = e.key.toLowerCase();

            if (key === "backspace" || key === "delete") {
                target.val("");
                const prev = target.prev();
                if (prev.length) {
                    prev.focus();
                }
                return;
            }
        });
      });
    </script>
  <style>
    /* Add this style to center the modal and create an overlay */
    #otpModal {
        display: flex;
        align-items: center;
        justify-content: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Background overlay color with transparency */
        z-index: 1; /* Ensure the overlay is on top */
    }

    .modal-content {
        background-color: #fff; /* Modal background color */
        padding: 20px;
        border-radius: 8px;
        max-width: 70%; /* Set a maximum width for the modal */
        width: 100%; /* Ensure the width is responsive */
        z-index: 2; /* Ensure the modal is on top of the overlay */
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    /* Add styles for the close button if needed */
    .close {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
    }
    .container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 40%;
    
}
 
.input {
    width: 40px;
    border: none;
    border-bottom: 3px solid rgba(0, 0, 0, 0.5);
    margin: 0 10px;
    margin-bottom:5px;
    text-align: center;
    font-size: 36px;
    cursor: not-allowed;
    pointer-events: none;
}
 
.input:focus {
    border-bottom: 3px solid orange;
    outline: none;
}
 
.input:nth-child(1) {
    cursor: pointer;
    pointer-events: all;
}

    
</style>



</x-laravel-ui-adminlte::adminlte-layout>
