                <!-- Close the installer form -->
                </form>

                <?php if (!isset($disableBackButton) or !$disableBackButton): ?>
                    <form method="POST">
                        <input type="hidden" name="gdpr-installer" value="previous" />
                        <input type="submit" class="button button-secondary gdpr-step-button gdpr-step-button-prev" value="&laquo; <?= __('Back'); ?>">
                    </form>
                <?php endif; ?>

            </div> <!-- .gdpr-content -->

            

                <!--
            <div class="gdpr-sticky">
                <hr>
                <a class="button button-primary" href="#">I need help</a>
            </div>
            -->

        </div> <!-- .container -->
        
        <div class="gdpr-footer-links">
          <p>
            You can always leave and continue the setup later from where you left off
          </p>
          <a class="button button-secondary" href="<?= admin_url(); ?>">
            Go to Dashboard
          </a>
        </div>
        
        <?php wp_print_scripts([
            'gdpr-installer',
            'jquery-repeater',
            'select2',
            'conditional-show'
        ]); ?>
        <?php do_action('admin_print_footer_scripts'); ?>
    </body>
</html>
