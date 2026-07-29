<script>
    (function($) {
        "use strict";
        initDropzone()
        $(document).ready(function() {
            is_for_browse_file = true
            filtermedia()
        });
        /** 
         * Will Store new courier
         *   
         **/
        $('.store-courier-btn').on('click', function(e) {
            e.preventDefault();
            $(document).find(".invalid-input").remove();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: "POST",
                data: $('#new-courier-form').serialize(),
                url: '{{ route('plugin.carrier.shipping.courier.store') }}',
                success: function(response) {
                    location.reload();
                },
                error: function(response) {
                    $.each(response.responseJSON.errors, function(field_name, error) {
                        $(document).find('[name=' + field_name + ']').after(
                            '<div class="invalid-input">' + error + '</div>')
                    })
                }
            });
        });
        /**
         * Change courier status
         **/
        $('.courier-status').on('click', function(e) {
            let $this = $(this);
            let id = $this.data('courier');
            $.post('{{ route('plugin.carrier.shipping.courier.status.update') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                location.reload();
            })

        });
        /**
         * Edit courier
         * 
         **/
        $('.edit-courier').on('click', function(e) {
            e.preventDefault();
            let id = $(this).data('courier');
            let data = {
                id: id,
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: "POST",
                data: data,
                url: '{{ route('plugin.carrier.shipping.courier.edit') }}',
                success: function(data) {
                    $('.edit-courier-data').html(data)
                    $('#edit-courier-modal').modal('show')
                }
            });
        });

        /**
         * Courier Properties
         * 
         **/
        $('.properties-courier').on('click', function(e) {
            e.preventDefault();
            let id = $(this).data('courier');
            let data = {
                id: id,
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: "POST",
                data: data,
                url: '{{ route('plugin.carrier.shipping.courier.properties') }}',
                success: function(data) {
                    $('.courier-properties-data').html(data)
                    $('#courier-properties-modal').modal('show')
                }
            });
        });
        /**
         * Will delete courier
         * 
         **/
        $('.delete-courier').on('click', function(e) {
            e.preventDefault();
            let id = $(this).data('courier');
            $('#delete-courier-id').val(id);
            $("#delete-courier-modal").modal('show');
        });
        /**
         * Activate courier
         * 
         **/
        $('.activate-courier').on('click', function(e) {
            e.preventDefault();
            $.post('{{ route('plugin.carrier.shipping.courier.module.status.update') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                location.reload();
            })
        });
    })(jQuery);
</script>
