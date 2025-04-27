@extends('layouts.comum')

@section('title', isset($evento) ? 'Editar Evento' : 'Criar Evento')

@section('head')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ getenv('MAPS_KEY') }}&libraries=maps,marker&v=beta"
        defer
    ></script>
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h3>{{ isset($evento) ? 'Editar Evento' : 'Criar Evento' }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ isset($evento) ? route('eventos.update', $evento->id) : route('eventos.store') }}" 
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($evento))
                @method('PUT')
            @endif

            <div class="mb-3 ms-1">
                <label for="nome" class="form-label">Nome do Evento</label>
                <input type="text" id="nome" name="nome" class="form-control" value="{{ old('nome', $evento->nome ?? '') }}" required>
            </div>

            <div class="mb-3 ms-1">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea id="descricao" name="descricao" class="form-control" required>{{ old('descricao', $evento->descricao ?? '') }}</textarea>
            </div>

            <div class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="mb-3 ms-1">
                                <label for="local" class="form-label">Local</label>
                                <textarea id="local" name="local" class="form-control" required>{{ old('local', $evento->local ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 ms-1">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="number" id="latitude" name="latitude" class="form-control" step="0.00001" min="-90" max="90" value="{{ old('latitude', $evento->latitude ?? '') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 ms-1">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="number" id="longitude" name="longitude" class="form-control" step="0.00001" min="-180" max="180" value="{{ old('longitude', $evento->longitude ?? '') }}" required>
                            </div>
                        </div>
                    </div>

                    @if(isset($evento))
                        <div class="col-md-8">
                            <gmp-map center="{{ $evento->latitude }},{{ $evento->longitude }}" zoom="10" map-id="DEMO_MAP_ID" style="height: 400px">
                                <gmp-advanced-marker
                                    position="{{ $evento->latitude }},{{ $evento->longitude }}"
                                ></gmp-advanced-marker>
                            </gmp-map>
                        </div>
                    @endif
                </div>
            </div>

            <div class="ms-1">
                <div class="row">
                    <div class="col-md-6 mb-3">
                    <label for="data_inicio" class="form-label">Data de Início</label>
                    <input type="datetime-local" id="data_inicio" name="data_inicio" class="form-control" 
                        value="{{ old('data_inicio', isset($evento) ? date('Y-m-d\TH:i', strtotime($evento->data_inicio)) : '') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="data_fim" class="form-label">Data de Fim</label>
                        <input type="datetime-local" id="data_fim" name="data_fim" class="form-control" 
                            value="{{ old('data_fim', isset($evento) ? date('Y-m-d\TH:i', strtotime($evento->data_fim)) : '') }}" required>
                    </div>
                </div>
            </div>

            <div class="ms-1">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="capacidade" class="form-label">Capacidade</label>
                        <input type="number" id="capacidade" name="capacidade" class="form-control" step="1" min="1" value="{{ old('capacidade', $evento->capacidade ?? '') }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="imagem" class="form-label">Imagem do Evento</label>
                        <input type="file" id="imagem" name="imagem" class="form-control" accept="image/*">
                    </div>

                    @if(isset($evento) && $evento->imagem)
                        <div class="col-md-6 mb-3">
                            <p>Imagem Atual:</p>
                            <img src="{{ asset('storage/' . $evento->imagem) }}" alt="Imagem do Evento" class="img-fluid rounded" height="200">
                        </div>
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary">{{ isset($evento) ? 'Atualizar' : 'Criar' }}</button>
            <a href="{{ route('eventos.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

@endsection
