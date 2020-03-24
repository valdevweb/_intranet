<?php


function insertTaskLog($pdoExploit,$idTask, $ko, $logfile){
	$req=$pdoExploit->prepare("INSERT INTO task_log (id_task, date_exec, ko, logfile) VALUES (:id_task, :date_exec, :ko, :logfile)");
	$req->execute([
		':id_task'		=>$idTask,
		':date_exec'	=> date('Y-m-d H:i:s'),
		':ko'			=>$ko,
		':logfile'			=>$logfile,
	]);
}


