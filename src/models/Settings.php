<?php
/**
 * Craft ReCaptcha v2 plugin for Craft CMS 3.x
 *
 * Submit entry form data  to SOAP service
 *
 * @link      https://www.flowsa.com
 * @copyright Copyright (c) 2020 Flow communications
 */

namespace flowsa\honeypot\models;

use Craft;
use craft\base\Model;

/**
 * CraftRecaptchaV2 Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Flow communications
 * @package   CraftRecaptchaV2
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some field model attribute
     *
     * @var string
     */
    public $siteKey = '';
    /**
     * Some field model attribute
     *
     * @var string
     */
    public $secretKey = '';
    /**
     * Some field model attribute
     *
     * @var string
     */
    public $honeypotParam = '';

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['siteKey'], 'string'],
            [['secretKey'], 'string'],
            [['honeypotParam'], 'string'],
        ];
    }
}
