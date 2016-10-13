<?php

class SearchPage extends Page {

  const DEFAULT_PAGE_LENGTH     = 8;
  const MIN_SEARCH_TERM_LENGTH  = 3;

  private $content;
  private $has_results;
  private $max_uptime;
  private $search_results;
  private $search_term;
  private $type;
  private $valid;

  public function __construct() {
    $this->type = Page::SEARCH_PAGE;

    if (isset($_POST['s'])) {
      $this->search_term = trim($_POST['s']);
    }

    if (isset($_GET['s'])) {
      $this->search_term = trim($_GET['s']);
    }

    $this->validate();
  }

  private function buildSearchResults() {
    $this->search_results = array();

    foreach ($this->articles as $article) {
      $this->has_results       = true;
      $this->search_results[] = new SearchResult($article['ID'], $this->search_term);
    }
  }

  private function calculateScores() {
    foreach ($this->articles as &$article) {
      $title_score    = $this->calculateTitleScore(   $article['Titel']);
      $content_score  = $this->calculateContentScore( $article['Inhalt']);

      if ($this->max_uptime == 0) {
        $time_factor = 1;

      } else {
        $time_factor    = $article['uptime'] / $this->max_uptime;
      }

      $db     = Database::getDB();
      $fields = array('Inhalt');
      $conds  = array('NewsID = ?', 'i', array($article['ID']));
      $res    = $db->select('kommentare', $fields, $conds);

      $popularity_score = 0;

      if (count($res) > 0) {
        $comments_score = 0;
        foreach ($res as $comment) {
          $comments_score += $this->calculateContentScore($comment['Inhalt']);
        }
        $popularity_score = count($res) + $comments_score;
      }

      $hit_factor       = 100 - $article['Hits'] * 100 / $article['uptime'];

      $rank = $title_score + $content_score;
      if($rank > 0) {
        $rank = ($rank + $popularity_score) * $hit_factor;
        $article['score'] = $rank * $time_factor;
      }
    }

    $this->articles = array_filter($this->articles, array('SearchPage', 'hasScore'));
  }


  private function calculateContentScore($content) {
    return $this->calculateMatchesScore($content, 3, 10, 15);
  }

  private function calculateMatchesScore($content, $similar_weight, $lower_weight, $exact_weight) {
    $similar_matches  = $this->countMatches($content);
    $lower_matches    = substr_count( mb_strtolower($content, 'UTF-8'),
                                      mb_strtolower($this->search_term, 'UTF-8'));
    $exact_matches    = substr_count( $content,
                                      $this->search_term);
    $similar_score    = ($similar_matches - $lower_matches) * $similar_weight;
    $lower_score      = ($lower_matches - $exact_matches) * $lower_weight;
    $exact_score      = $exact_matches * $exact_weight;

    return $similar_score + $lower_score + $exact_score;
  }

  private function calculateTitleScore($content) {
    return $this->calculateMatchesScore($content, 5, 10, 25);
  }

  private function countMatches($haystack) {
    $length   = mb_strlen($this->search_term);
    $haystack = preg_replace('#[\.,:]#', ' ', $haystack);
    $haystack = explode(' ', $haystack);

    foreach ($haystack as $key => $straw) {
      # unset empty strings and strings with to big diff
      if (trim($straw) === ''
        || preg_match('#\[(.*)\]#', $straw)
        || mb_strlen($straw) < self::MIN_SEARCH_TERM_LENGTH
        || abs(mb_strlen($straw) - $length) > 5) {
        unset($haystack[$key]);
        continue;
      }

      # unset strings with a too big distance
      if (Utilities::levenshtein($straw, $this->search_term) > 2) {
        unset($haystack[$key]);
        continue;
      }
    }

    # clean empty fields
    $haystack = array_merge($haystack);

    return count($haystack);
  }

  private function getArticles() {
    $db     = Database::getDB();
    $fields = array('ID', 'Titel', 'Inhalt', 'UNIX_TIMESTAMP(Datum) AS uptime, Hits');
    $conds  = array('enable = ? AND Datum < NOW()', 'i', array(1));
    $res    = $db->select('news', $fields, $conds);

    $this->articles = $res;

    foreach ($this->articles as &$article) {
      $article['score'] = 0;
    }
  }

  public function getContent() {
    return $this->content;
  }

  public function getCurrentPage() {
    if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
      return $_GET['page'];
    }

    return 1;
  }

  public function getError() {
    return $this->error;
  }

  public function getFilename() {
    return 'search.php';
  }

  private function getMaxArticleUptime() {
    $db     = Database::getDB();
    $fields = array('MIN(UNIX_TIMESTAMP(Datum)) AS max_uptime');
    $conds  = 'DATUM < NOW()';
    $res    = $db->select('news', $fields, $conds);

    if (count($res) > 1) {
      $this->max_uptime = $res[0]['max_uptime'];

    } else {
      $this->max_uptime = 0;
    }
  }

  public function getPagedSearchResults() {
    return array_slice($this->search_results, $this->getResultsSart() - 1, self::DEFAULT_PAGE_LENGTH);
  }

  public function getParsedContent() {
    return $this->content;
  }

  public function getResultsEnd() {
    if ($this->getCurrentPage() * self::DEFAULT_PAGE_LENGTH > count($this->search_results)) {
      return count($this->search_results);
    }
    return $this->getCurrentPage() * self::DEFAULT_PAGE_LENGTH;
  }

  public function getResultsSart() {
    return ($this->getCurrentPage() - 1) * self::DEFAULT_PAGE_LENGTH + 1;
  }

  public function getSearchInfo() {
    $info = '';
    $info .= '<section class="search_info">';
    $info .= '<p>';
    $info .= I18n::t( 'search.info.looked_for',
                      array('<b>' . $this->search_term . '</b>'));
    $info .= '</p>';

    if (count($this->search_results) > 0) {
      $info .= '<p>';
      $info .= I18n::t('search.info.results', array($this->getResultsSart(),
                                                    $this->getResultsEnd(),
                                                    count($this->search_results)));
      $info .= '</p>';

    } else {
      $info .= '<p>'.I18n::t('search.info.no_results').'</p>';
    }
    $info .= '</section>';
    return $info;
  }

  public function getSearchResults() {
    return $this->search_results;
  }

  public function getSearchTerm() {
    return $this->search_term;
  }

  public function getTitle() {
    return I18n::t('search.title', array($this->search_term));
  }

  public function getTotalPages() {
    return ceil( count($this->search_results) / self::DEFAULT_PAGE_LENGTH );
  }

  public function getType() {
    return $this->type;
  }

  public function hasResults() {
    return $this->has_results;
  }

  private static function hasScore($article) {
    return $article['score'] > 0;
  }

  public function isValid() {
    return $this->valid;
  }

  public function search() {
    $this->has_results = false;

    $this->getArticles();
    $this->getMaxArticleUptime();
    $this->calculateScores();

    usort($this->articles, array('Sorting', 'searchResultScoreDesc'));

    $this->buildSearchResults();
  }

  private function validate() {
    if ($this->search_term === '') {
      $this->error = I18n::t('search.no_search_term');
      $this->valid = false;

    } else if (mb_strlen($this->search_term) < self::MIN_SEARCH_TERM_LENGTH) {
      $this->error = I18n::t('search.too_short');
      $this->valid = false;

    } else {
      $this->valid = true;
    }
  }
}

?>