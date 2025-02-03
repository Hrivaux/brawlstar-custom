<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FlashMessageNormalizer
{
    private ?SessionInterface $session;

    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }

    public function normalize(): void
    {
        // Vérifier que la session est bien initialisée et que le flashBag est disponible
        if (!$this->session || !$this->session->isStarted()) {
            return;
        }

        $flashBag = $this->session->getFlashBag();
        $messages = $flashBag->all();

        $mapping = [
            'error' => 'danger', // Convertit automatiquement "error" en "danger"
        ];

        foreach ($messages as $type => $msgs) {
            if (isset($mapping[$type])) {
                foreach ($msgs as $msg) {
                    $flashBag->add($mapping[$type], $msg);
                }
                $flashBag->set($type, []); // Supprime les anciens messages "error"
            }
        }
    }
}
