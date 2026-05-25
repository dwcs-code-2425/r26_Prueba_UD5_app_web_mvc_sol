/**
 * FUNCIÓN WRAPPER: Encapsula la lógica de Fetch
 */
export async function apiCall(url, method = 'GET', body = null) {
    const options = {
        method
      
    };

   // 2. Solo añadimos cabeceras y cuerpo si realmente enviamos datos (POST, PUT, PATCH)
    if (body && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
         options.headers = {
             'Content-Type': 'application/json'
         };
        options.body = JSON.stringify(body);
    }

    const response = await fetch(url, options);

    // extra para evitar errores al eliminar (204 No Content)

    if (response.status === 204) {

        return null;

    }
    const data = await response.json();

    if (!response.ok) {
        // "Lanzamos" el objeto de error para que lo capture el .catch()
        throw data; 
    }

    return data;
}
