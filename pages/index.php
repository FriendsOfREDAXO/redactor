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


echo rex_view::title(rex_i18n::msg('redactor_title'));

$func = rex_request('func', 'string');
if ('clear_cache' == $func) {
    rex_addon::get('redactor')->clearCache();
    echo rex_view::success(rex_i18n::msg('redactor_cache_deleted'));
}

rex_be_controller::includeCurrentPageSubPath();
