<?php
/**
 * League.Uri (http://uri.thephpleague.com)
 *
 * @package    League\Uri
 * @subpackage League\Uri\Components
 * @author     Ignace Nyamagana Butera <nyamsprod@gmail.com>
 * @copyright  2016 Ignace Nyamagana Butera
 * @license    https://github.com/thephpleague/uri-components/blob/master/LICENSE (MIT License)
 * @version    1.0.0
 * @link       https://github.com/thephpleague/uri-components
 */
namespace League\Uri\Components;

use League\Uri\Components\Traits\ImmutableComponent;

/**
 * An abstract class to ease component manipulation
 *
 * @package    League\Uri
 * @subpackage League\Uri\Components
 * @author     Ignace Nyamagana Butera <nyamsprod@gmail.com>
 * @since      1.0.0
 */
abstract class Component
{
    use ImmutableComponent;

    /**
     * The component data
     *
     * @var int|string
     */
    protected $data;

    /**
     * @inheritdoc
     */
    public static function __set_state(array $properties)
    {
        return new static($properties['data']);
    }

    /**
     * new instance
     *
     * @param string|null $data the component value
     */
    public function __construct($data = null)
    {
        $this->data = $this->validate($data);
    }

    /**
     * Validate the component string
     *
     * @param mixed $data
     *
     * @throws InvalidArgumentException if the component is no valid
     *
     * @return mixed
     */
    protected function validate($data)
    {
        if (null === $data) {
            return $data;
        }

        return $this->decodeComponent($this->validateString($data));
    }

    /**
     * The component raw data
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->data;
    }

    /**
     * Returns whether or not the component is defined.
     *
     * @return bool
     */
    public function isDefined()
    {
        return null === $this->getContent();
    }

    /**
     * Returns the instance string representation; If the
     * instance is not defined an empty string is returned
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getContent();
    }

    /**
     * Returns the instance string representation
     * with its optional URI delimiters
     *
     * @return string
     */
    public function getUriComponent()
    {
        return $this->__toString();
    }

    /**
     * Returns an instance with the specified string
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the modified data
     *
     * @param string|null $value
     *
     * @return static
     */
    public function withContent($value)
    {
        if ($value === $this->getContent()) {
            return $this;
        }

        return new static($value);
    }
}