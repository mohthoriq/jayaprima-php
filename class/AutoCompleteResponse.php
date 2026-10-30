<?php
class AutoCompleteSuggestions{
    /*  @type mixed */
    public $data;
    /*  @type mixed */
    public $value;

    function __construct($data, $value){
        $this->data = $data;
        $this->value = $value;
    }
}
class AutoCompleteResponse{
    public string $query = 'Unit';
    /*  @type AutoCompleteSuggestions[] */
    public array $suggestions = array();
}