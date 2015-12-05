<?php
/**
 * JsonAPI - Slim extension to implement fast JSON API's
 *
 * @package Slim
 * @subpackage View
 * @author Jonathan Tavares <the.entomb@gmail.com>
 * @license GNU General Public License, version 3
 * @filesource
 *
 *
 */
namespace Slim\JsonApi;

use Slim\View;
use Slim\Slim;


/**
 * JsonApiView - view wrapper for json responses (with error code).
 *
 * @package Slim
 * @subpackage View
 * @author Jonathan Tavares <the.entomb@gmail.com>
 * @license GNU General Public License, version 3
 * @filesource
 */
class JsonApiView extends View
{

    /**
     * Bitmask consisting of <b>JSON_HEX_QUOT</b>,
     * <b>JSON_HEX_TAG</b>,
     * <b>JSON_HEX_AMP</b>,
     * <b>JSON_HEX_APOS</b>,
     * <b>JSON_NUMERIC_CHECK</b>,
     * <b>JSON_PRETTY_PRINT</b>,
     * <b>JSON_UNESCAPED_SLASHES</b>,
     * <b>JSON_FORCE_OBJECT</b>,
     * <b>JSON_UNESCAPED_UNICODE</b>.
     * The behaviour of these constants is described on
     * the JSON constants page.
     * @var int
     */
    public $encodingOptions = 0;

    /**
     * Content-Type sent through the HTTP header.
     * Default is set to "application/json",
     * append ";charset=UTF-8" to force the charset
     * @var string
     */
    public $contentType = 'application/json';

    /**
     * @var array
     */
    private $response;


    /**
     * Construct JsonApiView instance
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function render($status = 200, $data = null)
    {
        $app = Slim::getInstance();

        //remove flash messages
        unset($this->data['flash']);
        if ($status < 300) {
            if (isset($this->data['data'])) {
                // for pagination
                $this->response = $this->all();
            } else {
                // for normal
                $this->response['data'] = $this->all();
            }
        } else {
            // for error
            $this->response['errors'] = $this->all();
        }

        $app->response()->status($status);
        $app->response()->header('Content-Type', $this->contentType);

        $jsonpCallback = $app->request->get('callback', null);

        if ($jsonpCallback !== null) {
            $app->response()->body($jsonpCallback . '(' . json_encode($this->response, $this->encodingOptions) . ')');
        } else {
            $app->response()->body(json_encode($this->response, $this->encodingOptions));
        }

        $app->stop();
    }
}
