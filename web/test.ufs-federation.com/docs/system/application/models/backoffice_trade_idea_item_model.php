<?php

class trade_idea_item extends DataMapper {
  
  var $prefix = 'trade_idea_';
  var $table = 'items';
  var $has_many = array(
      'level_value' => array (
          'class' => 'trade_idea_level_value',
          'other_field' => 'item',
          'join_self_as' => 'item',
          'join_other_as' => 'level_value',
          'join_table' => 'trade_idea_items_level_values'
      ),
      'tool' => array (
          'class' => 'trade_idea_tool',
          'other_field' => 'item',
          'join_self_as' => 'item',
          'join_other_as' => 'tool'
      )
  );
  
  function __construct($id = NULL)
  {
      parent::__construct($id);
  }
  
}
