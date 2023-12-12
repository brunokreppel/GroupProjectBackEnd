<?php
    function clean($data){
        $data = trim($data);
        $data = htmlspecialchars($data);
        $data = strip_tags($data);
        return $data;
    }