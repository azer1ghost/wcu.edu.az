<?php

class Database extends PDO {


    public function __construct($DB_TYPE, $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS) {
        try {
            parent::__construct($DB_TYPE . ':host=' . $DB_HOST . ';charset=utf8;dbname=' . $DB_NAME, $DB_USER, $DB_PASS);
            parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e){
            die('ERROR: could not connect to mysql');
            //die('ERROR: '. $e->getMessage());
        }
    }

    /**
     * select
     * @param string $sql An SQL string
     * @param array $array Paramters to bind
     * @param constant $fetchMode A PDO Fetch mode
     * @return mixed
     */
    public function select($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC) {
        //print"<pre>";
        //echo $sql;
        //print"</pre>";
        $sth = $this->prepare($sql);
        foreach ($array as $key => $value) {
            $sth->bindValue("$key", $value);
        }

        $sth->execute();
        return $sth->fetchAll($fetchMode);
    }

    /**
     * select count
     * @param string $sql An SQL string
     * @param array $array Paramters to bind
     * @param constant $fetchMode A PDO Fetch mode
     * @return mixed
     */
    public function selectCount($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC) {
        $sth = $this->prepare($sql);
        foreach ($array as $key => $value) {
            $sth->bindValue("$key", $value);
        }

        $sth->execute();
        $result = $sth->fetchAll($fetchMode);
        return $result[0]['count'];
    }

    /**
     * insert
     * @param string $table A name of table to insert into
     * @param string $data An associative array
     */
    public function insert($table, $data) {
        ksort($data);

        $fieldNames = implode('`, `', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));

        $sth = $this->prepare("INSERT INTO $table (`$fieldNames`) VALUES ($fieldValues)");

        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }

        if (!$sth->execute()) {
            $error = $sth->errorInfo();
            return array('mysql_error' => $error[2]);
        } else {
            return $this->lastInsertId();
        }

        if (!$sth->execute()) {
            $error = $sth->errorInfo();
            return array('mysql_error' => $error[2]);
        } else {
            return 1;
        }
    }

    /**
     * update
     * @param string $table A name of table to insert into
     * @param string $data An associative array
     * @param string $where the WHERE query part
     */
    public function update($table, $data, $where) {
        ksort($data);

        $fieldDetails = NULL;
        foreach ($data as $key => $value) {
            $fieldDetails .= "`$key`=:$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');
        $query = "UPDATE $table SET $fieldDetails WHERE $where";
        $sth = $this->prepare($query);

        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }

        if (!$sth->execute()) {
            $error = $sth->errorInfo();
            return array('mysql_error' => $error[2]);
        } else {
            return 1;
        }
    }

    /**
     * delete
     *
     * @param string $table
     * @param string $where
     * @return integer Affected Rows
     */
    public function delete($table, $where) {
        $query = "DELETE FROM $table WHERE $where";
        $result = $this->exec($query);
        if (!$result) {
            $error = $this->errorInfo();
            return array('mysql_error' => $error[2]);
        } else {
            return $result;
        }
    }

}