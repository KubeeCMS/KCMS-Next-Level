<?php

if (!defined('ABSPATH')) exit;

?>
<article class="daftplugAdminPage -support" data-page="support">
    <div class="daftplugAdminPage_heading -flex12">
        <img class="daftplugAdminPage_illustration" src="<?php echo plugins_url('admin/assets/img/illustration-support.png', $this->pluginFile)?>"/>
        <h2 class="daftplugAdminPage_title"><?php esc_html_e('Support', $this->textDomain); ?></h2>
        <h5 class="daftplugAdminPage_subheading"><?php esc_html_e('We understand all the importance of product support for our customers. That’s why we are ready to solve all your issues and answer any questions related to our plugin.', $this->textDomain); ?></h5>
    </div>
    <div class="daftplugAdminPage_content -flex12">
        <div class="daftplugAdminContentWrapper">
            <div class="daftplugAdminFaq -flexAuto">
                <h4 class="daftplugAdminFaq_title"><?php esc_html_e('Frequently Asked Questions', $this->textDomain); ?></h4>
                <p class="daftplugAdminFaq_description"><?php esc_html_e('Reading the FAQ is useful when you\'re experiencing a common issue related to the plugin. If the FAQ didn\'t help and you have a hard time resolving the problem, please submit a ticket.', $this->textDomain); ?></p>
                <div class="daftplugAdminFaq_list">
                    <div class="daftplugAdminFaq_item">
                        <div class="daftplugAdminFaq_question"><?php esc_html_e('Add To Home Screen overlays are not showing, Why?', $this->textDomain); ?></div>
                        <div class="daftplugAdminFaq_answer"><?php esc_html_e('Make sure the installation overlays are enabled, you are visiting your website from a Chrome, Firefox or Safari mobile browser and you have not already dismissed overlay by tapping "Continue in browser" button.', $this->textDomain); ?></div>
                    </div>
                    <div class="daftplugAdminFaq_item">
                        <div class="daftplugAdminFaq_question"><?php esc_html_e('How can I setup Push Notifications?', $this->textDomain); ?></div>
                        <div class="daftplugAdminFaq_answer"><?php _e('Push Notifications are automatically configured for you via VAPID method, so you don\'t need additional configurations or Firebase project creation. Alternatively, you can use the <a class="daftplugAdminLink" href="https://wordpress.org/plugins/onesignal-free-web-push-notifications/" target="_blank">OneSignal Push Notification</a> plugin as a push notification service.', $this->textDomain); ?>
                        </div>
                    </div>
                    <div class="daftplugAdminFaq_item">
                        <div class="daftplugAdminFaq_question"><?php esc_html_e('Does Push Notifications work on iOS?', $this->textDomain); ?></div>
                        <div class="daftplugAdminFaq_answer"><?php esc_html_e('Currently iOS Safari is not supporting web based push notifications. It will be available in future Safari updates. It is technically impossible for now to send push notifications to iPhones, but however you can send push notifications to the Macbook and iMac users.', $this->textDomain); ?></div>
                    </div>
                    <div class="daftplugAdminFaq_item">
                        <div class="daftplugAdminFaq_question"><?php esc_html_e('How can I customize the design of AMP pages?', $this->textDomain); ?></div>
                        <div class="daftplugAdminFaq_answer"><?php esc_html_e('Instantify will use your active theme\'s templates and styles to create accelerated mobile pages from them, that means your AMP version will look exactly as the normal version of your website. So if you\'ll change anything on your website, AMP version will also get that change automatically.', $this->textDomain); ?></div>
                    </div>
                    <div class="daftplugAdminFaq_item">
                        <div class="daftplugAdminFaq_question"><?php esc_html_e('How to setup Facebook Instant Articles?', $this->textDomain); ?></div>
                        <div class="daftplugAdminFaq_answer"><?php esc_html_e('Facebook uses the HTML and RSS feed of your website for converting them into instant articles. We simplified setup process as much as possible by automatically creating an RSS feed and including meta tags, but most of the settings are in Facebook page’s publishing tool section, so you\'ll need to setup and configure Instant Articles on Facebook.', $this->textDomain); ?>
                        	<div class="daftplugAdminFaq_image">
                        		<h6 class="daftplugAdminFaq_label">1. <?php _e('Go to <a class="daftplugAdminLink" href="https://www.facebook.com/instant_articles/signup" target="_blank">Instant Articles signup page</a> and choose the Facebook Page.', $this->textDomain); ?></h6>
                        		<img class="daftplugAdminFaq_img" src="<?php echo plugins_url('admin/assets/img/image-signup.png', $this->pluginFile); ?>"/>
                        	</div>
                            <div class="daftplugAdminFaq_image">
                        		<h6 class="daftplugAdminFaq_label">2. <?php esc_html_e('Scroll down a little to the Tools section on the page, expand "Connect Your Site" tab, copy your Page ID, enter it into the plugin settings Facebook Page field and save settings.', $this->textDomain); ?></h6>
                        		<img class="daftplugAdminFaq_img" src="<?php echo plugins_url('admin/assets/img/image-copy-page-id.png', $this->pluginFile); ?>"/>
                        		<img class="daftplugAdminFaq_img" src="<?php echo plugins_url('admin/assets/img/image-paste-page-id.png', $this->pluginFile); ?>"/>
                        	</div>
                            <div class="daftplugAdminFaq_image">
                        		<h6 class="daftplugAdminFaq_label">3. <?php esc_html_e('Back on Facebook enter your website URL in the field and click "Submit URL" button.', $this->textDomain); ?></h6>
                        		<img class="daftplugAdminFaq_img" src="<?php echo plugins_url('admin/assets/img/image-connect-site.png', $this->pluginFile); ?>">
                        	</div>
                            <div class="daftplugAdminFaq_image">
                        		<h6 class="daftplugAdminFaq_label">4. <?php esc_html_e('Copy your Instant Articles RSS Feed URL from plugin\'s Overview section, expand "Production RSS Feed" tab on the Facebook page\'s publishing tool, paste it in the field and click on the "Save" button.', $this->textDomain); ?></h6>
                        		<img class="daftplugAdminFaq_img" src="<?php echo plugins_url('admin/assets/img/image-rss-feed-overview.png', $this->pluginFile); ?>"/>
                        		<img class="daftplugAdminFaq_img" src="<?php echo plugins_url('admin/assets/img/image-rss-feed-facebook.png', $this->pluginFile); ?>"/>
                        	</div>
                            <div class="daftplugAdminFaq_image">
                        		<h6 class="daftplugAdminFaq_label">5. <?php esc_html_e('Expand "Styles" tab and click "default" to open the style editor and custumize the look of your Instant Articles.', $this->textDomain); ?></h6>
                        		<img class="daftplugAdminFaq_img" src="<?php echo plugins_url('admin/assets/img/image-article-styles-default.png', $this->pluginFile); ?>"/>
                        		<img class="daftplugAdminFaq_img" src="<?php echo plugins_url('admin/assets/img/image-article-styles.png', $this->pluginFile); ?>"/>
                        	</div>
                            <div class="daftplugAdminFaq_image">
                        		<h6 class="daftplugAdminFaq_label">6. <?php esc_html_e('After custumizing your Instant Articles styles, expand "Step 2: Submit For Review" tab and click "Submit for Review" button.', $this->textDomain); ?></h6>
                        		<img class="daftplugAdminFaq_img" src="<?php echo plugins_url('admin/assets/img/image-submit-for-review.png', $this->pluginFile); ?>"/>
                                <?php esc_html_e('Please note that you will need to have at least 5 articles. And when you submit for review, Facebook team will review your content and give feedback within 3-5 days. After getting approval, you can start publishing your Instant Articles on your Facebook Page.', $this->textDomain); ?>
                        	</div>
                        </div>
                    </div>
                    <div class="daftplugAdminFaq_item">
                        <div class="daftplugAdminFaq_question"><?php esc_html_e('How PWA, AMP and FBIA relate to each other?', $this->textDomain); ?></div>
                        <div class="daftplugAdminFaq_answer"><?php esc_html_e('Progressive Web Apps, Google AMP and Facebook Instant Articles work great together. In fact, in many cases, they complement each other in one way or another. Instantify will preload your PWA from your AMP pages, so the entry point of your website will be lightning fast and it will also warm up the PWA behind the scenes for the onward journey. FBIA will bring this kind of instant experience to your website within the Facebook mobile app.', $this->textDomain); ?></div>
                    </div>
                    <div class="daftplugAdminFaq_item">
                        <div class="daftplugAdminFaq_question"><?php esc_html_e('Is the plugin compatible with all themes and plugins?', $this->textDomain); ?></div>
                        <div class="daftplugAdminFaq_answer"><?php esc_html_e('PWAs, AMPs and FBIAs designed by Instantify is fully compatible with all kinds of WordPress configuration, including plugins and themes. Please note that you should disable all other plugins that deliver the same functionality as Instantify in order to avoid compatibility issues.', $this->textDomain); ?></div>
                    </div>
                    <div class="daftplugAdminFaq_item">
                        <div class="daftplugAdminFaq_question"><?php esc_html_e('How can I update the plugin?', $this->textDomain); ?></div>
                        <div class="daftplugAdminFaq_answer"><?php printf(__('There are two ways to update the plugin to the newer version: Using a WordPress built-in update system which will automatically check for updates and show you a notification on an <a class="daftplugAdminLink" href="%s">admin plugins page</a> when there will be an update available or manually download latest version of plugin from <a class="daftplugAdminLink" href="https://codecanyon.net/downloads" target="_blank">Codecanyon</a> and re-install it.', $this->textDomain), admin_url('/plugins.php')); ?></div>
                    </div>
                </div>
            </div>
            <div class="daftplugAdminSupportTicket -flexAuto">
                <form name="daftplugAdminSupportTicket_form" class="daftplugAdminSupportTicket_form" data-nonce="<?php echo wp_create_nonce("{$this->optionName}_support_ticket_nonce"); ?>" spellcheck="false" autocomplete="off">
                    <h4 class="daftplugAdminSupportTicket_title"><?php esc_html_e('Open a ticket at our Support Center', $this->textDomain); ?></h4>
                    <p class="daftplugAdminSupportTicket_description"><?php esc_html_e('Before submitting a ticket, please make sure that the FAQ didn\'t help, you\'re using the latest version of the plugin and there are no javascript errors on your website.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enter your Envato purchase code here to verify your purchase.', $this->textDomain); ?></p>
                        <div class="daftplugAdminInputText -flexAuto">
                            <input type="text" name="purchaseCode" id="purchaseCode" class="daftplugAdminInputText_field" data-placeholder="<?php esc_html_e('Purchase Code', $this->textDomain); ?>" value="<?php echo esc_html($this->purchaseCode); ?>" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enter your name and email address where we\'ll send our response.', $this->textDomain); ?></p>
                        <div class="daftplugAdminInputText -flexAuto">
                            <input type="text" name="firstName" id="firstName" class="daftplugAdminInputText_field" data-placeholder="<?php esc_html_e('Your Name', $this->textDomain); ?>" value="" autocomplete="off" required>
                        </div>
                        <div class="daftplugAdminInputText -flexAuto">
                            <input type="email" name="contactEmail" id="contactEmail" class="daftplugAdminInputText_field" data-placeholder="<?php esc_html_e('Contact Email', $this->textDomain); ?>" value="<?php echo get_option('admin_email'); ?>" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Please be as descriptive as possible regarding the details of this ticket.', $this->textDomain); ?></p>
                        <div class="daftplugAdminInputTextarea -flexAuto">
                            <textarea name="problemDescription" id="problemDescription" class="daftplugAdminInputTextarea_field" data-placeholder="<?php esc_html_e('Problem Description', $this->textDomain); ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('In most cases we need a temporary access to your WordPress Dashboard to check and fix the issue.', $this->textDomain); ?></p>
                        <div class="daftplugAdminInputText -flexAuto">
                            <input type="text" name="wordpressUsername" id="wordpressUsername" class="daftplugAdminInputText_field" data-placeholder="<?php esc_html_e('WordPress Username', $this->textDomain); ?>" value="" autocomplete="off">
                        </div>
                        <div class="daftplugAdminInputText -flexAuto">
                            <input type="text" name="wordpressPassword" id="wordpressPassword" class="daftplugAdminInputText_field" data-placeholder="<?php esc_html_e('WordPress Password', $this->textDomain); ?>" value="" autocomplete="off">
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <div class="daftplugAdminField_response -flex7"></div>
                        <div class="daftplugAdminField_submit -flex5">
                            <button type="submit" class="daftplugAdminButton -submit" data-submit="<?php esc_html_e('Submit Ticket', $this->textDomain); ?>" data-waiting="<?php esc_html_e('Waiting', $this->textDomain); ?>" data-submitted="<?php esc_html_e('Ticket Submitted', $this->textDomain); ?>" data-failed="<?php esc_html_e('Submit Failed', $this->textDomain); ?>"></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="daftplugAdminPage_content -flex12">
        <div class="daftplugAdminContentWrapper">
            <div class="daftplugAdminSupportIncludes -flexAuto">
                <h4 class="daftplugAdminSupportIncludes_title"><?php esc_html_e('Our Support Includes', $this->textDomain); ?></h4>
                <ul class="daftplugAdminSupportIncludes_list">
                    <li class="daftplugAdminSupportIncludes_item">
                        <h5 class="daftplugAdminSupportIncludes_heading">
                            <svg class="daftplugAdminSupportIncludes_icon">
                                <use href="#iconCheck"></use>
                            </svg>
                            <span class="daftplugAdminSupportIncludes_label"><?php esc_html_e('Fixing product bugs', $this->textDomain); ?></span>
                        </h5>
                        <p class="daftplugAdminSupportIncludes_text"><?php esc_html_e('Our product doesn’t work properly on your website? Report your issue or bug by describing it in detail and providing us with a link to your website. We will do our best to find a solution.', $this->textDomain); ?></p>
                    </li>
                    <li class="daftplugAdminSupportIncludes_item">
                        <h5 class="daftplugAdminSupportIncludes_heading">
                            <svg class="daftplugAdminSupportIncludes_icon">
                                <use href="#iconCheck"></use>
                            </svg>
                            <span class="daftplugAdminSupportIncludes_label"><?php esc_html_e('Life-time updates', $this->textDomain); ?></span>
                        </h5>
                        <p class="daftplugAdminSupportIncludes_text"><?php esc_html_e('We release new updates and features on a regular basis. Just don’t forget to check for the latest version in your WordPress admin panel.', $this->textDomain); ?></p>
                    </li>
                    <li class="daftplugAdminSupportIncludes_item">
                        <h5 class="daftplugAdminSupportIncludes_heading">
                            <svg class="daftplugAdminSupportIncludes_icon">
                                <use href="#iconCheck"></use>
                            </svg> 
                            <span class="daftplugAdminSupportIncludes_label"><?php esc_html_e('Customer-friendly development', $this->textDomain); ?></span>
                        </h5>
                        <p class="daftplugAdminSupportIncludes_text"><?php esc_html_e('We are open to your ideas. If you need some specific features, that might also improve our products, then just drop us a line. We will consider implementing them in our future updates.', $this->textDomain); ?></p>
                    </li>
                </ul>
            </div>
            <div class="daftplugAdminSupportNotIncludes -flexAuto">
                <h4 class="daftplugAdminSupportNotIncludes_title"><?php esc_html_e('Our Support Doesn’t Include', $this->textDomain); ?></h4>
                <ul class="daftplugAdminSupportNotIncludes_list">
                    <li class="daftplugAdminSupportNotIncludes_item">
                        <h5 class="daftplugAdminSupportNotIncludes_heading">
                            <svg class="daftplugAdminSupportNotIncludes_icon">
                                <use href="#iconX"></use>
                            </svg>
                            <span class="daftplugAdminSupportNotIncludes_label"><?php esc_html_e('Plugin installation', $this->textDomain); ?></span>
                        </h5>
                        <p class="daftplugAdminSupportNotIncludes_text"><?php esc_html_e('We don’t provide installation services for our plugins. However, if any errors come up during installation, feel free to contact us.', $this->textDomain); ?></p>
                    </li>
                    <li class="daftplugAdminSupportNotIncludes_item">
                        <h5 class="daftplugAdminSupportNotIncludes_heading">
                            <svg class="daftplugAdminSupportNotIncludes_icon">
                                <use href="#iconX"></use>
                            </svg>
                            <span class="daftplugAdminSupportNotIncludes_label"><?php esc_html_e('Plugin customization', $this->textDomain); ?></span>
                        </h5>
                        <p class="daftplugAdminSupportNotIncludes_text"><?php esc_html_e('We don’t provide plugin customization services. If you need to modify the way some features work, share your ideas with us, and we will consider them for future updates.', $this->textDomain); ?></p>
                    </li>
                    <li class="daftplugAdminSupportNotIncludes_item">
                        <h5 class="daftplugAdminSupportNotIncludes_heading">
                            <svg class="daftplugAdminSupportNotIncludes_icon">
                                <use href="#iconX"></use>
                            </svg>
                            <span class="daftplugAdminSupportNotIncludes_label"><?php esc_html_e('3rd-party issues', $this->textDomain); ?></span>
                        </h5>
                        <p class="daftplugAdminSupportNotIncludes_text"><?php esc_html_e('We don’t fix bugs or issues related to other plugins and themes, created by 3rd-party developers. Also, we don’t provide integration services for 3rd-party plugins and themes.', $this->textDomain); ?></p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</article>