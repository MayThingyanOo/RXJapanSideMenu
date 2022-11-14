<?php
/**
 * Created by PhpStorm.
 * User: yujiro.takezawa
 * Date: 2016/01/20
 * Time: 19:37
 */

namespace App\Lib\CpsFile;

use App\Lib\CpsPDF\CpsPDF;
use App\Mail\ExceptionNotify;
use App\Models\BatchEntryTicketHistory;
use App\Models\Exhibition;
use App\Models\FunctionLock;
use App\Models\UploadFile;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Str;
use Knp\Snappy\Pdf;
use Mail;
use RuntimeException;
use SimpleXMLElement;
use Storage;

class CpsFile
{

    /**
     * @param $file
     * @param $exhibition_id
     * @return string
     */
    public function saveTemporaryDocumentFile($file, $exhibition_id)
    {
        $server_file_name = Str::random() . "." . $file->getClientOriginalExtension();
        $file->move($this->getTemporaryDocumentDirPath($exhibition_id), $server_file_name);

        // 権限
        chmod($this->getTemporaryDocumentDirPath($exhibition_id), 0777);
        chmod($this->getTemporaryDocumentDirPath($exhibition_id) . "/" . $server_file_name, 0777);

        return $server_file_name;
    }

    /**
     * @param null $exhibition_id
     * @return mixed
     */
    public function getTemporaryDocumentDirPath($exhibition_id = null)
    {
        // @todo from config
        $path = storage_path("app/document_temp");
        if (!empty($exhibition_id)) {
            $path .= "/" . $exhibition_id;
        }

        return $path;
    }

    public function getDocumentDirPath($exhibition_id = null)
    {
        // @todo from config
        $path = storage_path("app/document");
        if (!empty($exhibition_id)) {
            $path .= "/" . $exhibition_id;
        }

        return $path;
    }

    public function removeTempDir($exhibition_id)
    {
        \File::deleteDirectory($this->getTemporaryDocumentDirPath() . "/" . $exhibition_id, true);
    }

    public function moveDocumentFileFromTemp($exhibition_id, $file_path)
    {
        $doc_dir_path = $this->getDocumentDirPath($exhibition_id);
        if (!\File::exists($doc_dir_path)) {
            \File::makeDirectory($doc_dir_path, 0777, true);
        }
        $new_file_path = $doc_dir_path . "/" . $file_path;

        \File::copy($this->getTemporaryDocumentDirPath($exhibition_id) . "/" . $file_path, $new_file_path);
        chmod($this->getDocumentDirPath($exhibition_id), 0777);
        chmod($this->getDocumentDirPath($exhibition_id) . "/" . $file_path, 0777);

        return new \SplFileInfo($new_file_path);
    }

    /**
     * @param $exhibition_id
     * @param $file_name
     * @return bool
     */
    public function removeDocumentFile($exhibition_id, $file_name)
    {
        return \File::delete($this->getDocumentDirPath($exhibition_id) . "/" . $file_name);
    }

    /**
     * @param $byte
     * @return int
     */
    public function byteToMByte($byte)
    {
        $mbyte = round($byte / 1024 / 1024);

        if ($mbyte < 1) {
            $mbyte = 1;
        }

        return $mbyte;
    }

    public function getDocumentFile($exhibition_id, $file_name)
    {
        return \File::get($this->getDocumentDirPath($exhibition_id) . "/" . $file_name);
    }

    /**
     * @param       $entry_ticket_id
     * @param       $exhibition
     * @param       $visitors
     * @param array $with
     * @return
     */
    public function renderEntryTicket($exhibition, $visitors, $options = [])
    {
        $mode = $options['mode'] ?? 'pc';
        $view = ['mobile' => 'mobile.default', 'lp' => 'lp.default'][$mode] ?? '1';

        if (empty($exhibition)) {
            abort(404);
        }

        $html = cps_view_with_id("entry_ticket." . $view, $exhibition->id,
            compact('exhibition', 'visitors', 'options')
        );

        if (preg_match("/<eticket (.*) \/>/siU", $html, $matches)) {
            $xml = (array) new SimpleXMLElement($matches[0]);
            $options = array_merge($options, $xml['@attributes']);
        }

        return ($mode == 'mobile' || ($options['output'] ?? '') == 'html') ? $html : $this->responsePdfFromHtml($html);
    }

    /**
     * @param       $entry_ticket_id
     * @param       $exhibition
     * @param       $visitors
     * @param array $with
     * @return
     */
    public function renderEntryTicketPdfMail($exhibition, $original_visitors, $mail_text)
    {
        if (empty($exhibition)) {
            abort(404);
        }

        $file_path = 'documents/tmp/' . $exhibition->id;
        if (\File::exists(base_path('htdocs/fs/documents/tmp/' . $exhibition->id))) {
            $file_clean = Storage::disk('public')->deleteDirectory($file_path);
        }

        $chunk_visitor = is_array($original_visitors) ? array_chunk($original_visitors, 1000, true) : $original_visitors->chunk(1000, true);
        $url = route('qb_visitor_entry_ticket_download_list', [$exhibition->id]);
        // $mail_text = is_array($original_visitors) ? '空QR' : '来場証';

        /**
         * lock entry ticket download button
         * (exhibition_id + function_name) have to unqiue
         */
        $function_lock = FunctionLock::where('exhibition_id', $exhibition->id)
            ->where('function_name', 'entry_ticket_download')
            ->first();

        // lock entry ticket download button
        if (empty($function_lock)) {
            $data = [
                'exhibition_id' => $exhibition->id,
                'function_name' => 'entry_ticket_download',
                'expired_date' => Carbon::now()->addMinutes(60),
            ];
            $function_lock = FunctionLock::create($data);
        } else {
            $function_lock->update(['expired_date' => Carbon::now()->addMinutes(60)]);
        }

        try {
            DB::transaction(function () use ($chunk_visitor, $exhibition, $function_lock) {
                foreach ($chunk_visitor as $visitors) {
                    $temp_visitors = $visitors;

                    // get file display name
                    if (count($visitors) == 1) {
                        $display_name = is_array($visitors) ? array_shift($temp_visitors)->visitor_code : $visitors[$visitors->keys()->first()]->visitor_code;
                    } else {
                        $display_name = is_array($visitors) ? array_shift($temp_visitors)->visitor_code . '~' . end($visitors)->visitor_code :
                        $visitors[$visitors->keys()->first()]->visitor_code . '~' . $visitors[$visitors->reverse()->keys()->first()]->visitor_code;
                    }

                    $html = cps_view_with_id("entry_ticket.1", $exhibition->id, compact('exhibition', 'visitors'));
                    // $html = cps_view_with_id("entry_ticket.1" , $exhibition->id, compact('exhibition', 'visitors', 'options'));
                    // if (preg_match("/<eticket (.*) \/>/siU", $html, $matches)) {
                    //     $xml = (array) new SimpleXMLElement($matches[0]);
                    //     $options = array_merge($options, $xml['@attributes']);
                    // }

                    // generate pdf file and save.
                    $file_name = $this->responseSeparatePdfFromHtml($display_name, $exhibition, $html);

                    // get file size of saved pdf.
                    $file_size = $this->storage('', 'document')->size($exhibition->id . '/entry_tickets/' . $file_name);

                    // save record
                    $record = UploadFile::create([
                        'file_name' => $file_name,
                        'size' => $file_size,
                    ]);

                    // save history
                    $history = BatchEntryTicketHistory::create([
                        'upload_file_id' => $record->id,
                        'exhibition_id' => $exhibition->id,
                        'name' => $display_name,
                        'downloaded_date' => date('Y-m-d H:i:s'),
                        'expired_date' => date('Y-m-d H:i:s', strtotime('+3 day')),
                        'number_of_ticket' => count($visitors),
                    ]);

                    // open entry ticked download button lock
                    $function_lock->update([
                        'expired_date' => Carbon::now(),
                    ]);
                }
            });

            $mail_options = [
                'mail_text' => $mail_text,
                'url' => $url,
                'date' => date('Y年m月d日 H:i'),
                'ex_date' => date('Y年m月d日 H:i', strtotime('+3 day')),
            ];
            Mail::send('email.qbusiness.send_visitors_pdf_info', $mail_options, function ($message) {
                $message->from('no-reply@q-pass.jp', 'Q-Business');
                $message->to(\CpsAuth::user()->email)->subject('【Q-BUSINESS】来場証の一括出力が完了しました');
            });

        } catch (\Exception $e) {
            ini_set('max_execution_time', \Config::get('ini_values.default.max_execution_time'));
            ini_set('memory_limit', \Config::get('ini_values.default.memory_limit'));
            // send all exceptions to error_mail.
            Mail::to(config("error_mail.to_mail_address"))->queue(new ExceptionNotify($e));
            // open entry ticked download button lock
            $function_lock->update([
                'expired_date' => Carbon::now(),
            ]);
            // delete the folder if exists.
            if (\File::exists(base_path('htdocs/fs/documents/tmp/' . $exhibition->id))) {
                $file_clean = Storage::disk('public')->deleteDirectory($file_path);
            }
        }
    }

    public function renderInvoice($exhibition, $bill)
    {
        $html = cps_view_with_id("invoice.default", $exhibition->id, ['exhibition' => $exhibition, 'bill' => $bill, 'visitor' => $bill->visitor]);
        return $this->responsePdfFromHtml($html);
    }

    public function renderReceipt($exhibition, $bill)
    {
        $html = cps_view_with_id("receipt.default", $exhibition->id, ['exhibition' => $exhibition, 'bill' => $bill, 'visitor' => $bill->visitor]);
        return $this->responsePdfFromHtml($html);
    }

    protected function extractTitleFromHtml($html)
    {
        $res = preg_match("/<title>(.*)<\/title>/siU", $html, $title_matches);
        if (!$res) {
            $title = "entry_ticket";
        } else {
            $title = preg_replace('/\s+/', ' ', $title_matches[1]);
            $title = trim($title);
        }
        return $title;
    }

    protected function extractOptions($tag, $html)
    {
        if (preg_match("/<" . $tag . " (.*) \/>/siU", $html, $matches)) {
            $xml = (array) new SimpleXMLElement($matches[0]);
            return $xml['@attributes'];
        }
        return [];
    }

    /**
     * @param $html
     * @return \Illuminate\Http\Response
     */
    public function responsePdfFromHtml($html, $options = [])
    {
        $options = array_merge($options, $this->extractOptions('pdf', $html));

        //TODO: move to CpsPDF as wraper class for creaing pdf from html
        $snappy = new Pdf(base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64'));
        $snappy->setOption('encoding', 'utf-8');
        $snappy->setOption('margin-top', 0);
        $snappy->setOption('margin-right', 0);
        $snappy->setOption('margin-bottom', 0);
        $snappy->setOption('margin-left', 0);
        foreach ($this->extractOptions('wkhtmltopdf', $html) as $key => $value) {
            $snappy->setOption($key, $value);
        }

        $respone = $snappy->getOutputFromHtml($html);
        if (isset($options['sign'])) {
            $respone = (new CpsPDF())->sign($respone, $options);
        }

        $filename = $options['filename'] ?? ($this->extractTitleFromHtml($html) . '.pdf');
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename,
        ];
        return response($respone, 200, $headers);
    }

    /**
     * @param       $display_name
     * @param       $exhibition
     * @param       $html
     * @param array $with
     * @return \Illuminate\Http\Response
     */
    public function responseSeparatePdfFromHtml($display_name, $exhibition, $html, $options = [])
    {
        $options = array_merge($options, $this->extractOptions('pdf', $html));

        $snappy = new Pdf(base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64'));
        $snappy->setOption('encoding', 'utf-8');
        $snappy->setOption('margin-top', 0);
        $snappy->setOption('margin-right', 0);
        $snappy->setOption('margin-bottom', 0);
        $snappy->setOption('margin-left', 0);

        foreach ($this->extractOptions('wkhtmltopdf', $html) as $key => $value) {
            $snappy->setOption($key, $value);
        }

        $file_name = ('entry_tickets_' . $display_name . '_' . date('Ymdhis') . '.pdf');
        $file_path = base_path('htdocs/fs/documents/tmp/' . $exhibition->id);
        $file = $file_path . DIRECTORY_SEPARATOR . $file_name;
        $snappy->generateFromHtml($html, $file, array(), false);

        $storage = $this->storage('', 'document');
        $tmp_path = path('documents/tmp/' . $exhibition->id, $file_name);
        $target_path = path($exhibition->id . '/entry_tickets', $file_name);
        //$storage->copy($tmp_path, $target_path);
        //$storage->delete($tmp_path);
        $storage->put($target_path, Storage::disk('public')->get($tmp_path));
        $file_clean = Storage::disk('public')->deleteDirectory('documents/tmp/' . $exhibition->id);
        return $file_name;
    }

    /**
     *
     * @param type $name
     * @param type $subDir
     * @param string storage
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public function storage($subDir = '', $name = 'form_image', $storage = '')
    {
        $disk = config('filesystems.disks.' . (!empty($storage) ? $storage : env('FS_DISK', 'public')));
        $driverMethod = 'create' . ucfirst($disk['driver']) . 'Driver';

        $config = config('filesystems.business.' . $name);
        if (!$config) {
            throw new RuntimeException('Unknown storage: ' . $name);
        }
        $root = ($disk['root'] ?? '') . DIRECTORY_SEPARATOR . $config['root'] . DIRECTORY_SEPARATOR . $subDir;
        $url = Str::replaceFirst(public_path(), '', $root);

        return Storage::{$driverMethod}(array_merge($disk, $config, ['root' => $root, 'url' => url($url)]));
    }

    /**
     *
     * @param type $storage
     * @param type $path string|array
     * @param type $expired int|string|datetime
     */
    public function tmpUrl($path, $expired, $storage = 'form_image', $name = '')
    {
        $s = $this->storage('', $storage);
        if (is_array($path)) {
            $path = implode(DIRECTORY_SEPARATOR, $path);
        }
        if (is_int($expired)) {
            $expired = carbon()->addSecond($expired);
        } else if (is_string($expired)) {
            $expired = carbon($expired);
        }
        try {
            $options = empty($name) ? [] : [
                'ResponseContentType' => 'text/csv',
                'ResponseContentDisposition' => 'attachment; filename=' . urlencode($name),
            ];
            return $s->temporaryUrl($path, $expired, $options);
        } catch (RuntimeException $ex) {
            return $s->url($path);
        }
    }

    /**
     * return the original url of the an image.
     * 
     * This function is only for backup disk and need
     * FS_BACKUP (local => backup, server => s3_backup) env variable.
     * 
     * @param string $path
     * @param string $subDir = rxjapan
     * 
     * @return string url of an image.
     */
    public function url($path)
    {
        $storage = env('FS_BACKUP', '');
        $s = $this->storage('', 'backup', $storage);
        if (is_array($path)) {
            $path = implode(DIRECTORY_SEPARATOR, $path);
        }
        return $s->url($path);
    }

}
