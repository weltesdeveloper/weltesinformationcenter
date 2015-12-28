<?php

class DBConfig {

    private $ip = "192.168.100.70";
    private $database = "WELTES";
    private $username = "WELTESADMIN";
    private $password = "weltespass";
    private $conn = null;
    private $response = array();

    public function Connection() {
        $this->conn = oci_connect($this->username, $this->password, $this->ip . "/" . $this->database);
        return $this->conn;
    }

    public function SelectFrom($query) {
        $conn = $this->Connection();
        $parse = oci_parse($conn, $query);
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($this->response, $row);
        }
        return $this->response;
    }

    public function UpdateTable($query) {

        $conn = $this->Connection();
        $parse = oci_parse($conn, $query);
        $execute = oci_execute($parse);
        if ($execute) {
//            oci_commit($conn);
            array_push($this->response, "SUKSES UPDATE");
        } else {
//            oci_rollback($conn);
            array_push($this->response, "GAGAL UPDATE" . oci_error());
        }
        return $this->response;
    }

    public function InserTable($query) {
        $conn = $this->Connection();
        $parse = oci_parse($conn, $query);
        $execute = oci_execute($parse);
        if ($execute) {
//            oci_commit($conn);
            array_push($this->response, "SUKSES INSERT");
        } else {
//            oci_rollback($conn);
            array_push($this->response, "GAGAL INSERT" . oci_error());
        }
        return $this->response;
    }
    
    
    public function DeleteTable($query){
        $conn = $this->Connection();
        $parse = oci_parse($conn, $query);
        $execute = oci_execute($parse);
        if ($execute) {
//            oci_commit($conn);
            array_push($this->response, "SUKSES DELETE");
        } else {
//            oci_rollback($conn);
            array_push($this->response, "GAGAL DELETE" . oci_error());
        }
        return $this->response;
    }
            
}
