<?php

class UserAgent{

    private $db;

    function __construct($db) {

        $this->db = $db;

    }

    public function Register($ufName,$ulName, $uPass, $uEmail) {

        $uHashPass = password_hash($uPass, PASSWORD_DEFAULT);

        $this->db->Query('INSERT INTO users(user_fname, user_lname, user_email, user_pass) VALUES(?, ?, ?, ?)', array($ufName, $ulName, $uEmail, $uHashPass));

    }

    public function Login($uEmail, $uPass ) {

        $row = $this->db->Query('SELECT * FROM users WHERE user_email = ? LIMIT 1', array($uEmail));

        if(password_verify($uPass, $row['user_pass'])) {

            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['friends'] = $this->GetFriendsIds($_SESSION['user_id']);
            return true;

        }else {

            return false;

        }
    }
    public function GetUser($user_id){
        return $this->db->Query('SELECT user_fname,user_lname, user_subname, user_email ,user_avatar FROM users WHERE user_id = ?', array($user_id));
    }
    public function Logout($location) {

        session_destroy();
        unset($_SESSION['user_id']);
        unset($_SESSION['last']);
        unset($_SESSION['friends']);
        unset($_SESSION['lastpage']);
        header('Location: ' . $location);
        exit();
        return true;

    }

    public function IsLoggedIn($user_id) {
        
        if(isset($user_id)) {

            return true;

        }
    }

    public function Search($searchtext, $user_id){
        $searchtext = str_replace(' ', '', $searchtext);
        $searchtext .= '%';
        $result = $this->db->QueryAll("SELECT user_id, user_fname, user_lname, user_subname, user_avatar FROM users WHERE CONCAT( user_fname, user_lname ) LIKE ? OR CONCAT( user_lname, user_fname ) LIKE ?", array($searchtext, $searchtext));
        foreach($result as $key => $params){
            if($params['user_id'] == $user_id){
                unset($result[$key]);
            }
        }
        return $result;
    }
    public function GetFriends($friend_ids, $user_id){

        $conn = $this->db->GetConnection();

        $plist = ':id_' . implode(',:id_', array_keys($friend_ids));

        $query = "SELECT user_id, user_fname, user_lname, user_subname, user_avatar FROM users WHERE user_id IN (";

        $comma = '';

        for($i=0; $i<count($friend_ids); $i++){
            $query .= $comma .':id'.$i; 
            $comma = ',';
        }

        $query .= ')';

        $stmt = $conn->prepare($query);

        foreach($friend_ids as $key => $id) {
            $stmt->bindValue(':id'.$key, $id);
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function AddFriend($friend_id, $user_id){
        $this->db->Query('INSERT INTO friendlist (user_id, friend_id) VALUES (?, ?)', array($user_id, $friend_id));
        return $this->GetFriendsIds($user_id);
    }

    public function RemoveFriend($friend_id, $user_id){
        $this->db->Query('DELETE FROM friendlist WHERE user_id = ? AND friend_id = ? ', array($user_id, $friend_id));
        return $this->GetFriendsIds($user_id);
    }

    public function GetFriendsIds($user_id) {
        $arr = $this->db->QueryAll("SELECT friend_id FROM friendlist WHERE user_id = ?", array($user_id));
        return array_column($arr, 'friend_id');
    }

    public function ChangePass($passc, $passn, $passna, $user_id){
        $pass = $this->db->Query('SELECT * FROM users WHERE user_id = ?', array($user_id));
        if (password_verify($passc, $pass['user_pass']) && $passn == $passna) {
            $newpass = password_hash($passn, PASSWORD_DEFAULT);
            $this->db->Query('UPDATE users SET user_pass = ? WHERE user_id = ?', array($newpass, $user_id));
            return true;
        } else {
            return false;
        }
    }
}

?>