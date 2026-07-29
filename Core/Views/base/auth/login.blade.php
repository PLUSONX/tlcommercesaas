@php
$system_name = getGeneralSetting('system_name');
$desktop_logo = !empty(getGeneralSetting('admin_logo')) ? getFilePath(getGeneralSetting('admin_logo')) : '';
$login_bg_image = !empty(getGeneralSetting('login_bg_image')) ? getFilePath(getGeneralSetting('login_bg_image')) : '';

$login_bg_image = str_replace('/public', '', $login_bg_image);

$host = request()->getHost();
$centralDomains = config('tenancy.central_domains', ['localhost']);
$isCentralDomain = in_array($host, $centralDomains);



@endphp
@extends('core::base.auth.auth_layout')
@section('title')
{{ translate('Login') }}
@endsection
@section('custom_css')
<style>
    body {
        background-image: url('/themes/default/1920.png');
        background-size: 100% 100%;
        background-attachment: fixed;
        min-height: 100vh;
        overflow-x: hidden;
    }

    .login-page-layout {
        padding-right: 5% !important;
    }

    .login-page-layout .card {
        width: 450px !important;
        min-height: 500px !important;
        flex: none !important;
    }

    @media (max-width: 1200px) {
        body {
            background-image: url('/themes/default/bg.png');
            background-size: cover;
            background-attachment: scroll;
        }

        .login-page-layout {
        padding-right: 0px !important;
    }

        .login-page-layout .card {
            width: 400px !important;
            
            margin-left: auto !important;
            margin-right: auto !important;
        }

        .row {
            justify-content: center !important;
        }

        .col-xl-3 {
            margin-right: 0 !important;
        }
    }

    @media (max-width: 900px) {

        .login-page-layout {
        padding-right: 10px !important;
    }

        .login-page-layout .card {
            width: 350px !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }
    }
</style>
@include('core::base.auth._partials.login-styles')
@endsection
@section('main_content')
<div class="container-fluid login-page-layout position-relative">
    <div class="align-items-center h-100 justify-content-end row py-5">
        <div class="col-auto">
            @include('core::base.auth._partials.login-form', ['idPrefix' => ''])
        </div>
    </div>
</div>
@endsection

@section('custom_scripts')
@include('core::base.auth._partials.login-scripts', ['idPrefix' => ''])
@endsection
