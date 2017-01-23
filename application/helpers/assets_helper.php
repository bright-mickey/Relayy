<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Asset URL
 * 
 * Create a local URL to your assets based on your basepath.
 *
 * @access	public
 * @param   string
 * @return	string
 */
if (!function_exists('asset_url')) {
    function asset_url($uri = '', $group = FALSE) {
        $CI = & get_instance();
        
        if (!$dir = $CI->config->item('assets_path')) {
            $dir = 'assets/';
        }
        
        if ($group) {
            return $CI->config->base_url($dir . $group . '/' . $uri);
        } else {
            return $CI->config->base_url($dir . $uri);
        }
    }
    function asset_base_url(){
        $CI = & get_instance();
        if (!$dir = $CI->config->item('assets_path')) {
            $dir = 'assets/';
        }
        return $CI->config->base_url($dir);
    }
    function uploads_base_url(){
        $CI = & get_instance();
        return $CI->config->item('base_url')."uploads/";
    }
}

function gf_uploads_filepath()
{

    $uploads_path = $_SERVER['DOCUMENT_ROOT'].'/uploads/';

    return $uploads_path;
}

function gf_profile_picture_path()
{

    $profile_picture_path = $_SERVER['DOCUMENT_ROOT'].'/images/users/';

    return $profile_picture_path;
}

function gf_messages_filepath()
{

    $messages_path = $_SERVER['DOCUMENT_ROOT'].'/messages/';

    return $messages_path;
}

function gf_mtemplates_filepath()
{

    $mtemplates_path = $_SERVER['DOCUMENT_ROOT'].'/messages/templates/';

    return $mtemplates_path;
}

function gf_captcha_filepath()
{
    $path = $_SERVER['DOCUMENT_ROOT'].'/captcha/';

    return $path;
}

function gf_captcha_url()
{
    return site_url('captcha').'/';
}

function gf_attach_filepath()
{
    $path = $_SERVER['DOCUMENT_ROOT'].'/attach/';

    return $path;
}

function gf_attach_url()
{
    return site_url('attach').'/';
}

function gf_images_filepath()
{
    $path = $_SERVER['DOCUMENT_ROOT'].'/images/';

    return $path;
}

function gf_images_url()
{
    return site_url('images').'/';
}

function gf_utc2us_date($date, $format="m/d/Y")
{
    $date->setTimeZone(new DateTimeZone("America/Chicago"));

    return $date->format($format);
}

function gf_utc2us_time($date)
{
    $date->setTimeZone(new DateTimeZone("America/Chicago"));

    return $date->format("g:i A");
}

function gf_utc2us_long($date)
{

    $date->setTimeZone(new DateTimeZone("America/Chicago"));

    return $date->format("F j, Y").' at '.$date->format("g:i A");

}



/* End of file url_helper.php */
/* Location: ./application/helpers/url_helper.php */