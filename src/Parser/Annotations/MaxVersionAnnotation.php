<?php
namespace Mill\Parser\Annotations;

use Mill\Exceptions\Annotations\AbsoluteVersionException;
use Mill\Parser\Annotation;
use Mill\Parser\Version;

/**
 * Handler for the `@api-maxversion` annotation.
 *
 */
class MaxVersionAnnotation extends Annotation
{
    const ARRAYABLE = [
        'maximum_version'
    ];

    /** @var string */
    protected $maximum_version;

    /**
     * {@inheritdoc}
     * @throws AbsoluteVersionException If an `@api-maxversion` annotation version is not absolute.
     */
    protected function parser(): array
    {
        /** @var string $method */
        $method = $this->method;

        $parsed = new Version($this->docblock, $this->class, $method);
        if ($parsed->isRange()) {
            throw AbsoluteVersionException::create('max', $this->docblock, $this->class, $method);
        }

        return [
            'maximum_version' => $parsed->getConstraint()
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function interpreter(): void
    {
        // The Version class already does all of our validation, so if we're at this point, we have a good version and
        // don't need to run it through `$this->required()` again.
        $this->maximum_version = $this->parsed_data['maximum_version'];
    }

    /**
     * {@inheritdoc}
     */
    public static function hydrate(array $data = [], Version $version = null): self
    {
        /** @var MaxVersionAnnotation $annotation */
        $annotation = parent::hydrate($data, $version);
        $annotation->setMaximumVersion($data['maximum_version']);

        return $annotation;
    }

    /**
     * @return string
     */
    public function getMaximumVersion(): string
    {
        return $this->maximum_version;
    }

    /**
     * @param string $maximum_version
     * @return self
     */
    public function setMaximumVersion(string $maximum_version): self
    {
        $this->maximum_version = $maximum_version;
        return $this;
    }
}
