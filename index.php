<!DOCTYPE html>
<html>

<head>
  <title>Mellow - Mẫu Website Khách sạn HTML</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="author" content="">
  <meta name="keywords" content="">
  <meta name="description" content="">

  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">

  <link rel="stylesheet" type="text/css" href="assets/css/vendor.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="assets/css/style.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Upright:wght@300;400;500;600;700&family=Sora:wght@100..800&display=swap"
    rel="stylesheet">
</head>

<body>

  <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    </svg>

  <div class="preloader">
    <div class="loader"></div>
  </div>

  <header id="header">
    <nav class="header-top bg-secondary py-1">
      <div class="container-fluid padding-side">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
          <ul class="info d-flex flex-wrap list-unstyled m-0">
            <li class="location text-capitalize d-flex align-items-center me-4" style="font-size: 14px;">
              <svg class="color me-1" width="15" height="15">
                <use xlink:href="#location"></use>
              </svg>State Road 54 Trinity, Florida
            </li>
            <li class="phone d-flex align-items-center me-4" style="font-size: 14px;">
              <svg class="color me-1" width="15" height="15">
                <use xlink:href="#phone"></use>
              </svg>+666 333 9999
            </li>
            <li class="time d-flex align-items-center me-4" style="font-size: 14px;">
              <svg class="color me-1" width="15" height="15">
                <use xlink:href="#email"></use>
              </svg>yourinfo@yourmail.com
            </li>
          </ul>
          <ul class="social-links d-flex flex-wrap list-unstyled m-0 ">
            </ul>
        </div>
      </div>
    </nav>
    <nav id="primary-header" class="navbar navbar-expand-lg py-4">
      <div class="container-fluid padding-side">
        <div class="d-flex justify-content-between align-items-center w-100">
          <a class="navbar-brand" href="index.html">
            <img src="assets/images/main-logo.png" class="logo img-fluid">
          </a>
          <button class="navbar-toggler border-0 d-flex d-lg-none order-3 p-2 shadow-none" type="button"
            data-bs-toggle="offcanvas" data-bs-target="#bdNavbar" aria-controls="bdNavbar" aria-expanded="false">
            <svg class="navbar-icon" width="60" height="60">
              <use xlink:href="#navbar-icon"></use>
            </svg>
          </button>
          <div class="header-bottom offcanvas offcanvas-end " id="bdNavbar" aria-labelledby="bdNavbarOffcanvasLabel">
            <div class="offcanvas-header px-4 pb-0">
              <button type="button" class="btn-close btn-close-black mt-2" data-bs-dismiss="offcanvas"
                aria-label="Close" data-bs-target="#bdNavbar"></button>
            </div>
            <div class="offcanvas-body align-items-center justify-content-center">
              <ul class="navbar-nav align-items-center mb-2 mb-lg-0">
                <li class="nav-item px-3">
                  <a class="nav-link active p-0" aria-current="page" href="/BTL/index.html">Trang chủ</a>
                </li>
                <li class="nav-item px-3">
                  <a class="nav-link p-0" href="#">Giới thiệu</a>
                </li>
                <li class="nav-item px-3">
                  <a class="nav-link p-0" href="#">Dịch vụ</a>
                </li>
                <li class="nav-item px-3">
                  <a class="nav-link p-0" href="#">Tin tức</a>
                </li>
                <li class="nav-item px-3">
                  <a class="nav-link p-0" href="#">Liên hệ</a>
                </li>
                <li class="nav-item px-3">
                  <a class="nav-link p-0 fw-bold text-primary" href="/BTL/views/login.php">Đăng nhập / Đăng ký</a>
                </li>
              </ul>
            </div>
          </div>
          </div>
      </div>
    </nav>
  </header>

  <section id="slider" data-aos="fade-up">
    <div class="container-fluid padding-side">
      <div class="d-flex rounded-5"
        style="background-image: url(assets/images/slider-image.jpg); background-size: cover; background-repeat: no-repeat; height: 85vh; background-position: center;">
        <div class="row align-items-center m-auto pt-5 px-4 px-lg-0">
          <div class="text-start col-md-6 col-lg-5 col-xl-6 offset-lg-1">
            <h2 class="display-1 fw-normal">Hotel Mellow Cửa ngõ đến sự An yên.</h2>
            <a href="/BTL/views/login.php" class="btn btn-arrow btn-primary mt-3">
              <span>Khám phá phòng <svg width="18" height="18">
                  <use xlink:href="#arrow-right"></use>
                </svg></span>
            </a>
          </div>
          <div class="col-md-6 col-lg-5 col-xl-4 mt-5 mt-md-0">
            <form id="form" class="form-group flex-wrap bg-white p-5 rounded-4 ms-md-5" action="/BTL/views/login.php" method="GET">
              <h3 class="display-5">Kiểm tra phòng trống</h3>
              <div class="col-lg-12 my-4">
                <label class="form-label text-uppercase">Ngày nhận phòng</label>
                <div class="date position-relative bg-transparent" id="select-arrival-date">
                  <a href="#" class="position-absolute top-50 end-0 translate-middle-y pe-2 ">
                    <svg class="text-body" width="25" height="25">
                      <use xlink:href="#calendar"></use>
                    </svg>
                  </a>
                </div>
              </div>
              <div class="col-lg-12 my-4">
                <label class="form-label text-uppercase">Ngày trả phòng</label>
                <div class="date position-relative bg-transparent" id="select-departure-date">
                  <a href="#" class="position-absolute top-50 end-0 translate-middle-y pe-2 ">
                    <svg class="text-body" width="25" height="25">
                      <use xlink:href="#calendar"></use>
                    </svg>
                  </a>
                </div>
              </div>
              <div class="col-lg-12 my-4">
                <label class="form-label text-uppercase">Số phòng</label>
                <input type="number" value="1" name="quantity" class="form-control text-black-50 ps-3">
              </div>
              <div class="col-lg-12 my-4">
                <label class="form-label text-uppercase">Số khách</label>
                <input type="number" value="1" name="quantity" class="form-control text-black-50 ps-3">
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-arrow btn-primary mt-3">
                  <span>Kiểm tra<svg width="18" height="18">
                      <use xlink:href="#arrow-right"></use>
                    </svg></span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="about-us" class="padding-large">
    <div class="container-fluid padding-side" data-aos="fade-up">
      <h3 class="display-3 text-center fw-normal col-lg-4 offset-lg-4">Mellow: Cửa ngõ đến sự An yên</h3>
      <div class="row align-items-start mt-3 mt-lg-5">
        <div class="col-lg-6">
          <div class="p-5">
            <p>Chào mừng đến với Hotel Mellow, nơi tiện nghi gặp gỡ sự yên bình. Nằm giữa lòng thành phố nhộn nhịp,
              khách sạn của chúng tôi mang đến một chốn nghỉ ngơi yên tĩnh cho cả khách du lịch công tác và nghỉ dưỡng. Với
              tiện nghi hiện đại, và không khí ấm cúng, chúng tôi cố gắng làm cho kỳ nghỉ của bạn với chúng tôi thật đáng nhớ.</p>
            <a href="#" class="btn btn-arrow btn-primary mt-3">
              <span>Đọc về chúng tôi <svg width="18" height="18">
                  <use xlink:href="#arrow-right"></use>
                </svg></span>
            </a>
          </div>
          <img src="assets/images/about-img1.jpg" alt="img" class="img-fluid rounded-4 mt-4">
        </div>
        <div class="col-lg-6 mt-5 mt-lg-0">
          <img src="assets/images/about-img2.jpg" alt="img" class="img-fluid rounded-4">
          <img src="assets/images/about-img3.jpg" alt="img" class="img-fluid rounded-4 mt-4">

        </div>
      </div>
    </div>
  </section>

  <section id="info">
    <div class="container" data-aos="fade-up">
      <div class="row">
        <div class="col-md-3 text-center mb-4 mb-lg-0">
          <h3 class="display-1 fw-normal text-primary position-relative">25K <span
              class="position-absolute top-50 end-50 translate-middle z-n1 ps-lg-4 pt-lg-4">
              <img src="assets/images/pattern1.png" alt="pattern" class="img-fluid"></span></h3>
          <p class="text-capitalize">Khách hàng hài lòng</p>
        </div>
        <div class="col-md-3 text-center mb-4 mb-lg-0">
          <h3 class="display-1 fw-normal text-primary position-relative">160 <span
              class="position-absolute top-50 translate-middle z-n1">
              <img src="assets/images/pattern1.png" alt="pattern" class="img-fluid"></span></h3>
          <p class="text-capitalize">Tổng số phòng</p>
        </div>
        <div class="col-md-3 text-center mb-4 mb-lg-0">
          <h3 class="display-1 fw-normal text-primary position-relative">25 <span
              class="position-absolute top-100 pb-5 translate-middle z-n1">
              <img src="assets/images/pattern1.png" alt="pattern" class="img-fluid"></span></h3>
          <p class="text-capitalize">Giải thưởng</p>
        </div>
        <div class="col-md-3 text-center mb-4 mb-lg-0">
          <h3 class="display-1 fw-normal text-primary position-relative">200 <span
              class="position-absolute top-50 end-50 pb-lg-4 pe-lg-2 translate-middle z-n1">
              <img src="assets/images/pattern1.png" alt="pattern" class="img-fluid"></span></h3>
          <p class="text-capitalize">Tổng số thành viên</p>
        </div>
      </div>
    </div>
  </section>

  <section id="room" class="padding-medium">
    <div class="container-fluid padding-side" data-aos="fade-up">
      <div class="d-flex flex-wrap align-items-center justify-content-between">
        <div>
          <h3 class="display-3 fw-normal text-center">Khám phá các phòng của chúng tôi</h3>
        </div>
        <a href="/BTL/views/login.php" class="btn btn-arrow btn-primary mt-3">
          <span>Khám phá phòng<svg width="18" height="18">
              <use xlink:href="#arrow-right"></use>
            </svg></span>
        </a>
      </div>
      </div>
  </section>
  
  <section id="gallery" data-aos="fade-up">
    <h3 class="display-3 fw-normal text-center">Thư viện ảnh</h3>
    </section>

  <section id="services" class="padding-medium">
    <div class="container-fluid padding-side" data-aos="fade-up">
      <h3 class="display-3 text-center fw-normal col-lg-4 offset-lg-4">Dịch vụ & Tiện ích</h3>
      </div>
  </section>
  
  <section id="blog" class="padding-medium pt-0">
    <div class="container-fluid padding-side" data-aos="fade-up">
      <div class="d-flex flex-wrap align-items-center justify-content-between">
        <div>
          <h3 class="display-3 fw-normal text-center">Tin tức & Sự kiện</h3>
        </div>
        <a href="#" class="btn btn-arrow btn-primary mt-3">
          <span>Xem thêm tin tức<svg width="18" height="18">
              <use xlink:href="#arrow-right"></use>
            </svg></span>
        </a>
      </div>
      </div>
  </section>
  
  <section id="footer">
    <div class="container-fluid padding-side padding-small pt-0" data-aos="fade-up">
      <footer class="row">
        <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
          <img src="assets/images/main-logo-footer.png" alt="logo-footer" class="img-fluid">
          <p class="mt-3">Chào mừng đến với Hotel Mellow, nơi tiện nghi gặp gỡ sự yên bình...</p>
          </div>
        <div class="col-md-6 col-lg-3 offset-lg-1 mb-4 mb-lg-0">
          <h4 class="display-6 fw-normal">Đăng ký nhận tin</h4>
          <p>Đăng ký nhận bản tin của chúng tôi để nhận tin tức mới nhất.</p>
          <form class=" position-relative">
            <input type="text" class="form-control px-4 py-3 bg-transparent mb-3" placeholder="Tên của bạn">
            <input type="email" class="form-control px-4 py-3 bg-transparent" placeholder="Email của bạn">
            <div class="d-grid">
              <button href="#" class="btn btn-arrow btn-primary mt-3">
                <span>Đăng ký ngay<svg width="18" height="18">
                    <use xlink:href="#arrow-right"></use>
                  </svg></span>
              </button>
            </div>
          </form>
        </div>
        <div class="col-md-6 col-lg-3 offset-lg-1 mb-4 mb-lg-0">
          <h4 class="display-6 fw-normal">Thông tin</h4>
          <ul class="nav flex-column">
            <li class="location text-capitalize d-flex align-items-center">
              <svg class="color me-1" width="20" height="20">
                <use xlink:href="#location"></use>
              </svg>Mellow Hotel & Resort
            </li>
            </ul>
        </div>
      </footer>
    </div>
    <hr class="text-black">
    </section>

  <script src="assets/js/jquery-1.11.0.min.js"></script>
  <script type="text/javascript" src="assets/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="assets/js/plugins.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script type="text/javascript" src="assets/js/script.js"></script>
  <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</body>

</html>