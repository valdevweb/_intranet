    <?php

    class CrudHelpers{
        private $pdo;


        public function __construct($pdo){
            $this->pdo=$pdo;
        }


        public function selectManyParams($table,$params=[], $rows=null){
            if(!isset($rows)){
                $rows="*";
            }
            $param = implode(' AND ', array_map(
                function ($v, $k) { return sprintf("%s=:%s ", $k, $k); },
                $params,
                array_keys($params)
            ));
            $sql="SELECT $rows FROM $table WHERE ".$param;
            echo $sql;
            $req=$this->pdo->prepare($sql);
            $req->execute($params);


            return $req->fetchAll();

        }

        public function insert( $table,$arrParam){
            $markers=':'.implode(', :', array_keys($arrParam));
            $fields=str_replace(":", "",$markers);
            $query="INSERT INTO $table ($fields) VALUES ($markers)";
            $req = $this->pdo->prepare($query);
            $req->execute($arrParam);
            return $req->rowCount();
        }





        public function update($table,$para=array(),$id){
            $args = array();

            foreach ($para as $key => $value) {
                $args[] = "$key = '$value'";
            }

            $sql="UPDATE  $table SET " . implode(',', $args);

            $sql .=" WHERE $id";

            $result = $this->mysqli->query($sql);
        }

        public function delete($table,$id){
            $sql="DELETE FROM $table";
            $sql .=" WHERE $id ";
            $sql;
            $result = $this->mysqli->query($sql);
        }





        public function getPdo(){
            $pdo = $this->pdo;
            return $pdo;
        }

        public function setPdo(){
            $this->pdo = $pdo;
        }

        public function getTable(){
            $table = $this->table;
            return $table;
        }

        public function setTable(){
            $this->table = $table;
        }

    }