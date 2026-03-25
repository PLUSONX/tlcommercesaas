<header class="header white-bg fixed-top d-flex align-items-center flex-wrap">
    @include('core::base.layouts.headerLogo')
    
    <div class="main-header w-100">
        <div class="container-fluid">
            <div class="row justify-content-end align-items-center">
                
                <div class="col-auto">
                    <div class="main-header-right d-flex justify-content-end">
                        <ul class="nav align-items-center">
                            <li class="d-none d-lg-flex">
                                <div class="main-header-date-time text-right" id="dateTime">
                                    <h3 class="time mb-0"><span id="hours">21</span><span>:</span><span id="min">06</span></h3>
                                    <span class="date"><span id="date">Tue, 12 October 2019</span></span>
                                </div>
                            </li>

                            <li class="ml-3">
                                <div class="main-header-notification">
                                    <a href="/" target="_blank" class="header-icon"><img src="{{ asset('backend/assets/img/svg/globe-icon.svg') }}" class="svg"></a>
                                </div>
                            </li>

                            <li class="ml-3">
                                <!-- Main Header Language -->
                                <div class="main-header-notification">
                                    <a href="#" class="header-icon notification-icon" data-toggle="dropdown"
                                        title="Language Options">
                                        @if (isset($active_lang->code))
                                            <!-- <img src="{{ asset('backend/assets/img/flags/') . '/' . $active_lang->code . '.png' }}" -->
                                            <img src="{{ asset('flags/') . '/' . $active_lang->code . '.png' }}"
                                                class="w-20" alt="{{ $active_lang->code }}">
                                        @endif
                                    </a>
                                    <div id="lang-change" class="dropdown-menu style--three">
                                        @foreach ($active_langs as $lang)
                                            <a href="#" class="dropdown-item" data-lan="{{ $lang->code }}">
                                                <!-- <img src="{{ asset('backend/assets/img/flags/') . '/' . $lang->code . '.png' }}" -->
                                                <img src="{{ asset('flags/') . '/' . $lang->code . '.png' }}"
                                                    class="mr-2 w-20" alt="{{ $lang->code }}">
                                                {{ $lang->native_name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- End Main Header Language -->
                            </li>
                            <li>
                                <!-- Main Header Notification -->
                                <div class="main-header-notification">
                                    <a href="#" class="header-icon notification-icon" data-toggle="dropdown"
                                        title="Notification">
                                        <span class="count notification-counter"
                                            data-bg-img="{{ asset('backend/assets/img/count-bg.png') }}">0</span>
                                        <img src="{{ asset('backend/assets/img/svg/notification-icon.svg') }}"
                                            alt="bell" class="svg">
                                    </a>
                                    <div class="dropdown-menu style--two dropdown-menu-right py-0">
                                        <div
                                            class="dropdown-header bg-primary-light d-flex align-items-center justify-content-between">
                                            <h4 class="py-2 font-weight-normal">{{ translate('Notifications') }}</h4>
                                            <a href="#"
                                                class="text-mute mark-as-all-read d-none">{{ translate('Clear all') }}</a>
                                        </div>
                                        <div class="dropdown-body notification-list-items">
                                        </div>
                                    </div>
                                </div>
                                <!-- End Main Header Notification -->
                            </li>

                            </ul>
                    </div>
                </div>

                <div class="col-auto">
                    <div class="main-header-left h-100 d-flex align-items-center justify-content-end">
                        @auth
                            <div class="main-header-user dropdown">
                                <a href="javascript:void(0);" class="user-profile d-flex align-items-center" data-toggle="dropdown" style="text-decoration: none; color: inherit;">
                                    <div class="user-info mr-3 text-right d-none d-sm-block"> 
                                        <h4 class="user-name mb-0" style="font-size: 14px;">{{ auth()->user()->name }}</h4>
                                        <p class="user-email mb-0" style="font-size: 11px;">{{ auth()->user()->email }}</p>
                                    </div>
                                    <div class="user-avatar">
                                        <img src="{{ auth()->user()->image ? str_replace('/public', '', asset(getFilePath(auth()->user()->image))) : asset('backend/assets/img/avatar/avatar-user.png') }}"
                                             class="rounded-circle" 
                                             style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #ddd;">
                                    </div>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="{{ route('core.profile') }}"><i class="fa fa-user mr-2"></i> {{ translate('My Profile') }}</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('core.logout') }}"><i class="fa fa-sign-out mr-2"></i> {{ translate('Log Out') }}</a>
                                </div>
                            </div>
                        @endauth

                        <div class="main-header-menu d-block d-lg-none ml-3">
                            <div class="header-toogle-menu">
                                <img src="{{ asset('backend/assets/img/menu.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</header>