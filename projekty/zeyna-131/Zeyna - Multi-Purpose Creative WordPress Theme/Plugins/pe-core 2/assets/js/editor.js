(function ($) {
	"use strict";


	$(window).on('elementor/panel/init', function (settings) {

		// elementor.channels.editor.on('refresh_widget', (e) => {

		// 	console.log('aaaaa');
		// 	window.elementorFrontend.init();
		// 	let pins = document.querySelectorAll('.pin-spacer');

		// 	console.log(pins)

		// });

	

	});


	jQuery(window).on('elementor:init', () => {
		const ControlNestedRepeater = elementor.modules.controls.BaseData.extend({
			onReady() {
				const control = this;

				// Mevcut değerleri geri yükle
				const value = this.getControlValue() || [];
				if (Array.isArray(value)) {
					value.forEach(val => {
						const subItem = jQuery('<div class="nested-sub-item"><i class="eicon-paragraph"></i>')
							.append(`<input type="text" value="${val}" placeholder="Sub Field" />`)
							.append('<button class="remove-sub-item elementor-repeater-row-tool elementor-repeater-tool-remove"><i class="eicon-close" aria-hidden="true"></i></button>');

						control.$el.find('.nested-repeater-wrapper').append(subItem);
					});
				}

				// + Add Sub Item tıklandığında yeni alan ekle
				this.$el.on('click', '.add-sub-item', function (e) {
					e.preventDefault();

					const subItem = jQuery('<div class="nested-sub-item"><i class="eicon-paragraph"></i>')
						.append('<input type="text" placeholder="Sub Field" />')
						.append('<button class="remove-sub-item elementor-repeater-row-tool elementor-repeater-tool-remove"><i class="eicon-close" aria-hidden="true"></i></button>');

					control.$el.find('.nested-repeater-wrapper').append(subItem);

					control.saveValue();
				});

				// Remove
				this.$el.on('click', '.remove-sub-item', function (e) {
					e.preventDefault();
					jQuery(this).closest('.nested-sub-item').remove();
					control.saveValue();
				});

				// Input değişince kaydet
				this.$el.on('input', '.nested-sub-item input', function () {
					control.saveValue();
				});

				control.$el.find('.nested-repeater-wrapper').sortable({
					items: '.nested-sub-item',
					cursor: 'move',
					scrollSensitivity: 40,
					forcePlaceholderSize: true,
					forceHelperSize: false,
					helper: 'clone',
					opacity: 0.65,
					// placeholder: 'wc-metabox-sortable-placeholder',
					start: function (event, ui) {
						// ui.item.css('background-color', '#f6f6f6');
					},
					stop: function (event, ui) {
						// ui.item.removeAttr('style');
					},
					update: function () {
						control.saveValue();
						// var $galleryWrapper = $(this);
						// var $galleryInput = $galleryWrapper.siblings('.nested-sub-item');
						// var imageIds = [];

						// $galleryWrapper.find('li').each(function () {
						// 	imageIds.push($(this).data('attachment_id'));
						// });

						// $galleryInput.val(imageIds.join(','));
						// $('#variable_description0').trigger(
						// 	'change'
						// );

					}
				});


			},

			saveValue() {
				const values = [];
				this.$el.find('.nested-sub-item input').each(function () {
					values.push(jQuery(this).val());
				});

				this.setValue(values);
			},

			onBeforeDestroy() {
				this.$el.off();
			}
		});

		elementor.addControlView('nested_repeater', ControlNestedRepeater);
	});

})(jQuery)

