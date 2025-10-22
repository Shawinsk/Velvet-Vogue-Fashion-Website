<?php
session_start();
$pageTitle = 'Privacy Policy';
include 'includes/header.php';
?>

<div class="privacy-page">
    <!-- Privacy Hero -->
    <section class="privacy-hero">
        <div class="container">
            <div class="hero-content">
                <h1><i class="fas fa-shield-alt"></i> Privacy Policy</h1>
                <p>Your privacy is important to us. Learn how we collect, use, and protect your information.</p>
                <div class="last-updated">
                    <i class="fas fa-calendar-alt"></i>
                    Last updated: <?php echo date('F j, Y'); ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Privacy Content -->
    <section class="privacy-content">
        <div class="container">
            <div class="content-wrapper">
                <!-- Table of Contents -->
                <div class="toc-sidebar">
                    <div class="toc-card">
                        <h3>Table of Contents</h3>
                        <ul class="toc-list">
                            <li><a href="#information-collection">Information We Collect</a></li>
                            <li><a href="#information-use">How We Use Information</a></li>
                            <li><a href="#information-sharing">Information Sharing</a></li>
                            <li><a href="#data-security">Data Security</a></li>
                            <li><a href="#cookies">Cookies & Tracking</a></li>
                            <li><a href="#user-rights">Your Rights</a></li>
                            <li><a href="#children-privacy">Children's Privacy</a></li>
                            <li><a href="#policy-changes">Policy Changes</a></li>
                            <li><a href="#contact-us">Contact Us</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="privacy-main">
                    <div class="privacy-section" id="information-collection">
                        <h2><i class="fas fa-database"></i> Information We Collect</h2>
                        <div class="section-content">
                            <h3>Personal Information</h3>
                            <p>We collect information you provide directly to us, including:</p>
                            <ul>
                                <li>Name, email address, and contact information</li>
                                <li>Billing and shipping addresses</li>
                                <li>Payment information (processed securely by our payment providers)</li>
                                <li>Account credentials and preferences</li>
                                <li>Communication preferences and marketing consents</li>
                            </ul>

                            <h3>Automatically Collected Information</h3>
                            <p>When you visit our website, we automatically collect:</p>
                            <ul>
                                <li>Device information (browser type, operating system)</li>
                                <li>Usage data (pages visited, time spent, click patterns)</li>
                                <li>IP address and location data</li>
                                <li>Cookies and similar tracking technologies</li>
                            </ul>
                        </div>
                    </div>

                    <div class="privacy-section" id="information-use">
                        <h2><i class="fas fa-cogs"></i> How We Use Your Information</h2>
                        <div class="section-content">
                            <p>We use the information we collect to:</p>
                            <div class="use-cases">
                                <div class="use-case">
                                    <i class="fas fa-shopping-cart"></i>
                                    <div>
                                        <h4>Process Orders</h4>
                                        <p>Fulfill purchases, process payments, and provide customer support</p>
                                    </div>
                                </div>
                                <div class="use-case">
                                    <i class="fas fa-user-cog"></i>
                                    <div>
                                        <h4>Account Management</h4>
                                        <p>Create and manage your account, preferences, and order history</p>
                                    </div>
                                </div>
                                <div class="use-case">
                                    <i class="fas fa-envelope"></i>
                                    <div>
                                        <h4>Communication</h4>
                                        <p>Send order updates, promotional emails, and respond to inquiries</p>
                                    </div>
                                </div>
                                <div class="use-case">
                                    <i class="fas fa-chart-line"></i>
                                    <div>
                                        <h4>Improve Services</h4>
                                        <p>Analyze usage patterns to enhance our website and services</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="privacy-section" id="information-sharing">
                        <h2><i class="fas fa-share-alt"></i> Information Sharing</h2>
                        <div class="section-content">
                            <p>We do not sell your personal information. We may share your information with:</p>
                            <div class="sharing-list">
                                <div class="sharing-item">
                                    <h4>Service Providers</h4>
                                    <p>Payment processors, shipping companies, and other trusted partners who help us operate our business</p>
                                </div>
                                <div class="sharing-item">
                                    <h4>Legal Requirements</h4>
                                    <p>When required by law, court order, or to protect our rights and safety</p>
                                </div>
                                <div class="sharing-item">
                                    <h4>Business Transfers</h4>
                                    <p>In connection with mergers, acquisitions, or sale of business assets</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="privacy-section" id="data-security">
                        <h2><i class="fas fa-lock"></i> Data Security</h2>
                        <div class="section-content">
                            <p>We implement appropriate security measures to protect your information:</p>
                            <div class="security-features">
                                <div class="security-feature">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>SSL encryption for data transmission</span>
                                </div>
                                <div class="security-feature">
                                    <i class="fas fa-server"></i>
                                    <span>Secure servers and databases</span>
                                </div>
                                <div class="security-feature">
                                    <i class="fas fa-key"></i>
                                    <span>Access controls and authentication</span>
                                </div>
                                <div class="security-feature">
                                    <i class="fas fa-eye"></i>
                                    <span>Regular security monitoring</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="privacy-section" id="cookies">
                        <h2><i class="fas fa-cookie-bite"></i> Cookies & Tracking</h2>
                        <div class="section-content">
                            <p>We use cookies and similar technologies to:</p>
                            <ul>
                                <li>Remember your preferences and login status</li>
                                <li>Analyze website traffic and user behavior</li>
                                <li>Provide personalized content and recommendations</li>
                                <li>Enable social media features and advertising</li>
                            </ul>
                            <p>You can control cookies through your browser settings. Note that disabling cookies may affect website functionality.</p>
                        </div>
                    </div>

                    <div class="privacy-section" id="user-rights">
                        <h2><i class="fas fa-user-shield"></i> Your Rights</h2>
                        <div class="section-content">
                            <p>You have the right to:</p>
                            <div class="rights-grid">
                                <div class="right-item">
                                    <i class="fas fa-eye"></i>
                                    <h4>Access</h4>
                                    <p>Request access to your personal information</p>
                                </div>
                                <div class="right-item">
                                    <i class="fas fa-edit"></i>
                                    <h4>Correct</h4>
                                    <p>Update or correct inaccurate information</p>
                                </div>
                                <div class="right-item">
                                    <i class="fas fa-trash"></i>
                                    <h4>Delete</h4>
                                    <p>Request deletion of your personal data</p>
                                </div>
                                <div class="right-item">
                                    <i class="fas fa-download"></i>
                                    <h4>Portability</h4>
                                    <p>Export your data in a portable format</p>
                                </div>
                                <div class="right-item">
                                    <i class="fas fa-ban"></i>
                                    <h4>Opt-out</h4>
                                    <p>Unsubscribe from marketing communications</p>
                                </div>
                                <div class="right-item">
                                    <i class="fas fa-pause"></i>
                                    <h4>Restrict</h4>
                                    <p>Limit how we process your information</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="privacy-section" id="children-privacy">
                        <h2><i class="fas fa-child"></i> Children's Privacy</h2>
                        <div class="section-content">
                            <p>Our services are not intended for children under 13. We do not knowingly collect personal information from children under 13. If you believe we have collected information from a child under 13, please contact us immediately.</p>
                        </div>
                    </div>

                    <div class="privacy-section" id="policy-changes">
                        <h2><i class="fas fa-sync-alt"></i> Policy Changes</h2>
                        <div class="section-content">
                            <p>We may update this Privacy Policy from time to time. We will notify you of any material changes by:</p>
                            <ul>
                                <li>Posting the updated policy on our website</li>
                                <li>Sending email notifications to registered users</li>
                                <li>Displaying prominent notices on our website</li>
                            </ul>
                        </div>
                    </div>

                    <div class="privacy-section" id="contact-us">
                        <h2><i class="fas fa-envelope"></i> Contact Us</h2>
                        <div class="section-content">
                            <p>If you have questions about this Privacy Policy or our privacy practices, please contact us:</p>
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
/* Privacy Policy Styles */
.privacy-page {
    background: var(--background-color);
}

/* Privacy Hero */
.privacy-hero {
    background: linear-gradient(135deg, var(--secondary-color), var(--primary-dark));
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
    color: #4CAF50;
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

/* Privacy Content */
.privacy-content {
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
    margin-bottom: 10px;
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

.toc-list a:hover {
    background: var(--background-color);
    color: var(--primary-color);
}

/* Main Content */
.privacy-main {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    overflow: hidden;
}

.privacy-section {
    padding: 40px;
    border-bottom: 1px solid var(--border-color);
}

.privacy-section:last-child {
    border-bottom: none;
}

.privacy-section h2 {
    color: var(--secondary-color);
    margin-bottom: 25px;
    font-size: 1.8rem;
    display: flex;
    align-items: center;
    gap: 12px;
}

.privacy-section h2 i {
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

/* Use Cases */
.use-cases {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin: 25px 0;
}

.use-case {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 20px;
    background: var(--background-color);
    border-radius: var(--border-radius);
}

.use-case i {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-top: 5px;
}

.use-case h4 {
    margin: 0 0 8px 0;
    color: var(--secondary-color);
}

.use-case p {
    margin: 0;
    color: var(--text-light);
    font-size: 0.9rem;
}

/* Sharing List */
.sharing-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin: 25px 0;
}

.sharing-item {
    padding: 20px;
    background: var(--background-color);
    border-radius: var(--border-radius);
    border-left: 4px solid var(--primary-color);
}

.sharing-item h4 {
    margin: 0 0 10px 0;
    color: var(--secondary-color);
}

.sharing-item p {
    margin: 0;
    color: var(--text-light);
}

/* Security Features */
.security-features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin: 25px 0;
}

.security-feature {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    background: var(--background-color);
    border-radius: var(--border-radius);
}

.security-feature i {
    color: #4CAF50;
    font-size: 1.2rem;
}

/* Rights Grid */
.rights-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 25px 0;
}

.right-item {
    text-align: center;
    padding: 25px 15px;
    background: var(--background-color);
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.right-item:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-light);
}

.right-item i {
    color: var(--primary-color);
    font-size: 2rem;
    margin-bottom: 15px;
}

.right-item h4 {
    margin: 0 0 10px 0;
    color: var(--secondary-color);
}

.right-item p {
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
    
    .privacy-section {
        padding: 30px 25px;
    }
    
    .use-cases,
    .rights-grid,
    .security-features {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .privacy-hero {
        padding: 60px 0 40px;
    }
    
    .hero-content h1 {
        font-size: 2rem;
    }
    
    .privacy-content {
        padding: 60px 0;
    }
    
    .privacy-section {
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
        const sections = document.querySelectorAll('.privacy-section');
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