<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem Akademik</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root {
            --primary-color: #1e40af;
            --primary-hover: #1e3a8a;
            --error-color: #ef4444;
            --error-light: #fee2e2;
            --text-color: #1f2937;
            --text-light: #6b7280;
            --light-bg: #f9fafb;
            --white: #ffffff;
            --border-color: #e5e7eb;
            --radius: 0.5rem;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            background-image: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 58, 138, 0.8) 50%, rgba(13, 148, 136, 0.85) 100%), url('https://source.unsplash.com/random/1600x900/?university,campus');
            background-size: cover;
            background-position: center;
        }

        .login-container {
            background-color: var(--white);
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 380px;
            padding: 2rem 1.5rem;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            text-align: center;
            margin-bottom: 1.25rem;
        }

        .logo i {
            font-size: 2rem;
            color: #ffffff;
            background: linear-gradient(135deg, #1e40af 0%, #0d9488 100%);
            width: 70px;
            height: 70px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(13, 148, 136, 0.3);
        }

        h1 {
            text-align: center;
            margin-bottom: 1.25rem;
            color: var(--text-color);
            font-size: 1.5rem;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.25rem;
            position: relative;
        }

        .input-wrapper {
            position: relative;
        }

        input {
            width: 100%;
            padding: 0.75rem 2.5rem 0.75rem 3rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius);
            font-size: 0.875rem;
            transition: var(--transition);
            background-color: var(--light-bg);
            color: var(--text-color);
        }

        input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.2);
            background-color: var(--white);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            transition: var(--transition);
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            cursor: pointer;
            transition: var(--transition);
        }

        .toggle-password:hover {
            color: var(--primary-color);
        }

        input:focus+.input-icon {
            color: var(--primary-color);
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #1e40af 0%, #0d9488 100%);
            color: var(--white);
            border: none;
            border-radius: var(--radius);
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        button:hover {
            background: linear-gradient(135deg, #1e3a8a 0%, #0f766e 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(13, 148, 136, 0.3);
        }

        button:disabled {
            background: var(--text-light);
            cursor: not-allowed;
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            margin-bottom: 1.25rem;
            font-size: 0.875rem;
        }

        .alert-danger {
            background-color: var(--error-light);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--error-color);
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="logo"><i class="fas fa-graduation-cap"></i></div>
        <h1>Masuk ke Sistem Akademik</h1>

        <div id="error-container"></div>

        {{-- PERUBAHAN: Menambahkan ID pada form --}}
        <form method="POST" action="{{ route('login.attempt') }}" id="login-form">
            @csrf
            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="identifier" name="identifier" value="{{ old('identifier') }}"
                        placeholder="Email atau NIM" required autofocus>
                </div>
            </div>
            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                </div>
            </div>
            <button type="submit" id="submit-button">
                <i class="fas fa-sign-in-alt"></i><span>Masuk</span>
            </button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- PERUBAHAN: Menambahkan skrip AJAX --}}
    <script>
        $(document).ready(function () {
            $('#togglePassword').on('click', function() {
                const passwordField = $('#password');
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                $(this).toggleClass('fa-eye fa-eye-slash');
            });

            $('#login-form').on('submit', function (e) {
                e.preventDefault();
                const form = $(this);
                const submitButton = $('#submit-button');
                const errorContainer = $('#error-container');

                // Reset UI
                submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...');
                errorContainer.html('');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Login Berhasil!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false,
                                willClose: () => {
                                    window.location.href = response.redirect_url;
                                }
                            });
                        }
                    },
                    error: function (xhr) {
                        submitButton.prop('disabled', false).html('<i class="fas fa-sign-in-alt"></i><span>Masuk</span>');
                        let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                        if (xhr.status === 422 && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        const errorHtml = `<div class="alert alert-danger">${errorMessage}</div>`;
                        errorContainer.html(errorHtml);
                    }
                });
            });
        });
    </script>
</body>

</html>