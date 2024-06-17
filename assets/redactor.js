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
                if (!('pasteImages' in options)) {
                    options.pasteImages = false;
                }
                $R('.redactor-editor--' + $profile, options);
            }
        }
    })
    
    document.querySelectorAll('div[data-redactor-copy-yform]').forEach(function(el) {
        el.addEventListener('click', data_redactor_copy_yform)
    })
    document.querySelectorAll('div[data-redactor-copy-generic]').forEach(function(el) {
        el.addEventListener('click', data_redactor_copy_generic)
    })

});

function data_redactor_copy_yform(event) {
    // Holt das aktuelle Element, auf das geklickt wurde
    var element = event.currentTarget;
    // Fügt die Klasse "copied" zum aktuellen Element hinzu
    element.classList.add('copied');
    // Sucht das icon-Element im aktuellen Element
    var iconElement = element.querySelector('i');
    // Entfernt die Klasse "fa-clone" vom i-Element
    iconElement.classList.remove('fa-clone');
    // Fügt die Klasse "fa-check" zum i-Element hinzu
    iconElement.classList.add('fa-check');
    // Kopiert den Wert des data-wildcard-copy Attributs in die Zwischenablage
    navigator.clipboard.writeText(element.getAttribute('data-redactor-copy-yform'));
};

function data_redactor_copy_generic(event) {
    // Holt das aktuelle Element, auf das geklickt wurde
    var element = event.currentTarget;
    // Fügt die Klasse "copied" zum aktuellen Element hinzu
    element.classList.add('copied');
    // Sucht das icon-Element im aktuellen Element
    var iconElement = element.querySelector('i');
    // Entfernt die Klasse "fa-clone" vom i-Element
    iconElement.classList.remove('fa-clone');
    // Fügt die Klasse "fa-check" zum i-Element hinzu
    iconElement.classList.add('fa-check');
    // Kopiert den Wert des data-wildcard-copy Attributs in die Zwischenablage
    navigator.clipboard.writeText(element.getAttribute('data-redactor-copy-generic'));
};
