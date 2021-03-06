<?php
namespace Mill\Parser\Annotations;

use Mill\Parser\Annotation;

class LabelAnnotation extends Annotation
{
    const ARRAYABLE = [
        'label'
    ];

    /** @var string */
    protected $label;

    /**
     * {@inheritdoc}
     */
    protected function parser(): array
    {
        return [
            'label' => $this->docblock
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function interpreter(): void
    {
        $this->label = $this->required('label');
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return LabelAnnotation
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }
}
