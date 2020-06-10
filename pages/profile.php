<?php
/**
 * This file is part of the redactor package.
 *
 * @author (c) Friends Of REDAXO
 * @author <friendsof@redaxo.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$addon = rex_addon::get('redactor');

$func = rex_request('func', 'string');
$message = '';

if ($func == 'copy') {
    $profileId = rex_request('profile_id', 'int');
    if (!rex_csrf_token::factory('redactor_copy')->isValid()) {
        $message = rex_view::error(rex_i18n::msg('csrf_token_invalid'));
    } elseif ($profileId > 0) {
        $sql = rex_sql::factory();
        $profile = $sql->getArray('SELECT * FROM '.rex::getTable('redactor_profile').' WHERE id = :id', ['id' => $profileId]);
        if ($sql->getRows() == 1) {
            $profile = $profile[0];
            unset($profile['id']);
            $profile['name'] = $profile['name'].' '.rex_i18n::msg('redactor_profile_name_copy');
            $sqlInsert = rex_sql::factory();
            try {
                $sqlInsert->setTable(rex::getTable('redactor_profile'));
                $sqlInsert->setValues($profile);
                $sqlInsert->insert();
                $message = rex_view::success(rex_i18n::msg('redactor_profile_message_copy_success'));
            } catch (rex_sql_exception $e) {
                $message = rex_view::error($sqlInsert->getError());
            }
            $func = '';
        }
    }
}

if ($message != '') {
    echo $message;
}

if ($func == 'add' || $func == 'edit') {
    $id = rex_request('id', 'int');

    $form = rex_form::factory(rex::getTable('redactor_profile'), '', 'id='.$id);
    $formLabel = rex_i18n::msg('redactor_profile_add');

    if ($func == 'edit') {
        $formLabel = rex_i18n::msg('redactor_profile_edit');

        rex_extension::register('REX_FORM_SAVED', static function (rex_extension_point $ep) use ($addon, $form) {
            if ($form !== $ep->getParam('form')) {
                return;
            }
            $addon->clearCache();
        });
    }

    $field = $form->addTextField('name');
    $field->setLabel(rex_i18n::msg('redactor_profile_name'));

    $field = $form->addTextField('description');
    $field->setLabel(rex_i18n::msg('redactor_profile_description'));

    $field = $form->addTextField('min_height');
    $field->setLabel(rex_i18n::msg('redactor_profile_min_height'));

    $field = $form->addTextField('max_height');
    $field->setLabel(rex_i18n::msg('redactor_profile_max_height'));

    $field = $form->addSelectField('plugin_counter');
    $field->setLabel(rex_i18n::msg('redactor_profile_plugin_counter'));
    $field->setNotice(rex_i18n::msg('redactor_profile_plugin_counter_notice'));
    $select = $field->getSelect();
    $select->setSize(1);
    $select->addOption(rex_i18n::msg('yes'), '1');
    $select->addOption(rex_i18n::msg('no'), '0');

    $field = $form->addTextField('plugin_limiter');
    $field->setLabel(rex_i18n::msg('redactor_profile_plugin_limiter'));
    $field->setNotice(rex_i18n::msg('redactor_profile_plugin_limiter_notice'));

    $field = $form->addTextAreaField('plugins');
    $field->setLabel(rex_i18n::msg('redactor_profile_plugins'));
    $field->setNotice(rex_i18n::msg('redactor_profile_plugins_notice'));

    if ($func == 'edit') {
        $form->addParam('id', $id);
    }

    $content = $form->get();

    $fragment = new rex_fragment();
    $fragment->setVar('class', 'edit', false);
    $fragment->setVar('title', rex_i18n::msg('redactor_profile').' '.$formLabel, false);
    $fragment->setVar('body', $content, false);
    $content = $fragment->parse('core/page/section.php');
    echo $content;
} else {
    $list = rex_list::factory('SELECT `id`, `name`, `description`, CONCAT(".redactor-editor--", `name`) as `selector` FROM `'.rex::getTable('redactor_profile').'` ORDER BY `name` ASC');
    $list->addTableAttribute('class', 'table-striped');
    $list->setNoRowsMessage(rex_i18n::msg('redactor_profile_no_results'));

    $thIcon = '<a href="'.$list->getUrl(['func' => 'add']).'"><i class="rex-icon rex-icon-add-action"></i></a>';
    $tdIcon = '<i class="rex-icon fa-file-text-o"></i>';
    $list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);
    $list->setColumnParams($thIcon, ['func' => 'edit', 'id' => '###id###']);

    $functions = rex_i18n::msg('redactor_profile_functions');
    $list->addColumn($functions, '<i class="rex-icon rex-icon-duplicate"></i> ' . rex_i18n::msg('redactor_profile_copy'), -1, ['<th class="rex-table-action" colspan="1">###VALUE###</th>', '<td class="rex-table-action">###VALUE###</td>']);

    $list->setColumnLabel('name', rex_i18n::msg('redactor_profile_name'));
    $list->setColumnLabel('description', rex_i18n::msg('redactor_profile_description'));
    $list->setColumnLabel('selector', rex_i18n::msg('redactor_profile_selector'));
    $list->setColumnLabel($functions, rex_i18n::msg('redactor_profile_functions'));

    $list->setColumnParams('name', ['id' => '###id###', 'func' => 'edit']);
    $list->setColumnParams($functions, ['func' => 'copy', 'profile_id' => '###id###'] + rex_csrf_token::factory('redactor_copy')->getUrlParams());

    $list->removeColumn('id');

    $content = $list->get();

    $fragment = new rex_fragment();
    $fragment->setVar('content', $content, false);
    $content = $fragment->parse('core/page/section.php');

    echo $content;
}
