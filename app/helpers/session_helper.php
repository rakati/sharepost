<?php
session_start();

// Flash message helper
// EXAMPLE - flash('register_success', 'you are now registerd');
// DISPLAY IN VIEW - echo flash(register_seccess)
function flash($name = '', $message = '', $class = 'alert alert-success'){
    if (!empty($name)){
        if (!empty($message) and empty($_SESSION[$name])){
            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        } else if (empty($message) and !empty($_SESSION[$name])){
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="' . $class . '"id="msg-flash">' . $_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

function isLogedIn(){
    if (isset($_SESSION['user_id'])){
        return true;
    } else {
        return false;
    }
}

?>
