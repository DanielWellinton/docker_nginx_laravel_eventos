@extends('layouts.comum')

@section('content')
    <h1>Criar Evento</h1>
    <form action="{{ route('eventos.store') }}" method="POST">
        @csrf
        <label>Nome do Evento:</label>
        <input type="text" name="nome" required>

        <button type="submit">Criar</button>
    </form>
@endsection