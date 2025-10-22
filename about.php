<?php
session_start();
require_once 'includes/db_connect.php';

$page_title = 'About Us - Velvet Vogue | Our Story';

include 'includes/header.php';
?>

<div class="about-page">
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container">
            <div class="hero-content">
                <h1>About Velvet Vogue</h1>
                <p class="hero-subtitle">Crafting Elegance, Defining Style</p>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="our-story">
        <div class="container">
            <div class="story-grid">
                <div class="story-content">
                    <h2>Our Story</h2>
                    <p class="lead">Founded with a passion for timeless elegance and contemporary style, Velvet Vogue has been at the forefront of fashion innovation since our inception.</p>
                    
                    <p>What started as a small boutique with a vision to bring premium, affordable fashion to Sri Lanka has grown into a beloved brand trusted by thousands of fashion-conscious women across the country.</p>
                    
                    <p>At Velvet Vogue, we believe that every woman deserves to feel confident, beautiful, and empowered through the clothes she wears. Our carefully curated collections combine the latest global trends with timeless classic pieces, ensuring that our customers always have access to styles that make them look and feel their best.</p>
                    
                    <div class="story-highlights">
                        <div class="highlight-item">
                            <i class="fas fa-heart"></i>
                            <h4>Passion for Fashion</h4>
                            <p>Every piece is selected with love and attention to detail</p>
                        </div>
                        <div class="highlight-item">
                            <i class="fas fa-star"></i>
                            <h4>Quality First</h4>
                            <p>We never compromise on quality and craftsmanship</p>
                        </div>
                        <div class="highlight-item">
                            <i class="fas fa-users"></i>
                            <h4>Customer Focused</h4>
                            <p>Your satisfaction and style needs are our priority</p>
                        </div>
                    </div>
                </div>
                
                <div class="story-image">
                    <img src="assets/images/about-story.jpg" alt="Velvet Vogue Story" class="story-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Our Mission Section -->
    <section class="our-mission">
        <div class="container">
            <div class="mission-content">
                <h2>Our Mission</h2>
                <div class="mission-grid">
                    <div class="mission-card">
                        <div class="mission-icon">
                            <i class="fas fa-gem"></i>
                        </div>
                        <h3>Premium Quality</h3>
                        <p>To provide high-quality, stylish clothing and accessories that enhance every woman's natural beauty and confidence.</p>
                    </div>
                    
                    <div class="mission-card">
                        <div class="mission-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h3>Sustainable Fashion</h3>
                        <p>We're committed to sustainable and ethical fashion practices that respect both our customers and our environment.</p>
                    </div>
                    
                    <div class="mission-card">
                        <div class="mission-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3>Customer Excellence</h3>
                        <p>To deliver exceptional customer service and create lasting relationships built on trust and satisfaction.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values Section -->
    <section class="our-values">
        <div class="container">
            <div class="values-content">
                <div class="values-text">
                    <h2>Our Values</h2>
                    <div class="values-list">
                        <div class="value-item">
                            <h4><i class="fas fa-check-circle"></i> Authenticity</h4>
                            <p>We believe in being genuine and authentic in everything we do, from our product selection to our customer relationships.</p>
                        </div>
                        
                        <div class="value-item">
                            <h4><i class="fas fa-check-circle"></i> Innovation</h4>
                            <p>We constantly evolve and adapt to bring you the latest fashion trends while maintaining our commitment to quality.</p>
                        </div>
                        
                        <div class="value-item">
                            <h4><i class="fas fa-check-circle"></i> Inclusivity</h4>
                            <p>Fashion is for everyone. We celebrate diversity and strive to offer styles for women of all shapes, sizes, and preferences.</p>
                        </div>
                        
                        <div class="value-item">
                            <h4><i class="fas fa-check-circle"></i> Excellence</h4>
                            <p>We pursue excellence in every aspect of our business, from product quality to customer service.</p>
                        </div>
                    </div>
                </div>
                
                <div class="values-image">
                    <img src="assets/images/about-values.jpg" alt="Our Values" class="values-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="our-team">
        <div class="container">
            <div class="section-header">
                <h2>Meet Our Team</h2>
                <p>The passionate individuals behind Velvet Vogue</p>
            </div>
            
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-image">
                        <img src="assets/images/team-member-1.jpg" alt="Priya Perera">
                    </div>
                    <div class="member-info">
                        <h3>Priya Perera</h3>
                        <p class="member-role">Founder & Creative Director</p>
                        <p class="member-bio">With over 15 years in fashion, Priya brings her vision of accessible luxury to Velvet Vogue.</p>
                        <div class="member-social">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="team-member">
                    <div class="member-image">
                        <img src="assets/images/team-member-2.jpg" alt="Samantha Silva">
                    </div>
                    <div class="member-info">
                        <h3>Samantha Silva</h3>
                        <p class="member-role">Head of Design</p>
                        <p class="member-bio">Samantha's innovative designs blend contemporary trends with timeless elegance.</p>
                        <div class="member-social">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-behance"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="team-member">
                    <div class="member-image">
                        <img src="assets/images/team-member-3.jpg" alt="Kavitha Fernando">
                    </div>
                    <div class="member-info">
                        <h3>Kavitha Fernando</h3>
                        <p class="member-role">Customer Experience Manager</p>
                        <p class="member-bio">Kavitha ensures every customer receives exceptional service and support.</p>
                        <div class="member-social">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="about-stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">10,000+</div>
                    <div class="stat-label">Happy Customers</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Fashion Items</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">5</div>
                    <div class="stat-label">Years of Excellence</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Customer Support</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="about-cta">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Discover Your Style?</h2>
                <p>Explore our latest collections and find the perfect pieces to express your unique personality.</p>
                <div class="cta-buttons">
                    <a href="products.php" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i> Shop Now
                    </a>
                    <a href="contact.php" class="btn btn-outline">
                        <i class="fas fa-envelope"></i> Contact Us
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* About Page Styles */
.about-page {
    overflow-x: hidden;
}

/* Hero Section */
.about-hero {
    background: linear-gradient(135deg, rgba(169, 143, 104, 0.9), rgba(139, 115, 85, 0.9)), url('assets/images/about-hero-bg.jpg');
    background-size: cover;
    background-position: center;
    padding: 120px 0 80px;
    color: var(--white);
    text-align: center;
}

.hero-content h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.hero-subtitle {
    font-size: 1.4rem;
    font-weight: 300;
    opacity: 0.9;
    margin: 0;
}

/* Our Story Section */
.our-story {
    padding: 80px 0;
    background: var(--white);
}

.story-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
}

.story-content h2 {
    font-size: 2.5rem;
    color: var(--secondary-color);
    margin-bottom: 25px;
}

.story-content .lead {
    font-size: 1.2rem;
    color: var(--text-color);
    font-weight: 500;
    margin-bottom: 25px;
    line-height: 1.6;
}

.story-content p {
    color: var(--text-light);
    line-height: 1.7;
    margin-bottom: 20px;
}

.story-highlights {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.highlight-item {
    text-align: center;
    padding: 25px;
    background: var(--background-color);
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.highlight-item:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-medium);
}

.highlight-item i {
    font-size: 2.5rem;
    color: var(--primary-color);
    margin-bottom: 15px;
}

.highlight-item h4 {
    color: var(--secondary-color);
    margin-bottom: 10px;
    font-size: 1.1rem;
}

.highlight-item p {
    color: var(--text-light);
    font-size: 0.9rem;
    margin: 0;
}

.story-img {
    width: 100%;
    border-radius: 15px;
    box-shadow: var(--shadow-heavy);
}

/* Our Mission Section */
.our-mission {
    padding: 80px 0;
    background: var(--background-color);
}

.mission-content h2 {
    text-align: center;
    font-size: 2.5rem;
    color: var(--secondary-color);
    margin-bottom: 50px;
}

.mission-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 40px;
}

.mission-card {
    background: var(--white);
    padding: 40px 30px;
    border-radius: var(--border-radius);
    text-align: center;
    box-shadow: var(--shadow-light);
    transition: var(--transition);
}

.mission-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-medium);
}

.mission-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    color: var(--white);
    font-size: 2rem;
}

.mission-card h3 {
    color: var(--secondary-color);
    margin-bottom: 15px;
    font-size: 1.3rem;
}

.mission-card p {
    color: var(--text-light);
    line-height: 1.6;
    margin: 0;
}

/* Our Values Section */
.our-values {
    padding: 80px 0;
    background: var(--white);
}

.values-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
}

.values-text h2 {
    font-size: 2.5rem;
    color: var(--secondary-color);
    margin-bottom: 40px;
}

.value-item {
    margin-bottom: 30px;
    padding-bottom: 25px;
    border-bottom: 1px solid var(--border-color);
}

.value-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.value-item h4 {
    color: var(--secondary-color);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.2rem;
}

.value-item i {
    color: var(--primary-color);
}

.value-item p {
    color: var(--text-light);
    line-height: 1.6;
    margin: 0;
}

.values-img {
    width: 100%;
    border-radius: 15px;
    box-shadow: var(--shadow-heavy);
}

/* Team Section */
.our-team {
    padding: 80px 0;
    background: var(--background-color);
}

.section-header {
    text-align: center;
    margin-bottom: 50px;
}

.section-header h2 {
    font-size: 2.5rem;
    color: var(--secondary-color);
    margin-bottom: 15px;
}

.section-header p {
    color: var(--text-light);
    font-size: 1.1rem;
    margin: 0;
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 40px;
}

.team-member {
    background: var(--white);
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--shadow-light);
    transition: var(--transition);
}

.team-member:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-medium);
}

.member-image {
    position: relative;
    overflow: hidden;
    padding-bottom: 75%;
}

.member-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.team-member:hover .member-image img {
    transform: scale(1.1);
}

.member-info {
    padding: 30px;
    text-align: center;
}

.member-info h3 {
    color: var(--secondary-color);
    margin-bottom: 5px;
    font-size: 1.3rem;
}

.member-role {
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 15px;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.member-bio {
    color: var(--text-light);
    line-height: 1.6;
    margin-bottom: 20px;
    font-size: 0.9rem;
}

.member-social {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.member-social a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    background: var(--background-color);
    border-radius: 50%;
    color: var(--text-color);
    transition: var(--transition);
}

.member-social a:hover {
    background: var(--primary-color);
    color: var(--white);
    transform: translateY(-3px);
}

/* Statistics Section */
.about-stats {
    padding: 60px 0;
    background: linear-gradient(135deg, var(--secondary-color), var(--primary-dark));
    color: var(--white);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 40px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 3rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 10px;
}

.stat-label {
    font-size: 1.1rem;
    opacity: 0.9;
}

/* Call to Action */
.about-cta {
    padding: 80px 0;
    background: var(--white);
}

.cta-content {
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
}

.cta-content h2 {
    font-size: 2.5rem;
    color: var(--primary-color);
    margin-bottom: 20px;
}

.cta-content p {
    color: var(--text-light);
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 40px;
}

.cta-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-content h1 {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.2rem;
    }
    
    .story-grid,
    .values-content {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    
    .story-highlights {
        grid-template-columns: 1fr;
    }
    
    .mission-grid {
        grid-template-columns: 1fr;
    }
    
    .team-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
}

@media (max-width: 480px) {
    .about-hero {
        padding: 80px 0 60px;
    }
    
    .hero-content h1 {
        font-size: 2rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-number {
        font-size: 2.5rem;
    }
}
</style>

<?php include 'includes/footer.php'; ?>