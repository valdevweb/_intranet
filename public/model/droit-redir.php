<?php

require '../../Class/UserDao.php';
$userDao=new UserDao($pdoUser);
$droitAccess=$userDao->isUserAllowed([5,7,8]);








require '../../Class/UserDao.php';

$userDao=new UserDao($pdoUser);
$droitAccess=$userDao->isUserAllowed([5,6]);

if(!$droitAccess){
	header('Location:../home/home.php?access-denied');
}