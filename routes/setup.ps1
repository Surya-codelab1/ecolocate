$files = @(
"app/Http/Controllers/Admin/DashboardController.php",
"app/Http/Controllers/Admin/FacilityRequestController.php",
"app/Http/Controllers/Admin/FacilityController.php",
"app/Http/Controllers/Admin/DeviceController.php",
"app/Http/Controllers/Admin/DeviceRequestController.php",
"app/Http/Controllers/Admin/CityController.php",
"app/Http/Controllers/Admin/ContactMessageController.php",
"app/Http/Controllers/Admin/PickupController.php",
"app/Http/Controllers/Admin/AnalyticsController.php",
"app/Http/Controllers/Facility/DashboardController.php",
"app/Http/Controllers/Facility/FacilityProfileController.php",
"app/Http/Controllers/Facility/PickupController.php",
"routes/admin.php",
"routes/facility.php",
"resources/views/admin/layouts/app.blade.php",
"resources/views/admin/layouts/sidebar.blade.php",
"resources/views/admin/layouts/topbar.blade.php",
"resources/views/admin/dashboard.blade.php",
"resources/views/admin/facility-requests/index.blade.php",
"resources/views/admin/facility-requests/show.blade.php",
"resources/views/admin/facilities/index.blade.php",
"resources/views/admin/devices/index.blade.php",
"resources/views/admin/devices/create.blade.php",
"resources/views/admin/devices/edit.blade.php",
"resources/views/admin/device-requests/index.blade.php",
"resources/views/admin/cities/index.blade.php",
"resources/views/admin/messages/index.blade.php",
"resources/views/admin/pickups/index.blade.php",
"resources/views/admin/analytics/index.blade.php",
"public/assets/admin/js/counter.js",
"public/assets/admin/js/charts.js"
)

foreach ($f in $files) {
    New-Item -ItemType File -Force -Path $f
    Write-Host "Created: $f"
}
