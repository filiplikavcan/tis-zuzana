<?php

class HomePage extends Page
{
    private static $db = array(
        'QuestionAnsweredCount' => 'Int',
        'AboutTitle' => 'Varchar(1000)',

        'QuestionsTitle' => 'Varchar(1000)',
        'QuestionsContent' => 'HTMLText',

        'SupportTitle' => 'Varchar(1000)',
        'SupportContent' => 'HTMLText',

        'GoalsTitle' => 'Varchar(1000)',
        'GoalsContent' => 'HTMLText',

        'ThankYouTitle' => 'Varchar(1000)',
        'ThankYouContent' => 'HTMLText',

        'EmailTemplate' => 'Text',
    );

    private static $has_many = array(
        'Questions' => 'Question',
        'Goals' => 'Goal',
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab('Root.Main', new NumericField('QuestionAnsweredCount', 'Počet zodpovedaných otázok'), 'Content');
        $fields->addFieldToTab('Root.Main', new TextField('AboutTitle', 'Nadpis sekcie s popisom kauzy'), 'Content');

        $fields->addFieldToTab('Root.Otázky', new TextField('QuestionsTitle', 'Nadpis sekcie s otázkami'));
        $fields->addFieldToTab('Root.Otázky', new HtmlEditorField('QuestionsContent', 'Obsah sekcie s otázkami'));
        $fields->addFieldToTab('Root.Otázky', new GridField('Questions', 'Questions', $this->owner->Questions(), $this->getGridFieldConfig()));

        $fields->addFieldToTab('Root.Formulár', new TextField('SupportTitle', 'Nadpis sekcie s formulárom'));
        $fields->addFieldToTab('Root.Formulár', new HtmlEditorField('SupportContent', 'Obsah sekcie s formulárom'));
        $fields->addFieldToTab('Root.Formulár', new TextField('ThankYouTitle', 'Ďakovný nadpis'));
        $fields->addFieldToTab('Root.Formulár', new HtmlEditorField('ThankYouContent', 'Ďakovný obsah'));

        $fields->addFieldToTab('Root.Ciele', new TextField('GoalsTitle', 'Nadpis sekcie s cieľmi'));
        $fields->addFieldToTab('Root.Ciele', new HtmlEditorField('GoalsContent', 'Obsah sekcie s cieľmi'));
        $fields->addFieldToTab('Root.Ciele', new GridField('Goals', 'Goals', $this->owner->Goals(), $this->getGridFieldConfig()));

        $fields->addFieldToTab('Root.Email', new TextareaField('EmailTemplate', 'Šablóna emailu'));

        $fields->renameField('Content', 'Popis kauzy');

        return $fields;
    }
}

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
    }

    protected function setupForms()
    {
        $this->addFormWidget('Supporter');
    }

    public function getQuestions()
    {
        return $this->Questions()->Sort('Sort');
    }

    public function getGoals()
    {
        return $this->Goals()->Sort('Sort');
    }

    public function getAboutHash()
    {
        return \Nette\Utils\Strings::webalize($this->AboutTitle);
    }

    public function getQuestionsHash()
    {
        return \Nette\Utils\Strings::webalize($this->QuestionsTitle);
    }

    public function getSupportHash()
    {
        return \Nette\Utils\Strings::webalize($this->SupportTitle);
    }

    public function getCounter()
    {
        return Cleopas\Widgets\Data\Data::create()
            ->Days(function() {
                $start = new DateTime(date('Y-m-d'));
                $end = new DateTime('2016-12-19');
                $diff = $start->diff($end);
                return $diff->format('%R') == '+' ? $diff->days : 0;
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
}
