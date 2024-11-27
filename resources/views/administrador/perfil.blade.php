@extends('principal')
@section('titulo', 'PERFIL')
@section('contenido')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title " style="display: inline-block">Cambiar Contraseña </h4>
                    <span class="text-success ms-3 "> La contraseña debe ser minimo 8 digitos y debe de contener (1 mayúscula, 1 número y 1 símbolo) </span>
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <form action="#" id="form_password" method="post">
                        @csrf
                        <div>
                            <p class="text-danger text-center" id="error_contraseña"></p>
                        </div>
                        <div class="form-group mb-3 row">
                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Contraseña
                                Actual</label>
                            <div class="col-lg-9 col-xl-8">
                                <div style="position: relative">
                                    <input class="form-control  vista_password" type="password" id="password_actual"
                                        name="password_actual" placeholder="Contraseña Actual">
                                    <a type="button" onclick="togglePassword()" id="btn_vista"
                                        style="position: absolute; top:0;  right: 0; color: #d2691e; margin-top:12px; margin-right: 5px">
                                        <i class="fas fa-eye-slash fs-18" id="icono_password"></i>
                                    </a>
                                </div>

                                <div id="_password_actual"></div>
                            </div>

                        </div>

                        <div class="form-group mb-3 row">
                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Nueva
                                Contraseña</label>
                            <div class="col-lg-9 col-xl-8 ">

                                <input class="form-control vista_password" type="password" id="password_nuevo"
                                    name="password_nuevo" placeholder="Ingrese la nueva Contraseña">
                            

                                <div id="_password_nuevo"></div>
                            </div>

                        </div>
                        <div class="form-group mb-3 row">
                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Confirmar
                                Contraseña</label>
                            <div class="col-lg-9 col-xl-8">
                                <input class="form-control  vista_password" type="password" id="password_confirmar"
                                    name="password_confirmar" placeholder="Repita la contraseña nueva">
                                <div id="_password_confirmar"></div>
                            </div>
                        </div>
                    </form>
                    <div class="form-group row">
                        <div class="col-lg-9 col-xl-8 offset-lg-3">
                            <button type="button" id="perfil_btn" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </div>
                </div><!--end card-body-->
            </div><!--end card-->
        </div> <!--end col-->
    </div><!--end row-->
@endsection
@section('scripts')
    <script>
        let btn_perfil = document.getElementById('perfil_btn');
        let form_password = document.getElementById('form_password');
        //capturamos los que vana mostrar errores
        let valores_errores = ['_password_actual', '_password_nuevo', '_password_confirmar'];

        // Expresión regular para la contraseña
        const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*_])[A-Za-z\d!@#$%^&*_]{8,}$/;

        btn_perfil.addEventListener('click', async () => {
            let datos = Object.fromEntries(new FormData(form_password).entries());
            validar_boton(true, "Verificando datos . . . . ", btn_perfil);
            // valor de la contraseña

            let contraeñaUsuario = document.getElementById('password_nuevo');

            if (passwordRegex.test(contraeñaUsuario.value) == false) {
                document.getElementById('error_contraseña').textContent =
                    '* La nueva contraseña debe ser mínimo de 8 dígitos y contener (1 mayúscula, 1 número y 1 símbolo)';

                validar_boton(false, 'Guardar Cambios', btn_perfil);
                return;
            }
            try {
                let respuesta = await fetch("{{ route('pwd_guardar') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(datos)
                });

                let data = await respuesta.json();
                vaciar_errores(valores_errores);

                if (data.tipo === 'errores') {
                    mostrarErrores(data.mensaje);
                    validar_boton(false, 'Guardar Cambios', btn_perfil);
                }
                if (data.tipo === 'error') {
                    alerta_top(data.tipo, data.mensaje);
                    validar_boton(false, 'Guardar Cambios', btn_perfil);
                }
                if (data.tipo === 'success') {
                    alerta_top(data.tipo, data.mensaje);
                    document.getElementById('error_contraseña').textContent = '';
                    validar_boton(true, 'Guardar Cambios', btn_perfil);
                    await cerrarSesion();
                }
            } catch (error) {
                console.log('Existe un error: ' + error);
                validar_boton(false, "Guardar Cambios", btn_perfil);
            }
        });

        async function cerrarSesion() {
            setTimeout(async function() {
                await cerrar_session_cam();
            }, 2000);
        }





        function togglePassword() {
            let elementos = document.querySelectorAll('input.vista_password');
            let iconPassword = document.getElementById("icono_password");
            let type;
            // Iterar sobre ellos y trabajar con los elementos directamente
            elementos.forEach(elemento => {
                // Cambiar el tipo de input primero
                // console.log(elemento.type);
                type = elemento.type === "password" ? "text" : "password";
                elemento.type = type;
            });




            // Ahora cambiar el icono según el nuevo tipo
            if (type === "text") {
                iconPassword.classList.remove("fas", "fa-eye-slash", "fs-18"); // Remover clases por separado
                iconPassword.classList.add("fas", "fa-eye", "fs-18"); // Agregar clases por separado
            } else {
                iconPassword.classList.remove("fas", "fa-eye", "fs-18"); // Remover clases por separado
                iconPassword.classList.add("fas", "fa-eye-slash", "fs-18"); // Agregar clases por separado
            }
        }
    </script>
@endsection
