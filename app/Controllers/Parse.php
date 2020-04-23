<?php

namespace app\Controllers;

use app\Views\MainView;

class Parse
{

    private $siteUrl = [];
    private $innerUrls = [];
    private $flag = true;

    public function checkExists($url)
    {
        $domain = parse_url($url);
        $parseFile = str_replace('/', '',$domain['host'].$domain['path'] . ".csv");

        if (file_exists('app/ParseFiles/' . $parseFile) === true) {
            die ("\n<<<---Url has already been checked--->>>\n\n");
        } else {
            $mainUrl = $this->scheme($url);
            $mainUrlimg = $this->getImages($mainUrl);
            Report::saveImgsToFile($mainUrl, $mainUrlimg);
            $this->startParse($mainUrl);
        }
    }

    private function getImages($url)
    {
        $html = file_get_contents($url);
        preg_match_all('/<img[^>]+>/i', $html, $imgTags);
        $imgLinks = [];

        for ($i = 0; $i < count($imgTags[0]); $i++) {
            preg_match_all('/src=("[^"]*")/i', $imgTags[0][$i], $imgLinks[$i]);
        }

        return $imgLinks;
    }

    public function scheme($url)
    {
        $http = strstr($url, "http");
        if ($http === false) {
            $url = "http://" . $url;
        }
        return $url;
    }

    private function startParse($url)
    {
        $url = $this->scheme($url);
        $html = file_get_contents($url);
        if ($html) {

            array_push($this->innerUrls, $url);

            $domain = parse_url($url);
            preg_match_all('/<a[^>]+>/i', $html, $links);
            $href = [];

            for($i = 0; $i < count($links[0]); $i++) {
                preg_match_all('/href=("[^"]*")/i',$links[0][$i], $href[$i]);

            }

            $urlsByPage = [];

            for ($i = 0; $i < count($href); $i++) {
                $empty = stripos($href[$i][1][0], $domain['host']);

                if ($empty === false) {
                    $href[$i][1][0] = str_replace('"', '', $href[$i][1][0]);
                    array_push($urlsByPage, $domain['host'].$href[$i][1][0]);
                }
            }

            if ($this->flag) {
                $this->siteUrl = array_unique($urlsByPage);
                $this->flag = false;
            } else {
                $this->siteUrl = array_merge($this->siteUrl, array_unique($urlsByPage));
            }

        }

        foreach ($this->siteUrl as $key => $value)
        {
            $protocol = $this->scheme($value);
            preg_match_all('/http:\/\/[^\s]+.(com\/)[^\s]+/i',$protocol,$res);

                if (empty($res[0][0])===false) {
                    echo "Now scanning - " . $res[0][0] . "\n";
                    $img = $this->getImages($res[0][0]);
                    Report::saveImgsToFile($res[0][0], $img);
                }
        }
    }
}