<?php

/*
Name:    gdr2_Img
Version: 2.8.8
Author:  Milan Petrovic
Email:   milan@gdragon.info
Website: http://www.dev4press.com/libs/gdr2/

== Copyright ==
Copyright 2008 - 2015 Milan Petrovic (email: milan@gdragon.info)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once('gdr2.fnc.php');

if (!class_exists('gdr2_Img')) {
    class gdr2_Img {
        /**
         * Returns extension of a file.
         *
         * @param string $input file path or file name
         * @return string file extension
         */
        public static function get_extension($input) {
            return get_extension($input);
        }

        /**
         * Creates a thumbnail from input image.
         *
         * @param string $input_file file to create thumbnail from
         * @param int $new_width thumbnail width
         * @param int $new_height thumbnail height
         * @param string $output_folder where to save the thumbnail. if empty, saves in the input image folder
         */
        public static function create_thumbnail($input_file, $new_width, $new_height, $output_folder = '', $new_name = '%NAME%_thumb.jpg', $output_type = 'jpg') {
            gdr2_Img::resize_image($input_file, $new_width, $new_height, $output_folder, false, $new_name, $output_type);
        }

        /**
         * Creates a thumbnail from input image using crop resize.
         *
         * @param string $input_file file to create thumbnail from
         * @param int $new_width thumbnail width
         * @param int $new_height thumbnail height
         * @param string $output_folder where to save the thumbnail. if empty, saves in the input image folder
         */
        public static function create_thumbnail_crop($input_file, $new_width, $new_height, $output_folder = '', $new_name = '%NAME%_thumb.jpg', $output_type = 'jpg') {
            gdr2_Img::resize_image_crop($input_file, $new_width, $new_height, $output_folder, false, $new_name, $output_type);
        }

        /**
         * Resizes the image.
         *
         * @param string $input_file file to resize
         * @param int $new_width image width
         * @param int $new_height image height
         * @param string $output_folder where to save new image. if empty, saves in the input image folder
         * @param bool $delete_input should the input image be deleted
         * @param string $new_name created file name
         * @param string $new_extension created file extension
         */
        public static function resize_image($input_file, $new_width, $new_height, $output_folder = '', $delete_input = false, $new_name = '%NAME%.%EXT%', $output_type = '') {
            $ext = gdr2_Img::get_extension($input_file);
            if ($output_folder == '') $output_folder = dirname($input_file);

            $file_name = basename($input_file, '.'.$ext);
            $file_name = str_replace('%NAME%', $file_name, $new_name);
            $file_name = str_replace('%EXT%', $ext, $file_name);
            $output = $output_folder.'/'.$file_name;

            switch ($ext) {
                case 'jpg':
                    $src_image = imagecreatefromjpeg($input_file);
                    break;
                case 'png':
                    $src_image = imagecreatefrompng($input_file);
                    break;
                case 'gif':
                    $src_image = imagecreatefromgif($input_file);
                    break;
            }

            $src_size = getimagesize($input_file);
            $xr = $src_size[0] / $new_width;
            $yr = $src_size[1] / $new_height;
            if ($xr < 1 && $yr < 1) {
                if ($delete_input) rename($input_file, $output);
                else copy($input_file, $output);
            } else {
                if ($xr >= $yr) {
                    $xn = $new_width;
                    $yn = floor($src_size[1] / $xr);
                }
                else {
                    $yn = $new_height;
                    $xn = floor($src_size[0] / $yr);
                }
                $end_image = imagecreatetruecolor($xn, $yn);
                imagecopyresampled($end_image, $src_image, 0, 0, 0, 0, $xn, $yn, $src_size[0], $src_size[1]);
                imagedestroy($src_image);
                if ($delete_input) unlink($input_file);

                if ($output_type == '') $output_type = $ext;
                switch ($output_type) {
                    case 'jpg':
                        imagejpeg($end_image, $output);
                        break;
                    case 'png':
                        imagepng($end_image, $output);
                        break;
                    case 'gif':
                        imagegif($end_image, $output);
                        break;
                }
                imagedestroy($end_image);
            }
            return $output;
        }

        /**
         * Resizes the image to exact dimensions using cropping.
         *
         * @param string $input_file file to resize
         * @param int $new_width image width
         * @param int $new_height image height
         * @param string $output_folder where to save new image. if empty, saves in the input image folder
         * @param bool $delete_input should the input image be deleted
         * @param string $new_name created file name
         * @param string $new_extension created file extension
         */
        public static function resize_image_crop($input_file, $new_width, $new_height, $output_folder = '', $delete_input = false, $new_name = '%NAME%.%EXT%', $output_type = '') {
            $ext = gdr2_Img::get_extension($input_file);
            if ($output_folder == '') $output_folder = dirname($input_file);

            $file_name = basename($input_file, '.'.$ext);
            $file_name = str_replace('%NAME%', $file_name, $new_name);
            $file_name = str_replace('%EXT%', $ext, $file_name);
            $output = $output_folder.'/'.$file_name;

            switch ($ext) {
                case 'jpg':
                    $src_image = imagecreatefromjpeg($input_file);
                    break;
                case 'png':
                    $src_image = imagecreatefrompng($input_file);
                    break;
                case 'gif':
                    $src_image = imagecreatefromgif($input_file);
                    break;
            }

            $src_size = getimagesize($input_file);
            $xr = $src_size[0] / $new_width;
            $yr = $src_size[1] / $new_height;
            if ($xr < 1 && $yr < 1) {
                $end_image = gdr2_Img::create_image_overlay($src_image, $src_size, $new_width, $new_height);
            } else {
                if ($xr > $yr) {
                    $yloc = 0;
                    $ysize = $src_size[1];
                    $xsize = $new_width * $yr;
                    $xloc = floor(($src_size[0] - $xsize)/ 2);
                } else if ($xr < $yr) {
                    $xloc = 0;
                    $xsize = $src_size[0];
                    $ysize = $new_height * $xr;
                    $yloc = floor(($src_size[1] - $ysize) / 2);
                } else {
                    $xloc = 0;
                    $yloc = 0;
                    $xsize = $src_size[0];
                    $ysize = $src_size[1];
                }
                $end_image = imagecreatetruecolor($new_width, $new_height);
                imagecopyresampled($end_image, $src_image, 0, 0, $xloc, $yloc, $new_width, $new_height, $xsize, $ysize);
            }

            imagedestroy($src_image);
            if ($delete_input) unlink($input_file);

            if ($output_type == '') $output_type = $ext;
            switch ($output_type) {
                case 'jpg':
                    imagejpeg($end_image, $output);
                    break;
                case 'png':
                    imagepng($end_image, $output);
                    break;
                case 'gif':
                    imagegif($end_image, $output);
                    break;
            }
            imagedestroy($end_image);
            return $output;
        }

        /**
         * Create new image and overlays src_image in the middle.
         *
         * @param image $src_image start image
         * @param array $src_size getimage size result array
         * @param int $new_width image width
         * @param int $new_height image height
         * @param array $background_color rgb color
         * @return image image resource
         */
        public static function create_image_overlay($src_image, $src_size, $new_width, $new_height, $background_color = array(255, 255, 255)) {
            $end_image = imagecreatetruecolor($new_width, $new_height);
            $bgd_color = imagecolorallocate($end_image, $background_color[0], $background_color[2], $background_color[2]);
            imagefill($end_image, 0, 0, $bgd_color);
            return $end_image;
        }

        /**
         * Get random string using rand function and timestamp.
         *
         * @param int $rand_length
         * @return string random string
         */
        public static function random_with_time($rand_length = 8) {
            $letters = '1234567890abcdefghijklmnopqrstuvwxyz';
            $s = '';
            $lettersLength = strlen($letters) - 1;

            for ($i = 0 ; $i < $rand_length ; $i++) {
                $s.= $letters[rand(0, $lettersLength)];
            }

            return time().'_'.$s;
        }

        /**
         * Handles upload of image into
         *
         * @param array $upl_file uploaded file from the $_FILES
         * @param string $output_folder where to save the file
         * @param array $settings upload settings based on $img_default
         */
        public static function upload_image($upl_file, $output_folder, $new_name = '', $settings = array()) {
            $img_defaults = array(
                'limit_extensions' => false,
                'limit_size' => false,
                'limit_dimensions' => false,
                'allowed_extension' => 'gif|jpg|jpeg|png',
                'maximum_size' => '1024K',
                'maximum_width' => 1920,
                'maximum_height' => 1080,
                'minimum_width' => 0,
                'minimum_height' => 0,
                'auto_resize' => false,
                'resize_crop' => true,
                'force_square' => false,
                'existing_image' => 'rename',
                'image_name' => '%OLD_NAME%%RENAME%.%EXT%'
            );

            $result = $errors = array();
            $options = prefill_attributes($img_defaults, $settings);

            $upl_ext = gdr2_Img::get_extension($upl_file['name']);
            $upl_name = substr($upl_file['name'], 0, strlen($upl_file['name']) - strlen($upl_ext) - 1);

            $image_path_temp = $output_folder.time().'_'.$upl_file['name'];

            $result['original_name'] = $upl_file['name'];
            if (is_uploaded_file($upl_file['tmp_name'])) {
                move_uploaded_file($upl_file['tmp_name'], $image_path_temp);
                $valid = true;
                $resize = false;
                $img_size = getimagesize($image_path_temp);
                $result['image_size'] = $img_size;
                if ($options['limit_extensions']) {
                    $exts = explode('|', $options['allowed_extension']);
                    if (!in_array($upl_ext, $exts)) {
                        $valid = false;
                        $errors[] = 'Invalid extension.';
                    }
                }

                $too_big = false;
                if ($options['limit_dimensions']) {
                    if (!(($img_size[0] >= $options['minimum_width']) && ($img_size[1] >= $options['minimum_height']))) {
                        $valid = false;
                        $errors[] = __("Image is too small.", "gdr2");
                    } else if (!(($img_size[0] <= $options['maximum_width']) && ($img_size[1] <= $options['maximum_height']))) {
                        $too_big = true;
                        if (!$options['auto_resize'] || ($options['auto_resize'] && !$valid)) {
                            $valid = false;
                            $errors[] = __("Image has invalid dimensions.", "gdr2");
                        }
                    }
                }

                if (($options['auto_resize'] && $too_big) || $options['force_square']) {
                    $r_width = $options['maximum_width'];
                    $r_height = $options['maximum_height'];
                    if ($options['force_square']) {
                        if ($img_size[0] < $options['maximum_width'] || $img_size[1] < $options['maximum_height']) {
                            $r_width = $r_height = min(array($img_size[0], $img_size[1]));
                        }
                    }

                    if ($options['resize_crop'] || $options['force_square']) {
                        $image_path_temp = gdr2_Img::resize_image_crop($image_path_temp, $r_width, $r_height, '', true, '%NAME%.%EXT%.resize');
                    } else {
                        $image_path_temp = gdr2_Img::resize_image($image_path_temp, $r_width, $r_height, '', true, '%NAME%.%EXT%.resize');
                    }
                    $resize = true;
                }

                if ($options['limit_size'] && !$resize) {
                    if ($upl_file['size'] > gdr2_Core::recalculate_size($options['maximum_size'])) {
                        $valid = false;
                        $errors[] = __("Image is too big.", "gdr2");
                    }
                }

                if ($valid) {
                    $image_path = '';
                    $image_name = str_replace('%TIME%', time(), $options['image_name']);
                    $image_name = str_replace('%RANDOM%', gdr2_Img::random_with_time(16), $options['image_name']);
                    $image_name = str_replace('%OLD_NAME%', $upl_name, $image_name);
                    $image_name = str_replace('%NEW_NAME%', $new_name, $image_name);
                    $image_name = str_replace('%EXT%', $upl_ext, $image_name);
                    $image_main = str_replace('%RENAME%', '', $image_name);

                    $move = false;
                    if (file_exists($output_folder.$image_main)) {
                        if ($options['existing_image'] == 'overwrite') {
                            unlink($image_path);
                            $image_name = str_replace('%RENAME%', '', $image_name);
                            $image_path = $output_folder.$image_name;
                            $move = true;
                        }
                        if ($options['existing_image'] == 'rename') {
                            $image_name = str_replace('%RENAME%', '_1', $image_name);
                            $image_path = $output_folder.$image_name;
                            $move = true;
                        }
                    } else {
                        $image_name = str_replace('%RENAME%', '', $image_name);
                        $image_path = $output_folder.$image_name;
                        $move = true;
                    }

                    if ($move) {
                        rename($image_path_temp, $image_path);
                        $result['status'] = 'ok';
                    } else {
                        $result['status'] = 'exists';
                    }

                    $result['file_path'] = $image_path;
                    $result['content_type'] = $upl_file['type'];
                    $result['file_name'] = $image_name;
                } else {
                    unlink($image_path_temp);
                    $result['status'] = 'error';
                    $result['errors'] = $errors;
                }
            } else {
                $errors[] = __("File upload failed.", "gdr2");
                $result['status'] = 'error';
                $result['errors'] = $errors;
            }
            return $result;
        }

        /**
         * Returns the image.
         *
         * @param string $path image path
         * @param string $content_type full content type
         * @param string $ext extension
         */
        public static function serve_image($path, $content_type, $ext) {
            Header("Content-type: ".$content_type);
            switch ($ext) {
                case 'jpg':
                    $image = imagecreatefromjpeg($path);
                    imagejpeg($image);
                    imagedestroy($image);
                    break;
                case 'png':
                    $image = imagecreatefrompng($path);
                    imagepng($image);
                    imagedestroy($image);
                    break;
                case 'gif':
                    $image = imagecreatefromgif($path);
                    imagegif($image);
                    imagedestroy($image);
                    break;
            }
        }
    }
}

?>