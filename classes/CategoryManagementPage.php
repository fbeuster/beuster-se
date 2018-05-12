<?php

  class CategoryManagementPage extends AbstractAdminPage {

    private $categories;
    private $errors = array();
    private $parent_categories;

    public function __construct() {
      $this->handlePost();
      $this->load();
    }

    /**
     * This method handles changes to sub categories.
     * If a sub category is deleted, a target needs to be set,
     * to which the articles will be attached.
     *
     * A sub category can also be attached to a new parent
     * category, but cannot be deleted at the same time.
     */
    private function handleCategoryChanges() {
      $db         = Database::getDB();
      $fields     = array('id');
      $conds      = array('type = ?', 'i', array(Category::CAT_TYPE_SUB));
      $categories = $db->select('categories', $fields, $conds);

      $to_delete_categories   = array();
      $to_reassign_categories = array();

      foreach ($categories as $category) {
        if (isset($_POST['delete_category_'.$category['ID']])) {
          $to_delete_categories[] = $category['ID'];
        }

        if (isset($_POST['category_'.$category['ID'].'_new_parent']) &&
            $_POST['category_'.$category['ID'].'_new_parent'] !== 'error') {
          $to_reassign_categories[] = $category['ID'];
        }
      }

      foreach ($to_delete_categories as $category_id) {
        $target = 'delete_category_'.$category_id.'_target';

        if (!isset($_POST[$target]) || $_POST[$target] === 'error') {
          $this->errors[$target] = array(
                'message' => I18n::t('admin.category.sub_category.error.missing_target'),
                'value'   => 'error');
        }

        if (in_array($_POST[$target], $to_delete_categories)) {
          $this->errors[$target] = array(
                'message' => I18n::t('admin.category.sub_category.error.target_deleted'),
                'value'   => 'error');
        }
      }

      foreach ($to_reassign_categories as $category_id) {
        $target = 'category_'.$category_id.'_new_parent';

        if (in_array($category_id, $to_delete_categories)) {
          $this->errors[$target] = array(
                'message' => I18n::t('admin.category.sub_category.error.new_parent_and_delete'),
                'value'   => 'error');
        }
      }

      if (empty($this->errors)) {
        foreach ($to_delete_categories as $category_id) {
          $category = new Category($category_id);
          $target   = 'delete_category_'.$category_id.'_target';

          $category->moveArticles( $_POST[$target] );
          Category::delete($category_id);
        }

        foreach ($to_reassign_categories as $category_id) {
          $target   = 'category_'.$category_id.'_new_parent';
          $category = new Category($category_id);

          $category->assignToParent( $_POST[$target] );
        }
      }
    }

    private function handleNewCategory() {
      # TODO
      # Creating a new category should also check for reserved names
      # to avoid conflicts with admin pages.
      if (!isset($_POST['new_category'])) {
        $this->errors['new_category'] = array(
              'message' => I18n::t('admin.category.new.error.empty_mame'),
              'value'   => '');
      }

      if (!isset($_POST['new_category_parent'])) {
        $this->errors['new_category_parent'] = array(
              'message' => I18n::t('admin.category.new.error.empty_parent'),
              'value'   => '');
      }

      $new_category     = trim($_POST['new_category']);
      $parent_category  = $_POST['new_category_parent'];

      if (empty($this->errors)) {
        if ($new_category == '') {
          $this->errors['new_category'] = array(
                'message' => I18n::t('admin.category.new.error.empty_mame'),
                'value'   => '');
        }

        if ($parent_category == 'err') {
          $this->errors['new_category_parent'] = array(
                'message' => I18n::t('admin.category.new.error.parent_not_set'),
                'value'   => '');
        }
      }

      if (empty($this->errors) && Category::exists($new_category)) {
        $this->errors['new_category'] = array(
                'message' => I18n::t('admin.category.new.error.already_exists'),
                'value'   => '');
      }

      if (empty($this->errors)) {
        $id = Category::create($new_category, $parent_category);

        if (!$id) {
          $this->errors['new_category'] = array(
                  'message' => I18n::t('admin.category.new.error.save_error'),
                  'value'   => '');
        }
      }
    }

    private function handleNewParentCategory() {
      # TODO
      # Creating a new category should also check for reserved names
      # to avoid conflicts with admin pages.
      if (!isset($_POST['new_parent'])) {
        $this->errors['new_parent'] = array(
              'message' => I18n::t('admin.category.new_parent.error.empty_mame'),
              'value'   => '');
      }

      $new_parent = trim($_POST['new_parent']);

      if (empty($this->errors) && $new_parent == '') {
        $this->errors['new_parent'] = array(
              'message' => I18n::t('admin.category.new_parent.error.empty_mame'),
              'value'   => '');
      }

      if (empty($this->errors) && Category::exists($new_parent)) {
        $this->errors['new_parent'] = array(
              'message' => I18n::t('admin.category.new_parent.error.already_exists'),
              'value'   => '');
      }

      if (empty($this->errors)) {
        $id = Category::create($new_parent, 0, Category::CAT_TYPE_TOP);

        if (!$id) {
          $this->errors['new_parent'] = array(
                  'message' => I18n::t('admin.category.new_parent.error.save_error'),
                  'value'   => '');
        }
      }
    }

    /**
     * This method handles changes to parent categories.
     * If a parent category is deleted, a target needs to be set,
     * to which the sub categories will be attached.
     */
    private function handleParentCategoryChanges() {
      $db       = Database::getDB();
      $fields   = array('id');
      $conds    = array('type = ?', 'i', array(Category::CAT_TYPE_TOP));
      $parents  = $db->select('categories', $fields, $conds);

      $to_delete_parents = array();

      foreach ($parents as $parent) {
        if (isset($_POST['delete_parent_'.$parent['ID']])) {
          $to_delete_parents[] = $parent['ID'];
        }
      }

      foreach ($to_delete_parents as $parent_id) {
        $target = 'delete_parent_'.$parent_id.'_target';

        if (!isset($_POST[$target]) || $_POST[$target] === 'error') {
          $this->errors[$target] = array(
                'message' => I18n::t('admin.category.parent_category.error.missing_target'),
                'value'   => 'error');
        }

        if (in_array($_POST[$target], $to_delete_parents)) {
          $this->errors[$target] = array(
                'message' => I18n::t('admin.category.parent_category.error.target_deleted'),
                'value'   => 'error');
        }
      }

      if (empty($this->errors)) {
        foreach ($to_delete_parents as $parent_id) {
          $parent = new Category($parent_id);
          $target = 'delete_parent_'.$parent_id.'_target';

          $parent->moveChildren( $_POST[$target] );
          Category::delete($parent_id);
        }
      }
    }

    private function handlePost() {
      if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['parent_categories_changes'])) {
          $this->handleParentCategoryChanges();

        } else if(isset($_POST['categories_changes'])) {
          $this->handleCategoryChanges();

        } else if(isset($_POST['action_new_parent'])) {
          $this->handleNewParentCategory();

        } else if(isset($_POST['action_new_category'])) {
          $this->handleNewCategory();
        }

        if(empty($this->errors)) {
          $link     = '<br /><a href="/admin">'.
                      I18n::t('admin.back_link').'</a>';
          $message  = I18n::t('admin.category.success').$link;
          $this->showMessage($message, 'admin');
        }
      }
    }

    private function load() {
      $this->setTitle(I18n::t('admin.category.label'));

      $sub_categories = getSubCats();

      foreach($sub_categories as $sub_category) {
        $this->categories[] = array(
          'id'      => getCatID($sub_category),
          'name'    => $sub_category,
          'parent'  => getCatName(getCatParent(getCatID($sub_category))));
      }

      $top_categories = getTopCats();

      foreach($top_categories as $top_category) {
        $this->parent_categories[] = array(
          'id'    => $top_category,
          'name'  => getCatName($top_category));
      }
    }

    public function show() {
      if ($this->has_message) {
        include 'system/views/admin/static.php';

      } else {
        include 'system/views/admin/category_management.php';
      }
    }
  }

?>
