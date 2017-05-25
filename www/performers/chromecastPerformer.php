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

    function playVideo($parameter)
    {
        shell_exec('python ../performers/playVideo.py ' . $parameter);
    }
}