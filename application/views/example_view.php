<?php 
    $referred_username = $this->User_model->get_username_by_referred_by($user->referred_by);
    echo $referred_username;
?>