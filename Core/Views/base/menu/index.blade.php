@php
    $menu_groups = getAllMenuGroups();
    $selected_menu_group = isset(request()->group_id) ? request()->group_id : -1;
    $all_menu_positions = getAllMenuPositions();
    $languages = getAllLanguages();

    $menu_items = getAllMenuItems();

    $all_categories = getAllCategories();
    $all_recent_categories = getAllRecentCategories();

    $all_posts = getAllPosts();
    $all_recent_posts = getAllRecentPosts();

    $all_pages = getAllPages();
    $all_recent_pages = getAllRecentPages();

    $all_tags = getAllTags();
    $all_recent_tags = getAllRecentTags();
@endphp
@extends('core::base.layouts.master')
@section('title')
    {{ translate('Menu') }}
@endsection
@section('custom_css')
    <link href="{{ asset('backend/assets/plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2/select2.min.css') }}">
    <style>
        .menu-management-frame {
            gap: 24px;
        }

        .menu-structure.card,
        .add-menu-item.card {
            height: fit-content;
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

        a.btn-link:not(.text-danger),
        button.btn-link:not(.text-danger),
        .menu-locations-table .locations-row-links a {
            background: linear-gradient(90deg, #ff8c00, #ff4500);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: #ff5A1f;
            text-decoration: none;
            border: none;
            box-shadow: none !important;
        }

        a.btn-link:not(.text-danger):hover,
        button.btn-link:not(.text-danger):hover,
        .menu-locations-table .locations-row-links a:hover {
            background: linear-gradient(90deg, #ff4500, #ff8c00);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: #ff5A1f;
            text-decoration: underline;
        }

        button.submit-add-to-menu,
        input.submit-add-to-menu {
            background: #ff5A1f !important;
            border: 1px solid #e64a10 !important;
            color: #fff !important;
            border-radius: 6px !important;
            box-shadow: none !important;
            transition: background 0.2s ease;
            cursor: pointer;
        }

        button.submit-add-to-menu:hover,
        input.submit-add-to-menu:hover {
            background: #ff7545 !important;
            border-color: #e07b00 !important;
            color: #fff !important;
        }

        button.submit-add-to-menu:focus,
        button.submit-add-to-menu:active,
        input.submit-add-to-menu:focus,
        input.submit-add-to-menu:active {
            background: #ff7545 !important;
            border-color: #e07b00 !important;
            color: #fff !important;
            outline: none !important;
        }
    </style>
@endsection

@section('main_content')
    <div class="row">
        <div class="col-12">
            <div class="card bg-transparent mb-20">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-10">
                        <h4 style="font-size: 30px;">{{ translate('Menus') }}</h4>
                        <button type="button" class="btn long btn-orange" onclick="showMenuCreationForm()">
                            {{ translate('Create Menu') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-30" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="card-header bg-white border-bottom2 py-3">
                    <ul class="nav nav-tabs border-0 pl-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="edit_menus-tab" data-toggle="tab" href="#edit_menus"
                                role="tab" aria-controls="edit_menus"
                                aria-selected="true">{{ translate('Edit Menus') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="manage_location-tab" data-toggle="tab" href="#manage_location"
                                role="tab" aria-controls="manage_location"
                                aria-selected="false">{{ translate('Manage Locations') }}</a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="edit_menus" role="tabpanel"
                        aria-labelledby="edit_menus-tab">
                        <div class="card-body border-bottom2 px-4 py-3 filter-area">
                            <div class="row align-items-end">
                                <div class="col-md-5 mb-3 mb-md-0">
                                    <label class="font-16 bold black d-block mb-2"
                                        for="menu_group">{{ translate('Select a menu to edit') }}</label>
                                    <select class="theme-input-style w-100" id="menu_group" onchange="getMenuStructure()">
                                        @foreach ($menu_groups as $menu)
                                            <option value="{{ $menu->id }}" class="text-uppercase"
                                                id="menu_option_{{ $menu->id }}"
                                                {{ $selected_menu_group == $menu->id ? 'selected' : '' }}>
                                                {{ $menu->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5 mb-3 mb-md-0" id="language-col">
                                    <label class="font-16 bold black d-block mb-2"
                                        for="language">{{ translate('Translate Menu Into') }}</label>
                                    <select class="theme-input-style w-100" id="language" onchange="getMenuStructure()">
                                        @foreach ($languages as $lang)
                                            <option value="{{ $lang->id }}" class="text-uppercase"
                                                id="lang_{{ $lang->id }}">
                                                {{ $lang->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="px-4 pt-3">
                            <p class="alert alert-info mb-0">You are editing
                                <strong>"{{ getLanguageNameByCode(getDefaultLang()) }}"</strong> version
                            </p>
                        </div> -->

                        <div class="card-body pt-4">
                            <div class="menu-management-frame">
                                <div class="add-menu-item card mb-0 {{ sizeof($menu_groups) > 0 ? '' : 'area-disabled' }}"
                                    id="accordion-container"
                                    style="border-radius: 12px !important; overflow: hidden !important;">
                                    <div class="card-header bg-white border-bottom2 py-3">
                                        <h4 class="mb-0">{{ translate('Add Menu Items') }}</h4>
                                    </div>
                                    <div class="card-body p-0">
                                        <form action="#" class="nav-menu-meta">
                                            <div class="card accordion border-0">
                            <!-- Custom menu-->
                            <div data-accordion-tab="toggle">
                                <div class="accordion-title d-flex gap-10 align-items-center justify-content-between">
                                    <h5>{{ translate('Custom Links') }}</h5>
                                    <i class="icofont-caret-down"></i>
                                </div>
                                <div class="accordion-content">
                                    <p>
                                        <label class="mb-2 black" for="custom-menu-item-url">{{ translate('URL') }}</label>
                                        <input id="custom_url" type="text" class="theme-input-style menu-item-textbox"
                                            placeholder="https://">
                                    </p>
                                    <p>
                                        <label class="mb-2 black"
                                            for="custom-menu-item-name">{{ translate('Link Text') }}</label>
                                        <input id="custom_link" type="text" class="theme-input-style menu-item-textbox">
                                    </p>
                                    <p class="button-controls">
                                        <span class="add-to-menu">
                                            <button type="button" class="submit-add-to-menu "
                                                onclick="addCustomMenu()">{{ translate('Add to Menu') }}</button>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <!-- /Custom Menu-->

                            <!-- Default Category Menu-->
                            <div data-accordion-tab="toggle">
                                <div class="accordion-title d-flex gap-10 align-items-center justify-content-between">
                                    <h5>{{ translate('Categories') }}</h5>
                                    <i class="icofont-caret-down"></i>
                                </div>
                                <div class="accordion-content">
                                    <ul class="nav nav-tabs small-tabs pl-1" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#categories_recent"
                                                role="tab">{{ translate('Most Recent') }}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#categories_all"
                                                role="tab">{{ translate('View All') }}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#categories_searched"
                                                role="tab">{{ translate('Search') }}</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="categories_recent" role="tabpanel">
                                            <ul class="item-check-list pages-check-list">
                                                @for ($i = 0; $i < sizeof($all_recent_categories); $i++)
                                                    <li>
                                                        <label class="menu-item-title">
                                                            <input type="checkbox" class="menu-item-checkbox"
                                                                id="recent_cat_{{ $all_recent_categories[$i]->id }}">
                                                            {{ $all_recent_categories[$i]->translation('name', getLocale()) }}
                                                        </label>
                                                    </li>
                                                @endfor
                                            </ul>
                                            <p
                                                class="button-controls d-flex justify-content-between gap-10 align-items-center pt-3 border-top2">
                                                <span class="list-controls">
                                                    <input type="checkbox" id="select_recent_cat" class="select-all"
                                                        onclick="selectItemToMenu('#select_recent_cat' , '#categories_recent')">
                                                    <label for="page-tab8"
                                                        class="cursor-pointer">{{ translate('Select All') }}</label>
                                                </span>

                                                <span class="add-to-menu">
                                                    <input type="button" class="submit-add-to-menu" value="Add to Menu"
                                                        onclick="addItemToMenu('#recent_cat_' , {{ json_encode($all_recent_categories) }} , 'category')">
                                                </span>
                                            </p>
                                        </div>
                                        <div class="tab-pane fade" id="categories_all" role="tabpanel">
                                            <ul class="item-check-list pages-check-list">
                                                @for ($i = 0; $i < sizeof($all_categories); $i++)
                                                    <li>
                                                        <label class="menu-item-title">
                                                            <input type="checkbox" class="menu-item-checkbox"
                                                                id="all_cat_{{ $all_categories[$i]->id }}">
                                                            {{ $all_categories[$i]->translation('name', getLocale()) }}
                                                        </label>
                                                    </li>
                                                @endfor
                                            </ul>
                                            <p
                                                class="button-controls d-flex justify-content-between gap-10 align-items-center pt-3 border-top2">
                                                <span class="list-controls">
                                                    <input type="checkbox" id="select_all_cat" class="select-all"
                                                        onclick="selectItemToMenu('#select_all_cat' , '#categories_all')">
                                                    <label for="page-tab8"
                                                        class="cursor-pointer">{{ translate('Select All ') }}</label>
                                                </span>

                                                <span class="add-to-menu">
                                                    <input type="button" class="submit-add-to-menu" value="Add to Menu"
                                                        onclick="addItemToMenu('#all_cat_' , {{ json_encode($all_categories) }} , 'category')">
                                                </span>
                                            </p>
                                        </div>
                                        <div class="tab-pane fade" id="categories_searched" role="tabpanel">
                                            <div class="pt-3">
                                                <input type="search" class="theme-input-style" placeholder="Search"
                                                    id="search_category"
                                                    onkeyup="searchItem('#search_category' , '#searched_category_list' , '{{ route('core.search.category.by.keywords') }}')">
                                            </div>
                                            <div id="searched_category_list">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Default Category Menu-->

                            <!-- Post Menu-->
                            <div data-accordion-tab="toggle">
                                <div class="accordion-title d-flex gap-10 align-items-center justify-content-between">
                                    <h5>{{ translate('Posts') }}</h5>
                                    <i class="icofont-caret-down"></i>
                                </div>
                                <div class="accordion-content">
                                    <ul class="nav nav-tabs small-tabs pl-1" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#post_recent"
                                                role="tab">{{ translate('Most Recent') }}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#post_all"
                                                role="tab">{{ translate('View All') }}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#post_searched"
                                                role="tab">{{ translate('Search') }}</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="post_recent" role="tabpanel">
                                            <ul class="item-check-list pages-check-list">
                                                @for ($i = 0; $i < sizeof($all_recent_posts); $i++)
                                                    <li>
                                                        <label class="menu-item-title">
                                                            <input type="checkbox" class="menu-item-checkbox"
                                                                id="recent_post_{{ $all_recent_posts[$i]->id }}">
                                                            {{ $all_recent_posts[$i]->translation('name', getLocale()) }}
                                                        </label>
                                                    </li>
                                                @endfor
                                            </ul>
                                            <p
                                                class="button-controls d-flex justify-content-between gap-10 align-items-center pt-3 border-top2">
                                                <span class="list-controls">
                                                    <input type="checkbox" id="select_recent_post" class="select-all"
                                                        onclick="selectItemToMenu('#select_recent_post' , '#post_recent')">
                                                    <label for="page-tab8"
                                                        class="cursor-pointer">{{ translate('Select All') }}</label>
                                                </span>

                                                <span class="add-to-menu">
                                                    <input type="button" class="submit-add-to-menu" value="Add to Menu"
                                                        onclick="addItemToMenu('#recent_post_' , {{ json_encode($all_recent_posts) }} , 'post')">
                                                </span>
                                            </p>
                                        </div>
                                        <div class="tab-pane fade" id="post_all" role="tabpanel">
                                            <ul class="item-check-list pages-check-list">
                                                @for ($i = 0; $i < sizeof($all_posts); $i++)
                                                    <li>
                                                        <label class="menu-item-title">
                                                            <input type="checkbox" class="menu-item-checkbox"
                                                                id="all_post_{{ $all_posts[$i]->id }}">
                                                            {{ $all_posts[$i]->translation('name', getLocale()) }}
                                                        </label>
                                                    </li>
                                                @endfor
                                            </ul>
                                            <p
                                                class="button-controls d-flex justify-content-between gap-10 align-items-center pt-3 border-top2">
                                                <span class="list-controls">
                                                    <input type="checkbox" id="select_all_post" class="select-all"
                                                        onclick="selectItemToMenu('#select_all_post' , '#post_all')">
                                                    <label for="page-tab8"
                                                        class="cursor-pointer">{{ translate('Select All ') }}</label>
                                                </span>

                                                <span class="add-to-menu">
                                                    <input type="button" class="submit-add-to-menu" value="Add to Menu"
                                                        onclick="addItemToMenu('#all_post_' , {{ json_encode($all_posts) }} , 'post')">
                                                </span>
                                            </p>
                                        </div>
                                        <div class="tab-pane fade" id="post_searched" role="tabpanel">
                                            <div class="pt-3">
                                                <input type="search" class="theme-input-style" placeholder="Search"
                                                    id="search_post"
                                                    onkeyup="searchItem('#search_post' , '#searched_post_list' , '{{ route('core.search.post.by.keywords') }}')">
                                            </div>
                                            <div id="searched_post_list">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Post menu-->

                            <!-- Page Menu-->
                            <div data-accordion-tab="toggle">
                                <div class="accordion-title d-flex gap-10 align-items-center justify-content-between">
                                    <h5>{{ translate('Pages') }}</h5>
                                    <i class="icofont-caret-down"></i>
                                </div>
                                <div class="accordion-content">
                                    <ul class="nav nav-tabs small-tabs pl-1" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#page_recent"
                                                role="tab">{{ translate('Most Recent') }}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#page_all"
                                                role="tab">{{ translate('View All') }}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#page_searched"
                                                role="tab">{{ translate('Search') }}</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="page_recent" role="tabpanel">
                                            <ul class="item-check-list pages-check-list">
                                                @for ($i = 0; $i < sizeof($all_recent_pages); $i++)
                                                    <li>
                                                        <label class="menu-item-title">
                                                            <input type="checkbox" class="menu-item-checkbox"
                                                                id="recent_page_{{ $all_recent_pages[$i]->id }}">
                                                            {{ $all_recent_pages[$i]->translation('name', getLocale()) }}
                                                        </label>
                                                    </li>
                                                @endfor
                                            </ul>
                                            <p
                                                class="button-controls d-flex justify-content-between gap-10 align-items-center pt-3 border-top2">
                                                <span class="list-controls">
                                                    <input type="checkbox" id="select_recent_page" class="select-all"
                                                        onclick="selectItemToMenu('#select_recent_page' , '#page_recent')">
                                                    <label for="page-tab8"
                                                        class="cursor-pointer">{{ translate('Select All') }}</label>
                                                </span>

                                                <span class="add-to-menu">
                                                    <input type="button" class="submit-add-to-menu" value="Add to Menu"
                                                        onclick="addItemToMenu('#recent_page_', {{ json_encode($all_recent_pages) }} , 'page')">
                                                </span>
                                            </p>
                                        </div>
                                        <div class="tab-pane fade" id="page_all" role="tabpanel">
                                            <ul class="item-check-list pages-check-list">
                                                @for ($i = 0; $i < sizeof($all_pages); $i++)
                                                    <li>
                                                        <label class="menu-item-title">
                                                            <input type="checkbox" class="menu-item-checkbox"
                                                                id="all_page_{{ $all_pages[$i]->id }}">
                                                            {{ $all_pages[$i]->translation('name', getLocale()) }}
                                                        </label>
                                                    </li>
                                                @endfor
                                            </ul>
                                            <p
                                                class="button-controls d-flex justify-content-between gap-10 align-items-center pt-3 border-top2">
                                                <span class="list-controls">
                                                    <input type="checkbox" id="select_all_page" class="select-all"
                                                        onclick="selectItemToMenu('#select_all_page' , '#page_all')">
                                                    <label for="page-tab8"
                                                        class="cursor-pointer">{{ translate('Select All ') }}</label>
                                                </span>

                                                <span class="add-to-menu">
                                                    <input type="button" class="submit-add-to-menu" value="Add to Menu"
                                                        onclick="addItemToMenu('#all_page_', {{ json_encode($all_pages) }} , 'page')">
                                                </span>
                                            </p>
                                        </div>
                                        <div class="tab-pane fade" id="page_searched" role="tabpanel">
                                            <div class="pt-3">
                                                <input type="search" class="theme-input-style" placeholder="Search"
                                                    id="search_page"
                                                    onkeyup="searchItem('#search_page' , '#searched_page_list' , '{{ route('core.search.page.by.keywords') }}')">
                                            </div>
                                            <div id="searched_page_list">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Page menu-->

                            <!-- Tag Menu-->
                            <div data-accordion-tab="toggle">
                                <div class="accordion-title d-flex gap-10 align-items-center justify-content-between">
                                    <h5>{{ translate('Tags') }}</h5>
                                    <i class="icofont-caret-down"></i>
                                </div>
                                <div class="accordion-content">
                                    <ul class="nav nav-tabs small-tabs pl-1" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#tag_recent"
                                                role="tab">{{ translate('Most Recent') }}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#tag_all"
                                                role="tab">{{ translate('View All') }}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#tag_searched"
                                                role="tab">{{ translate('Search') }}</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="tag_recent" role="tabpanel">
                                            <ul class="item-check-list tags-check-list">
                                                @for ($i = 0; $i < sizeof($all_recent_tags); $i++)
                                                    <li>
                                                        <label class="menu-item-title">
                                                            <input type="checkbox" class="menu-item-checkbox"
                                                                id="recent_tag_{{ $all_recent_tags[$i]->id }}">
                                                            {{ $all_recent_tags[$i]->translation('name', getLocale()) }}
                                                        </label>
                                                    </li>
                                                @endfor
                                            </ul>
                                            <p
                                                class="button-controls d-flex justify-content-between gap-10 align-items-center pt-3 border-top2">
                                                <span class="list-controls">
                                                    <input type="checkbox" id="select_recent_tag" class="select-all"
                                                        onclick="selectItemToMenu('#select_recent_tag' , '#tag_recent')">
                                                    <label for="tag-tab8"
                                                        class="cursor-pointer">{{ translate('Select All') }}</label>
                                                </span>

                                                <span class="add-to-menu">
                                                    <input type="button" class="submit-add-to-menu" value="Add to Menu"
                                                        onclick="addItemToMenu('#recent_tag_', {{ json_encode($all_recent_tags) }} , 'tag')">
                                                </span>
                                            </p>
                                        </div>
                                        <div class="tab-pane fade" id="tag_all" role="tabpanel">
                                            <ul class="item-check-list tags-check-list">
                                                @for ($i = 0; $i < sizeof($all_tags); $i++)
                                                    <li>
                                                        <label class="menu-item-title">
                                                            <input type="checkbox" class="menu-item-checkbox"
                                                                id="all_tag_{{ $all_tags[$i]->id }}">
                                                            {{ $all_tags[$i]->translation('name', getLocale()) }}
                                                        </label>
                                                    </li>
                                                @endfor
                                            </ul>
                                            <p
                                                class="button-controls d-flex justify-content-between gap-10 align-items-center pt-3 border-top2">
                                                <span class="list-controls">
                                                    <input type="checkbox" id="select_all_tag" class="select-all"
                                                        onclick="selectItemToMenu('#select_all_tag' , '#tag_all')">
                                                    <label for="tag-tab8"
                                                        class="cursor-pointer">{{ translate('Select All ') }}</label>
                                                </span>

                                                <span class="add-to-menu">
                                                    <input type="button" class="submit-add-to-menu" value="Add to Menu"
                                                        onclick="addItemToMenu('#all_tag_', {{ json_encode($all_tags) }} , 'tag')">
                                                </span>
                                            </p>
                                        </div>
                                        <div class="tab-pane fade" id="tag_searched" role="tabpanel">
                                            <div class="pt-3">
                                                <input type="search" class="theme-input-style" placeholder="Search"
                                                    id="search_tag"
                                                    onkeyup="searchItem('#search_tag' , '#searched_tag_list' , '{{ route('core.search.tag.by.keywords') }}')">
                                            </div>
                                            <div id="searched_tag_list">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Tag menu-->

                            @for ($i = 0; $i < sizeof($menu_items); $i++)
                                @php
                                    $template = $menu_items[$i]->template;
                                @endphp
                                @if (View::exists($template))
                                    @include($template)
                                @endif
                            @endfor
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Add new menu -->
                <div class="menu-structure card mb-0" id="create_menu_group"
                    style="border-radius: 12px !important; overflow: hidden !important;">
                    <div class="card-header bg-white border-bottom2 py-3">
                        <h4 class="mb-0">{{ translate('Create New Menu') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-row mb-20">
                            <div class="col-md-12">
                                <label class="font-14 bold black d-block mb-2"
                                    for="menu_group_name">{{ translate('Menu Name') }}</label>
                                <input name="menu_group_name" id="menu_group_name" type="text" class="theme-input-style w-100"
                                    value="" required>
                                <div class="invalid-input" id="create_menu_name_error"></div>
                            </div>
                        </div>

                        <p class="text-muted mb-3">{{ translate('Give your menu a name, then click Save Menu.') }}</p>

                        <h4 class="mb-3">{{ translate('Menu Settings') }}</h4>
                        <label class="font-14 bold black d-block mb-3">{{ translate('Display Locations') }}</label>
                        @for ($i = 0; $i < sizeof($all_menu_positions); $i++)
                            @php
                                $menu_group = getMenuGroupOnThisPosition($all_menu_positions[$i]->id);
                            @endphp
                            <div class="d-flex align-items-center mb-3">
                                <label class="custom-checkbox position-relative mr-2 mr-md-4">
                                    <input type="checkbox" id="check_{{ $all_menu_positions[$i]->id }}"
                                        name="position[]" value="{{ $all_menu_positions[$i]->id }}">
                                    <span class="checkmark"></span>
                                </label>
                                <label for="check_{{ $all_menu_positions[$i]->id }}">
                                    {{ $all_menu_positions[$i]->position }}
                                    {{ $menu_group != null ? '(' . translate('Currently set to : ') . $menu_group->name . ')' : '' }}
                                </label>
                            </div>
                        @endfor
                    </div>
                    <div class="card-footer bg-white border-top2 d-flex justify-content-end">
                        <button type="button" class="btn long btn-orange"
                            onclick="saveMenuGroup()">{{ translate('Save Menu') }}</button>
                    </div>
                </div>
                <!-- /Add new menu -->

                <!-- Update Menu-->
                <div class="menu-structure card mb-0" id="edit_menu_group"
                    style="border-radius: 12px !important; overflow: hidden !important;">
                    <div class="card-header bg-white border-bottom2 py-3">
                        <div class="form-row align-items-center mb-0">
                            <div class="col-md-12">
                                <label class="font-14 bold black d-block mb-2"
                                    for="editable_menu_group">{{ translate('Menu Name') }}</label>
                                <input name="menu_group" id="editable_menu_group" type="text"
                                    class="theme-input-style w-100" value="">
                                <div class="invalid-input" id="edit_menu_name_error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            {{ translate('Drag the items into the order you prefer. Click the arrow on the right of the item to reveal additional configuration options.') }}
                        </p>
                        <div class="post-body-content mb-4">
                            <ul id="tree"></ul>
                        </div>
                        <hr>
                        <h4 class="mb-3">{{ translate('Menu Settings') }}</h4>
                        <label class="font-14 bold black d-block mb-3">{{ translate('Display Locations') }}</label>
                        @for ($i = 0; $i < sizeof($all_menu_positions); $i++)
                            @php
                                $menu_group = getMenuGroupOnThisPosition($all_menu_positions[$i]->id);
                            @endphp
                            <div class="d-flex align-items-center mb-3">
                                <label class="custom-checkbox position-relative mr-2 mr-md-4">
                                    <input type="checkbox" id="check_e_{{ $all_menu_positions[$i]->id }}"
                                        name="edit_position[]" value="{{ $all_menu_positions[$i]->id }}">
                                    <span class="checkmark"></span>
                                </label>
                                <label for="check_e_{{ $all_menu_positions[$i]->id }}">
                                    {{ $all_menu_positions[$i]->position }}
                                    {{ $menu_group != null ? ' (' . translate('Currently set to') . ': ' . $menu_group->name . ')' : '' }}
                                </label>
                            </div>
                        @endfor
                    </div>
                    <div class="card-footer bg-white border-top2 d-flex justify-content-between align-items-center flex-wrap gap-10">
                        <button type="button" class="btn-link text-danger"
                            onclick="deleteMenuGroup()">{{ translate('Delete Menu') }}</button>
                        <button type="button" class="btn long btn-orange"
                            onclick="updateGroupMenu()">{{ translate('Update Menu') }}</button>
                    </div>
                </div>
                                <!-- /Update Menu-->

                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="manage_location" role="tabpanel"
                        aria-labelledby="manage_location-tab">
                        <div class="card-body">
                            <p class="mb-4">{{ translate('Your theme supports') }} {{ sizeof($all_menu_positions) }}
                                {{ translate('menus. Select which menu appears in each location.') }}
                            </p>
                            <form action="#">
                                <div class="table-responsive">
                                    <table class="menu-locations-table table table-hover" id="menu-locations-table">
                                <thead>
                                    <tr>
                                        <th class="manage-column column-locations">{{ translate('Theme Location') }}</th>
                                        <th class="manage-column column-menus">{{ translate('Assigned Menu') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="menu-locations">
                                    @for ($i = 0; $i < sizeof($all_menu_positions); $i++)
                                        <tr class="menu-locations-row">
                                            @php
                                                $menu_group = getMenuGroupOnThisPosition($all_menu_positions[$i]->id);
                                            @endphp
                                            @if ($menu_group != null)
                                                <td class="menu-location-title">
                                                    <label for="locations-primary-menu"
                                                        class="semi-bold black">{{ $all_menu_positions[$i]->position }}</label>
                                                </td>
                                                <td class="menu-location-menus">
                                                    <select class="theme-input-style w-100"
                                                        id="location_{{ $all_menu_positions[$i]->id }}"
                                                        onchange="hideEditButton('{{ $menu_group->id }}','{{ $all_menu_positions[$i]->id }}')">
                                                        @foreach ($menu_groups as $menu)
                                                            <option value="{{ $menu->id }}" class="text-uppercase"
                                                                {{ $menu_group->id == $menu->id ? 'selected' : '' }}>
                                                                {{ $menu->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="locations-row-links">
                                                        <span class="locations-edit-menu-link border-right2 pr-2"
                                                            id="location_edit_{{ $all_menu_positions[$i]->id }}">
                                                            <a
                                                                href="{{ route('core.manage.menus') }}?group_id={{ $menu_group->id }}">
                                                                <span aria-hidden="true">{{ translate(' Edit') }}</span>
                                                            </a>
                                                        </span>
                                                        <span class="locations-add-menu-link pl-2">
                                                            <a href="#" onclick="showMenuCreationForm()">
                                                                {{ translate('Use new menu') }}
                                                            </a>
                                                        </span>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endfor
                                    </tbody>
                                </table>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('core::base.media.partial.media_modal')
@endsection
@section('custom_scripts')
    <!-- <script src="{{ asset('/public/backend/assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('/public/backend/assets/js/menu_tree_sortable.js') }}"></script>
    <script src="{{ asset('/public/backend/assets/js/menu_script.js') }}"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="{{ asset('/public/backend/assets/plugins/select2/select2.min.js') }}"></script> -->

    <script src="{{ asset('backend/assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/menu_tree_sortable.js') }}"></script>
    <script src="{{ asset('backend/assets/js/menu_script.js') }}"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="{{ asset('backend/assets/plugins/select2/select2.min.js') }}"></script>
    <script>
        let data = [];
        let temp_data = [];
        let tree_data = [];
        let data_for_level_count = [];

        let sortable = new TreeSortable();
        let $tree = $("#tree");
        let $content = null;

        (function($) {
            "use strict";

            initDropzone()
            $(document).ready(function() {
                $('#menu_group').select2({
                    theme: "classic"
                });
                $('#menu_group_2').select2({
                    theme: "classic"
                });

                filtermedia()

                is_for_browse_file = true
                enable_multiple_file_select = false
                let total_menu_group = {{ sizeof($menu_groups) }}

                if (total_menu_group > 0) {
                    hideElement(['#create_menu_group', '#menu_group_on_create'])
                    getMenuStructure()
                } else {
                    hideElement(['#edit_menu_group'])
                    $('#menu_group').append(
                        '<option value="-1" class="text-uppercase" id="menu_option_0" selected>Select Menu' +
                        '</option>')
                }
            });

            $content = data.map(sortable.createBranch)
            $tree.html($content);
            sortable.run();
        })(jQuery);



        /**
         * Set tree view structure
         */
        function setTreeViewData(parent_id, id, level) {
            "use strict";

            for (let i = 0; i < data.length; i++) {
                if (typeof data[i] != 'undefined') {
                    if (data[i].id == id) {
                        data[i].level = parseInt(level)
                        if (parent_id + '' == 'undefined') {
                            data[i].parent_id = null
                        } else {
                            data[i].parent_id = parent_id
                        }
                    }
                }
            }
        }

        /**
         * Will update group wise menus
         */
        function updateGroupMenu() {
            "use strict";
            let menu_group_id = $('#menu_group :selected').val()
            let menu_group_name = $('#editable_menu_group').val()
            let lang_id = $('#language').val()

            var all_location_id = document.querySelectorAll('input[name="edit_position[]"]:checked');
            var all_location = [];

            for (var x = 0, l = all_location_id.length; x < l; x++) {
                all_location.push(all_location_id[x].value);
            }

            $.post("{{ route('core.update.menu.structure') }}", {
                    _token: '{{ csrf_token() }}',
                    menu_group_id: menu_group_id,
                    menu_group_name: menu_group_name,
                    all_position: all_location,
                    lang_id: lang_id,
                    sorting: 1
                },
                function(details, status) {
                    if (details.demo_mode) {
                        toastr.error(details.message, "Alert!");
                    } else {
                        toastr.success("Menu group updated successfully", "Success!");
                    }
                }
            ).fail(function(xhr, status, error) {
                let error_response = JSON.parse(xhr.responseText)
                let error_message = error_response.message
                let errors = {}
                if (error_response.hasOwnProperty('errors')) {
                    errors = error_response.errors
                    if (errors.hasOwnProperty('menu_group_name')) {
                        $('#edit_menu_name_error').html(errors.menu_group_name)
                    } else {
                        toastr.error(error_message, "Error!");
                    }
                } else {
                    toastr.error(error_message, "Error!");
                }
            });
        }

        /*
         * Delete menu group
         **/
        function deleteMenuGroup() {
            "use strict";
            let menu_group_id = $('#menu_group :selected').val()

            $.post("{{ route('core.delete.menu.group') }}", {
                    _token: '{{ csrf_token() }}',
                    menu_group_id: menu_group_id
                },
                function(details, status) {
                    if (details.demo_mode) {
                        toastr.error(details.message, "Alert!");
                    } else {
                        toastr.success("Menu group deleted successfully", "Success!");
                    }
                }
            ).fail(function(xhr, status, error) {
                toastr.error("Unable to delete menu group", "Error!");
            });
        }

        /**
         * Save menu information
         */
        function saveMenuInfo(id) {
            "use strict";
            let url = DOMPurify.sanitize($('#url_' + id).val()).replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g,
                '&gt;');
            let name = DOMPurify.sanitize($('#menu_' + id).val()).replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(
                />/g, '&gt;');
            let icon = $('#menu_icon_' + id).val()
            let menu_group_id = $('#menu_group :selected').val()
            let lang_id = $('#language').val()

            $.post("{{ route('core.update.tree.view.data') }}", {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    url: url,
                    name: name,
                    icon: icon,
                    menu_group_id: menu_group_id,
                    lang_id: lang_id,
                },
                function(details, status) {
                    if (details.demo_mode) {
                        toastr.error(details.message, "Alert!");
                    } else {
                        getMenuStructure()
                        toastr.success("Menu information updated successfully", "Success!");
                    }
                }
            ).fail(function(xhr, status, error) {
                toastr.error("Unable to update menu information", "Error!");
            });
        }

        /**
         * Store new menu group 
         */
        function saveMenuGroup() {
            "use strict";
            let menu_group_name = $('#menu_group_name').val()

            var all_location_id = document.querySelectorAll('input[name="position[]"]:checked');
            var all_location = [];

            for (var x = 0, l = all_location_id.length; x < l; x++) {
                all_location.push(all_location_id[x].value);
            }

            $.post("{{ route('core.add.menu.group') }}", {
                    _token: '{{ csrf_token() }}',
                    menu_name: menu_group_name,
                    all_position: all_location
                },
                function(details, status) {
                    if (details.demo_mode) {
                        toastr.error(details.message, "Alert!");
                    } else {
                        window.location.replace('{{ route('core.manage.menus') }}');
                    }
                }
            ).fail(function(xhr, status, error) {
                let error_response = JSON.parse(xhr.responseText)
                let error_message = error_response.message
                let errors = {}
                if (error_response.hasOwnProperty('errors')) {
                    errors = error_response.errors
                    if (errors.hasOwnProperty('menu_name')) {
                        $('#create_menu_name_error').html(errors.menu_name)
                    } else {
                        toastr.error(error_message, "Error!");
                    }
                } else {
                    toastr.error(error_message, "Error!");
                }
            });
        }

        /**
         * Remove specific menu from tree view
         */
        function removeMenu(id, index) {
            "use strict";
            if (id != -1) {
                $.post("{{ route('core.delete.tree.view.data') }}", {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    function(details, status) {
                        if (details.demo_mode) {
                            toastr.error(details.message, "Alert!");
                        } else {
                            getMenuStructure()
                            toastr.success("Menu deletion successful", "Success!");
                        }
                    }
                ).fail(function(xhr, status, error) {
                    toastr.error("You can not delete a parent menu", "Error!");
                });
            } else {
                data.splice(index, 1);
                $content = data.map(sortable.createBranch);
                $tree.html($content);
                sortable.run();
            }
        }

        /**
         * Will set menu structure for specific menu group
         */
        function getMenuStructure() {
            "use strict";
            let menu_group_id = $('#menu_group :selected').val()

            if (menu_group_id + '' !== '-1') {
                let menu_group_name = $('#menu_group :selected').text()
                let lang_id = $('#language').val()

                let lang_name = $('#language :selected').text().trim();
                $('.alert > strong').text('"' + lang_name + '"');

                showElement(['#edit_menu_group', '#language-col'])
                hideElement(['#create_menu_group'])
                $('#accordion-container').removeClass('area-disabled')

                $.post("{{ route('core.tree.view.data') }}", {
                        _token: '{{ csrf_token() }}',
                        menu_group_id: menu_group_id,
                        lang_id: lang_id,
                        menu_group_name: menu_group_name,
                    },

                    function(details, status) {
                        let menu_position = details.menu_position
                        menu_group_name = details.menu_group_name

                        menu_position = menu_position.map(function(value) {
                            return parseInt(value);
                        });

                        var all_location_id = document.querySelectorAll('input[name="edit_position[]"]');
                        var all_location = [];

                        for (var x = 0, l = all_location_id.length; x < l; x++) {
                            if (menu_position.indexOf(parseInt(all_location_id[x].value)) !== -1) {
                                $('#' + all_location_id[x].id).prop('checked', true);
                            } else {
                                $('#' + all_location_id[x].id).prop('checked', false);
                            }
                        }
                        data = details.data;
                        $content = data.map(sortable.createBranch);
                        $tree.html($content);
                        sortable.run();

                        $('#editable_menu_group').val(menu_group_name)
                        if (lang_id != '{{ getGeneralSetting('default_language') }}') {
                            $('#accordion-container').addClass('area-disabled')
                            $('.hide_on_lang_change').addClass('area-disabled')
                        } else {
                            $('#accordion-container').removeClass('area-disabled')
                            $('.hide_on_lang_change').removeClass('area-disabled')
                        }
                    }
                ).fail(function(xhr, status, error) {
                    toastr.error("Unable to fetch data", "Error!");
                });
            }
        }

        /**
         * show menu creation form
         */
        function showMenuCreationForm() {
            "use strict";

            $('.alert').html(`You are creating <strong>"{{ getLanguageNameByCode(getDefaultLang()) }}"</strong> version`);

            showElement(['#create_menu_group'])
            hideElement(['#edit_menu_group'])
            if (!$('#edit_menus-tab').hasClass('active')) {
                $('#edit_menus-tab').addClass('active')
                $('#manage_location-tab').removeClass('active')

                $('#manage_location').removeClass('active')
                $('#manage_location').removeClass('show')

                $('#edit_menus').addClass('active')
                $('#edit_menus').addClass('show')
            }

            if ($('#menu_option_0').length == 0) {
                $('#menu_group').append(
                    '<option value="-1" class="text-uppercase" id="menu_option_0" selected>Select Menu' + '</option>')
            } else {
                $('#menu_option_0').remove()
                $('#menu_group').append(
                    '<option value="-1" class="text-uppercase" id="menu_option_0" selected>Select Menu' + '</option>')
            }

            $('#accordion-container').addClass('area-disabled')
            $('#language-col').hide()
        }

        /*
         * Search default product categories by keywords
         */
        function searchItem(search_keyword, search_list, route) {
            "use strict";
            let keywords = $(search_keyword).val()
            $.post(route, {
                    _token: '{{ csrf_token() }}',
                    keyword: keywords
                },
                function(details, status) {
                    $(search_list).html(details)
                }
            ).fail(function(xhr, status, error) {
                let error_response = JSON.parse(xhr.responseText)
                let error_message = error_response.message
                toastr.error(error_message, "Error!");
            });
        }

        /**
         * Add custom menu to the existing menu structure
         */
        function addCustomMenu() {
            "use strict";

            let custom_url = DOMPurify.sanitize($('#custom_url').val()).replace(/"/g, '&quot;').replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
            let custom_link = DOMPurify.sanitize($('#custom_link').val()).replace(/"/g, '&quot;').replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
            let image_id = $('#menu_icon_id').val();
            let image_src = $('#menu_icon_preview').attr('src');
            let image_alt = $('#menu_icon_preview').attr('alt');

            if (custom_link != "") {
                let new_menu = {
                    index: data.length,
                    alt: image_alt,
                    icon: image_id,
                    id: -1,
                    level: 1,
                    parent_id: 0,
                    path: image_src,
                    title: custom_link,
                    url: custom_url,
                    preview_url: custom_url
                }

                data.push(new_menu)

                $content = data.map(sortable.createBranch);
                $tree.html($content);
                sortable.run();
            }
            updateMenuStructure(data)
        }

        /**
         * Select all menu items after clicking select all button
         */
        function selectItemToMenu(select_id, list_id) {
            "use strict";
            if ($(select_id).is(':checked')) {
                $('li', $(list_id)).each(function() {
                    $(this).children().children().attr('checked', true);
                });
            } else {
                $('li', $(list_id)).each(function() {
                    $(this).children().children().attr('checked', false);
                });
            }
        }

        /**
         * Add product types item menu to existing menu structure
         */
        function addItemToMenu(id_prefix, all_items, menu_type) {
            "use strict";
            all_items = JSON.parse(JSON.stringify(all_items));

            for (let i = 0; i < all_items.length; i++) {
                if ($(id_prefix + all_items[i].id).is(':checked')) {

                    let new_menu = {
                        index: data.length,
                        alt: null,
                        icon: null,
                        id: -1,
                        level: 1,
                        parent_id: 0,
                        menu_type_id: all_items[i].id,
                        menu_type: menu_type,
                        path: null,
                        title: all_items[i].name,
                        url: all_items[i].permalink,
                        preview_url: all_items[i].preview_url
                    }

                    data.push(new_menu)

                    $content = data.map(sortable.createBranch);
                    $tree.html($content);
                    sortable.run();
                }
            }
            updateMenuStructure(data)
        }


        /**
         * Hide edit buttong from menu location list after selecting new menu
         */
        function hideEditButton(menu_group_id, menu_position_id) {
            "use strict";
            let selected_menu_group_id = $("#location_" + menu_position_id + " option:selected").val()
            if (menu_group_id + "" != selected_menu_group_id) {
                $("#location_edit_" + menu_position_id).hide()
            } else {
                $("#location_edit_" + menu_position_id).show()
            }
        }

        /*
         * will request to update menu structure 
         */
        function updateMenuStructure(list) {
            "use strict";
            let menu_group_id = $('#menu_group :selected').val()
            $.post("{{ route('core.update.menu.structure.on.sort') }}", {
                    _token: '{{ csrf_token() }}',
                    data: list,
                    menu_group_id: menu_group_id
                },
                function(details, status) {
                    if (details.demo_mode) {
                        toastr.error(details.message, "Alert!");
                    } else {
                        getMenuStructure()
                        toastr.success("Menu list updated successfully", "Success!");
                    }
                }
            ).fail(function(xhr, status, error) {
                let error_response = JSON.parse(xhr.responseText)
                let error_message = error_response.message
                toastr.error(error_message, "Error!");
            });
        }
    </script>
@endsection
