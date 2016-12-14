<?php

class SupportersPage_Controller extends Page_Controller
{
    private static $url_handlers = array(
        'load' => 'loadMore'
    );

    private static $allowed_actions = array(
        'loadMore',
    );

    protected function setupForms()
    {
        $this->addFormWidget('Supporter');
    }

    public function setupWidgets()
    {
        parent::setupWidgets();

        $supporters_widget = $this->addWidget('Supporters', TisZuzana\Widgets\Supporters\Supporters::create()
            ->setTitle($this->Title));
    }

    public function getHomepage()
    {
        return HomePage::get()->first();
    }

    public function getVipSupporters()
    {
        return VipSupporter::get();
    }
}