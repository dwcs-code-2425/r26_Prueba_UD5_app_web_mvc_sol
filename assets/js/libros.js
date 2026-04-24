import { apiCall } from './api_client.js';

const API_URL = 'https://127.0.0.1:8001/api/v2/libros';

document.addEventListener('DOMContentLoaded', () => {
    const btnCargar = document.getElementById('btnLibros');
    const btnCrear = document.querySelector('#btnCrearLibro');
    const formularioContenedor = document.getElementById('divFormCrear');
    const form = document.getElementById('formLibro');
    const btnCancelar = document.getElementById('btnCancelar');

    if (!btnCargar || !btnCrear) { console.debug('Botones no encontrados'); return; }

    // --- CARGAR LIBROS ---
    btnCargar.addEventListener('click', () => {
        formularioContenedor.style.display = 'none';
        apiCall(API_URL).then(libros => mostrarLibros(libros))
            .catch(err => {
                console.error('Error al cargar libros:', err);
                mostrarNotificacion('Error al cargar libros', 'danger');
            });

    });

    // --- MOSTRAR/OCULTAR FORM ---
    btnCrear.addEventListener('click', () => {
        formularioContenedor.style.display = 'block';
        form.reset();
    });

    btnCancelar.addEventListener('click', () => {
        formularioContenedor.style.display = 'none';
        form.reset();
    });

    // --- CREAR LIBRO (POST) ---
    form.addEventListener('submit', (e) => {
        //Se previene el comportamiento por defecto del formulario, que es recargar la pÃ¡gina y enviar los datos de forma tradicional
        e.preventDefault();

        const nuevoLibro = {
            titulo: document.getElementById('titulo').value,
            // Si el campo de descripciÃ³n estÃ¡ vacÃ­o, enviamos null para que se guarde como NULL en la base de datos
            descripcion: document.getElementById('descripcion').value.trim() || null
        };

        //Completar con la llamada a apiCall para crear el libro, mostrar notificaciÃ³n y refrescar la lista

        apiCall(API_URL, 'POST', nuevoLibro)
            .then(libro => {
                mostrarNotificacion(`Se ha creado el libro correctamente con título: ${libro.titulo}`);
                btnCargar.click();
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
                mostrarNotificacion('Ha ocurrido un error y no se ha podido crear el libro ' + mensaje, 'danger');
            })
    });
});


// --- FUNCIONES DE UI ---

function mostrarLibros(libros) {
    const cuerpoTabla = document.getElementById('cuerpoTablaLibros');
    document.getElementById('divListaLibrosApi').style.display = 'block';
    cuerpoTabla.innerHTML = '';

    libros.forEach(libro => {
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>${libro.id}</td>
            <td><strong>${libro.titulo}</strong></td>
            <td>${libro.descripcion || '<span class="text-muted">Sin descripción</span>'}</td>
        `;
        cuerpoTabla.appendChild(fila);
    });
}

function mostrarNotificacion(mensaje, tipo = 'success') {
    const divAlert = document.getElementById('buzonMensajes');
    divAlert.className = `alert alert-${tipo} mt-3`;
    divAlert.innerHTML = mensaje; // Usamos innerHTML por si enviamos <br/>
    divAlert.style.display = 'block';

    if (tipo === 'success') {
        setTimeout(() => divAlert.style.display = 'none', 3000);
    }
}