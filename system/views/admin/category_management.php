<?php
  $lb = Lixter::getLix()->getLinkBuilder();
?>

<article>
  <?php include 'navbar.php'; ?>
  <section class="article">
    <?php if(count($this->errors)){ ?>
      <div class="error">
        <div class="title">Error</div>
        <ul class="messages">
        <?php foreach ($this->errors as $name => $error) { ?>

          <?php if (!isset($error['message'])) { ?>
            <?php foreach ($error as $single) { ?>
              <li><?php echo $single['message']; ?></li>
            <?php } ?>

          <?php } else { ?>
            <li><?php echo $error['message']; ?></li>
          <?php } ?>

        <?php } ?>
        </ul>
      </div>
    <?php } ?>

    <h1><?php I18n::e('admin.category.label'); ?></h1>
    <?php if( count($this->categories) ||
              count($this->parent_categories)) {
        $i = 0; ?>
      <form action="<?php echo $lb->makeAdminLink('category-management'); ?>" method="post" class="userform articleform multiFieldset">
        <fieldset>
          <fieldset>
            <legend>
              <?php I18n::e('admin.category.parent_category.label'); ?>
            </legend>
            <table class="categoryTable">
              <thead>
                <tr>
                  <th class="text">
                    <?php I18n::e('admin.category.parent_category.table_header.name'); ?>
                  </th>
                  <th class="radio">
                    <?php I18n::e('admin.category.parent_category.table_header.delete'); ?>
                  </th>
                  <th>
                    <?php I18n::e('admin.category.parent_category.table_header.target'); ?>
                  </th>
                </tr>
              </thead>
              <tbody>
              <?php foreach($this->parent_categories as $parent_category) { ?>
                <tr class=<?php echo '"backendTableRow'.($i%2).'"'; ?>>
                  <td class="text">
                    <?php echo $parent_category['name']; ?>
                  </td>
                  <td class="radio">
                    <input type="checkbox"
                          name="delete_parent_<?php echo $parent_category['id']; ?>"
                          title="<?php I18n::e('admin.category.parent_category.delete.title'); ?>">
                  </td>
                  <td class="select">
                    <select name="delete_parent_<?php echo $parent_category['id']; ?>_target"<?php echo (isset($this->errors['delete_parent_'.$parent_category['id'].'_target']) ? 'class="has_error"' : ''); ?> title="<?php I18n::e('admin.category.parent_category.target.title'); ?>">
                      <option value="error">
                        <?php I18n::e('admin.category.parent_category.target.label'); ?>
                      </option>
                      <?php foreach($this->parent_categories as $parent) { ?>
                        <?php if($parent_category['name'] != $parent['name']) { ?>
                          <option value="<?php echo $parent['id']; ?>">
                            <?php echo $parent['name']; ?>
                          </option>
                        <?php } ?>
                      <?php } ?>
                    </select>
                  </td>
                </tr>
              <?php $i++; } ?>
              </tbody>
            </table>
            <input type="submit"
                  value="<?php I18n::e('admin.category.parent_category.submit'); ?>"
                  name="parent_categories_changes">
          </fieldset>

          <fieldset>
            <legend>
              <?php I18n::e('admin.category.sub_category.label'); ?>
            </legend>
            <?php if (count($this->categories) == 0) { ?>
              <p><?php I18n::e('admin.category.sub_category.empty'); ?></p>

            <?php } else { ?>
              <table class="categoryTable">
                <thead>
                  <tr>
                    <th class="text">
                      <?php I18n::e('admin.category.sub_category.table_header.name'); ?>
                    </th>
                    <th class="text">
                      <?php I18n::e('admin.category.sub_category.table_header.parent'); ?>
                    </th>
                    <th>
                      <?php I18n::e('admin.category.sub_category.table_header.parent_new'); ?>
                    </th>
                    <th class="radio">
                      <?php I18n::e('admin.category.sub_category.table_header.delete'); ?>
                    </th>
                    <th>
                      <?php I18n::e('admin.category.sub_category.table_header.target'); ?>
                    </th>
                  </tr>
                </thead>
                <tbody>
                <?php $i = 0;
                  foreach($this->categories as $category) { ?>
                  <tr>
                    <td class="text"><?php echo $category['name']; ?></td>
                    <td class="text"><?php echo $category['parent']; ?></td>
                    <td class="select">
                      <select name="category_<?php  echo $category['id']; ?>_new_parent">
                        <option value="error">
                            <?php I18n::e('admin.category.sub_category.new_parent.label'); ?>
                          </option>
                        <?php foreach($this->parent_categories as $parent_category) { ?>
                          <?php if($category['parent'] != $parent_category['name']) { ?>
                            <option value="<?php echo $parent_category['id']; ?>">
                              <?php echo $parent_category['name']; ?>
                            </option>
                          <?php } ?>
                        <?php } ?>
                      </select>
                    </td>
                    <td class="radio">
                      <input type="checkbox"
                            name="delete_category_<?php echo $category['id'];?>"
                            class="del"
                            title="<?php I18n::e('admin.category.parent_category.delete.title'); ?>"></td>
                    <td class="select">
                      <select name="delete_category_<?php echo $category['id'];?>_target" title="<?php I18n::e('admin.category.sub_category.target.title'); ?>">
                        <option value="error">
                            <?php I18n::e('admin.category.sub_category.target.label'); ?>
                          </option>
                        <?php foreach ($this->categories as $cat) { ?>
                          <?php if ($category['name'] != $cat['name'] &&
                                    !in_array($cat['name'],
                                              $this->parent_categories)) { ?>
                            <option value="<?php echo $cat['id']; ?>">
                              <?php echo $cat['name']; ?>
                            </option>
                          <?php } ?>
                        <?php } ?>
                      </select>
                    </td>
                  </tr>
                  <?php $i++; } ?>
                </tbody>
              </table>
              <input type="submit"
                    value="<?php I18n::e('admin.category.sub_category.submit'); ?>"
                    name="categories_changes">
            <?php } ?>
          </fieldset>

          <fieldset>
            <legend>
              <?php I18n::e('admin.category.new_parent.label'); ?>
            </legend>
            <label>
              <span>
                <?php I18n::e('admin.category.new_parent.new_parent.label'); ?>
              </span>
              <input type="text" name="new_parent" placeholder="<?php I18n::e('admin.category.new_parent.new_parent.placeholder'); ?>">
            </label>
            <input type="submit"
                  value="<?php I18n::e('admin.category.new_parent.submit'); ?>"
                  name="action_new_parent">
          </fieldset>

          <fieldset>
            <legend>
              <?php I18n::e('admin.category.new.label'); ?>
            </legend>
            <label>
              <span>
                <?php I18n::e('admin.category.new.new_category.label'); ?>
              </span>
              <input type="text" name="new_category" placeholder="<?php I18n::e('admin.category.new.new_category.placeholder'); ?>">
              <select name="new_category_parent">
                <option>
                  <?php I18n::e('admin.category.new.new_category_parent.label'); ?>
                </option>
                <?php foreach($this->parent_categories as $parent_category) { ?>
                  <option value="<?php echo $parent_category['id']; ?>">
                    <?php echo $parent_category['name']; ?>
                  </option>
                <?php } ?>
              </select>
            </label>
            <input type="submit"
                  value="<?php I18n::e('admin.category.new.submit'); ?>"
                  name="action_new_category">
          </fieldset>

        </fieldset>
      </form>

    <?php } else { ?>
      <p>Keine Kategorien vorhanden.</p>
    <?php } ?>
  </section>
</article>