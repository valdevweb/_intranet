
session_start();

define('WEBSITE_NAME', 'BTLEC Est - conseil');
$path=dirname(__FILE__);

function connectToDb($dbname) {
	$host='localhost';
	$username='sql';
	$pwd='User19092017+';
	try {
		$pdo=new PDO("mysql:host=$host;dbname=$dbname", $username, $pwd);

	}
	catch(Exception $e)
	{
		die('Erreur : '.$e->getMessage());
	}
	return  $pdo;
}


if (preg_match('/_conseil/', $path))
{
	define("ROOT_PATH","/_conseil");
	define("SITE_ADDRESS", "http://172.30.92.53/_conseil");
	// $pdo_file='_pdo_connect.php';
	$version='_';
	define("VERSION",'_');
	// define("PORTAIL","Portail BTlec - dev" );
	// define("UPLOAD_DIR","http://172.30.92.53/_upload" );
	$pdo=connectToDb('_conseil');
	$pdoUser=connectToDb('web_users');
	$pdoBt=connectToDb('_btlec');
	$pdoStat=connectToDb('stats');
}
else
{
	define("ROOT_PATH","/conseil");
	define("SITE_ADDRESS", "http://172.30.92.53/conseil");
	// $pdo_file='pdo_connect.php';
	$version='';
	// define("PORTAIL","Portail BTlec" );
	define("VERSION",'');
	// define("UPLOAD_DIR","http://172.30.92.53/upload" );
	$pdo=connectToDb('conseil');
	$pdoUser=connectToDb('web_users');
	$pdoBt=connectToDb('btlec');
	$pdoStat=connectToDb('stats');
}

$okko= 'version : ' . ROOT_PATH  ;












