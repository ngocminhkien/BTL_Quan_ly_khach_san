/* ============================================ */
/* PHẦN 1: GIAO DIỆN TEMPLATE (Swiper, AOS, Preloader)
/* ============================================ */
(function($) {
  "use strict";

  // 1. Ẩn màn hình chờ (Preloader)
  $(window).on('load', function() {
    if ($('.preloader').length) {
      $('.preloader').fadeOut('slow');
    }
  });

  // 2. Cấu hình Slider Phòng (Room Swiper)
  var roomSwiper = new Swiper(".room-swiper", {
    slidesPerView: 3,
    spaceBetween: 30,
    pagination: {
      el: ".room-pagination",
      clickable: true,
    },
    breakpoints: {
      0: { slidesPerView: 1, spaceBetween: 20 },
      768: { slidesPerView: 2, spaceBetween: 20 },
      1200: { slidesPerView: 3, spaceBetween: 30 },
    },
  });

  // 3. Cấu hình Slider Thư viện ảnh (Gallery Swiper)
  var gallerySwiper = new Swiper(".gallery-swiper", {
    slidesPerView: 3,
    spaceBetween: 30,
    navigation: {
      nextEl: ".main-slider-button-next",
      prevEl: ".main-slider-button-prev",
    },
    breakpoints: {
      0: { slidesPerView: 1, spaceBetween: 20 },
      768: { slidesPerView: 2, spaceBetween: 20 },
      1200: { slidesPerView: 3, spaceBetween: 30 },
    },
  });

  // 4. Khởi tạo hiệu ứng cuộn trang (AOS)
  AOS.init({
    duration: 1000,
    once: true,
  });

})(jQuery);


/* ============================================ */
/* PHẦN 2: LOGIC XỬ LÝ (Đăng nhập & Đặt phòng)
/* ============================================ */

// Biến lưu thông tin người dùng hiện tại
let userSession = {
    loggedIn: false,
    role: null,
    username: ''
};

// Chạy khi trang web tải xong
document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Tự động tải thông tin đăng nhập và Danh sách loại phòng
    checkLoginAndLoadData();

    // 2. Bắt sự kiện nút "Kiểm tra" trên form
    const bookingForm = document.getElementById('form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Chặn tải lại trang
            handleBookingForm();
        });
    }
});

/**
 * HÀM 1: Kiểm tra Session và Tải Loại phòng vào Dropdown
 */
async function checkLoginAndLoadData() {
    const authSection = document.getElementById('user-auth-section');
    const roomTypeSelect = document.getElementById('room_type_id_public');
    
    try {
        // Gọi API lấy dữ liệu (Đảm bảo đường dẫn đúng)
        const response = await fetch('/BTL/handle/check_session.php');
        
        // Nếu lỗi mạng hoặc server lỗi
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        
        // --- A. CẬP NHẬT BIẾN SESSION ---
        userSession = {
            loggedIn: result.loggedIn,
            role: result.role,
            username: result.username
        };

        // --- B. CẬP NHẬT MENU ĐĂNG NHẬP ---
        if (authSection) {
            if (result.loggedIn) {
                // Nếu đã đăng nhập -> Hiện tên và menu logout
                let displayName = (result.role == 1) ? `Admin: ${result.username}` : result.username;
                
                // Tạo menu dropdown cho người dùng đã đăng nhập
                let menuHtml = `
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle p-0 fw-bold text-dark" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Chào, ${displayName}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                `;

                // Nếu là Admin -> Thêm nút vào trang quản trị
                if (result.role == 1) {
                    menuHtml += `<li><a class="dropdown-item" href="/BTL/views/dashboard.php">Vào trang quản trị</a></li>`;
                } else {
                    // Nếu là Khách -> Thêm nút xem lịch sử
                    menuHtml += `<li><a class="dropdown-item" href="/BTL/views/booking/history.php">Lịch sử đặt phòng</a></li>`;
                }

                menuHtml += `
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/BTL/logout.php">Đăng xuất</a></li>
                        </ul>
                    </li>
                `;
                authSection.innerHTML = menuHtml; // Thay thế nội dung menu cũ
                // Xóa class mặc định để tránh vỡ layout flex
                authSection.classList.remove('d-flex'); 
            } else {
                // Nếu chưa đăng nhập -> Hiện nút Đăng nhập/Đăng ký
                authSection.innerHTML = `
                    <a class="nav-link p-0 fw-bold text-primary" href="/BTL/views/login.php">Đăng nhập / Đăng ký</a>
                `;
            }
        }

        // --- C. CẬP NHẬT DROPDOWN LOẠI PHÒNG ---
        if (roomTypeSelect) {
            // Xóa dòng "Đang tải..."
            roomTypeSelect.innerHTML = '<option value="">-- Chọn loại phòng --</option>';
            
            if (result.roomTypes && result.roomTypes.length > 0) {
                result.roomTypes.forEach(type => {
                    // Format tiền Việt
                    let price = new Intl.NumberFormat('vi-VN').format(type.price_per_night);
                    // Thêm option vào select
                    roomTypeSelect.innerHTML += `
                        <option value="${type.id}">
                            ${type.type_name} (${price} VND/đêm)
                        </option>
                    `;
                });
            } else {
                roomTypeSelect.innerHTML = '<option value="" disabled>Không có dữ liệu phòng</option>';
            }
        }

    } catch (error) {
        console.error('Lỗi tải dữ liệu:', error);
        // Nếu lỗi, hiển thị thông báo vào ô select để biết
        if (roomTypeSelect) {
            roomTypeSelect.innerHTML = '<option value="">-- Lỗi kết nối Server --</option>';
        }
    }
}

/**
 * HÀM 2: Xử lý Logic khi bấm nút "Kiểm tra"
 */
async function handleBookingForm() {
    const checkIn = document.getElementById('check_in_date_public').value;
    const checkOut = document.getElementById('check_out_date_public').value;
    const roomTypeId = document.getElementById('room_type_id_public').value;
    const alertBox = document.getElementById('form-check-alert');
    
    // Style cho thông báo
    const alertStyle = 'padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 14px; display: block;';
    const errStyle = `background-color: #ffebee; color: #c62828; border: 1px solid #ef9a9a; ${alertStyle}`;
    const successStyle = `background-color: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; ${alertStyle}`;
    const warnStyle = `background-color: #fff8e1; color: #f57f17; border: 1px solid #ffe082; ${alertStyle}`;

    // 1. Kiểm tra đăng nhập
    if (!userSession.loggedIn) {
        alertBox.innerHTML = `<div style="${errStyle}">Bạn cần <a href="/BTL/views/login.php"><b>Đăng nhập</b></a> để kiểm tra và đặt phòng.</div>`;
        return;
    }
    
    // 2. Admin không được đặt
    if (userSession.role == 1) {
        alertBox.innerHTML = `<div style="${warnStyle}">Tài khoản Quản trị viên không thể đặt phòng.</div>`;
        return;
    }

    // 3. Validate dữ liệu
    if (!checkIn || !checkOut || !roomTypeId) {
        alertBox.innerHTML = `<div style="${errStyle}">Vui lòng nhập đầy đủ thông tin!</div>`;
        return;
    }
    
    if (new Date(checkIn) >= new Date(checkOut)) {
        alertBox.innerHTML = `<div style="${errStyle}">Ngày trả phòng phải sau ngày nhận phòng.</div>`;
        return;
    }
    
    // 4. Gửi yêu cầu kiểm tra
    alertBox.innerHTML = `<div style="${warnStyle}"><i class="fa fa-spinner fa-spin"></i> Đang tìm phòng trống...</div>`;

    try {
        const url = `/BTL/handle/public_check_availability.php?room_type_id=${roomTypeId}&check_in=${checkIn}&check_out=${checkOut}`;
        const response = await fetch(url);
        const result = await response.json();

        if (result.available) {
            // --- TRƯỜNG HỢP CÒN PHÒNG ---
            let html = `<div style="${successStyle}">
                            <strong>Thành công!</strong> Có ${result.available_count} phòng trống.
                        </div>`;

            // Nếu server trả về danh sách phòng cụ thể
            if (result.rooms && result.rooms.length > 0) {
                html += `<div class="mt-2">
                            <p style="font-size:13px; margin-bottom:5px;">Danh sách phòng gợi ý:</p>
                            <ul class="list-group mb-3">`;
                
                result.rooms.forEach(r => {
                    // Link đặt phòng trực tiếp
                    const bookLink = `/BTL/views/booking/add.php?check_in=${checkIn}&check_out=${checkOut}&room_type_id=${roomTypeId}&room_id=${r.id}&customer_id=${userSession.id || ''}`;
                    
                    html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Phòng <strong>${r.room_number}</strong></span>
                                <a href="${bookLink}" class="btn btn-sm btn-primary">Đặt ngay</a>
                             </li>`;
                });
                
                html += `</ul></div>`;
            } else {
                // Fallback nếu không có list phòng
                const bookLinkBase = `/BTL/views/booking/add.php?check_in=${checkIn}&check_out=${checkOut}&room_type_id=${roomTypeId}`;
                html += `<div class="mt-2"><a href="${bookLinkBase}" class="btn btn-primary w-100">Tiếp tục đặt phòng</a></div>`;
            }

            alertBox.innerHTML = html;

        } else {
            // --- TRƯỜNG HỢP HẾT PHÒNG ---
            alertBox.innerHTML = `<div style="${errStyle}">
                                    <strong>Rất tiếc!</strong> ${result.message}
                                  </div>`;
        }

    } catch (error) {
        console.error(error);
        alertBox.innerHTML = `<div style="${errStyle}">Lỗi hệ thống. Vui lòng thử lại sau.</div>`;
    }
}