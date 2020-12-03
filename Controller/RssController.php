<?php


namespace Controller;


use PDO;

/**
 * Classe mère de tous les contrôleurs
 * @package Controller
 */
class RssController
{
    /**
     * @var PDO
     */
    private $db;

    /**
     * @var string
     */
    private $template;

    /**
     * Controller constructor.
     * @param PDO $db
     * @param string $template
     */
    public function __construct($db, $template = ROOT_DIR . 'view/template.php')
    {
        $this->db = $db;
        $this->template = $template;
    }

    /**
     * Affiche la page d'accueil
     */
    public function index()
    {
        $this->render(ROOT_DIR . 'view/index.php', compact([]));
    }

    /**
     * Envoie les paramètres aux vues
     * @param string $view Chemin de la vue
     * @param array $params Paramètres passés à la vue sous la forme "clef => valeur"
     * où les clefs sont ajoutées à la table des symbôles
     */
    public function render(string $view, array $params): void
    {
        extract($params);
        ob_start();
        require_once $view;
        $section = ob_get_clean();
        require_once $this->template;
    }

    /**
     * @param string $lien
     * @param string $titre
     * @param string $linkClass
     * @return string
     */
    public static function nav_item(string $lien, string $titre, string $linkClass = ''): string
    {
        $classe = 'nav-item';
        if ($_SERVER['SCRIPT_NAME'] === $lien) {
            $classe .= ' active';
        }
        return '<li class="' . $classe . '">
                <a class="' . $linkClass . '" href="' . $lien . '">' . $titre . '</a>
            </li>';
    }

    /**
     * @param string $linkClass
     * @return string
     */
    public static function nav_menu(string $linkClass = ''): string
    {
        return
            self::nav_item(ROOT_DIR . 'index.php', 'Accueil', $linkClass) .
            self::nav_item(ROOT_DIR . 'index.php?target=article', 'Articles', $linkClass);
    }

}