<?php
include 'config.inc.php';

  $fieldseparator = ",";
  $lineseparator = "\n";


$importDir="D:\btlec\dumps\gessica\\";
// vérif si fichier à la date du jour

$arrFilename=["SBBCFMAG.txt","SBBCFPID.txt","SCEBFADH.txt", 'test.txt'];
$csvfile=$importDir.$arrFilename[0];
 if(!file_exists('test.txt'))
  {
    die("File not found. Make sure you specified the correct path.");
  }

// $req=

//   $affectedRows = $pdoQlik->exec("LOAD DATA  INFILE "
//     .$pdoQlik->quote($csvfile)
//     ." INTO TABLE mag_ctbt FIELDS TERMINATED BY "
//     .$pdoQlik->quote($fieldseparator)
//     ."LINES TERMINATED BY "
//     .$pdoQlik->quote($lineseparator)
//   );


//   echo "Loaded a total of $affectedRows records from this csv file.\n";


  $sql = "LOAD DATA INFILE 'test.txt'
        INTO TABLE test_import
        FIELDS TERMINATED BY ','
        OPTIONALLY ENCLOSED BY '\"'
        LINES TERMINATED BY '\\r\\n'
        IGNORE 1 LINES
        (id,one,two)


            ";
            echo $sql;
            echo "<br>";

$req=$pdoQlik->prepare($sql);
    echo "<pre>";
    print_r($req);
    echo '</pre>';

$req->execute();
$err=$req->errorInfo();
    echo "<pre>";
    print_r($err);
    echo '</pre>';


                  // date = STR_TO_DATE(@date, '%b-%d-%Y %h:%i:%s %p'),
        //     number = TRIM(BOTH '\'' FROM @number),
        //     duration = 1 * TRIM(TRAILING 'Secs' FROM @duration),
        //     addr = NULLIF(@addr, 'null'),
        //     pin  = NULLIF(@pin, 'null'),
        //     city = NULLIF(@city, 'null'),
        //     state = NULLIF(@state, 'null'),
        //     country = NULLIF(@country, 'null')