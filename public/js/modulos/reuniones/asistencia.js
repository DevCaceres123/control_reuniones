import { mensajeAlerta } from '../../../funciones_helper/notificaciones/mensajes.js';
import { crud } from '../../../funciones_helper/operaciones_crud/crud.js';
import { vaciar_errores, vaciar_formulario } from '../../../funciones_helper/vistas/formulario.js';



$('#form_reporteAsistencia').submit(function (e) {

    e.preventDefault();
   
    let datosFormulario = $('#form_reporteAsistencia').serialize();
    // vaciar_errores("formularioReunion_crear");
    crud("admin/asistencias", "POST", null, datosFormulario, function (error, response) {

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
        vaciar_formulario("formularioReunion_crear");
        mensajeAlerta(response.mensaje, response.tipo);
        listar_reuniones();
        

    })
})