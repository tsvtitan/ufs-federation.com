<?php

class trade_idea_level_type extends DataMapper {
  
  var $prefix = 'trade_idea_';
  var $table = 'level_types';
  var $has_many = array("level_value");
  
  function __construct($id = NULL)
  {
      parent::__construct($id);
  }
  
}
