<?php

class Month
{

  public $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

  private $months = [
    'Janvier',
    'Février',
    'Mars',
    'Avril',
    'Mai',
    'Juin',
    'Juillet',
    'Aout',
    'Septembre',
    'Octobre',
    'Novembre',
    'Décembre'
  ];
  public $month;
  public $year;
  public $nWeek;
    // public $maxWeek;
  public $calMonth;




    /**
     * Month constructor.
     * @param int $month Le mois compris entre 1 et 12
     * @param int $year L'année
     * ainsi  on peut faire $month= new Month(2018,1)
     * pour rendre les paramètres optionnels on les mets à null
     * et type de var int avec ? interrogation devant => type nullable
     */
    public function __construct(?int $month = null, ?int $year = null, ?int $nWeek= null)
    {
        // si vie ou autre non correct, mois et année actuelle
      if ($month === null || $month < 1 || $month > 12) {
            // force entier avec intval
        $month = intval(date('m'));
      }
      if ($year === null) {
        $year = intval(date('Y'));
      }
      $lastWeek=new DateTime($year.'-'.$month );
      $lastWeek=$lastWeek->modify('last day of december this year');
      $maxWeek=intval($lastWeek->format('W'));
        // echo $maxWeek;
        // le dernier jour du mois peut tomber semaine 1
      while($maxWeek==1){
        $lastWeek=$lastWeek->modify('- 1 day');
        $maxWeek=intval($lastWeek->format('W'));
            // echo $maxWeek;
      }

      if($nWeek===null || $nWeek < 1 || $nWeek > $maxWeek ){
        $nWeek=intval(date('W'));
      }

        // pour accéder à month et yea dans toute la classe
      $this->month = $month;
      $this->year = $year;
      $this->nWeek = $nWeek;
    }


    public function getThisMonday(): \DateTimeInterface
    {
      return (new \DateTimeImmutable())->setISODate($this->year,$this->nWeek);
    }


    public function getStartingDay(): \DateTimeInterface
    {
      return new \DateTimeImmutable("{$this->year}-{$this->month}-01");
    }


    public function toString(): string
    {
      return $this->months[$this->month - 1] . ' ' . $this->year;
    }



 public function getWeeks(): int
 {
        // 1er jour du mois
  $start = $this->getStartingDay();
        // der jour du mois
        // on peut faire un modify sans clone car on utilise des DateTimeInterface et non DateTime
  $end = $start->modify('+1 month -1 day');
        // num semaine 1er jour mois
  $startWeek = intval($start->format('W'));
        // num semaine der jour mois
  $endWeek = intval($end->format('W'));

  if ($endWeek === 1) {
    $endWeek = intval($end->modify('- 7 days')->format('W')) + 1;
  }
  $weeks = $endWeek - $startWeek + 1;
        // si le résultat de la soustraction est négatif, c'est qu'il y a eu un changement d'année (1er janvier pas forcément 1er semaine du mois)
        // on sait donc que l'on est au mois de janvier , le nombre de semaine est cdonc égale au numéro de la der semaine
  if ($weeks < 0) {
    $weeks = intval($end->format('W'));
  }
  return $weeks;
}


    /**
     * Renvoie le mois suivant
     * @return Month
     */
    public function nextMonth(): Month
    {
      $month = $this->month + 1;
      $year = $this->year;
      if ($month > 12) {
        $month = 1;
        $year += 1;
      }
      return new Month($month, $year);
    }

    /**
     * Renvoie le mois précédent
     * @return Month
     */
    public function previousMonth(): Month
    {
      $month = $this->month - 1;
      $year = $this->year;
      if ($month < 1) {
        $month = 12;
        $year -= 1;
      }
      return new Month($month, $year);
    }


    public function nextWeek(): Month
    {
      $lastWeek=new DateTime($this->year);
      $lastWeek=$lastWeek->modify('last day of december this year');
      $maxWeek=intval($lastWeek->format('W'));

      while($maxWeek==1){
        $lastWeek=$lastWeek->modify('- 1 day');
        $maxWeek=intval($lastWeek->format('W'));
      }

      if(intval($this->nWeek)==$maxWeek){
        $nWeek=1;
        $month = 1;
        $year = $this->year +1 ;

      }else{
        $nWeek=$this->nWeek + 1;
        $year = $this->year;
        $month = $this->month;
        // si en calculant la date avec le numéro de semaine, on obtient pas le même numéro de mois
        // c'est qu'on a changé de mois
        $calMonth=(new \DateTimeImmutable())->setISODate($year,$nWeek);
        if(intval($calMonth->format('m'))!=intval($month)){
          $month = $this->month + 1;
        }
        else{
          $month = $this->month;

        }
      }
      return new Month($month, $year, $nWeek);
    }


    public function previousWeek(): Month
    {
  // der semaine de l'année dernière
      $lastWeek=new DateTime($this->year -1);
      $lastWeek=$lastWeek->modify('last day of december this year');
      $maxWeek=intval($lastWeek->format('W'));

      while($maxWeek==1){
        $lastWeek=$lastWeek->modify('- 1 day');
        $maxWeek=intval($lastWeek->format('W'));
      }
      if(intval($this->nWeek)==1){
        $nWeek=$maxWeek;
        $month = 12;
        $year = $this->year -1 ;
      }else{
        $nWeek=$this->nWeek -1;
        $year = $this->year;
        $month = $this->month;
        //recupère le mois grace au numéro de semaine
        $calMonth=(new \DateTimeImmutable())->setISODate($year,$nWeek);


        if(intval($calMonth->format('m'))!=intval($this->month)){
          if($month==1){
            $month=12;
          }
          else{
            $month = intval($this->month - 1);
          }
        }
      }
      return new Month($month, $year, $nWeek);
    }

    public function nextYear() : Month{
      $year=intval($this->year+1);
      $month = intval(date('m'));
      return new Month($month,$year);

    }
    public function previousYear() : Month{
      $month = intval(date('m'));
      $year=intval($this->year-1);

      return new Month($month,$year);

    }
    public function displayMonth(){
      echo $this->month;
    }

    public function displaynWeek(){
      echo $this->nWeek;
    }

    public function getYear(){
      return $this->year;
    }
    public function getMonth(){
      return $this->month;

    }




  }