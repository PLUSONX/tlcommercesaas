@php
    echo "<script>console.log('widget_image:', " . json_encode($data['widget_image']) . ");</script>";

    $url = asset('themes/default/' . $data['widget_image']);

    echo "<script>console.log('url:', " . json_encode($url) . ");</script>";

@endphp
@if (!isset($data['link']) || (isset($data['link']) && $data['link'] == 'none'))
    <img src="{{ $url }}" alt="Image">
    <!-- <img src="{{ asset(getFilePath($data['widget_image'])) }}" alt="Image"> -->
@else
    <a href="{{ $data['link'] == 'media_file' ? asset(getFilePath($data['widget_image'])) : $data['link_url'] }}"
        target="{{ $data['link'] == 'custom_url' && $data['new_window'] == '1' ? '_blank' : '_self' }}">
        <img src="{{ $url }}" alt="Image">
        <!-- <img src="{{ asset(getFilePath($data['widget_image'])) }}" alt="Image"> -->
    </a>
@endif
