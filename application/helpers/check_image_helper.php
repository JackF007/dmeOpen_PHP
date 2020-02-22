<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('remote_file_exists')) {

    function remote_file_exists($source, $extra_mile = 0) {
# EXTRA MILE = 0////////////////////////////////// Does it exist?
# # EXTRA MILE = 1////////////////////////////////// Is it an image?
        if ($extra_mile === 1) {
            $img = @getimagesize($source);
            if (!$img)
                return 0;
            else {
                switch ($img[2]) {
                    case 0: return 0;
                        break;
                    case 1:return $source;
                        break;
                    case 2:return $source;
                        break;
                    case 3:return $source;
                        break;
                    case 6:return $source;
                        break;
                    default:return 0;
                        break;
                }
            }
        } else {
            if (@FClose(@FOpen($source, 'r')))
                return 1;
            else
                return 0;
        }
    }

}
