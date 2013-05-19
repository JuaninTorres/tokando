<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');
$opciones = array(
    'upload_dir' => '../../_files/',
    'upload_url' => '/selectdj/_upload/_files/',
    'user_dirs'  => true,
    'mkdir_mode' => 0777
    );

class CustomUploadHandler extends UploadHandler {
    protected function get_user_id() {
        @session_start();
        return session_id();
    }
}

$upload_handler = new CustomUploadHandler($opciones);