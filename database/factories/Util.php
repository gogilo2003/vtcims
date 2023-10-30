<?php

namespace Database\Factories;

trait Util
{
    public function placeholder($dir)
    {
        $t = rand(0, 1);
        if ($t) {
            $url = 'https://placebeard.it/256x256';
            $filename = time() . '.jpg';
            $saveto = $dir . $filename;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $raw = curl_exec($ch);
            curl_close($ch);
            if (file_exists($saveto)) {
                unlink($saveto);
            }

            $fp = fopen($saveto, 'x');
            fwrite($fp, $raw);
            fclose($fp);
            return $filename;
        } else {
            return null;
        }
    }
}
