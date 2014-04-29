<?php

require_once dirname(__FILE__) . '/../third-party/NotORM.php';
require_once dirname(__FILE__) . '/../configs/db.php';

class uup {

    private $pdo;
    private $db;

    public function __construct() {

        $this->pdo = new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST,DB_USER,DB_PASS);
        $this->db = new NotORM($this->pdo);

    }

    public function getMaxViewedMessage() {

        $view = $this->db->messages()->max("view");
        $data = $this->db->messages("view = ?",$view)->fetch();
        $message = array(
            "id"        => $data["id"],
            "content"   => $data["content"],
            "code"      => $data["code"],
            "date"      => $data["date"],
            "view"      => $data["view"]
        );
        return $message;

    }

    private function upView($message) {

        $data = array(
            "view" => $message['view']+1
        );
        return $message->update($data);

    }

    public function getMessageDateWithId($id) {

        $data = $this->db->messages()->where('id', $id)->fetch();
        return $data['date'];

    }

    public function getMessageViewWithId($id) {

        $data = $this->db->messages()->where('id', $id)->fetch();
        return $data['view'];

    }

    public function getMessageWithCode($code) {

        if($data = $this->db->messages()->where('code', $code)->fetch()) {
            $this->upView($data);
            $message = array(
                "id"        => $data["id"],
                "content"   => $data["content"],
                "code"      => $data["code"],
                "date"      => $data["date"],
                "view"      => $data["view"]+1 // upView
            );
            return $message;
        } else {
            $error = array(
                "error"     => "NO_CONTENT"
            );
            return $error;
        }

    }

    public function getMessagesByIp($ip) {

        $messageArray = array();
        $messages = $this->db->messages()->where('ip', $ip);
        foreach ($messages as $data) {
            $message = array(
                "id"        => $data["id"],
                "content"   => $data["content"],
                "code"      => $data["code"],
                "date"      => $data["date"],
                "view"      => $data["view"]
            );
            $messageArray[] = $message;
        } return $messageArray;

    }

    public function saveContent($content) {

        if($data = $this->db->messages()->insert($content)) {
            $message = array(
                "id"        => $data["id"],
                "content"   => $data["content"],
                "code"      => $data["code"],
                "date"      => $this->getMessageDateWithId($data["id"]),
                "view"      => $this->getMessageViewWithId($data["id"])
            );
            return $message;
        } else {
            $error = array(
                "error"     => "SAVE_ERROR"
            );
            return $error;
        }

    }

    public function errorNotFound() {
        $error = array(
            "error"     => "NOT_FOUND"
        );
        return $error;
    }

    public function status() {
        $status = array(
            "version"     => "1.0"
        );
        return $status;
    }

    public function randomString() {
        $salt = "abcdefghijklmnopqrstuvwxyz1234567890";
        $rand = '';
        $i = 0;
        while ($i < 8) {
            $num = rand() % strlen($salt);
            $tmp = substr($salt, $num, 1);
            $rand = $rand . $tmp;
            $i++;
        }
        return $rand;
    }

}


