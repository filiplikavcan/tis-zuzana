<?php

class MenuItem extends DataObject
{
    private static $db = array(
        'Title' => 'Varchar(1000)',
        'Link' => 'Varchar(100)',
        'SortOrder' => 'Int',
    );

    private static $has_one = array(
        'Parent' => 'HomePage'
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('SortOrder');
        $fields->removeByName('ParentID');

        return $fields;
    }
}