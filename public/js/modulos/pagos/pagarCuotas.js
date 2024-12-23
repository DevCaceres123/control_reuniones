import { mensajeAlerta } from '../../../funciones_helper/notificaciones/mensajes.js';
import { crud } from '../../../funciones_helper/operaciones_crud/crud.js';
import { vaciar_errores, vaciar_formulario } from '../../../funciones_helper/vistas/formulario.js';


// PAGAR CUOTA
$('#form_pagarCuotas').submit(function (e) {

    setTimeout(() => {
        location.reload();
    }, 1500);
});


// PAGAR DONACION
$('#buton_PagarCuotaDonacion').click(function (e) {

    e.preventDefault();
    $('#modalDonacion').modal('show');

});


$('#btn_nueva_donacion').click(function (e) {


    e.preventDefault();

    Swal.fire({
        title: "NOTA!",
        text: "¿Está seguro de realizar el pago?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, Pagar",
        cancelButtonText: "Cancelar",
    }).then(async function (result) {
        if (result.isConfirmed) {

            let datos = $('#form_pagarCuotas').serialize();
            $("#btn_nueva_donacion").prop("disabled", true);
            // console.log(datos);

            crud("admin/PagarCuotasDonacion", "POST", null, datos, function (error, response) {
                $("#btn_nueva_donacion").prop("disabled", false);
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
                $('#modalDonacion').modal('hide');
                // si todo esta correcto muestra el mensaje de correcto
                mensajeAlerta(response.mensaje, response.tipo);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            })
        } else {
            alerta_top('error', 'Se canceló el pago');
        }
    })
});





// BUSCAR ESTUDIANTE POR CI
$('#ci_estudiante').keyup(function () {
    let ci_estudiante = $(this).val(); // Captura el valor actual del campo de entrada
    if (ci_estudiante.length < 6) {

        $("#buton_PagarCuota").prop("disabled", true);
        $("#buton_PagarCuotaDonacion").prop("disabled", true);
        $('#mesesPagados').html("");
        return;
    }

    crud("admin/pagarCuotas", "GET", ci_estudiante, null, function (error, response) {

        console.log(response);
        // Verificamos que no haya un error o que todos los campos sean llenados
        if (response.tipo === "errores") {

            mensajeAlerta(response.mensaje, "errores");
            $('#nombre_apellido_res').text("no encontrado...");
            return;
        }
        if (response.tipo != "exito") {
            $('#nombre_apellido_res').text("no encontrado...");
            $("#buton_PagarCuota").prop("disabled", true);
            $("#buton_PagarCuotaDonacion").prop("disabled", true);
            $('#mesesPagados').html("");
            return;
        }
        $("#buton_PagarCuotaDonacion").prop("disabled", false);
        $("#buton_PagarCuota").prop("disabled", false);


        let elemento = "";
        if (response.mensaje.meses.length == 0) {

            console.log("enmtro");
            $('#mesesPagados').html("<p class='text-center mt-1 text-success'>Todos los meses han sido pagados</p>");

            return;
        }

        response.mensaje.meses.forEach(element => {
            elemento += `

             <div class="col-12 col-md-6">
                <div class="form-check d-flex justify-content-between align-items-center mt-2">
                    <label class="form-check-label me-3" for="enero">${element.mes}</label>
                        <input class="form-check-input" type="checkbox" id="${element.mes}"
                                        name="meses[]" value="${element.id}"  style="border-color: #007bff">
                </div>
             </div>
            `;

        });

        let nombreCompleto = response.mensaje.estudiante.nombres + " " + response.mensaje.estudiante.paterno + " " + response.mensaje.estudiante.materno;
        $('#nombre_apellido_res').text(nombreCompleto);
        $('#mesesPagados').html(elemento);

    })
});


// MARCAR DESMARCAR CHECK
$('#select_all').on('change', function () {
    // Cambia el estado de todos los checkboxes dentro de #form_rol
    $('#form_pagarCuotas input[type="checkbox"]').prop('checked', this.checked);
});




