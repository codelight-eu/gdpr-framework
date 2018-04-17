<div class="control-box">
    <fieldset>
        <legend><?php echo sprintf(esc_html($description), $descLink); ?></legend>
    </fieldset>
    <p>
        <?= _x('This tag generates the default text for Terms & Conditions and/or Privacy Policy checkbox.', '(Admin)', 'gdpr-framework'); ?> <br/>
    </p>
</div>

<div class="insert-box">
    <input type="text" name="asdf" class="tag code" readonly="readonly" onfocus="this.select()"/>

    <div class="submitbox">
        <input type="button" class="button button-primary insert-tag"
               value="<?php echo esc_attr(__('Insert', 'contact-form-7')); ?>"/>
    </div>
</div>
