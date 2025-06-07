<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Pengguna</title>

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- AdminLTE style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body class="hold-transition">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="login-card-split">
                <div class="login-card-left">
                    <div class="brand-logo text-center" style="font-weight: 600; font-size: 40px;">presma</div>
                    <h2 class="mb-1 text-center" style="font-weight: 500;">Create Account</h2>
                    <p class="mb-4 text-center text-muted">Register a new account</p>

                    <form action="{{ url('register') }}" method="POST" id="form-register">
                        @csrf
                        <div class="form-group">
                            <label for="username">NIM</label>
                            <input type="text" id="username" name="username" class="form-control"
                                placeholder="Enter your NIM">
                            <small id="error-username" class="error-text text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label for="nama">Full Name</label>
                            <input type="text" id="nama" name="nama" class="form-control"
                                placeholder="Enter your full name">
                            <small id="error-nama" class="error-text text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="Enter your email">
                            <small id="error-email" class="error-text text-danger"></small>
                        </div>

                        <div class="form-group position-relative">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="Create a password">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-eye" id="toggle-password" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                            </div>
                            <small id="error-password" class="error-text text-danger"></small>
                        </div>

                        <div class="form-group position-relative">
                            <label for="password_confirmation">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control" placeholder="Confirm your password">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-eye" id="toggle-password-confirmation"
                                            style="cursor: pointer;"></i>
                                    </span>
                                </div>
                            </div>
                            <small id="error-password_confirmation" class="error-text text-danger"></small>
                        </div>

                        <input type="hidden" name="role" id="role" value="mahasiswa">
                        <button type="submit" class="btn btn-primary btn-block">Create Account</button>
                    </form>

                    <div class="sign-up-link">
                        Already have an account? <a href="{{ url('login') }}">Sign in</a>
                    </div>
                </div>

                <div class="login-card-right">
                    <img src="{{ asset('images/polinemas.png') }}" alt="Student 2">
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#form-register").validate({
                rules: {
                    nama: {
                        required: true,
                        minlength: 3
                    },
                    username: {
                        required: true,
                        minlength: 4,
                        maxlength: 20
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 5
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password"
                    },
                    role: {
                        required: true
                    }
                },
                messages: {
                    nama: {
                        required: "Nama lengkap wajib diisi",
                        minlength: "Nama harus terdiri dari minimal 3 karakter"
                    },
                    password_confirmation: {
                        equalTo: "Password tidak sama!"
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: "Register Berhasil",
                                    text: response.message,
                                }).then(() => window.location = response.redirect);
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $("#error-" + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                },
                errorElement: "span",
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                }
            });
            // Fitur view/hide password
            $('#toggle-password').on('click', function() {
                const passwordInput = $('#password');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);

                // Toggle icon
                $(this).toggleClass('fa-eye fa-eye-slash');
            });
            $('#toggle-password-confirmation').on('click', function() {
                const passwordConfirmationInput = $('#password_confirmation');
                const type = passwordConfirmationInput.attr('type') === 'password' ? 'text' : 'password';
                passwordConfirmationInput.attr('type', type);

                // Toggle icon
                $(this).toggleClass('fa-eye fa-eye-slash');
            });
        });
    </script>
</body>

</html>
