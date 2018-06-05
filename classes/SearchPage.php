<?php

class SearchPage extends Page {

  const DEFAULT_PAGE_LENGTH     = 8;
  const MIN_SEARCH_TERM_LENGTH  = 3;

  private $content;
  private $has_results;
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

    $this->search_term = htmlentities($this->search_term);
    $this->validate();
  }

  private function buildSearchResults() {
    $this->search_results = array();

    foreach ($this->articles as $article) {
      $this->has_results       = true;
      $this->search_results[] = new SearchResult($article['id'], $this->search_term);
    }
  }

  private function getArticles() {
    $db     = Database::getDB();
    $fields = array('id');
    $conds  = array('MATCH (title, content) AGAINST (?)',
                    's', array($this->search_term));
    $res    = $db->select('articles', $fields, $conds);

    $this->articles = array();

    if (count($res)) {

      foreach ($res as $match) {
        $this->articles[] = array('id' => $match['id']);
      }
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

  public function getLink() {
    $lb = Lixter::getLix()->getLinkBuilder();
    return $lb->makeSearchLink($this->search_term);
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

  public function isValid() {
    return $this->valid;
  }

  public function search() {
    # Future ideas:
    # - include comments in search
    # - include tags in search

    $this->has_results = false;

    $this->getArticles();
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
