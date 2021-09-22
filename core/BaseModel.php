<?php

namespace core;

use core\ConnectDB;

abstract class BaseModel
{
    static $table = 'table';
    static $sql_str = '';

    abstract public function rules(): array;

    public function loadPost()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;
            $fields = get_object_vars($this);
            foreach ($fields as $key => $field) {
                if (isset($data[$key])) {
                    $this->{$key} = $data[$key];
                }
            }
            return true;
        }
        return false;
    }

    public function loadGet()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $data = $_GET;
            $fields = get_object_vars($this);
            foreach ($fields as $key => $field) {
                if (isset($data[$key])) {
                    $this->{$key} = $data[$key];
                }
            }
            return true;
        }
        return false;
    }

    public function validate()
    {
        $error = true;
        $error_message = '';
        $rules = $this->rules();
        foreach ($rules as $key => $fields) {
            switch ($key) {
                case 'email':
                    foreach ($fields as $field) {
                        if (!filter_var($this->$field, FILTER_VALIDATE_EMAIL)) {
                            $error_message .= "$field is invalid email <br>";
                            $error = $error && false;
                        }
                    }
                    break;
                case 'required':
                    foreach ($fields as $field) {
                        if ($this->$field == '') {
                            $error_message .= "$field is required <br>";
                            $error = $error && false;
                        }
                    }
                    break;

                    // !case boolean, float, integer, string

                case 'string':
                    foreach ($fields as $field) {
                        if (!is_string($this->$field) || trim($this->$field) == "") {
                            $error_message .= "$field not string <br>";
                            $error = $error && false;
                        }
                    }
                    break;

                case 'integer':
                    foreach ($fields as $field) {
                        if (!is_int(intval($this->$field))) {
                            $error_message .= "$field not integer <br>";
                            $error = $error && false;
                        }
                    }
                    break;
                case 'int':
                    foreach ($fields as $field) {
                        if (!is_int(intval($this->$field))) {
                            $error_message .= "$field not integer <br>";
                            $error = $error && false;
                        }
                    }
                    break;

                case 'float':
                    foreach ($fields as $field) {
                        if (!is_float(floatval($this->$field))) {
                            $error_message .= "$field not float <br>";
                            $error = $error && false;
                        }
                    }
                    break;

                case 'boolean':
                    foreach ($fields as $field) {
                        // if (!is_bool(boolval($this->$field))) {
                        // if (gettype(boolval($this->{$field})) != $key) {
                        if (!is_bool(filter_var($this->$field, FILTER_VALIDATE_BOOLEAN))) {
                            // $error_message .= "$field expected $key recived " . gettype($this->{$field}) . ". <br>";
                            $error_message .= "$field not boolean <br>";
                            $error = $error && false;
                        }
                    }
                    break;
                case 'bool':
                    foreach ($fields as $field) {
                        if (!is_bool($this->$field)) {
                            $error_message .= "$field not boolean <br>";
                            $error = $error && false;
                        }
                    }
                    break;
            }
        }

        if (!isset($_SESSION)) session_start();
        if (!$error) $_SESSION['error'] = $error_message;

        return $error;
    }
    public function save()
    {
        $fields = get_object_vars($this);
        $keys = [];
        $values = [];
        $values_update = [];
        foreach ($fields as $key => $value) {
            if ($value) {
                $keys[] = "`$key`";
                $values[] = ":$key";
                $values_update[] = "`$key`=:$key";
            }
        }

        try {
            $conn = ConnectDB::connect();
            $table = static::$table;
            if ($this->id) {
                $sql_set = implode(', ', $values_update);
                $stmt = $conn->prepare("UPDATE `$table` SET $sql_set WHERE id=" . $this->id);
            } else {
                $keys = implode(', ', $keys);
                $values = implode(', ', $values);
                $stmt = $conn->prepare("INSERT INTO `$table` ($keys) VALUES ($values)");
            }
            foreach ($fields as $key => $value) {
                if (isset($value)) {
                    $stmt->bindParam(":$key", $fields[$key]);
                }
            }
            // die(var_dump($fields));

            if ($stmt->execute()) {
                if (!$this->id) $this->id = $conn->lastInsertId();
                return $this;
            }
        } catch (\PDOException $e) {
            // } catch (\Throwable $e) {
            echo $e->getMessage();
            // echo $e->();
        }

        return false;
    }
    public function update() // for taskmodel-
    {
        $fields = get_object_vars($this);
        $values_update = [];
        foreach ($fields as $key => $value) {
            if (!$value) {
                $values_update[] = "`$key`= NULL";
            } else {
                $values_update[] = "`$key`= '$value'";
            }
        }

        try {
            $conn = ConnectDB::connect();
            $table = static::$table;
            if ($this->id) {
                $sql_set = implode(', ', $values_update);
                $stmt = $conn->prepare("UPDATE `$table` SET $sql_set WHERE id=" . $this->id);
            }

            if ($stmt->execute()) {
                if (!$this->id) $this->id = $conn->lastInsertId();
                return $this;
            }
        } catch (\PDOException $e) {
            // echo $e->getMessage();
        }

        return false;
    }

    static public function find()
    {
        $table = static::$table;
        static::$sql_str = "SELECT * FROM `$table`";
        return new static;
    }
    public function where($params = [])
    {
        if ($params) {
            $sql = [];
            foreach ($params as $key => $value) {
                $value = htmlspecialchars($value);
                $sql[] = "`$key` = '$value'";
            }
            static::$sql_str .= " WHERE " . implode(' AND ', $sql);
        }
        return $this;
    }
    public function order($params = [])
    {
        if ($params) {
            $sql = [];
            $table = static::$table;
            foreach ($params as $key => $value) {
                if ($value) {
                    $sql[] = "`$table`.`$value`";
                }
            }
            $sql = implode(', ', $sql);
            if ($sql) {
                static::$sql_str .= " ORDER BY " . $sql;
            }
        }
        return $this;
    }
    public function one()
    {
        try {
            $conn = ConnectDB::connect();
            $stmt = $conn->prepare(static::$sql_str);

            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            // echo $e->getMessage();
        }
        if ($result) {
            $obj = new static;
            foreach ($result as $key => $value) {
                $obj->{$key} = $result->{$key};
            }
            return $obj;
        }
        return $result;
    }
    public function all()
    {
        try {
            $conn = ConnectDB::connect();
            $stmt = $conn->prepare(static::$sql_str);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_OBJ);
            return $result;
        } catch (\PDOException $e) {
            // echo $e->getMessage();
        }
    }
    public static function delete($params = [])
    {
        try {
            $conn = ConnectDB::connect();
            $table = static::$table;
            $sql_where = [];
            foreach ($params as $key => $value) {
                $sql_where[] = "`$key` = '$value'";
            }
            $sql_where = implode(' AND ', $sql_where);
            $sql = "DELETE FROM `$table` WHERE $sql_where";
            $stmt = $conn->prepare($sql);
            return $stmt->execute();
        } catch (\PDOException $e) {
            // echo $e->getMessage();
        }
    }

    static public function count() // working or not?
    {
        try {
            $conn = connectDB::connectDB();
            $table = static::$table;
            $stmt = $conn->prepare("SELECT COUNT(*) as `count` FROM $table");
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_OBJ);
            return $result->count;
        } catch (\PDOException $e) {
            // echo $e->getMessage();
        }
    }
}
