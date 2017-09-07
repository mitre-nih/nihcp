<?php

namespace Nihcp\Entity;

class PirFormField {

    public $name = "";
    public $character_limit = "";
    public $input_type = "";
    public $input_vars = "";

    function __construct($name, $input_type, $character_limit = 0, $input_vars = [], $alt_text = '') {
        $this->name = $name;
        $this->character_limit = $character_limit;
        $this->input_type = $input_type;
        $this->input_vars = $input_vars;
        $this->alt_text = $alt_text;
    }


}