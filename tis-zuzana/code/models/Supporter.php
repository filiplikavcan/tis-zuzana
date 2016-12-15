<?php

use Nette\Utils\Random;

class Supporter extends DataObject
{
    private static $db = array(
        'Email' => 'Varchar(100)',
        'Name' => 'Varchar(255)',
        'City' => 'Varchar(100)',
        'Country' => 'Varchar(100)',
        'Show' => 'Boolean',

        'ConfirmationHash' => 'Varchar(100)',
        'ConfirmationIDGenerator' => 'Varchar(20)', // Int fields are generated with NOT NULL and cannot be defined as unique indexes :(
        'ConfirmationID' => 'Int(11)'
    );

    private static $defaults = array(
        'ConfirmationID' => null
    );

    private static $indexes = array(
        'Presentable' => array(
            'type' => 'index',
            'value' => '"Name","Show"'
        ),
        'ConfirmationHash' => 'unique("ConfirmationHash")',
        'ConfirmationIDGenerator' => 'unique("ConfirmationIDGenerator")',
        'ConfirmationID' => true,
    );

    private static $summary_fields = array(
        'Created' => 'Podpísané',
        'getConfirmedAt' => 'Potvrdené',
        'Email' => 'Email',
        'Name' => 'Meno',
        'City' => 'Mesto',
        'getCountryName' => 'Krajina'
    );

    private static $default_sort = 'Created DESC';

    public function getConfirmedAt()
    {
        return $this->ConfirmationID > 0 ? $this->LastEdited : NULL;
    }

    public function canCreate($member = null)
    {
        return false;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab('Root.Main', new DropdownField('Country', 'Country', Country::all('sk')));
        $fields->removeByName('ConfirmationHash');
        $fields->removeByName('ConfirmationIDGenerator');
        $fields->removeByName('ConfirmationID');

        return $fields;
    }

    public function sendConfirmationEmail()
    {
        $email = new PHPMailer();

        $email->isSMTP();

        //$email->SMTPDebug = 3;

        $email->CharSet = 'utf-8';
        $email->Host = SMTP_HOST;
        $email->SMTPAuth = true;
        $email->Username = SMTP_LOGIN;
        $email->Password = SMTP_PASSWORD;
        $email->SMTPSecure = SMTP_PROTOCOL;
        $email->Port = SMTP_PORT;

        $email->From = 'info@odpovedztezuzane.sk';
        $email->FromName = 'Odpovedzte Zuzane';

        $email->addAddress($this->Email);

        $name = empty($this->Name) ? '' : ' ' . $this->Name;

        $email->Subject = 'Odpovedzte Zuzane: Overenie emailovej adresy';
        $email->Body = "Dobrý deň$name,\n\n";
        $email->Body .= "ďakujeme za Vašu podporu. Ak sa chcete zapojiť do výzvy Odpovedzte Zuzane, potvrďte záujem kliknutím na nasledujúci odkaz:\n\n";
        $email->Body .= $this->getConfirmationLink() . "\n\n";
        $email->Body .= "Pomáhate nám tým predísť viacnásobným podpisom a odoslaniam od fiktívnych osôb.\n\n";
        $email->Body .= "Ďakujeme\n\n";
        $email->Body .= "S pozdravom\n";
        $email->Body .= "Transparency International SK";

        $email->send();
    }

    public static function confirmed()
    {
        return static::get()->where('ConfirmationID > 0');
    }

    public static function confirmedEmailByHash($hash)
    {
        $supporter = static::get()->filter(array('ConfirmationHash' => $hash))->first();

        if ($supporter instanceof Supporter && $supporter->LastEdited > date('Y-m-d H:i:s', time() - (24 * 3600 * 7)))
        {
            static::saveConfirmed($supporter);

            return true;
        }
        else
        {
            return false;
        }
    }

    protected static function saveConfirmed($supporter)
    {
        try
        {
            $supporter->ConfirmationIDGenerator = (int) DB::query('SELECT MAX(CAST(ConfirmationIDGenerator AS UNSIGNED)) FROM Supporter')->value() + 1;
            $supporter->write();
        }
        catch (SS_DatabaseException $e)
        {
            if (strpos($e->getMessage(), 'Duplicate entry'))
            {
                static::saveConfirmed($supporter);
            }
            else
            {
                throw $e;
            }
        }

        $supporter->ConfirmationID = (int) $supporter->ConfirmationIDGenerator;
        $supporter->write();
    }

    protected function getConfirmationLink()
    {
        return MAIL_BASE_URL . 'home/confirm/' . $this->generateConfirmationHash();
    }

    protected function generateConfirmationHash()
    {
        $hash = Random::generate(10);

        try
        {
            $this->ConfirmationHash = $hash;
            $this->write();

            return $this->ConfirmationHash;
        }
        catch (SS_DatabaseException $e)
        {
            if (strpos($e->getMessage(), 'Duplicate entry'))
            {
                echo $this->generateConfirmationHash();
            }
            else
            {
                throw $e;
            }
        }
    }

    public function getCountryName()
    {
        return Country::byCode($this->Country, 'sk');
    }
}