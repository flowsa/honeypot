<?php
/**
 * Craft ReCaptcha v2 plugin for Craft CMS 3.x
 *
 * Submit entry form data  to SOAP service
 *
 * @link      https://www.flowsa.com
 * @copyright Copyright (c) 2020 Flow communications
 */

namespace flowsa\honeypot\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Html;

// use flowsa\honeypot\assetbundles\honeypotfieldfield\HoneypotFieldFieldAsset;
use flowsa\honeypot\assetbundles\honeypotfieldfield\HoneypotFieldFieldAsset;
use flowsa\honeypot\Plugin;

use yii\db\Schema;


/**
 * CraftRecaptchaV2Field Field
 *
 * Whenever someone creates a new field in Craft, they must specify what
 * type of field it is. The system comes with a handful of field types baked in,
 * and we’ve made it extremely easy for plugins to add new ones.
 *
 * https://craftcms.com/docs/plugins/field-types
 *
 * @author    Flow communications
 * @package   CraftRecaptchaV2
 * @since     1.0.0
 */
class HoneypotField extends Field
{
    // Public Properties
    // =========================================================================
 /**
     * Some attribute
     *
     * @var string
     */
    public $siteKey = '';

    /**
     * Some attribute
     *
     * @var string
     */
    public $secretKey = '';
    
    // Static Methods
    // =========================================================================

    /**
     * Returns the display name of this class.
     *
     * @return string The display name of this class.
     */
    public static function displayName(): string
    {
        return Craft::t('honeypot', 'Honeypot (Flow)');
    }

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
        $rules = parent::rules();
        // $rules = array_merge($rules, [
        //     ['someAttribute', 'string'],
        //     ['someAttribute', 'default', 'value' => 'Some Default'],
        // ]);
        return $rules;
    }

    /**
     * Returns the column type that this field should get within the content table.
     *
     * This method will only be called if [[hasContentColumn()]] returns true.
     *
     * @return string The column type. [[\yii\db\QueryBuilder::getColumnType()]] will be called
     * to convert the give column type to the physical one. For example, `string` will be converted
     * as `varchar(255)` and `string(100)` becomes `varchar(100)`. `not null` will automatically be
     * appended as well.
     * @see \yii\db\QueryBuilder::getColumnType()
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_STRING;
    }

    /**
     * Normalizes the field’s value for use.
     *
     * This method is called when the field’s value is first accessed from the element. For example, the first time
     * `entry.myFieldHandle` is called from a template, or right before [[getInputHtml()]] is called. Whatever
     * this method returns is what `entry.myFieldHandle` will likewise return, and what [[getInputHtml()]]’s and
     * [[serializeValue()]]’s $value arguments will be set to.
     *
     * @param mixed                 $value   The raw field value
     * @param ElementInterface|null $element The element the field is associated with, if there is one
     *
     * @return mixed The prepared field value
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        return $value;
    }

    /**
     * Prepares the field’s value to be stored somewhere, like the content table or JSON-encoded in an entry revision table.
     *
     * Data types that are JSON-encodable are safe (arrays, integers, strings, booleans, etc).
     * Whatever this returns should be something [[normalizeValue()]] can handle.
     *
     * @param mixed $value The raw field value
     * @param ElementInterface|null $element The element the field is associated with, if there is one
     * @return mixed The serialized field value
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
        return parent::serializeValue($value, $element);
    }


    /**
     * Returns the field’s input HTML.
     *
    
     * ```
     *
     * The same principles also apply if you’re including your JavaScript code with
     * [[\craft\web\View::registerJs()]].
     *
     * @param mixed                 $value           The field’s value. This will either be the [[normalizeValue() normalized value]],
     *                                               raw POST data (i.e. if there was a validation error), or null
     * @param ElementInterface|null $element         The element the field is associated with, if there is one
     *
     * @return string The input HTML.
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
       
        Craft::$app->getView()->registerAssetBundle(HoneypotFieldFieldAsset::class);
        $settings = Plugin::$plugin->settings;
        $id = Html::id($this->handle);

        $id = $settings->honeypotParam ?: $id;

        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        return Craft::$app->getView()->renderTemplate('_includes/forms/text', [
            'type' => 'text',
            'id' => $id,
            'instructionsId' => "$id-instructions",
            'name' => $id,
            'value' => '',
            'fieldLabel' => '',
            'namespace' => $namespacedId,
        ]);
    }
}
