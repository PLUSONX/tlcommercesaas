@extends('core::base.layouts.master')
@section('title')
    {{ translate('Payment Methods') }}
@endsection
@section('custom_css')
@section('custom_css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2/select2.min.css') }}">
    <!--  End select2  -->
    <!--Editor-->
    <link href="{{ asset('backend/assets/plugins/summernote/summernote-lite.css') }}" rel="stylesheet" />
    <!--End editor-->
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
@endsection
@endsection
@section('main_content')

<div class="row">
        <div class="col-12">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('Payment Methods') }}</h4>
                    </div>
                </div>

            </div>
        </div>
</div>
<!-- <div class="border-bottom2 pb-3 mb-4">
    <h4><i class="icofont-pay"></i> {{ translate('Payment Methods') }}</h4>
</div> -->
@if (count($payment_methods) > 0)
    @foreach ($payment_methods as $key => $method)
        <div class="card mb-30" style="border-radius: 12px !important; overflow: hidden !important;">
            <div class="card-bod">
                <div class="payment-method-items">
                    <div class="payment-method-item">
                        <!--Payment title-->
                        <div class="payment-method-item-header px-3">
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="payment-icon ">
                                        <i class="icofont-pay"></i>
                                    </div>
                                </div>
                                <div class="payment-logo">
                                    <h4 class="black">{{ $method->name }}</h4>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-15">
                                <label class="switch glow primary medium">
                                    <input type="checkbox" data-payment="{{ $method->id }}"
                                        class="payment-method-status"
                                        @if ($method->status == config('settings.general_status.active')) checked @endif />
                                    <span class="control"></span>
                                </label>
                                <button class="btn sm btn-orange get-configuration" data-id="{{ $method->id }}"><i
                                        class="icofont-settings"></i> Configuration
                                </button>
                                <!-- <button class="btn sm get-configuration" data-id="{{ $method->id }}"><i
                                        class="icofont-settings"></i> Configuration
                                </button> -->
                            </div>
                        </div>
                        <!--End payment title-->
                        <!--Payment Configuration-->
                        <div id="item-body-{{ $method->id }}" class="hidden configuration">
                        </div>
                        <!--End payment Configuration-->
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <p class="alert alert-danger">{{ translate('No payment method found') }}</p>
@endif
@include('core::base.media.partial.media_modal')
<!--Edit Modal-->
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span id="title" class="mr-1"></span>{{ translate('Configuration') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body form-container">

            </div>
        </div>
    </div>
</div>
<!--End Edit Modal-->
@endsection
@section('custom_scripts')
<!--Select2-->
<script src="{{ asset('backend/assets/plugins/select2/select2.min.js') }}"></script>
<!--End Select2-->
<!--Editor-->
<script src="{{ asset('backend/assets/plugins/summernote/summernote-lite.js') }}"></script>
<!--End Editor-->
<script>
    (function($) {
        "use strict";
        initDropzone()
        $(document).ready(function() {
            is_for_browse_file = true;
            filtermedia();
        });
        /**
         *Active and deactive product review
         *
         **/
        $('.payment-method-status').on('change', function(e) {
            e.preventDefault();
            let id = $(this).data('payment');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: "POST",
                data: {
                    id: id
                },
                url: '{{ route('plugin.tlcommercecore.payments.methods.status.update') }}',
                success: function(response) {
                    location.reload();
                },
                error: function(response) {
                    location.reload();
                }
            });
        });
        ///will get configuration
        $(".get-configuration").on("click", function(e) {
            e.preventDefault();
            $('.configuration').fadeOut("slow");
            $('.configuration').removeClass('border-top2');
            $(".configuration").html('');
            let id = $(this).data("id");
            let body_id = "item-body-" + id;
            if ($("#" + body_id).css('display') === 'block') {
                return 0;
            }
            $.ajax({
                type: "POST",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                url: '{{ route('plugin.tlcommercecore.payments.methods.credential.edit') }}',
                success: function(response) {
                    if (response.success) {
                        $("#" + body_id).html(response.html);
                        $("#" + body_id).addClass('border-top2');
                        $("#" + body_id).fadeIn('slow');
                        initSelect2();
                        initSummerNote();
                    } else {
                        toastr.error('{{ translate('No configuration found') }}');
                    }
                },
                error: function(response) {
                    toastr.error('{{ translate('No configuration found') }}');
                }
            });
        });
        /**
         * Update payment method credential
         *
         **/
        $(document).on("submit", "#credential-form", function(e) {
            e.preventDefault();
            $(document).find(".invalid-input").remove();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: "POST",
                data: $("#credential-form").serialize(),
                url: '{{ route('plugin.tlcommercecore.payments.methods.credential.update') }}',
                success: function(response) {
                    if (response.success) {
                        toastr.success('{{ translate('Credential updated successfully') }}');
                        $("#edit-modal").modal("hide");
                    } else {
                        toastr.error('{{ translate('Update Failed ') }}');
                    }
                },
                error: function(response) {
                    if (response.status == 422) {
                        $.each(response.responseJSON.errors, function(field_name, error) {
                            $(document).find('[name=' + field_name + ']').closest(
                                '.input-option').after(
                                '<div class="invalid-input">' + error + '</div>')
                        })
                    } else {
                        toastr.error('{{ translate('Update Failed ') }}');
                    }
                }
            });

        });

        function initSelect2() {
            $('.selectCurrency').select2({
                theme: 'classic'
            });
        }

        function initSummerNote() {
            $('#instruction').summernote({
                tabsize: 2,
                height: 200,
                codeviewIframeFilter: false,
                codeviewFilter: true,
                codeviewFilterRegex: /<\/*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|ilayer|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|t(?:itle|extarea)|xml)[^>]*>|on\w+\s*=\s*"[^"]*"|on\w+\s*=\s*'[^']*'|on\w+\s*=\s*[^\s>]+/gi,
                toolbar: [
                    ["style", ["style"]],
                    ["font", ["bold", "underline", "clear"]],
                    ["color", ["color"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["table", ["table"]],
                    ["insert", ["link", "video", 'picture']],
                    ["view", ["fullscreen", "help"]],
                ],
                placeholder: 'Write Instructions',
                callbacks: {
                    onImageUpload: function(images, editor, welEditable) {
                        sendFile(images[0], editor, welEditable);
                    },
                    onChangeCodeview: function(contents, $editable) {
                        let code = $(this).summernote('code')
                        code = code.replace(
                            /<\/*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|ilayer|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|t(?:itle|extarea)|xml)[^>]*>|on\w+\s*=\s*"[^"]*"|on\w+\s*=\s*'[^']*'|on\w+\s*=\s*[^\s>]+/gi,
                            '')
                        $(this).val(code)
                    }
                }
            });
        }

        function sendFile(image, editor, welEditable) {
            "use strict";
            let imageUploadUrl = '{{ route('core.blog.content.image') }}';
            let data = new FormData();
            data.append("image", image);
            data.append("_token", "{{ csrf_token() }}");

            $.ajax({
                data: data,
                type: "POST",
                url: imageUploadUrl,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {

                    if (data.url) {
                        var image = $('<img>').attr('src', data.url);
                        $('#instruction').summernote("insertNode", image[0]);
                    } else {
                        toastr.error(data.error, "Error!");
                    }

                },
                error: function(data) {
                    toastr.error('Image Upload Failed', "Error!");
                }
            });
        }
    })(jQuery);
</script>
@endsection


<style>

    button.btn-orange,
    a.btn-orange {
        background: #ff5A1f !important;
        border-color: #e64a10 !important;
        color: #fff !important;
        transition: background 0.2s ease;
        border-radius: 8px !important;
        box-shadow: none !important;

    }

    button.btn-orange:hover,
    a.btn-orange:hover {
        background: #ff7545 !important;
        border-color: #e07b00 !important;
        color: #fff !important;
        box-shadow: none !important;

    }

    button.btn-orange:focus,
    button.btn-orange:active,
    button.btn-orange:active:focus {
        background: #ff7545 !important;
        border-color: #e07b00 !important;
        box-shadow: none !important;
        outline: none !important;
    }

    /* 1. The background of the switch track when ON */
    .switch.medium input:checked ~ .control {
        background-color: #ff5A1f !important;
        border-color: #ff8c00 !important;
    }

    /* 2. The sliding circle (the knob) */
    /* We usually keep this white or a very light grey for contrast */
    .switch.medium .control:after {
        background-color: #ffffff !important;
        border: 1px solid #e0e0e0;
        box-shadow: none !important;

    }

    /* 3. If your template uses a shadow on the circle when active */
    .switch.medium input:checked ~ .control:after {
        border-color: #ff8c00 !important; 
        box-shadow: none !important;

    }

        /* 1. Change the Active Page background and border */
     .pagination .page-item.active .page-link {
        background-color: #ff5A1f !important;
        border-color: #ff5A1f !important;
        color: #ffffff !important; /* Ensure text is white on orange */
    }

    /* 2. Change the Hover state for non-active links */
    .pagination .page-item .page-link:hover {
        background-color: #ff7545 !important; /* The lighter orange we picked earlier */
        border-color: #ff7545 !important;
        color: #ffffff !important;
    }

    /* 3. Change the default text color for non-active links */
   .pagination .page-item .page-link {
        color: #ff5A1f; /* Orange text on white background */
        border-color: #dee2e6; /* Standard light border */
    }

    select.theme-input-style {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px bottom 10px; /* adjust 12px to move arrow left/right */
        padding-right: 2rem;
    }

    .theme-input-style {
        width: 100%;
        background-color: white !important;
        border: 1px solid black !important;
    }

    .theme-input-style:focus, 
    .theme-input-style:active,
    .theme-input-style:hover {
        background-color: white !important;
        outline: none;                /* Optional: removes default browser glow */
        border: 1px solid black !important; /* Keeps your border consistent */
    }

      .btn-link {
        color: #ff5A1f !important;
    }


</style>