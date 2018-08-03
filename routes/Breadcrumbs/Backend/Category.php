<?php

Breadcrumbs::register('admin.category.index', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push(trans('labels.backend.category.management'), route('admin.category.index'));
});

Breadcrumbs::register('admin.category.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.category.index');
    $breadcrumbs->push(trans('labels.backend.category.create'), route('admin.category.create'));
});

Breadcrumbs::register('admin.category.edit', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('admin.category.index');
    $breadcrumbs->push(trans('labels.backend.category.edit'), route('admin.category.edit', $id));
});

Breadcrumbs::register('admin.category.show', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('admin.category.index');
    $breadcrumbs->push($id->name, route('admin.category.show', $id));
});

Breadcrumbs::register('admin.category.subcategory.create', function ($breadcrumbs, $id) {
    $category = \App\Models\Category::where('id', $id)->first();
    $breadcrumbs->parent('admin.category.show', $category);
    $breadcrumbs->push(trans('labels.backend.category.subcategory.create'), route('admin.category.subcategory.create', $id));
});

Breadcrumbs::register('admin.category.subcategory.edit', function ($breadcrumbs, $id, $subcategoryid) {
    $category = \App\Models\Category::where('id', $id)->first();
    $subcategory = \App\Models\Category::where('id', $subcategoryid)->first();
    $breadcrumbs->parent('admin.category.show', $category);
    $breadcrumbs->push(trans('labels.backend.category.subcategory.edit'), route('admin.category.subcategory.edit', [$id, $subcategory]));
});

Breadcrumbs::register('admin.category.subcategory.subshow', function ($breadcrumbs, $id, $subcategoryid) {
    $category = \App\Models\Category::where('id', $id)->first();
    $subcategory = \App\Models\Category::where('id', $subcategoryid)->first();
    $breadcrumbs->parent('admin.category.show', $category);
    $breadcrumbs->push($subcategory->name, route('admin.category.subcategory.subshow', [$id,$subcategory]));
});

Breadcrumbs::register('admin.category.subcategory.subcreate', function ($breadcrumbs, $id, $subcategoryid) {
    $breadcrumbs->parent('admin.category.subcategory.subshow', $id, $subcategoryid);
    $breadcrumbs->push(trans('labels.backend.category.subcategory.create'), route('admin.category.subcategory.subcreate', [$id,$subcategoryid]));
});

Breadcrumbs::register('admin.category.subcategory.subedit', function ($breadcrumbs, $id, $subcategoryid, $categoryid) {
    $topcategory = \App\Models\Category::where('id', $id)->first();
    $category = \App\Models\Category::where('id', $categoryid)->first();

    if($topcategory->parent == 0)
    {
        $breadcrumbs->parent('admin.category.subcategory.subshow', $id, $subcategoryid);
        $breadcrumbs->push('Edit '.$category->name, route('admin.category.subcategory.subedit', [$id,$subcategoryid, $categoryid]));
    }
    elseif($topcategory->parent > 0)
    {
        $breadcrumbs->parent('admin.category.subcategory.subcategoryshow', $topcategory->parent, $id, $subcategoryid);
        $breadcrumbs->push('Edit '.$category->name, route('admin.category.subcategory.subedit', [$id,$subcategoryid, $categoryid]));
    }
});

Breadcrumbs::register('admin.category.subcategory.subcategoryshow', function ($breadcrumbs, $topid, $id, $subcategoryid) {
    $topcategory = \App\Models\Category::where('id', $topid)->first();
    $category = \App\Models\Category::where('id', $id)->first();
    $subcategory = \App\Models\Category::where('id', $subcategoryid)->first();

    $breadcrumbs->parent('admin.category.subcategory.subshow', $topid, $id);
    $breadcrumbs->push($subcategory->name, route('admin.category.subcategory.subcategoryshow', [$topid, $id,$subcategory]));
});


Breadcrumbs::register('admin.category.subcategory.subcategorycreate', function ($breadcrumbs, $topid, $upperid, $categoryid) {
    $breadcrumbs->parent('admin.category.subcategory.subcategoryshow', $topid, $upperid, $categoryid);
    $breadcrumbs->push(trans('labels.backend.category.subcategory.create'), route('admin.category.subcategory.subcategorycreate', [$topid,$upperid,$categoryid]));
});