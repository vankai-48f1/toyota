<?php
/**
 * Cấu hình cơ bản cho WordPress
 *
 * Trong quá trình cài đặt, file "wp-config.php" sẽ được tạo dựa trên nội dung 
 * mẫu của file này. Bạn không bắt buộc phải sử dụng giao diện web để cài đặt, 
 * chỉ cần lưu file này lại với tên "wp-config.php" và điền các thông tin cần thiết.
 *
 * File này chứa các thiết lập sau:
 *
 * * Thiết lập MySQL
 * * Các khóa bí mật
 * * Tiền tố cho các bảng database
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Thiết lập MySQL - Bạn có thể lấy các thông tin này từ host/server ** //
/** Tên database MySQL */
define( 'DB_NAME', 'toyotangocanh' );

/** Username của database */
define( 'DB_USER', 'root' );

/** Mật khẩu của database */
define( 'DB_PASSWORD', '' );

/** Hostname của database */
define( 'DB_HOST', 'localhost' );

/** Database charset sử dụng để tạo bảng database. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Kiểu database collate. Đừng thay đổi nếu không hiểu rõ. */
define('DB_COLLATE', '');

/**#@+
 * Khóa xác thực và salt.
 *
 * Thay đổi các giá trị dưới đây thành các khóa không trùng nhau!
 * Bạn có thể tạo ra các khóa này bằng công cụ
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Bạn có thể thay đổi chúng bất cứ lúc nào để vô hiệu hóa tất cả
 * các cookie hiện có. Điều này sẽ buộc tất cả người dùng phải đăng nhập lại.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '4rF_Z78`XZe8GH|`/n7?Gg=uGZq:_ZM|,YwP=yjH!MDnZ:1iJoNItD%e+H:|$i,y' );
define( 'SECURE_AUTH_KEY',  '_]v0aOG.4U$]1Y!hkU>&N2 +7ePd=9<LU1avl8VFyk^%[l76Y}L3K5Sm08?l>v[u' );
define( 'LOGGED_IN_KEY',    '3 2V(}sdw.5$+xiB<|]`0evru}#O+,S<<}^#m?Gn^4?M%PO=2vuNeT$}RkA3e|[Q' );
define( 'NONCE_KEY',        'Sm-ijq%WUn &X-yYbSMzw0QG,m _-G(2i9)S5S@SdSmq`|$7Pcg%P=#^-h9DS!WT' );
define( 'AUTH_SALT',        'q@Xc@AQ0st&~^#OU`UW[G3K7wpgrQG>N)&pk-pnBlSuPy*j=HVo7f<{/~;Y2HPqZ' );
define( 'SECURE_AUTH_SALT', 'E61q1FUefn5)D-;i07F_i2*oEa=V70.MRQ^<V}ymvK<?y6S:}t0c@]mcd8-obx;h' );
define( 'LOGGED_IN_SALT',   '8 KjAkuv({:JtB77K{V]MdkyefT8q6!.*<,]m)%:$qE8Q})[&FS8A-T[d~ZXUD#t' );
define( 'NONCE_SALT',       '.mN~RZv]]GMIiaHCvW4Jxh}QgFn,Dfm1o>^0J(?hl(O6%vmK!gWHL[i5?_?gqEWM' );

/**#@-*/

/**
 * Tiền tố cho bảng database.
 *
 * Đặt tiền tố cho bảng giúp bạn có thể cài nhiều site WordPress vào cùng một database.
 * Chỉ sử dụng số, ký tự và dấu gạch dưới!
 */
$table_prefix = 'wp_';

/**
 * Dành cho developer: Chế độ debug.
 *
 * Thay đổi hằng số này thành true sẽ làm hiện lên các thông báo trong quá trình phát triển.
 * Chúng tôi khuyến cáo các developer sử dụng WP_DEBUG trong quá trình phát triển plugin và theme.
 *
 * Để có thông tin về các hằng số khác có thể sử dụng khi debug, hãy xem tại Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Đó là tất cả thiết lập, ngưng sửa từ phần này trở xuống. Chúc bạn viết blog vui vẻ. */

/** Đường dẫn tuyệt đối đến thư mục cài đặt WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Thiết lập biến và include file. */
require_once(ABSPATH . 'wp-settings.php');
