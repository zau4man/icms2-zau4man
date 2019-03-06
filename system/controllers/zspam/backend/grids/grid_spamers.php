<?php

function grid_spamers($controller){

    $options = array(
        'is_sortable' => true,
        'is_filter' => false,
        'is_pagination' => true,
        'is_draggable' => false,
        'order_by' => 'id',
        'order_to' => 'desc',
        'show_id' => true
    );

    $columns = array(
        'id' => array(
            'title' => 'id',
            'width' => 30
        ),
        'nickname' => array(
            'title' => 'Никнейм',
            'href' => href_to('admin', 'users', array('edit', '{user_id}')),
        ),
        'email' => array(
            'title' => 'E-mail'
        ),
    );

    $actions = array(
        
    );

    return array(
        'options' => $options,
        'columns' => $columns,
        'actions' => $actions
    );

}

