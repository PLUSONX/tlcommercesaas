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
            <div class="card mb-30">
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
                                <button type="submit" class="btn long courier-update-btn">{{ translate('Save Changes') }}</button>
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
            <div class="card mb-30">
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
                                <div class="col-sm-8">
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
                                <div class="col-sm-8">
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
                                <div class="col-sm-4">
                                    <label class="font-14 bold black mb-0">{{ translate('Feature Image') }} </label>
                                    <p>960×1080</p>
                                </div>
                                <div class="col-sm-8">
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
                                    <button type="submit" class="btn long">{{ translate('Save') }}</button>
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
    border-color: #007bff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.layout-option.active .layout-card,
.layout-option input:checked + .layout-card {
    border-color: #007bff;
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
    background: #007bff;
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
    color: #007bff;
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
</style>
