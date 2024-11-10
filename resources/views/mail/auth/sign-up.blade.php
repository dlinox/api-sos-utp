<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Registro Realizado
    </title>
    <style>
        .password {
            background-color: #f9f9f9;
            border: 1px solid #e1e1e1;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 30px;

        }
    </style>
</head>

<body>

    <h1>¡Bienvenido a la familia!</h1>
    <p>¡Hola! Gracias por registrarte en nuestra app de seguridad y alertas entre vecinos.</p>
    <p>Use la siguiente contraseña para iniciar sesión:</p>
    <div class="password">
        <strong>{{ $password }}</strong>
    </div>
</body>

</html>