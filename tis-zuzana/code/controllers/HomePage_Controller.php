<?php

class HomePage_Controller extends Page_Controller
{
    private static $url_handlers = array(
        'confirm/$hash' => 'confirmSupporterEmail'
    );

    private static $allowed_actions = array(
        'confirmSupporterEmail',
    );

    protected $is_data_cached = false;

    public function setupWidgets()
    {
        parent::setupWidgets();

        $this->addWidget('EmailConfirmation', Cleopas\Widgets\Data\Data::create()
            ->IsInvoked(false)
            ->IsSuccess(false));

        $this->addWidget('Supporters', TisZuzana\Widgets\Supporters\Supporters::create());
    }

    protected function setupForms()
    {
        $this->addFormWidget('Supporter');
    }

    public function getCounter()
    {
        return Cleopas\Widgets\Data\Data::create()
            ->Time(function() {
                $start = new DateTime(date('Y-m-d H:i:s'));
                $end = new DateTime('2017-01-05 10:00:00');
                $diff = $start->diff($end);

                if ($diff->format('%R') == '+')
                {
                    return array(
                        'days' => $diff->days,
                        'hours' => $diff->h,
                    );
                }
                else
                {
                    return array(
                        'days' => 0,
                        'hours' => 0,
                    );
                }
            })
            ->Supporters(Supporter::get()->count())
            ->Answers($this->Questions()->filter(array('IsAnswered' => true))->count());
    }

    public function confirmSupporterEmail(SS_HTTPRequest $request)
    {
        $this->getWidget('EmailConfirmation')
            ->IsInvoked(true)
            ->IsSuccess(Supporter::confirmedEmailByHash($request->param('hash')));
    }

    public function getEmailConfirmation()
    {
        return $this->getWidget('EmailConfirmation');
    }

    public function getSupportersUrl()
    {
        return $this->getWidget('Supporters')->getUrl();
    }
}