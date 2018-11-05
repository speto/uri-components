<?php

/**
 * League.Uri (http://uri.thephpleague.com/components).
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

namespace League\Uri\Component;

final class Fragment extends Component
{
    private const REGEXP_FRAGMENT_ENCODING = '/
        (?:[^A-Za-z0-9_\-\.~\!\$&\'\(\)\*\+,;\=%\:\/@\?]+|
        %(?![A-Fa-f0-9]{2}))
    /x';

    /**
     * @var string|null
     */
    private $fragment;

    /**
     * {@inheritdoc}
     */
    public static function __set_state(array $properties): self
    {
        return new self($properties['fragment']);
    }

    /**
     * New instance.
     *
     * @param null|mixed $fragment
     */
    public function __construct($fragment = null)
    {
        $this->fragment = $this->validateComponent($fragment);
    }

    /**
     * {@inheritdoc}
     */
    public function getContent(): ?string
    {
        return $this->encodeComponent($this->fragment, self::RFC3986_ENCODING, self::REGEXP_FRAGMENT_ENCODING);
    }

    /**
     * {@inheritdoc}
     */
    public function getUriComponent(): string
    {
        return (null === $this->fragment ? '' : '#').$this->getContent();
    }

    /**
     * Returns the decoded query.
     */
    public function decoded(): ?string
    {
        return $this->encodeComponent($this->fragment, self::NO_ENCODING, self::REGEXP_FRAGMENT_ENCODING);
    }

    /**
     * {@inheritdoc}
     */
    public function withContent($content): self
    {
        $content = $this->filterComponent($content);
        if ($content === $this->getContent()) {
            return $this;
        }

        return new self($content);
    }
}
