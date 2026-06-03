<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check 360</title>
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <style type="text/css">
      body{
      width: 650px;
      font-family: work-Sans, sans-serif;
      background-color: #f6f7fb;
      display: block;
      }
      a{
      text-decoration: none;
      }
      span {
      font-size: 14px;
      }
      p {
        font-size: 13px;
        line-height: 1.7;
        letter-spacing: 0.7px;
        margin-top: 0;
      }
      .text-center{
      text-align: center
      }
      h6 {
      font-size: 16px;
      margin: 0 0 18px 0;
      }
    </style>
  </head>
  <body style="margin: 30px auto;">
    <table style="width: 100%;background-color:#f6f7fb;">
      <tbody>
        <tr>
          <td>
            <table style="background-color: #f6f7fb; width: 100%">
              <tbody>
                <tr>
                  <td>
                    <table style="width: 650px; margin: 0 auto; margin-bottom: 30px">
                      <tbody>
                        <tr>
                          <td><img style="width: 11rem;margin-top: 1rem;" src="{{ asset('assets/images/logo/logo_check360.png') }}" alt=""></td>
                          <td style="text-align: right; color:#999"><span>-</span></td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
            <table style="width: 650px; margin: 0 auto; background-color: #fff; border-radius: 8px">
              <tbody>
                <tr>
                  <td style="padding: 30px"> 
                    <h6 style="font-weight: 600">{{$data['titulo']}}</h6>
                    <p>Haz sido registrado como usuario <a href="{{$data['plataforma']}}" target="_blank">Check 360</a>
                    Puedes ingresar al sistema con los siguientes datos:
                    <br>
                    <br>
                    <table>
                        <tbody>
                            <tr>
                                <td><b>Usuario: </b></td><td>{{$data['correo_electronico']}}</td>
                            </tr>
                            <tr>
                                <td><b>Contraseña: </b></td><td>{{$data['pass']}}</td>
                            </tr>
                        </tbody>
                    </table><br><br>
                    <p style="margin-bottom: 0">
                      Saluda, <br><strong>Equipo de Check 360</strong></p>
                  </td>
                </tr>
              </tbody>
            </table>
            <table style="width: 650px; margin: 0 auto; margin-top: 30px">
              <tbody>       
                <tr style="text-align: center">
                  <td> 
                    <p style="color: #999; margin-bottom: 0">No responder a este correo, es un correo auto-generado</p>
                    
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>