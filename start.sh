#!/bin/bash
php artisan serve --host=0.0.0.0 &
npm run dev -- --host &
php artisan reverb:start
