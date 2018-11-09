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
            <th rowspan="3">
                <img src="img/miCoope.jfif" alt="mi coope">
            </th>
            <th><center><b>COOPERATIVA DE AHORRO Y CRÉDITO INTEGRAL PARROQUIAL</b></center></th>
        </tr>
        <tr id="sol">
            <th></th>
        </tr>
        <tr id="req">
            <th><center><b>ABASTECER LOS SIGUIENTES PRODUCTOS</b></center></th>
        </tr>
        <tr id="head">
            <td><b>Stock</b></b></td>
            <td><b>Descripción</b></td>
        </tr>
        @foreach($productos as $det)
        <tr>
            <td>{{ $det->total_stock }}</td>
            <td>{{ $det->nombre }}</td>
        </tr>
        @endforeach
    </table>


</div>

</body>
</html>
