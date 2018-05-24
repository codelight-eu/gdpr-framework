<label for="gdpr_has_dpo">
    <input
        type="checkbox"
        name="gdpr_has_dpo"
        id="gdpr_has_dpo"
        class="js-gdpr-conditional"
        data-show=".gdpr-dpo"
        value="yes"
        <?= checked($hasDPO, 'yes'); ?>
    >
    <?= _x('I have appointed a Data Protection Officer (DPO)', '(Admin)', 'gdpr-framework'); ?>
</label>