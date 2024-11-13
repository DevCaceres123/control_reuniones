<!DOCTYPE html>
<html lang="en" data-startbar="dark" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <title>LOGIN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('admin_template/images/logo_cruz.png') }}">

    <!-- App css -->
    <link href="{{ asset('admin_template/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_template/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_template/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    {{-- login csss --}}

    <link href="{{ asset('assets/login.css') }}" rel="stylesheet" type="text/css" />

</head>

<!-- Top Bar Start -->

<body>


    <div class="contenedor_fondo">
        <div class="contenedor_fondo-img slider">

        </div>
        <div class="contenedor_login">


            <div class="contenedor_login-logo">
                <img src="{{ asset('assets/logo_cruz.png') }}" alt="">
            </div>

            <div class="contenedor_login-formulario-carusel">

                <div class="contenedor-carusel">
                    <div class="contenedor-carusel-titulo-descripcion">

                        <h3> <u>Iglesia Luz de Vida</u> </h3>

                        <p>Predicar el evangelio de Jesucristo a las personas de la comunidad moldeando vidas y formando
                            lideres comprometidos para la gloria de Dios. (Mateo 9:35)</p>


                    </div>
                    <div class="slider-wrap">
                        <div class="single-slide slider" id="slide-1"></div>
                        <div class="single-slide slider" id="slide-2"></div>
                        <div class="single-slide slider" id="slide-3"></div>
                        <div class="single-slide slider" id="slide-4"></div>
                        <div class="single-slide slider" id="slide-5"></div>

                    </div>
                </div>

                <div class="contenedor-formulario">

                    <form class="formulario-login" id="formulario_login" autocomplete="off">
                        @csrf
                        <h2><strong>Iniciar Session</strong></h2>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="usuario" name="usuario">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password">
                        </div>
                        <button type="button" class="boton btn_formulario" id="btn_ingresar_usuario">Ingresar</button>
                        <div class="text-center py-2 text-light" id="mensaje_error"></div>
                    </form>
                </div>


            </div>
        </div>
    </div>

</body>

</html>

<script>
    // Seleccionar elementos del DOM
    let loginBtn = document.getElementById('btn_ingresar_usuario');
    let formularioLogin = document.getElementById('formulario_login');
    let mensajeError = document.getElementById('mensaje_error');

    // Función para crear y mostrar alertas
    function mostrarAlerta(tipo, mensaje) {
        let iconoClase = tipo === 'success' ? 'fa-check' : 'fa-xmark';
        let color = tipo === 'success' ? 'success' : 'danger';
        mensajeError.innerHTML = `
        <div class="alert alert-${color} shadow-sm border-theme-white-2" role="alert">
            <div class="d-inline-flex justify-content-center align-items-center thumb-xs bg-${color} rounded-circle mx-auto me-1 ">
                <i class="fas ${iconoClase} align-self-center mb-0 text-light"></i>
            </div>
            <strong class="text-light">${mensaje}</strong>
        </div>
        `;
        // Configurar el temporizador para ocultar la alerta después de 5 segundos
        setTimeout(() => {
            mensajeError.innerHTML = '';
        }, 4000);
    }

    // Función para validar el botón
    function validarBoton(estaDeshabilitado, mensaje) {
        loginBtn.textContent = mensaje;
        loginBtn.disabled = estaDeshabilitado;
    }

    // Manejar el envío del formulario
    loginBtn.addEventListener('click', async (e) => {
        let datos = Object.fromEntries(new FormData(formularioLogin).entries());
        validarBoton(true, "Verificando datos...");
        try {
            let respuesta = await fetch("{{ route('log_ingresar') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datos)
            });
            if (!respuesta.ok) {
                throw new Error(`HTTP error! status: ${respuesta.status}`);
            }
            let data = await respuesta.json();
            mostrarAlerta(data.tipo, data.mensaje);
            if (data.tipo === 'success') {
                validarBoton(true, 'Datos correctos...');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                validarBoton(false, 'INGRESAR');
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarAlerta('error', 'Ocurrió un error al procesar la solicitud');
            validarBoton(false, 'INGRESAR');
        }
    });
</script>
