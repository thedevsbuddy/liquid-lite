

<p align="center">
<a href="https://travis-ci.org/thedevsbuddy/liquid-lite"><img src="https://travis-ci.org/thedevsbuddy/liquid-lite.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/thedevsbuddy/liquid-lite"><img src="https://img.shields.io/packagist/dt/thedevsbuddy/liquid-lite" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/thedevsbuddy/liquid-lite"><img src="https://img.shields.io/packagist/v/thedevsbuddy/liquid-lite" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/thedevsbuddy/liquid-lite"><img src="https://img.shields.io/packagist/l/thedevsbuddy/liquid-lite" alt="License"></a>
</p>


# Liquid Lite

Just a helper package for our 
<a target="_blank" href="https://github.com/thedevsbuddy/laravel-adminr">Laravel AdminR</a> project.

## Installation
```bash
composer require thedevsbuddy/liquid-lite
```
### Publish assets
```bash
php artisan vendor:publish --provider="Devsbuddy\LiquidLite\Providers\LiquidLiteServiceProvider" --tag=laravel-assets
```

### Publish Config
```bash
php artisan vendor:publish --provider="Devsbuddy\LiquidLite\Providers\LiquidLiteServiceProvider" --tag=laravel-config
```

