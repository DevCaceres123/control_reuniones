import { mensajeAlerta } from '../../../funciones_helper/notificaciones/mensajes.js';
import { crud } from '../../../funciones_helper/operaciones_crud/crud.js';

$('#ci_estudiante').keyup(function () {
    let ci_estudiante = $(this).val(); // Captura el valor actual del campo de entrada


    if (ci_estudiante.length < 6) {
        $('#nombre_apellido_res').text("....");
        $("#buton_ReporteAsistencia").prop("disabled", true);
        return;
    }

    crud("admin/asistencias", "GET", ci_estudiante, null, function (error, response) {

        console.log(response);
        // Verificamos que no haya un error o que todos los campos sean llenados
        if (response.tipo === "errores") {

            mensajeAlerta(response.mensaje, "errores");
            return;
        }
        if (response.tipo != "exito") {
            $('#nombre_apellido_res').text("NO ENCONTRADO....");
            $("#buton_ReporteAsistencia").prop("disabled", true);

            return;
        }

        $("#buton_ReporteAsistencia").prop("disabled", false);
        let nombre_completo = response.mensaje[0].nombres + " " + response.mensaje[0].paterno + " " + response.mensaje[0].materno;
        $('#nombre_apellido_res').text(nombre_completo);
       
    })
});
