import { mensajeAlerta } from '../../../funciones_helper/notificaciones/mensajes.js';
import { crud } from '../../../funciones_helper/operaciones_crud/crud.js';
import { vaciar_errores, vaciar_formulario } from '../../../funciones_helper/vistas/formulario.js';

$('#formularioLectoresCrear').submit(function (e) {
    e.preventDefault();
    let datosFormulario = $('#formularioLectoresCrear').serialize();

    $("#btnLector_nuevo").prop("disabled", true);
    vaciar_errores("formularioLectoresCrear");
    crud("admin/lectores", "POST", null, datosFormulario, function (error, response) {

        $("#btnLector_nuevo").prop("disabled", false);
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
        mensajeAlerta(response.mensaje, response.tipo);
        vaciar_formulario("formularioLectoresCrear");
        $('#ModalLector').modal('hide');

        setTimeout(() => {
            location.reload();
        }, 1500);

    })
});

// DESVINCULAR LECTOR
$('.terminar_uso').click(function (e) {
    e.preventDefault(); // Evitar que el enlace recargue la página
    let lector_id = $(this).data('id'); // Obtener el id del alumno desde el data-id
    Swal.fire({
        title: "NOTA!",
        text: "¿Está seguro de desvincularte del lector?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, Desvincular",
        cancelButtonText: "Cancelar",
    }).then(async function (result) {
        if (result.isConfirmed) {

            crud("admin/lectores/terminar_uso", "PUT", lector_id, null, function (error, response) {

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
                setTimeout(() => {
                    location.reload();
                }, 1500);

            })
        } else {
            alerta_top('error', 'Se canceló la desvinculacion');
        }
    })
});


// ELIMINAR LECTOR
$('.eliminar_lector').click(function (e) {
    e.preventDefault(); // Evitar que el enlace recargue la página
    let lector_id = $(this).data('id'); // Obtener el id del alumno desde el data-id
    Swal.fire({
        title: "NOTA!",
        text: "¿Está seguro de eliminar del lector?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, Eliminar",
        cancelButtonText: "Cancelar",
    }).then(async function (result) {
        if (result.isConfirmed) {

            crud("admin/lectores", "DELETE", lector_id, null, function (error, response) {

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
                setTimeout(() => {
                    location.reload();
                }, 1500);

            })
        } else {
            alerta_top('error', 'Se canceló la eliminacion');
        }
    })
});

// REGISTRAR EL USO DEL BIOMETRICO
$('.uso_lector').click(function (e) {

    e.preventDefault();

    let lector_id = $(this).data('id');
    let datos =
    {
        uso: document.querySelector('input[name="opcionLector"]:checked').value,
    }
    crud("admin/lectores", "PUT", lector_id, datos, function (error, response) {

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

        setTimeout(() => {
            location.reload();
        }, 1500);

    })
});


$('.editar_lector').click(function (e) {
    e.preventDefault(); // Evitar que el enlace recargue la página
    let lector_id = $(this).data('id'); // Obtener el id del alumno desde el data-id
    $('#ModalLector_editar').modal('show');

    crud("admin/lectores", "GET", lector_id + "/edit", null, function (error, response) {

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

        $('#lector_id_edit').val(response.mensaje.id);
        $('#nombre_editar').val(response.mensaje.nombre);
        $('#descripcion_editar').val(response.mensaje.descripcion);
    })
});


$('#formularioLectoresCrearEditar').submit(function (e) {
    e.preventDefault();
    $("#btnLector_editar").prop("disabled", true);
    let id_lector = $('#lector_id_edit').val();
    let datos =
    {
        nombre: $('#nombre_editar').val(),
        descripcion: $('#descripcion_editar').val(),
    }

    crud("admin/lectores/actualizar_lector", "PUT", id_lector, datos, function (error, response) {
        $("#btnLector_editar").prop("disabled", false);
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
        mensajeAlerta(response.mensaje, response.tipo);

        $('#ModalLector_editar').modal('hide');
        setTimeout(() => {
            location.reload();
        }, 1500);

    })

});
