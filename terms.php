<?php
session_start();
$pageTitle = 'Terms of Service';
include 'includes/header.php';
?>

<div class="terms-page">
    <!-- Terms Hero -->
    <section class="terms-hero">
        <div class="container">
            <div class="hero-content">
                <h1><i class="fas fa-file-contract"></i> Terms of Service</h1>
                <p>Please read these terms carefully before using our services.</p>
                <div class="last-updated">
                    <i class="fas fa-calendar-alt"></i>
                    Last updated: <?php echo date('F j, Y'); ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Terms Content -->
    <section class="terms-content">
        <div class="container">
            <div class="content-wrapper">
                <!-- Table of Contents -->
                <div class="toc-sidebar">
                    <div class="toc-card">
                        <h3>Table of Contents</h3>
                        <ul class="toc-list">
                            <li><a href="#acceptance">Acceptance of Terms</a></li>
                            <li><a href="#description">Service Description</a></li>
                            <li><a href="#account">Account Registration</a></li>
                            <li><a href="#orders">Orders & Payment</a></li>
                            <li><a href="#shipping">Shipping & Delivery</a></li>
                            <li><a href="#returns">Returns & Refunds</a></li>
                            <li><a href="#intellectual-property">Intellectual Property</a></li>
                            <li><a href="#user-conduct">User Conduct</a></li>
                            <li><a href="#privacy">Privacy Policy</a></li>
                            <li><a href="#disclaimers">Disclaimers</a></li>
                            <li><a href="#limitation">Limitation of Liability</a></li>
                            <li><a href="#termination">Termination</a></li>
                            <li><a href="#governing-law">Governing Law</a></li>
                            <li><a href="#contact">Contact Information</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="terms-main">
                    <div class="terms-section" id="acceptance">
                        <h2><i class="fas fa-handshake"></i> Acceptance of Terms</h2>
                        <div class="section-content">
                            <p>By accessing and using the Velvet Vogue website ("Service"), you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>
                            <div class="highlight-box">
                                <i class="fas fa-exclamation-triangle"></i>
                                <p><strong>Important:</strong> These terms constitute a legally binding agreement between you and Velvet Vogue. Please read them carefully.</p>
                            </div>
                        </div>
                    </div>

                    <div class="terms-section" id="description">
                        <h2><i class="fas fa-store"></i> Service Description</h2>
                        <div class="section-content">
                            <p>Velvet Vogue is an online fashion retailer specializing in women's clothing and accessories. Our services include:</p>
                            <div class="service-grid">
                                <div class="service-item">
                                    <i class="fas fa-tshirt"></i>
                                    <h4>Fashion Retail</h4>
                                    <p>Sale of clothing, accessories, and fashion items</p>
                                </div>
                                <div class="service-item">
                                    <i class="fas fa-shipping-fast"></i>
                                    <h4>Delivery Services</h4>
                                    <p>Shipping and delivery of purchased items</p>
                                </div>
                                <div class="service-item">
                                    <i class="fas fa-user-circle"></i>
                                    <h4>Customer Accounts</h4>
                                    <p>Personal accounts for order tracking and preferences</p>
                                </div>
                                <div class="service-item">
                                    <i class="fas fa-headset"></i>
                                    <h4>Customer Support</h4>
                                    <p>Assistance with orders, returns, and inquiries</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="terms-section" id="account">
                        <h2><i class="fas fa-user-plus"></i> Account Registration</h2>
                        <div class="section-content">
                            <h3>Account Creation</h3>
                            <p>To access certain features of our service, you may be required to create an account. You agree to:</p>
                            <ul>
                                <li>Provide accurate, current, and complete information</li>
                                <li>Maintain and update your information to keep it accurate</li>
                                <li>Maintain the security of your password and account</li>
                                <li>Accept responsibility for all activities under your account</li>
                                <li>Notify us immediately of any unauthorized use</li>
                            </ul>

                            <h3>Account Responsibilities</h3>
                            <div class="responsibility-cards">
                                <div class="responsibility-card">
                                    <i class="fas fa-shield-alt"></i>
                                    <h4>Security</h4>
                                    <p>Keep your login credentials secure and confidential</p>
                                </div>
                                <div class="responsibility-card">
                                    <i class="fas fa-sync-alt"></i>
                                    <h4>Updates</h4>
                                    <p>Keep your account information current and accurate</p>
                                </div>
                                <div class="responsibility-card">
                                    <i class="fas fa-bell"></i>
                                    <h4>Notifications</h4>
                                    <p>Report any suspicious activity immediately</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="terms-section" id="orders">
                        <h2><i class="fas fa-shopping-cart"></i> Orders & Payment</h2>
                        <div class="section-content">
                            <h3>Order Process</h3>
                            <p>When you place an order through our service:</p>
                            <ol>
                                <li>You make an offer to purchase products at the listed price</li>
                                <li>We send you an order confirmation email</li>
                                <li>A contract is formed when we accept your order</li>
                                <li>We reserve the right to refuse or cancel orders</li>
                            </ol>

                            <h3>Pricing and Payment</h3>
                            <div class="payment-info">
                                <div class="payment-item">
                                    <i class="fas fa-tag"></i>
                                    <div>
                                        <h4>Pricing</h4>
                                        <p>All prices are in Sri Lankan Rupees (LKR) and include applicable taxes unless otherwise stated</p>
                                    </div>
                                </div>
                                <div class="payment-item">
                                    <i class="fas fa-credit-card"></i>
                                    <div>
                                        <h4>Payment Methods</h4>
                                        <p>We accept major credit cards, debit cards, and other payment methods as displayed at checkout</p>
                                    </div>
                                </div>
                                <div class="payment-item">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <div>
                                        <h4>Payment Authorization</h4>
                                        <p>Payment must be authorized before order processing begins</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="terms-section" id="shipping">
                        <h2><i class="fas fa-truck"></i> Shipping & Delivery</h2>
                        <div class="section-content">
                            <h3>Shipping Policy</h3>
                            <p>We strive to process and ship orders promptly:</p>
                            <div class="shipping-timeline">
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h4>Processing Time</h4>
                                        <p>1-2 business days for order processing</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                        <i class="fas fa-shipping-fast"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h4>Shipping Time</h4>
                                        <p>3-7 business days for standard delivery</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                        <i class="fas fa-home"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h4>Delivery</h4>
                                        <p>Delivered to your specified address</p>
                                    </div>
                                </div>
                            </div>

                            <h3>Delivery Terms</h3>
                            <ul>
                                <li>Delivery times are estimates and not guaranteed</li>
                                <li>Risk of loss transfers to you upon delivery</li>
                                <li>You must provide accurate delivery information</li>
                                <li>Additional charges may apply for remote areas</li>
                            </ul>
                        </div>
                    </div>

                    <div class="terms-section" id="returns">
                        <h2><i class="fas fa-undo"></i> Returns & Refunds</h2>
                        <div class="section-content">
                            <h3>Return Policy</h3>
                            <p>We want you to be satisfied with your purchase. Our return policy includes:</p>
                            <div class="return-conditions">
                                <div class="condition-card">
                                    <i class="fas fa-calendar-check"></i>
                                    <h4>30-Day Window</h4>
                                    <p>Items can be returned within 30 days of delivery</p>
                                </div>
                                <div class="condition-card">
                                    <i class="fas fa-tags"></i>
                                    <h4>Original Condition</h4>
                                    <p>Items must be unworn with original tags attached</p>
                                </div>
                                <div class="condition-card">
                                    <i class="fas fa-receipt"></i>
                                    <h4>Proof of Purchase</h4>
                                    <p>Original receipt or order confirmation required</p>
                                </div>
                            </div>

                            <h3>Refund Process</h3>
                            <p>Refunds will be processed to the original payment method within 5-10 business days after we receive and inspect the returned items.</p>
                        </div>
                    </div>

                    <div class="terms-section" id="intellectual-property">
                        <h2><i class="fas fa-copyright"></i> Intellectual Property</h2>
                        <div class="section-content">
                            <p>All content on this website, including but not limited to:</p>
                            <div class="ip-grid">
                                <div class="ip-item">
                                    <i class="fas fa-image"></i>
                                    <span>Images and Graphics</span>
                                </div>
                                <div class="ip-item">
                                    <i class="fas fa-font"></i>
                                    <span>Text and Copy</span>
                                </div>
                                <div class="ip-item">
                                    <i class="fas fa-code"></i>
                                    <span>Software and Code</span>
                                </div>
                                <div class="ip-item">
                                    <i class="fas fa-trademark"></i>
                                    <span>Trademarks and Logos</span>
                                </div>
                            </div>
                            <p>is the property of Velvet Vogue and is protected by copyright and other intellectual property laws. You may not reproduce, distribute, or create derivative works without our express written permission.</p>
                        </div>
                    </div>

                    <div class="terms-section" id="user-conduct">
                        <h2><i class="fas fa-user-check"></i> User Conduct</h2>
                        <div class="section-content">
                            <p>You agree not to use our service to:</p>
                            <div class="conduct-list">
                                <div class="conduct-item prohibited">
                                    <i class="fas fa-times-circle"></i>
                                    <span>Violate any laws or regulations</span>
                                </div>
                                <div class="conduct-item prohibited">
                                    <i class="fas fa-times-circle"></i>
                                    <span>Infringe on intellectual property rights</span>
                                </div>
                                <div class="conduct-item prohibited">
                                    <i class="fas fa-times-circle"></i>
                                    <span>Transmit harmful or malicious content</span>
                                </div>
                                <div class="conduct-item prohibited">
                                    <i class="fas fa-times-circle"></i>
                                    <span>Interfere with service operation</span>
                                </div>
                                <div class="conduct-item prohibited">
                                    <i class="fas fa-times-circle"></i>
                                    <span>Impersonate others or provide false information</span>
                                </div>
                                <div class="conduct-item prohibited">
                                    <i class="fas fa-times-circle"></i>
                                    <span>Engage in fraudulent activities</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="terms-section" id="privacy">
                        <h2><i class="fas fa-user-shield"></i> Privacy Policy</h2>
                        <div class="section-content">
                            <p>Your privacy is important to us. Our Privacy Policy explains how we collect, use, and protect your information when you use our service.</p>
                            <div class="privacy-link-card">
                                <i class="fas fa-external-link-alt"></i>
                                <div>
                                    <h4>Read Our Privacy Policy</h4>
                                    <p>For detailed information about our privacy practices</p>
                                    <a href="privacy.php" class="btn btn-outline">View Privacy Policy</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="terms-section" id="disclaimers">
                        <h2><i class="fas fa-exclamation-triangle"></i> Disclaimers</h2>
                        <div class="section-content">
                            <div class="disclaimer-box">
                                <h3>Service "As Is"</h3>
                                <p>Our service is provided "as is" without warranties of any kind, either express or implied, including but not limited to warranties of merchantability, fitness for a particular purpose, or non-infringement.</p>
                            </div>
                            <div class="disclaimer-box">
                                <h3>Product Information</h3>
                                <p>We strive for accuracy in product descriptions and images, but we do not warrant that product descriptions or other content is accurate, complete, reliable, current, or error-free.</p>
                            </div>
                        </div>
                    </div>

                    <div class="terms-section" id="limitation">
                        <h2><i class="fas fa-shield-alt"></i> Limitation of Liability</h2>
                        <div class="section-content">
                            <p>To the fullest extent permitted by law, Velvet Vogue shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to:</p>
                            <ul>
                                <li>Loss of profits or revenue</li>
                                <li>Loss of data or information</li>
                                <li>Business interruption</li>
                                <li>Personal injury or property damage</li>
                            </ul>
                            <p>Our total liability shall not exceed the amount you paid for the specific product or service that gave rise to the claim.</p>
                        </div>
                    </div>

                    <div class="terms-section" id="termination">
                        <h2><i class="fas fa-power-off"></i> Termination</h2>
                        <div class="section-content">
                            <h3>Termination by You</h3>
                            <p>You may terminate your account at any time by contacting our customer service or using the account deletion feature.</p>
                            
                            <h3>Termination by Us</h3>
                            <p>We may terminate or suspend your account immediately, without prior notice, for conduct that we believe:</p>
                            <ul>
                                <li>Violates these Terms of Service</li>
                                <li>Is harmful to other users or our business</li>
                                <li>Violates applicable laws or regulations</li>
                                <li>Is fraudulent or involves unauthorized use</li>
                            </ul>
                        </div>
                    </div>

                    <div class="terms-section" id="governing-law">
                        <h2><i class="fas fa-gavel"></i> Governing Law</h2>
                        <div class="section-content">
                            <p>These Terms of Service shall be governed by and construed in accordance with the laws of Sri Lanka, without regard to its conflict of law provisions.</p>
                            <p>Any disputes arising from these terms or your use of our service shall be subject to the exclusive jurisdiction of the courts of Sri Lanka.</p>
                        </div>
                    </div>

                    <div class="terms-section" id="contact">
                        <h2><i class="fas fa-envelope"></i> Contact Information</h2>
                        <div class="section-content">
                            <p>If you have any questions about these Terms of Service, please contact us:</p>
                            <div class="contact-methods">
                                <div class="contact-method">
                                    <i class="fas fa-envelope"></i>
                                    <div>
                                        <strong>Email:</strong>
                                        <a href="mailto:legal@velvetvogue.lk">legal@velvetvogue.lk</a>
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
/* Terms of Service Styles */
.terms-page {
    background: var(--background-color);
}

/* Terms Hero */
.terms-hero {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
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
    color: #FFD700;
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

/* Terms Content */
.terms-content {
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
.terms-main {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    overflow: hidden;
}

.terms-section {
    padding: 40px;
    border-bottom: 1px solid var(--border-color);
}

.terms-section:last-child {
    border-bottom: none;
}

.terms-section h2 {
    color: var(--secondary-color);
    margin-bottom: 25px;
    font-size: 1.8rem;
    display: flex;
    align-items: center;
    gap: 12px;
}

.terms-section h2 i {
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

.section-content ul,
.section-content ol {
    margin: 15px 0;
    padding-left: 25px;
}

.section-content li {
    margin-bottom: 8px;
    color: var(--text-light);
}

/* Highlight Box */
.highlight-box {
    background: #FFF3CD;
    border: 1px solid #FFEAA7;
    border-radius: var(--border-radius);
    padding: 20px;
    margin: 20px 0;
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.highlight-box i {
    color: #856404;
    font-size: 1.3rem;
    margin-top: 2px;
}

.highlight-box p {
    margin: 0;
    color: #856404;
}

/* Service Grid */
.service-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin: 25px 0;
}

.service-item {
    text-align: center;
    padding: 25px 20px;
    background: var(--background-color);
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.service-item:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-light);
}

.service-item i {
    color: var(--primary-color);
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.service-item h4 {
    margin: 0 0 10px 0;
    color: var(--secondary-color);
}

.service-item p {
    margin: 0;
    color: var(--text-light);
    font-size: 0.9rem;
}

/* Responsibility Cards */
.responsibility-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 25px 0;
}

.responsibility-card {
    text-align: center;
    padding: 25px 15px;
    background: var(--background-color);
    border-radius: var(--border-radius);
    border-top: 3px solid var(--primary-color);
}

.responsibility-card i {
    color: var(--primary-color);
    font-size: 2rem;
    margin-bottom: 15px;
}

.responsibility-card h4 {
    margin: 0 0 10px 0;
    color: var(--secondary-color);
}

.responsibility-card p {
    margin: 0;
    color: var(--text-light);
    font-size: 0.9rem;
}

/* Payment Info */
.payment-info {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin: 25px 0;
}

.payment-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 20px;
    background: var(--background-color);
    border-radius: var(--border-radius);
}

.payment-item i {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-top: 5px;
}

.payment-item h4 {
    margin: 0 0 8px 0;
    color: var(--secondary-color);
}

.payment-item p {
    margin: 0;
    color: var(--text-light);
}

/* Shipping Timeline */
.shipping-timeline {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin: 25px 0;
}

.timeline-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background: var(--background-color);
    border-radius: var(--border-radius);
    position: relative;
}

.timeline-icon {
    width: 50px;
    height: 50px;
    background: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 1.2rem;
    flex-shrink: 0;
}

.timeline-content h4 {
    margin: 0 0 8px 0;
    color: var(--secondary-color);
}

.timeline-content p {
    margin: 0;
    color: var(--text-light);
}

/* Return Conditions */
.return-conditions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 25px 0;
}

.condition-card {
    text-align: center;
    padding: 25px 15px;
    background: var(--background-color);
    border-radius: var(--border-radius);
    border: 2px solid transparent;
    transition: var(--transition);
}

.condition-card:hover {
    border-color: var(--primary-color);
    transform: translateY(-3px);
}

.condition-card i {
    color: var(--primary-color);
    font-size: 2rem;
    margin-bottom: 15px;
}

.condition-card h4 {
    margin: 0 0 10px 0;
    color: var(--secondary-color);
}

.condition-card p {
    margin: 0;
    color: var(--text-light);
    font-size: 0.9rem;
}

/* IP Grid */
.ip-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin: 20px 0;
}

.ip-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    background: var(--background-color);
    border-radius: var(--border-radius);
}

.ip-item i {
    color: var(--primary-color);
    font-size: 1.2rem;
}

/* Conduct List */
.conduct-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
    margin: 25px 0;
}

.conduct-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    border-radius: var(--border-radius);
}

.conduct-item.prohibited {
    background: #FFEBEE;
    border-left: 4px solid #F44336;
}

.conduct-item.prohibited i {
    color: #F44336;
}

.conduct-item span {
    color: var(--text-color);
}

/* Privacy Link Card */
.privacy-link-card {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 25px;
    background: var(--background-color);
    border-radius: var(--border-radius);
    border: 2px solid var(--primary-color);
    margin: 25px 0;
}

.privacy-link-card i {
    color: var(--primary-color);
    font-size: 2rem;
}

.privacy-link-card h4 {
    margin: 0 0 8px 0;
    color: var(--secondary-color);
}

.privacy-link-card p {
    margin: 0 0 15px 0;
    color: var(--text-light);
}

.btn.btn-outline {
    background: transparent;
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
    padding: 10px 20px;
    border-radius: var(--border-radius);
    text-decoration: none;
    transition: var(--transition);
    display: inline-block;
}

.btn.btn-outline:hover {
    background: var(--primary-color);
    color: var(--white);
}

/* Disclaimer Boxes */
.disclaimer-box {
    background: #F8F9FA;
    border-left: 4px solid var(--primary-color);
    padding: 20px;
    margin: 20px 0;
    border-radius: 0 var(--border-radius) var(--border-radius) 0;
}

.disclaimer-box h3 {
    margin: 0 0 15px 0;
    color: var(--secondary-color);
    font-size: 1.2rem;
}

.disclaimer-box p {
    margin: 0;
    color: var(--text-light);
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
    
    .terms-section {
        padding: 30px 25px;
    }
    
    .service-grid,
    .responsibility-cards,
    .return-conditions,
    .ip-grid,
    .conduct-list {
        grid-template-columns: 1fr;
    }
    
    .shipping-timeline {
        gap: 15px;
    }
    
    .timeline-item {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .privacy-link-card {
        flex-direction: column;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .terms-hero {
        padding: 60px 0 40px;
    }
    
    .hero-content h1 {
        font-size: 2rem;
    }
    
    .terms-content {
        padding: 60px 0;
    }
    
    .terms-section {
        padding: 25px 20px;
    }
}
</style>

<script>
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
        const sections = document.querySelectorAll('.terms-section');
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