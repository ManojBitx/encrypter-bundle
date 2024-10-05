<?php

namespace ManojX\EncrypterBundle;

use ManojX\EncrypterBundle\DependencyInjection\EncrypterExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EncrypterBundle extends Bundle
{

    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new EncrypterExtension();
        }

        return $this->extension;
    }

}
