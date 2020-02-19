<?php


namespace App\Controller;


use Cake\Error\Debugger;
use Cake\Utility;
use Cake\Utility\Security;

class Upload extends AppController
{
    public function upload()
    {
        // Sample for encrypting/ decrypting image file
        if ($this->request->is('post')) {
            $fileUpload = $_FILES["fileToUpload"];
            $oldSize = $fileUpload['size'];

            $file = file_get_contents($fileUpload["tmp_name"]);

            $cipher = "aes-128-cbc";
            $ivLen = openssl_cipher_iv_length($cipher);
            $key = openssl_random_pseudo_bytes(128);

            $iv = openssl_random_pseudo_bytes($ivLen);
            $cipherText = openssl_encrypt($file, $cipher, $key, $options=0, $iv);

            $img = openssl_decrypt($cipherText, $cipher, $key, $options=0, $iv);
            Debugger::dump(['info' => phpinfo(), 'old' => $oldSize, 'new' => strlen($img)]);

            $output = '
               <table class="table table-bordered table-striped">
                <tr>
                 <th width="10%">ID</th>
                 <th width="70%">Image</th>
                 <th width="10%">Remove</th>
                </tr>
              ';

            $output .= '
                <tr>
                 <td>
                  <img src="data:image/jpeg;base64,'.base64_encode($img).'" class="img-thumbnail" alt=""/>
                 </td>
                </tr>
               ';
            $output .= '</table>';
            echo $output;
        }
    }
}
