<table class="table">
    <thead>
        <tr>
            <th>Valor</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($precos as $preco)
        <tr>
            <td>R$ {{ number_format($preco->unit_amount / 100, 2, ',', '.') }}</td>
            <td>
                @if($preco->active)
                    <span class="badge bg-success">Ativo</span>
                @else
                    <span class="badge bg-secondary">Inativo</span>
                @endif
            </td>
            <td>
                @if(!$preco->active)
                <button onclick="ativarPreco({{ $preco->id }})" class="btn btn-sm btn-outline-primary">Ativar</button>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
