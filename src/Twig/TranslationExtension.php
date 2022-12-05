<?php

namespace App\Twig;

use App\Model\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TranslationExtension extends AbstractExtension
{
    private string $locale;

    public function __construct(TranslatorInterface $translator)
    {
        $this->locale = $translator->getLocale();
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('label_translate', [$this, 'labelTranslate']),
        ];
    }

    public function labelTranslate($object): ?string
    {
        if (!$object instanceof TranslatableInterface) {
            return $object->__toString();
        }

        return 'en' === $this->locale ? $object->getLabelEn() : $object->getLabelFr();
    }
}
