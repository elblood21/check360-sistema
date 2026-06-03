<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #000;
        padding: 2px 6px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }
</style>
<body>
    <div style="width:100%;">
        <div style="width:8rem;aspect-ratio:1;height:5rem;padding:2rem 1rem;display:flex;border:0.1rem solid rgb(201, 201, 201);">
            <img src="{{$logo}}" alt="" style="width:100%;height:100%;margin:auto;">
        </div>
        <h1 style="color:#17365D;text-align:left;width:95%;">INFORME LEGAL DE CUMPLIMIENTO LEGAL</h1>
        <hr style="color:#17365D;">
        <h3 style="color:#17365D">1. Ficha de identificación</h3>

        <table style="width:100%;">
            <tbody>
                <tr>
                    <td style="width:40%;">Nombre de la empresa</td>
                    <td>{{$cliente->razon_social}}</td>
                </tr>
                <tr>
                    <td>RUT</td>
                    <td>{{$cliente->rut}}</td>  
                </tr>
                <tr>
                    <td>Giro</td>
                    <td>{{$cliente->giro}}</td>
                </tr>
                <tr>
                    <td>Domicilio</td>
                    <td>{{$cliente->domicilio}}</td>
                </tr>
                <tr>
                    <td>Representante legal</td>
                    <td>{{$cliente->representante_legal}}</td>
                </tr>
                <tr>
                    <td>Correo electronico</td>
                    <td>{{$cliente->representante_email}}</td>
                </tr>
            </tbody>
        </table>

        <h3 style="color:#17365D">Trabajador evaluado</h3>

        <table style="width:100%;">
            <tbody>
                <tr>
                    <td style="width:40%;">Nombre completo</td>
                    <td>{{$trabajador['name']}}</td>
                </tr>
                <tr>
                    <td>RUT</td>
                    <td>{{$trabajador['rut']}}</td>  
                </tr>
                <tr>
                    <td>Cargo</td>
                    <td>{{$trabajador['cargo']}}</td>
                </tr>
                <tr>
                    <td>Fecha de ingreso</td>
                    <td>{{date('d/m/Y', strtotime($trabajador['fecha_ingreso']))}}</td>
                </tr>
                <tr>
                    <td>Tipo de contrato</td>
                    <td>{{$trabajador['tipo_contrato']}}</td>
                </tr>
            </tbody>
        </table>

        <h3 style="color:#17365D">2. Resumen ejecutivo</h3>
        <p>{{$informe['data1']}}</p>

        <h3 style="color:#17365D">3. Porcentaje de cumplimiento general</h3>
        <p>{{$informe['data2']}}</p>
        <br>
        <br>
        <h3 style="color:#17365D">4. Grafico circular: Cumplimiento vs Brecha</h3>
        <br>
        <div style="width:80%;display:flex;border:0.1rem solid rgb(201, 201, 201);padding:1rem;">
            <img src="{{$grafico1}}" alt="" style="width:35rem;aspect-ratio:1;margin:auto;">
        </div>
        <br>
        <br>
        <h3 style="color:#17365D">5. Gráfico de barras: Cumplimiento por documento</h3>
        <br>
        <div style="width:80%;display:flex;border:0.1rem solid rgb(201, 201, 201);padding:1rem;">
            <img src="{{$grafico2}}" alt="" style="width:35rem;aspect-ratio:1;margin:auto;">
        </div>
        <br>
        <br>
        <h3 style="color:#17365D">6. Análisis de brechas y riesgos detectados</h3>
        <p>{{$informe['data3']}}</p>
        <br>
        <br>
        <h3 style="color:#17365D">7. Recomendaciones legales prioritarias</h3>
        <p>{{$informe['data4']}}</p>
        <br>
        <br>
        <h3 style="color:#17365D">8. Fundamento corporativo consolidado</h3>
        <p>{{$informe['data5']}}</p>
        <br>
        <br>
        <h3 style="color:#17365D">9. Llamado a la accion comercial</h3>
        <p>{{$informe['data6']}}</p>
        <br>
        <br>
        <h3 style="color:#17365D">10. Conclusión ejecutiva</h3>
        <p>{{$informe['data7']}}</p>
    </div>
</div>