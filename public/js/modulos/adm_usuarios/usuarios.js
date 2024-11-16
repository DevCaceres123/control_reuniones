import { mensajeAlerta } from '../../../funciones_helper/notificaciones/mensajes.js';
import { crud } from '../../../funciones_helper/operaciones_crud/crud.js';
import { vaciar_errores, vaciar_formulario } from '../../../funciones_helper/vistas/formulario.js';

let tablaUser;

let ciUsuario = document.getElementById("ci");
let nombreUsuario = document.getElementById("nombres");
let complemento = document.getElementById("complemento");


$(document).ready(function () {
    tablaUser = $("#table_user").DataTable({
        processing: true,
        responsive: true,
    });
    // listar_usuarios();
});



function listar_usuarios() {
    crud("admin/listarUsuarios", "GET", null, null, function (error, respuesta) {
        if (error != null) {
            mensajeAlerta(error, "error");
            return; // Agregar un return para evitar ejecutar el resto si hay un error
        }

        let usuarios = respuesta.usuarios;
        let permissions = respuesta.permissions;

        $('#table_user').DataTable({
            responsive: true,
            data: usuarios,
            columns: [
                {
                    data: null,
                    className: 'table-td',
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // Usar meta.row para obtener el índice de la fila
                    }
                },
                {
                    data: 'nombres',
                    className: 'table-td text-uppercase',
                    render: function (data) {
                        return `
                            <img src="/admin_template/images/logos/lang-logo/slack.png"
                                 alt="" class="rounded-circle thumb-md me-1 d-inline">
                            ${data}
                        `;
                    }
                },
                {
                    data: 'paterno',
                    className: 'table-td',
                    render: function (data) {
                        return data;
                    }
                },
                {
                    data: 'materno',
                    className: 'table-td',
                    render: function (data) {
                        return data;
                    }
                },
                {
                    data: null,
                    className: 'table-td',
                    render: function (data, type, row) {

                        return `<b class="text-muted">${row.ci}</b>`;

                    }
                },
                {
                    data: 'roles',
                    render: function (data) {
                        if (data.length != 0) {
                            return data.map(role =>
                                `<span class="badge bg-success fs-5">${role.name}</span>`
                            ).join(' ');
                        } else {
                            return `<span class="badge bg-success fs-5">Sin roles asignados</span>`;
                        }
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        let estadoChecked = row.estado === "activo" ? 'checked' : '';

                        // Aquí verificamos el permiso de desactivar
                        let desactivarContent = permissions['desactivar'] ? `
                            <a class="cambiar_estado_usuario" data-id="${row.id},${row.estado}">
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" 
                                           ${estadoChecked} style="transform: scale(2.0);">
                                </div>
                            </a>` : `
                           <p>No permitido...<p/>
                        `;

                        return `
                            <div data-class="">
                                ${desactivarContent}
                            </div>`;
                    }
                },

                {
                    data: 'cod_targeta',
                    render: function (data) {
                        return data == null
                            ? `<span class="badge bg-danger fs-5">Sin asignar</span>`
                            : `<span class="badge bg-success fs-5">${data}</span>`;
                    }
                },
                {
                    data: null,
                    className: 'table-td',
                    render: function (data, type, row) {
                        return `
                            <div class="text-end">
                                <td>
                                    <div class="d-flex justify-content-between">
                                        ${permissions['reset'] ? `
                                            <a class="btn btn-sm btn-outline-info px-2 d-inline-flex align-items-center resetear_usuario" data-id="${row.id}">
                                                <i class="fas fa-redo fs-16"></i>
                                            </a>
                                        ` : ''}
                
                                        ${permissions['editarRol'] ? `
                                            <a class="btn btn-sm btn-outline-primary px-2 d-inline-flex align-items-center cambiar_rol" data-id="${row.id}">
                                                <i class="far fa-edit fs-16"></i>
                                            </a>
                                        ` : ''}
                
                                        ${permissions['editarTargeta'] && row.roles[0].name === "estudiante" ? `
                                            <a class="btn btn-sm btn-outline-warning px-2 d-inline-flex align-items-center asignar_targeta" data-id="${row.id}">
                                                <i class="fas fa-id-card fs-16"></i>
                                            </a>
                                        ` : ''}
                                    </div>   
                                </td>
                            </div>
                        `;
                    }
                }

            ],
            destroy: true
        });
    });
}


// REGISTRAR  USUARIO
$('#formularioUsuario').submit(function (e) {

    e.preventDefault();
    $("#btnUser_nuevo").prop("disabled", true);
    generarUsuario();
    generarContraseña();
    vaciar_errores("formularioUsuario");
    let datosFormulario = $('#formularioUsuario').serialize();

    crud("admin/usuarios", "POST", null, datosFormulario, function (error, response) {
        1
        //console.log(response);
        // if (error != null) {
        //     mensajeAlerta(error, "error");
        //     return;
        // }

        console.log(response);
        if (response.tipo === "errores") {

            mensajeAlerta(response.mensaje, "errores");
            return;
        }
        if (response.tipo != "exito") {
            mensajeAlerta(response.mensaje, response.tipo);
            return;
        }
        listar_usuarios();
        mensajeAlerta(response.mensaje, response.tipo);
        vaciar_formulario("formularioUsuario");
        $('#ModalUsuario').modal('hide');


    });

});


// OBTENER NUMERO DE TARGETA EN EL MODAL USUARIO
$('#obtnerTargeta').click(function (e) {
    e.preventDefault();
    let id_alumno = 0;
    crud("admin/usuarios", "GET", id_alumno, null, function (error, response) {
        // console.log(response);
        if (error != null) {
            mensajeAlerta(error, "error");
            return;
        }

        if (response.tipo != "exito") {
            $('#cod_targeta').attr('placeholder', response.mensaje);
            return;
        }

        $('#cod_targeta').val(response.mensaje.cod_targeta);

    });

})

// Agregar el evento de clic después de que la tabla haya sido creada
$('#table_user').on('click', '.desactivar_usuario', function (e) {
    e.preventDefault(); // Evitar que el enlace recargue la página

    Swal.fire({
        title: '¿Eliminar Registro?',
        text: "Estas seguro que quiere eliminar el registro!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si,estoy seguro!'
    }).then((result) => {
        if (result.isConfirmed) {
            let id_alumno = $(this).data('id'); // Obtener el id del alumno desde el data-id
            // console.log(id_alumno);
            crud("eliminarAlumno", "DELETE", id_alumno, null, function (error, response) {

                if (error != null) {
                    mensajeAlerta(error, "error");
                    return;
                }
                if (response.tipo != "exito") {
                    mensajeAlerta(response.mensaje, response.tipo);
                    return;
                }

                listar_usuarios();
                mensajeAlerta(response.mensaje, response.tipo);

            });
        }
    })
});


// CAMBIAR ROL
$('#table_user').on('click', '.cambiar_rol', function (e) {
    e.preventDefault(); // Evitar que el enlace recargue la página
    $('#ModalRol').modal('show');
    let id_user = $(this).data('id');

    $('#user_id_edit').val(id_user);

});

// GUARDAR EL NUEVO ROL DE USUARIO

$('#formEditarRol').submit(function (e) {
    e.preventDefault(); // Evitar que el enlace recargue la página
    let id_user = $('#user_id_edit').val();
    $("#btn_editRol").prop("disabled", true);
    let datosFormulario = {
        'user_id': $('#user_id_edit').val(),
        'rol_id': $('#role_edit').val(),
    };
    crud("admin/editar_rol", "PUT", id_user, datosFormulario, function (error, response) {
        $("#btn_editRol").prop("disabled", false);
        if (error != null) {
            mensajeAlerta(error, "error");
            return;
        }
        if (response.tipo != "exito") {
            mensajeAlerta(response.mensaje, response.tipo);
            return;
        }
        mensajeAlerta(response.mensaje, response.tipo);


        listar_usuarios();
        vaciar_formulario("formEditarRol");
        $('#ModalRol').modal('hide');

    });

});


// asignar targeta
$('#table_user').on('click', '.asignar_targeta', function (e) {

    e.preventDefault(); // Evitar que el enlace recargue la página
    $('#ModalTargeta').modal('show');
    let id_alumno = $(this).data('id'); // Obtener el id del alumno desde el data-id

    crud("admin/usuarios", "GET", id_alumno, null, function (error, response) {


        if (error != null) {
            mensajeAlerta(error, "error");
            return;
        }
        if (response.tipo != "exito") {
            mensajeAlerta(response.mensaje, response.tipo);
            return;
        }


        $('#id_usuario_targeta').val(response.mensaje.user_id);
        $('#codigo_targeta').val(response.mensaje.cod_targeta);
        //listar_usuarios();


    });

});


// OBTENER DATOS PARA  RESETEAR USUARIO
$('#table_user').on('click', '.resetear_usuario', function (e) {
    e.preventDefault(); // Evitar que el enlace recargue la página
    $('#ModalResetearUsuario').modal('show');
    let id_usuario = $(this).data('id'); // Obtener el id del alumno desde el data-id
    crud("admin/usuarios", "GET", id_usuario + "/edit", null, function (error, response) {

        // console.log(response);
        // if (error != null) {
        //     mensajeAlerta(error, "error");
        //     return;
        // }
        if (response.tipo != "exito") {
            mensajeAlerta(response.mensaje, response.tipo);
            return;
        }

        let nombre_completo = (response.mensaje.nombres + " " + response.mensaje.paterno + " " + response.mensaje.materno);
        // console.log(nombre_completo);
        $('#nombre_apellido_res').text(nombre_completo);
        $('#usuarioReset').val(response.mensaje.ci);
        $('#passwordReset').val(response.mensaje.ci + "_" + response.mensaje.nombres);
        $('#id_usuarioReset').val(response.mensaje.id);

    });

});


// RESETEAR USUARIO

$('#formResetear_usuario').submit(function (e) {
    e.preventDefault(); // Evitar que el enlace recargue la página
    let id_usuario = $('#id_usuarioReset').val();
    $("#btnUser_reset").prop("disabled", true);

    crud("admin/resetar_usuario", "PUT", id_usuario, null, function (error, response) {
        $("#btnUser_reset").prop("disabled", false);
        // console.log(response);
        // if (error != null) {
        //     mensajeAlerta(error, "error");
        //     return;
        // }
        if (response.tipo != "exito") {
            mensajeAlerta(response.mensaje, response.tipo);
            return;
        }

        $('#ModalResetearUsuario').modal('hide');
        mensajeAlerta(response.mensaje, response.tipo);
        vaciar_formulario("formResetear_usuario");
    });


});


// registrar codigo de targeta
$('#registrtarCodigoTargeta').submit(function (e) {
    e.preventDefault();

    let datosFormulario = $('#registrtarCodigoTargeta').serialize();

    $("#btn_targeta").prop("disabled", true);
    // console.log(datosFormulario);
    vaciar_formulario('registrtarCodigoTargeta');

    crud("admin/asignar_targeta", "POST", null, datosFormulario, function (error, response) {
        $("#btn_targeta").prop("disabled", false);
        // console.log(response);
        if (error != null) {
            mensajeAlerta(error, "error");
            return;
        }
        if (response.tipo != "exito") {
            mensajeAlerta(response.mensaje, response.tipo);
            return;
        }

        listar_usuarios();
        mensajeAlerta(response.mensaje, response.tipo);
        $('#ModalTargeta').modal('hide');
        vaciar_formulario('registrtarCodigoTargeta');

    });
})


// CAMBIAR ESTADO USAURIO


$('#table_user').on('click', '.cambiar_estado_usuario', function (e) {
    e.preventDefault(); // Evitar que el enlace recargue la página


    // Obtener el valor de data-id
    var dataId = $(this).data('id'); // "1,activo"

    // Separar el id y el estado
    var values = dataId.split(','); // ["1", "activo"]

    let datos =
    {
        id_usaurio: values[0],
        estado: values[1]
    }

    crud("admin/usuarios", "PUT", values[0], datos, function (error, response) {
        if (response.tipo === "errores") {

            mensajeAlerta(response.mensaje.estado[0], "error");
            return;
        }
        if (response.tipo != "exito") {
            mensajeAlerta(response.mensaje, response.tipo);
            return;
        }

        mensajeAlerta(response.mensaje, response.tipo);

        listar_usuarios();





    });

});

$('#alumno_form_nuevo').submit(function (e) {
    e.preventDefault();
    const datosFormulario = $('#alumno_form_nuevo').serialize();
    console.log(datosFormulario);
    crud("nuevoAlumno", "POST", null, datosFormulario, function (error, respuesta) {

    });
});



// Esta funcion es para generar el nombre usaurio
function generarUsuario() {

    console.log(complemento.value);

    if (complemento.value != "") {
        $('#usuario').val(ciUsuario.value.replace(/\s/g, "") + "-" + complemento.value.replace(/\s/g, ""));
    } else {
        $('#usuario').val(ciUsuario.value.replace(/\s/g, ""));
    }



}

function generarContraseña() {
    $('#password').val(ciUsuario.value.replace(/\s/g, "") + "_" + nombreUsuario.value.replace(/\s/g, ""));
}



