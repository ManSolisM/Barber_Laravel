@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="bi bi-scissors" style="font-size: 3rem; color: var(--secondary-color);"></i>
                    <h2 class="mt-3">Iniciar Sesión</h2>
                    <p class="text-muted">Accede a tu cuenta de barbería</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Recordarme
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                    </button>

                    <div class="text-center">
                        <p class="mb-0">¿No tienes cuenta? 
                            <a href="{{ route('register') }}" class="text-decoration-none">
                                Regístrate aquí
                            </a>
                        </p>
                    </div>
                </form>

                <hr class="my-4">
                
                <div class="text-center">
                    <small class="text-muted">
                        <strong>Cuentas de prueba:</strong><br>
                        Admin: admin@barberia.com / admin123<br>
                        Cliente: cliente@example.com / cliente123
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
