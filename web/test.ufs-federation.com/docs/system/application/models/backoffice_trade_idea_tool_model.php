<?php

class trade_idea_tool extends DataMapper {
  
  var $prefix = 'trade_idea_';
  var $table = 'tools';  
  var $has_one = array(
      'item' => array(
          'class' => 'trade_idea_item',
          'other_field' => 'tool',
          'join_self_as' => 'tool',
          'join_other_as' => 'item'
      )
  );
  var $has_many = array(
      'level_value' => array (
          'class' => 'trade_idea_level_value',
          'other_field' => 'tool',
          'join_self_as' => 'tool',
          'join_other_as' => 'level_value',
          'join_table' => 'trade_idea_tools_level_values'
      )
  );
  
  function __construct($id = NULL)
  {
      parent::__construct($id);
  }
  
}

