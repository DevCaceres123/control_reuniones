import { mensajeAlerta } from '../../../funciones_helper/notificaciones/mensajes.js';
import { crud } from '../../../funciones_helper/operaciones_crud/crud.js';
import { vaciar_errores, vaciar_formulario } from '../../../funciones_helper/vistas/formulario.js';

$('#form_pagarCuotas').submit(function (e) {
    e.preventDefault();
    
    Swal.fire({
        title: "NOTA!",
        text: "¿Está seguro de registrar el pago?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, Pagar",
        cancelButtonText: "Cancelar",
    }).then(async function (result) {
        if (result.isConfirmed) {

            let datos=$('#form_pagarCuotas').serialize();
            crud("admin/pagarCuotas", "POST", null, datos, function (error, response) {

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
                setTimeout(() => {
                    location.reload();
                }, 1500);

            })
        } else {
            alerta_top('error', 'Se canceló el registro de pago');
        }
    })
});