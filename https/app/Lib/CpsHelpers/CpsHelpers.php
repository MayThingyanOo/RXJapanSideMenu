<?php

if (!function_exists('format_date')) {

    function format_date($date_time, $type = 1)
    {
        if (empty($date_time)) {
            return "";
        }

        $dt = \Carbon\Carbon::parse($date_time);
        if ($type == 1) {
            return $dt->format("Y/m/d");
        } elseif ($type == 2) {
            return $dt->format("Y年m月d日");
        }
    }
}

if (!function_exists('format_time')) {

    function format_time($date_time, $type = 1)
    {
        $dt = \Carbon\Carbon::parse($date_time);
        if ($type == 1) {
            return $dt->format("H:i");
        } elseif ($type == 2) {
            return $dt->format("H:i:s");
        }
    }
}

if (!function_exists('cps_trans')) {

    function cps_trans($id = null, $param_exhibition_id = null)
    {
        if (is_null($id)) {
            return app('translator');
        }

        $exhibition_id = $param_exhibition_id ?: \Route::input('exhibition_id');

        if (empty($exhibition_id)) {
            return trans($id);
        }

        $namespace = $exhibition_id;
        $custom_path = Config::get("custom_resources.path") . "/" . $exhibition_id . "/lang";
        Lang::addNamespace($namespace, $custom_path);

        $newId = $namespace . '::' . $id;
        $translatedValue = app('translator')->get($newId);
        // If the translater can't locate the key ($newId), it will return the same value as the given key ($newId)
        if ($translatedValue == $newId) {
            $translatedValue = app('translator')->get($id);
        }

        return $translatedValue;
    }
}

if (!function_exists('cps_view_with_id')) {
    function cps_view_with_id($view, $exhibition_id, $data = [], $mergeData = [])
    {
        View::addLocation(\Config::get("custom_resources.path"));
        /**
         * config/view.phpに書いてあるstorage/app/custom_resourcesの
         * 下にカスタムテンプレートがあるか判断して、ある場合はそちらを表示する
         * 展示会ごとにカスタムテンプレート
         */
        if (!empty($exhibition_id) && cps_view_exist($view, $exhibition_id)) {
            $dir_path = substr($view, 0, strrpos($view, "."));
            $dir_path = str_replace(".", "/", $dir_path);
            $mergeData["view_folder_path"] = custom_resource_view_path($exhibition_id, $dir_path);

            return View::make($exhibition_id . ".views." . $view, $data, $mergeData);
        }

        return View::make($view, $data, $mergeData);
    }
}

if (!function_exists('custom_resource_view_path')) {
    function custom_resource_view_path($exhibition_id, $dir_path = "")
    {
        return custom_resource_path($exhibition_id) . "views/" . $dir_path;
    }
}

if (!function_exists('custom_resource_path')) {

    function custom_resource_path($exhibition_id, $path = null)
    {
        return Config::get("custom_resources.path") . "/" . $exhibition_id . "/" . $path;
    }
}

if (!function_exists('auto_version')) {

    function auto_version($file)
    {
        if (strpos($file, '/') !== 0 || !file_exists($_SERVER['DOCUMENT_ROOT'] . $file)) {
            return $file;
        }

        $mtime = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);
        return $file . "?fv=" . $mtime;
    }
}

if (!function_exists('csp_script_if')) {

    function csp_script_if($script)
    {
        if (strpos($script, '/') !== 0) {
            $script = '/scripts/' . str_replace('.', '/', $script) . '.js';
        }
        if (\file_exists($_SERVER['DOCUMENT_ROOT'] . $script)) {
            return csp_script($script);
        }
    }
}

if (!function_exists('csp_script')) {

    function csp_script($script)
    {
        if (strpos($script, '/') !== 0) {
            $script = '/scripts/' . str_replace('.', '/', $script) . '.js';
        }
        return (config('app.debug') ? PHP_EOL : '') . '<script src="' . auto_version($script) . '" ></script>';
    }
}

if (!function_exists('csp_scope')) {

    function csp_scope($vars, $scope = 'vars')
    {
        return '<script class="_scope hidden" hidden data-scope="' . $scope . '" type="text/json" >'
        . json_encode($vars, JSON_HEX_TAG | (config('app.debug') ? JSON_PRETTY_PRINT : 0))
        . '</script>'
        . csp_script('/js/scope.js');
    }
}

if (!function_exists('printErrorMessage')) {
    function printErrorMessage($msg, $class = "")
    {
        $tag = "<div class=\"qb-error-box {$class} \">{$msg}</div>";

        return $tag;
    }
}
