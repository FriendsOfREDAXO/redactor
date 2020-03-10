/**
 * This file is part of the redactor package.
 *
 * @author (c) Friends Of REDAXO
 * @author <friendsof@redaxo.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
$(document).on('rex:ready',function(event, container) {
    $(container).find('[class*="redactor-editor--"]').each(function() {
        let classNames = $(this).attr('class').split(' ');
        for (let i = 0; i < classNames.length; i++) {
            let $profile = classNames[i].substring('redactor-editor--'.length);
            if ($profile !== '' && redactor_profiles[$profile]) {
                let options = redactor_profiles[$profile];
                options.lang = redactorLang;
                options.pasteImages = false;
                $R('.redactor-editor--' + $profile, options);
            }
        }
    })
});
