<?php

class CategoryPage extends Page {
  const DEFAULT_PAGE_LENGTH = 8;

  private $articles;
  private $author;
  private $config;
  private $content;
  private $destination = '';
  private $file_name = 'category.php';
  private $refresh = '';
  private $title;
  private $type = Page::CATEGORY_PAGE;

  public function __construct($category = null, $author = null) {
    $this->config = Config::getConfig();

    if ($category === null) {
      $this->category = null;

    } else {
      $this->category = Category::newFromName($category);
    }

    if ($author === null) {
      $this->author = null;

    } else {
      $this->author = User::newFromName($author);
    }

    $this->loadContent();
  }

  public function getArticles() {
    return $this->articles;
  }

  private function getAuthorConditions() {
    $cat_sql    = ' news.Autor = ?';
    $cat_params = 'i';
    $cat_vars   = array( $this->author->getId() );

    return array($cat_sql, $cat_params, $cat_vars);
  }

  public function getCategory() {
    return $this->category;
  }

  private function getCategoryConditions() {
    if ($this->category->isTopCategory()) {
      $cat_sql    = ' AND (newscat.ParentID = ? OR newscat.ID = ?)';
      $cat_params = 'ii';
      $cat_vars   = array(  $this->category->getId(),
                            $this->category->getId() );

    } else {
      $cat_sql    = ' AND newscatcross.Cat = ?';
      $cat_params = 'i';
      $cat_vars   = array($this->category->getId());
    }

    return array($cat_sql, $cat_params, $cat_vars);
  }

  private function getCategoryJoins() {
    $joins = 'JOIN newscatcross ON news.ID = newscatcross.NewsID';

    if ($this->category->isTopCategory()) {
      $joins .= ' JOIN newscat ON newscat.ID = newscatcross.Cat';
    }

    return $joins;
  }

  public function getContent() {
    return $this->content;
  }

  private function getDateSQL() {
    if (!isset($_GET['y'])) {
      return "news.Datum < NOW()";

    } else {
      $year = (int) $_GET['y'];

      if ($year > (int) date("Y")) {
        return "news.Datum < NOW()";

      } else {
        $lb       = Lixter::getLix()->getLinkBuilder();
        $year_sql = "YEAR(news.Datum) = " . $year;

        if (!isset($_GET['m'])) {
          $this->destination = $lb->makeArchiveYearLink($year);
          return $year_sql . " AND news.Datum < NOW()";

        } else {
          $month = (int) $_GET['m'];

          if ($month > 12 || $month < 1) {
            return "news.Datum < NOW()";

          } else {
            $this->destination = $lb->makeArchiveMonthLink($year,$month);
            $month_sql = " AND MONTH(news.Datum) = " . $month;
            return $year_sql . $month_sql . " AND news.Datum < NOW()";
          }
        }
      }
    }
  }

  private function getDateConditions() {
    if(Utilities::isDevServer()) {
      return array( $this->getDateSQL(), '', array() );

    } else {
      return array( $this->getDateSQL().' AND news.enable = ?',
                      'i', array(true));
    }
  }

  public function getDestination() {
    return $this->destination;
  }

  public function getFileName() {
    return $this->file_name;
  }

  private function getOffsetPages() {
    $start_page   = $this->getStartPage();
    $total_pages  = $this->getTotalPagesCount();

    if ($total_pages == 1) {
      return 0;
    }

    if ($start_page < 1) {
      $start_page = 1;
    }

    if ($start_page > $total_pages) {
      $start_page = $total_pages;
    }

    return ($start_page - 1) * $this->getPageLength();
  }

  public function getPageLength() {
    $length = $this->config->get('site', 'category_page_length');

    if ($length == null || !(is_int($length) || ctype_digit($length))) {
      return self::DEFAULT_PAGE_LENGTH;
    }

    return $this->config->get('site', 'category_page_length');
  }

  public function getParsedContent() {
    return $this->content;
  }

  public function getStartPage() {
    if (isset($_GET['page'])) {
      return (int) $_GET['page'];

    } else {
      return 1;
    }
  }

  public function getTags() {
    if ($this->category == null) {
      return 'Blog';
    }

    return $this->category->getName();
  }

  private function getTotalArticleCount() {
    $db     = Database::getDB();
    $conds  = $this->getDateConditions();

    if ($this->category === null) {
      $joins = null;

      if ($this->author != null) {
        $conds  = $this->getAuthorConditions();
      }

    } else {
      $joins      = $this->getCategoryJoins();
      $cat_conds  = $this->getCategoryConditions();

      $conds[0] = $conds[0] . $cat_conds[0];
      $conds[1] = $conds[1] . $cat_conds[1];
      $conds[2] = array_merge($conds[2], $cat_conds[2]);
    }

    $fields = array('COUNT(*) AS total');
    $res    = $db->select('news', $fields, $conds, null, null, $joins);

    if (count($res) == 1) {
      return $res[0]['total'];

    } else {
      return 0;
    }
  }

  public function getTotalPagesCount() {
    $total_articles = $this->getTotalArticleCount();

    if ($total_articles == 0) {
      return 1;
    }

    $total_pages = ceil($total_articles / $this->getPageLength());

    if (!$total_pages) {
      $total_pages = 1;
    }

    return $total_pages;
  }

  public function getTitle() {
    return $this->title;
  }

  public function getType() {
    return $this->type;
  }

  public function hasArticles() {
    return count($this->articles) > 0;
  }

  private function loadContent() {
    $db     = Database::getDB();
    $conds  = $this->getDateConditions();

    if ($this->category == null) {
      $joins = null;
      $this->title = $this->config->get('site', 'title');

      if ($this->author != null) {
        $conds  = $this->getAuthorConditions();

        # TODO
        # author name needs to be url safe
        $this->destination  = $this->author->getName();
        $this->title        = $this->author->getClearname();
      }

    } else {
      $joins      = $this->getCategoryJoins();
      $cat_conds  = $this->getCategoryConditions();

      $conds[0] = $conds[0] . $cat_conds[0];
      $conds[1] = $conds[1] . $cat_conds[1];
      $conds[2] = array_merge($conds[2], $cat_conds[2]);

      $this->destination  = $this->category->getLink();
      $this->title        = $this->category->getName();
    }

    $fields = array('news.ID');
    $opt    = 'GROUP BY news.ID ORDER BY news.Datum DESC';
    $limit  = array('LIMIT ?, ?', 'ii',
                    array($this->getOffsetPages(), $this->getPageLength()));
    $res    = $db->select('news', $fields, $conds, $opt, $limit, $joins);

    if ($res) {
      foreach ($res as $aId) {
        $this->articles[] = new Article($aId['ID']);
      }
    }
  }
}

?>
