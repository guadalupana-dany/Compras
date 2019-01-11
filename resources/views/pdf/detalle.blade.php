<!DOCTYPE>
<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Reporte de venta</title>
<style>
    div{
        margin-left: 15px;
        margin-top: 15px;
    }
    table{
        border-collapse: collapse;
        width: 100%;

    }
    td,th{
        border: 1px solid black;
        text-align: center;
    }
    #head{
        height: 40px;
        background-color:silver;
    }
    #req{
        height: 25px;
    }
    #sol{
        height: 25px;
        background-color:silver;
    }
    #total{
        text-align: right;
        font-weight: bold;
    }
    #numTol{
        text-align: center;
        font-weight: bold;
    }
</style>
<body>
<div>
    <table>
        <tr>
            <th colspan="3"><center><b>COOPERATIVA DE AHORRO Y CRÉDITO INTEGRAL PARROQUIAL</b></center></th>
            <th rowspan="3">
                <img src="img/miCoope.jfif" alt="mi coope">
            </th>
        </tr>
        <tr id="sol">
            <th colspan="3"></th>
        </tr>
        <tr id="req">
            <th colspan="3"><center><b>REQUISICION DE PAPELERIA Y UTILES NO. {{ $solicitud->orden }}</b></center></th>
        </tr>
        <tr>
            <td colspan="2"><center>{{date('d-M-y', strtotime($solicitud->fecha_hora))  }}</center></td>
            <td colspan="2"><center>{{ $solicitud->agencia_nombre }} -- {{ $solicitud->departamento_nombre }}</center></td>
        </tr>
        <tr id="head">
            <td><b>Cantidad Entregada</b></td>
            <td><b>Descripción</b></td>
            <td><b>Observacion</b></td>
            <td><b>Gasto</b></td>
        </tr>
        @foreach($detalleSolicitud as $det)
        <tr>
            <td>{{ $det->cantidad }}</td>
            <td>{{ $det->nombre }}</td>
            <td>{{ $det->comentario }}</td>
            <td>{{ $det->precio_unitario *  $det->cantidad }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="3" id="total">Total Gasto </td>
            <td id="numTol">{{ $solicitud->total }}</td>
        </tr>
    </table>

    <br><br><br><br>
    <p>___________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;___________________</p>
    <p><b>Entregado por:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Recibido por:</b></p>
    <p>Encargado de Compras&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $solicitud->nombre_soli }}</p>


</div>

</body>
</html>
