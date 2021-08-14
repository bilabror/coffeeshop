<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['dashboard'] = 'dashboard/index';
$route['dashboard/laporan/produk-terjual'] = 'dashboard/laporan/produk_terjual';
$route['dashboard/setting/shop-address'] = 'dashboard/setting/shop_address';
$route['dashboard/setting/bank-account'] = 'dashboard/setting/bank_account';
$route['dashboard/booking-tempat'] = 'dashboard/booking_tempat/index';
$route['dashboard/qrcode'] = 'dashboard/index/qrcode';




$route['login'] = 'auth/login'; // halaman login
$route['daftar'] = 'auth/daftar'; // halaman registrasi
$route['logout'] = 'auth/logout'; // aksi logout
$route['base_url'] = 'home/r_base_url'; // redirect user ke base url
$route['produk/(:any)'] = 'home/produk/$1'; // halaman detail produk


$route['user/ganti-password'] = 'user/changepw';
$route['lupa-password'] = 'register/forgotpw';
$route['pesanan-saya'] = 'home/myorder';
$route['scan'] = 'home/scan';
$route['order-vqrcode/(:any)'] = 'home/order_vqrcode/$1';
$route['booking-tempat'] = 'home/booking_tempat';
$route['pesanan-saya/(:any)'] = 'home/detail_order/$1';
$route['riwayat/pesanan/detail/(:any)'] = 'riwayat/detail_pesanan/$1';
$route['riwayat/booking/detail/(:any)'] = 'riwayat/detail_booking/$1';
$route['bukti-pembayaran/(:any)'] = 'home/bukti_pembayaran/$1';
$route['membayar/(:any)'] = 'home/pay/$1';
$route['pay/(:any)'] = 'pay/index/$1';
$route['kategori/(:any)'] = 'home/kategori/$1';
$route['tracking/(:any)'] = 'home/tracking/$1';
$route['poinku/(:any)'] = 'home/produk_poin/$1';
$route['notifikasi'] = 'home/notifikasi';