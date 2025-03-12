<?php

use App\Models\User;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Spatie\Permission\Models\Role;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('dashboard'));
});

Breadcrumbs::for('karyawan', function (BreadcrumbTrail $trail) {
    $trail->push('Data Karyawan', route('user-management.create'));
});

// Home > Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Dashboard', route('dashboard'));
});

Breadcrumbs::for('user-management', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Data Karyawan', route('user-management.index'));
});

Breadcrumbs::for('user-management-create', function (BreadcrumbTrail $trail) {
    $trail->parent('karyawan');
    $trail->push('Tambah Data Karyawan', route('user-management.create'));
});

Breadcrumbs::for('user-management-edit', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('karyawan');
    $trail->push('Edit Data Karyawan', route('user-management.edit', $user->id));
});

Breadcrumbs::for('log-absen-index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Log Absen Karyawan', route('log-absen.index'));
});

Breadcrumbs::for('log-absen-detail', function (BreadcrumbTrail $trail, $logAbsen) {
    $trail->parent('log-absen-index');
    $trail->push('Detail Absen Karyawan', route('log-absen.detail', $logAbsen->id));
});

Breadcrumbs::for('pengajuan-cuti-index', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('home');
    $trail->push('Pengajuan Cuti', route('pengajuan-cuti.index', $id));
});

Breadcrumbs::for('data-cuti', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Data Cuti', route('pengajuan-cuti.index-admin'));
});

Breadcrumbs::for('data-departemen', function (BreadcrumbTrail $trail) {
    $trail->parent('karyawan');
    $trail->push('Data Departemen', route('departemen.index'));
});

Breadcrumbs::for('data-jabatan', function (BreadcrumbTrail $trail) {
    $trail->parent('karyawan');
    $trail->push('Data Jabatan', route('jabatan.index'));
});

Breadcrumbs::for('data-izin-karyawan', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('home');
    $trail->push('Data Izin', route('log-absen.izinKaryawan', $id));
});