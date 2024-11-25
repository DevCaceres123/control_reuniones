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



$('#buton_ReporteAsistenciaFinal').click(function (e) {
   
    Swal.fire({
        title: "¿Quieres generar el reporte final?",
        text: "Se mostrara la asistencia de todos los estudiantes!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, generar reporte!"
      }).then((result) => {
        if (result.isConfirmed) {
            $("#reporte_asistencia_final").prop("disabled", true);
  
            // Llamada a la función crud para hacer la solicitud
            window.open('reporte_asistencia_final', '_blank');
            setTimeout(() => {
                $("#buton_ReporteAsistenciaFinal").prop("disabled", false);
            }, 2500);
        }
      });
   

});
