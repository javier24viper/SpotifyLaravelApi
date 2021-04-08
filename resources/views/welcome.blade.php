@extends('layouts.layout')

@section('pageTitle')
Welcome
@endsection

@section('title')
Spotify Favourites
@endsection

@section('content')
<div class="max-w-md rounded overflow-hidden shadow-lg mx-auto mt-8 border bg-grey-lightest">
    <div class="py-6 px-4">
        <div class="font-bold text-xl pb-2">
            Login
        </div>
        <div>
            <p class="mb-2">Después de iniciar secion se mostrará un menú desde el cual podrá seleccionar sus artistas o canciones favoritas</p>
            <p class="mb-2">podrá reproducir las canciones y obtener información de ellas.</p>
            <p class="pt-4">
                <a href="{{ route('login') }}" class="bg-neon-green hover:bg-neon-green-dark text-white font-bold py-2 px-4 rounded mt-2 no-underline">Inicia sesión</a>
            </p>
        </div>
    </div>
</div>

@endsection