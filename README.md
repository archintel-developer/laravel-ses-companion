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

### You can beautify the vue template created in the package found in resources folder __js/ArchintelDev/SesCompanion/__

add the assets in your **app.js** file
```js
require('./ArchintelDev/SesCompanion/app');
```

Run this command **npm run dev**

To use the created dashboard, redirect to route __dashboard__