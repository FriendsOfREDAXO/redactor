<?php

echo rex_view::title(rex_i18n::msg('redactor_title'));

$addon = rex_addon::get('redactor');

$form = rex_config_form::factory($addon->getName());

$field = $form->addInputField('text', 'mytextfield', null, ['class' => 'form-control']);
$field->setLabel(rex_i18n::msg('redactor_config_mytextfield_label'));
$field->setNotice(rex_i18n::msg('redactor_config_mytextfield_notice'));

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $addon->i18n('redactor_config'), false);
$fragment->setVar('body', $form->get(), false);
echo $fragment->parse('core/page/section.php');
