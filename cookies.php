<?php
session_start();
$pageTitle = 'Cookie Policy';
include 'includes/header.php';
?>

<div class="cookies-page">
    <!-- Cookies Hero -->
    <section class="cookies-hero">
        <div class="container">
            <div class="hero-content">
                <h1><i class="fas fa-cookie-bite"></i> Cookie Policy</h1>
                <p>Learn how we use cookies to improve your browsing experience.</p>
                <div class="last-updated">
                    <i class="fas fa-calendar-alt"></i>
                    Last updated: <?php echo date('F j, Y'); ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Cookies Content -->
    <section class="cookies-content">
        <div class="container">
            <div class="content-wrapper">
                <!-- Table of Contents -->
                <div class="toc-sidebar">
                    <div class="toc-card">
                        <h3>Table of Contents</h3>
                        <ul class="toc-list">
                            <li><a href="#what-are-cookies">What Are Cookies?</a></li>
                            <li><a href="#how-we-use-cookies">How We Use Cookies</a></li>
                            <li><a href="#types-of-cookies">Types of Cookies</a></li>
                            <li><a href="#essential-cookies">Essential Cookies</a></li>
                            <li><a href="#analytics-cookies">Analytics Cookies</a></li>
                            <li><a href="#marketing-cookies">Marketing Cookies</a></li>
                            <li><a href="#third-party-cookies">Third-Party Cookies</a></li>
                            <li><a href="#managing-cookies">Managing Cookies</a></li>
                            <li><a href="#cookie-consent">Cookie Consent</a></li>
                            <li><a href="#contact-us">Contact Us</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="cookies-main">
                    <div class="cookies-section" id="what-are-cookies">
                        <h2><i class="fas fa-question-circle"></i> What Are Cookies?</h2>
                        <div class="section-content">
                            <p>Cookies are small text files that are stored on your device (computer, tablet, or mobile) when you visit a website. They are widely used to make websites work more efficiently and to provide information to website owners.</p>
                            
                            <div class="info-box">
                                <i class="fas fa-info-circle"></i>
                                <div>
                                    <h4>Key Points About Cookies</h4>
                                    <ul>
                                        <li>Cookies are not harmful to your device</li>
                                        <li>They cannot access personal files on your computer</li>
                                        <li>They help websites remember your preferences</li>
                                        <li>Most websites use cookies to improve user experience</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cookies-section" id="how-we-use-cookies">
                        <h2><i class="fas fa-cogs"></i> How We Use Cookies</h2>
                        <div class="section-content">
                            <p>At Velvet Vogue, we use cookies to enhance your shopping experience and improve our services. Here's how we use them:</p>
                            
                            <div class="usage-grid">
                                <div class="usage-item">
                                    <i class="fas fa-user-cog"></i>
                                    <h4>Personalization</h4>
                                    <p>Remember your preferences, language settings, and login status</p>
                                </div>
                                <div class="usage-item">
                                    <i class="fas fa-shopping-cart"></i>
                                    <h4>Shopping Cart</h4>
                                    <p>Keep track of items in your cart as you browse our website</p>
                                </div>
                                <div class="usage-item">
                                    <i class="fas fa-chart-line"></i>
                                    <h4>Analytics</h4>
                                    <p>Understand how visitors use our website to improve functionality</p>
                                </div>
                                <div class="usage-item">
                                    <i class="fas fa-shield-alt"></i>
                                    <h4>Security</h4>
                                    <p>Protect against fraud and ensure secure transactions</p>
                                </div>
                                <div class="usage-item">
                                    <i class="fas fa-bullhorn"></i>
                                    <h4>Marketing</h4>
                                    <p>Show relevant advertisements and measure campaign effectiveness</p>
                                </div>
                                <div class="usage-item">
                                    <i class="fas fa-tools"></i>
                                    <h4>Functionality</h4>
                                    <p>Enable website features like live chat and social media integration</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cookies-section" id="types-of-cookies">
                        <h2><i class="fas fa-layer-group"></i> Types of Cookies</h2>
                        <div class="section-content">
                            <p>We use different types of cookies based on their purpose and duration:</p>
                            
                            <div class="cookie-types">
                                <div class="cookie-type">
                                    <div class="type-header">
                                        <i class="fas fa-clock"></i>
                                        <h3>Session Cookies</h3>
                                    </div>
                                    <p>Temporary cookies that are deleted when you close your browser. They help maintain your session while browsing our website.</p>
                                    <div class="examples">
                                        <strong>Examples:</strong> Shopping cart contents, login status
                                    </div>
                                </div>
                                
                                <div class="cookie-type">
                                    <div class="type-header">
                                        <i class="fas fa-save"></i>
                                        <h3>Persistent Cookies</h3>
                                    </div>
                                    <p>Cookies that remain on your device for a set period or until you delete them. They remember your preferences across visits.</p>
                                    <div class="examples">
                                        <strong>Examples:</strong> Language preferences, "Remember me" settings
                                    </div>
                                </div>
                                
                                <div class="cookie-type">
                                    <div class="type-header">
                                        <i class="fas fa-home"></i>
                                        <h3>First-Party Cookies</h3>
                                    </div>
                                    <p>Cookies set directly by our website. We have full control over these cookies and how they're used.</p>
                                    <div class="examples">
                                        <strong>Examples:</strong> User preferences, shopping cart, analytics
                                    </div>
                                </div>
                                
                                <div class="cookie-type">
                                    <div class="type-header">
                                        <i class="fas fa-external-link-alt"></i>
                                        <h3>Third-Party Cookies</h3>
                                    </div>
                                    <p>Cookies set by external services we use on our website, such as analytics tools or advertising networks.</p>
                                    <div class="examples">
                                        <strong>Examples:</strong> Google Analytics, social media widgets, advertising
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cookies-section" id="essential-cookies">
                        <h2><i class="fas fa-exclamation-circle"></i> Essential Cookies</h2>
                        <div class="section-content">
                            <p>These cookies are necessary for our website to function properly. They cannot be disabled without affecting the basic functionality of our site.</p>
                            
                            <div class="cookie-table">
                                <div class="table-header">
                                    <div>Cookie Name</div>
                                    <div>Purpose</div>
                                    <div>Duration</div>
                                </div>
                                <div class="table-row">
                                    <div><code>PHPSESSID</code></div>
                                    <div>Maintains your session while browsing</div>
                                    <div>Session</div>
                                </div>
                                <div class="table-row">
                                    <div><code>cart_items</code></div>
                                    <div>Stores items in your shopping cart</div>
                                    <div>7 days</div>
                                </div>
                                <div class="table-row">
                                    <div><code>user_preferences</code></div>
                                    <div>Remembers your site preferences</div>
                                    <div>30 days</div>
                                </div>
                                <div class="table-row">
                                    <div><code>security_token</code></div>
                                    <div>Protects against security threats</div>
                                    <div>Session</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cookies-section" id="analytics-cookies">
                        <h2><i class="fas fa-chart-bar"></i> Analytics Cookies</h2>
                        <div class="section-content">
                            <p>These cookies help us understand how visitors interact with our website by collecting and reporting information anonymously.</p>
                            
                            <div class="analytics-info">
                                <div class="analytics-provider">
                                    <div class="provider-header">
                                        <i class="fab fa-google"></i>
                                        <h4>Google Analytics</h4>
                                    </div>
                                    <p>We use Google Analytics to analyze website traffic and user behavior. This helps us improve our website and services.</p>
                                    <div class="cookie-list">
                                        <div class="cookie-item">
                                            <strong>_ga:</strong> Distinguishes unique users (2 years)
                                        </div>
                                        <div class="cookie-item">
                                            <strong>_gid:</strong> Distinguishes unique users (24 hours)
                                        </div>
                                        <div class="cookie-item">
                                            <strong>_gat:</strong> Throttles request rate (1 minute)
                                        </div>
                                    </div>
                                    <a href="https://policies.google.com/privacy" target="_blank" class="privacy-link">
                                        <i class="fas fa-external-link-alt"></i>
                                        Google Privacy Policy
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cookies-section" id="marketing-cookies">
                        <h2><i class="fas fa-bullhorn"></i> Marketing Cookies</h2>
                        <div class="section-content">
                            <p>These cookies are used to deliver advertisements that are relevant to you and your interests. They also help measure the effectiveness of advertising campaigns.</p>
                            
                            <div class="marketing-features">
                                <div class="feature-item">
                                    <i class="fas fa-target"></i>
                                    <div>
                                        <h4>Targeted Advertising</h4>
                                        <p>Show you relevant ads based on your browsing behavior and interests</p>
                                    </div>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-sync-alt"></i>
                                    <div>
                                        <h4>Retargeting</h4>
                                        <p>Display ads for products you've viewed on other websites</p>
                                    </div>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-chart-pie"></i>
                                    <div>
                                        <h4>Campaign Measurement</h4>
                                        <p>Track the effectiveness of our advertising campaigns</p>
                                    </div>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-users"></i>
                                    <div>
                                        <h4>Audience Building</h4>
                                        <p>Create custom audiences for more relevant advertising</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="opt-out-info">
                                <i class="fas fa-info-circle"></i>
                                <p><strong>Note:</strong> You can opt out of personalized advertising by visiting the <a href="https://www.google.com/settings/ads" target="_blank">Google Ads Settings</a> or the <a href="http://www.aboutads.info/choices/" target="_blank">Digital Advertising Alliance</a>.</p>
                            </div>
                        </div>
                    </div>

                    <div class="cookies-section" id="third-party-cookies">
                        <h2><i class="fas fa-external-link-alt"></i> Third-Party Cookies</h2>
                        <div class="section-content">
                            <p>We work with trusted third-party services that may set cookies on our website. Here are the main third-party services we use:</p>
                            
                            <div class="third-party-services">
                                <div class="service-card">
                                    <div class="service-header">
                                        <i class="fab fa-google"></i>
                                        <h4>Google Services</h4>
                                    </div>
                                    <ul>
                                        <li>Google Analytics (website analytics)</li>
                                        <li>Google Ads (advertising)</li>
                                        <li>Google Fonts (web fonts)</li>
                                        <li>reCAPTCHA (spam protection)</li>
                                    </ul>
                                </div>
                                
                                <div class="service-card">
                                    <div class="service-header">
                                        <i class="fab fa-facebook"></i>
                                        <h4>Social Media</h4>
                                    </div>
                                    <ul>
                                        <li>Facebook Pixel (advertising)</li>
                                        <li>Instagram integration</li>
                                        <li>Social sharing buttons</li>
                                        <li>Social login features</li>
                                    </ul>
                                </div>
                                
                                <div class="service-card">
                                    <div class="service-header">
                                        <i class="fas fa-credit-card"></i>
                                        <h4>Payment Processors</h4>
                                    </div>
                                    <ul>
                                        <li>Stripe (payment processing)</li>
                                        <li>PayPal (payment processing)</li>
                                        <li>Fraud detection services</li>
                                        <li>Security verification</li>
                                    </ul>
                                </div>
                                
                                <div class="service-card">
                                    <div class="service-header">
                                        <i class="fas fa-comments"></i>
                                        <h4>Customer Support</h4>
                                    </div>
                                    <ul>
                                        <li>Live chat widgets</li>
                                        <li>Help desk integration</li>
                                        <li>Customer feedback tools</li>
                                        <li>Support ticket systems</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cookies-section" id="managing-cookies">
                        <h2><i class="fas fa-sliders-h"></i> Managing Cookies</h2>
                        <div class="section-content">
                            <p>You have several options for managing cookies on your device:</p>
                            
                            <div class="management-options">
                                <div class="option-card">
                                    <i class="fas fa-cog"></i>
                                    <h4>Browser Settings</h4>
                                    <p>Most browsers allow you to control cookies through their settings. You can:</p>
                                    <ul>
                                        <li>Block all cookies</li>
                                        <li>Block third-party cookies only</li>
                                        <li>Delete existing cookies</li>
                                        <li>Set cookies to expire when you close your browser</li>
                                    </ul>
                                </div>
                                
                                <div class="option-card">
                                    <i class="fas fa-toggle-on"></i>
                                    <h4>Cookie Preferences</h4>
                                    <p>Use our cookie preference center to control which types of cookies you accept:</p>
                                    <div class="preference-controls">
                                        <button class="btn btn-primary" onclick="openCookiePreferences()">Manage Cookie Preferences</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="browser-guides">
                                <h3>Browser-Specific Instructions</h3>
                                <div class="browser-grid">
                                    <div class="browser-item">
                                        <i class="fab fa-chrome"></i>
                                        <h4>Chrome</h4>
                                        <p>Settings → Privacy and security → Cookies and other site data</p>
                                    </div>
                                    <div class="browser-item">
                                        <i class="fab fa-firefox"></i>
                                        <h4>Firefox</h4>
                                        <p>Options → Privacy & Security → Cookies and Site Data</p>
                                    </div>
                                    <div class="browser-item">
                                        <i class="fab fa-safari"></i>
                                        <h4>Safari</h4>
                                        <p>Preferences → Privacy → Manage Website Data</p>
                                    </div>
                                    <div class="browser-item">
                                        <i class="fab fa-edge"></i>
                                        <h4>Edge</h4>
                                        <p>Settings → Cookies and site permissions → Cookies and site data</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="warning-box">
                                <i class="fas fa-exclamation-triangle"></i>
                                <div>
                                    <h4>Important Note</h4>
                                    <p>Disabling cookies may affect the functionality of our website. Some features may not work properly, and you may not be able to access certain areas of the site.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cookies-section" id="cookie-consent">
                        <h2><i class="fas fa-check-circle"></i> Cookie Consent</h2>
                        <div class="section-content">
                            <p>We respect your privacy and give you control over your cookie preferences. Here's how our consent system works:</p>
                            
                            <div class="consent-process">
                                <div class="process-step">
                                    <div class="step-number">1</div>
                                    <div class="step-content">
                                        <h4>First Visit</h4>
                                        <p>When you first visit our website, we'll show you a cookie banner explaining our use of cookies</p>
                                    </div>
                                </div>
                                <div class="process-step">
                                    <div class="step-number">2</div>
                                    <div class="step-content">
                                        <h4>Your Choice</h4>
                                        <p>You can accept all cookies, reject non-essential cookies, or customize your preferences</p>
                                    </div>
                                </div>
                                <div class="process-step">
                                    <div class="step-number">3</div>
                                    <div class="step-content">
                                        <h4>Ongoing Control</h4>
                                        <p>You can change your preferences at any time using our cookie preference center</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="consent-categories">
                                <h3>Cookie Categories</h3>
                                <div class="category-list">
                                    <div class="category-item essential">
                                        <div class="category-header">
                                            <i class="fas fa-lock"></i>
                                            <h4>Essential Cookies</h4>
                                            <span class="status required">Always Active</span>
                                        </div>
                                        <p>Required for basic website functionality. Cannot be disabled.</p>
                                    </div>
                                    <div class="category-item analytics">
                                        <div class="category-header">
                                            <i class="fas fa-chart-line"></i>
                                            <h4>Analytics Cookies</h4>
                                            <span class="status optional">Optional</span>
                                        </div>
                                        <p>Help us understand how you use our website to improve performance.</p>
                                    </div>
                                    <div class="category-item marketing">
                                        <div class="category-header">
                                            <i class="fas fa-bullhorn"></i>
                                            <h4>Marketing Cookies</h4>
                                            <span class="status optional">Optional</span>
                                        </div>
                                        <p>Used to deliver personalized advertisements and measure campaign effectiveness.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cookies-section" id="contact-us">
                        <h2><i class="fas fa-envelope"></i> Contact Us</h2>
                        <div class="section-content">
                            <p>If you have any questions about our Cookie Policy or how we use cookies, please don't hesitate to contact us:</p>
                            <div class="contact-methods">
                                <div class="contact-method">
                                    <i class="fas fa-envelope"></i>
                                    <div>
                                        <strong>Email:</strong>
                                        <a href="mailto:privacy@velvetvogue.lk">privacy@velvetvogue.lk</a>
                                    </div>
                                </div>
                                <div class="contact-method">
                                    <i class="fas fa-phone"></i>
                                    <div>
                                        <strong>Phone:</strong>
                                        <a href="tel:+94112345678">+94 11 234 5678</a>
                                    </div>
                                </div>
                                <div class="contact-method">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <div>
                                        <strong>Address:</strong>
                                        123 Fashion Street, Colombo 03, Sri Lanka
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* Cookie Policy Styles */
.cookies-page {
    background: var(--background-color);
}

/* Cookies Hero */
.cookies-hero {
    background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
    color: var(--white);
    padding: 80px 0 60px;
    text-align: center;
}

.hero-content h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.hero-content h1 i {
    margin-right: 15px;
    color: #FFE66D;
}

.hero-content p {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 20px;
}

.last-updated {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.1);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
}

/* Cookies Content */
.cookies-content {
    padding: 80px 0;
}

.content-wrapper {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 40px;
}

/* Table of Contents */
.toc-sidebar {
    position: sticky;
    top: 100px;
    height: fit-content;
}

.toc-card {
    background: var(--white);
    padding: 30px;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
}

.toc-card h3 {
    color: var(--secondary-color);
    margin-bottom: 20px;
    font-size: 1.2rem;
}

.toc-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.toc-list li {
    margin-bottom: 8px;
}

.toc-list a {
    color: var(--text-color);
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 6px;
    display: block;
    transition: var(--transition);
    font-size: 0.9rem;
}

.toc-list a:hover,
.toc-list a.active {
    background: var(--background-color);
    color: var(--primary-color);
}

/* Main Content */
.cookies-main {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    overflow: hidden;
}

.cookies-section {
    padding: 40px;
    border-bottom: 1px solid var(--border-color);
}

.cookies-section:last-child {
    border-bottom: none;
}

.cookies-section h2 {
    color: var(--secondary-color);
    margin-bottom: 25px;
    font-size: 1.8rem;
    display: flex;
    align-items: center;
    gap: 12px;
}

.cookies-section h2 i {
    color: var(--primary-color);
    font-size: 1.5rem;
}

.section-content {
    line-height: 1.7;
}

.section-content h3 {
    color: var(--secondary-color);
    margin: 25px 0 15px 0;
    font-size: 1.3rem;
}

.section-content h4 {
    color: var(--secondary-color);
    margin: 20px 0 10px 0;
    font-size: 1.1rem;
}

.section-content ul {
    margin: 15px 0;
    padding-left: 25px;
}

.section-content li {
    margin-bottom: 8px;
    color: var(--text-light);
}

/* Info Box */
.info-box {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    background: #E3F2FD;
    border: 1px solid #BBDEFB;
    border-radius: var(--border-radius);
    padding: 20px;
    margin: 20px 0;
}

.info-box i {
    color: #1976D2;
    font-size: 1.5rem;
    margin-top: 2px;
}

.info-box h4 {
    margin: 0 0 10px 0;
    color: #1976D2;
}

.info-box ul {
    margin: 0;
    padding-left: 20px;
}

.info-box li {
    color: #1565C0;
}

/* Usage Grid */
.usage-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin: 25px 0;
}

.usage-item {
    text-align: center;
    padding: 25px 20px;
    background: var(--background-color);
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.usage-item:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-light);
}

.usage-item i {
    color: var(--primary-color);
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.usage-item h4 {
    margin: 0 0 10px 0;
    color: var(--secondary-color);
}

.usage-item p {
    margin: 0;
    color: var(--text-light);
    font-size: 0.9rem;
}

/* Cookie Types */
.cookie-types {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin: 25px 0;
}

.cookie-type {
    background: var(--background-color);
    border-radius: var(--border-radius);
    padding: 25px;
    border-left: 4px solid var(--primary-color);
}

.type-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
}

.type-header i {
    color: var(--primary-color);
    font-size: 1.5rem;
}

.type-header h3 {
    margin: 0;
    color: var(--secondary-color);
    font-size: 1.2rem;
}

.cookie-type p {
    margin: 0 0 15px 0;
    color: var(--text-light);
}

.examples {
    background: rgba(var(--primary-color-rgb), 0.1);
    padding: 10px 15px;
    border-radius: 6px;
    font-size: 0.9rem;
    color: var(--text-color);
}

/* Cookie Table */
.cookie-table {
    background: var(--background-color);
    border-radius: var(--border-radius);
    overflow: hidden;
    margin: 25px 0;
}

.table-header {
    display: grid;
    grid-template-columns: 1fr 2fr 1fr;
    background: var(--primary-color);
    color: var(--white);
    font-weight: 600;
    padding: 15px 20px;
}

.table-row {
    display: grid;
    grid-template-columns: 1fr 2fr 1fr;
    padding: 15px 20px;
    border-bottom: 1px solid var(--border-color);
    align-items: center;
}

.table-row:last-child {
    border-bottom: none;
}

.table-row code {
    background: #F5F5F5;
    padding: 4px 8px;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    color: #D32F2F;
}

/* Analytics Info */
.analytics-info {
    margin: 25px 0;
}

.analytics-provider {
    background: var(--background-color);
    border-radius: var(--border-radius);
    padding: 25px;
    border: 2px solid #4285F4;
}

.provider-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
}

.provider-header i {
    color: #4285F4;
    font-size: 1.5rem;
}

.provider-header h4 {
    margin: 0;
    color: var(--secondary-color);
}

.cookie-list {
    margin: 15px 0;
}

.cookie-item {
    background: var(--white);
    padding: 10px 15px;
    border-radius: 6px;
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.privacy-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #4285F4;
    text-decoration: none;
    font-weight: 500;
    margin-top: 15px;
}

.privacy-link:hover {
    text-decoration: underline;
}

/* Marketing Features */
.marketing-features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin: 25px 0;
}

.feature-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 20px;
    background: var(--background-color);
    border-radius: var(--border-radius);
}

.feature-item i {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-top: 5px;
}

.feature-item h4 {
    margin: 0 0 8px 0;
    color: var(--secondary-color);
}

.feature-item p {
    margin: 0;
    color: var(--text-light);
    font-size: 0.9rem;
}

.opt-out-info {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    background: #FFF3E0;
    border: 1px solid #FFE0B2;
    border-radius: var(--border-radius);
    padding: 15px;
    margin: 20px 0;
}

.opt-out-info i {
    color: #F57C00;
    font-size: 1.2rem;
    margin-top: 2px;
}

.opt-out-info p {
    margin: 0;
    color: #E65100;
}

.opt-out-info a {
    color: #F57C00;
}

/* Third Party Services */
.third-party-services {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin: 25px 0;
}

.service-card {
    background: var(--background-color);
    border-radius: var(--border-radius);
    padding: 25px;
    border-top: 3px solid var(--primary-color);
}

.service-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
}

.service-header i {
    color: var(--primary-color);
    font-size: 1.5rem;
}

.service-header h4 {
    margin: 0;
    color: var(--secondary-color);
}

.service-card ul {
    margin: 0;
    padding-left: 20px;
}

.service-card li {
    margin-bottom: 6px;
    color: var(--text-light);
    font-size: 0.9rem;
}

/* Management Options */
.management-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin: 25px 0;
}

.option-card {
    background: var(--background-color);
    border-radius: var(--border-radius);
    padding: 25px;
    border-left: 4px solid var(--primary-color);
}

.option-card i {
    color: var(--primary-color);
    font-size: 2rem;
    margin-bottom: 15px;
}

.option-card h4 {
    margin: 0 0 15px 0;
    color: var(--secondary-color);
}

.option-card p {
    margin: 0 0 15px 0;
    color: var(--text-light);
}

.option-card ul {
    margin: 0;
    padding-left: 20px;
}

.preference-controls {
    margin-top: 15px;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: var(--border-radius);
    cursor: pointer;
    font-weight: 500;
    text-decoration: none;
    display: inline-block;
    transition: var(--transition);
}

.btn-primary {
    background: var(--primary-color);
    color: var(--white);
}

.btn-primary:hover {
    background: var(--primary-dark);
}

/* Browser Guides */
.browser-guides {
    margin: 30px 0;
}

.browser-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.browser-item {
    text-align: center;
    padding: 20px 15px;
    background: var(--background-color);
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.browser-item:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-light);
}

.browser-item i {
    font-size: 2rem;
    margin-bottom: 10px;
    color: var(--primary-color);
}

.browser-item h4 {
    margin: 0 0 10px 0;
    color: var(--secondary-color);
}

.browser-item p {
    margin: 0;
    color: var(--text-light);
    font-size: 0.9rem;
}

/* Warning Box */
.warning-box {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    background: #FFEBEE;
    border: 1px solid #FFCDD2;
    border-radius: var(--border-radius);
    padding: 20px;
    margin: 25px 0;
}

.warning-box i {
    color: #D32F2F;
    font-size: 1.5rem;
    margin-top: 2px;
}

.warning-box h4 {
    margin: 0 0 10px 0;
    color: #D32F2F;
}

.warning-box p {
    margin: 0;
    color: #C62828;
}

/* Consent Process */
.consent-process {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin: 25px 0;
}

.process-step {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 20px;
    background: var(--background-color);
    border-radius: var(--border-radius);
}

.step-number {
    width: 40px;
    height: 40px;
    background: var(--primary-color);
    color: var(--white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.step-content h4 {
    margin: 0 0 8px 0;
    color: var(--secondary-color);
}

.step-content p {
    margin: 0;
    color: var(--text-light);
}

/* Consent Categories */
.consent-categories {
    margin: 30px 0;
}

.category-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin: 20px 0;
}

.category-item {
    background: var(--background-color);
    border-radius: var(--border-radius);
    padding: 20px;
    border-left: 4px solid;
}

.category-item.essential {
    border-left-color: #4CAF50;
}

.category-item.analytics {
    border-left-color: #2196F3;
}

.category-item.marketing {
    border-left-color: #FF9800;
}

.category-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}

.category-header div {
    display: flex;
    align-items: center;
    gap: 12px;
}

.category-header i {
    font-size: 1.3rem;
}

.category-item.essential .category-header i {
    color: #4CAF50;
}

.category-item.analytics .category-header i {
    color: #2196F3;
}

.category-item.marketing .category-header i {
    color: #FF9800;
}

.category-header h4 {
    margin: 0;
    color: var(--secondary-color);
}

.status {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status.required {
    background: #E8F5E8;
    color: #2E7D32;
}

.status.optional {
    background: #E3F2FD;
    color: #1565C0;
}

.category-item p {
    margin: 0;
    color: var(--text-light);
    font-size: 0.9rem;
}

/* Contact Methods */
.contact-methods {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin: 25px 0;
}

.contact-method {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: var(--background-color);
    border-radius: var(--border-radius);
}

.contact-method i {
    color: var(--primary-color);
    font-size: 1.3rem;
    width: 20px;
}

.contact-method a {
    color: var(--primary-color);
    text-decoration: none;
}

.contact-method a:hover {
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-content h1 {
        font-size: 2.5rem;
    }
    
    .content-wrapper {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .toc-sidebar {
        position: static;
        order: 2;
    }
    
    .cookies-section {
        padding: 30px 25px;
    }
    
    .usage-grid,
    .cookie-types,
    .marketing-features,
    .third-party-services,
    .management-options,
    .browser-grid {
        grid-template-columns: 1fr;
    }
    
    .table-header,
    .table-row {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .table-header {
        display: none;
    }
    
    .table-row {
        border: 1px solid var(--border-color);
        margin-bottom: 10px;
        border-radius: var(--border-radius);
    }
}

@media (max-width: 480px) {
    .cookies-hero {
        padding: 60px 0 40px;
    }
    
    .hero-content h1 {
        font-size: 2rem;
    }
    
    .cookies-content {
        padding: 60px 0;
    }
    
    .cookies-section {
        padding: 25px 20px;
    }
    
    .process-step {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
}
</style>

<script>
// Cookie preference management
function openCookiePreferences() {
    // This would open a cookie preference modal/panel
    alert('Cookie preference center would open here. This is a demo implementation.');
}

// Smooth scrolling for table of contents
document.addEventListener('DOMContentLoaded', function() {
    const tocLinks = document.querySelectorAll('.toc-list a');
    
    tocLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                const headerOffset = 100;
                const elementPosition = targetElement.offsetTop;
                const offsetPosition = elementPosition - headerOffset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
                
                // Update active link
                tocLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });
    
    // Highlight current section in TOC
    window.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('.cookies-section');
        const scrollPos = window.scrollY + 150;
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            const sectionId = section.getAttribute('id');
            
            if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                tocLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === '#' + sectionId) {
                        link.classList.add('active');
                    }
                });
            }
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>