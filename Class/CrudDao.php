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


        public function getAll($table, $order=null, $ascDesc=null){
            $orderParam="";
            if(isset($order)){
                if(isset($ascDesc) && $ascDesc=="desc"){
                    $orderParam='ORDER BY '.$order . ' DESC';

                }else{
                    $orderParam='ORDER BY '.$order;
                }
            }
            $query='SELECT * FROM '.$table . ' '.$orderParam;
            // echo $query;
            $req=$this->pdo->query($query);
            return $req->fetchAll();
        }


        public function getOneById($table,$id){
            $req=$this->pdo->prepare("SELECT * FROM {$table} WHERE id= :id");
            $req->execute([
                ':id'       =>$id
            ]);
            return $req->fetch();
        }

        public function getOneWhere($table, $param){
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


        public function getMany($table ,$where, $order=null, $ascDesc=null){
            $orderParam="";
            if(isset($order)){
                if(isset($ascDesc) && $ascDesc=="desc"){
                    $orderParam='ORDER BY '.$order . ' DESC';

                }else{
                    $orderParam='ORDER BY '.$order;
                }
            }
            $query='SELECT * FROM '.$table .' ' .$where. ' '.$orderParam;
            // echo $query;
            $req=$this->pdo->query($query);
            return $req->fetchAll();
        }

        public function insertOne($table, $field, $value){
            $req=$this->pdo->prepare("INSERT INTO  $table ({$field}) VALUES (?)");
            $req->execute([
                $value
            ]);
            return $this->pdo->lastInsertId();

        }
        public function insert($table,  $datas){
            $fields=array_keys($datas);
            $strFields=join(', ', $fields);
            $placeholders=":".join(', :', $fields);

            $query="INSERT INTO  $table ({$strFields}) VALUES ({$placeholders})";
            $req=$this->pdo->prepare($query);
            $req->execute($datas);
            return $this->pdo->lastInsertId();
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
            return $req->errorInfo();

            return $req->rowCount();
        }


        public function deleteTable($table){
            $req=$this->pdo->query("DELETE FROM {$table}");
            return $req->errorInfo();
        }

        public function copyTable($tableFrom, $tableDest){
            $req=$this->pdo->query("INSERT INTO $tableDest SELECT * FROM $tableFrom");

            return $req->rowCount();
        }


        public function copyRowById($tableFrom, $tableDest, $id){
            $req=$this->pdo->prepare("INSERT INTO $tableDest SELECT * FROM $tableFrom WHERE id= :id");
            $req->execute([
                ':id'       =>$id
            ]);
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

        public function setPdo($pdo){
            $this->pdo = $pdo;
        }

        public function getTable(){
            $table = $this->table;
            return $table;
        }

        public function setTable($table){
            $this->table = $table;
        }

    }