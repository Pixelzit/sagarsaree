<?php
if ( ! function_exists( 'pxlt_home_services_shortcode' ) ) {
	function pxlt_home_services_shortcode() {
		wp_enqueue_style( 'home-services-shortcode', get_stylesheet_directory_uri() . '/assets/sass/home-services-shortcode.scss', array(), null );

		ob_start();
		?>

		<section class="wholesale-banner">

  <!-- Top Header -->
  <div class="banner-top">
    <div class="top-inner">
      <div class="title-row">
        <span class="ornament">❦</span>
        <h1>WHOLESALE SAREES</h1>
        <span class="ornament">❦</span>
      </div>

      <div class="subtitle">
        <span class="line"></span>
        <p>EXCLUSIVE FOR BUSINESS BUYERS</p>
        <span class="line"></span>
      </div>
    </div>
  </div>

  <!-- Features -->
  <div class="features-grid">

    <div class="feature-item">
      <div class="icon-circle">
        <svg viewBox="0 0 64 64">
          <path d="M12 52h40M18 52V30l10-8v30M36 52V18l10 6v28" />
          <path d="M22 36h4M22 42h4M40 30h4M40 36h4" />
        </svg>
      </div>
      <h3>Factory<br>Direct Prices</h3>
      <span class="mini-line"></span>
      <p>Best rates directly<br>from manufacturers</p>
    </div>

    <div class="feature-item">
      <div class="icon-circle">
        <svg viewBox="0 0 64 64">
          <path d="M12 22l20-10 20 10-20 10-20-10z"/>
          <path d="M12 22v20l20 10 20-10V22"/>
          <path d="M32 32v20"/>
        </svg>
      </div>
      <h3>Low MOQ</h3>
      <span class="mini-line"></span>
      <p>Start your business<br>with low investment</p>
    </div>

    <div class="feature-item">
      <div class="icon-circle">
        <svg viewBox="0 0 64 64">
          <circle cx="32" cy="32" r="20"/>
          <path d="M12 32h40"/>
          <path d="M32 12c6 6 10 12 10 20s-4 14-10 20c-6-6-10-12-10-20s4-14 10-20z"/>
        </svg>
      </div>
      <h3>Worldwide<br>Shipping</h3>
      <span class="mini-line"></span>
      <p>Delivering to 50+<br>countries</p>
    </div>

    <div class="feature-item">
      <div class="icon-circle">
        <svg viewBox="0 0 64 64">
          <rect x="18" y="14" width="24" height="34" rx="2"/>
          <path d="M24 22h12M24 28h8"/>
          <circle cx="46" cy="40" r="8"/>
          <path d="M42 40l3 3 5-6"/>
        </svg>
      </div>
      <h3>GST Billing<br>Available</h3>
      <span class="mini-line"></span>
      <p>100% GST compliant<br>invoices</p>
    </div>

    <div class="feature-item">
      <div class="icon-circle">
        <svg viewBox="0 0 64 64">
          <circle cx="24" cy="44" r="4"/>
          <circle cx="46" cy="44" r="4"/>
          <path d="M14 18h22v18h10l4 6v2H14z"/>
          <circle cx="24" cy="20" r="8"/>
          <path d="M24 16v5l3 2"/>
        </svg>
      </div>
      <h3>Fast & Reliable<br>Dispatch</h3>
      <span class="mini-line"></span>
      <p>On-time delivery<br>guaranteed</p>
    </div>

    <div class="feature-item">
      <div class="icon-circle">
        <svg viewBox="0 0 64 64">
          <path d="M18 28a14 14 0 0128 0v12"/>
          <path d="M18 40h-4a4 4 0 01-4-4v-2a4 4 0 014-4h4"/>
          <path d="M46 40h4a4 4 0 004-4v-2a4 4 0 00-4-4h-4"/>
          <path d="M32 50h8"/>
        </svg>
      </div>
      <h3>Dedicated<br>Support</h3>
      <span class="mini-line"></span>
      <p>Your success is<br>our priority</p>
    </div>

  </div>

</section>

		<?php
		return ob_get_clean();
	}
	add_shortcode( 'home-services', 'pxlt_home_services_shortcode' );
}
