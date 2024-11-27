<!-- Top Bar Start -->
<div class="topbar d-print-none">
    <div class="container">
        <nav class="topbar-custom d-flex justify-content-between" id="topbar-custom">


            <ul class="topbar-item list-unstyled d-inline-flex align-items-center ">
                <li>
                    <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu">
                        <i class="iconoir-menu-scale"></i>
                    </button>
                </li>

                <li class="mx-3 welcome-text d-flex justify-content-between align-items-center">
                    @role('administrador')
                        <h3 class="mb-0 fw-bold text-center text-uppercase text-light p-2 rounded bg-success me-1">Iglesia
                            presbiteriana luz de vida
                        </h3>
                    @endrole
                    <img src="{{ asset('admin_template/images/logo_mundo.png') }}" alt="" alt="logo-small"
                        class="logo-sm rounded-pill ms-3" width="60" height="60">
                    <!-- <h6 class="mb-0 fw-normal text-muted text-truncate fs-14">Here's your overview this week.</h6> -->
                </li>
                <li class="mx-5 welcome-text ">
                    <h5 class="mb-0 fw-bold text-truncate text-capitalize text-center mt-1">Bienvenido
                        {{ Auth::user()->nombres }} !</h5>
                    <!-- <h6 class="mb-0 fw-normal text-muted text-truncate fs-14">Here's your overview this week.</h6> -->
                </li>
            </ul>
            <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                @role('administrador')
                    <li class="topbar-item">
                        <a class="nav-link nav-icon" href="" id="btn-error_lector">
                            <i class="fas fa-desktop menu-icon text-warning"></i>
                        </a>
                    </li>
                @endrole

                <li class="topbar-item">
                    <a class="nav-link nav-icon" href="javascript:void(0);" id="light-dark-mode">
                        <i class="icofont-moon dark-mode"></i>
                        <i class="icofont-sun light-mode"></i>
                    </a>
                </li>




                <li class="dropdown topbar-item">
                    <a class="nav-link dropdown-toggle arrow-none nav-icon " data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="fas fa-user-cog text-info"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end py-0">
                        <div class="d-flex align-items-center dropdown-item py-2 bg-secondary-subtle">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('admin_template/images/logo_cruz.png') }}" alt=""
                                    class="thumb-md rounded-circle">
                            </div>
                            <div class="flex-grow-1 ms-2 text-truncate align-self-center">
                                <h6 class="my-0 fw-medium text-dark fs-13">Admin</h6>
                                <small class="text-muted mb-0">Rol</small>
                            </div><!--end media-body-->
                        </div>
                        <div class="dropdown-divider mt-0"></div>
                        <small class="text-muted px-2 pb-1 d-block">Cuenta</small>
                        <a class="dropdown-item" href="{{ route('perfil') }}">
                            <i class="las la-user fs-18 me-1 align-text-bottom"></i>
                            Perfil
                        </a>

                        <div class="dropdown-divider mb-0"></div>
                        <a class="dropdown-item text-danger" href="javascript:void(0)" id="btn-cerrar-session"><i
                                class="las la-power-off fs-18 me-1 align-text-bottom"></i> Salir</a>
                    </div>
                    <form id="formulario_salir" method="POST">@csrf</form>
                </li>
            </ul><!--end topbar-nav-->
        </nav>
        <!-- end navbar-->
    </div>
</div>
<!-- Top Bar End -->

<div class="modal fade" id="moda_lector_error" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-center modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title " id="exampleModalLabel"><span
                        class="badge badge-outline-primary rounded">ERRORES DE LECTOR</span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <p class="text-bold p-1  fs-5  text-center">
                    Error: <strong id="error_lector_dep"></strong>
                </p>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger rounded btn-sm" data-bs-dismiss="modal"> <i
                            class="ri-close-line me-1 align-middle"></i> Cerrar</button>

                </div>



            </div>
        </div>
    </div>
</div>


<script>
    document.getElementById('btn-error_lector').addEventListener('click', async function(e) {
        e.preventDefault();
        // Mostrar el modal utilizando Bootstrap
        const modal = new bootstrap.Modal(document.getElementById('moda_lector_error'));
        modal.show();
        try {
            // Hacer una petición asíncrona
            const response = await fetch('errores_lector', {
                method: 'GET', // O POST según tu necesidad
                headers: {
                    'Content-Type': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error('Error en la petición al servidor');
            }

            const data = await response.json(); // Asume que la respuesta es JSON

            document.getElementById('error_lector_dep').textContent = data;


        } catch (error) {
            console.error('Ocurrió un error:', error);
        }
    });
</script>
