# ses-companion
A laravel-ses companion to create a group and adding subscriber to a group, send email to groups subscriber.

Install via composer
```
composer require archintel-dev/ses-companion
```
in your terminal run this commanp
```
php artisan vendor:publish
```

add the assets in your **app.js** file
```js
require('./ArchintelDev/SesCompanion/app');
```

### Adding the component in your blade
```
will be provided soon
```
for dashboard :
```html
<dashboard></dashboard>
```
for import subscribers: **add in your routes**
```html
<import-subscriber></import-subscriber>
```
for view subscribers on group:
```php
Route::get('client/{uuid}', ['uses' => 'ArchintelDev\SesCompanion\Controllers\ManagementController@show_subscribers']);
```

### 