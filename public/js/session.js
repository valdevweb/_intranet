function redirect(){
	window.location.href ='../logoff.php';
}
window.onload = function(){
	setInterval(function(){
		alert("Votre session est inactive depuis 23 minutes, sans action de votre part, vous allez être déconnecté");
	}, 1380000);
	setInterval(function(){
		redirect();
	}, 1440000);
};