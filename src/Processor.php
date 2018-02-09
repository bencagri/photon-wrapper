<?php

namespace Photon\Wrapper;

use Image_Processor;

require_once ( dirname( __FILE__ ) . '/Photon/class-image-processor.php' );

/**
 * Class Processor
 * @package Photon\Wrapper
 */
class Processor
{

    protected $allowed_functions = [
    //	'q'           => RESERVED
    //	'zoom'        => global resolution multiplier (argument filter)
    //	'quality'     => sets the quality of JPEG images during processing
    //	'strip        => strips JPEG images of exif, icc or all "extra" data (params: info,color,all)
        'h'           => 'set_height',      // done
        'w'           => 'set_width',       // done
        'crop'        => 'crop',            // done
        'resize'      => 'resize_and_crop', // done
        'fit'         => 'fit_in_box',      // done
        'lb'          => 'letterbox',       // done
        'ulb'         => 'unletterbox',     // compat
        'filter'      => 'filter',          // compat
        'brightness'  => 'brightness',      // compat
        'contrast'    => 'contrast',        // compat
        'colorize'    => 'colorize',        // compat
        'smooth'      => 'smooth',          // compat
    ];

    /**
     * @var array
     */
    protected $allowed_types = [
        'gif',
        'jpg',
        'jpeg',
        'png',
    ];


    /**
     * @var array
     */
    protected $disallowed_file_headers = [
        '8BPS'
    ];

    /**
     * @var int
     */
    protected $remote_image_max_size = 55 * 1024 * 1024 ;

    /**
     * Array of domains exceptions
     * Keys are domain name
     * Values are bitmasks with the following options:
     * PHOTON__ALLOW_QUERY_STRINGS: Append the string found in the 'q' query string parameter as the query string of the remote URL
     */
    protected $origin_domain_exceptions = [];

    /**
     * If unprocessed origin images should cached by a Photon-enabled CDN, then the CDN's base URL should be returned by the filter
     */
    protected $origin_image_cdn_url = false;


    protected $image_url;

    protected $query;
    /**
     * Processor constructor.
     */
    public function __construct($imageUrl, $query)
    {
        error_reporting(0); // because of $GLOBALS

        $this->image_url = $imageUrl;
        $this->query = $query;

        define( 'PHOTON__ALLOW_QUERY_STRINGS', 1 );

        define( 'JPG_MAX_QUALITY', 89 );
        define( 'PNG_MAX_QUALITY', 80 );
        define( 'WEBP_MAX_QUALITY', 80 );

        // The 'w' and 'h' parameter are processed distinctly
        define( 'ALLOW_DIMS_CHAINING', true );

        // Strip all meta data from WebP images by default
        define( 'CWEBP_DEFAULT_META_STRIP', 'all' );

        // You can override this by defining it in config.php
        if ( ! defined( 'UPSCALE_MAX_PIXELS' ) )
            define( 'UPSCALE_MAX_PIXELS', 2000 );

        // Allow smaller upscales for GIFs, compared to the other image types
        if ( ! defined( 'UPSCALE_MAX_PIXELS_GIF' ) )
            define( 'UPSCALE_MAX_PIXELS_GIF', 1000 );

        // Implicit configuration
        if ( file_exists( '/usr/local/bin/optipng' ) && ! defined( 'DISABLE_IMAGE_OPTIMIZATIONS' ) )
            define( 'OPTIPNG', '/usr/local/bin/optipng' );
        else
            define( 'OPTIPNG', false );

        if ( file_exists( '/usr/local/bin/pngquant' ) && ! defined( 'DISABLE_IMAGE_OPTIMIZATIONS' ) )
            define( 'PNGQUANT', '/usr/local/bin/pngquant' );
        else
            define( 'PNGQUANT', false );

        if ( file_exists( '/usr/local/bin/cwebp' ) && ! defined( 'DISABLE_IMAGE_OPTIMIZATIONS' ) )
            define( 'CWEBP', '/usr/local/bin/cwebp' );
        else
            define( 'CWEBP', false );

        if ( file_exists( '/usr/local/bin/jpegoptim' ) && ! defined( 'DISABLE_IMAGE_OPTIMIZATIONS' ) )
            define( 'JPEGOPTIM', '/usr/local/bin/jpegoptim' );
        else
            define( 'JPEGOPTIM', false );
        
    }

    public function process()
    {
        $_GET += $this->query;
        $request_arg_array = array_intersect_key( $this->query, $this->allowed_functions );
        $request_from_origin_cdn = ( 0 < count( $request_arg_array ) && false !== $this->origin_image_cdn_url );

        $raw_data = $this->fetch_raw_data( $this->image_url, 10, 3, 3, $request_from_origin_cdn );
        if ( ! $raw_data ) {
            $this->httpdie( '400 Bad Request', 'Sorry, the parameters you provided were not valid' );
        }

        foreach ( $this->disallowed_file_headers as $file_header ) {
            if ( substr( $raw_data, 0, strlen( $file_header ) ) == $file_header )
                $this->httpdie( '400 Bad Request', 'Error 0002. The type of image you are trying to process is not allowed.' );
        }

        $img_proc = new Image_Processor();
        if ( ! $img_proc )
            $this->httpdie( '500 Internal Server Error', 'Error 0003. Unable to load the image.' );

        $img_proc->use_client_hints    = false;
        $img_proc->send_nosniff_header = true;
        $img_proc->norm_color_profile  = false;
        $img_proc->send_bytes_saved    = true;
        $img_proc->send_etag_header    = true;
        $img_proc->canonical_url       = $this->image_url;
        $img_proc->image_max_age       = 63115200;
        $img_proc->image_data          = $GLOBALS['raw_data'];

        if ( ! $img_proc->load_image() )
            $this->httpdie( '400 Bad Request', 'Error 0004. Unable to load the image.' );

        if ( ! in_array( $img_proc->image_format, $this->allowed_types ) )
            $this->httpdie( '400 Bad Request', 'Error 0005. The type of image you are trying to process is not allowed.' );

        $img_proc->process_image();
    }

    private function httpdie( $code = '404 Not Found', $message = 'Error: 404 Not Found' ) {
        $numerical_error_code = preg_replace( '/[^\\d]/', '', $code );
        header( 'HTTP/1.1 ' . $code );
        die( $message );
    }

    private function fetch_raw_data( $url, $timeout = 10, $connect_timeout = 3, $max_redirs = 3, $fetch_from_origin_cdn = false ) {
        // reset image data since we redirect recursively
        $GLOBALS['raw_data'] = '';
        $GLOBALS['raw_data_size'] = 0;

        if ( $fetch_from_origin_cdn ) {
            // Construct a Photon request for the unprocessed origin image
            $timeout = $timeout + 2;
            $is_ssl  = preg_match( '|^https://|', $url );
            $path    = preg_replace( '|^http[s]?://|', '', $url );
            $url     = $GLOBALS['origin_image_cdn_url'] . $path;
            if ( $is_ssl ) {
                $url .= '?ssl=1';
            }
        }

        $parsed = parse_url( $url );
        $required = array( 'scheme', 'host', 'path' );

        if ( ! $parsed || count( array_intersect_key( array_flip( $required ), $parsed ) ) !== count( $required ) ) {
            dump(11);

            return false;
        }

        $ip   = gethostbyname( $parsed['host'] );
        $port = getservbyname( $parsed['scheme'], 'tcp' );
        $url  = $parsed['scheme'] . '://' . $parsed['host'] . $parsed['path'];

        if ( PHOTON__ALLOW_QUERY_STRINGS && isset( $parsed['query'] ) ) {
            $host = strtolower( $parsed['host'] );
            if ( $GLOBALS['origin_domain_exceptions'] && array_key_exists( $host, $GLOBALS['origin_domain_exceptions'] ) ) {
                if ( $GLOBALS['origin_domain_exceptions'][$host] ) {
                    $url .= '?' . $parsed['query'];
                }
            }
        }

        // Ensure we maintain our SSL flag for 'fetch_from_origin_cdn' requests,
        // regardless of whether PHOTON__ALLOW_QUERY_STRINGS is enabled or not.
        if ( $fetch_from_origin_cdn && 'ssl=1' == $parsed['query'] ) {
            $url .= '?ssl=1';
        }

        // https://bugs.php.net/bug.php?id=64948
        if ( ! filter_var( str_replace( '_', '-', $url ), FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_PATH_REQUIRED ) ) {
            dump(1);
            return false;
        }

        $allowed_ip_types = array( 'flags' => FILTER_FLAG_IPV4, );

        if ( ! filter_var( $ip, FILTER_VALIDATE_IP, $allowed_ip_types ) ) {
            dump(2);

            return false;
        }

        if ( ! filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) && ! apply_filters( 'allow_private_ips', false ) ) {
            dump(3);

            return false;
        }

        if ( isset( $parsed['port'] ) && $parsed['port'] !== $port ) {
            dump(4);
            return false;
        }

        $ch = curl_init( $url );

        curl_setopt_array( $ch, array(
            CURLOPT_USERAGENT            =>'Photon/1.0',
            CURLOPT_TIMEOUT              => $timeout,
            CURLOPT_CONNECTTIMEOUT       => $connect_timeout,
            CURLOPT_PROTOCOLS            => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_SSL_VERIFYPEER       => false,
            CURLOPT_SSL_VERIFYHOST       => false,
            CURLOPT_FOLLOWLOCATION       => false,
            CURLOPT_DNS_USE_GLOBAL_CACHE => false,
            CURLOPT_RESOLVE              => array( $parsed['host'] . ':' . $port . ':' . $ip ),
            CURLOPT_HEADERFUNCTION       => function( $ch, $header ) {
                if ( preg_match( '/^Content-Length:\s*(\d+)$/i', rtrim( $header ), $matches ) ) {
                    if ( $matches[1] >  $this->remote_image_max_size ) {
                        $this->httpdie( '400 Bad Request', 'You can only process images up to ' . $this->remote_image_max_size . ' bytes.' );
                    }
                }

                return strlen( $header );
            },
            CURLOPT_WRITEFUNCTION        => function( $ch, $data ) {
                $bytes = strlen( $data );
                $GLOBALS['raw_data'] .= $data;
                $GLOBALS['raw_data_size'] += $bytes;

                if ( $GLOBALS['raw_data_size'] > $this->remote_image_max_size ) {
                    $this->httpdie( '400 Bad Request', 'You can only process images up to ' . $this->remote_image_max_size . ' bytes.' );
                }

                return $bytes;
            },
        ) );

        if ( ! curl_exec( $ch ) ) {
            dump(5);

            return false;
        }

        $status = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
        if ( 200 == $status ) {
            return true;
        }

        // handle redirects
        if ( $status >= 300 && $status <= 399 ) {
            if ( $max_redirs > 0 ) {
                return $this->fetch_raw_data( curl_getinfo( $ch, CURLINFO_REDIRECT_URL ), $timeout, $connect_timeout, $max_redirs - 1 );
            }
            $this->httpdie( '400 Bad Request', 'Too many redirects' );
        }

        // handle all other errors
        switch( $status ) {
            case 401:
            case 403:
                $this->httpdie( '403 Forbidden', 'We cannot complete this request, remote data could not be fetched' );
                break;
            case 404:
            case 410:
                $this->httpdie( '404 File Not Found', 'We cannot complete this request, remote data could not be fetched' );
                break;
            case 429:
                $this->httpdie( '429 Too Many Requests', 'We cannot complete this request, remote data could not be fetched' );
                break;
            case 451:
                $this->httpdie( '451 Unavailable For Legal Reasons', 'We cannot complete this request, remote data could not be fetched' );
                break;
            default:
                $this->httpdie( '400 Bad Request', 'We cannot complete this request, remote server returned an unexpected status code (' . $status . ')' );
        }
    }
}