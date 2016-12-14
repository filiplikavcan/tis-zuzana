<?php

class MediaPage_Controller extends Page_Controller
{
    private static $url_handlers = array(
        '$active_category_slug' => 'showCategory'
    );

    private static $allowed_actions = array(
        'showCategory',
    );

    public function setupWidgets()
    {
        parent::setupWidgets();

        $this->addWidget('Media', TisZuzana\Widgets\Media\Media::create());
    }

    public function showCategory(SS_HTTPRequest $request)
    {
        $this->getWidget('Media')->request($request);
    }
}