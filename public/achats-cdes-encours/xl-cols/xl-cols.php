<?php
function getNameFromNumber($num)
{
    $numeric = $num % 26;
    $letter = chr(65 + $numeric);
    $num2 = intval($num / 26);
    if ($num2 > 0) {
        return getNameFromNumber($num2 - 1) . $letter;
    } else {
        return $letter;
    }
}
function dateFormat($str)
{
    $dateParts = explode('-', $str);
    if (count($dateParts) != 3) {
        $dateParts = explode('-', $str);
    }
    if (count($dateParts) == 3) {
        foreach ($dateParts as $date) {
            $day = $dateParts[0];
            $month = $dateParts[1];
            $year = $dateParts[2];
        }
        $dateStr = $day . '-' . $month . '-' . $year;
        if ($year < 2000) {
            return DateTime::createFromFormat('d-m-y', $dateStr)->format('Y-m-d');
        } else {
            return DateTime::createFromFormat('d-m-Y', $dateStr)->format('Y-m-d');
        }
    }
}

// 01/01/20
//x     =       cmt_btlec       = 24
//y     =       cmt_galec
//z	    =       qte_previ 1
// aa	date previ 1
// ab	qte_previ 2
// ac	date previ 2
// ad	qte_previ 3
// ae	date previ 3
// af	qte_previ 4
// ag	date previ 4
// ah	qte_previ 5
// ai	date previ 5
// aj	qte_previ 6
// ak	date previ 6
// al	id_cdes_cmt
// am	id_cdes_info 1
// an	id_cdes_info 2
// ao	id_cdes_info 3
// ap	id_cdes_info 4
// aq	id_cdes_info 5
// ar	id_cdes_info 6


$excelStart = new DateTimeImmutable('1899-12-30');

$colQteInit = 25;
$colDateInit = 26;
$colIdInfoInit = 39;

for ($i = 0; $i < 6; $i++) {
    $colQte = $colQteInit + $i * 2;
    $colDate = $colDateInit + $i * 2;
    $colIdInfo = $colIdInfoInit + $i;

    $colQteArray[] = $colQte;
    $colQteStrArray[] = getNameFromNumber($colQte);
    $colDateArray[] = $colDate;
    $colDateStrArray[] = getNameFromNumber($colDate);
    $colIdInfoArray[] = $colIdInfo;
    $colIdInfoStrArray[] = getNameFromNumber($colIdInfo);
}

define("COL_QTE", $colQteArray);
define("COL_DATE", $colDateArray);
define("COL_ID_INFO", $colIdInfoArray);

define("COL_QTE_STR", $colQteStrArray);
define("COL_DATE_STR", $colDateStrArray);
define("COL_ID_INFO_STR", $colIdInfoStrArray);
$colIdCmt = 38;
$colIdCmtStr = getNameFromNumber($colIdCmt);
