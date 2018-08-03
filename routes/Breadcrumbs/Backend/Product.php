<?php

Breadcrumbs::register('admin.product.index', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push(trans('labels.backend.product.management'), route('admin.product.index'));
});

Breadcrumbs::register('admin.product.newproduct', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('admin.product.index');
    $breadcrumbs->push(trans('labels.backend.product.create'), route('admin.product.newproduct', $id));
});

Breadcrumbs::register('admin.product.viewproduct', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('admin.product.index');
    $breadcrumbs->push(trans('labels.backend.product.view'), route('admin.product.viewproduct', $id));
});

Breadcrumbs::register('admin.product.editproduct', function ($breadcrumbs, $id) {
    $productinfo = \App\Models\Product::where('id', $id)->first();

    $breadcrumbs->parent('admin.product.index', ['store'=> $productinfo->store]);
    $breadcrumbs->push(trans('labels.backend.product.edit'), route('admin.product.editproduct', $id));
});

