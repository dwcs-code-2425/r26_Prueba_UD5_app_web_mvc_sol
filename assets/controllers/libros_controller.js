import { Controller } from '@hotwired/stimulus';
import { apiCall } from './../js/api_client.js';

const API_URL = 'https://127.0.0.1:8001/api/v2/libros';

export default class extends Controller {
    static targets = ["formularioContenedor",
        "divListaLibrosApi", "cuerpoTablaLibros", "formLibro",
         "titulo", "descripcion"];
    connect() {
        console.log('Hola desde el controlador de libros');
    }

    async cargarLibros() {
        // Ocultamos el formulario usando el target
        this.formularioContenedorTarget.style.display = 'none';

        apiCall(API_URL)
            .then(libros => this.#mostrarLibros(libros))
            .catch(err => {
                console.error('Error al cargar libros:', err);
                this.#mostrarNotificacion('Error al cargar libros', 'danger');
            });
    }

    confirmarEliminarLibro(e) {
        if (e.target.classList.contains('btn-borrar')) {

            const id = e.target.dataset.id;
            const fila = e.target.closest('tr'); // Encuentra la fila (tr) más cercana al botón
            if (confirm(`¿Estás seguro de eliminar el libro con ID ${id}?`)) {
                this.#eliminarLibro(id, fila);
            }
        }
    }

    mostrarFormCrear() {
        this.formularioContenedorTarget.style.display = 'block';
        this.formLibroTarget.reset();
    }

    crearLibro(e) {
        //Se previene el comportamiento por defecto del formulario, que es recargar la pÃ¡gina y enviar los datos de forma tradicional
        e.preventDefault();

        const nuevoLibro = {
            titulo: this.tituloTarget.value, //.getElementById('titulo').value,
            // Si el campo de descripciÃ³n estÃ¡ vacÃ­o, enviamos null para que se guarde como NULL en la base de datos
            descripcion: this.descripcionTarget.value.trim()  || null
        };

        //Completar con la llamada a apiCall para crear el libro, mostrar notificaciÃ³n y refrescar la lista

        apiCall(API_URL, 'POST', nuevoLibro)
            .then(libro => {
                this.#mostrarNotificacion(`Se ha creado el libro correctamente con título: ${libro.titulo}`);
                //btnCargar.click();
                this.cargarLibros();
            }
            )
            .catch(error => {
                let mensaje = '';
                console.error(error);
                if (error.fields) {

                    for (let msg in error.fields) {
                        mensaje += ' ' + error.fields[msg];
                    }
                }
                this.#mostrarNotificacion('Ha ocurrido un error y no se ha podido crear el libro ' + mensaje, 'danger');
            });

    }

    #mostrarLibros(libros) {
        // const cuerpoTabla = document.getElementById('cuerpoTablaLibros');
        //document.getElementById('divListaLibrosApi').style.display = 'block';
        this.divListaLibrosApiTarget.style.display = 'block';
        //  cuerpoTabla.innerHTML = '';
        this.cuerpoTablaLibrosTarget.innerHTML = '';

        libros.forEach(libro => {
            const fila = document.createElement('tr');
            fila.innerHTML = `
            <td>${libro.id}</td>
            <td><strong>${libro.titulo}</strong></td>
            <td>${libro.descripcion || '<span class="text-muted">Sin descripción</span>'}</td>
            <td class="text-center">
                <button class="btn btn-danger btn-sm btn-borrar" data-id="${libro.id}">
                    Eliminar
                </button>
            </td>
        `;
            this.cuerpoTablaLibrosTarget.appendChild(fila);
        });
    }

    #mostrarNotificacion(mensaje, tipo = 'success') {
        const divAlert = document.getElementById('buzonMensajes');
        divAlert.className = `alert alert-${tipo} mt-3`;
        divAlert.innerHTML = mensaje;
        divAlert.style.display = 'block';

        if (tipo === 'success') {
            setTimeout(() => divAlert.style.display = 'none', 3000);
        }
    }

    #eliminarLibro(id, fila) {

        apiCall(API_URL + '/' + id, "DELETE")
            .then(libro => {
                console.log(' éxito');
                fila.remove();
                this.#mostrarNotificacion('Se ha eliminado con éxito el libro con id: ' + id);
            })
            .catch(error => {
                console.error('error en catch: ' + error);
                this.#mostrarNotificacion('No se ha podido eliminar libro con id: ' + id, 'danger');
            })


    }
}