<?php

namespace BucklesHusky\FontAwesomeIconPicker\Forms;

use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Flushable;
use SilverStripe\Forms\FormField;
use SilverStripe\Forms\TextField;

class FAPickerField extends TextField implements Flushable
{
    protected $schemaDataType = FormField::SCHEMA_DATA_TYPE_TEXT;

    protected $schemaComponent = 'FAPickerField';

    /**
     * Compiles all CSS-classes. Optionally includes a "form-group--no-label" class if no title was set on the
     * FormField.
     *
     * Uses {@link Message()} and {@link MessageType()} to add validation error classes which can
     * be used to style the contained tags.
     *
     * @return string
     */
    public function extraClass()
    {
        //add in text class
        $classes[] = parent::extraClass();

        $classes[] .= "text";

        return implode(' ', $classes);
    }

    /**
     * Determine if the iconpicker should use the pro version of fontawesome
     *
     * @return boolean
     */
    public function getIsProVersion()
    {
        if (Config::inst()->get('FontawesomeIcons', 'unlock_pro_mode')) {
            return true;
        }
        return false;
    }

    /**
     * Determine if sharp icons are disabled
     *
     * @return boolean
     */
    public function getIsSharpIconsDisabled()
    {
        if (Config::inst()->get('FontawesomeIcons', 'disable_sharp_icons')) {
            return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     * */
    public static function flush()
    {
        // Injector::inst()->get(CacheInterface::class . '.fontawesomeiconpicker')->clear();
    }

    /**
     * @inheritDoc
     *
     * */
    public function getAttributes()
    {
        $defaults = [
            'data' => [
                'pro' => $this->getIsProVersion(),
                'isSharpDisabled' => $this->getIsSharpIconsDisabled(),
                'taskLink' => Controller::join_links([
                    Director::absoluteBaseURL(),
                    'dev/tasks/generate-font-awesome',
                ]),
            ]
        ];

        $attributes = array(
            'class' => $this->extraClass(),
            'id' => $this->ID(),
            'name' => $this->getName(),
            'value' => $this->getValue(),
            'data-schema' => json_encode($defaults),//$this->getSchemaData()
            'data-state' => json_encode($this->getSchemaState()),
        );

        $attributes = array_merge($attributes, $this->attributes);

        return $attributes;
    }
}
