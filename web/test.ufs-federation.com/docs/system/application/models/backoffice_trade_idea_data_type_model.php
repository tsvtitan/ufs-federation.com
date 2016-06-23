<?php

class trade_idea_data_type extends DataMapper {
  
  var $prefix = 'trade_idea_';
  var $table = 'data_types';
  var $has_many = array(
      'level_value' => array(
          'class' => 'trade_idea_level_value',
          'other_field' => 'data_type',
          'join_self_as' => 'data_type',
          'join_other_as' => 'level_value'
      )
  );
  
  function __construct($id = NULL)
  {
      parent::__construct($id);
  }
  
}
