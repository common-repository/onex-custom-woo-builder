(function( $ ) {

	'use strict';

	var CustomWooTemplatePopup = {

		init: function() {

			var self = this;

			$( document )
				.on( 'click.CustomWooTemplatePopup', '.page-title-action', self.openPopup )
				.on( 'click.CustomWooTemplatePopup', '.custom-template-popup__overlay', self.closePopup )
				.on( 'change.CustomWooTemplatePopup', '#template_type', self.switchTemplates )
				.on( 'click.CustomWooTemplatePopup', '.custom-template-popup__item--uncheck', self.uncheckItem )
				.on( 'click.CustomWooTemplatePopup', '.custom-template-popup__label', self.isCheckedItem );

		},

		switchTemplates: function() {
			var $this = $( this ),
				value = $this.find( 'option:selected' ).val();

			if ( '' !== value ) {
				$( '.predesigned-row.template-' + value ).addClass( 'is-active' ).siblings().removeClass( 'is-active' );
			}
		},

		isCheckedItem: function() {
			var $this = $( this ),
				value = $this.find('input'),
				checked = value.prop( "checked" );

			CustomWooTemplatePopup.uncheckAll();

			if( checked ){
				$this.addClass( 'is--checked');
			}
		},

		uncheckAll: function() {
			var item = $( '.custom-template-popup__label' );

			if( item.hasClass('is--checked') ){
				item.removeClass('is--checked');
				item.find('input').prop( "checked", false );
			}
		},

		uncheckItem: function() {
			var $this = $( this ),
				label = $this.parent().find('.custom-template-popup__label'),
				input = label.find('input'),
				checked = input.prop( "checked" );

			if( checked ){
				input.prop( "checked", false );
				label.removeClass('is--checked');
			}
		},

		openPopup: function( event ) {
			event.preventDefault();
			$( '.custom-template-popup' ).addClass( 'custom-template-popup-active' );

			CustomWooTemplatePopup.uncheckAll();
		},

		closePopup: function() {
			$( '.custom-template-popup' ).removeClass( 'custom-template-popup-active' );
		}

	};

	CustomWooTemplatePopup.init();

})( jQuery );