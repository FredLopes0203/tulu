<?php

Breadcrumbs::register('admin.brand.index', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push(trans('labels.backend.brand.management'), route('admin.brand.index'));
});

Breadcrumbs::register('admin.brand.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.brand.index');
    $breadcrumbs->push(trans('labels.backend.brand.create'), route('admin.brand.create'));
});

Breadcrumbs::register('admin.brand.edit', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('admin.brand.index');
    $breadcrumbs->push(trans('labels.backend.brand.edit'), route('admin.brand.edit', $id));
});
