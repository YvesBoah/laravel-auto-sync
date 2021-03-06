<?php

/*
 * The MIT License
 *
 * Copyright 2017 Mohammed Zaki mohammedzaki.dev@gmail.com.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace AutoSync\Controllers;

use Illuminate\Http\Request;
use Validator;
use Input;
use File;
use AutoSync\Utils\Helpers;
use AutoSync\Jobs\ProcessSyncLogFile;
use AutoSync\Utils\Constants;

/**
 * Description of AutoSyncController
 *
 * @author Mohammed Zaki mohammedzaki.dev@gmail.com
 */
class AutoSyncController extends Controller {

    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
        ]);

        $validator->setAttributeNames([
        ]);

        return $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pushNewSyncFile(Request $request)
    {
        if ($request->username == config(Constants::MASTER_SERVER_USERNAME) && $request->password == config(Constants::MASTER_SERVER_PASSWORD)) {
            $logFile = Input::file(Constants::API_LOG_FILE);
            $path    = Helpers::getCurrentSyncingDirectory() . '/' . $logFile->getClientOriginalName();
            File::put($path, File::get($logFile));
            chmod($path, 0777);
            ProcessSyncLogFile::dispatch($path)
                    ->onConnection(config(Constants::SYNC_QUEUE_DRIVER))
                    ->onQueue(config(Constants::SYNC_QUEUE_NAME))
                    ->delay(now()->addMinutes(config(Constants::SYNC_QUEUE_DELAY)));
            return "True";
        } else {
            abort(401, 'unauthorized user');
        }
    }

}
