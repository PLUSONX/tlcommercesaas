{{-- layout_settings.blade.php --}}

@extends('core::base.layouts.master')

@section('title')
    {{ translate('Store Layout Settings') }}
@endsection

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2/select2.min.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('/public/backend/assets/plugins/select2/select2.min.css') }}"> -->
@endsection

@section('main_content')
    <div class="row">
         <div class="col-12">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('Store Layout') }}</h4>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-12">
            <div class="card mb-30" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="card-body">
                    <div class="card-header">
                        <h4>{{ translate('Choose Your Store Layout') }}</h4>
                        <p class="text-muted">{{ translate('Select how you want your store to appear to customers') }}</p>
                    </div>

                    <form action="{{ route('theme.tlcommerce.updateLayoutSettings') }}" method="POST">
                        @csrf
                        
                        <div class="row mt-4">
                            @foreach($layouts as $layout)
                            <div class="col-md-6 col-lg-3 mb-4">
                                <label class="layout-option {{ $activeLayout->id == $layout->id ? 'active' : '' }}">
                                    <input 
                                        type="radio" 
                                        name="layout_id" 
                                        value="{{ $layout->id }}"
                                        {{ $activeLayout->id == $layout->id ? 'checked' : '' }}
                                        required
                                    >
                                    <div class="layout-card">

                                        @if($layout->name == 'split_screen')
                                            <div class="dropdown-button" style="margin-bottom: 10px;">
                                                <a href="#" class="d-flex align-items-center justify-content-end"
                                                    data-toggle="dropdown">
                                                    <div class="menu-icon mr-0">
                                                        <span></span>
                                                        <span></span>
                                                        <span></span>
                                                    </div>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="#" class="edit-layout-btn">
                                                        {{ translate('Edit') }}
                                                    </a>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="layout-preview layout-preview-{{ $layout->name }}">
                                            @if($layout->name == 'standard')
                                                <div class="preview-box full"></div>
                                            @elseif($layout->name == 'sidebar_left')
                                                <div class="preview-box sidebar-left"></div>
                                                <div class="preview-box content"></div>
                                            @elseif($layout->name == 'sidebar_right')
                                                <div class="preview-box content"></div>
                                                <div class="preview-box sidebar-right"></div>
                                            @elseif($layout->name == 'split_screen')
                                                <div class="preview-box split-left"></div>
                                                <div class="preview-box split-right"></div>

                                                
                                            
                                            @endif
                                        </div>
                                        <div class="layout-info">
                                            <h5>{{ ucwords(str_replace('_', ' ', $layout->name)) }}</h5>
                                            @if($activeLayout->id == $layout->id)
                                                <span class="badge badge-success">{{ translate('Active') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn long courier-update-btn btn-orange">{{ translate('Save Changes') }}</button>
                            </div>
                            <!-- <button type="submit" class="btn btn-primary">
                                {{ translate('Save Layout') }}
                            </button> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="row" id="split-screen-edit-form" style="display: none;">
        <div class="col-12">
            <div class="card mb-30" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="card-body">
                    <div class="card-header d-flex justify-content-between">
                        <h4>{{ translate('Edit Split Screen') }}</h4>
                    </div>

                </div>

                <div class="card-body">

                    <form action="{{ route('theme.tlcommerce.editLayoutSettings') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="form-row mb-20">
                                <div class="col-sm-4">
                                    <label class="font-14 bold black">
                                        {{ translate('Content Position') }}
                                    </label>
                                </div>
                                <div class="col-md-12">
                                    <select
                                        class="form-control"
                                        name="content_position"
                                    >
                                        <option value="">
                                            {{ translate('Select Content Position') }}
                                        </option>
                                        <option value="left">{{ translate('Left') }}</option>
                                        <option value="right">{{ translate('Right') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row mb-20">
                                <div class="col-sm-4">
                                    <label class="font-14 bold black">
                                        {{ translate('Feature Type') }}
                                    </label>
                                </div>
                                <div class="col-md-12">
                                    <select
                                        class="form-control"
                                        name="feature_type"
                                    >
                                        <option value="">
                                            {{ translate('Select Feature Type') }}
                                        </option>
                                        <option value="left">{{ translate('Banner') }}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-row mb-20">
                                <div class="col-md-12">
                                    <label class="font-14 bold black mb-0">{{ translate('Feature Image') }} </label>
                                    <p>960×1080</p>
                                </div>
                                <div class="col-md-12">
                                    @include('core::base.includes.media.media_input', [
                                        'input' => 'feature_image',
                                        'data' => old('feature_image'),
                                    ])
                                    @if ($errors->has('feature_image'))
                                        <div class="invalid-input">{{ $errors->first('feature_image') }}</div>
                                    @endif
                                </div>
                            </div>
                        
                            <div class="form-row">
                                <div class="col-12 text-right">
                                    <button type="submit" class="btn long btn-orange">{{ translate('Save') }}</button>
                                </div>
                            </div>
                        </form>

                </div>
                
            </div>
        </div>
    </div>

@endsection

 @include('core::base.media.partial.media_modal')

@section('custom_scripts')

    <script src="{{ asset('backend/assets/plugins/select2/select2.min.js') }}"></script>

    <script>

        (function($) {

            "use strict";
                initDropzone()
            $(document).ready(function () {

                is_for_browse_file = true
                filtermedia()

                function showEditForm() {
                    const $form = $('#split-screen-edit-form');

                    if ($form.is(':visible')) return;

                    $form.fadeIn(200, function () {
                        // 🔥 IMPORTANT: re-init media input AFTER visible
                        if (typeof initMedia === 'function') {
                            initMedia();
                        }
                    });

                    $('html, body').animate({
                        scrollTop: $form.offset().top
                    }, 500);
                }

                $('.edit-layout-btn').on('click', function (e) {
                    e.preventDefault();
                    showEditForm();
                });

                // Auto-show if active layout is 4
                fetch("{{ route('theme.tlcommerce.getActiveLayout') }}")
                    .then(response => response.json())
                    .then(data => {
                        if (data.layout && data.layout.id == 4) {
                            showEditForm();
                        }
                    })
                    .catch(error => console.error('Layout fetch error:', error));
            });

            function hideEditForm() {
                $('#split-screen-edit-form').fadeOut();
            }

        })(jQuery);
        
    </script>


    <!-- <script>

        $(document).ready(function() {

        $('.edit-layout-btn').on('click', function(e) {
                e.preventDefault();
                // Show the form
                $('#split-screen-edit-form').fadeIn(); 
                
                // Optional: Scroll to the form
                $('html, body').animate({
                    scrollTop: $("#split-screen-edit-form").offset().top
                }, 500);
            });
        });

        function hideEditForm() {
            $('#split-screen-edit-form').fadeOut();
        }


        document.addEventListener('DOMContentLoaded', function () {
            fetch("{{ route('theme.tlcommerce.getActiveLayout') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.layout && data.layout.id == 4) {
                        document.getElementById('split-screen-edit-form').style.display = 'block';
                    }
                })
                .catch(error => console.error('Layout fetch error:', error));
        });

    </script> -->

@endsection



    

<style>
.layout-option {
    cursor: pointer;
    display: block;
}

.layout-option input[type="radio"] {
    display: none;
}

.layout-card {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 15px;
    transition: all 0.3s;
    background: #fff;
}

.layout-option:hover .layout-card {
    border-color: #ff5A1f;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.layout-option.active .layout-card,
.layout-option input:checked + .layout-card {
    border-color: #ff5A1f;
    background: #f8f9ff;
}

.layout-preview {
    height: 150px;
    border: 1px solid #ddd;
    border-radius: 4px;
    display: flex;
    gap: 5px;
    padding: 5px;
    background: #fafafa;
    margin-bottom: 15px;
    position: relative; /* Ensure child absolute positioning works */
}


.preview-box {
    background: #ff5A1f;
    border-radius: 3px;
    opacity: 0.7;
}

.preview-box.full {
    width: 100%;
}

.preview-box.sidebar-left,
.preview-box.sidebar-right {
    width: 30%;
}

.preview-box.content {
    flex: 1;
}

.preview-box.split-left,
.preview-box.split-right {
    width: 50%;
}

.layout-info h5 {
    margin: 0 0 5px 0;
    font-size: 16px;
    font-weight: 600;
}

.badge {
    font-size: 11px;
    padding: 3px 8px;
}

.layout-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
}

.btn-dots {
    color: #666;
    background: rgba(255, 255, 255, 0.8);
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s;
    text-decoration: none !important;
}

.btn-dots:hover {
    background: #fff;
    color: #ff5A1f;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* Adjust dropdown styling for a cleaner look */
.dropdown-menu {
    min-width: 100px;
    padding: 5px 0;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border: 1px solid #eee;
}

.dropdown-item {
    font-size: 13px;
    padding: 8px 15px;
}


    button.btn-orange,
    a.btn-orange {
        background: #ff5A1f !important;
        border-color: #e64a10 !important;
        color: #fff !important;
        transition: background 0.2s ease;
        border-radius: 6px !important;
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

    /* 4. Optional: Style the Focus state (when clicked) to remove the blue shadow */
    /* .pagination .page-item .page-link:focus {
        box-shadow: 0 0 0 0.2rem rgba(255, 90, 31, 0.25);
    } */

</style>