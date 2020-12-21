<?php


namespace App\Router;


use App\Controller\{
    ArticleController,
    FeedController
};

/**
 * Class Router
 * @package App\Router
 */
class Router
{
    /**
     * @var array
     */
    private $getParams;

    /**
     * @var array
     */
    private $postParams;

    /**
     * Router constructor.
     * @param array $getParams
     * @param array $postParams
     */
    public function __construct(array $getParams, array $postParams)
    {
        $this->getParams = $getParams;
        $this->postParams = $postParams;
    }

    public function route()
    {
        $target = array_key_exists('target', $this->getParams) ? $this->getParams['target'] : null;
        $action = array_key_exists('action', $this->getParams) ? $this->getParams['action'] : null;
        if ($target === 'feed') {
            $ctrl = new FeedController();
            switch ($action) {
                case 'url':
                    $ctrl->feedWithArticles($this->postParams['url']);
                    break;
                case 'all':
                    $ctrl->all();
                    break;
                case 'one':
                    $ctrl->one($this->postParams['id']);
                    break;
                case 'add':
                    $ctrl->add($this->postParams['url']);
                    break;
                case 'upd':
                    echo 'coucou4';
                    break;
                case 'del':
                    echo 'coucou5';
                    break;
                default:
                    echo 'Pas de paramètre "action" depuis "feed"';
            }
        } elseif ($target === 'article') {
            $ctrl = new ArticleController();
            switch ($action) {
                case 'all':
                    echo 'salut';
                    break;
                case 'one':
                    echo 'salut2';
                    break;
                case 'add':
                    echo 'salut3';
                    break;
                case 'upd':
                    echo 'salut4';
                    break;
                case 'del':
                    echo 'salut5';
                    break;
                default:
                    echo 'Pas de paramètre "action" depuis "article"';
            }
        } else {
            $ctrl = new FeedController();
            $ctrl->index();
        }
    }

}
