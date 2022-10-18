<?php


namespace App\Services\History;


class ColumnChange
{

    public $body;

    public function __construct($body) {

        $this->body = $body;
    }
}
