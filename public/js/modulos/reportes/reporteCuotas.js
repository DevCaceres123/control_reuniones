import { mensajeAlerta } from '../../../funciones_helper/notificaciones/mensajes.js';
import { crud } from '../../../funciones_helper/operaciones_crud/crud.js';

$('#ci_estudiante').keyup(function () {
    let ci_estudiante = $(this).val(); // Captura el valor actual del campo de entrada


    if (ci_estudiante.length < 6) {
        $('#nombre_apellido_res').text("....");
        $("#buton_ReporteCuotas").prop("disabled", true);
        return;
    }

    crud("admin/cuotas", "GET", ci_estudiante, null, function (error, response) {

        console.log(response);
        // Verificamos que no haya un error o que todos los campos sean llenados
        if (response.tipo === "errores") {

            mensajeAlerta(response.mensaje, "errores");
            return;
        }
        if (response.tipo != "exito") {
            $('#nombre_apellido_res').text("NO ENCONTRADO....");
            $("#buton_ReporteCuotas").prop("disabled", true);

            return;
        }

        $("#buton_ReporteCuotas").prop("disabled", false);
        let nombre_completo = response.mensaje[0].nombres + " " + response.mensaje[0].paterno + " " + response.mensaje[0].materno;
        $('#nombre_apellido_res').text(nombre_completo);

    })
});

$('#buton_ReporteCuotasFinal').click(function (e) {
   
   
    $("#buton_ReporteCuotasFinal").prop("disabled", true);
   temporizadorMensaje();
    // Llamada a la función crud para hacer la solicitud
    crud("admin/cuotas_reporte_anual", "GET", null, null, function (error, response) {
        // Verificar si hay un error
        $("#buton_ReporteCuotasFinal").prop("disabled", false);
        console.log(response);
        if (error) {
            console.error('Error en la solicitud:', error);
            return;
        }

        if (response instanceof Blob) {
            const pdfUrl = URL.createObjectURL(response);

            // Abrir el PDF en una nueva pestaña
            const nuevaPestana = window.open(pdfUrl, '_blank');
            if (nuevaPestana) {
                nuevaPestana.focus();
            } else {
                console.error('No se pudo abrir una nueva pestaña.');
            }
        } else {
            // Aquí manejas otras respuestas JSON si las hay
            console.log('Respuesta JSON:', response);
        }
    });
});




function temporizadorMensaje() {
    let timerInterval;
    Swal.fire({
        title: "Se esta generando el Reporte espere Por Favor!!! ",
        html: "Generando reporte..!!<b></b> tiempo.",
        timer: 2000,
        timerProgressBar: true,
        position: "top-end",
        didOpen: () => {
            Swal.showLoading();
            const timer = Swal.getPopup().querySelector("b");
            timerInterval = setInterval(() => {
                timer.textContent = `${Swal.getTimerLeft()}`;
            }, 100);
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
    }).then((result) => {
        // Tiempo en milisegundos (ejemplo: 5 segundos)
       
       
    });
}