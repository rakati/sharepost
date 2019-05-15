<?php

Class Posts extends Controller
{

    public function __construct(){
        // denide access for not loged in to enter to this page
        if (!isLogedIn()){
            redirect('users/login');
        }
        // load model that we need
        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
    }

    public function index(){
        // Get posts
        $post = $this->postModel->getPosts();

        $data = [
            'posts' => $post
        ];

        $this->view('posts/index', $data);
    }

    // add Post
    public function add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Sanitize the post array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'body_err' => ''
            ];

            // Validate title
            if(empty($data['title'])){
                $data['title_err'] = 'Please enter a title';
            }

            // Validate body
            if(empty($data['body'])){
                $data['body_err'] = 'Please write somthing';
            }

            // Make sure no errors
            if(empty($data['title_err']) and empty($data['body_err'])){
                if ($this->postModel->addPost($data)){
                    flash('post_message', 'post Added');
                    redirect('posts');
                }
            } else {
                // Load view with errors
                $this->view('posts/add', $data);
            }
        }
        $data = [
            'title' => '',
            'body' => ''
        ];

        $this->view('posts/add', $data);
    }

    // edit
    public function edit($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Sanitize the post array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'body_err' => ''
            ];

            // Validate title
            if(empty($data['title'])){
                $data['title_err'] = 'Please enter a title';
            }

            // Validate body
            if(empty($data['body'])){
                $data['body_err'] = 'Please write somthing';
            }

            // Make sure no errors
            if(empty($data['title_err']) and empty($data['body_err'])){
                if ($this->postModel->updatePost($data)){
                    flash('post_message', 'Post updated');
                    redirect('posts');
                }
            } else {
                // Load view with errors
                $this->view('posts/edit', $data);
            }
        } else {
            // get existing post from model
            $post = $this->postModel->getPostById($id);
            // Check for owner
            if ($post->user_id != $_SESSION['user_id']){
                redirect('posts');
            }
            $data = [
                'id' => $id,
                'title' => $post->title,
                'body' => $post->body
            ];
    
            $this->view('posts/edit', $data);
        }
    }

    // show details
    public function show($id){
        $post = $this->postModel->getPostById($id);
        $user = $this->userModel->getUserById($post->user_id);
        $data = [
            'post' => $post,
            'user' => $user
        ];

        $this->view('posts/show', $data);
    }

    // Delete posts
    public function delete($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            // Check for owner
            if ($post->user_id != $_SESSION['user_id']){
                redirect('posts');
            }
            if ($this->postModel->deletePost($id)){
                flash('post_message', 'Post Removed');
                redirect('posts');
            } else {
                die ('something went wrong');
            }
        } else {
            redirect('posts');
        }
    }
}

?>
