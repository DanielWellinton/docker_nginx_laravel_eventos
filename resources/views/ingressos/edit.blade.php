@extends('layouts.comum')

@section('title', 'Editar Ingresso')

@section('content')
    <h1 class="mb-4">Editar Ingresso</h1>

    <form action="{{ route('ingressos.update', $ingresso->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="evento_id" class="form-label">Evento</label>
            <select class="form-select" name="evento_id" required>
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id }}" {{ $ingresso->evento_id == $evento->id ? 'selected' : '' }}>
                        {{ $evento->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Nome do Ingresso</label>
            <input type="text" class="form-control" name="name" value="{{ $ingresso->name }}" required>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantidade</label>
            <input type="number" class="form-control" name="quantity" min="1" value="{{ $ingresso->quantity }}" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="active" name="active" {{ old('active', $ingresso->active) ? 'checked' : '' }}>
            <label class="form-check-label" for="active">Ativo?</label>
        </div>

        <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Atualizar</button>
        <a href="{{ route('ingressos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>

    <hr class="my-4">

    <h5>Preços</h5>
    <div id="precos-wrapper">
        <!-- Tabela de preços será carregada via AJAX -->
    </div>
    <button class="btn btn-sm btn-primary mt-2" onclick="abrirModalNovoPreco()">Adicionar Preço</button>

    <!-- Modal para adicionar novo preço -->
    <div class="modal fade" id="modalNovoPreco" tabindex="-1" aria-labelledby="modalNovoPrecoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formNovoPreco">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalNovoPrecoLabel">Novo Preço</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="unit_amount" class="form-label">Valor (em centavos)</label>
                            <input type="number" class="form-control" id="unit_amount" name="unit_amount" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="active_price" name="active_price">
                            <label class="form-check-label" for="active_price">Ativo?</label>
                        </div>
                        <input type="hidden" name="ingresso_id" id="modal_ingresso_id">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        let INGRESSO_ID = {{ $ingresso->id }};

        function carregarPrecos(ingressoId) {
            const wrapper = document.getElementById('precos-wrapper');
            wrapper.innerHTML = '<p>Carregando preços...</p>';

            fetch(`/ingressos/${ingressoId}/precos`)
                .then(res => res.text())
                .then(html => {
                    wrapper.innerHTML = html;
                });
        }

        function abrirModalNovoPreco() {
            document.getElementById('unit_amount').value = '';
            document.getElementById('active_price').checked = false;
            document.getElementById('modal_ingresso_id').value = INGRESSO_ID;
            const modal = new bootstrap.Modal(document.getElementById('modalNovoPreco'));
            modal.show();
        }

        document.getElementById('formNovoPreco').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            // Obter o token CSRF a partir do input hidden
            const csrfToken = document.querySelector('input[name="_token"]').value;

            fetch('/precos', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Usando o token CSRF do input hidden
                },
                body: formData
            }).then(response => {
                if (!response.ok) throw new Error('Erro ao salvar o preço');
                return response.json();
            }).then(data => {
                bootstrap.Modal.getInstance(document.getElementById('modalNovoPreco')).hide();
                carregarPrecos(INGRESSO_ID);
            }).catch(error => {
                alert(error.message);
            });
        });

        function ativarPreco(id) {
            const csrfToken = document.querySelector('input[name="_token"]').value;

            fetch(`/precos/${id}/ativar`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken, // Usando o token CSRF do input hidden
                },
            }).then(() => {
                carregarPrecos(INGRESSO_ID);
            });
        }

        // Carrega os preços ao abrir a página
        window.onload = () => {
            carregarPrecos(INGRESSO_ID);
        }
    </script>
@endsection
