<?php

class HomePage extends Page
{
    private static $db = array(
        'QuestionAnsweredCount' => 'Int',
        'AboutTitle' => 'Varchar(1000)',
        'SupportTitle' => 'Varchar(1000)',
    );

    private static $has_many = array(
        'Questions' => 'Question'
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab('Root.Main', new NumericField('QuestionAnsweredCount', 'Počet zodpovedaných otázok'), 'Content');
        $fields->addFieldToTab('Root.Main', new TextField('AboutTitle', 'Nadpis sekcie s popisom kauzy'), 'Content');
        $fields->addFieldToTab('Root.Main', new TextField('SupportTitle', 'Nadpis sekcie s požiadavkami'));

        $field = new GridField('Questions', 'Questions', $this->owner->Questions(), $this->getGridFieldConfig());
        $fields->addFieldToTab('Root.Otázky', $field);

        $fields->renameField('Content', 'Popis kauzy');

        return $fields;
    }
}

class HomePage_Controller extends Page_Controller
{
    protected $is_data_cached = false;

    protected function setupForms()
    {
        $this->addFormWidget('Supporter');
    }

    public function getQuestions()
    {
        return $this->Questions();
    }

    public function getCounter()
    {
        return Cleopas\Widgets\Data\Basic::create()
            ->Days(function() {
                $start = new DateTime(date('Y-m-d'));
                $end = new DateTime('2016-12-19');
                $diff = $start->diff($end);
                return $diff->format('%R') == '+' ? $diff->days : 0;
            })
            ->Supporters(Supporter::get()->count())
            ->Answers($this->Questions()->filter(array('IsAnswered' => true))->count());
    }
}
