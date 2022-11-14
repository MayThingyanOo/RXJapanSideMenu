<?php

use App\Models\MailForm;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

if (!function_exists('format_datetime')) {

    function format_datetime($date_time, $type = 1)
    {
        if (empty($date_time)) {
            return "";
        }

        $dt = \Carbon\Carbon::parse($date_time);
        if ($type == 1) {
            return $dt->format("Y/m/d H:i");
        } else if ($type == 2) {
            return $dt->format("Y年m月d日 H:i");
        }
    }
}

if (!function_exists('format_mmdd')) {

    function format_mmdd($date_time, $type = 1)
    {
        $dt = \Carbon\Carbon::parse($date_time);
        if ($type == 1) {
            return $dt->format("m/d");
        } else if ($type == 2) {
            return $dt->format("m月d日");
        } else if ($type == 3) {
            return $dt->format("m_d");
        }
    }
}

if (!function_exists('format_date')) {

    function format_date($date_time, $type = 1)
    {
        if (empty($date_time)) {
            return "";
        }

        $dt = \Carbon\Carbon::parse($date_time);
        if ($type == 1) {
            return $dt->format("Y/m/d");
        } else if ($type == 2) {
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
        } else if ($type == 2) {
            return $dt->format("H:i:s");
        }
    }
}

if (!function_exists('carbon')) {

    function carbon($datetime = 'now')
    {
        return \Carbon\Carbon::parse($datetime);
    }
}

if (!function_exists('is_active_route')) {

    function is_active_route($path)
    {
        if (!is_array($path)) {
            return Str::is($path . "*", request()->url());
        }

        foreach ($path as $value) {
            if (is_array($value) && !array_key_exists(0, $value)) {
                // for 2nd level menu
                foreach ($value as $val) {
                    if (Str::is($val[0] . "*", request()->url())) {
                        return true;
                    }
                }
            } else {
                if (Str::is($value[0] . "*", request()->url())) {
                    return true;
                }
            }
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

if (!function_exists('cps_view_exist')) {

    function cps_view_exist($view, $exhibition_id = null)
    {
        View::addLocation(\Config::get("custom_resources.path"));

        if (empty($exhibition_id)) {
            $exhibition_id = \Route::input('exhibition_id');
        }

        return View::exists($exhibition_id . ".views." . $view);
    }
}

if (!function_exists('cps_view')) {

    function cps_view($view, $data = [], $mergeData = [])
    {
        $exhibition_id = \Route::input('exhibition_id');

        return cps_view_with_id($view, $exhibition_id, $data, $mergeData);
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

if (!function_exists('auto_linker')) {

    function auto_linker($str)
    {
        if (strpos($str, '</a>') === false) {
            $pat_sub = preg_quote('-._~%:/?#[]@!$&\'()*+,;=', '/'); // 正規表現向けのエスケープ処理
            $pat = '/((http|https):\/\/[0-9a-z' . $pat_sub . ']+)/i'; // 正規表現パターン
            $rep = '<a href="\\1" target=\"_BLANK\">\\1</a>'; // \\1が正規表現にマッチした文字列に置き換わります

            $str = preg_replace($pat, $rep, $str); // 実処理
        }
        return $str;
    }
}

if (!function_exists('custom_resource_path')) {

    function custom_resource_path($exhibition_id, $path = null)
    {
        return Config::get("custom_resources.path") . "/" . $exhibition_id . "/" . $path;
    }
}

if (!function_exists('breadcrumbs')) {

    function breadcrumbs()
    {
        $breadcrumbs = [];
        $first = \Route::current();
        $currentRoute = $first;
        $currentParam = $currentRoute->parameters();

        while (true) {
            $breadcrumbs[] = [
                'display' => $currentRoute->getDisplayName(),
                'href' => $first == $currentRoute ? null : route($currentRoute->getName(), $currentParam),
            ];
            $parent = $currentRoute->getParentName();
            if (!$parent) {
                break;
            }
            $currentRoute = \Route::getRoutes()
                ->getByName($parent);
        }

        return array_reverse($breadcrumbs);
    }
}

if (!function_exists('response_json')) {

    function response_json($data = [], array $metadata = [], $status = 200, array $headers = [])
    {
        $response = array_merge($status == 200 ? ["data" => $data] : [], $metadata);

        return \Response::json($response, $status, $headers);
    }

    function response_json_error($status, $errors = [], array $headers = [])
    {
        $errors = $errors ?: [["code" => $status, "source" => request()->path()]];

        return response_json([], ["errors" => $errors], $status, $headers);
    }
}

if (!function_exists('implode_array_object')) {

    function implode_array_object(string $glue, array $pieces, $key_or_func)
    {
        return \implode($glue, array_map(is_callable($key_or_func) ? $key_or_func : function ($obj) use ($key_or_func) {
            return $obj[$key_or_func];
        }, $pieces));
    }
}

if (!function_exists('format_visitor_item_value')) {

    function format_visitor_item_value($items)
    {
        $value = "";
        foreach ($items as $item) {
            $v = $item["value"];
            if ($item->is_other) {
                $v = empty($v) ? 'その他' : ('その他:' . $v);
            }
            $value .= ($v . ",");
        }

        return e(rtrim($value, ","));
    }
}

if (!function_exists('json_encode_with_function')) {

    function json_encode_with_function($array)
    {
        $string = json_encode($array);
        $string = str_replace('"%%', '', $string);
        $string = str_replace('%%"', '', $string);
        $string = str_replace('\r', '', $string);
        $string = str_replace('\n', '', $string);

        return $string;
    }
}

/**
 * guide url generator
 */
if (!function_exists('guide_url')) {

    /**
     * @param      $path
     * @param null $anchor ページ内リンク用のアンカー
     * @return string
     */
    function guide_url($path, $anchor = null)
    {
        $current_url = url()->current();
        $target_url = route("qb_guide_show_page") . "/" . $path;
        if ($current_url == $target_url) {
            return "#" . $anchor;
        }

        if ($anchor != null) {
            $target_url = $target_url . "?id=" . $anchor;
        }

        return $target_url;
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

if (!function_exists('ken_list')) {

    function ken_list()
    {
        return \App\Lib\CpsAddress\Ken::all();
    }
}

if (!function_exists('customize')) {

    function customize($type, $id, $default = null)
    {
        $arr = Arr::wrap(config('customize.' . $type));
        return Arr::get($arr, $id, Arr::get($arr, 'default', $default));
    }
}

if (!function_exists('valOrNull')) {

    function valOrNull($val)
    {
        return $val === '' ? null : $val;
    }
}

if (!function_exists('route_input')) {

    function route_input($key = null)
    {
        return $key ? Route::input($key) : Route::current()->parameters();
    }
}

if (!function_exists('url_input')) {

    function url_input($key = null, $default = null)
    {
        $params = array_merge(Route::current()->parameters(), request()->query());
        return $key ? Arr::get($params, $key, $default) : $params;
    }
}

if (!function_exists('parent_route')) {

    function parent_route($name = 'current')
    {
        //TODO: calculate the parent route from the given name
        $route = Route::current();
        return route($route->getParentName(), $route->parameters());
    }
}

if (!function_exists('strtoarray')) {

    function strtoarray($str, $options = [])
    {
        if (!is_string($str)) {
            return [];
        }

        $options = array_merge([
            'delimiter' => ',',
            'filter' => true,
            'unique' => true,
            'trim' => true,
        ], $options);
        $arr = explode($options['delimiter'], $str);
        $arr = $options['trim'] ? array_map('trim', $arr) : $arr;
        $arr = $options['filter'] ? array_filter($arr) : $arr;
        $arr = $options['unique'] ? array_unique($arr) : $arr;
        return $arr;
    }
}

if (!function_exists('lambda')) {

    /**
     * <b>Caution</b> this function is very dangerous because it allows execution of arbitrary PHP code.
     * Its use thus is discouraged. If you have carefully verified that there is no other option than to use this function,
     * pay special attention not to pass <b>any user provided data</b> into it without properly validating it beforehand.
     */
    function lambda($exp, string $exp1 = '')
    {
        $use = is_string($exp) ? [] : $exp;
        $exp = is_string($exp) ? $exp : $exp1;

        if ($use) {
            extract($use, EXTR_SKIP);
            $using = ' use ($' . implode(',$', array_keys($use)) . ')';
        }

        $body = explode("=>", $exp, 2)[1] ?? '';
        $body = trim($body, '{}' . " \t\n\r\0\x0B");
        if ((!str_contains($body, ';')) || (!trim(explode(";", $body)[1] ?? ''))) {
            $body = 'return ' . $body . ';';
        }

        $params = trim(explode("=>", $exp)[0], '()' . " \t\n\r\0\x0B");
        return eval('return function(' . $params . ')' . ($using ?? '') . ' { ' . $body . ' };');
    }
}

if (!function_exists('path')) {

    function path()
    {
        return implode(DIRECTORY_SEPARATOR, func_get_args());
    }
}

if (!function_exists('throw_response_if')) {

    function throw_response_if($boolean, $content)
    {
        if ($boolean) {
            throw new Illuminate\Http\Exceptions\HttpResponseException(response($content));
        }
    }
}

if (!function_exists('route_list')) {

    /**
     * for menu_bar
     * this function is used in inExhibition.blade.php
     * @param collection $h_exhibition
     * @return array $menu_bar
     */
    function route_list($h_exhibition)
    {
        $menu_bar = [];
        $before_event = [];
        $list = [];
        $during_event = [];
        $mail = [];

        $before_event = [
            '事前設定' => [route('qb_pre_setting', [$h_exhibition->id]), true],
            'セッション情報' => [route('qb_detail_ex_setting', [$h_exhibition->id]), true],
            '申込フォーム' => [route('qb_detail_ex_items', [$h_exhibition->id]), true],
            'マイページ' => [route('qb_detail_mypage', [$h_exhibition->id]), $h_exhibition->hasService('mypage')],
            '来場証' => [route('qb_attendance_card', [$h_exhibition->id]), $h_exhibition->hasService('ticket')],
            '空QRコード' => [route('qb_empty_qr', [$h_exhibition->id]), $h_exhibition->hasService('empty_qr')],
            'セミナー' => [route('qb_seminar_list', [$h_exhibition->id]), $h_exhibition->hasService('seminar')],
            'レコメンド' => [route('qb_recommendation_list', [$h_exhibition->id]), $h_exhibition->hasService('service_recommendation')],
            'アンケート' => [route('qb_survey_list', [$h_exhibition->id]), $h_exhibition->hasService('survey')],
            '支払い' => [route('qb_payment_price_list', [$h_exhibition->id]), $h_exhibition->is_payment_enabled],
            '翻訳' => [route('qb_item_translation', [$h_exhibition->id]), $h_exhibition->hasService('translation')],
            'ポイント' => [route('qb_point', [$h_exhibition->id]), $h_exhibition->hasService('point')],
            'スポット' => [route('qb_reception_place_list', [$h_exhibition->id]), true],
        ];

        $list = [
            '申込状況' => [route('qb_application_status', [$h_exhibition->id]), true],
            '申込者' => [route('qb_visitor_list', [$h_exhibition->id]), true],
            'セミナー' => [route('qb_seminar_visitor_list', [$h_exhibition->id]), $h_exhibition->hasService('seminar')],
            '支払い' => [route('qb_payment_list', [$h_exhibition->id]), $h_exhibition->is_payment_enabled],
            '承認' => [route('qb_visitor_approval', [$h_exhibition->id]), $h_exhibition->exhibition_group->isApprovalAllFunctionEnabled(), true],
            'NGリスト' => [route('qb_ng_list', [$h_exhibition->id]), $h_exhibition->exhibition_group->isApprovalNGFunctionEnabled(), true],
            'スポット' => [route('qb_rec_place_new_list', [$h_exhibition->id]), true],
        ];

        $during_event = [
            '当日状況' => [route('qb_realtime_status', [$h_exhibition->id]), true],
            '受付' => [route('qb_reception_show_list', [$h_exhibition->id]), true],
            'マイページ' => [$h_exhibition->hasService('mypage_document') ? route('qb_mypage_doc_download_log', [$h_exhibition->id]) : ($h_exhibition->hasService('mypage_url_streaming') ? route('qb_url_streaming_log', [$h_exhibition->id]) : route('qb_mypage_streaming_log', [$h_exhibition->id])), $h_exhibition->hasService('mypage_document') || $h_exhibition->hasService('mypage_streaming')],
            'アンケート集計' => [route('qb_detail_survey_list', [$h_exhibition->id]), $h_exhibition->hasService('survey')],
        ];

        //since custom mail name can be same, we have to use nested array structure.
        $mail = [
            ['メール' => [route('qb_show_mail_list', ['exhibition_id' => $h_exhibition->id]), true]],
            ['バスワード' => [route('qb_group_mail_detail', ['exhibition_id' => $h_exhibition->id, 'mail_form' => 'password_reset']), $h_exhibition->hasService('mypage')]],
            ['申込完了' => [route('qb_group_mail_detail', ['exhibition_id' => $h_exhibition->id, 'mail_form' => 'thank_you']), true]],
            ['承認/非承認' => [route('qb_group_mail_detail', ['exhibition_id' => $h_exhibition->id, 'mail_form' => 'approval']), $h_exhibition->exhibition_group->isApprovalAllFunctionEnabled()]],
            ['NGリスト' => [route('qb_group_mail_detail', ['exhibition_id' => $h_exhibition->id, 'mail_form' => 'ng_list']), $h_exhibition->exhibition_group->isApprovalNGFunctionEnabled()]],
            ['マイページ登録情報変更' => [route('qb_group_mail_detail', ['exhibition_id' => $h_exhibition->id, 'mail_form' => 'mypage_profile']), $h_exhibition->hasService('mypage')]],
            ['入金完了' => [route('qb_group_mail_detail', ['exhibition_id' => $h_exhibition->id, 'mail_form' => 'payment_complete']),
                $h_exhibition->hasService('credit') || $h_exhibition->hasService('bank'),
            ]],
            ['入金期限超過' => [route('qb_group_mail_detail', ['exhibition_id' => $h_exhibition->id, 'mail_form' => 'payment_cancel']),
                $h_exhibition->hasService('credit') || $h_exhibition->hasService('bank'),
            ]],
            ['申込変更' => [route('qb_group_mail_detail', ['exhibition_id' => $h_exhibition->id, 'mail_form' => 'seminar_change']),
                $h_exhibition->hasService('credit') || $h_exhibition->hasService('bank'),
            ]],
            ['受付通知' => [route('qb_group_mail_detail', ['exhibition_id' => $h_exhibition->id, 'mail_form' => 'attend_acknowledgement']), true]],
            ['受付時本人（入場口）' => [route('qb_group_mail_detail', ['exhibition_id' => $h_exhibition->id, 'mail_form' => 'entry_visitor']), $h_exhibition->hasService('entry_visitor')]],
            ['受付時本人（退場口）' => [route('qb_group_mail_detail', ['exhibition_id' => $h_exhibition->id, 'mail_form' => 'exit_visitor']), $h_exhibition->hasService('exit_visitor')]],
            ['セミナー受付時本人宛メール' => [route('qb_group_mail_detail', ['exhibition_id' => $h_exhibition->id, 'mail_form' => 'survey_seminar_url']), $h_exhibition->hasService('survey_seminar')]],
            ['担当スタッフ' => [route('qb_group_mail_detail', ['exhibition_id' => $h_exhibition->id, 'mail_form' => 'staff']), $h_exhibition->hasService('staff')]],
        ];

        if (is_active_route($mail[0])) {
            $custom_mail = MailForm::getByExhibitionId($h_exhibition->exhibition_id, 0)->orderBy('mail_form_id')->pluck('name', 'mail_form_id');

            foreach ($custom_mail as $key => $value) {
                $mail[] = [
                    $value => [route('qb_group_mail_detail', ['exhibition_id' => $h_exhibition->id, 'mail_form' => $key]), true],
                ];
            }
        }
        // route array init

        // push to menu array
        array_push($menu_bar, $before_event, $list, $during_event, $mail);

        return $menu_bar;
    }
}

if (!function_exists('change_csv_error_format')) {

    /**
     * for csv error body
     * this function change validation error format to csv line type
     * @param array $validator->errors()
     * @return string formatted csv body data in (line number, field, message) type
     */
    function change_csv_error_format($errors)
    {
        $csv_body = '';
        $errors_message = [];
        foreach ($errors->toArray() as $key => $message) {
            if (preg_match('/^csv_file_data\.[0-9]/', $key)) {
                preg_match_all('!\d+!', $key, $matches);
                $errors_message[] = [
                    0 => ((int) $matches[0][0] + 2) . ' 行目',
                    1 => substr($message[0], 0, strpos($message[0], '：')),
                    2 => substr($message[0], strpos($message[0], '：') + 3),
                ];
            } else if (preg_match('/^booths\.[0-9]/', $key)) {
                preg_match_all('!\d+!', $key, $matches);
                if (array_key_exists(2, $matches[0])) {
                    $matches = $matches[0][2];
                } else {
                    if (array_key_exists(1, $matches[0])) {
                        $matches = $matches[0][1];
                    } else {
                        $matches = $matches[0][0];
                    }
                }
                $errors_message[] = [
                    0 => ((int) $matches + 2) . ' 行目',
                    1 => substr($message[0], 0, strpos($message[0], '：')),
                    2 => substr($message[0], strpos($message[0], '：') + 3),
                ];
            } else if (preg_match('/^promotions\.[0-9]/', $key)) {
                preg_match_all('!\d+!', $key, $matches);

                if (array_key_exists(1, $matches[0])) {
                    $matches = $matches[0][1];
                } else {
                    $matches = $matches[0][0];
                }

                $errors_message[] = [
                    0 => ((int) $matches + 2) . ' 行目',
                    1 => substr($message[0], 0, strpos($message[0], '：')),
                    2 => substr($message[0], strpos($message[0], '：') + 3),
                ];
            } else {
                $errors_message[] = [
                    0 => '',
                    1 => '',
                    2 => $message[0],
                ];
            }
        }
        foreach ($errors_message as $key => $message) {
            $csv_body .= CpsCSV::toLineFromArray($message);
        }
        return $csv_body;
    }
}

if (!function_exists('bytesToSize')) {

    function bytesToSize($bytes = 0)
    {
        if ($bytes == 0) {
            return '0 Byte';
        }

        $sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $sizes[$i];
    }
}

if (!function_exists('checkCsvDateFormat')) {
    function checkCsvDateFormat($date = null)
    {
        if ($date == null) return false;
        $rule = preg_match("/^([1-9][0-9][0-9][0-9])[\/]([1-9]|0[1-9]|[1][0-2])[\/]([1-9]|0[1-9]|[1-2][0-9]|3[0-1])$/", $date);
        if ($rule) {
            $format_1 = 'Y/n/j';
            $format_2 = 'Y/m/d';
            $format_3 = 'Y/n/d';
            $format_4 = 'Y/m/j';
            $d_1 = DateTime::createFromFormat($format_1, $date);
            $data_1 = $d_1 && $d_1->format($format_1) == $date;
            $d_2 = DateTime::createFromFormat($format_2, $date);
            $data_2 = $d_2 && $d_2->format($format_2) == $date;
            $d_3 = DateTime::createFromFormat($format_3, $date);
            $data_3 = $d_3 && $d_3->format($format_3) == $date;
            $d_4 = DateTime::createFromFormat($format_4, $date);
            $data_4 = $d_4 && $d_4->format($format_4) == $date;
            if ((bool) $data_1 || (bool) $data_2 || (bool) $data_3 || (bool) $data_4) {
                return true;
            }
            return false;
        }
        return false;
    }
}

// base_convert on large integers
if (!function_exists('big_base_convert')) {
    function big_base_convert($numstring, $frombase, $tobase)
    {
        $chars = "0123456789abcdefghijklmnopqrstuvwxyz";
        $tostring = substr($chars, 0, $tobase);

        $length = strlen($numstring);
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $number[$i] = strpos($chars, $numstring[$i]);
        }
        do {
            $divide = 0;
            $newlen = 0;
            for ($i = 0; $i < $length; $i++) {
                $divide = $divide * $frombase + $number[$i];
                if ($divide >= $tobase) {
                    $number[$newlen++] = (int) ($divide / $tobase);
                    $divide = $divide % $tobase;
                } elseif ($newlen > 0) {
                    $number[$newlen++] = 0;
                }
            }
            $length = $newlen;
            $result = $tostring[$divide] . $result;
        } while ($newlen != 0);
        return $result;
    }
}

if (!function_exists('validation_str_replace')) {
    /**
     * replace keywords in validation message.
     *
     * @param array $replace|$message
     * @return string $message
     */
    function validation_str_replace($replace, $message)
    {
        foreach ($replace as $search => $keyword) {
            $message = str_replace($search, $keyword, $message);
        }

        return $message;
    }
}

if (!function_exists('isEmptyCsvLine')) {
    /**
     * check whether csv line is empty
     *
     * @param array $line
     * @return bool
     */
    function isEmptyCsvLine($line)
    {
        foreach ($line as $key => $value) {
            if ($value) {
                return false;
            }
        }
        return true;
    }
}

if (!function_exists('getExhibitionItemLimit')) {
    function getExhibitionItemLimit($exhibition_id)
    {
        $customize_ex_ids = strtoarray(customize('additional_items_limit', 'default') ?: '');
        $exhibition_items_limit = 30;
        foreach ($customize_ex_ids as $key => $value) {
            $customize_ex_id_with_limit = explode(':', $value);
            if ($customize_ex_id_with_limit[0] == $exhibition_id) {
                $exhibition_items_limit = $customize_ex_id_with_limit[1];
            }
        }
        return $exhibition_items_limit;
    }
}
