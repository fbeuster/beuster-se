<?php
class ArticleRecommender {

  public static function getRecommendationsFromArticleCategory($article_id, $category_id) {
    $db       = Database::getDB();
    $fields   = array('article_categories.article_id');
    $conds    = array('article_categories.article_id != ? AND article_categories.category_id = ? AND articles.public = 1', 'ii', array($article_id, $category_id));
    $join     = ' JOIN article_categories ON articles.id = article_categories.article_id';
    $options  = ' ORDER BY articles.hits DESC';
    $limit    = array('LIMIT ?', 'i', array(32));
    $result   = $db->select('articles', $fields, $conds, $options, $limit, $join);
    $results  = count($result);

    $recommendations = array();

    if ($results == 1) {
      $recommendations[] = new Article($result[0]['article_id']);

    } else if ($results > 1) {
      $keys = array_rand( $result, min(4, $results));

      foreach ($keys as $key) {
        $recommendations[] = new Article($result[$key]['article_id']);
      }
    }

    return $recommendations;
  }

  public static function getRandomRecommendationsFromArticle($article_id) {
    $db       = Database::getDB();
    $fields   = array('id');
    $conds    = array('id != ? AND public = 1', 'i', array($article_id));
    $options  = ' ORDER BY RAND()';
    $limit    = array('LIMIT ?', 'i', array(4));
    $result   = $db->select('articles', $fields, $conds, $options, $limit);

    $recommendations = array();

    foreach ($result as $row) {
      $recommendations[] = new Article($row['id']);
    }

    return $recommendations;
  }
}