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

$file = rex_file::get(rex_path::addon('redactor', 'README.md'));
$body = rex_markdown::factory()->parse($file);

$fragment = new rex_fragment();
$fragment->setVar('title', rex_i18n::msg('redactor_help'));
$fragment->setVar('body', $body, false);

echo $fragment->parse('core/page/section.php');
