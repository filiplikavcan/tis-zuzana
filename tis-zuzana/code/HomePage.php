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
        'EmailTemplate' => 'Text',
    );

    private static $has_many = array(
        'Questions' => 'Question'
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab('Root.Main', new NumericField('QuestionAnsweredCount', 'Počet zodpovedaných otázok'), 'Content');
        $fields->addFieldToTab('Root.Main', new TextField('AboutTitle', 'Nadpis sekcie s popisom kauzy'), 'Content');
        $fields->addFieldToTab('Root.Otázky', new TextField('QuestionsTitle', 'Nadpis sekcie s otázkami'));
        $fields->addFieldToTab('Root.Otázky', new HtmlEditorField('QuestionsContent', 'Obsah sekcie s otázkami'));
        $fields->addFieldToTab('Root.Formulár', new TextField('SupportTitle', 'Nadpis sekcie s formulárom'));
        $fields->addFieldToTab('Root.Formulár', new HtmlEditorField('SupportContent', 'Obsah sekcie s formulárom'));
        $fields->addFieldToTab('Root.Email', new TextareaField('EmailTemplate', 'Šablóna emailu'));

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
        return $this->Questions()->Sort('Sort');
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