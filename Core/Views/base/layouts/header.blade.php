<header class="header white-bg fixed-top d-flex align-items-center">
    @include('core::base.layouts.headerLogo')
    
    <div class="w-100">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-between">

                <div class="col-auto" style="margin-left: 60px;">
                    @auth
                        <div class="user-info d-none d-sm-block"> 
                            <h4 class="user-name mb-0" style="font-size: 18px; font-weight: 600;">{{ auth()->user()->name }}</h4>

                            <a href="/" target="_blank" class="d-flex align-items-center mt-1 text-decoration-none" style="gap: 5px; color: #6c757d; font-size: 14px;">
                                <span>{{ 'Visit store' }}</span>
                                
                                <x-lucide-arrow-up-right style="width: 14px; height: 14px; color: #ff8c00;" />
                                <!-- <i class="icofont-external-link" style="font-size: 14px; color: #ff8c00;"></i> -->
                            </a>
                        </div>
                    @endauth
                </div>

                <div class="col-auto">
                    <div class="d-flex align-items-center">
                        <ul class="nav align-items-center">
                            
    
                            <li class="ml-3">
                                <div class="main-header-notification">
                                 
                                    <a href="#" class="header-icon notification-icon" data-toggle="dropdown" title="Notification">
                                        <!-- <span class="count notification-counter" data-bg-img="{{ asset('backend/assets/img/count-bg.png') }}">0</span> -->
                                        <span class="count notification-counter" style=" color: white; background: #ff8c00;" >0</span>
                                        
                                        <x-lucide-bell style="width: 22px; height: 22px; color: #ff8c00;" />
                                    </a>
                                    <div class="dropdown-menu style--two dropdown-menu-right py-0">
                                        <div class="dropdown-header bg-primary-light d-flex align-items-center justify-content-between">
                                            <h4 class="py-2 font-weight-normal">{{ translate('Notifications') }}</h4>
                                            <a href="#" class="text-mute mark-as-all-read d-none">{{ translate('Clear all') }}</a>
                                        </div>
                                        <div class="dropdown-body notification-list-items"></div>
                                    </div>
                                </div>
                            </li>

                            <li class="ml-3">
                                @auth
                                    <div class="main-header-user dropdown">
                                        <a href="javascript:void(0);" class="user-profile d-flex align-items-center" data-toggle="dropdown">
                                            <!-- <div class="user-avatar">
                                                <img src="{{ auth()->user()->image ? str_replace('/public', '', asset(getFilePath(auth()->user()->image))) : asset('backend/assets/img/avatar/avatar-user.png') }}"
                                                     class="rounded-circle" 
                                                     style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #ff8c00;">
                                            </div> -->
                                            <div class="user-avatar">
                                                <img src="{{ auth()->user()->image ? str_replace('/public', '', asset(getFilePath(auth()->user()->image))) : asset('backend/assets/img/avatar/avatar-user.png') }}"
                                                    class="rounded-circle" 
                                                    style="width: 50px; height: 50px; min-width: 50px; object-fit: cover; border: 1px solid #ff8c00; flex-shrink: 0;">
                                            </div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{ route('core.profile') }}"><i class="fa fa-user mr-2"></i> {{ translate('My Profile') }}</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('core.logout') }}"><i class="fa fa-sign-out mr-2"></i> {{ translate('Log Out') }}</a>
                                        </div>
                                    </div>
                                @endauth
                            </li>

                            <!-- <li class="ml-3 d-lg-none">
                                <div class="header-toogle-menu">
                                    <img src="{{ asset('backend/assets/img/menu.png') }}" alt="menu" style="width: 25px;">
                                </div>
                            </li> -->

                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</header>
