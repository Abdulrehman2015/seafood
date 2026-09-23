<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

app()->setLocale('bm');
echo "LOCALE: " . app()->getLocale() . "\n";
echo "walkin.catalogue_title: " . __t('walkin.catalogue_title') . "\n";
echo "walkin.subtitle: " . __t('walkin.subtitle') . "\n";
echo "walkin.step_qr: " . __t('walkin.step_qr') . "\n";
echo "walkin.step_pick: " . __t('walkin.step_pick') . "\n";
echo "walkin.step_pay: " . __t('walkin.step_pay') . "\n";
echo "walkin.step_collect: " . __t('walkin.step_collect') . "\n";
echo "walkin.in_your_cart: " . __t('walkin.in_your_cart') . "\n";
echo "walkin.pay_and_collect: " . __t('walkin.pay_and_collect') . "\n";
echo "nav.products: " . __t('nav.products') . "\n";
echo "nav.walkin_menu: " . __t('nav.walkin_menu') . "\n";
