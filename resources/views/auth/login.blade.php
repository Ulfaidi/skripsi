<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HALAMAN LOGIN - SISTEM PENDUKUNG KEPUTUSAN JEMBATAN</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-card {
      width: 100%;
      max-width: 400px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      padding: 20px;
      border: none;
    }

    .header-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      border-bottom: 1px solid #dee2e6;
      padding-bottom: 20px;
    }

    .logo-section img {
      height: 70px;
      object-fit: contain;
    }

    .pupr-text {
      font-size: 60px;
      font-weight: bold;
      letter-spacing: 2px;
      text-transform: uppercase;
      display: flex;
      align-items: center;
    }

    .bina-marga-section {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding-top: 4px;
      padding-bottom: 4px;
    }

    .bina,
    .marga {
      font-size: 18px;
      font-weight: bold;
      letter-spacing: 10px;
      text-transform: uppercase;
    }

    .btn-login {
      background-color: #0d6efd;
      color: #fff;
      padding: 10px;
      border: none;
    }

    .btn-login:hover {
      background-color: #0b5ed7;
    }

    @media (max-width: 576px) {
      .header-logo {
        flex-direction: column;
        align-items: center;
        gap: 10px;
      }

      .pupr-text {
        font-size: 40px;
      }

      .bina,
      .marga {
        font-size: 16px;
        letter-spacing: 6px;
        text-align: center;
      }

      .bina-marga-section {
        align-items: center;
      }
    }
  </style>
</head>
<body>
  <div class="card login-card">
    <div class="header-logo">
      <div class="logo-section">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" />
      </div>
      <div class="pupr-text">PUPR</div>
      <div class="bina-marga-section">
        <div class="bina">BINA</div>
        <div class="marga">MARGA</div>
      </div>
    </div>

    <div class="card-body p-0 pt-4">
      @if ($errors->any())
        <div class="alert alert-danger">
          {{ $errors->first() }}
        </div>
      @endif

      <form action="{{ route('login.submit') }}" method="POST">
        @csrf
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input
            type="text"
            class="form-control"
            id="username"
            name="username"
            value="{{ old('username') }}"
            required
            autofocus
          />
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input
            type="password"
            class="form-control"
            id="password"
            name="password"
            required
          />
        </div>

        <button type="submit" class="btn btn-login w-100">Login</button>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
