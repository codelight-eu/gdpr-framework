<h1>
    Privacy Policy (2/2)
</h1>

<h2>&#10004; Privacy Policy configured!</h2>
<?php if ($policyGenerated): ?>
    <p>
        Take a look at your new Privacy Policy page <a href="<?= $policyUrl; ?>" target="_blank">here</a>! <br>
        Make sure you read it - you will need to know all this anyway. <br><br>
        <strong>Important!</strong><br> The contents of your Privacy Policy depend on what your website (and
        business) does exactly. Note that this is just a template. <strong>You will need to modify it to suit your needs</strong>. To do that,
        go to the <a href="<?= $editPolicyUrl; ?>" target="_blank">admin Privacy Policy page</a> and just edit the contents of your policy.
    </p>
<?php else: ?>
    <p>
        It looks like you selected an already existing Privacy Policy. <br>
        Note that GDPR puts some new requirements on what you need to have in your Privacy Policy. <br>
        <a href="<?= gdpr('helpers')->docs('guide/setting-up-the-privacy-policy/'); ?>" target="_blank">Read more about the requirements to your Privacy Policy</a>
    </p>
<?php endif; ?>

<hr>
<br>

<input type="submit" class="button button-gdpr button-right" value="Save &raquo;" />
