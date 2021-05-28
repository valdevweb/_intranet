function redirect(){
	window.location.href ='../logoff.php';
}
// 2880
window.onload = function(){
	setInterval(function(){
		alert("Votre session est inactive depuis 45 minutes, sans action de votre part, vous allez être déconnecté");
	}, 2870000);
	setInterval(function(){
		redirect();
	}, 2880000);
};