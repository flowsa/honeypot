<?php
/**
 * Craft ReCaptcha v2 plugin for Craft CMS 3.x
 *
 * Submit entry form data  to SOAP service
 *
 * @link      https://www.flowsa.com
 * @copyright Copyright (c) 2020 Flow communications
 */

namespace flowsa\honeypot;

use flowsa\honeypot\models\Settings;
use flowsa\honeypot\fields\HoneypotField;

use Craft;

use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;

use flowsa\guestentries\controllers\SaveController;
use flowsa\guestentries\events\SaveEvent;

use yii\base\Event;

/**
 * Class Plugin
 * 
 * @property  Settings $settings
 * @method    Settings getSettings()
 */
class Plugin extends \craft\base\Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Plugin::$plugin
     *
     * @var Plugin
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '1.0.0';

    /**
     * Set to `true` if the plugin should have a settings view in the control panel.
     *
     * @var bool
     */
    public $hasCpSettings = true;

    /**
     * Set to `true` if the plugin should have its own section (main nav item) in the control panel.
     *
     * @var bool
     */
    public $hasCpSection = false;

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * CraftRecaptchaV2::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Register our fields
        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = HoneypotField::class;
            }
        );

        if (!class_exists(SaveController::class)) {
            return;
        }

        

        Event::on(
            SaveController::class, 
            SaveController::EVENT_BEFORE_SAVE_ENTRY, 
            function(SaveEvent $e) {
            $settings = $this->getSettings();
            
            if (!$settings->honeypotParam) {
                Craft::warning('Couldn\'t check honeypot field because the "Honeypot Form Param" setting isn\'t set yet.');
                return;
            }
            
            $request = Craft::$app->getRequest();
            $fields = $request->getBodyParam('fields');
            $val = $request->getBodyParam($settings->honeypotParam);
            $siteKey = $request->getBodyParam($settings->siteKey);
            $isSpam = false;
            if (array_key_exists($settings->honeypotParam, $fields)) {
                $val = $fields[$settings->honeypotParam];
            }

            $siteKey = $fields[$settings->siteKey] ?? null;

            if($siteKey !== null) {
                $siteUrl = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
                $isSpam = (strpos($siteKey, $siteUrl) === FALSE);
            }

            if ($val === null) {
                Craft::warning('Couldn\'t check honeypot field because no POST parameter named "'.$settings->honeypotParam.'" exists.');
                return;
            }

            // All conditions are favorable
            if ($val !== '' || $isSpam) {
                $e->isSpam = true;
            }
        });
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
       
        // Get the settings that are being defined by the config file
        $overrides = Craft::$app->getConfig()->getConfigFromFile(strtolower($this->id));

        return Craft::$app->view->renderTemplate(
            'honeypot/settings',
            [
                'settings' => $this->getSettings(),
                'overrides' => array_keys($overrides)
            ]
        );
    }
}
