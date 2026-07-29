<script>
    (function($) {
        "use strict";
        /**
         *
         * Change country status
         *
         * */
        $('.change-status').on('click', function(e) {
            e.preventDefault();
            let $this = $(this);
            let id = $this.data('country');
            $.post('{{ route('plugin.tlcommercecore.shipping.locations.country.status.change') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                location.reload();
            })
        });
        /**
         *
         * Delete country
         *
         * */
        $('.delete-country').on('click', function(e) {
            e.preventDefault();
            let $this = $(this);
            let id = $this.data('country');
            $("#delete-country-id").val(id);
            $('#delete-modal').modal('show');
        });

        /**
         *
         * Checked all items
         **/
        $('.checked-all-items').on('change', function(e) {
            if ($('.checked-all-items').is(":checked")) {
                $(".item-id").prop("checked", true);
            } else {
                $(".item-id").prop("checked", false);
            }
        });
        /**
         *
         * Bulk action
         **/
        $('.fire-bulk-action').on('click', function(e) {
            let action = $('.bulk-action-selection').val();
            if (action != 'null') {
                var selected_items = [];
                $('input[name^="item_id"]:checked').each(function() {
                    selected_items.push($(this).val());
                });
                if (selected_items.length > 0) {
                    $.post('{{ route('plugin.tlcommercecore.shipping.locations.country.bulk.action') }}', {
                        _token: '{{ csrf_token() }}',
                        items: selected_items,
                        action: action
                    }, function(data) {
                        if (data.success) {
                            toastr.success('{{ translate('Action Applied Successfully') }}');
                            location.reload();
                        }
                        if (!data.success) {
                            toastr.error('{{ translate('Action Failed') }}');
                        }
                    })
                } else {
                    toastr.error('{{ translate('No Item Selected') }}');
                }
            } else {
                toastr.error('{{ translate('No Action Selected') }}');
            }
        });
    })(jQuery);
</script>
