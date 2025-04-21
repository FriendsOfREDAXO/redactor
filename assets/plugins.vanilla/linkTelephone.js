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
    $R.add('plugin', 'linkTelephone', {
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
                title: redactorTranslations.linkTelephone_title,
                icon: false,
                tooltip: redactorTranslations.linkTelephone_tooltip,
                api: 'plugin.linkTelephone.open'
            };

            let button = this.toolbar.addButton('linkTelephone', obj);
            button.setIcon('<i class="re-icon-link"></i><i class="fa fa-phone" style="margin-left: .1em; vertical-align: top;"></i>');
        },

        open: function () {
            this.$currentItem = this._getCurrent();

            var options = {
                title: redactorTranslations.linkTelephone_title,
                name: 'linkTelephoneModal',
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
            'linkTelephoneModal': {
                open: function ($modal, $form) {
                    if (this.selection.getText() !== '') {
                        $form.setData({
                            'linkTelephoneText': this.selection.getText()
                        });
                    }
                },
                opened: function ($modal, $form) {
                    $form.getField('linkTelephoneNumber').focus();
                    if (this.$currentItem) {
                        let $el = $R.dom(this.$currentItem);
                        let email = decodeURI($el.attr('href').substring(4));
                        let text = $el.text();
                        $form.getField('linkTelephoneNumber').val(email);
                        $form.getField('linkTelephoneText').val(text);
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
            'linkTelephoneModal': '<form action="">'
                + '<div class="form-item">'
                + '<label>' + redactorTranslations.linkTelephone_label_number + ' <span class="req">*</span</label>'
                + '<input name="linkTelephoneNumber" type="text" />'
                + '<span class="desc">' + redactorTranslations.linkTelephone_notice_number + '</span>'
                + '</div>'
                + '<div class="form-item">'
                + '<label>' + redactorTranslations.linkTelephone_label_text + '</label>'
                + '<input name="linkTelephoneText" type="text" />'
                + '</div>'
                + '</form>'
        },

        oncontextbar: function (e, contextbar) {

            let data = this.inspector.parse(e.target);
            if (data.isLink()) {
                let node = data.getLink();
                let $el = $R.dom(node);

                let url = $el.attr('href');
                if (url.substring(0, 4) === 'tel:') {
                    let $point = $R.dom('<a>');

                    $point.text(url.substring(4));
                    $point.attr('href', url);

                    var buttons = {
                        'link': {
                            title: $point,
                            html: url.substring(4)
                        },
                        'edit': {
                            title: redactorTranslations.edit,
                            api: 'plugin.linkTelephone.open',
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
            if (data.linkTelephoneText.trim() === '') {
                data.linkTelephoneText = data.linkTelephoneNumber;
            }

            let current = this._getCurrent();
            if (current) {
                let element = $R.dom(current);
                element.attr('href', 'tel:'+data.linkTelephoneNumber);
                element.text(data.linkTelephoneText);
            } else {
                this.insertion.insertRaw('<a href="tel:' + data.linkTelephoneNumber + '">' + data.linkTelephoneText + '</a>');
            }
        },

        _validateData: function ($form, data) {
            let regex = /^\+(?:[0-9] ?){6,14}[0-9]$/;
            return regex.test(data.linkTelephoneNumber) ? true : $form.setError('linkTelephoneNumber');
        },
    });
})(Redactor);
