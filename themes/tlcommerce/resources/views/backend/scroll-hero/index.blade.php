@extends('core::base.layouts.master')

@section('title')
    {{ translate('Scroll Hero Animation') }}
@endsection

@section('main_content')
    <div class="row">
        <div class="col-lg-8 mx-auto">

            <div class="form-element py-30 mb-30">
                <h4 class="font-20 mb-10">
                    {{ translate('Scroll Hero Animation') }}
                </h4>

                <p class="mb-30">
                    Upload ZIP files containing sequential WebP, AVIF,
                    JPG or PNG frames.
                </p>

                <form
                    action="{{ route('theme.tlcommerce.scroll-hero.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    @csrf

                    <div class="form-row mb-20">
                        <div class="col-sm-4">
                            <label class="font-14 bold black">
                                {{ translate('Desktop Frames') }}
                            </label>

                            <p class="mb-0">ZIP file</p>
                            <small>Recommended: 60–100 WebP frames</small>
                        </div>

                        <div class="col-sm-8">
                            <input
                                type="file"
                                name="desktop_frames"
                                class="theme-input-style"
                                accept=".zip"
                            >

                            @error('desktop_frames')
                                <div class="invalid-input">
                                    {{ $message }}
                                </div>
                            @enderror

                            @if (!empty($manifest['desktop']))
                                <div class="mt-2 text-success">
                                    {{ count($manifest['desktop']) }}
                                    desktop frames uploaded
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-row mb-20">
                        <div class="col-sm-4">
                            <label class="font-14 bold black">
                                {{ translate('Mobile Frames') }}
                            </label>

                            <p class="mb-0">Optional ZIP file</p>
                            <small>Desktop frames are used as fallback</small>
                        </div>

                        <div class="col-sm-8">
                            <input
                                type="file"
                                name="mobile_frames"
                                class="theme-input-style"
                                accept=".zip"
                            >

                            @error('mobile_frames')
                                <div class="invalid-input">
                                    {{ $message }}
                                </div>
                            @enderror

                            @if (!empty($manifest['mobile']))
                                <div class="mt-2 text-success">
                                    {{ count($manifest['mobile']) }}
                                    mobile frames uploaded
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-row mb-20">
                        <div class="col-sm-4">
                            <label class="font-14 bold black">
                                {{ translate('Scroll Length') }}
                            </label>

                            <small>Percentage of viewport height</small>
                        </div>

                        <div class="col-sm-8">
                            <input
                                type="number"
                                name="scroll_height"
                                class="theme-input-style"
                                min="150"
                                max="600"
                                value="{{ old(
                                    'scroll_height',
                                    $manifest['scroll_height'] ?? 300
                                ) }}"
                            >

                            @error('scroll_height')
                                <div class="invalid-input">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-12 text-right">
                            <button type="submit" class="btn long">
                                {{ translate('Upload Frames') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            @if (!empty($manifest['desktop']))
                <div class="form-element py-30 mb-30">
                    <h4 class="font-20 mb-20">
                        {{ translate('Animation Status') }}
                    </h4>

                    <form
                        action="{{ route('theme.tlcommerce.scroll-hero.status') }}"
                        method="POST"
                        class="mb-20"
                    >
                        @csrf

                        <input
                            type="hidden"
                            name="enabled"
                            value="{{ !empty($manifest['enabled']) ? 0 : 1 }}"
                        >

                        <button
                            type="submit"
                            class="btn long {{ !empty($manifest['enabled']) ? 'btn-danger' : '' }}"
                        >
                            @if (!empty($manifest['enabled']))
                                {{ translate('Disable Scroll Hero') }}
                            @else
                                {{ translate('Enable Scroll Hero') }}
                            @endif
                        </button>
                    </form>

                    <form
                        action="{{ route('theme.tlcommerce.scroll-hero.delete') }}"
                        method="POST"
                        onsubmit="return confirm('Delete all scroll hero frames?')"
                    >
                        @csrf

                        <button type="submit" class="btn long btn-danger">
                            {{ translate('Delete Frames') }}
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
@endsection