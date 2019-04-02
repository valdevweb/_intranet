<?php
function isUserAllowed($pdoUser, $params)
{
	$session=$_SESSION['id'];
	$placeholders=implode(',', array_fill(0, count($params), '?'));
	$req=$pdoUser->prepare("SELECT login FROM attributions WHERE id_droit IN($placeholders) AND id_user=$session" );
	$req->execute($params);
	return $req->fetchAll(PDO::FETCH_ASSOC);

}

// accès reversement : admin, compta, rev
$revIds=array(5,7,8);
$d_rev=isUserAllowed($pdoUser,$revIds);


$a_rev="<li><a href='".ROOT_PATH."/public/doc/exploit_rev.php'>Exploit reversements</a></li>";
if($d_rev)
{
	echo $a_rev;
}

?>
