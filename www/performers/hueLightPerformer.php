<?php

namespace Ewetasker\Performer;

/**
* 
*/
class HueLightPerformer
{
    private $hue_light_performer;
    
    function __construct()
    {
        return $this->hue_light_performer;
    }

    function turnOn()
    {
        #HAY QUE CAMBIAR LA DIRECCION IP http://192.168.0.158
        $url = 'http://192.168.0.158/api/ISrD4jnQ66SwRNZAhPrLqrOY1G1nHAJ8tA4Iw-rT/lights/1/state';
        $data = array(
            'on' => true
        );
        $content = json_encode($data);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
            );
        curl_exec($curl);
        curl_close($curl);
    }

    function turnOff()
    {
        #HAY QUE CAMBIAR LA DIRECCION IP http://192.168.0.158
        $url = 'http://192.168.0.158/api/ISrD4jnQ66SwRNZAhPrLqrOY1G1nHAJ8tA4Iw-rT/lights/1/state';
        $data = array(
            'on' => false
        );
        $content = json_encode($data);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
            );            
        curl_exec($curl);
        curl_close($curl);
    }

    function setBrightness($bright)
    {
        #$bright es un múltiplo de 10 entre 0 y 100 para que el valor maximo de brillo sea el 254
        $bri = round($bright * 254 / 100);
        #HAY QUE CAMBIAR LA DIRECCION IP http://192.168.0.158
        $url = 'http://192.168.0.158/api/ISrD4jnQ66SwRNZAhPrLqrOY1G1nHAJ8tA4Iw-rT/lights/1/state';
        $data = array(
            'bri' => $bri
        );
        $content = json_encode($data);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
            );
        curl_exec($curl);
        curl_close($curl);
    }
}
