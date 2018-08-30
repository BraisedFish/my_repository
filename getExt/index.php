<?php

$json_str = file_get_contents('./data.json');
$data = json_decode($json_str, true);

global $ext_list;
$ext_list = [];

getExt($data);
//print_r($ext_list);


function getExt($data = [])
{
    global $ext_list;
    if ($data && is_array($data)) {
        foreach ($data as $key => $val) {
//            if ($key == 'mblog') continue;
            if ($val && is_array($val)) {
                getExt($val);
            } elseif ($key === 'ext') {
//                $ext_list[] = $val;
                echo $val;
                echo "\n";
            }
        }
    }
}