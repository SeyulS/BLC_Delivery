#!/bin/bash
php artisan migrate:fresh --seed
php artisan serve --host=0.0.0.0 &
npm run dev -- --host &
php artisan reverb:start
