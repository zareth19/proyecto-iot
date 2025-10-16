@extends('layouts.app') {{-- Usa el mismo layout que el dashboard --}}

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-2xl text-center border border-gray-200">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">
            👋 ¡Bienvenido, {{ $usuario->nombre }} {{ $usuario->apellido }}!
        </h1>
        <p class="text-gray-600 text-lg mb-6">
            Nos alegra verte de nuevo. Desde aquí puedes gestionar tus sensores y mucho más.
        </p>

    </div>
</div>
@endsection
