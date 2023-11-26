<?php
    function pre($data)
    {
        echo '<pre>';
        print_r($data);
        echo'</pre>';
    }
    function pre_exit($data)
    {
        echo '<pre>';
        print_r($data);
        echo'</pre>';
        exit;
    }
?>