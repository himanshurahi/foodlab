<?php
header("Content-Type:text/css");
$color1 = $_GET['color1']; // Change your Color Here

function checkhexcolor($color1){
    return preg_match('/^#[a-f0-9]{6}$/i', $color1);
}

if (isset($_GET['color1']) AND $_GET['color1'] != '') {
    $color1 = "#" . $_GET['color1'];
}

if (!$color1 OR !checkhexcolor($color1)) {
    $color1 = "#336699";
}

?>

.nav-tabs .nav-link:hover, .nav-tabs .nav-link.active, .btn--base.active, .btn--base.active:focus, .btn--base.active:hover, .btn--base:focus, .btn--base:hover, .header-bottom-area .navbar-collapse .main-menu li a:hover, .header-bottom-area .navbar-collapse .main-menu li a.active, .navbar-toggler span, .banner-section .call-area a, .inner-banner-section .banner-content .title, .breadcrumb-item a, .breadcrumb-item.active::before, .footer-links li:hover, .footer-links li:hover::before, .order-item:hover .order-content .title, .order-content .ratings i, .food-item:hover .food-content .title, .food-content .title span, .restaurants-details-area .restaurants-details-content .ratings i, .restaurant-details-wrapper a, .restaurants-header-area .food-details-tab li a:hover, .edit-btn, .account-header .title, .contact-info-icon i, .profile-thumb-content .profile-content .user-info-list li i, .dashboard-icon, .blog-item:hover .blog-content .title a, .category-content li:hover, .cookie__wrapper .title, .cookie__wrapper .btn--close, .payment-item .payment-content .p .modal-content .modal-title, #infoModal .modal-header button {
  color: <?= $color1 ?>;
}

.text--base, .custom-btn {
  color: <?= $color1 ?> !important;
}

.scrollToTop, .pagination .page-item.active .page-link, .pagination .page-item:hover .page-link, .radio-item [type="radio"]:checked + label:after, .radio-item [type="radio"]:not(:checked) + label:after, .item-discount, .item-discount::before, .item-discount::after, .account-header .title::before {
  background: <?= $color1 ?>;
}

*::-webkit-scrollbar-button, *::-webkit-scrollbar-thumb, .swiper-pagination .swiper-pagination-bullet-active, .slider-next, .slider-prev, .btn--base, .input-group-text, .custom-check-group input:checked + label::before, .submit-btn, ::selection, .footer-toggle .right-icon, .subscribe-form button, .subscribe-form input[type="button"], .subscribe-form input[type="reset"], .subscribe-form input[type="submit"], .footer-social li a:hover, .footer-social li a.active, .order-thumb .offer-badge, .food-thumb .offer-badge, .food-thumb .cart-badge, .side-sidebar-close-btn, .food-cart-slider .food-wrapper .cart-badge, .checkout-details-header .title::before, .draw-countdown .syotimer__body .syotimer-cell, .account-btn-area .account-btn, .dash-btn, .modal-content .close, .modal-content .close {
  background-color: <?= $color1 ?>;
}

.bg--base {
    background-color: <?= $color1 ?> !important;
}

.pagination .page-item.active .page-link, .pagination .page-item:hover .page-link {
  border-color: <?= $color1 ?>;
}

.btn--base.active:focus, .btn--base.active:hover, .btn--base:focus, .btn--base:hover, .cookie__wrapper .read-policy {
  border: 1px solid <?= $color1 ?>;
}

.nav-tabs .nav-link.active, .section-header .section-title, .restaurants-header-area .food-details-tab li a.active, .checkout-widget-title {
  border-bottom: 2px solid <?= $color1 ?>;
}

.header-bottom-area .navbar-collapse .main-menu li .sub-menu {
  border-top: 2px solid <?= $color1 ?>;
}

.footer-section {
  border-top: 3px solid <?= $color1 ?>;
}

.offer-section {
  border-bottom: 3px solid <?= $color1 ?>;
}

.dashboard-item {
  border-left: 4px solid <?= $color1 ?>;
}

.cookie__wrapper {
  border-top: 1px solid <?= $color1 ?>;
}

@media only screen and (max-width: 991px) {
  .custom-table tbody tr td::before {
    color: <?= $color1 ?>;
  }
}

@media only screen and (max-width: 767px) {
  .restaurant-details-wrapper a{
    background-color: <?= $color1 ?>;
  }
}