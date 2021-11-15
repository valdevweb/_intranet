<?php


$ldaptree="OU=btlec,OU=galec,o=e-leclerc,c=fr";
$filter = "(ou=*)";
    $ldap_user ="ADMIN_BTLEC";

    $ldap_pass = "toronto";
    $connect = ldap_connect('217.0.222.26',389);
    ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
// $ldaptree    = "OU=btlec,OU=galec,o=e-leclerc,c=fr";

    $ldapbind = ldap_bind($connect, $ldap_user, $ldap_pass) or die ("Error trying to bind: ".ldap_error($ldapbind));

    $read = ldap_search($connect,$ldaptree, $filter);

    $info = ldap_get_entries($connect, $read);
    echo $info["count"]." entrees retournees<BR><BR>";
    for($ligne = 0; $ligne<$info["count"]; $ligne++)
    {
        for($colonne = 0; $colonne<$info[$ligne]["count"]; $colonne++)
        {
            $data = $info[$ligne][$colonne];
            echo $data.":".$info[$ligne][$data][0]."<BR>";
        }
        echo "<BR>";
    }
ldap_close($connect);


