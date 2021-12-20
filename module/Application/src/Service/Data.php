<?php

namespace Application\Service;

class Data
{
  public function getTable()
  {
    $string = file_get_contents(__DIR__ . "/data.json");
    $json = json_decode($string, true);


    return $json;
  }
}
