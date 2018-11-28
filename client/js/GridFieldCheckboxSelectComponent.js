/**
 * Handles selection and check-all as well as confirmations.
 *
 * @author Mark Guinn <mark@adaircreative.com>
 * @date 12.22.2014
 * @package gridfieldmultiselect
 * @subpackage javascript
 */
(function($){
	$.entwine('ss', function($) {

		$('.ss-gridfield .multiselect').entwine({
			onclick: function (e) {
				e.stopPropagation();
			}
		});

		$('.ss-gridfield .multiselect-all').entwine({
			onclick: function () {
				this.closest('table').find('.multiselect').prop('checked', this.prop('checked'));
			}
		});

		$('.ss-gridfield .multiselect-button').entwine({
			onclick: function(e) {
				if (this.closest('.ss-gridfield').find('.multiselect:checked').length == 0) {
					alert('Please check one or more rows.');
					return false;
				}

				if (this.data('confirm') && !confirm(this.data('confirm'))) {
					e.preventDefault();
					e.stopPropagation();
					return false;
				}

				this._super(e);
			}
		});

	});
})(jQuery);
