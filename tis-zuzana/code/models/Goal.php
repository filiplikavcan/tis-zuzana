<?php

class Goal extends DataObject
{
    private static $db = array(
        'Title' => 'Varchar(1000)',
        'Content' => 'HTMLText',
        'Sort' => 'Int',
    );

    private static $has_one = array(
        'Parent' => 'Page'
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('Sort');
        $fields->removeByName('ParentID');

        return $fields;
    }
}