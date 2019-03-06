<div id="links-language" >

    <?php foreach ($translationTool->getLangList() as $lang) : ?>

        <?php $url = menu_page_url('rg-content-translation/list.php', false).'&lang='.$lang; ?>
        <?php if ($lang == $currentPluginLanguage) : ?>

            <div class="current_lang">
                <h2>Current language : <strong><?php echo $currentPluginLanguage; ?></strong></h2>
                <div class="buttons-form">
                    <!-- Crawl templates to extract strings -->
                    <form method="GET">
                        <input type="hidden" name="page" value="rg-content-translation/list.php" />
                        <input type="hidden" name="lang" value="<?php echo $currentPluginLanguage; ?>" />
                        <input type="hidden" name="action" value="generate_db" />
                        
                        <button type="submit" class="button" data-lang="<?php echo $lang; ?>" >Extract strings from theme templates</button>
                    </form>
                    <!-- Delete unuse database string -->
                    <form method="GET">
                        <input type="hidden" name="page" value="rg-content-translation/list.php" />
                        <input type="hidden" name="lang" value="<?php echo $currentPluginLanguage; ?>" />
                        <input type="hidden" name="action" value="clean_db" />
                        
                        <button type="submit" class="button" data-lang="<?php echo $lang; ?>" >Delete unused strings from list</button>
                    </form>
                </div>

                <div class="search-form">

                    <form method="GET">
                        <input type="hidden" name="page" value="rg-content-translation/list.php" />
                        <input type="hidden" name="lang" value="<?php echo $currentPluginLanguage; ?>" />
                        <input type="hidden" name="action" value="search_db" />

                        <input type="text" class="input-search" name="search" placeholder="Search" value="<?php echo isset($_GET['search'])?$_GET['search']:''; ?>" />
                        
                        <button type="submit" class="button" data-lang="<?php echo $lang; ?>" >Search</button>
                    </form>
                </div>
            </div>

        <?php else : ?>
            <div class="other_lang">
                <a href="<?php echo $url; ?>" class="button" data-lang="<?php echo $lang; ?>"><?php echo $lang; ?></a>
            </div>
        <?php endif; ?>

    <?php endforeach; ?>
    
</div>