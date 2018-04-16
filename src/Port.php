<?php
/**
 * League.Uri (http://uri.thephpleague.com).
 *
 * @package    League\Uri
 * @subpackage League\Uri\Components
 * @author     Ignace Nyamagana Butera <nyamsprod@gmail.com>
 * @license    https://github.com/thephpleague/uri-components/blob/master/LICENSE (MIT License)
 * @version    2.0.0
 * @link       https://github.com/thephpleague/uri-components
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace League\Uri\Components;

/**
 * Value object representing a URI Port component.
 *
 * Instances of this interface are considered immutable; all methods that
 * might change state MUST be implemented such that they retain the internal
 * state of the current instance and return an instance that contains the
 * changed state.
 *
 * @package    League\Uri
 * @subpackage League\Uri\Components
 * @author     Ignace Nyamagana Butera <nyamsprod@gmail.com>
 * @since      1.0.0
 * @see        https://tools.ietf.org/html/rfc3986#section-3.2.3
 */
final class Port extends AbstractComponent
{
    /**
     * @var int|null
     */
    private $port;

    /**
     * {@inheritdoc}
     */
    public static function __set_state(array $properties): self
    {
        return new self($properties['port']);
    }

    /**
     * New instance.
     *
     * @param mixed $port
     */
    public function __construct($port = null)
    {
        $this->port = $this->validate($port);
    }

    /**
     * Validate a port.
     *
     * @param mixed $port
     *
     * @throws Exception if the port is invalid
     *
     * @return null|int
     */
    protected function validate($port)
    {
        $port = $this->filterComponent($port);
        if (null === $port) {
            return null;
        }

        if (false !== ($fport = filter_var($port, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]))) {
            return $fport;
        }

        throw new Exception(sprintf('Expected port to be a positive integer or 0; received %s', $port));
    }

    /**
     * {@inheritdoc}
     */
    public function getContent(int $enc_type = self::RFC3986_ENCODING)
    {
        $this->filterEncoding($enc_type);

        return $this->port;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return (string) $this->port;
    }

    /**
     * {@inheritdoc}
     */
    public function getUriComponent(): string
    {
        if (null === $this->port) {
            return '';
        }

        return ':'.$this->port;
    }

    /**
     * {@inheritdoc}
     */
    public function __debugInfo()
    {
        return ['component' => $this->port];
    }

    /**
     * {@inheritdoc}
     */
    public function withContent($content)
    {
        $content = $this->validate($content);
        if ($content === $this->port) {
            return $this;
        }

        $clone = clone $this;
        $clone->port = $content;

        return $clone;
    }
}
