    <?php

    class CrudDao{
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
            $req=$this->pdo->prepare($sql);
            $req->execute($params);
            return $req->fetchAll();
        }



        public function getOneById($table,$id){
            $req=$this->pdo->prepare("SELECT * FROM {$table} WHERE id= :id");
            $req->execute([
                ':id'       =>$id
            ]);
            return $req->fetch();
        }

        public function getOneParam($table, $param){
            $query='SELECT * FROM '.$table.' '.$param;
            $req=$this->pdo->query($query);
            return $req->fetch();
        }


        public function getOneByField($table, $field, $value){
            $req=$this->pdo->prepare("SELECT * FROM {$table} WHERE $field= :field");
            $req->execute([
                ':field'       =>$value
            ]);
            return $req->fetch();
        }


        public function getAll($table ,$where, $order=null, $ascDesc=null){
            $orderParam="";
            if(isset($order)){
                if(isset($ascDesc) && $ascDesc=="desc"){
                    $orderParam='ORDER BY '.$order . ' DESC';

                }else{
                    $orderParam='ORDER BY '.$order;
                }
            }
            $query='SELECT * FROM '.$table .' ' .$where. ' '.$orderParam;
            $req=$this->pdo->query($query);
            return $req->fetchAll();
        }

        public function insert($table,$arrParam){
            $markers=':'.implode(', :', array_keys($arrParam));
            $fields=str_replace(":", "",$markers);
            $query="INSERT INTO $table ($fields) VALUES ($markers)";
            $req = $this->pdo->prepare($query);
            $req->execute($arrParam);
            return $req->rowCount();
        }


        public function insertOne($table, $field, $value){
            $req=$this->pdo->prepare("INSERT INTO  $table ({$field}) VALUES (?)");
            $req->execute([
                $value
            ]);
            return $req->rowCount();
        }

        public function insertMany($table,  $datas){
            $fields=array_keys($datas);
            $strFields=join(', ', $fields);
            $placeholders=":".join(', :', $fields);

            $query="INSERT INTO  $table ({$strFields}) VALUES ({$placeholders})";
            $req=$this->pdo->prepare($query);
            $req->execute($datas);
            return $req->rowCount();
        }


        public function updateOneField($table, $field, $value, $id){
            $req=$this->pdo->prepare("UPDATE $table SET $field=:value WHERE id= :id");
            $req->execute([
                ':value'    =>$value,
                ':id'       =>$id
            ]);
            return $req->rowCount();
        }


        public function update($table, $where, $datas){
            $param = implode(', ', array_map(
                function ($v, $k) { return sprintf("%s=:%s ", $k, $k); },
                $datas,
                array_keys($datas)
            ));
            $query="UPDATE $table SET $param WHERE $where";
        // echo $query;
            $req = $this->pdo->prepare($query);
            $req->execute($datas);
            return $req->rowCount();
        }




        public function deleteOne($table,$id){
            $req=$this->pdo->prepare("DELETE FROM {$table} WHERE id= :id");
            $req->execute([
                ':id'       =>$id
            ]);
            return $req->errorInfo();
        }

        public function deleteByField($table,$field, $value){
            $req=$this->pdo->prepare("DELETE FROM {$table} WHERE $field= :value");
            $req->execute([
                ':value'       =>$value,

            ]);
            return $req->errorInfo();
        }

        public function deleteByFields($table, $params){
            $where = implode(' AND ', array_map(
                function ($v, $k) { return sprintf("%s=:%s ", $k, $k); },
                $params,
                array_keys($params)
            ));
            $query="DELETE FROM {$table} WHERE $where";
        // echo $query;
            $req=$this->pdo->prepare($query);
            $req->execute($params);
            return $req->errorInfo();
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