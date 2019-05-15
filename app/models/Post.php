<?php

Class Post
{
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // get posts from database
    public function getPosts(){
        $this->db->query('SELECT *,
                          posts.id as postId,
                          user.id as userId,
                          posts.created_at as creat_date,
                          posts.created_at as creat_date
                          FROM posts
                          INNER JOIN user
                          ON posts.user_id = user.id
                          ORDER BY posts.created_at DESC');

        $result = $this->db->resultSet();

        return $result;
    }

    // add post
    public function addPost($data){
        // insert info into posts table
        $this->db->query('INSERT INTO posts (user_id, title, body) VALUES(:user_id, :title, :body)');
        // bind values
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':body', $data['body']);
        
        // execute
        if ($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

     // update post
     public function updatePost($data){
        // insert info into posts table
        $this->db->query('UPDATE posts SET title = :title, body= :body WHERE id = :id');
        // bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':body', $data['body']);
        
        // execute
        if ($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // delete post
    public function deletePost($id){
        // insert info into posts table
        $this->db->query('DELETE FROM posts WHERE id = :id');
        
        // bind values
        $this->db->bind(':id', $id);
        
        // execute
        if ($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }


    public function getPostById($id){
        $this->db->query('SELECT * FROM posts WHERE id = :id');
        $this->db->bind(':id', $id);

        // get row result
        $row = $this->db->single();
        return $row;
    }
}

?>