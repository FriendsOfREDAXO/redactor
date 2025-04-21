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
    $R.add('plugin', 'quote', {
        init: function (app) {
            this.app = app;
            this.toolbar = app.toolbar;
            this.insertion = app.insertion;
            this.selection = app.selection;
            this.cleaner = app.cleaner;
        },

        // public
        start: function () {
            let obj = {
                title: redactorTranslations.blockquote_title,
                icon: false,
                tooltip: redactorTranslations.blockquote_tooltip,
                api: 'plugin.quote.open',
                args: {
                    tag: 'blockquote'
                }
            };

            this.toolbar.addButton('quote', obj);
        },

        open: function() {
            var options = {
                title: redactorTranslations.quote_title,
                name: 'redaxoModal',
                width: '500px',
                height: false,
                handle: 'insert',
                commands: {
                    insert: { title: redactorTranslations.quote_insert, },
                    cancel: { title: redactorTranslations.quote_cancel, }
                }
            };

            this.app.api('module.modal.build', options);
        },

        onmodal: {
            'redaxoModal': {
                open: function($modal, $form) {
                    if (this.selection.getText() !== '') {
                        $form.setData({
                            'quoteText': this.selection.getText()
                        });
                    }
                },
                opened: function($modal, $form) {
                    $form.getField('quoteText').focus();
                },
                insert: function($modal, $form) {
                    let data = $form.getData();
                    console.log(data);
                    this._insert(data);
                }
            }
        },

        modals: {
            'redaxoModal': '<form action="">'
                + '<div class="form-item">'
                    + '<label>'+redactorTranslations.quote_label_text+'</label>'
                    + '<textarea name="quoteText" style="height: 200px;"></textarea>'
                + '</div>'
                + '<div class="form-item">'
                    + '<label>'+redactorTranslations.quote_label_author+'</label>'
                    + '<input name="quoteAuthor" type="text" />'
                + '</div>'
                + '<div class="form-item">'
                    + '<label>'+redactorTranslations.quote_label_cite+'</label>'
                    + '<input name="quoteCite" type="text" />'
                + '</div>'
            + '</form>'
        },

        _insert: function(data) {
            // close the modal
            this.app.api('module.modal.close');

            // check the data
            if (data.quoteText.trim() === '') {
                return;
            }

            let cite = '';
            if (data.quoteCite.trim() !== '') {
                 cite = '<cite>'+data.quoteCite+'</cite>';
            }

            let author = '';
            if (data.quoteAuthor.trim() !== '') {
                author = data.quoteAuthor;
                if (cite !== '') {
                    author += ', '+cite;
                    cite = '';
                }
                author = '<footer>'+author+'</footer>';
            }

            // insert data with Insertion Service
            this.insertion.insertHtml('<blockquote><div>'+this.cleaner.paragraphize(data.quoteText)+'</div>'+author+cite+'</blockquote>');
        }
    });
})(Redactor);
