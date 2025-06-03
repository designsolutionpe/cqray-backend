<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de pago</title>
    <style>
        *{ padding: 0; margin: 0; }
        html{ height: 100%; }
        body{
            height: calc(100% - 60px);
            padding: 30px;
        }

        /* table,tr,td{outline: 1px solid black;} */

        img.logo
        {
            width: 150px;
            margin: auto;
            display: block;
        }

        p.info_sede{ text-align: center; margin: 5px 0; }
        p.info_comprobante{ text-align: right; margin: 5px 0; }

        p.info_sede.razon,
        p.info_comprobante span.bold
        { font-weight: bold; letter-spacing: 1px; }

        table.cliente,
        table.compra
        {
            border: 1px solid black;
        }

        table.firmas
        {
            margin-top: 25px;
            position: relative;
            top: 30px;
        }

        table.cliente td,
        table.compra td,
        table.firmas td
        { padding: 10px; }
    </style>
</head>
<body>
    <table class="header" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td width="33%">
                <img class="logo" src="{{ public_path('images/ICORAY.png') }}" alt="Logo">
            </td>
            <td width="33%">
                <p class="info_sede razon">{{ $sede->razon_social ?? 'CENTRO QUIROPRACTICO CQRAY' }}</p>
                <p class="info_sede ruc">RUC: {{ $sede->ruc ?? 'NO RUC' }}</p>
                <p class="info_sede direccion">{{ $sede->direccion_fiscal ?? 'SIN DIRECCION' }}</p>
                <p class="info_sede telef">Telf.: {{ $sede->telefono ?? 'SIN TELEFONO' }}</p>
            </td>
            <td width="33%">
                <p class="info_comprobante">
                    <span class="bold">Fecha emisión: </span>
                    <span>{{ $fecha_emision }}</span>
                </p>
                <p class="info_comprobante">
                    <span class="bold">Número de comprobante: </span>
                </p>
                <p class="info_comprobante">
                    <span>Nº {{ $numero_correlativo }}</span>
                </p>
            </td>
        </tr>
    </table>
    <hr style="width: 100%; margin: 10px 0;">
    <h3 style="text-align: center;">Constancia de Pago</h3>
    <p style="margin-bottom: 10px;">Informacion de cliente:</p>
    <table class="cliente" width="100%" cellspacing="0">
        <tr>
            <td>
                <p>
                    <span style="font-weight: bold; letter-spacing: 1px;">Nombre: </span>
                    <span>{{ $cliente->nombre }} {{ $cliente->apellido }}</span>
                </p>
                <p>
                    <span style="font-weight: bold; letter-spacing: 1px;">Tipo de documento: </span>
                    <span>{{ $cliente->tipo_documento }}</span>
                </p>
                <p>
                    <span style="font-weight: bold; letter-spacing: 1px;">Numero de documento: </span>
                    <span>{{ $cliente->numero_documento }}</span>
                </p>
            </td>
        </tr>
    </table>
    <p style="margin: 10px 0;">Información de pago:</p>
    <table class="compra" width="100%" cellspacing="0">
        <tr>
            <td>
                <p>
                    <span style="font-weight: bold; letter-spacing: 1px;">Tipo de pago: </span>
                    <span>{{ $tipo_pago->nombre }}</span>
                </p>
                <p>
                    <span style="font-weight: bold; letter-spacing: 1px;">Importe: </span>
                    <span>S/ {{ number_format($pago_total / 100,2) }}</span>
                </p>
                <p>
                    <span style="font-weight: bold; letter-spacing: 1px;">Por concepto de: </span>
                    <span>Adquisión de servicio</span>
                </p>
            </td>
        </tr>
    </table>
    <table class="firmas" width="100%" cellspacing="0">
        <tr>
            <td width="50%" style="text-align: center;">
                <div style="border-top: 1px solid black; width: 100%; display: block;"></div>
                <p style="margin-top: 5px;">Firma cliente</p>
            </td>
            <td width="50%" style="text-align: center;">
                <div style="border-top: 1px solid black; width: 100%; display: block;"></div>
                <p style="margin-top: 5px;">Firma administración</p>
            </td>
        </tr>
    </table>
</body>
</html>
