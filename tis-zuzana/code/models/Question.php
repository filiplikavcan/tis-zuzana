<?php

class Question extends DataObject
{
    private static $db = array(
        'Title' => 'Varchar(1000)',
        'Content' => 'HTMLText',
        'IsAnswered' => 'Boolean',
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