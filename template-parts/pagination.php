<?php //show pagination if there is more than 1 page ?>
<?php if($translations['totalPages'] > 1) : ?>

    <?php 
        $page = $translations['currentPage'];
        $url = menu_page_url('rg-content-translation/list.php', false).'&lang='.$lang; 
        if(isset($_GET['search']) && $_GET['search'] != '') { $url .= '&action=search_db&search=' . $_GET['search']; }
    ?>

    <div class="pages">
                
        <div class="pages-container">

            <?php //link to previous page ?>
            <?php if($page > 1) : ?>
            <a href="<?php echo $url . '&paginate=' . ($page - 1); ?>" class="go-next arrowSvg"> < </a>
            <?php endif; ?>
            
            <?php if($page == 1) : ?>
                <?php //if current page is the first one show the second one and the third one if it exists ?>
                <a class="go-next active-page">1</a>
                <a href="<?php echo $url . '&paginate=2'; ?>" class="go-next">2</a>
                <?php if($translations['totalPages'] >= 3) : ?>
                    <a href="<?php echo $url . '&paginate=3'; ?>" class="go-next">3</a>
                <?php endif; ?>
            <?php else : ?>
                <?php // if current page is the last one show the last two ?>
                <?php if($page == $translations['totalPages'] && $page > 2) : ?>
                    <a href="<?php echo $url . '&paginate=' . ($page - 2); ?>" class="go-next"><?php echo ($page - 2); ?></a>
                <?php endif; ?>

                <?php // link to previous page ?>
                <a href="<?php echo $url . '&paginate=' . ($page - 1); ?>" class="go-next"><?php echo ($page - 1); ?></a>

                <?php // Current page without link ?>
                <a class="go-next active-page"><?php echo $page; ?></a>

                <?php // link to next page ?>
                <?php if($page != $translations['totalPages']) : ?>
                    <a href="<?php echo $url . '&paginate=' . ($page + 1); ?>" class="go-next"><?php echo ($page + 1); ?></a>
                <?php endif; ?>

            <?php endif; ?>
                    
            <?php //link to next page ?>
            <?php if($page != $translations['totalPages']) : ?>
                <a href="<?php echo $url . '&paginate=' . ($page + 1); ?>" class="go-next arrowSvg"> > </a>
            <?php endif; ?>

        </div>

    </div>

<?php endif; ?>
