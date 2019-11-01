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

## Usage
to send an email with all tracking enable
```php
SesMail::enableAllTracking()->to('hello@example.com')->send(new Mailable);
```
you can read the documentation [here](https://packagist.org/packages/archintel-dev/laravel-ses) or the original package [here](https://packagist.org/packages/oliveready7/laravel-ses)

To send an email with all tracking enable and to set data to specific account, you can add **setAccount()** where the value is __client_uuid__
```php
SesMail::enableAllTracking()->setAccount('client_uuid')->to('hello@example.com')->send(new Mailable);
```
To send by batch with all tracking enable, just add **setBatch()** where the value is __group_slug__, dont remove the **setAccout()**, since it is under the account
```php
SesMail::enableAllTracking()->setAccount('client_uuid')->setBatch('group_slug')->to('hello@example.com')->send(new Mailable);
```

To test, you can send email through this url
```
/send-sample-mail/{type}/{account}/{account_id}/{group?}
```
where 
### 
**type** = __'account'__ or __'group'__ ,
### 
**account** = __client_slug__ ,
### 
**account_id** = __client_uuid__ and
### 
**group** is optional, if you want to send it by batch you can set it to __group_slug__


### 
```
GET /{account}/{group}/api/stats/email/{email}
```
### Response
```json
{
  "success": true,
  "data": {
    "account": {
      "id": 1,
      "email": "support@account.com",
      "name": "Account",
      "slug": "account",
      "client_uuid": "30c9a55c-f6d6-11e9-b8cb-f894c26e1fcc",
      "created_at": "2019-10-25 03:19:01",
      "updated_at": "2019-10-25 03:19:37"
    },
    "group": [
      {
        "id": 1,
        "name": "Group",
        "slug": "group",
        "client_id": "30c9a55c-f6d6-11e9-b8cb-f894c26e1fcc",
        "created_at": "2019-10-25 03:23:02",
        "updated_at": "2019-10-25 03:23:02"
      }
    ],
    "counts": {
      "sent_emails": 1,
      "deliveries": 1,
      "opens": 1,
      "bounces": 0,
      "complaints": 0,
      "click_throughs": 0
    },
    "data": {
      "sent_emails": [
        {
          "id": 1,
          "message_id": "761ff93f2f32f554072d01bda1a06d04@192.168.0.73",
          "email": "email@sample.com",
          "client_id": "30c9a55c-f6d6-11e9-b8cb-f894c26e1fcc",
          "batch": "group",
          "sent_at": "2019-10-25 03:29:34",
          "delivered_at": null,
          "complaint_tracking": 1,
          "delivery_tracking": 1,
          "bounce_tracking": 1,
          "created_at": "2019-10-25 03:29:34",
          "updated_at": "2019-10-25 03:29:34"
        },
        {
          "id": 2,
          "message_id": "b30713638ea10ab8437b28b98c342f33@192.168.0.73",
          "email": "email@sample.com",
          "client_id": "30c9a55c-f6d6-11e9-b8cb-f894c26e1fcc",
          "batch": "group",
          "sent_at": "2019-10-25 03:33:21",
          "delivered_at": "2019-10-25 03:33:24",
          "complaint_tracking": 1,
          "delivery_tracking": 1,
          "bounce_tracking": 1,
          "created_at": "2019-10-25 03:33:21",
          "updated_at": "2019-10-25 03:33:26"
        }
      ],
      "deliveries": [
        {
          "id": 2,
          "message_id": "b30713638ea10ab8437b28b98c342f33@192.168.0.73",
          "email": "email@sample.com",
          "client_id": "30c9a55c-f6d6-11e9-b8cb-f894c26e1fcc",
          "batch": "group",
          "sent_at": "2019-10-25 03:33:21",
          "delivered_at": "2019-10-25 03:33:24",
          "complaint_tracking": 1,
          "delivery_tracking": 1,
          "bounce_tracking": 1,
          "created_at": "2019-10-25 03:33:21",
          "updated_at": "2019-10-25 03:33:26"
        }
      ],
      "opens": [
        {
          "id": 2,
          "sent_email_id": 2,
          "email": "email@sample.com",
          "batch": "group",
          "beacon_identifier": "37d85226-8253-47a4-bfe9-d2cf5050b824",
          "url": "http://93722b3e.ngrok.io/sr/beacon/37d85226-8253-47a4-bfe9-d2cf5050b824",
          "client_id": "30c9a55c-f6d6-11e9-b8cb-f894c26e1fcc",
          "opened_at": "2019-10-25 03:39:03",
          "created_at": "2019-10-25 03:33:21",
          "updated_at": "2019-10-25 03:39:03"
        }
      ],
      "bounces": [
        
      ],
      "complaints": [
        
      ],
      "click_throughs": [
        
      ]
    }
  }
}
```
### 
```
GET /{account}/{group}/api/stats/batch/{name}
```
### Response
```json
{
  "success": true,
  "data": {
    "client": [
      {
        "id": 1,
        "email": "account@sample.com",
        "name": "Account",
        "slug": "account",
        "client_uuid": "30c9a55c-f6d6-11e9-b8cb-f894c26e1fcc",
        "created_at": "2019-10-25 03:19:01",
        "updated_at": "2019-10-25 03:19:37"
      }
    ],
    "group": [
      {
        "id": 1,
        "name": "Group",
        "slug": "group",
        "client_id": "30c9a55c-f6d6-11e9-b8cb-f894c26e1fcc",
        "created_at": "2019-10-25 03:23:02",
        "updated_at": "2019-10-25 03:23:02"
      }
    ],
    "send_count": 3,
    "deliveries": 2,
    "opens": 1,
    "bounces": 0,
    "complaints": 0,
    "click_throughs": 0,
    "link_popularity": [
      
    ]
  }
}
```

### 
```
GET /{account}/{group}/api/has/complained/{email}
```
### Response
```json
{

}
```
### 
```
GET /{account}/{group}/api/has/bounced/{email}
```
### Response
```json
{

}
```