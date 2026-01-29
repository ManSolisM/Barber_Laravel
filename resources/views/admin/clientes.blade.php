@extends('layouts.app')
@section('title', 'Gestión de Clientes')
@section('content')
<h1><i class="bi bi-people"></i> Gestión de Clientes</h1>
<div class="card mt-4">
    <div class="card-body">
        <table class="table">
            <thead><tr><th>Nombre</th><th>Email</th><th>Tipo</th><th>Citas</th><th>Acciones</th></tr></thead>
            <tbody>
                @foreach($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->nombre_completo }}</td>
                    <td>{{ $cliente->email }}</td>
                    <td>
                        @if($cliente->isPermanente())
                        <span class="badge bg-success">Permanente</span>
                        @else
                        <span class="badge bg-warning">Temporal</span>
                        @endif
                    </td>
                    <td>{{ $cliente->citas_count }}</td>
                    <td>
                        @if($cliente->isTemporal())
                        <form method="POST" action="{{ route('admin.clientes.aprobar', $cliente) }}" style="display:inline">
                            @csrf
                            <button class="btn btn-sm btn-success">Aprobar</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $clientes->links() }}
    </div>
</div>
@endsection
