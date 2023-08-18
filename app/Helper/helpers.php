<?php

use App\Models\Category;
use App\Models\Group;

function getAllCate()
{
    $cate = new Category();
    return $cate->where('active', '=', '2')->get();
}
function getAllGroup()
{
    $group = new Group;
    return $group->get();
}
function isRole($dataArr, $moduleName, $role = 'view')
{
    if (!empty($dataArr[$moduleName])) {
        $roleArr = $dataArr[$moduleName];
        if (!empty($roleArr) && in_array($role, $roleArr)) {
            return true;
        }
    }
    return false;
}
