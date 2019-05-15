<?php

Class Pages extends Controller{
    public function __construct(){
        
    }

    public function index(){
        if (isLogedIn()){
            redirect('posts');
        }
        $data = [
            'title' => 'SharePosts',
            'description' => 'Simple social network build on the php mvc framework'
        ];

        $this->view('index', $data);
    }

    public function about(){
        $data = [
            'title' => 'About Us',
            'description' => 'App to share posts with other users'
        ];
        $this->view('about', $data);
    } 
}

?>