<?php
require_once 'includes/header.php';
?>

<?php
require_once 'includes/analytics/Analytics.php';
$analytics = new Analytics($conn);
echo $analytics->getTrackingCode();
?>

<main>
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="display-4 luxury-text mb-4">Experience Luxury Travel</h1>
                    <p class="lead mb-4">Discover the world with unparalleled comfort and elegance. Your journey to extraordinary destinations begins here.</p>
                    <div class="search-box p-4 bg-white rounded-3 shadow-lg">
                        <form action="#" method="GET" class="luxury-form">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <select class="form-select" name="travel_type" required>
                                        <option value="">Select Travel Type</option>
                                        <option value="flight">Luxury Flights</option>
                                        <option value="train">Premium Trains</option>
                                        <option value="bus">Executive Buses</option>
                                        <option value="hotel">5-Star Hotels</option>
                                        <option value="package">Custom Packages</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="From" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="To" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="date" class="form-control" placeholder="Departure" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="date" class="form-control" placeholder="Return">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-2"></i>Search Luxury Travel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Services -->
    <section class="featured-services py-5">
        <div class="container">
            <h2 class="text-center luxury-text mb-5">Premium Travel Services</h2>
            <div class="row g-4">
                <div class="col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="luxury-card service-card p-4 h-100">
                        <div class="icon-box mb-4">
                            <i class="fas fa-plane-departure"></i>
                        </div>
                        <h3 class="h4 mb-3">Private Jets</h3>
                        <p class="text-muted mb-4">Experience the ultimate in air travel with our exclusive private jet services.</p>
                        <div class="service-features mb-4">
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>24/7 Availability</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Personal Concierge</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Custom Itineraries</span>
                            </div>
                        </div>
                        <a href="private-jets.php" class="btn btn-outline-primary">Explore Options</a>
                    </div>
                </div>
                <div class="col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="luxury-card service-card p-4 h-100">
                        <div class="icon-box mb-4">
                            <i class="fas fa-hotel"></i>
                        </div>
                        <h3 class="h4 mb-3">Ultra-Luxury Stays</h3>
                        <p class="text-muted mb-4">Indulge in world-class accommodations at the most prestigious locations.</p>
                        <div class="service-features mb-4">
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>5-Star Properties</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Butler Service</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Exclusive Access</span>
                            </div>
                        </div>
                        <a href="luxury-hotels.php" class="btn btn-outline-primary">View Properties</a>
                    </div>
                </div>
                <div class="col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="luxury-card service-card p-4 h-100">
                        <div class="icon-box mb-4">
                            <i class="fas fa-ship"></i>
                        </div>
                        <h3 class="h4 mb-3">Yacht Charters</h3>
                        <p class="text-muted mb-4">Set sail in style with our premium yacht charter experiences.</p>
                        <div class="service-features mb-4">
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Private Crew</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Gourmet Dining</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Custom Routes</span>
                            </div>
                        </div>
                        <a href="yacht-charters.php" class="btn btn-outline-primary">Discover Yachts</a>
                    </div>
                </div>
                <div class="col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="luxury-card service-card p-4 h-100">
                        <div class="icon-box mb-4">
                            <i class="fas fa-glass-martini-alt"></i>
                        </div>
                        <h3 class="h4 mb-3">VIP Experiences</h3>
                        <p class="text-muted mb-4">Access exclusive events and experiences around the globe.</p>
                        <div class="service-features mb-4">
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Private Events</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Celebrity Access</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Luxury Transport</span>
                            </div>
                        </div>
                        <a href="vip-experiences.php" class="btn btn-outline-primary">Explore VIP</a>
                    </div>
                </div>
                <div class="col-lg-3" data-aos="fade-up" data-aos-delay="500">
                    <div class="luxury-card service-card p-4 h-100">
                        <div class="icon-box mb-4">
                            <i class="fas fa-helicopter"></i>
                        </div>
                        <h3 class="h4 mb-3">Helicopter Tours</h3>
                        <p class="text-muted mb-4">Experience breathtaking aerial views with private helicopter excursions.</p>
                        <div class="service-features mb-4">
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Scenic Routes</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Private Tours</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Champagne Flights</span>
                            </div>
                        </div>
                        <a href="helicopter-tours.php" class="btn btn-outline-primary">Book Tour</a>
                    </div>
                </div>
                <div class="col-lg-3" data-aos="fade-up" data-aos-delay="600">
                    <div class="luxury-card service-card p-4 h-100">
                        <div class="icon-box mb-4">
                            <i class="fas fa-spa"></i>
                        </div>
                        <h3 class="h4 mb-3">Wellness Retreats</h3>
                        <p class="text-muted mb-4">Rejuvenate at exclusive spa resorts and wellness sanctuaries.</p>
                        <div class="service-features mb-4">
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Private Sessions</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Luxury Spa</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Holistic Treatments</span>
                            </div>
                        </div>
                        <a href="wellness-retreats.php" class="btn btn-outline-primary">Explore Wellness</a>
                    </div>
                </div>
                <div class="col-lg-3" data-aos="fade-up" data-aos-delay="700">
                    <div class="luxury-card service-card p-4 h-100">
                        <div class="icon-box mb-4">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3 class="h4 mb-3">Culinary Journeys</h3>
                        <p class="text-muted mb-4">Embark on exclusive gastronomic adventures worldwide.</p>
                        <div class="service-features mb-4">
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Michelin Stars</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Private Chefs</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Wine Tastings</span>
                            </div>
                        </div>
                        <a href="culinary-tours.php" class="btn btn-outline-primary">Taste Luxury</a>
                    </div>
                </div>
                <div class="col-lg-3" data-aos="fade-up" data-aos-delay="800">
                    <div class="luxury-card service-card p-4 h-100">
                        <div class="icon-box mb-4">
                            <i class="fas fa-gem"></i>
                        </div>
                        <h3 class="h4 mb-3">Shopping Concierge</h3>
                        <p class="text-muted mb-4">Personal shopping assistance at the world's most exclusive boutiques.</p>
                        <div class="service-features mb-4">
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>VIP Access</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Personal Stylist</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-gold me-2"></i>
                                <span>Private Showings</span>
                            </div>
                        </div>
                        <a href="shopping-concierge.php" class="btn btn-outline-primary">Shop Elite</a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <a href="all-services.php" class="btn btn-lg btn-primary luxury-btn">
                    <span>View All Premium Services</span>
                    <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Special Offers -->
    <section class="special-offers py-5 bg-light">
        <div class="container">
            <h2 class="text-center luxury-text mb-5">Exclusive Offers</h2>
            <div class="row g-4">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="offer-card position-relative overflow-hidden rounded-3">
                        <img src="assets/images/maldives.jpg" alt="Maldives" class="w-100">
                        <div class="offer-content p-4">
                            <h3 class="h4 mb-3">Maldives Luxury Package</h3>
                            <p class="mb-3">7 Days | 5-Star Resort | Private Beach</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="price">
                                    <span class="h3 mb-0">₹1,99,999</span>
                                    <small class="text-muted">/person</small>
                                </div>
                                <a href="#" class="btn btn-primary">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="offer-card position-relative overflow-hidden rounded-3">
                        <img src="assets/images/dubai.jpg" alt="Dubai" class="w-100">
                        <div class="offer-content p-4">
                            <h3 class="h4 mb-3">Dubai Royal Experience</h3>
                            <p class="mb-3">5 Days | Burj Khalifa | Desert Safari</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="price">
                                    <span class="h3 mb-0">₹1,49,999</span>
                                    <small class="text-muted">/person</small>
                                </div>
                                <a href="#" class="btn btn-primary">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials py-5">
        <div class="container">
            <h2 class="text-center luxury-text mb-5">Guest Experiences</h2>
            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-up">
                    <div class="testimonial-card p-4">
                        <div class="stars mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="mb-4">"An absolutely magnificent experience. The attention to detail and level of service was extraordinary."</p>
                        <div class="d-flex align-items-center">
                            <img src="assets/images/testimonial1.jpg" alt="Guest" class="rounded-circle me-3" width="50">
                            <div>
                                <h5 class="mb-0">Rajesh Sharma</h5>
                                <small class="text-muted">CEO, Tech Solutions</small>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Add more testimonials as needed -->
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="newsletter py-5 bg-dark text-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <h2 class="mb-4">Subscribe to Our Newsletter</h2>
                    <p class="mb-4">Stay updated with our latest luxury travel offers and exclusive deals.</p>
                    <form class="newsletter-form">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Enter your email">
                            <button class="btn btn-primary" type="submit">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Initialize AOS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        easing: 'ease-in-out'
    });
</script>

<?php
require_once 'includes/footer.php';
?>

<?php
require_once 'includes/legal/CookieManager.php';
$cookieManager = new CookieManager($conn);
echo $cookieManager->getCookieConsentBanner();
?>
</body>
