import { mensajeAlerta } from '../../../funciones_helper/notificaciones/mensajes.js';
import { crud } from '../../../funciones_helper/operaciones_crud/crud.js';
import { vaciar_errores, vaciar_formulario } from '../../../funciones_helper/vistas/formulario.js';

$(document).ready(function () {
    let tablaPlanificacion = $("#table_planificacion").DataTable({
        processing: true,
        responsive: true,
    });
    listar_reuniones();
});


function listar_reuniones() {
    crud("admin/listar_reuniones", "GET", null, null, function (error, respuesta) {
        console.log(respuesta);
        if (error != null) {
            mensajeAlerta(error, "error");
            return; // Agregar un return para evitar ejecutar el resto si hay un error
        }

        let reuniones = respuesta.reuniones;
        let permissions = respuesta.permissions;

        $('#table_planificacion').DataTable({
            responsive: true,
            data: reuniones,
            columns: [
                {
                    data: null,
                    className: 'table-td',
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // Usar meta.row para obtener el índice de la fila
                    }
                },
                {
                    data: 'titulo',
                    className: 'table-td ',
                    render: function (data) {
                        return `                            
                            ${data}
                        `;
                    }
                },
                {
                    data: 'descripcion',
                    className: 'table-td',
                    render: function (data) {
                        return data;
                    }
                },
                {
                    data: 'estado',
                    className: 'table-td',
                    render: function (data) {
                        return data == "activo"
                            ? `<span class="badge bg-success fs-5">${data}</span>`
                            : `<span class="badge bg-danger fs-5">${data}</span>`
                    }
                },
                {
                    data: null, // Configurado en `null` para personalizar el renderizado
                    className: 'table-td',
                    render: function (data, type, row) {
                        const fecha = row.fecha || '';
                        const entrada = row.hora_entrada || ''; // Accede al valor de `entrada`
                        const salida = row.hora_salida || '';   // Accede al valor de `salida`

                        return `${fecha} - <b class="">${entrada} hasta ${salida}</b>`;
                    }
                },
                {
                    data: 'anticipo',
                    className: 'table-td',
                    render: function (data) {
                        return data;
                    }
                },
                {
                    data: 'tolerancia',
                    className: 'table-td',
                    render: function (data) {
                        return data;
                    }
                },
                {
                    data: null,
                    className: 'table-td text-end',
                    render: function (data, type, row) {
                        if (row.estado == "activo") {
                            return ` <div>

                             ${permissions['eliminar'] ?
                                    `
                                      <a class="btn btn-sm btn-outline-danger px-2 d-inline-flex align-items-center terminar_reunion" data-id="${row.id}">
                                <i class="fas fa-window-close fs-16"></i>
                            </a>
                                `
                                    : ``
                                }
                          
                                 ${permissions['verAsistencia'] ?
                                    ` <a href="lista_asistencia/${row.id}" class="btn btn-sm btn-outline-primary px-2 d-inline-flex align-items-center " data-id="${row.id}">
                                <i class="fas fa-clipboard-check fs-16"></i>
                            </a>`
                                    : ``
                                }
                              ${permissions['generarReporte'] ?
                                    ` <a  href="reporte_asistencia/${row.id}" class="btn btn-sm btn-outline-info px-2 d-inline-flex align-items-center" target="_blank">
                                <i class="fas fa-id-cardfas fa-clipboard-list fs-16"></i>
                            </a> `
                                    : ``
                                }
                             
                            </div>`;
                        } else {
                            return `
                              <div>
                                ${permissions['verAsistencia'] ?
                                    ` <a href="lista_asistencia/${row.id}" class="btn btn-sm btn-outline-primary px-2 d-inline-flex align-items-center " data-id="${row.id}">
                                <i class="fas fa-clipboard-check fs-16"></i>
                            </a>`
                                    : ``
                                }
                              ${permissions['generarReporte'] ?
                                    ` <a  href="reporte_asistencia/${row.id}" class="btn btn-sm btn-outline-info px-2 d-inline-flex align-items-center" target="_blank">
                                <i class="fas fa-id-cardfas fa-clipboard-list fs-16"></i>
                            </a> `
                                    : ``
                                }
                           
                           </div> `;
                        }

                    }
                },
            ],
            destroy: true
        });
    });
}

// CREAR REUNION

$('#formularioReunion_crear').submit(function (e) {

    e.preventDefault();
    let datosFormulario = $('#formularioReunion_crear').serialize();
    $("#btnReunion_nueva").prop("disabled", true);
    vaciar_errores("formularioReunion_crear");
    crud("admin/reuniones", "POST", null, datosFormulario, function (error, response) {
        $("#btnReunion_nueva").prop("disabled", false);
        // console.log(response);
        // Verificamos que no haya un error o que todos los campos sean llenados
        if (response.tipo === "errores") {

            mensajeAlerta(response.mensaje, "errores");
            return;
        }
        if (response.tipo != "exito") {
            mensajeAlerta(response.mensaje, response.tipo);
            return;
        }

        // si todo esta correcto muestra el mensaje de correcto
        vaciar_formulario("formularioReunion_crear");
        mensajeAlerta(response.mensaje, response.tipo);
        listar_reuniones();
        $('#ModalReunion').modal('hide');

    })
})

// TERMINAR REUNION

$('#table_planificacion').on('click', '.terminar_reunion', function (e) {

    e.preventDefault(); // Evitar que el enlace recargue la página
    let lector_id = $(this).data('id'); // Obtener el id del alumno desde el data-id
    Swal.fire({
        title: "NOTA!",
        text: "¿Está seguro de terminar la reunion?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, Estoy seguro",
        cancelButtonText: "Cancelar",
    }).then(async function (result) {
        if (result.isConfirmed) {

            crud("admin/reuniones", "PATCH", lector_id, null, function (error, response) {

                console.log(response);
                // Verificamos que no haya un error o que todos los campos sean llenados
                if (response.tipo === "errores") {
                    mensajeAlerta(response.mensaje, "errores");
                    return;
                }
                if (response.tipo != "exito") {
                    mensajeAlerta(response.mensaje, response.tipo);
                    return;
                }
                // si todo esta correcto muestra el mensaje de correcto
                mensajeAlerta(response.mensaje, response.tipo);
                listar_reuniones();

            })
        } else {
            alerta_top('error', 'Se canceló la desvinculacion');
        }
    })
});


$('#table_planificacion').on('click', '.listar_asistencia', function (e) {

    e.preventDefault(); // Evitar que el enlace recargue la página
    let lector_id = $(this).data('id'); // Obtener el id del alumno desde el data-id

    crud("admin/reuniones", "GET", lector_id, null, function (error, response) {



    })


});