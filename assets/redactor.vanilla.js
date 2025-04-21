document.addEventListener('rex:ready', function(event) {
    const container = event.detail || document;
    const elements = container.querySelectorAll('[class*="redactor-editor--"]');
    
    elements.forEach(function(element) {
        const classNames = element.className.split(' ');
        classNames.forEach(function(className) {
            if (className.startsWith('redactor-editor--')) {
                const profile = className.substring('redactor-editor--'.length);
                if (profile !== '' && redactor_profiles[profile]) {
                    const options = { ...redactor_profiles[profile] };
                    options.lang = redactorLang;
                    if (!('pasteImages' in options)) {
                        options.pasteImages = false;
                    }
                    $R('.redactor-editor--' + profile, options);
                }
            }
        });
    });
});
