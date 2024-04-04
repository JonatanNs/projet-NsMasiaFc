<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use Twig\Extra\Intl\IntlExtension; // Importez la classe IntlExtension

abstract class AbstractController
{
    private Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader('templates');
        $twig = new Environment($loader, [
            'debug' => true,
        ]);

        // Configurez le fuseau horaire pour Twig
        $twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Paris');

        //$twig->addGlobal('session', $_SESSION['csrf-token']);
        $twig->addExtension(new DebugExtension());

        // Ajoutez l'extension IntlExtension à l'environnement Twig
        $twig->addExtension(new IntlExtension());

        $this->twig = $twig;
    }

    protected function render(string $template, array $data): void
    {
        echo $this->twig->render($template, $data);
    }
}

