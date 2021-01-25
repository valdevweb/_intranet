<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="viewport" content="width=320, target-densitydpi=device-dpi">
    <style type="text/css">
        a.home{
            color: #f18e0b;
            font-weight:  bold;
            text-decoration: none;

        }
        .footer{
            font-size: 0.9em;
        }

    </style>
</head>

<body style="font-family:arial,helvetica, 'sans serif'; color:dimGray; font-size:14pt">
    <table cellpadding="0" cellspacing="0" width="920px">
        <tr>
            <td>
                <table width="900px" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td colspan="2">
                            <img src="{SITE_ADDRESS}/public/mail/mail-hebdo-header.jpg">
                        </td>
                    </tr>
                    <tr>
                        <td  colspan="2">
                            <div style="font-style:italic; text-align:right">{TODAY}</div><br>
                        </td>
                    </tr>

                    <tr>
                        <td width="669px">
                            <p>Bonjour,</p>
                            Aujourd'hui, retrouvez sur le <a href="<?=SITE_ADDRESS?>/index.php" class="home">portail BTLec</a> :
                            <div style="color:darkblue; font-weight:bold">
                               {FILELIST}
                           </div>
                       </td>
                       <td  width="251px" >
                        <p><img src="<?=SITE_ADDRESS?>/public/mail/flash-mail.jpg" style="border:1px #ccc solid"></p>

                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <p style="color:darkblue;">------------------------<br>
                        Portail BTLec EST</p>
                        <div class="footer">
                            <p>Pour vous rendre sur le portail, vous pouvez :<br>
                                - soit<a href="{SITE_ADDRESS}/index.php"> cliquer ici </a>
                                <br>- soit copier/coller cette adresse  :  <span style="color:darkblue;"> {SITE_ADDRESS}</span>
                                <p style="color:darkorange;">*** Merci de ne pas répondre à ce mail, cette boîte mail n'est pas consultée ***</p>
                            </div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>

    </table>


</body>
</html>

