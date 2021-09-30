<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        body{
            font-family: helvetica, sans-serif;
            font-size: 12pt;
            color:  #212529;
        }
        .bigger{
            font-size: 20px;
        }
        .txt-small{
            font-size: 10pt;
        }
        p{
            font-size: 10pt;
        }
        th{
            background-color :#0D47A1;
            border: 1px solid #0D47A1;
            border-collapse: collapse;
            color: #f8f9fa;
            padding: 10px;
        }
        table,td, tr{
            font-size: 11px;
            border-collapse: collapse;

        }
        table{
            border: 1px solid #999;
        }
        td{
            padding: 10px;

        }
        .heavy{
            font-weight: bold;
        }
        .text-white{
            color: #f8f9fa;
        }
        .text-black{
            color: #000;
        }

        .mx-auto{
            margin-left: auto;
            margin-right: auto;
        }
        h2{
            font-size: 16px;
        }
        .text-center{
            text-align: center;
        }
        .text-right{
            text-align : right;
        }
        .spacing-s{
            height:  15px;
            border : 0;
        }

        .spacing-m{
            height:  20px;
            border : 0;
        }
        .spacing-l{
            height:  40px;
            border : 0;
        }
        .padding-table, .padding-table td{
            padding:  10px;
            border-collapse: collapse;
            border:  0;
        }





    </style>
</style>
<title></title>
</head>
<body>

    <h2 class="text-center">Listing des evos de la semaine <?=$week?></h2>



        <?php foreach ($evos as $key => $evo): ?>
            <h4>#<?=$evo['id']. " ".$evo['objet']?></h4>
            <p><?=nl2br($evo['evo'])?></p>
            <p><i><?=$evo['cmt_dd']?></i></p>
            <p><i><?=$evo['cmt_dev']?></i></p>

        <?php endforeach ?>

</body>
</html>