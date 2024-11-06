import { mensajeAlerta } from '../../../funciones_helper/notificaciones/mensajes.js';
import { crud } from '../../../funciones_helper/operaciones_crud/crud.js';
import { vaciar_errores, vaciar_formulario } from '../../../funciones_helper/vistas/formulario.js';

$(document).ready(function () {
    let tablaPlanificacion = $("#table_asistencia").DataTable({
        processing: true,
        responsive: true,
    });
    // listar_reuniones();
});




$('#ci_estudiante').keyup(function () {
    let ci_estudiante = $(this).val(); // Captura el valor actual del campo de entrada


    if (ci_estudiante.length < 6) {
        $('#nombre_apellido_res').text("....");
        $("#btnUser_asistencia").prop("disabled", true);
        return;
    }

    crud("admin/buscar_usuario", "GET", ci_estudiante, null, function (error, response) {

        console.log(response);
        // Verificamos que no haya un error o que todos los campos sean llenados
        if (response.tipo === "errores") {

            mensajeAlerta(response.mensaje, "errores");
            return;
        }
        if (response.tipo != "exito") {
            $('#nombre_apellido_res').text("NO ENCONTRADO....");
            $("#btnUser_asistencia").prop("disabled", true);

            return;
        }

        let nombre_completo = response.mensaje[0].nombres + " " + response.mensaje[0].paterno + " " + response.mensaje[0].materno;
        $('#nombre_apellido_res').text(nombre_completo);
        $("#btnUser_asistencia").prop("disabled", false);
        $("#id_usuarioEstudiante").val(response.mensaje[0].id);
    })
});

// CREAR REUNION

$('#nueva_asistencia').submit(function (e) {

    e.preventDefault();
    let datosFormulario = $('#nueva_asistencia').serialize();
    //vaciar_errores("formularioReunion_crear");
   
    crud("admin/nueva_asistencia", "POST", null, datosFormulario, function (error, response) {
        console.log(response);
        // Verificamos que no haya un error o que todos los campos sean llenados
        if (response.tipo == "errores") {

            mensajeAlerta(response.mensaje, "errores");
            return;
        }
        if (response.tipo != "exito") {
            mensajeAlerta(response.mensaje, response.tipo);
            return;
        }

        // si todo esta correcto muestra el mensaje de correcto
       
        mensajeAlerta(response.mensaje, response.tipo);
        
        $('#ModalAsistencia').modal('hide');


        setTimeout(() => {
            location.reload();
        }, 1500);

    })
})