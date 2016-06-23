<?php

class trade_idea_type extends DataMapper {
  
  var $prefix = 'trade_idea_';
  var $table = 'types';
  var $has_many = array("item");
  
  function __construct($id = NULL)
  {
      parent::__construct($id);
  }
  
}

