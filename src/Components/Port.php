<?php
/**
 * League.Url (http://url.thephpleague.com)
 *
 * @link      https://github.com/thephpleague/uri/
 * @copyright Copyright (c) 2013-2015 Ignace Nyamagana Butera
 * @license   https://github.com/thephpleague/uri/blob/master/LICENSE (MIT License)
 * @version   4.0.0
 * @package   League.uri
 */
namespace League\Uri\Components;

use InvalidArgumentException;
use League\Uri\Interfaces;

/**
 * Value object representing a URI port component.
 *
 * @package League.uri
 * @since   1.0.0
 */
class Port extends AbstractComponent implements Interfaces\Components\Port
{
    /**
     * New Instance
     *
     * @param int $data
     */
    public function __construct($data = null)
    {
        $this->data = $this->validate($data);
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($data)
    {
        if (is_null($data)) {
            return $data;
        }

        $res = filter_var($data, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 65535]]);
        if (false === $res || is_bool($data)) {
            throw new InvalidArgumentException('The submitted port is invalid');
        }

        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function getUriComponent()
    {
        $data = $this->__toString();
        if (empty($data)) {
            return $data;
        }

        return ':'.$data;
    }

    /**
     * {@inheritdoc}
     */
    public function toInt()
    {
        return $this->data;
    }
}