/**
 * This file is part of the redactor package.
 *
 * @author (c) Friends Of REDAXO
 * @author <friendsof@redaxo.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
(function ($R) {
    $R.add('plugin', 'linkEmail', {
        init: function (app) {
            this.app = app;
            this.toolbar = app.toolbar;
            this.insertion = app.insertion;
            this.inspector = app.inspector;
            this.selection = app.selection;
            this.cleaner = app.cleaner;
            this.component = app.component;
        },

        // public
        start: function () {
            let obj = {
                title: redactorTranslations.linkEmail_title,
                icon: false,
                tooltip: redactorTranslations.linkEmail_tooltip,
                api: 'plugin.linkEmail.open'
            };

            let button = this.toolbar.addButton('linkEmail', obj);
            button.setIcon('<i class="re-icon-link"></i><i class="fa fa-envelope-o" style="margin-left: .1em; vertical-align: top;"></i>');
        },

        open: function () {
            this.$currentItem = this._getCurrent();

            var options = {
                title: redactorTranslations.linkEmail_title,
                name: 'linkEmailModal',
                width: '500px',
                height: false,
                handle: 'insert',
                commands: {
                    insert: {title: (this.$currentItem) ? redactorTranslations.save : redactorTranslations.insert},
                    cancel: {title: redactorTranslations.cancel,}
                }
            };

            this.app.api('module.modal.build', options);
        },

        onmodal: {
            'linkEmailModal': {
                open: function ($modal, $form) {
                    if (this.selection.getText() !== '') {
                        $form.setData({
                            'linkEmailText': this.selection.getText()
                        });
                    }
                },
                opened: function ($modal, $form) {
                    $form.getField('linkEmailAddress').focus();
                    if (this.$currentItem) {
                        let $el = $R.dom(this.$currentItem);
                        let email = decodeURI($el.attr('href').substring(7));
                        let text = $el.text();
                        $form.getField('linkEmailAddress').val(email);
                        $form.getField('linkEmailText').val(text);
                    }
                },
                insert: function ($modal, $form) {
                    let data = $form.getData();
                    if (this._validateData($form, data)) {
                        this._insert(data);
                    }
                },
            }
        },

        modals: {
            'linkEmailModal': '<form action="">'
                + '<div class="form-item">'
                + '<label>' + redactorTranslations.linkEmail_label_email + ' <span class="req">*</span</label>'
                + '<input name="linkEmailAddress" type="text" />'
                + '</div>'
                + '<div class="form-item">'
                + '<label>' + redactorTranslations.linkEmail_label_text + '</label>'
                + '<input name="linkEmailText" type="text" />'
                + '</div>'
                + '</form>'
        },

        oncontextbar: function (e, contextbar) {

            let data = this.inspector.parse(e.target);
            if (data.isLink()) {
                let node = data.getLink();
                let $el = $R.dom(node);

                let url = $el.attr('href');
                if (url.substring(0, 7) === 'mailto:') {
                    let $point = $R.dom('<a>');

                    $point.text(url.substring(7));
                    $point.attr('href', url);

                    var buttons = {
                        'link': {
                            title: $point,
                            html: url.substring(7)
                        },
                        'edit': {
                            title: redactorTranslations.edit,
                            api: 'plugin.linkEmail.open',
                            args: node
                        },
                        'unlink': {
                            title: redactorTranslations.unlink,
                            api: 'module.link.unlink'
                        }
                    };
                    contextbar.set(e, node, buttons, 'bottom');
                }
            }
        },

        _getCurrent: function () {
            var current = this.selection.getCurrent();
            var data = this.inspector.parse(current);
            if (data.isLink()) {
                return this.component.build(data.getLink());
            }
        },

        _insert: function (data) {
            // close the modal
            this.app.api('module.modal.close');

            // check the data
            if (data.linkEmailAddress.trim() === '') {
                return;
            }
            if (data.linkEmailText.trim() === '') {
                data.linkEmailText = data.linkEmailAddress;
            }

            let current = this._getCurrent();
            if (current) {
                let element = $R.dom(current);
                element.attr('href', 'mailto:'+data.linkEmailAddress);
                element.text(data.linkEmailText);
            } else {
                this.insertion.insertRaw('<a href="mailto:' + data.linkEmailAddress + '">' + data.linkEmailText + '</a>');
            }
        },

        _validateData: function ($form, data) {
            return (data.linkEmailAddress.trim() === '') ? $form.setError('linkEmailAddress') : true;
        },
    });
})(Redactor);
