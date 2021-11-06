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

/** @var rex_addon $this */

$fragment = new rex_fragment();
$fragment->setVar('content', rex_markdown::factory()->parse(rex_file::get($this->getPath('README.md'))), false);
$content = $fragment->parse('core/page/docs.php');

$fragment = new rex_fragment();
$fragment->setVar('title', rex_i18n::msg('redactor_help'));
$fragment->setVar('body', $content, false);

echo $fragment->parse('core/page/section.php');
