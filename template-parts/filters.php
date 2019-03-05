<div class="wp-filter">
    <div id="links-language" class="media-toolbar-secondary">

        <?php foreach ($translationTool->getLangList() as $lang) : ?>
            <?php $url = menu_page_url('rg-content-translation/list.php', false).'&lang='.$lang; ?>
            <?php if ($lang == $currentPluginLanguage) : ?>
                <!-- Crawl templates to extract strings -->
                <form method="GET">
                    <input type="hidden" name="page" value="rg-content-translation/list.php" />
                    <input type="hidden" name="lang" value="<?php echo $currentPluginLanguage; ?>" />
                    <input type="hidden" name="action" value="generate_db" />
                    
                    <button type="submit" class="button" data-lang="<?php echo $lang; ?>" >Extract strings from theme templates [ <?php echo $lang ?> ]</button>
                </form>
                <!-- Delete unuse database string -->
                <form method="GET">
                    <input type="hidden" name="page" value="rg-content-translation/list.php" />
                    <input type="hidden" name="lang" value="<?php echo $currentPluginLanguage; ?>" />
                    <input type="hidden" name="action" value="clean_db" />
                    
                    <button type="submit" class="button" data-lang="<?php echo $lang; ?>" >Delete unused strings from list [ <?php echo $lang ?> ]</button>
                </form>

                <form method="GET">
                    <input type="hidden" name="page" value="rg-content-translation/list.php" />
                    <input type="hidden" name="lang" value="<?php echo $currentPluginLanguage; ?>" />
                    <input type="hidden" name="action" value="search_db" />

                    <input type="text" name="search" placeholder="Search" value="<?php echo isset($_GET['search'])?$_GET['search']:''; ?>" />
                    
                    <button type="submit" class="button" data-lang="<?php echo $lang; ?>" >Search [ <?php echo $lang ?> ]</button>
                </form>

            <?php else : ?>
                <a href="<?php echo $url; ?>" class="button" data-lang="<?php echo $lang; ?>"><?php echo $lang; ?></a>
            <?php endif; ?>
        <?php endforeach; ?>
        
    </div>
</div>