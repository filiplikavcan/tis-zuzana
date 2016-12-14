<?php

global $project;
$project = 'tis-zuzana';

global $database;
$database = 'tis_zuzana';

require_once('conf/ConfigureFromEnv.php');

// Set the site locale
i18n::set_locale('sk_SK');

TwigContainer::extendConfig(array(
    'twig.environment_options' => array(
        'debug' => true
    ),
    'twig.extensions' => array(
        '.twig'
    ),
    'twig.compilation_cache' => BASE_PATH . '/silverstripe-cache',
    'twig.template_paths' => array(
        BASE_PATH . '/frontend/templates'
    ),
    'twig.controller_variable_name' => 'c'
));

Security::setDefaultAdmin('admin', 'admin');