<?php

class MyXmlParser {

    private $filePath;
    private $results = [];

    public function __construct($filePath) 
    {
        $this->filePath = $filePath;
    }


    public function regexCount() :int
    {
        $this->results = [];
        $str = file_get_contents($this->filePath);
        preg_match_all('/<modification name="(.*?)"/m', $str, $matches, PREG_SET_ORDER, 0);
        foreach($matches as $match) {
            $this->results[] = $match[1];
        }
        return $this->getResult();
    }

    public function parseXmlCount() 
    {
        $this->results = [];
        $xml = simplexml_load_file($this->filePath);
        $modifications = $xml->xpath("//modification");
        
        foreach($modifications as $modification)
        {
            $this->results[] = $modification->attributes()->name; 
        }
        return $this->getResult();
    }

    private function getResult()
    {
        $this->results = array_unique($this->results);
        return count($this->results);
    }
}

if (isset($argv[1])) {
    $filePath = $argv[1];
    if (file_exists($filePath)) {
        $parser = new MyXmlParser($filePath);
        if (isset($argv[2]) && $argv[2] == "regex") {
            print($parser->regexCount()."\r\n");
        } else {
            print($parser->parseXmlCount()."\r\n");
        }
    } 
} else {
    print("Не удалось открыть файл\r\n");
}

