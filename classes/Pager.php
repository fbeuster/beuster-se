<?php

class Pager {

  const FILLER_TEXT = '...';
  const VISIBLE_PAGES = 5;

  private $current_page;
  private $destination;
  private $make_full_list;
  private $next_text = null;
  private $prev_text = null;
  private $total_pages;

  public function __construct($full_list, $total_pages, $current_page, $destination) {
    $this->current_page   = $current_page;
    $this->destination    = $destination;
    $this->make_full_list = $full_list;
    $this->total_pages    = $total_pages;
  }

  private function getFullPagesList() {
    $ret = array();
    $i = 1;

    for($i; $i <= $this->total_pages; $i++) {
      if ($i == $this->current_page) {
        $page_active = true;
        $page_href = "#";

      } else {
        $page_active = false;
        $page_href = $this->destination . $i;
      }
      $ret[] = array($i, $page_href, $page_active);
    }

    return $ret;
  }

  public function getList() {
    if ($this->next_text == null) {
      $this->next_text = I18n::t('pager.next_text');
      $this->prev_text = I18n::t('pager.prev_text');
    }
    $ret = array();

    # prev
    if ($this->current_page == 1) {
      $prev_active = false;
      $prev_href = '#';

    } else {
      $prev_active = true;
      $prev_href = $this->destination.($this->current_page - 1);
    }
    $ret[] = array( $this->prev_text, $prev_href, $prev_active);

    # pages
    if ($this->make_full_list) {
      $ret = array_merge($ret, $this->getFullPagesList());

    } else {
      if ($this->total_pages < Pager::VISIBLE_PAGES) {
        $ret = array_merge($ret, $this->getFullPagesList());

      } else {
        $i = 1;
        $last_insert_text = '';

        for($i; $i <= $this->total_pages; $i++) {
          $skip = false;
          if ($i == $this->current_page) {
            $page_active = true;
            $page_href = "#";
            $page_text = $i;

          } elseif ($i == 1 || $i == $this->total_pages ||
                    $i == $this->current_page - 1 ||
                    $i == $this->current_page + 1 ||
                    ( $this->current_page < Pager::VISIBLE_PAGES - 1 &&
                      $i < Pager::VISIBLE_PAGES - 1) ||
                    ( $this->current_page > $this->total_pages - Pager::VISIBLE_PAGES + 1 &
                      $i > $this->total_pages - Pager::VISIBLE_PAGES + 1)) {
            $page_active = false;
            $page_href = $this->destination . $i;
            $page_text = $i;

          } else {
            if ($last_insert_text !== Pager::FILLER_TEXT) {
              $page_active = false;
              $page_href = '';
              $page_text = Pager::FILLER_TEXT;
            } else {
              $skip = true;
            }
          }

          if(!$skip) {
            $last_insert_text = $page_text;
            $ret[] = array($page_text, $page_href, $page_active);
          }
        }
      }
    }

    # next
    if ($this->current_page == $this->total_pages) {
      $next_active = false;
      $next_href = '#';

    } else {
      $next_active = true;
      $next_href = $this->destination.($this->current_page -1);
    }
    $ret[] = array( $this->next_text, $next_href, $next_active);

    return $ret;
  }

  public function setNextPrevText($next_text, $prev_text) {
    $this->next_text = $next_text;
    $this->prev_text = $prev_text;
  }
}

?>
