<?php

class SupportersPage extends Page
{

}

class SupportersPage_Controller extends Page_Controller
{
    protected function setupForms()
    {
        $this->addFormWidget('Supporter');
    }

    public function getHomepage()
    {
        return HomePage::get()->first();
    }

    public function getSupportersCount()
    {
        return Supporter::confirmed()->count();
    }

    public function getSupporters()
    {
        return Supporter::confirmed()->where('Name IS NOT NULL AND `Show` = 1')->sort('ID DESC')->limit(10);
    }
}
