<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RiskIntel Supply Chain</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Custom Theme Variables -->
    <link rel="stylesheet" href="{{ asset('css/custom-theme.css') }}">
    
    <style>
        body {
            background: radial-gradient(circle at 10% 20%, #0d1222 0%, #080b14 90%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
        }
    </style>
</head>
<body>
    <div class="glass-card login-card fade-in-up shadow">
        <!-- Logo -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary p-3 rounded-circle mb-3" style="width: 60px; height: 60px;">
                <i class="fa-solid fa-earth-asia fa-spin fs-3" style="--fa-animation-duration: 25s; color: var(--accent-color);"></i>
            </div>
            <h2 class="fw-bold text-white mb-1" style="font-family: 'Outfit';">RiskIntel</h2>
            <p class="text-secondary fs-7">Global Supply Chain Risk Intelligence Platform</p>
        </div>

        <form action="{{ route('dashboard') }}" method="GET" class="d-flex flex-column gap-3">
            <!-- Email -->
            <div>
                <label class="form-label text-secondary fs-7">Alamat Email</label>
                <div class="position-relative d-flex align-items-center">
                    <i class="fa-regular fa-envelope text-secondary position-absolute ms-3"></i>
                    <input type="email" class="form-control custom-input ps-5 w-100" placeholder="admin@riskintel.local" required value="admin@riskintel.local">
                </div>
            </div>

            <!-- Password -->
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label text-secondary fs-7 mb-0">Kata Sandi</label>
                    <a href="#" class="text-primary fs-8 text-decoration-none">Lupa Sandi?</a>
                </div>
                <div class="position-relative d-flex align-items-center">
                    <i class="fa-solid fa-lock text-secondary position-absolute ms-3"></i>
                    <input type="password" class="form-control custom-input ps-5 w-100" placeholder="••••••••" required value="password">
                </div>
            </div>

            <!-- Remember me -->
            <div class="form-check">
                <input class="form-check-input bg-transparent border-secondary" type="checkbox" id="remember">
                <label class="form-check-label text-secondary fs-7" for="remember">Ingat perangkat ini</label>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary w-100 py-2.5 mt-2 fw-semibold d-flex align-items-center justify-content-center gap-2">
                Masuk Sistem <i class="fa-solid fa-right-to-bracket"></i>
            </button>
        </form>

        <div class="text-center mt-4">
            <span class="text-secondary fs-8">Belum memiliki akun? <a href="#" class="text-primary text-decoration-none fw-semibold">Hubungi Admin</a></span>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
