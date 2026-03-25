<header class="header white-bg fixed-top d-flex align-content-center flex-wrap">
    @include('core::base.layouts.headerLogo')
    <!-- Main Header -->
    <div class="main-header">
        <div class="container-fluid">
            <div class="row justify-content-between">
                
                <div class="col-9 col-lg-9 col-xl-8">
                    <!-- Header Right -->
                    <div class="main-header-right d-flex justify-content-end">
                        <ul class="nav">
                            <li class="d-none d-lg-flex">
                                <!-- Main Header Time -->
                                <div class="main-header-date-time text-right"
                                    data-timezone="{{ getGeneralSetting('default_timezone') }}" id="dateTime">
                                    <h3 class="time">
                                        <span id="hours">21</span>
                                        <span id="point">:</span>
                                        <span id="min">06</span>
                                    </h3>
                                    <span class="date"><span id="date">Tue, 12 October
                                            2019</span></span>
                                </div>
                                <!-- End Main Header Time -->
                            </li>


                            <!-- <li class="d-none d-lg-flex"> -->
                                <!-- Main Header Button -->
                                <!-- <div class="main-header-btn ml-md-1">
                                    <a href="{{ route('core.admin.clear.system.cache') }}"
                                        class="btn">{{ translate('Clear Cache') }}</a>
                                </div> -->
                                <!-- End Main Header Button -->
                            <!-- </li> -->


                            <li class="ml-3">
                                <!-- Visit website -->
                                <div class="main-header-notification">
                                    <a href="/" target="_blank" title="Visit website"
                                        class="header-icon notification-icon">
                                        <img src="{{ asset('backend/assets/img/svg/globe-icon.svg') }}"
                                            alt="bell" class="svg">
                                    </a>
                                </div>
                                <!-- End Visit website -->
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
                    <!-- End Header Right -->
                </div>

                <div class="col-3 col-lg-3 col-xl-5">
                    <div class="main-header-left h-100 d-flex align-items-center" style="padding: 2px;">
                        @auth
                            <div class="main-header-user dropdown"> <a href="javascript:void(0);" class="user-profile d-lg-flex align-items-center d-none" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-decoration: none; color: inherit;">
                                    
                                    <div class="user-info mr-3 text-right"> <h4 class="user-name mb-0">{{ auth()->user()->name }}</h4>
                                        <p class="user-email mb-0">{{ auth()->user()->email }}</p>
                                    </div>
                                    <div class="user-avatar">
                                        @if (auth()->user()->image != null)
                                            <img src="{{ str_replace('/public', '', asset(getFilePath(auth()->user()->image)) ) }}"
                                                alt="{{ auth()->user()->name }}" 
                                                class="rounded-circle" 
                                                style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('backend/assets/img/avatar/avatar-user.png') }}"
                                                alt="{{ auth()->user()->name }}" 
                                                class="rounded-circle" 
                                                style="width: 40px; height: 40px; object-fit: cover;">
                                        @endif
                                    </div>
                                    </a>

                                <div class="dropdown-menu dropdown-menu-right"> <a class="dropdown-item" href="{{ route('core.profile') }}">
                                        <i class="fa fa-user mr-2"></i> {{ translate('My Profile') }}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('core.logout') }}">
                                        <i class="fa fa-sign-out mr-2"></i> {{ translate('Log Out') }}
                                    </a>
                                </div>
                            </div>
                        @endauth
                        <div class="main-header-menu d-block d-lg-none">
                            <div class="header-toogle-menu">
                                <img src="{{ asset('backend/assets/img/menu.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <!-- End Main Header -->
</header>
