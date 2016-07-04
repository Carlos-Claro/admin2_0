/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
        //config.disallowedContent = 'br';
	//config.enterMode = CKEDITOR.ENTER_BR;
        //config.blockedKeystrokes = [ CKEDITOR.CTRL + 86 ] // Bloqueia CTRL+V;
        /*config.toolbarGroups = [
            { name: 'document', groups : ['mode', 'document', 'doctools'] },
            { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
            { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
            { name: 'forms' },
            '/',
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
            { name: 'links' },
            { name: 'insert' },
            '/',
            { name: 'styles' },
            { name: 'colors' },
            { name: 'tools' },
            { name: 'others' },
            { name: 'about' }
        ];*/
	config.autoParagraph = false;
	config.bodyClass = 'contents';
	config.enterMode = CKEDITOR.ENTER_P;

        config.allowedContent = true;
        config.extraAllowedContent = '*{*}';
        config.forcePasteAsPlainText = true;
	// Se the most common block elements.
	//config.format_tags = 'p;h1;h2;h3;pre';

};
