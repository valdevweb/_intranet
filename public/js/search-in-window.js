
var emplacement = null;
var i=0;
function findString(str) {
	if (parseInt(navigator.appVersion) < 4){
		return;
	}
	var strFound;
	var result;
	if (window.find){
		strFound = self.find(str);
		i++;
		result=strFound;
		if (!strFound){
			if(i==1){
				alert("String '" + str + "' non trouvé!");
			}
			i=0;
		}

		if (strFound && self.getSelection && !self.getSelection().anchorNode) {
			strFound = self.find(str)
		}
		if (!strFound) {
			strFound = self.find(str, 0, 1)
			while (self.find(str, 0, 1)){
				continue;
			}
		}
	}else if(navigator.appName.indexOf("Microsoft") != -1) {
		if (emplacement != null) {
			emplacement.collapse(false)
			strFound = emplacement.findText(str)
			if (strFound) {
				emplacement.select()
			}
		}
		if (emplacement == null || strFound == 0) {
			emplacement = self.document.body.createTextRange()
			strFound = emplacement.findText(str)
			if (strFound){
				emplacement.select()
			}
		}
	}
}