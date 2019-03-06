<?php if(count($translations['results'])) : ?>

    <form method="post" id="translation-form" class="form-table" cellspacing="0">
        <input type="hidden" name="lang" id="form-lang" value="<?php echo $translationTool->getDefaultLang(); ?>" />
        <input type="hidden" name="action" value="rgct_save" />
        
        <table id="fields-container">
            <thead>
                <th class="translation_key">Translation key</th>
                <th class="translation_value">Value</th>
            </thead>
            <?php foreach($translations['results'] as $row) : ?>

                <tr valign="top">
                    <td scope="row" valign="middle" class="translation_key">
                        <label><?php echo $row->translation_key; ?></label>
                    </td>
                    <td class="translation_value">
                        <textarea name="form[<?php echo $row->id; ?>]" ><?php echo $row->value; ?></textarea>
                    </td>
                </tr>

            <?php endforeach; ?>

        </table>
        
        <p class="submit">
            <input type="submit" id="submit" class="button button-primary" value="Save"><span class="spinner"></span>
        </p>
    </form>

    <?php include('pagination.php'); ?>

<?php else: ?>
    <h2> There's no translation currently available.</h2>
    <p>You can try the button to extract the strings from your template.</p>
<?php endif; ?>