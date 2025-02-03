<?php

namespace App;

use App\EventListener\FlashMessageNormalizer;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel implements HttpKernelInterface
{
    use MicroKernelTrait;

    private ?FlashMessageNormalizer $flashMessageNormalizer = null;

    // Ajout d'un setter pour injecter FlashMessageNormalizer
    public function setFlashMessageNormalizer(FlashMessageNormalizer $flashMessageNormalizer): void
    {
        $this->flashMessageNormalizer = $flashMessageNormalizer;
    }

    public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
    {
        // Vérifie si le normalisateur est bien injecté avant d'exécuter la normalisation
        if ($this->flashMessageNormalizer !== null) {
            $this->flashMessageNormalizer->normalize();
        }

        return parent::handle($request, $type, $catch);
    }
}
