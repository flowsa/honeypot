<?php
/**
 * FlowReCaptcha plugin for Craft CMS 3.x
 *
 * A re-captcha field type for validating forms
 *
 * @link      https://www.flowsa.com
 * @copyright Copyright (c) 2018 Flow Communications
 */

namespace flowsa\honeypot\assetbundles\honeypotfieldfield;

use Craft;
use craft\web\AssetBundle;
use yii\web\JqueryAsset;


/**
 * @author    Flow Communications
 * @package   FlowReCaptcha
 * @since     0.0.1
 */
class HoneypotFieldFieldAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@flowsa/honeypot/assetbundles/honeypotfieldfield/dist";

        $this->depends = [
            JqueryAsset::class,
        ];

        $this->js = [
            'js/HoneypotField.js',
        ];

        $this->css = [
            'css/HoneypotField.css',
        ];

        parent::init();
    }
}
