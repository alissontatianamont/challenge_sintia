## DOCUMENTACIÓN DESARROLLO CHALLENGE-SINTIA

## REQUERIMIENTOS TÉCNICOS

- PHP > 8.1
- SERVIDOR LOCAL (XAMPP, WAMPP, LARAGON, ETC)
- Tener instalado node (con npm) y Composer
- Configurar variables .env listadas a continuación:

    - APP_KEY (GENERAR CON EL COMANDO php artisan key:generate)
    - APP_URL
    - DB_CONNECTION
    - DB_HOST
    - DB_PORT
    - DB_DATABASE
    - DB_USERNAME
    - DB_PASSWORD
    - GOOGLE_CLIENT_ID
    - GOOGLE_CLIENT_SECRET
    - GOOGLE_FOLDER_ID

para generar variables de entorno en google drive puede remitirse al siguiente video de apoyo: https://www.youtube.com/watch?v=WhlIQ2Sv6s8 y utilizar el entorno https://developers.google.com/oauthplayground para crear variables de uso gratuito, pueden usarse las variables actuales, en un plazo de 7 dias aproximadamente.

IMPORTANTE: REMITIRSE en  google console a: oauth pantalla de consentimiento -> Público -> Usuarios de prueba y  agregar el GMAIL de la cuenta con la que se generarán las credenciales

## DESPLIEGUE PROYECTO

- Ejecute los siguientes comandos en este orden:
    - php artisan migrate (ESTO GENERARA LAS TABLAS EN BD)
    - php artisan serve (Corre laravel)
    - npm run dev (Corre livewire)

## Credenciales Demo

- Las siguientes credenciales son para esta demo:

    - Email: admin@gmail.com
    - Password: 12345678


GRACIAS.



