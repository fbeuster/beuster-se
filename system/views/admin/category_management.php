
<article>
  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
  <section class="article">
    <h1><?php I18n::e('admin.category.label'); ?></h1>
    <?php if( count($this->categories) &&
              count($this->parent_categories)) {
        $i = 0; ?>
      <form action="/category-management" method="post" class="userform articleform">
        <fieldset>
          <fieldset>
            <legend>
              <?php I18n::e('admin.category.parent_category.label'); ?>
            </legend>
            <table class="newslist">
              <thead>
                <tr>
                  <th>
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
                <?php if($parent_category['name'] != 'Blog') { ?>
                  <tr class=<?php echo '"backendTableRow'.($i%2).'"'; ?>>
                    <td>
                      <?php echo $parent_category['name']; ?>
                    </td>
                    <td>
                      <?php if($parent_category['name'] != 'Blog') { ?>
                        <input type="checkbox"
                              name="delete_parent_<?php echo $parent_category['id']; ?>"
                              title="<?php I18n::e('admin.category.parent_category.delete.title'); ?>">
                      <?php } ?>
                    </td>
                    <td>
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
                <?php } ?>
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
            <table class="newslist">
              <thead>
                <tr>
                  <th>
                    <?php I18n::e('admin.category.sub_category.table_header.name'); ?>
                  </th>
                  <th>
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
                  <td><?php echo $category['name']; ?></td>
                  <td><?php echo $category['parent']; ?></td>
                  <td>
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
                  <td>
                    <input type="checkbox"
                          name="delete_category_<?php echo $category['id'];?>"
                          class="del"
                          title="<?php I18n::e('admin.category.parent_category.delete.title'); ?>"></td>
                  <td>
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
  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
</article>