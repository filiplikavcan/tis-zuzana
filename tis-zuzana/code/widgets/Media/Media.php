<?php

namespace TisZuzana\Widgets\Media;

use Cleopas\Widgets\IRequestable;
use Cleopas\Widgets\Widget;
use MediaCategory;

class Media extends Widget implements IRequestable
{
    protected $is_data_cached = false;

    public function getCategories()
    {
        $active_category_slug = $this->param('active_category_slug', '');

        yield array(
            'Title' => 'Všetky',
            'Slug' => '',
            'IsActive' => '' == $active_category_slug,
            'Link' => $this->parent->Link(),
        );

        foreach (MediaCategory::get()->sort('Sort') as $media_category)
        {
            yield array(
                'Title' => $media_category->Title,
                'Slug' => $media_category->Slug,
                'IsActive' => $media_category->Slug == $active_category_slug,
                'Link' => $this->parent->Link($media_category->Slug),
            );
        }
    }

    public function getItems()
    {
        $active_category = MediaCategory::get()->filter(array('Slug' => $this->param('active_category_slug', '')))->first();

        if ($active_category instanceof MediaCategory)
        {
            return \Media::get()->filter(array('CategoryID' => $active_category->ID))->sort('Sort');
        }
        else
        {
            return \Media::get()->sort('Sort');
        }
    }
}