<?php

class PostsAgent {

    private $db;

    function __construct($db) {

        $this->db = $db;

    }

    public function SendPost($user_id, $post_text){

        $this->db->Query('INSERT INTO posts(user_id, post_text) VALUES(?, ?)', array($user_id, $post_text));
    }
    
    public function LoadPosts($user_ids, $limit) {
        $conn = $this->db->GetConnection();


        $plist = ':id_' . implode(',:id_', array_keys($user_ids));

        $query = "SELECT posts.post_id, posts.post_text,posts.post_date, users.user_fname,users.user_lname, users.user_subname, users.user_avatar FROM posts JOIN users ON posts.user_id = users.user_id
                               WHERE posts.user_id IN (";
        
        $ids = array(1,2,3,7,8,9);

        $comma = '';

        for($i=0; $i<count($user_ids); $i++){
            $query .= $comma.':id'.$i; 
            $comma = ',';
        }

        $query .= ') ORDER BY posts.post_id DESC LIMIT :plimit';

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':plimit', $limit, PDO::PARAM_INT);

        foreach($user_ids as $key => $id) {
            $stmt->bindValue(':id'.$key, $id);
        }

        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    
}
?>