<?php

use Nette\Utils\Strings;

class MediaCategory extends DataObject
{
    private static $db = array(
        'Title' => 'Varchar(255)',
        'Slug' => 'Varchar(255)',
        'Sort' => 'Int',
    );

    private static $summary_fields = array(
        'Title' => 'Názov',
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('Slug');
        $fields->removeByName('Sort');

        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        $this->Slug = Strings::webalize($this->Title);
    }
}