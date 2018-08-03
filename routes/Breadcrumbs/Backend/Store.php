<?php

Breadcrumbs::register('admin.store.index', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push(trans('labels.backend.store.management'), route('admin.store.index'));
});

Breadcrumbs::register('admin.store.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.store.index');
    $breadcrumbs->push(trans('labels.backend.store.create'), route('admin.store.create'));
});

Breadcrumbs::register('admin.store.edit', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('admin.store.index');
    $breadcrumbs->push(trans('labels.backend.store.edit'), route('admin.store.edit', $id));
});
