		var bndIdCookiesInfos = 'cookiesAccept';
		var bndIdCookiesScript = 'cookiesScript';
		var contentInfosMessage = 'BTLec EST, éditeur de ce site, n\'utilise des cookies que pour assurer le bon fonctionnement du site. Nous n\'utilisons aucun cookie d\'analyse de traffic et/ou publicitaire';
		var contentInfosMessageLinkMore = '';
		var blkInfos = null;var blkScript = null;var htmlChecked = null;
		window.onload = function () {
			if ((document.getElementById(bndIdCookiesInfos)) && (document.getElementById(bndIdCookiesScript))) {
				var cookiesAccepted = null;
				blkInfos = document.getElementById(bndIdCookiesInfos);
				blkScript = document.getElementById(bndIdCookiesScript);
				htmlChecked = true;
				if (sessionStorage.getItem("cookiesAccepted")) {cookiesAccepted = sessionStorage.getItem("cookiesAccepted");}
				else {sessionStorage.setItem('cookiesAccepted', 'null');}
				CookiesAreAccepted(cookiesAccepted);
			}
		};
		function CookiesAreAccepted(param) {
			if (htmlChecked === true) {
				if (param === 'true') {
					sessionStorage.setItem('cookiesAccepted', 'true');
					blkInfos.remove();
				}
				else if (param === 'false') {
					sessionStorage.setItem('cookiesAccepted', 'false');
					blkInfos.remove(); blkScript.remove();
				}
				else {
					var htmlContent = '<div style="z-index: 9999 !important;position: fixed !important;background-color: rgba(51, 51, 51, 0.6);color:#fff;bottom: 0;width: 100%;padding: 10px;">';
					htmlContent += '<div style="float: left;">' + contentInfosMessage + '';
					if (contentInfosMessageLinkMore != '') {
						htmlContent += ' <a href="' + contentInfosMessageLinkMore + '" style="color:#fff;text-decoration: underline;">en savoir +</a>';
					}
					htmlContent += '</div>';
					htmlContent += '<div style="float: right; margin-right: 50px;">';
					htmlContent += '<span id="btnAcceptCookies" style="padding:5px;background-color: lightgrey;cursor:pointer;" onclick="CookiesAreAccepted(\'true\');">OK</span>  ';
					htmlContent += '</div></div>';
					blkInfos.innerHTML = htmlContent;
				}
			}
		}
		function ClearCookieschoices() {sessionStorage.removeItem('cookiesAccepted');}