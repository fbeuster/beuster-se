<?php

class CategoryPage extends Page {
  const PAGE_LENGTH = 8;

  private $articles;
  private $content;
  private $destination = '';
  private $file_name = 'category.php';
  private $refresh = '';
  private $title;
  private $type = Page::CATEGORY_PAGE;

  public function __construct($category = null) {
    $this->category = $category;
    $this->loadContent();
  }

  public function getArticles() {
    return $this->articles;
  }

  public function getContent() {
    return $this->content;
  }

  private function getDateSQL() {
    if (!isset($_GET['y'])) {
      return "Datum < NOW()";

    } else {
      $year = (int) $_GET['y'];

      if ($year > (int) date("Y")) {
        return "Datum < NOW()";

      } else {
        $year_sql = "YEAR(Datum) = " . $year;

        if (!isset($_GET['m'])) {
          $this->destination = $year;
          return $year_sql . " AND Datum < NOW()";

        } else {
          $month = (int) $_GET['m'];

          if ($month > 12 || $month < 1) {
            return "Datum < NOW()";

          } else {
            $this->destination = $year . '/' . $month;
            $month_sql = " AND MONTH(Datum) = " . $month;
            return $year_sql . $month_sql . " AND Datum < NOW()";
          }
        }
      }
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

    return ($start_page - 1) * self::PAGE_LENGTH;
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
    # TODO
    if (is_string($this->content))                return '';
    if (!isset($this->content['articles']))       return '';
    if (count($this->content['articles']) !== 1)  return '';

    return $this->content['articles'][0]->getTagsString();
  }

  private function getTotalArticleCount() {
    $db = Database::getDB();

    $fields = array('COUNT(*) AS total');
    $res    = $db->select('news', $fields);

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

    $total_pages = ceil($total_articles / self::PAGE_LENGTH);

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
    $config = Config::getConfig();
    $db     = Database::getDB();

    if ($this->category == null) {

      if(Utilities::isDevServer()) {
        $conds = $this->getDateSQL();

      } else {
        $conds = array($this->getDateSQL().' AND enable = ?', 'i',
                        array(true));
      }

      $fields = array('ID');
      $opt    = 'GROUP BY ID ORDER BY Datum DESC';
      $limit  = array('LIMIT ?, ?', 'ii', array($this->getOffsetPages(), self::PAGE_LENGTH));
      $res = $db->select('news', $fields, $conds, $opt, $limit);

      if ($res) {
        foreach ($res as $aId) {
          $this->articles[] = new Article($aId['ID']);
        }
      }

      $this->title = $config->get('site_title');
    }
  }
}

?>