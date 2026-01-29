<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Barbería') - Sistema de Citas</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #1a1a1a;
            --secondary-color: #d4af37;
            --accent-color: #c9a961;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar-brand {
            font-weight: bold;
            color: var(--secondary-color) !important;
            font-size: 1.5rem;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #2d2d2d 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: var(--primary-color);
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .stat-card.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        
        .stat-card.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .stat-card.info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .badge {
            padding: 0.5em 1em;
            font-weight: 500;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            transition: color 0.2s;
        }
        
        .nav-link:hover {
            color: var(--secondary-color) !important;
        }
        
        .nav-link.active {
            color: var(--secondary-color) !important;
        }
        
        footer {
            background-color: var(--primary-color);
            color: white;
            padding: 2rem 0;
            margin-top: 4rem;
        }
        
        .table {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table thead {
            background-color: var(--primary-color);
            color: white;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('cliente.dashboard')) : '/' }}">
                <i class="bi bi-scissors"></i> Barbería Premium
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('calendario.index') }}">
                                <i class="bi bi-calendar3"></i> Calendario
                            </a>
                        </li>
                        
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.citas') }}">
                                    <i class="bi bi-clipboard-check"></i> Citas
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.clientes') }}">
                                    <i class="bi bi-people"></i> Clientes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.servicios') }}">
                                    <i class="bi bi-bag"></i> Servicios
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('cliente.dashboard') }}">
                                    <i class="bi bi-house"></i> Inicio
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('cliente.citas.crear') }}">
                                    <i class="bi bi-plus-circle"></i> Nueva Cita
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('cliente.mis-citas') }}">
                                    <i class="bi bi-list-check"></i> Mis Citas
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
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->nombre }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <span class="dropdown-item-text">
                                        <small class="text-muted">
                                            @if(auth()->user()->isAdmin())
                                                <i class="bi bi-shield-check"></i> Administrador
                                            @else
                                                <i class="bi bi-person"></i> Cliente {{ ucfirst(auth()->user()->tipo_cliente) }}
                                            @endif
                                        </small>
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
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

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            <!-- Alerts -->
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

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Barbería Premium. Sistema de Citas Laravel.</p>
            <p class="mb-0">
                <small>
                    <i class="bi bi-code-slash"></i> Desarrollado con Laravel
                </small>
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    
    @yield('scripts')
</body>
</html>
