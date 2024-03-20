<?php

use utils\DB;
use utils\CommentManager;
use class\News;

require_once(ROOT . '/utils/DB.php');
require_once(ROOT . '/utils/CommentManager.php');
require_once(ROOT . '/class/News.php');

class NewsManager
{
    private static $instance = null;

    private function __construct()
    {
        // No need to require_once when using 'use' statement
    }

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
    * List all news
    */
    public function listNews()
    {
        $db = DB::getInstance();
        $rows = $db->query('SELECT * FROM `news`');

        $news = [];
        foreach ($rows as $row) {
            $newsItem = new News();
            $newsItem->setId($row['id'])
                ->setTitle($row['title'])
                ->setBody($row['body'])
                ->setCreatedAt($row['created_at']);
            $news[] = $newsItem;
        }

        return $news;
    }

    /**
    * Add a record in news table
    */
    public function addNews($title, $body)
    {
        $db = DB::getInstance();
        $sql = "INSERT INTO `news` (`title`, `body`, `created_at`) VALUES (?, ?, ?)";
        $params = [$title, $body, date('Y-m-d')];

        $db->execute($sql, $params);
        return $db->lastInsertId();
    }

    /**
    * Deletes a news, and also linked comments
    */
    public function deleteNews($id)
    {
        $commentManager = CommentManager::getInstance();
        $comments = $commentManager->listComments();
        $idsToDelete = [];

        foreach ($comments as $comment) {
            if ($comment->getNewsId() == $id) {
                $idsToDelete[] = $comment->getId();
            }
        }

        foreach ($idsToDelete as $idToDelete) {
            $commentManager->deleteComment($idToDelete);
        }

        $db = DB::getInstance();
        $sql = "DELETE FROM `news` WHERE `id` = ?";
        $params = [$id];

        return $db->execute($sql, $params);
    }
}

/**
 *Comments
 *Used the use statement to import DB, CommentManager, and News classes into the namespace, removing the need for require_once statements.
 *Changed the addNews() method to use prepared statements and the execute() method instead of directly concatenating values into the SQL query string.
 *Updated the deleteNews() method to use prepared statements and the execute() method instead of directly concatenating values into the SQL query string.
 *Refactored the deletion of linked comments to use the CommentManager instance directly, improving readability and separation of concerns.
 *Renamed variable $n to $newsItem for clarity in the listNews() method.
 *Updated comments for better readability.
 */