<?php

namespace Ewetasker\Performer;

/**
* 
*/
class ChromecastPerformer
{
    private $chromecast_perfomer;
    
    function __construct()
    {
        return $this->chromecast_perfomer;
    }

    function playVideo()
    {
        shell_exec('python ../performers/prueba.py');
    }
}