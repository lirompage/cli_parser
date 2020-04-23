<?php

namespace app\Controllers;

class Report
{
    public static function saveImgsToFile($url, $allImages)
    {
        $domain = parse_url($url);
        $parseFile = $domain['host'] . ".csv";

        $fd = fopen('app/ParseFiles/' . $parseFile, 'a');

        $str = "";
        foreach ($allImages as $img)
        {
            $str = $str. $img[1][0] . "\r\n";
        }
        fwrite($fd, $str);
        fclose($fd);

    }

    public function showReport($url)
    {
        $domain = parse_url($url);

        $fileData = fopen('app/ParseFiles/'.$domain['host'] . '.csv', "r");

        echo "\nAnalysis for ".$domain['host'] . "\n
        <<<---Start of list--->>>\n\n";

            for ($i = 1; (!feof($fileData)); $i++)
            {
                $urls = fgets($fileData);
                echo $i . '. ' . $urls;
            }

        echo "\n
            <<<---end of list--->>>\n\n";
        fclose($fileData);
    }
}