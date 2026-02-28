<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'JAMELZ Barbería') - Sistema de Citas</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --jamelz-turquesa: #2D9B9B;
            --jamelz-coral: #FF6B4A;
            --jamelz-azul-profundo: #1A2332;
            --jamelz-beige: #F4D6A8;
            --jamelz-verde-circuito: #3B7D7D;
            --jamelz-naranja-fuego: #E85D3F;
            --jamelz-gris-tech: #4A5568;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8F9FA;
        }
        
        h1,h2,h3,h4,h5,h6 {
            font-family:'Montserrat',sans-serif;
            font-weight:700;
        }
        
        .navbar {
            background: linear-gradient(135deg,var(--jamelz-azul-profundo) 0%,var(--jamelz-verde-circuito) 100%);
            box-shadow: 0 4px 20px rgba(45,155,155,0.3);
            padding:1rem 0;
        }
        
        .navbar-brand {
            font-family:'Montserrat',sans-serif;
            font-weight:800;
            font-size:1.8rem;
            color:var(--jamelz-coral)!important;
            text-transform:uppercase;
            letter-spacing:2px;
            display:flex;
            align-items:center;
            gap:0.8rem;
        }
        
        .logo-container {
            width:50px;
            height:50px;
            background:var(--jamelz-beige);
            border-radius:12px;
            display:flex;
            align-items:center;
            justify-content:center;
            box-shadow:0 4px 12px rgba(0,0,0,0.2);
        }
        
        .nav-link {
            color:rgba(255,255,255,0.9)!important;
            font-weight:500;
            transition:all 0.3s ease;
            position:relative;
            padding:0.5rem 1rem!important;
        }
        
        .nav-link:hover {
            color:var(--jamelz-coral)!important;
        }
        
        .btn-primary {
            background:linear-gradient(135deg,var(--jamelz-coral) 0%,var(--jamelz-naranja-fuego) 100%);
            border:none;
            color:white;
            font-weight:600;
            padding:0.7rem 1.5rem;
            border-radius:12px;
            box-shadow:0 4px 15px rgba(255,107,74,0.3);
        }
        
        .btn-primary:hover {
            box-shadow:0 6px 25px rgba(255,107,74,0.5);
        }
        
        .btn-success {
            background:var(--jamelz-turquesa);
            border:none;
        }
        
        .btn-success:hover {
            background:var(--jamelz-verde-circuito);
        }
        
        /* CARDS SIN ANIMACIONES MOLESTAS */
        .card {
            border:none;
            border-radius:16px;
            box-shadow:0 8px 24px rgba(0,0,0,0.08);
            /* QUITAMOS LA TRANSICIÓN */
        }
        
        /* QUITAMOS EL HOVER QUE MUEVE LAS CARDS */
        .card:hover {
            /* NO transform */
            /* NO box-shadow animado */
        }
        
        .card-header {
            background:linear-gradient(145deg,var(--jamelz-turquesa) 0%,var(--jamelz-verde-circuito) 100%);
            color:white;
            border:none;
            padding:1.2rem 1.5rem;
            font-weight:600;
        }
        
        .stat-card {
            background:linear-gradient(135deg,var(--jamelz-turquesa) 0%,var(--jamelz-verde-circuito) 100%);
            color:white;
            border-radius:16px;
            padding:1.5rem;
        }
        
        .stat-card.warning {
            background:linear-gradient(135deg,var(--jamelz-coral) 0%,var(--jamelz-naranja-fuego) 100%);
        }
        
        .stat-card.success {
            background:linear-gradient(135deg,#11998e 0%,#38ef7d 100%);
        }
        
        .stat-card.info {
            background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);
        }
        
        .badge {
            padding:0.5em 1em;
            font-weight:600;
            border-radius:8px;
        }
        
        .alert {
            border-radius:12px;
            border:none;
            box-shadow:0 4px 12px rgba(0,0,0,0.08);
        }
        
        .table thead {
            background:linear-gradient(145deg,var(--jamelz-turquesa) 0%,var(--jamelz-verde-circuito) 100%);
            color:white;
        }
        
        /* HOVER DE TABLA MÁS SUAVE */
        .table tbody tr {
            transition: background-color 0.2s ease;
        }
        
        .table tbody tr:hover {
            background:rgba(45,155,155,0.05);
        }
        
        footer {
            background:linear-gradient(135deg,var(--jamelz-azul-profundo) 0%,var(--jamelz-verde-circuito) 100%);
            color:white;
            padding:3rem 0;
            margin-top:5rem;
        }
        
        .modal-content {
            border-radius:16px;
            border:none;
        }
        
        .modal-header {
            background:linear-gradient(145deg,var(--jamelz-turquesa) 0%,var(--jamelz-verde-circuito) 100%);
            color:white;
            border-radius:16px 16px 0 0;
            border:none;
        }
        
        .modal-header.bg-success {
            background:linear-gradient(145deg,#11998e 0%,#38ef7d 100%) !important;
        }

        .modal-header.bg-danger {
            background:linear-gradient(145deg,#dc3545 0%,#c82333 100%) !important;
        }

        .modal-header.bg-info {
            background:linear-gradient(145deg,#4facfe 0%,#00f2fe 100%) !important;
        }
        
        .form-control,.form-select {
            border-radius:10px;
            border:2px solid #E5E7EB;
            padding:0.7rem 1rem;
        }
        
        .form-control:focus,.form-select:focus {
            border-color:var(--jamelz-turquesa);
            box-shadow:0 0 0 0.25rem rgba(45,155,155,0.15);
        }
        
        /* QUITAMOS ANIMACIONES INNECESARIAS */
        .fade-in-up {
            /* Sin animación */
        }
        
        .text-jamelz-turquesa {color:var(--jamelz-turquesa)}
        .text-jamelz-coral {color:var(--jamelz-coral)}

        .input-group-text {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .alert ul {
            padding-left: 1.2rem;
        }

        .modal-body .alert:last-child {
            margin-bottom: 0;
        }

        /* Dropdown mejorado */
        .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .dropdown-item {
            padding: 0.7rem 1.5rem;
        }

        .dropdown-item:hover {
            background-color: rgba(45,155,155,0.1);
        }

        /* Navbar toggler */
        .navbar-toggler {
            border: 2px solid rgba(255,255,255,0.5);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* OPTIMIZACIÓN PERFORMANCE */
        * {
            backface-visibility: hidden;
        }

        .table {
            transform: translateZ(0);
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('cliente.dashboard')) : '/' }}">
                <div class="logo-container">
                    <i class="bi bi-scissors" style="font-size:1.5rem;color:var(--jamelz-verde-circuito)"></i>
                </div>
                JAMELZ
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.citas') }}">
                                    <i class="bi bi-calendar-check"></i> Citas
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.clientes') }}">
                                    <i class="bi bi-people"></i> Clientes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.servicios') }}">
                                    <i class="bi bi-briefcase"></i> Servicios
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('cliente.dashboard') }}">
                                    <i class="bi bi-house"></i> Inicio
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('cliente.mis-citas') }}">
                                    <i class="bi bi-calendar3"></i> Mis Citas
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('cliente.citas.crear') }}">
                                    <i class="bi bi-plus-circle"></i> Nueva Cita
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('calendario.index') }}">
                                    <i class="bi bi-calendar-week"></i> Calendario
                                </a>
                            </li>
                            @if(auth()->user()->isPermanente())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('cliente.historial') }}">
                                        <i class="bi bi-clock-history"></i> Historial
                                    </a>
                                </li>
                            @endif
                        @endif
                            
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->nombre }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li>
                                    <span class="dropdown-item-text">
                                        <small class="text-muted">{{ auth()->user()->email }}</small>
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> Registrarse
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="text-center">
        <div class="container">
            <p class="mb-2 fs-5 fw-bold">
                <i class="bi bi-scissors"></i> JAMELZ Barbería Premium
            </p>
            <p class="mb-0">&copy; {{ date('Y') }} Todos los derechos reservados.</p>
            <p class="mb-0">
                <small>
                    <i class="bi bi-code-slash"></i> Desarrollado con Laravel & ❤️
                </small>
            </p>
        </div>
    </footer>

    <!-- Scripts - SIN DUPLICADOS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    @yield('scripts')
</body>
</html>