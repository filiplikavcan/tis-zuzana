<?php

class SupporterFormFactory extends BaseFormFactory
{
    public function create() {
        $form = parent::create();

        $form->addText('Email', 'Email*')
            ->addRule(Application\Form::EMAIL, 'Emailová adresa nie je platná.')
            ->addRule(\Nette\Forms\Form::REQUIRED, 'Tento údaj je povinný.');
        $form->addText('Name', 'Meno a priezvisko');
        $form->addText('City', 'Mesto');
        $form->addText('Country', 'Krajina')
            ->setDefaultValue('SK');
        $form->addCheckbox('Show', 'Súhlasím so zverejnením mena a krajiny v zozname signatárov')
            ->setDefaultValue(true);

        $submit = $form->addSubmit('submit', 'Odoslať');

        $submit->onClick[] = array($this, 'onSubmit');

        return $form;
    }

    public function onSubmit(Nette\Forms\Controls\SubmitButton $button)
    {
        /**
         * @var $form \Application\Form
         */
        $form = $button->getForm();        

        if ($form->isValid())
        {
            if (!$form->isStored())
            {
                $data = $form->getValues();

                $supporter = new Supporter();

                $supporter->Email = $data->Email;
                $supporter->Name = $data->Name;
                $supporter->City = $data->City;
                $supporter->Country = $data->Country;
                $supporter->Show = $data->Show;

                $supporter->write();

                $supporter->sendConfirmationEmail();

                $form->markAsStored();
            }
        }
        else
        {
            $form->cleanErrors();
            $form->addError('Zadajte aspoň email, prosím.');
        }
    }
}