# Instrucciones para recrear el esqueleto de Symfony WebApp 7.4

Este proyecto se ha creado siguiendo los pasos indicados a continuación:

## 1️⃣ Crear el proyecto base

```bash
composer create-project symfony/skeleton:"7.4.*" my_project_directory
cd my_project_directory
composer require webapp
```

## 2️⃣ Eliminar elementos que no se utilizarán

Ejecutar los siguientes comandos:

```bash
php bin/console importmap:remove @hotwired/stimulus
php bin/console importmap:remove @hotwired/turbo
php bin/console importmap:remove @symfony/stimulus-bundle
```

## 3️⃣ Añadir estilos de Bootstrap

```bash
php bin/console importmap:require bootstrap
```

## 4️⃣ Modificar el archivo `assets/app.js`

Comentar la siguiente línea:

```js
// import './stimulus_bootstrap.js';
```

Añadir la siguiente línea:

```js
import 'bootstrap/dist/css/bootstrap.min.css';
```

## ✅ Proyecto listo

Con estos pasos conseguimos un esqueleto limpio de Symfony 7.4 con Bootstrap integrado y sin Stimulus ni Turbo.
Arranca el servidor local con:
```bash
symfony server:start
```

# Actividad 4.18

 Cómo cambiar la configuración para que se redirija a otra ruta diferente de otro controlador. Indica en el README.md cómo has hecho.
 En la guía hay un enlace a https://symfony.com/doc/7.4/reference/configuration/security.html#form-login-authentication
 Concretamente https://symfony.com/doc/7.4/reference/configuration/security.html#default-target-path
 Añadimos en security.yaml bajo form_login: 
 default_target_path: consultas_biblioteca

Otra opción sería añadir en login.html.twig
<input type="hidden"  name="_target_path" value="/consultas/notas">