<h1>Alertas</h1>
<table class="table-auto w-full">
    <thead>
        <tr>
            <th>Sensor</th>
            <th>Valor</th>
            <th>Descripción</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        @foreach($alertas as $alerta)
            <tr>
                <td>{{ $alerta->sensor }}</td>
                <td>{{ $alerta->valor }}</td>
                <td>{{ $alerta->descripcion }}</td>
                <td>{{ $alerta->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
