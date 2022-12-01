<?php

namespace App\Providers;

use App\Models\StaffPasswordReminder;
use Illuminate\Support\ServiceProvider;
use Validator;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('is_used', function ($attribute, $value, $parameters, $validator) {

            $hash = $value;

            $staff_password_reminder = StaffPasswordReminder::where('hash', $hash)
                ->first();

            if (empty($staff_password_reminder)) {
                return false;
            }

            $is_used = $staff_password_reminder->is_used;

            if ($is_used == true) {
                return false;
            }

            return true;
        });

        Validator::extend('is_within_ten_min', function ($attribute, $value, $parameters, $validator) {

            $hash = $value;

            $staff_password_reminder = StaffPasswordReminder::where('hash', $hash)
                ->first();

            if (empty($staff_password_reminder)) {
                return false;
            }

            $created_at = $staff_password_reminder->created_at;
            if (time() - strtotime($created_at) > (60 * 10)) {
                return false;
            }

            return true;
        });

        Validator::extend('password_character_used', function ($attribute, $value, $parameters, $validator) {
            if (preg_match('/^[A-Za-z\d!#$%&\'()*+\-.\/:;<=>?@^_`{|}~]+$/u', $value)) {
                return true;
            }

            return false;
        });

        Validator::extend('password_rules', function ($attribute, $value, $parameters, $validator) {
            $rules = "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$!#$%&'()*+\-.\/:;<=>?@^_`{|}~])[A-Za-z\d$!#$%&'()*+\-.\/:;<=>?@^_`{|}~]+$/u";
            if (preg_match($rules, $value)) {
                return true;
            }

            return false;
        });
    }
}
