<?php


namespace App\Controller;


use App\{
    Manager\ArticleManager,
    Manager\FeedManager,
    Model\Article,
    Model\Feed,
    Util\ErrorManager
};
use Exception;

/**
 * Class FeedController
 * @package Controller
 */
class FeedController extends RssController
{
    /**
     * @var FeedManager
     */
    private $feedManager;

    /**
     * @var ArticleManager
     */
    private $articleManager;

    /**
     * FeedController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->feedManager = new FeedManager();
        $this->articleManager = new ArticleManager();
    }

    // ToDo : Actualiser les différents flux

    /**
     * Affiche la page d'accueil
     */
    public function index()
    {
        $this->render(ROOT_DIR . 'view/index.php', compact([]));
    }

    /**
     * @param $url
     * @throws Exception
     */
    public function feedWithArticles($url)
    {
        $feed = null;
        $articles = [];
        try {
            $rss = simplexml_load_file($url);
            if ($rss) {
                $rss = $rss->channel;
                $feed = (new Feed())->createFeed($rss);
                foreach ($rss->item as $item) {
                    $article = (new Article())->createArticleFromFeed($item);
                    $articles[] = $article;
                }
            } else {
                throw new Exception("Le flux demandé n'a pas été trouvé");
            }
        } catch (Exception $e) {
            ErrorManager::add($e->getMessage());
        } finally {
            $this->render(ROOT_DIR . 'view/index.php', compact('feed', 'articles'));
        }
    }

        /**
     * Affiche tous les flux
     * @throws Exception
     */
    public function all()
    {
        $feeds = $this->feedManager->all();
        $this->render(ROOT_DIR . 'view/feeds.php', compact('feeds'));
    }

    /**
     * Affiche le flux dont l'id est passé en paramètre (sans les articles)
     * @param int $id
     * @throws Exception
     */
    public function one($id)
    {
        $feed = $this->feedManager->one($id);
        $this->render(ROOT_DIR . 'view/feed.php', compact('feed'));
    }

    /**
     * Affiche le flux dont l'id est passé en paramètre (avec les articles)
     * @param int $id
     * @throws Exception
     */
    public function oneWithArticles(int $id)
    {
        $feed = $this->feedManager->one($id);
        $articles = $this->feedManager->articlesFromFeed($id);
        $this->render(ROOT_DIR . 'view/index.php', compact('feed', 'articles'));
    }

    /**
     * Crée et ajoute un flux (sans ses articles) avec les paramètres reçus
     * @param array $params Tableau associatif dont les clefs et valeurs
     * correspondent respectivement aux champs "name" et "value" du formulaire
     */
    public function add(array $params): void
    {
        try {
            $rss = simplexml_load_file($params['url'])->channel;
            $feed = (new Feed())->createFeed($rss);
            $this->feedManager->insert($feed);
            $this->all();
        } catch (Exception $e) {
            ErrorManager::add($e->getMessage());
        }
    }

    /**
     * Modifie un flux (sans ses articles) avec les paramètres reçus (sans ses articles)
     * @param array $params Tableau associatif dont les clefs et valeurs
     * correspondent respectivement aux champs "name" et "value" du formulaire
     */
    public function modify(array $params): void
    {
        try {
            $feed = new Feed();
            $feed->setId($params['id']);
            $feed->setWebsite($params['website']);
            $feed->setDescription($params['description']);
            $feed->setUrl($params['url']);
            $feed->setLastBuildDate($params['lastBuildDate']);
            $feed->setPictureUrl($params['pictureUrl']);
            $this->feedManager->update($feed);
            $this->all();
        } catch (Exception $e) {
            ErrorManager::add($e->getMessage());
        }
    }

    /**
     * Supprime le flux (sans ses articles) dont l'id est passé en paramètre
     * @param int $id
     * @throws Exception
     */
    public function delete(int $id): void
    {
        try {
            $this->feedManager->delete($id);
            $this->all();
        } catch (Exception $e) {
            ErrorManager::add($e->getMessage());
        }
    }

}
