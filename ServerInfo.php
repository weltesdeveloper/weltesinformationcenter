<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function get_server_cpu_usage(){

    $load = sys_getloadavg();
    return $load[0];

}

echo get_server_cpu_usage();


?>