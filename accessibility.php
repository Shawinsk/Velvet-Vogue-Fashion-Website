<?php
session_start();
$pageTitle = 'Accessibility Statement';
include 'includes/header.php';
?>

<div class="accessibility-page">
    <!-- Accessibility Hero -->
    <section class="accessibility-hero">
        <div class="container">
            <div class="hero-content">
                <h1><i class="fas fa-universal-access"></i> Accessibility Statement</h1>
                <p>We are committed to ensuring our website is accessible to everyone.</p>
                <div class="last-updated">
                    <i class="fas fa-calendar-alt"></i>
                    Last updated: <?php echo date('F j, Y'); ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Accessibility Content -->
    <section class="accessibility-content">
        <div class="container">
            <div class="content-wrapper">
                <!-- Table of Contents -->
                <div class="toc-sidebar">
                    <div class="toc-card">
                        <h3>Table of Contents</h3>
                        <ul class="toc-list">
                            <li><a href="#commitment">Our Commitment</a></li>
                            <li><a href="#standards">Accessibility Standards</a></li>
                            <li><a href="#features">Accessibility Features</a></li>
                            <li><a href="#assistive-technologies">Assistive Technologies</a></li>
                            <li><a href="#keyboard-navigation">Keyboard Navigation</a></li>
                            <li><a href="#screen-readers">Screen Reader Support</a></li>
                            <li><a href="#visual-accessibility">Visual Accessibility</a></li>
                            <li><a href="#known-issues">Known Issues</a></li>
                            <li><a href="#feedback">Feedback & Support</a></li>
                            <li><a href="#contact">Contact Information</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="accessibility-main">
                    <div class="accessibility-section" id="commitment">
                        <h2><i class="fas fa-heart"></i> Our Commitment</h2>
                        <div class="section-content">
                            <p>At Velvet Vogue, we believe that fashion should be accessible to everyone. We are committed to providing an inclusive online shopping experience that enables all users, regardless of their abilities or disabilities, to navigate, understand, and interact with our website effectively.</p>
                            
                            <div class="commitment-principles">
                                <div class="principle-card">
                                    <i class="fas fa-users"></i>
                                    <h4>Inclusive Design</h4>
                                    <p>We design with accessibility in mind from the start, ensuring our website works for users with diverse needs and abilities.</p>
                                </div>
                                <div class="principle-card">
                                    <i class="fas fa-sync-alt"></i>
                                    <h4>Continuous Improvement</h4>
                                    <p>We regularly review and update our website to improve accessibility and address any barriers that may exist.</p>
                                </div>
                                <div class="principle-card">
                                    <i class="fas fa-comments"></i>
                                    <h4>User Feedback</h4>
                                    <p>We actively seek feedback from our users to identify and resolve accessibility issues.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accessibility-section" id="standards">
                        <h2><i class="fas fa-check-circle"></i> Accessibility Standards</h2>
                        <div class="section-content">
                            <p>Our website aims to conform to the Web Content Accessibility Guidelines (WCAG) 2.1 Level AA standards. These guidelines explain how to make web content more accessible to people with disabilities.</p>
                            
                            <div class="standards-grid">
                                <div class="standard-item">
                                    <div class="standard-header">
                                        <i class="fas fa-eye"></i>
                                        <h4>Perceivable</h4>
                                    </div>
                                    <p>Information and user interface components must be presentable to users in ways they can perceive.</p>
                                    <ul>
                                        <li>Text alternatives for images</li>
                                        <li>Captions for videos</li>
                                        <li>Sufficient color contrast</li>
                                        <li>Resizable text</li>
                                    </ul>
                                </div>
                                
                                <div class="standard-item">
                                    <div class="standard-header">
                                        <i class="fas fa-hand-pointer"></i>
                                        <h4>Operable</h4>
                                    </div>
                                    <p>User interface components and navigation must be operable by all users.</p>
                                    <ul>
                                        <li>Keyboard accessible</li>
                                        <li>No seizure-inducing content</li>
                                        <li>Sufficient time limits</li>
                                        <li>Clear navigation</li>
                                    </ul>
                                </div>
                                
                                <div class="standard-item">
                                    <div class="standard-header">
                                        <i class="fas fa-brain"></i>
                                        <h4>Understandable</h4>
                                    </div>
                                    <p>Information and the operation of user interface must be understandable.</p>
                                    <ul>
                                        <li>Readable text</li>
                                        <li>Predictable functionality</li>
                                        <li>Input assistance</li>
                                        <li>Error identification</li>
                                    </ul>
                                </div>
                                
                                <div class="standard-item">
                                    <div class="standard-header">
                                        <i class="fas fa-cogs"></i>
                                        <h4>Robust</h4>
                                    </div>
                                    <p>Content must be robust enough to be interpreted by a wide variety of user agents.</p>
                                    <ul>
                                        <li>Compatible with assistive technologies</li>
                                        <li>Valid HTML markup</li>
                                        <li>Future-proof code</li>
                                        <li>Cross-browser compatibility</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accessibility-section" id="features">
                        <h2><i class="fas fa-star"></i> Accessibility Features</h2>
                        <div class="section-content">
                            <p>Our website includes numerous accessibility features to ensure a smooth experience for all users:</p>
                            
                            <div class="features-list">
                                <div class="feature-category">
                                    <h3><i class="fas fa-keyboard"></i> Navigation & Interaction</h3>
                                    <div class="feature-items">
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>Full keyboard navigation support</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>Skip navigation links</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>Focus indicators for interactive elements</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>Logical tab order</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="feature-category">
                                    <h3><i class="fas fa-eye"></i> Visual Design</h3>
                                    <div class="feature-items">
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>High contrast color schemes</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>Scalable text up to 200%</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>Clear visual hierarchy</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>Consistent layout and design</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="feature-category">
                                    <h3><i class="fas fa-volume-up"></i> Content & Media</h3>
                                    <div class="feature-items">
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>Alternative text for all images</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>Descriptive link text</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>Clear headings and structure</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>Form labels and instructions</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="feature-category">
                                    <h3><i class="fas fa-mobile-alt"></i> Responsive Design</h3>
                                    <div class="feature-items">
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>Mobile-friendly responsive design</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>Touch-friendly interface</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>Flexible layouts</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>Optimized for various screen sizes</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accessibility-section" id="assistive-technologies">
                        <h2><i class="fas fa-assistive-listening-systems"></i> Assistive Technologies</h2>
                        <div class="section-content">
                            <p>Our website is designed to work with various assistive technologies to ensure accessibility for users with different needs:</p>
                            
                            <div class="assistive-tech-grid">
                                <div class="tech-card">
                                    <i class="fas fa-volume-up"></i>
                                    <h4>Screen Readers</h4>
                                    <p>Compatible with popular screen readers including JAWS, NVDA, and VoiceOver</p>
                                    <div class="compatibility-list">
                                        <span class="compatible">JAWS</span>
                                        <span class="compatible">NVDA</span>
                                        <span class="compatible">VoiceOver</span>
                                        <span class="compatible">TalkBack</span>
                                    </div>
                                </div>
                                
                                <div class="tech-card">
                                    <i class="fas fa-keyboard"></i>
                                    <h4>Keyboard Navigation</h4>
                                    <p>Full functionality available through keyboard-only navigation</p>
                                    <div class="keyboard-shortcuts">
                                        <div class="shortcut"><kbd>Tab</kbd> Navigate forward</div>
                                        <div class="shortcut"><kbd>Shift+Tab</kbd> Navigate backward</div>
                                        <div class="shortcut"><kbd>Enter</kbd> Activate links/buttons</div>
                                        <div class="shortcut"><kbd>Space</kbd> Activate buttons</div>
                                    </div>
                                </div>
                                
                                <div class="tech-card">
                                    <i class="fas fa-mouse-pointer"></i>
                                    <h4>Voice Control</h4>
                                    <p>Works with voice control software like Dragon NaturallySpeaking</p>
                                    <div class="voice-features">
                                        <div class="feature">Voice navigation</div>
                                        <div class="feature">Voice commands</div>
                                        <div class="feature">Dictation support</div>
                                    </div>
                                </div>
                                
                                <div class="tech-card">
                                    <i class="fas fa-search-plus"></i>
                                    <h4>Magnification</h4>
                                    <p>Compatible with screen magnification software and browser zoom</p>
                                    <div class="magnification-features">
                                        <div class="feature">Up to 400% zoom</div>
                                        <div class="feature">Maintains functionality</div>
                                        <div class="feature">Responsive layout</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accessibility-section" id="keyboard-navigation">
                        <h2><i class="fas fa-keyboard"></i> Keyboard Navigation</h2>
                        <div class="section-content">
                            <p>Our website can be fully navigated using only a keyboard. Here's how to navigate effectively:</p>
                            
                            <div class="navigation-guide">
                                <div class="guide-section">
                                    <h3>Basic Navigation</h3>
                                    <div class="keyboard-instructions">
                                        <div class="instruction">
                                            <div class="keys">
                                                <kbd>Tab</kbd>
                                            </div>
                                            <div class="description">
                                                <strong>Move Forward:</strong> Navigate to the next interactive element (links, buttons, form fields)
                                            </div>
                                        </div>
                                        <div class="instruction">
                                            <div class="keys">
                                                <kbd>Shift</kbd> + <kbd>Tab</kbd>
                                            </div>
                                            <div class="description">
                                                <strong>Move Backward:</strong> Navigate to the previous interactive element
                                            </div>
                                        </div>
                                        <div class="instruction">
                                            <div class="keys">
                                                <kbd>Enter</kbd>
                                            </div>
                                            <div class="description">
                                                <strong>Activate:</strong> Follow links or activate buttons
                                            </div>
                                        </div>
                                        <div class="instruction">
                                            <div class="keys">
                                                <kbd>Space</kbd>
                                            </div>
                                            <div class="description">
                                                <strong>Select:</strong> Activate buttons or toggle checkboxes
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="guide-section">
                                    <h3>Form Navigation</h3>
                                    <div class="keyboard-instructions">
                                        <div class="instruction">
                                            <div class="keys">
                                                <kbd>Arrow Keys</kbd>
                                            </div>
                                            <div class="description">
                                                <strong>Radio Buttons:</strong> Select options in radio button groups
                                            </div>
                                        </div>
                                        <div class="instruction">
                                            <div class="keys">
                                                <kbd>Space</kbd>
                                            </div>
                                            <div class="description">
                                                <strong>Checkboxes:</strong> Check or uncheck checkbox options
                                            </div>
                                        </div>
                                        <div class="instruction">
                                            <div class="keys">
                                                <kbd>Alt</kbd> + <kbd>↓</kbd>
                                            </div>
                                            <div class="description">
                                                <strong>Dropdowns:</strong> Open dropdown menus
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="guide-section">
                                    <h3>Page Navigation</h3>
                                    <div class="keyboard-instructions">
                                        <div class="instruction">
                                            <div class="keys">
                                                <kbd>Home</kbd>
                                            </div>
                                            <div class="description">
                                                <strong>Page Top:</strong> Jump to the beginning of the page
                                            </div>
                                        </div>
                                        <div class="instruction">
                                            <div class="keys">
                                                <kbd>End</kbd>
                                            </div>
                                            <div class="description">
                                                <strong>Page Bottom:</strong> Jump to the end of the page
                                            </div>
                                        </div>
                                        <div class="instruction">
                                            <div class="keys">
                                                <kbd>Page Up</kbd> / <kbd>Page Down</kbd>
                                            </div>
                                            <div class="description">
                                                <strong>Scroll:</strong> Move up or down the page
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accessibility-section" id="screen-readers">
                        <h2><i class="fas fa-volume-up"></i> Screen Reader Support</h2>
                        <div class="section-content">
                            <p>Our website is optimized for screen reader users with proper semantic markup and ARIA labels:</p>
                            
                            <div class="screen-reader-features">
                                <div class="sr-feature">
                                    <i class="fas fa-heading"></i>
                                    <div>
                                        <h4>Proper Heading Structure</h4>
                                        <p>Logical heading hierarchy (H1-H6) for easy navigation and content understanding</p>
                                    </div>
                                </div>
                                
                                <div class="sr-feature">
                                    <i class="fas fa-tags"></i>
                                    <div>
                                        <h4>ARIA Labels</h4>
                                        <p>Comprehensive ARIA labels and descriptions for interactive elements and complex content</p>
                                    </div>
                                </div>
                                
                                <div class="sr-feature">
                                    <i class="fas fa-list"></i>
                                    <div>
                                        <h4>Landmark Regions</h4>
                                        <p>Proper use of landmark roles (navigation, main, aside, footer) for easy page structure understanding</p>
                                    </div>
                                </div>
                                
                                <div class="sr-feature">
                                    <i class="fas fa-image"></i>
                                    <div>
                                        <h4>Alternative Text</h4>
                                        <p>Descriptive alt text for all meaningful images and decorative images marked appropriately</p>
                                    </div>
                                </div>
                                
                                <div class="sr-feature">
                                    <i class="fas fa-link"></i>
                                    <div>
                                        <h4>Descriptive Links</h4>
                                        <p>Clear, descriptive link text that makes sense out of context</p>
                                    </div>
                                </div>
                                
                                <div class="sr-feature">
                                    <i class="fas fa-table"></i>
                                    <div>
                                        <h4>Table Headers</h4>
                                        <p>Proper table markup with headers and captions for data tables</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="sr-tips">
                                <h3>Tips for Screen Reader Users</h3>
                                <div class="tips-grid">
                                    <div class="tip-card">
                                        <i class="fas fa-lightbulb"></i>
                                        <h4>Navigation</h4>
                                        <p>Use heading navigation (H key in JAWS/NVDA) to quickly move between sections</p>
                                    </div>
                                    <div class="tip-card">
                                        <i class="fas fa-lightbulb"></i>
                                        <h4>Forms</h4>
                                        <p>Use forms mode (F key) to navigate through form fields efficiently</p>
                                    </div>
                                    <div class="tip-card">
                                        <i class="fas fa-lightbulb"></i>
                                        <h4>Links</h4>
                                        <p>Use link navigation (K key) to jump between links on the page</p>
                                    </div>
                                    <div class="tip-card">
                                        <i class="fas fa-lightbulb"></i>
                                        <h4>Landmarks</h4>
                                        <p>Use landmark navigation (D key) to move between page regions</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accessibility-section" id="visual-accessibility">
                        <h2><i class="fas fa-eye"></i> Visual Accessibility</h2>
                        <div class="section-content">
                            <p>We've designed our website with visual accessibility in mind to accommodate users with various visual needs:</p>
                            
                            <div class="visual-features">
                                <div class="visual-category">
                                    <h3><i class="fas fa-palette"></i> Color & Contrast</h3>
                                    <div class="feature-grid">
                                        <div class="visual-feature">
                                            <i class="fas fa-adjust"></i>
                                            <h4>High Contrast</h4>
                                            <p>All text meets WCAG AA contrast requirements (4.5:1 ratio minimum)</p>
                                        </div>
                                        <div class="visual-feature">
                                            <i class="fas fa-eye-slash"></i>
                                            <h4>Color Independence</h4>
                                            <p>Information is not conveyed by color alone</p>
                                        </div>
                                        <div class="visual-feature">
                                            <i class="fas fa-circle"></i>
                                            <h4>Color Blind Friendly</h4>
                                            <p>Tested for various types of color blindness</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="visual-category">
                                    <h3><i class="fas fa-text-height"></i> Typography & Layout</h3>
                                    <div class="feature-grid">
                                        <div class="visual-feature">
                                            <i class="fas fa-search-plus"></i>
                                            <h4>Scalable Text</h4>
                                            <p>Text can be enlarged up to 200% without loss of functionality</p>
                                        </div>
                                        <div class="visual-feature">
                                            <i class="fas fa-font"></i>
                                            <h4>Readable Fonts</h4>
                                            <p>Clear, legible fonts with adequate spacing</p>
                                        </div>
                                        <div class="visual-feature">
                                            <i class="fas fa-align-left"></i>
                                            <h4>Clear Layout</h4>
                                            <p>Consistent, predictable layout with clear visual hierarchy</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="visual-category">
                                    <h3><i class="fas fa-mouse-pointer"></i> Interactive Elements</h3>
                                    <div class="feature-grid">
                                        <div class="visual-feature">
                                            <i class="fas fa-crosshairs"></i>
                                            <h4>Focus Indicators</h4>
                                            <p>Clear visual focus indicators for keyboard navigation</p>
                                        </div>
                                        <div class="visual-feature">
                                            <i class="fas fa-hand-pointer"></i>
                                            <h4>Large Click Targets</h4>
                                            <p>Buttons and links are large enough for easy interaction</p>
                                        </div>
                                        <div class="visual-feature">
                                            <i class="fas fa-mobile-alt"></i>
                                            <h4>Touch Friendly</h4>
                                            <p>Optimized for touch devices with adequate spacing</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accessibility-section" id="known-issues">
                        <h2><i class="fas fa-exclamation-triangle"></i> Known Issues</h2>
                        <div class="section-content">
                            <p>We are continuously working to improve our website's accessibility. Here are some known issues we are addressing:</p>
                            
                            <div class="issues-list">
                                <div class="issue-item in-progress">
                                    <div class="issue-header">
                                        <i class="fas fa-cog"></i>
                                        <h4>Third-Party Widgets</h4>
                                        <span class="status in-progress">In Progress</span>
                                    </div>
                                    <p>Some third-party widgets (like social media embeds) may not be fully accessible. We are working with vendors to improve this.</p>
                                    <div class="timeline">Expected resolution: Q2 2024</div>
                                </div>
                                
                                <div class="issue-item planned">
                                    <div class="issue-header">
                                        <i class="fas fa-video"></i>
                                        <h4>Video Captions</h4>
                                        <span class="status planned">Planned</span>
                                    </div>
                                    <p>We are working on adding captions to all promotional videos on our website.</p>
                                    <div class="timeline">Expected resolution: Q3 2024</div>
                                </div>
                                
                                <div class="issue-item investigating">
                                    <div class="issue-header">
                                        <i class="fas fa-mobile-alt"></i>
                                        <h4>Mobile Screen Reader</h4>
                                        <span class="status investigating">Investigating</span>
                                    </div>
                                    <p>Some users have reported minor issues with mobile screen readers on certain product pages.</p>
                                    <div class="timeline">Under investigation</div>
                                </div>
                            </div>
                            
                            <div class="workaround-info">
                                <i class="fas fa-info-circle"></i>
                                <div>
                                    <h4>Need Help?</h4>
                                    <p>If you encounter any accessibility barriers while using our website, please contact our support team. We're here to help and can provide alternative ways to access our content and services.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accessibility-section" id="feedback">
                        <h2><i class="fas fa-comments"></i> Feedback & Support</h2>
                        <div class="section-content">
                            <p>Your feedback is essential in helping us improve our website's accessibility. We welcome your input and are committed to addressing any accessibility concerns.</p>
                            
                            <div class="feedback-options">
                                <div class="feedback-card">
                                    <i class="fas fa-bug"></i>
                                    <h4>Report an Issue</h4>
                                    <p>Found an accessibility barrier? Let us know so we can fix it.</p>
                                    <a href="contact.php?subject=Accessibility%20Issue" class="btn btn-primary">Report Issue</a>
                                </div>
                                
                                <div class="feedback-card">
                                    <i class="fas fa-lightbulb"></i>
                                    <h4>Suggest Improvements</h4>
                                    <p>Have ideas for making our site more accessible? We'd love to hear them.</p>
                                    <a href="contact.php?subject=Accessibility%20Suggestion" class="btn btn-outline">Share Ideas</a>
                                </div>
                                
                                <div class="feedback-card">
                                    <i class="fas fa-headset"></i>
                                    <h4>Get Support</h4>
                                    <p>Need help accessing our content or services? Our team is here to assist.</p>
                                    <a href="contact.php?subject=Accessibility%20Support" class="btn btn-outline">Get Help</a>
                                </div>
                            </div>
                            
                            <div class="response-commitment">
                                <h3>Our Response Commitment</h3>
                                <div class="commitment-timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h4>24 Hours</h4>
                                            <p>We'll acknowledge your feedback within 24 hours</p>
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-icon">
                                            <i class="fas fa-search"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h4>1 Week</h4>
                                            <p>We'll investigate and provide an initial response within one week</p>
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-icon">
                                            <i class="fas fa-tools"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h4>Ongoing</h4>
                                            <p>We'll keep you updated on our progress toward resolution</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accessibility-section" id="contact">
                        <h2><i class="fas fa-envelope"></i> Contact Information</h2>
                        <div class="section-content">
                            <p>For accessibility-related questions, feedback, or support, please contact us using any of the following methods:</p>
                            
                            <div class="contact-methods">
                                <div class="contact-method priority">
                                    <i class="fas fa-envelope"></i>
                                    <div>
                                        <strong>Accessibility Email:</strong>
                                        <a href="mailto:accessibility@velvetvogue.lk">accessibility@velvetvogue.lk</a>
                                        <span class="note">Dedicated accessibility support team</span>
                                    </div>
                                </div>
                                
                                <div class="contact-method">
                                    <i class="fas fa-phone"></i>
                                    <div>
                                        <strong>Phone:</strong>
                                        <a href="tel:+94112345678">+94 11 234 5678</a>
                                        <span class="note">Monday-Friday, 9 AM - 6 PM</span>
                                    </div>
                                </div>
                                
                                <div class="contact-method">
                                    <i class="fas fa-comments"></i>
                                    <div>
                                        <strong>Live Chat:</strong>
                                        Available on our website
                                        <span class="note">Real-time support during business hours</span>
                                    </div>
                                </div>
                                
                                <div class="contact-method">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <div>
                                        <strong>Mailing Address:</strong>
                                        Velvet Vogue Accessibility Team<br>
                                        123 Fashion Street<br>
                                        Colombo 03, Sri Lanka
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
/* Accessibility Statement Styles */
.accessibility-page {
    background: var(--background-color);
}

/* Accessibility Hero */
.accessibility-hero {
    background: linear-gradient(135deg, #6C5CE7, #A29BFE);
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
    color: #00CEC9;
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

/* Accessibility Content */
.accessibility-content {
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
.accessibility-main {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    overflow: hidden;
}

.accessibility-section {
    padding: 40px;
    border-bottom: 1px solid var(--border-color);
}

.accessibility-section:last-child {
    border-bottom: none;
}

.accessibility-section h2 {
    color: var(--secondary-color);
    margin-bottom: 25px;
    font-size: 1.8rem;
    display: flex;
    align-items: center;
    gap: 12px;
}

.accessibility-section h2 i {
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

/* Commitment Principles */
.commitment-principles {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    margin: 25px 0;
}

.principle-card {
    text-align: center;
    padding: 30px 20px;
    background: var(--background-color);
    border-radius: var(--border-radius);
    border-top: 4px solid var(--primary-color);
    transition: var(--transition);
}

.principle-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-light);
}

.principle-card i {
    color: var(--primary-color);
    font-size: 2.5rem;
    margin-bottom: 20px;
}

.principle-card h4 {
    margin: 0 0 15px 0;
    color: var(--secondary-color);
}

.principle-card p {
    margin: 0;
    color: var(--text-light);
    font-size: 0.9rem;
}

/* Standards Grid */
.standards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin: 25px 0;
}

.standard-item {
    background: var(--background-color);
    border-radius: var(--border-radius);
    padding: 25px;
    border-left: 4px solid var(--primary-color);
}

.standard-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.standard-header i {
    color: var(--primary-color);
    font-size: 1.8rem;
}

.standard-header h4 {
    margin: 0;
    color: var(--secondary-color);
    font-size: 1.2rem;
}

.standard-item p {
    margin: 0 0 15px 0;
    color: var(--text-light);
}

.standard-item ul {
    margin: 0;
    padding-left: 20px;
}

.standard-item li {
    margin-bottom: 6px;
    color: var(--text-color);
    font-size: 0.9rem;
}

/* Features List */
.features-list {
    margin: 25px 0;
}

.feature-category {
    margin-bottom: 30px;
    background: var(--background-color);
    border-radius: var(--border-radius);
    padding: 25px;
}

.feature-category h3 {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0 0 20px 0;
    color: var(--secondary-color);
    font-size: 1.2rem;
}

.feature-category h3 i {
    color: var(--primary-color);
    font-size: 1.3rem;
}

.feature-items {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    background: var(--white);
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.feature-item i {
    color: #4CAF50;
    font-size: 1.1rem;
}

.feature-item span {
    color: var(--text-color);
    font-size: 0.9rem;
}

/* Assistive Tech Grid */
.assistive-tech-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin: 25px 0;
}

.tech-card {
    background: var(--background-color);
    border-radius: var(--border-radius);
    padding: 25px;
    text-align: center;
    border: 2px solid transparent;
    transition: var(--transition);
}

.tech-card:hover {
    border-color: var(--primary-color);
    transform: translateY(-3px);
}

.tech-card i {
    color: var(--primary-color);
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.tech-card h4 {
    margin: 0 0 15px 0;
    color: var(--secondary-color);
}

.tech-card p {
    margin: 0 0 20px 0;
    color: var(--text-light);
}

.compatibility-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: center;
}

.compatible {
    background: #E8F5E8;
    color: #2E7D32;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
}

.keyboard-shortcuts,
.voice-features,
.magnification-features {
    text-align: left;
}

.shortcut,
.feature {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
    font-size: 0.9rem;
    color: var(--text-color);
}

kbd {
    background: #F5F5F5;
    border: 1px solid #DDD;
    border-radius: 4px;
    padding: 2px 6px;
    font-family: monospace;
    font-size: 0.8rem;
    color: var(--text-color);
}

/* Navigation Guide */
.navigation-guide {
    margin: 25px 0;
}

.guide-section {
    background: var(--background-color);
    border-radius: var(--border-radius);
    padding: 25px;
    margin-bottom: 25px;
}

.guide-section h3 {
    margin: 0 0 20px 0;
    color: var(--secondary-color);
    font-size: 1.2rem;
}

.keyboard-instructions {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.instruction {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 15px;
    background: var(--white);
    border-radius: 8px;
    border-left: 3px solid var(--primary-color);
}

.keys {
    display: flex;
    gap: 5px;
    align-items: center;
    min-width: 120px;
}

.description strong {
    color: var(--secondary-color);
    display: block;
    margin-bottom: 5px;
}

.description {
    color: var(--text-light);
    font-size: 0.9rem;
}

/* Screen Reader Features */
.screen-reader-features {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin: 25px 0;
}

.sr-feature {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 20px;
    background: var(--background-color);
    border-radius: var(--border-radius);
}

.sr-feature i {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-top: 5px;
}

.sr-feature h4 {
    margin: 0 0 10px 0;
    color: var(--secondary-color);
}

.sr-feature p {
    margin: 0;
    color: var(--text-light);
    font-size: 0.9rem;
}

.sr-tips {
    margin: 30px 0;
}

.tips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.tip-card {
    text-align: center;
    padding: 20px 15px;
    background: var(--background-color);
    border-radius: var(--border-radius);
    border: 2px solid transparent;
    transition: var(--transition);
}

.tip-card:hover {
    border-color: var(--primary-color);
    transform: translateY(-3px);
}

.tip-card i {
    color: #FFA726;
    font-size: 1.8rem;
    margin-bottom: 15px;
}

.tip-card h4 {
    margin: 0 0 10px 0;
    color: var(--secondary-color);
}

.tip-card p {
    margin: 0;
    color: var(--text-light);
    font-size: 0.9rem;
}

/* Visual Features */
.visual-features {
    margin: 25px 0;
}

.visual-category {
    margin-bottom: 30px;
    background: var(--background-color);
    border-radius: var(--border-radius);
    padding: 25px;
}

.visual-category h3 {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0 0 20px 0;
    color: var(--secondary-color);
    font-size: 1.2rem;
}

.visual-category h3 i {
    color: var(--primary-color);
    font-size: 1.3rem;
}

.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.visual-feature {
    text-align: center;
    padding: 20px 15px;
    background: var(--white);
    border-radius: 8px;
    border: 1px solid var(--border-color);
    transition: var(--transition);
}

.visual-feature:hover {
    border-color: var(--primary-color);
    transform: translateY(-2px);
}

.visual-feature i {
    color: var(--primary-color);
    font-size: 1.8rem;
    margin-bottom: 15px;
}

.visual-feature h4 {
    margin: 0 0 10px 0;
    color: var(--secondary-color);
    font-size: 1rem;
}

.visual-feature p {
    margin: 0;
    color: var(--text-light);
    font-size: 0.9rem;
}

/* Known Issues */
.issues-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin: 25px 0;
}

.issue-item {
    background: var(--background-color);
    border-radius: var(--border-radius);
    padding: 25px;
    border-left: 4px solid;
}

.issue-item.in-progress {
    border-left-color: #FF9800;
}

.issue-item.planned {
    border-left-color: #2196F3;
}

.issue-item.investigating {
    border-left-color: #9C27B0;
}

.issue-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
}

.issue-header div {
    display: flex;
    align-items: center;
    gap: 12px;
}

.issue-header i {
    font-size: 1.3rem;
}

.issue-item.in-progress .issue-header i {
    color: #FF9800;
}

.issue-item.planned .issue-header i {
    color: #2196F3;
}

.issue-item.investigating .issue-header i {
    color: #9C27B0;
}

.issue-header h4 {
    margin: 0;
    color: var(--secondary-color);
}

.status {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status.in-progress {
    background: #FFF3E0;
    color: #E65100;
}

.status.planned {
    background: #E3F2FD;
    color: #1565C0;
}

.status.investigating {
    background: #F3E5F5;
    color: #7B1FA2;
}

.issue-item p {
    margin: 0 0 10px 0;
    color: var(--text-light);
}

.timeline {
    font-size: 0.9rem;
    color: var(--text-color);
    font-style: italic;
}

.workaround-info {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    background: #E8F5E8;
    border: 1px solid #C8E6C9;
    border-radius: var(--border-radius);
    padding: 20px;
    margin: 25px 0;
}

.workaround-info i {
    color: #2E7D32;
    font-size: 1.5rem;
    margin-top: 2px;
}

.workaround-info h4 {
    margin: 0 0 10px 0;
    color: #2E7D32;
}

.workaround-info p {
    margin: 0;
    color: #1B5E20;
}

/* Feedback Options */
.feedback-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    margin: 25px 0;
}

.feedback-card {
    text-align: center;
    padding: 30px 20px;
    background: var(--background-color);
    border-radius: var(--border-radius);
    border: 2px solid transparent;
    transition: var(--transition);
}

.feedback-card:hover {
    border-color: var(--primary-color);
    transform: translateY(-3px);
}

.feedback-card i {
    color: var(--primary-color);
    font-size: 2.5rem;
    margin-bottom: 20px;
}

.feedback-card h4 {
    margin: 0 0 15px 0;
    color: var(--secondary-color);
}

.feedback-card p {
    margin: 0 0 20px 0;
    color: var(--text-light);
    font-size: 0.9rem;
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
    font-size: 0.9rem;
}

.btn-primary {
    background: var(--primary-color);
    color: var(--white);
}

.btn-primary:hover {
    background: var(--primary-dark);
}

.btn-outline {
    background: transparent;
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
}

.btn-outline:hover {
    background: var(--primary-color);
    color: var(--white);
}

/* Response Commitment */
.response-commitment {
    margin: 30px 0;
}

.commitment-timeline {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin: 20px 0;
}

.timeline-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background: var(--background-color);
    border-radius: var(--border-radius);
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
    align-items: flex-start;
    gap: 20px;
    padding: 25px;
    background: var(--background-color);
    border-radius: var(--border-radius);
    border: 2px solid transparent;
    transition: var(--transition);
}

.contact-method.priority {
    border-color: var(--primary-color);
    background: rgba(var(--primary-color-rgb), 0.05);
}

.contact-method:hover {
    border-color: var(--primary-color);
}

.contact-method i {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-top: 2px;
}

.contact-method strong {
    color: var(--secondary-color);
    display: block;
    margin-bottom: 5px;
}

.contact-method a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
}

.contact-method a:hover {
    text-decoration: underline;
}

.note {
    display: block;
    font-size: 0.9rem;
    color: var(--text-light);
    margin-top: 5px;
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
    
    .accessibility-section {
        padding: 30px 25px;
    }
    
    .commitment-principles,
    .standards-grid,
    .assistive-tech-grid,
    .tips-grid,
    .feature-grid,
    .feedback-options {
        grid-template-columns: 1fr;
    }
    
    .feature-items {
        grid-template-columns: 1fr;
    }
    
    .instruction {
        flex-direction: column;
        gap: 10px;
    }
    
    .keys {
        min-width: auto;
        justify-content: center;
    }
    
    .timeline-item {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
}

@media (max-width: 480px) {
    .accessibility-hero {
        padding: 60px 0 40px;
    }
    
    .hero-content h1 {
        font-size: 2rem;
    }
    
    .accessibility-content {
        padding: 60px 0;
    }
    
    .accessibility-section {
        padding: 25px 20px;
    }
    
    .issue-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .contact-method {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
}
</style>

<script>
// Smooth scrolling for table of contents links
document.addEventListener('DOMContentLoaded', function() {
    // Get all TOC links
    const tocLinks = document.querySelectorAll('.toc-list a');
    
    // Add click event listeners
    tocLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links
            tocLinks.forEach(l => l.classList.remove('active'));
            
            // Add active class to clicked link
            this.classList.add('active');
            
            // Get target section
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            
            if (targetSection) {
                // Smooth scroll to target
                targetSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Highlight active section on scroll
    const sections = document.querySelectorAll('.accessibility-section');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const sectionId = entry.target.id;
                
                // Remove active class from all TOC links
                tocLinks.forEach(link => link.classList.remove('active'));
                
                // Add active class to corresponding TOC link
                const activeLink = document.querySelector(`.toc-list a[href="#${sectionId}"]`);
                if (activeLink) {
                    activeLink.classList.add('active');
                }
            }
        });
    }, {
        threshold: 0.3,
        rootMargin: '-100px 0px -50% 0px'
    });
    
    // Observe all sections
    sections.forEach(section => {
        observer.observe(section);
    });
    
    // Keyboard navigation enhancement
    document.addEventListener('keydown', function(e) {
        // Skip to main content with Alt+M
        if (e.altKey && e.key === 'm') {
            e.preventDefault();
            const mainContent = document.querySelector('.accessibility-main');
            if (mainContent) {
                mainContent.focus();
                mainContent.scrollIntoView({ behavior: 'smooth' });
            }
        }
        
        // Skip to navigation with Alt+N
        if (e.altKey && e.key === 'n') {
            e.preventDefault();
            const navigation = document.querySelector('.toc-sidebar');
            if (navigation) {
                const firstLink = navigation.querySelector('a');
                if (firstLink) {
                    firstLink.focus();
                }
            }
        }
    });
    
    // Add focus indicators for better keyboard navigation
    const focusableElements = document.querySelectorAll('a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])');
    
    focusableElements.forEach(element => {
        element.addEventListener('focus', function() {
            this.style.outline = '3px solid #6C5CE7';
            this.style.outlineOffset = '2px';
        });
        
        element.addEventListener('blur', function() {
            this.style.outline = '';
            this.style.outlineOffset = '';
        });
    });
    
    // Announce page changes for screen readers
    const announcer = document.createElement('div');
    announcer.setAttribute('aria-live', 'polite');
    announcer.setAttribute('aria-atomic', 'true');
    announcer.style.position = 'absolute';
    announcer.style.left = '-10000px';
    announcer.style.width = '1px';
    announcer.style.height = '1px';
    announcer.style.overflow = 'hidden';
    document.body.appendChild(announcer);
    
    // Announce section changes
    tocLinks.forEach(link => {
        link.addEventListener('click', function() {
            const sectionTitle = this.textContent;
            setTimeout(() => {
                announcer.textContent = `Navigated to ${sectionTitle} section`;
            }, 100);
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>