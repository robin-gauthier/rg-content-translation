<form method="post" id="translation-form" class="form-table" cellspacing="0">
    <input type="hidden" name="lang" id="form-lang" value="<?php echo $translationTool->getDefaultLang(); ?>" />
    <input type="hidden" name="action" value="rgct_save" />
    
    <table id="fields-container">

        <?php foreach($rowsArray as $row) : ?>

            <tr valign="top">
                <th scope="row">
                    <label><?php echo $row->translation_key; ?></label></th>
                <td>
                    <textarea name="form[<?php echo $row->id; ?>]" cols="100" rows="3"><?php echo $row->value; ?></textarea>
                </td>
            </tr>

        <?php endforeach; ?>

    </table>
    
    <p class="submit" style="display:none;">
        <input type="submit" id="submit" class="button button-primary" value="Sauvegarder"><span class="spinner"></span>
    </p>
</form>