### TODO APP

#### Features
---

- A user can sign up and sign in to the platform.
- The signed in user can be able to create, edit, view, complete and
delete the todo list.
- The app should be integrated to a another app and make sure the app
will provide some API  Auth and API functionality.

###### Used following in build features of framework.

- Migrations (created appropriate migrations)
- Events (an event class should be created and the event should be
logged, when ever CRUD happens)
- Notification (daily users should be notified renaming tasks)
- API Protection using a middleware ( passport )

##### Commands used
---
```
composer create-project --prefer-dist laravel/laravel todolist "v5.5.0"
cd todolist
php artisan migrate
php artisan make:auth
php artisan make:migration create_tasks_table --create=tasks
php artisan migrate
php artisan make:model Task
php artisan make:notification RenamingTasks
php artisan notifications:table
php artisan migrate
php artisan event:generate
php artisan make:resource Task
composer require laravel/passport
php artisan migrate
php artisan passport:install
php artisan passport:keys
php artisan route:list
php artisan db:seed
```
##### Run the app
---
```
php artisan serve
```
##### used following command to access crontab
---
```
crontab -e
```

##### Add following to crontab -e
---
```
* * * * * /usr/local/php5/bin/php /Users/samithahewawasam/Documents/todolist/artisan schedule:run > /Users/samithahewawasam/Documents/todolist/stdout.log 2>/Users/samithahewawasam/Documents/todolist/stderr.log
```


#### Client Details
- Client ID: 1
- Client Secret: Iu8sn6GOupdXhIaWGj0FrTo8qNjouEDTfIqAUjdW
- Client ID: 2
- Client Secret: QPRQcSNbeBUPFm890RoXjOS1gVCLaCl7MZj7IaHN


#### Postman

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/2bf8e2f65290572996d8)
