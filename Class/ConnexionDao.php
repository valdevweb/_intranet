<?php


class ConnexionDao{

	public function getMag($pdoUser,$idCentralesList){
		$centrales=' AND (' .join(' OR ', array_map(function($value){return 'centrale='.$value;},$idCentralesList)) .')';
		$req=$pdoUser->prepare("SELECT deno, centrale, users.galec FROM users LEFT JOIN magasin.mag ON users.galec=magasin.mag.galec WHERE type= 'mag' AND gel=0 $centrales ORDER BY mag.centrale, deno");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getConnexion($pdoStat,$startDateConn,$endDateConn,$idCentralesList){
		$centrales=' AND (' .join(' OR ', array_map(function($value){return 'centrale='.$value;},$idCentralesList)) .')';
		$req=$pdoStat->prepare("SELECT
			DATE_FORMAT(date_heure,'%c') as mois,
			DATE_FORMAT(date_heure,'%Y') as year,
			DATE_FORMAT(date_heure,'%e') as jour,
			DATE_FORMAT(date_heure,'%Y-%m-%d') as date_entiere,
			id_web_user,
			magasin.mag.galec
			FROM stats_logs
			LEFT JOIN web_users.users ON stats_logs.id_user=web_users.users.login
			LEFT JOIN magasin.mag ON web_users.users.galec=magasin.mag.galec
			WHERE site='portail BT' AND type_log='prod' AND description='user authentifié' AND magasin.mag.galec IS NOT NULL
			AND web_users.users.type='mag'
			AND DATE_FORMAT(date_heure, '%Y-%m') BETWEEN :datestart AND :dateend

			GROUP BY DATE_FORMAT(date_heure,'%Y-%m-%d'),`id_web_user`
			ORDER BY centrale, deno, date_heure ");
		$req->execute([
			':datestart'		=>$startDateConn->format('Y-m'),
			':dateend'			=>$endDateConn->format('Y-m')
		]);
	// return $req->errorInfo();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

}
