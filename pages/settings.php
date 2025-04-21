<?php

/** @var rex_addon $this */

$form = rex_config_form::factory('redactor');

$form->addFieldset(rex_i18n::msg('redactor_settings'));

// Checkbox für experimentelle Funktion "Vanilla JS statt JQuery verwenden"

$field = $form->addCheckboxField('use_vanilla_js');

$field->addOption(rex_i18n::msg('redactor_settings_use_vanilla_js'), '1');

// Ausgabe in Container-Fragment

$fragment = new rex_fragment();
$fragment->setVar('title', rex_i18n::msg('redactor_settings'));
$fragment->setVar('body', $form->get(), false);
$content = $fragment->parse('core/page/section.php');

echo $content;
