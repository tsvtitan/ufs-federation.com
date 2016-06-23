<?php

class trade_idea_level_value extends DataMapper {
  
  var $prefix = 'trade_idea_';
  var $table = 'level_values';
  var $has_one = array(
      'data_type' => array(
          'class' => 'trade_idea_data_type',
          'other_field' => 'level_value',
          'join_self_as' => 'level_value',
          'join_other_as' => 'data_type'
      )
  );
  var $has_many = array(
      'item' => array (
          'class' => 'trade_idea_item',
          'other_field' => 'level_value',
          'join_self_as' => 'level_value',
          'join_other_as' => 'item',
          'join_table' => 'trade_idea_items_level_values'
      ),
      'tool' => array (
          'class' => 'trade_idea_tool',
          'other_field' => 'level_value',
          'join_self_as' => 'level_value',
          'join_other_as' => 'tool',
          'join_table' => 'trade_idea_tools_level_values'
      )
  );
  
  function __construct($id = NULL)
  {
      parent::__construct($id);
  }  
}
