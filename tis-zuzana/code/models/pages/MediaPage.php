<?php

class MediaPage extends Page
{
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab('Root.Médiá', new GridField('Media', 'Média', Media::get(), $this->getGridFieldConfig()));
        $fields->addFieldToTab('Root.Kategórie', new GridField('MediaCategory', 'Kategórie', MediaCategory::get(), $this->getGridFieldConfig()));

        return $fields;
    }

    public function getMediaCategories()
    {
        foreach (MediaCategory::get() as $media_category)
        {
            yield array(
                'Title' => $media_category->Title,
                'Link' => \Nette\Utils\Strings::webalize($media_category->Title)
            );
        }
    }
}
