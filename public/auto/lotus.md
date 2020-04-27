TRAITEMENTS : 

1-lotus-file-new 
=> include lotus lotus-file-import et lotus-errors (lotus-errors plus sutilisé)
=> si ficher date jour => traitement 

2- lotus-compare
=> compare ajout et suppression ld_lotus(n) et ld_lotus_old (n-1) 
=> diff dans histo : 1 ajout /0  suppression

3- lotus-maj : 
=> lotus-histo => maj mag_email


PROCESS lotus-file-import :

1- size($nomListesDiffu==$contenuListeDiffu) ?      
$nomListesDiffu[$idLd]
$contenuListeDiffu[[$idLd]]
                            => les $idLd correspondent
2- nettoyage des 2 tableau
3- contenuLd($i)==""        => insert code 0
4- arrOfMails=explode(contenuLD[$i])
5- each arrOfMails[$i]
    => ="/"                 => lotusList[]
    => ="@"                 => validEmail[]
    => != "@" & !="/"       => anotherLd[]
6- each valideEmail         => insert code 0
7- each anotherLd           => insert code 6
8- each lotusList            
    =>found in ldap         => insert code 0
    => tj '/'                => insert code 5
    => not found            => insert code 2