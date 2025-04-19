## Carga Inicial

Los usuarios iniciales son

soporte@miasoftware.net
x5w93kra

### Laravel Socialite
https://www.cursosdesarrolloweb.es/blog/autenticacion-laravel
https://laravel.com/docs/11.x/socialite

Se esta usando integracion



## Clear CACHE

php artisan cache:clear
php artisan view:cache
php artisan view:clear
php artisan config:cache
php artisan config:clear
php artisan event:cache
php artisan event:clear
php artisan route:cache
php artisan route:clear


## Crear AD

<?php
$ldap_host = "ldap://contact.com"; // o la IP del servidor AD
$ldap_port = 389; // o 636 si usas LDAPS
$ldap_user = "llombardi@contact.com"; // usuario con permisos
$ldap_pass = "Peru+1014";

// Conectarse al servidor LDAP
$ldap_conn = ldap_connect($ldap_host, $ldap_port);
ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);

// Autenticarse
if (ldap_bind($ldap_conn, $ldap_user, $ldap_pass)) {
    echo "Conexión y autenticación exitosa.<br>";

    // DN donde se creará el nuevo usuario
    $dn = "CN=Juan Perez,OU=win,OU=OPERACIONES,OU=CONTACT,DC=contact,DC=com";

    // DN del grupo
    #$group_dn = [];
    #$group_dn[0] = "CN=ESTRUCTURA,OU=CONTACT,DC=contact,DC=com";
    #$group_dn[1] = "CN=win,OU=OPERACIONES,OU=CONTACT,DC=contact,DC=com";

    $group_dn = "CN=ESTRUCTURA,OU=CONTACT,DC=contact,DC=com";

    // Atributos del nuevo usuario
    $info = [];
    $info["cn"] = "Juan Perez";
    $info["givenName"] = "Juan";
    $info["sn"] = "Perez";
    $info["objectClass"] = ["top", "person", "organizationalPerson", "user"];
    $info["sAMAccountName"] = "jperez";
    $info["userPrincipalName"] = "jperez@contact.com";
    $info["displayName"] = "Juan Perez";
    $info["mail"] = "jperez@contact.com";
    $info["member"] = $group_dn;

    // Crear el usuario
    if (ldap_add($ldap_conn, $dn, $info)) {
        echo "Usuario creado correctamente.";
    } else {
        echo "Error al crear usuario: " . ldap_error($ldap_conn);
    }

    ldap_unbind($ldap_conn);
} else {
    echo "Error al conectar o autenticar: " . ldap_error($ldap_conn);
}
?>
