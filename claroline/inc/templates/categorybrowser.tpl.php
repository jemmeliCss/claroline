<!-- $Id$ -->

<!-- CURRENT CATEGORY (default: root category) -->
<?php if ($this->categoryBrowser->categoryId > 0) : ?>
<h3 id="categoryContent"><?php echo $this->currentCategory->name; ?></h3>

<p>
    <a href="<?php echo 
                $_SERVER['PHP_SELF'] . '?'
              . (isset($_REQUEST['cmd']) ? 'cmd='.urlencode($_REQUEST['cmd']) : '')
              . '&amp;category=' . urlencode( $this->currentCategory->idParent ) . '#categoryContent'; ?>">
        <span style="background-image: url(<?php echo get_icon_url('back'); ?>); background-repeat: no-repeat; background-position: left center; padding-left: 20px;">
            <?php echo get_lang('Back to parent category'); ?>
        </span>
    </a>
</p>

<?php else : ?>
<h3><?php echo get_lang('Root category'); ?></h3>

<?php endif; ?>



<!-- SUB CATEGORIES (with link to go deeper when possible) -->
<?php if ( count($this->categoryList) - 1 >= 0 ) : ?>

<h4><?php echo get_lang('Sub categories'); ?></h4>

<ul>
<?php foreach( $this->categoryList as $category ) : ?>
    
    <?php if (claroCategory::countAllCourses($category['id']) + claroCategory::countAllSubCategories($category['id']) > 0) : ?>
    <li>
        <?php echo '<a href="' . $_SERVER['PHP_SELF'] . '?'
        . (isset($_REQUEST['cmd']) ? 'cmd='.urlencode($_REQUEST['cmd']) : '')
        . '&amp;category='
        . (int) $category['id'] . '#categoryContent">'
        . $category['name'] . '</a>'; ?>
    </li>
    <?php else : ?>
    <li><?php echo $category['name']; ?></li>
    <?php endif; ?>
    
<?php endforeach; ?>
</ul>

<?php endif; ?>



<!-- COURSES (belonging to the current category) -->
<h4><?php echo get_lang( 'Courses in this category' ); ?></h4>

<?php echo $this->courseTreeView->render(); ?>


<?php if ($this->categoryBrowser->categoryId > 0) : ?>
<p>
    <a href="<?php echo 
                $_SERVER['PHP_SELF'] . '?'
              . (isset($_REQUEST['cmd']) ? 'cmd='.urlencode($_REQUEST['cmd']) : '')
              . '&amp;category=' . urlencode( $this->currentCategory->idParent ) . '#categoryContent'; ?>">
        <span style="background-image: url(<?php echo get_icon_url('back'); ?>); background-repeat: no-repeat; background-position: left center; padding-left: 20px;">
            <?php echo get_lang('Back to parent category'); ?>
        </span>
    </a>
</p>
<?php endif; ?>
