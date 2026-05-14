import { Controller } from '@hotwired/stimulus';
import { apiCall } from './../js/api_client.js';

const API_URL = 'https://127.0.0.1:8001/api/product';

export default class extends Controller {
    static targets = ["formularioContenedor",
        "divListaProductosApi", "cuerpoTablaProductos", "formProducto",
        "name", "price"];
    connect() {
        console.log('Hola desde el controlador de productos');
        this.#cargarProductos();

    }

    #cargarProductos() {
        // Ocultamos el formulario usando el target
        this.formularioContenedorTarget.style.display = 'none';

        apiCall(API_URL)
            .then(productos => this.#mostrarProductos(productos))
            .catch(err => {
                console.error('Error al cargar productos:', err);
                this.#mostrarNotificacion('Error al cargar productos', 'danger');
            });
    }



    mostrarFormCrear() {
        this.formularioContenedorTarget.style.display = 'block';
        this.formProductoTarget.reset();
    }

    crearProducto(e) {
        //Se previene el comportamiento por defecto del formulario, que es recargar la pÃ¡gina y enviar los datos de forma tradicional
        e.preventDefault();

        const nuevoProducto = {
            name: this.nameTarget.value,
            // Si el campo de precio estÃ¡ vacÃ­o, enviamos null para que se guarde como NULL en la base de datos
            price: this.priceTarget.value.trim() || null
        };

        //Completar con la llamada a apiCall para crear el producto, mostrar notificaciÃ³n y refrescar la lista

        apiCall(API_URL, 'POST', nuevoProducto)
            .then(producto => {
                this.#mostrarNotificacion(`Se ha creado el producto correctamente con nombre: ${producto.name}`);
                //btnCargar.click();
                this.#cargarProductos();
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
                this.#mostrarNotificacion('Ha ocurrido un error y no se ha podido crear el producto ' + mensaje, 'danger');
            });

    }

    #mostrarProductos(productos) {

        this.divListaProductosApiTarget.style.display = 'block';

        this.cuerpoTablaProductosTarget.innerHTML = '';

        productos.forEach(producto => {
            const fila = document.createElement('tr');
            fila.innerHTML = `
            <td>${producto.id}</td>
            <td><strong>${producto.name}</strong></td>
            <td>${producto.price || '<span class="text-muted">Sin precio</span>'}</td>
          
        `;
            this.cuerpoTablaProductosTarget.appendChild(fila);
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


}