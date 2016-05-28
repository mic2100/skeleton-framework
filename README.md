Converse
========

Setup
-----

Composer
--------
Install composer on your machine : https://getcomposer.org/download/

Run `$ composer install` from the command line to install all the required packages

Vagrant
-------
Install vagrant on your machine : http://www.vagrantup.com/downloads

Run `$ vagrant up` from the command line to setup the vagrant machine and setup all the required packages

Hosts
-----
Add the following to your hosts file `/etc/hosts` (for linux and Mac OSX)

`192.168.56.101  converse.local`

You will now be able to view the site in your browser by going to `https://converse.local`

Eloquent
--------
Laravel's eloquent package is used as a stand alone component. More information about this can be found here : http://laravel.com/docs/5.1/eloquent

Headers Required
----------------

Some headers will be needed so the user/device can be authenticated.

| Key | Description |
| --- | --- |
| api-key | This will need to be sent in every request including the signin |
| jwt | This will be the token that is returned when the user logs in |
| device-id | Unique ID for the device |
| make | make of the device sending the request |
| model | model of the device sending the request |
| screen-height | screen height of the device sending the request |
| screen-width | screen width of the device sending the request |

Paths
-----

| Path | Type | Fields/Data Types | Expected Repsonses |
| --- | --- | --- | --- |
| /api/v1/user/login | POST | --- | --- |
| /api/v1/user/sign-up | POST | --- | --- |
| /api/v1/user/profile | GET | --- | --- |
| /api/v1/user/profile | POST | --- | --- |
| /api/v1/user/profile | PUT | --- | --- |