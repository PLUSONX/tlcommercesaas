<script>
    (function($) {
        "use strict";

        let slotRowIndex = 1;

        function toggleRecurrenceFields() {
            const enabled = $('#enable-recurrence-toggle').is(':checked');
            $('#recurrence-fields').toggle(enabled);
            $('#date-range-helper').toggle(!enabled);
            $('#schedule-from-input').prop('required', true);
            $('#schedule-until-input').prop('required', true);
            $('.recurrence-weekday-checkbox').prop('required', false);
        }

        $('#enable-recurrence-toggle').on('change', toggleRecurrenceFields);
        toggleRecurrenceFields();

        function reindexSlotRows() {
            $('#delivery-slot-rows .delivery-slot-row').each(function(index) {
                $(this).find('[name^="slots["]').each(function() {
                    const field = $(this).attr('name').replace(/slots\[\d+\]/, 'slots[' + index + ']');
                    $(this).attr('name', field);
                });
            });

            const rowCount = $('#delivery-slot-rows .delivery-slot-row').length;
            $('.remove-slot-row').prop('disabled', rowCount <= 1);
        }

        $('#add-slot-row').on('click', function() {
            const template = `
                <div class="delivery-slot-row border rounded p-3 mb-15">
                    <div class="form-row">
                        <div class="col-md-3 mb-10">
                            <label class="font-14 black">{{ translate('Start time') }}</label>
                            <input type="time" name="slots[${slotRowIndex}][start_time]" class="theme-input-style" required>
                        </div>
                        <div class="col-md-3 mb-10">
                            <label class="font-14 black">{{ translate('End time') }}</label>
                            <input type="time" name="slots[${slotRowIndex}][end_time]" class="theme-input-style" required>
                        </div>
                        <div class="col-md-3 mb-10">
                            <label class="font-14 black">{{ translate('Label') }} ({{ translate('optional') }})</label>
                            <input type="text" name="slots[${slotRowIndex}][label]" class="theme-input-style"
                                placeholder="{{ translate('Morning') }}">
                        </div>
                        <div class="col-md-2 mb-10">
                            <label class="font-14 black">{{ translate('Max orders') }}</label>
                            <input type="number" name="slots[${slotRowIndex}][max_orders]" class="theme-input-style" min="1"
                                placeholder="{{ translate('Unlimited') }}">
                        </div>
                        <div class="col-md-1 d-flex align-items-end mb-10">
                            <button type="button" class="btn btn-danger remove-slot-row">&times;</button>
                        </div>
                    </div>
                </div>
            `;

            $('#delivery-slot-rows').append(template);
            slotRowIndex++;
            reindexSlotRows();
        });

        $(document).on('click', '.remove-slot-row', function() {
            $(this).closest('.delivery-slot-row').remove();
            reindexSlotRows();
        });

        $('#delivery-slots-form').on('submit', function(e) {
            if ($('#enable-recurrence-toggle').is(':checked')) {
                const checkedDays = $('.recurrence-weekday-checkbox:checked').length;
                if (checkedDays === 0) {
                    e.preventDefault();
                    alert('{{ translate('Please select at least one weekday.') }}');
                }
            }
        });

        $('.slot-status-toggle').on('change', function() {
            $(this).closest('.slot-status-form').submit();
        });

        $('.edit-delivery-slot').on('click', function(e) {
            e.preventDefault();
            const $el = $(this);
            const bookedCount = parseInt($el.data('booked-count') || 0, 10);

            $('#edit-slot-id').val($el.data('id'));
            $('#edit-schedule-date').val($el.data('schedule-date'));
            $('#edit-start-time').val($el.data('start-time'));
            $('#edit-end-time').val($el.data('end-time'));
            $('#edit-label').val($el.data('label') || '');
            $('#edit-max-orders').val($el.data('max-orders') || '');
            $('#edit-slot-booking-warning').toggle(bookedCount > 0);

            $('#edit-delivery-slot-modal').modal('show');
        });

        $('.copy-slots-to-date').on('click', function(e) {
            e.preventDefault();
            const sourceDate = $(this).data('source-date');
            const displayDate = sourceDate;

            $('#copy-source-date').val(sourceDate);
            $('#copy-source-date-display').val(displayDate);
            $('#copy-target-date').val('');
            $('#copy-slots-modal').modal('show');
        });
    })(jQuery);
</script>
