<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\ShippingZoneController;
use App\Http\Controllers\Api\AdvertisementController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AttributeValueController;
// use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;



// RateLimiter::for('checkout', function (Request $request) {
//     return Limit::perHour(80)->by($request->ip());
// });

Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/admin/login', [AuthController::class, 'login']);

    // 1. PUBLIC ROUTES (React Storefront)
    Route::get('/public/shipping-zones', [ShippingZoneController::class, 'index']);
    Route::apiResource('shipping-zones', ShippingZoneController::class)->except(['index', 'show']);




    // product & category
    Route::get('/public/products', [StoreController::class, 'getProducts']);
    Route::get('/public/product/{product}', [ProductController::class, 'show']);

    Route::get('/public/categories', [StoreController::class, 'getCategories']);

    // build your pc
    Route::get('/public/pc-configurator', [ProductController::class, 'getPcComponents']);

    Route::get('/public/advertisements', [AdvertisementController::class, 'index']);

    Route::get('/public/home-sections', [SectionController::class, 'getHomeSections']);
    Route::get('/public/store-navigation', [CategoryController::class, 'getStoreNavigation']);

    Route::post('/public/cart/recommendations', [ProductController::class, 'getCartRecommendations']);

    // مسار جلب الفلاتر المتوافقة مع السكشن للمستخدمين في الفرونت إند
    Route::get('/public/sections/filters', [ProductController::class, 'getSectionFilters']);

    // banners
    Route::get('/public/banners', [BannerController::class, 'getPublicBanners']);

    // orders
    Route::get('/orders/track/{trackingCode}', [OrderController::class, 'trackOrder']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancelPublicOrder']);



    Route::get('public/tags', [TagController::class, 'index']);
    // 2. PROTECTED ROUTES (React Admin Panel)
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('sections', SectionController::class);
        Route::apiResource('brands', BrandController::class);
        Route::apiResource('products', ProductController::class);

        // Admin Notifications
        Route::get('/admin/notifications', function (Request $request) {
            return response()->json($request->user()->unreadNotifications);
        });
        Route::post('/admin/notifications/{id}/mark-as-read', function (Request $request, $id) {
            $request->user()->notifications()->where('id', $id)->first()->markAsRead();
            return response()->json(['message' => 'Marked as read']);
        });

        Route::get('/admin/banners', [BannerController::class, 'index']);
        Route::post('/admin/banners', [BannerController::class, 'store']);
        Route::put('/admin/banners/{id}', [BannerController::class, 'update']);
        Route::delete('/admin/banners/{id}', [BannerController::class, 'destroy']);

        // Admin Protected Orders Routes
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::post('/orders/{order}', [OrderController::class, 'update']);
        Route::delete('/orders/{order}', [OrderController::class, 'destroy']);

        // Route::get('/dashboard', [AdminDashboardController::class, 'index']);
        Route::post('/settings', [SettingController::class, 'update']);


        // تجميعة مسارات الأدمن للخصائص والمواصفات الديناميكية الاحترافية
        Route::prefix('admin')->group(function () {
            Route::apiResource('advertisements', AdvertisementController::class);

            // مسارات الـ Attributes الأساسية
            Route::get('/attributes', [AttributeController::class, 'index']);
            Route::post('/attributes', [AttributeController::class, 'store']);
            Route::post('/attributes/{attribute}', [AttributeController::class, 'update']);
            Route::delete('/attributes/{attribute}', [AttributeController::class, 'destroy']);

            // مسارات الـ Attribute Values الفرعية
            Route::get('/attributes/{attribute}/values', [AttributeValueController::class, 'getValuesByAttribute']);
            Route::post('/attribute-values', [AttributeValueController::class, 'store']);
            Route::put('/attribute-values/{attributeValue}', [AttributeValueController::class, 'update']);
            Route::delete('/attribute-values/{attributeValue}', [AttributeValueController::class, 'destroy']);

            // مسارات ربط المواصفات بالأقسام وجلبها كاملة
            Route::post('/sections/{section}/attributes', [SectionController::class, 'syncAttributes']);
            Route::get('/sections/{section}/attributes', [SectionController::class, 'getAttributes']);
            Route::get('/sections/{section}/full-attributes', [SectionController::class, 'getFullAttributes']);

            Route::apiResource('tags', TagController::class);

            Route::get('/dashboard/main', [AdminDashboardController::class, 'index']);
            Route::get('/orders-by-status', [AdminDashboardController::class, 'getOrdersByStatus']);
            Route::get('/sections/inventory-stats', [AdminDashboardController::class, 'getSectionComponentStats']);
        });
    });

    Route::get('/public/settings', [SettingController::class, 'index']);
});
