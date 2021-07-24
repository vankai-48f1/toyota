// tạo table kích thước
export function tableKichThuoc(d_x_r_x_c__mm_, chieu_dai_co_so, khoang_sang_gam_xe, ban_kinh_vong_quay_toi_thieu, 
    trong_luong_khong_tai, trong_luong_toan_tai, chieu_rong_co_so_truoc_sau, kich_thuoc_noi_that) {
    var size = `<tr>
                    <td colspan="2" class="title-spec-group">
                    Kích thước
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="spec-title">
                    D x R x C ( mm )
                    </td>
                </tr>
                <tr>
                    ${d_x_r_x_c__mm_}
                </tr>

                <tr>
                    <td colspan="2" class="spec-title">
                    Chiều dài cơ sở ( mm )
                    </td>
                </tr>
                <tr>
                    ${chieu_dai_co_so}
                </tr>
                
                <tr>
                    <td colspan="2" class="spec-title">
                    Khoảng sáng gầm xe ( mm )
                    </td>
                </tr>
                <tr>
                    ${khoang_sang_gam_xe}
                </tr>
                
                <tr>
                    <td colspan="2" class="spec-title">
                    Bán kính vòng quay tối thiểu ( m )
                    </td>
                </tr>
                <tr>
                    ${ban_kinh_vong_quay_toi_thieu}
                </tr>
                
                <tr>
                    <td colspan="2" class="spec-title">
                    Trọng lượng không tải ( kg )
                    </td>
                </tr>
                <tr>
                    ${trong_luong_khong_tai}
                </tr>
                
                <tr>
                    <td colspan="2" class="spec-title">
                    Trọng lượng toàn tải ( kg )
                    </td>
                </tr>
                <tr>
                    ${trong_luong_toan_tai}
                </tr>
                
                <tr>
                    <td colspan="2" class="spec-title">
                    Chiều rộng cơ sở (Trước/ sau) ( mm )
                    </td>
                </tr>
                <tr>
                    ${chieu_rong_co_so_truoc_sau}
                </tr>
                
                <tr>
                    <td colspan="2" class="spec-title">
                    Kích thước nội thất ( mm x mm x mm )
                    </td>
                </tr>
                <tr>
                    ${kich_thuoc_noi_that}
                </tr>
                `;
    return size;
}

// tạo table Động cơ
export function tableDongCo (toc_do_toi_da, loai_dong_co, dung_tich_xy_lanh_cc, cong_sust_toi_da__kw_hp_vongphut, 
                            mo_men_xoan_toi_da_nm_vongphut, dung_tich_binh_nhien_lieu, 
                            he_thong_nhien_lieu, nhien_lieu, so_xylanh, bo_tri_xy_lanh) {
    var engine = `<tr>
                    <td colspan="2" class="title-spec-group">
                    Động cơ
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="spec-title">
                    Tốc độ tối đa ( km/h )
                    </td>
                </tr>
                <tr>
                    ${toc_do_toi_da}
                </tr>

                <tr>
                    <td colspan="2" class="spec-title">
                    Loại động cơ
                    </td>
                </tr>
                <tr>
                    ${loai_dong_co}
                </tr>
                
                <tr>
                    <td colspan="2" class="spec-title">
                    Dung tích xy lanh ( cc )
                    </td>
                </tr>
                <tr>
                    ${dung_tich_xy_lanh_cc}
                </tr>
                
                <tr>
                    <td colspan="2" class="spec-title">
                    Công suất tối đa ( KW (HP)/ vòng/phút )
                    </td>
                </tr>
                <tr>
                    ${cong_sust_toi_da__kw_hp_vongphut}
                </tr>
                
                <tr>
                    <td colspan="2" class="spec-title">
                    Mô men xoắn tối đa ( Nm @ vòng/phút )
                    </td>
                </tr>
                <tr>
                    ${mo_men_xoan_toi_da_nm_vongphut}
                </tr>
                
                <tr>
                    <td colspan="2" class="spec-title">
                    Dung tích bình nhiên liệu ( L )
                    </td>
                </tr>
                <tr>
                    ${dung_tich_binh_nhien_lieu}
                </tr>
                
                <tr>
                    <td colspan="2" class="spec-title">
                    Hệ thống nhiên liệu
                    </td>
                </tr>
                <tr>
                    ${he_thong_nhien_lieu}
                </tr>
                
                <tr>
                    <td colspan="2" class="spec-title">
                    Nhiên liệu
                    </td>
                </tr>
                <tr>
                    ${nhien_lieu}
                </tr>
                
                <tr>
                    <td colspan="2" class="spec-title">
                    Số xy lanh
                    </td>
                </tr>
                <tr>
                    ${so_xylanh}
                </tr>
                
                <tr>
                    <td colspan="2" class="spec-title">
                    Bố trí xy lanh
                    </td>
                </tr>
                <tr>
                    ${bo_tri_xy_lanh}
                </tr>
                `;
    return engine;
}

export function tableHeThongTruyenDong(he_thong_truyen_dong) {
    var heThongTruyenDong =`<tr>
                                <td colspan="2" class="title-spec-group">
                                Hệ thống truyền động
                                </td>
                            </tr>
                            <tr>
                                ${he_thong_truyen_dong}
                            </tr>`;
    return heThongTruyenDong;
}


export function tableHopSo(hop_so) {
    var hopSo =`<tr>
                    <td colspan="2" class="title-spec-group">
                    Hộp số
                    </td>
                </tr>
                <tr>
                    ${hop_so}
                </tr>`;
    return hopSo;
}

export function tableHeThongTreo(truoc, sau) {
    var heThongTreo =`<tr>
                        <td colspan="2" class="title-spec-group">
                        Hệ thống treo
                        </td>
                    </tr>
                    
                    <tr>
                        <td colspan="2" class="spec-title">
                        Trước
                        </td>
                    </tr>
                    <tr>
                        ${truoc}
                    </tr>
                    
                    <tr>
                        <td colspan="2" class="spec-title">
                        Sau
                        </td>
                    </tr>
                    <tr>
                        ${sau}
                    </tr>`;
    return heThongTreo;
}

export function tableVanhVaLopXe(loai_vanh = '', kich_thuoc_lop = '', lop_du_phong = '') {
    var vanhVaLopXe =`<tr>
                        <td colspan="2" class="title-spec-group">
                        Vành & Lốp xe
                        </td>
                    </tr>
                    
                    <tr>
                        <td colspan="2" class="spec-title">
                        Loại vành
                        </td>
                    </tr>
                    <tr>
                        ${loai_vanh}
                    </tr>
                    
                    <tr>
                        <td colspan="2" class="spec-title">
                        Kích thước lốp
                        </td>
                    </tr>
                    <tr>
                        ${kich_thuoc_lop}
                    </tr>

                    <tr>
                        <td colspan="2" class="spec-title">
                        Lốp dự phòng
                        </td>
                    </tr>
                    <tr>
                        ${lop_du_phong}
                    </tr>`;
    return vanhVaLopXe;
}


export function tablePhanh(truoc, sau) {
    var phanhXe =`<tr>
                        <td colspan="2" class="title-spec-group">
                        Phanh
                        </td>
                    </tr>
                    
                    <tr>
                        <td colspan="2" class="spec-title">
                        Trước
                        </td>
                    </tr>
                    <tr>
                        ${truoc}
                    </tr>
                    
                    <tr>
                        <td colspan="2" class="spec-title">
                        Sau
                        </td>
                    </tr>
                    <tr>
                        ${sau}
                    </tr>`;
    return phanhXe;
}

export function tableTieuChuanKhiThai(tieu_chuan_khi_thai) {
    var tieuChuanKhiThai =`<tr>
                    <td colspan="2" class="title-spec-group">
                    Tiêu chuẩn khí thải
                    </td>
                </tr>
                <tr>
                    ${tieu_chuan_khi_thai}
                </tr>`;
    return tieuChuanKhiThai;
}

export function tableTieuThuNhienLieu(ttnl_trong_do_thi, ttnl_ngoai_do_thi, ttnl_ket_hop) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Tiêu thụ nhiên liệu
                            </td>
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Trong đô thị ( L/100km )
                            </td>
                        </tr>
                        <tr>
                            ${ttnl_trong_do_thi}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Ngoài đô thị ( L/100km )
                            </td>
                        </tr>
                        <tr>
                            ${ttnl_ngoai_do_thi}
                        </tr>

                        <tr>
                            <td colspan="2" class="spec-title">
                            Kết hợp ( L/100km )
                            </td>
                        </tr>
                        <tr>
                            ${ttnl_ket_hop}
                        </tr>`;
    return table_vehicle;
}


export function tableCheDoLaiEco(che_do_lai_eco_power) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Chế độ lái ECO / POWER
                            </td>
                        </tr>
                        <tr>
                            ${che_do_lai_eco_power}
                        </tr>`;
    return table_vehicle;
}

export function tableCheDoLai(che_do_lai) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Chế độ lái
                            </td>
                        </tr>
                        <tr>
                            ${che_do_lai}
                        </tr>`;
    return table_vehicle;
}

// NGOẠI THẤT       

export function tableThanhCanGiamVaCham(truoc, sau) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Thanh cản (giảm va chạm)
                            </td>
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Trước
                            </td>
                        </tr>
                        <tr>
                            ${truoc}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Sau
                            </td>
                        </tr>
                        <tr>
                            ${sau}
                        </tr>`;
    return table_vehicle;
}

export function tableheThongChieuSangBanNgay(he_thong_chieu_sang_ban_ngay) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Hệ thống chiếu sáng ban ngày
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_chieu_sang_ban_ngay}
                        </tr>`;
    return table_vehicle;
}


// tạo table Gương chiếu hậu ngoài
export function tableGuongChieuHauNgoai (mau_guong_chieu_hau, chuc_nang_dieu_chinh_dien, chuc_nang_gap_dien, tich_hop_den_bao_re, 
                                        chuc_nang_tu_dieu_chinh_khi_lui, chuc_nang_say_guong, 
                                        chuc_nang_chong_bam_nuoc, chuc_nang_chong_chay_tu_dong, cung_mau_than_xe, tich_hop_den_chao_mung) {
    var table_vehicle = `<tr>
                            <td colspan="2" class="title-spec-group">
                            Gương chiếu hậu ngoài
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="spec-title">
                            Màu
                            </td>
                        </tr>
                        <tr>
                            ${mau_guong_chieu_hau}
                        </tr>

                        <tr>
                            <td colspan="2" class="spec-title">
                            Chức năng điều chỉnh điện
                            </td>
                        </tr>
                        <tr>
                            ${chuc_nang_dieu_chinh_dien}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Chức năng gập điện
                            </td>
                        </tr>
                        <tr>
                            ${chuc_nang_gap_dien}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Tích hợp đèn báo rẽ
                            </td>
                        </tr>
                        <tr>
                            ${tich_hop_den_bao_re}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Chức năng tự điều chỉnh khi lùi
                            </td>
                        </tr>
                        <tr>
                            ${chuc_nang_tu_dieu_chinh_khi_lui}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Chức năng sấy gương
                            </td>
                        </tr>
                        <tr>
                            ${chuc_nang_say_guong}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Chức năng chống bám nước
                            </td>
                        </tr>
                        <tr>
                            ${chuc_nang_chong_bam_nuoc}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Chức năng chống chói tự động
                            </td>
                        </tr>
                        <tr>
                            ${chuc_nang_chong_chay_tu_dong}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Cùng màu thân xe
                            </td>
                        </tr>
                        <tr>
                            ${cung_mau_than_xe}
                        </tr>

                        <tr>
                            <td colspan="2" class="spec-title">
                            Tích hợp đèn chào mừng
                            </td>
                        </tr>
                        <tr>
                            ${tich_hop_den_chao_mung}
                        </tr>
                `;
    return table_vehicle;
}

// tạo table Cụm đèn trước
export function tableCumDenTruoc (he_thong_can_bang_den_pha, den_chieu_gan, den_chieu_xa, den_chieu_sang_ban_ngay, 
                                        he_thong_rua_den, he_thong_dieu_khien_den_tu_dong, he_thong_mo_rong_goc_chieu_tu_dong, 
                                        he_thong_dieu_chinh_goc_chieu, che_do_den_cho_dan_duong, he_thong_nhac_nho_den_sang) {
    var table_vehicle = `<tr>
                            <td colspan="2" class="title-spec-group">
                            Cụm đèn trước
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="spec-title">
                            Hệ thống cân bằng đèn pha
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_can_bang_den_pha}
                        </tr>

                        <tr>
                            <td colspan="2" class="spec-title">
                            Đèn chiếu gần
                            </td>
                        </tr>
                        <tr>
                            ${den_chieu_gan}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Đèn chiếu xa
                            </td>
                        </tr>
                        <tr>
                            ${den_chieu_xa}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Đèn chiếu sáng ban ngày
                            </td>
                        </tr>
                        <tr>
                            ${den_chieu_sang_ban_ngay}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Hệ thống rửa đèn
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_rua_den}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Hệ thống điều khiển đèn tự động
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_dieu_khien_den_tu_dong}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Hệ thống mở rộng góc chiếu tự động
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_mo_rong_goc_chieu_tu_dong}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Hệ thống điều chỉnh góc chiếu
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_dieu_chinh_goc_chieu}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Chế độ đèn chờ dẫn đường
                            </td>
                        </tr>
                        <tr>
                            ${che_do_den_cho_dan_duong}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Hệ thống nhắc nhở đèn sáng
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_nhac_nho_den_sang}
                        </tr>
                `;
    return table_vehicle;
}


export function tableCumDenSau(den_vi_tri, den_phanh, den_bao_re, den_lui) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Cụm đèn sau
                            </td>
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Đèn vị trí
                            </td>
                        </tr>
                        <tr>
                            ${den_vi_tri}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Đèn phanh
                            </td>
                        </tr>
                        <tr>
                            ${den_phanh}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Đèn báo rẽ
                            </td>
                        </tr>
                        <tr>
                            ${den_bao_re}
                        </tr>
                        
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Đèn lùi
                            </td>
                        </tr>
                        <tr>
                            ${den_lui}
                        </tr>`;
    return table_vehicle;
}

export function tableDenSuongMu(den_suong_mu_truoc, den_suong_mu_sau) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Đèn sương mù
                            </td>
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Trước
                            </td>
                        </tr>
                        <tr>
                            ${den_suong_mu_truoc}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Sau
                            </td>
                        </tr>
                        <tr>
                            ${den_suong_mu_sau}
                        </tr>`;
    return table_vehicle;
}


export function tableDenBaoPhanhTrenCao(den_bao_phanh_tren_cao) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Đèn báo phanh trên cao
                            </td>
                        </tr>
                        <tr>
                            ${den_bao_phanh_tren_cao}
                        </tr>`;
    return table_vehicle;
}


export function tableGatMuaGianDoan(gat_mua_gian_doan) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Gạt mưa gián đoạn
                            </td>
                        </tr>
                        <tr>
                            ${gat_mua_gian_doan}
                        </tr>`;
    return table_vehicle;
}

export function tableChucNangSayKinhSau(chuc_nang_say_kinh_sau) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Chức năng sấy kính sau
                            </td>
                        </tr>
                        <tr>
                            ${chuc_nang_say_kinh_sau}
                        </tr>`;
    return table_vehicle;
}


export function tableAngTen(ang_ten) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Ăng ten
                            </td>
                        </tr>
                        <tr>
                            ${ang_ten}
                        </tr>`;
    return table_vehicle;
}

export function tableTayNamCuaNgoai(tay_nam_cua_ngoai) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Tay nắm cửa ngoài
                            </td>
                        </tr>
                        <tr>
                            ${tay_nam_cua_ngoai}
                        </tr>`;
    return table_vehicle;
}

export function tableCanhHuongGioCanSau(canh_huong_gio_can_sau) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Cánh hướng gió cản sau
                            </td>
                        </tr>
                        <tr>
                            ${canh_huong_gio_can_sau}
                        </tr>`;
    return table_vehicle;
}

export function tableChanBunTruocSau(chan_bun_truoc_sau) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Chắn bùn trước & sau
                            </td>
                        </tr>
                        <tr>
                            ${chan_bun_truoc_sau}
                        </tr>`;
    return table_vehicle;
}

export function tableOngXaKep(ong_xa_kep) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Ống xả kép
                            </td>
                        </tr>
                        <tr>
                            ${ong_xa_kep}
                        </tr>`;
    return table_vehicle;
}

export function tableGatMua(gat_mua_truoc, gat_mua_sau) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Gạt mưa
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="spec-title">
                            Trước
                            </td>
                        </tr>
                        <tr>
                            ${gat_mua_truoc}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Sau
                            </td>
                        </tr>
                        <tr>
                            ${gat_mua_sau}
                        </tr>`;
    return table_vehicle;
}

export function tablekinh(kinh_chan_gio, kinh_goc_truoc, kinh_hai_ben_hang_ghe_thu_2, kinh_hai_ben_hang_ghe_thu_3,
                        kinh_hai_ben_hang_ghe_truoc, kinh_phia_sau) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Kính
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="spec-title">
                            Kính chắn gió
                            </td>
                        </tr>
                        <tr>
                            ${kinh_chan_gio}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Kính góc trước
                            </td>
                        </tr>
                        <tr>
                            ${kinh_goc_truoc}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Kính hai bên hàng ghế thứ 2
                            </td>
                        </tr>
                        <tr>
                            ${kinh_hai_ben_hang_ghe_thu_2}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Kính hai bên hàng ghế thứ 3
                            </td>
                        </tr>
                        <tr>
                            ${kinh_hai_ben_hang_ghe_thu_3}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Kính hai bên hàng ghế trước
                            </td>
                        </tr>
                        <tr>
                            ${kinh_hai_ben_hang_ghe_truoc}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Kính phía sau
                            </td>
                        </tr>
                        <tr>
                            ${kinh_phia_sau}
                        </tr>`;
    return table_vehicle;
}


// NỘI THẤT
// Tay lái
export function tableTayLai(loai_tay_lai, chat_lieu_tay_lai, nut_bam_dieu_khien_tich_hop, dieu_chinh_tay_lai,
                        lay_chuyen_so, tro_luc_lai, suoi_vo_lang) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Tay lái
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="spec-title">
                            Loại tay lái
                            </td>
                        </tr>
                        <tr>
                            ${loai_tay_lai}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Chất liệu
                            </td>
                        </tr>
                        <tr>
                            ${chat_lieu_tay_lai}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Nút bấm điều khiển tích hợp
                            </td>
                        </tr>
                        <tr>
                            ${nut_bam_dieu_khien_tich_hop}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Điều chỉnh
                            </td>
                        </tr>
                        <tr>
                            ${dieu_chinh_tay_lai}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Lẫy chuyển số
                            </td>
                        </tr>
                        <tr>
                            ${lay_chuyen_so}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Trợ lực lái
                            </td>
                        </tr>
                        <tr>
                            ${tro_luc_lai}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Sưởi vô lăng
                            </td>
                        </tr>
                        <tr>
                            ${suoi_vo_lang}
                        </tr>`;
    return table_vehicle;
}


// Ghế trướcs
export function tableGheTruoc(ghe_hanh_khach_truoc, loai_ghe_truoc, dieu_chinh_ghe_lai, dieu_chinh_ghe_khach,
                    bo_nho_vi_tri) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Ghế trước
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="spec-title">
                            Ghế hành khách trước
                            </td>
                        </tr>
                        <tr>
                            ${ghe_hanh_khach_truoc}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Loại ghế
                            </td>
                        </tr>
                        <tr>
                            ${loai_ghe_truoc}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Điều chỉnh ghế lái
                            </td>
                        </tr>
                        <tr>
                            ${dieu_chinh_ghe_lai}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Điều chỉnh ghế hành khách
                            </td>
                        </tr>
                        <tr>
                            ${dieu_chinh_ghe_khach}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Bộ nhớ vị trí
                            </td>
                        </tr>
                        <tr>
                            ${bo_nho_vi_tri}
                        </tr>
                       `;

    return table_vehicle;
}

export function tableGuongChieuHauTrong(guong_chieu_hau_trong) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Gương chiếu hậu trong
                            </td>
                        </tr>
                        <tr>
                            ${guong_chieu_hau_trong}
                        </tr>`;
    return table_vehicle;
}

export function tableTayNamCuaTrong(tay_nam_cua_trong) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Tay nắm cửa trong
                            </td>
                        </tr>
                        <tr>
                            ${tay_nam_cua_trong}
                        </tr>`;
    return table_vehicle;
}

// Cụm đồng hồ và bảng táplô
export function tableCumDongHo(loai_dong_ho, den_bao_che_do_eco, chuc_nang_bao_luong_tieu_thu_nhien_lieu, chuc_nang_bao_vi_tri_can_so,
                    man_hinh_hien_thị_da_thong_tin) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Ghế trướcs
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="spec-title">
                            Loại đồng hồ
                            </td>
                        </tr>
                        <tr>
                            ${loai_dong_ho}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Đèn báo chế độ Eco
                            </td>
                        </tr>
                        <tr>
                            ${den_bao_che_do_eco}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Chức năng báo lượng tiêu thụ nhiên liệu
                            </td>
                        </tr>
                        <tr>
                            ${chuc_nang_bao_luong_tieu_thu_nhien_lieu}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Chức năng báo vị trí cần số
                            </td>
                        </tr>
                        <tr>
                            ${chuc_nang_bao_vi_tri_can_so}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Màn hình hiển thị đa thông tin
                            </td>
                        </tr>
                        <tr>
                            ${man_hinh_hien_thị_da_thong_tin}
                        </tr>
                       `;

    return table_vehicle;
}

export function tableCuaSoTroi(cua_so_troi) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Cửa sổ trời
                            </td>
                        </tr>
                        <tr>
                            ${cua_so_troi}
                        </tr>`;
    return table_vehicle;
}

export function tableChatLieuBocGhe(chat_lieu_boc_ghe) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Chất liệu bọc ghế
                            </td>
                        </tr>
                        <tr>
                            ${chat_lieu_boc_ghe}
                        </tr>`;
    return table_vehicle;
}


// Ghế sau
export function tableGheSau(hang_ghe_thu_hai, tua_tay_hang_ghe_thu_hai, hang_ghe_thu_ba) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Ghế sau
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="spec-title">
                            Hàng ghế thứ hai
                            </td>
                        </tr>
                        <tr>
                            ${hang_ghe_thu_hai}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Tựa tay hàng ghế thứ hai
                            </td>
                        </tr>
                        <tr>
                            ${tua_tay_hang_ghe_thu_hai}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Hàng ghế thứ ba
                            </td>
                        </tr>
                        <tr>
                            ${hang_ghe_thu_ba}
                        </tr>
                       `;

    return table_vehicle;
}

export function tableHopLanh(hop_lanh) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Hộp lạnh
                            </td>
                        </tr>
                        <tr>
                            ${hop_lanh}
                        </tr>`;
    return table_vehicle;
}

export function tableSoGhe(so_ghe_ngoi) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Số ghế
                            </td>
                        </tr>
                        <tr>
                            ${so_ghe_ngoi}
                        </tr>`;
    return table_vehicle;
}


// TIỆN NGHI

export function tableKhoaCuaTuDongTheoTocDo(khoa_cua_tu_dong_theo_toc_do) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Khóa cửa tự động theo tốc độ
                            </td>
                        </tr>
                        <tr>
                            ${khoa_cua_tu_dong_theo_toc_do}
                        </tr>`;
    return table_vehicle;
}


// tạo table Hệ thống âm thanh
export function tableHeThongAmThanh (apple_car_play_android_auto, dau_dia, so_loa, cong_ket_noi_aux, 
                                        cong_ket_noi_usb, ket_noi_bluetooth, dieu_khien_bang_giong_noi, 
                                        chuc_nang_dieu_khien_tu_hang_ghe_sau, cong_ket_noi_hdmi, he_thong_dam_thoai_ranh_tay, 
                                        ket_noi_wifi, ket_noi_dien_thoai_thong_minh) {
    var table_vehicle = `<tr>
                            <td colspan="2" class="title-spec-group">
                            Hệ thống âm thanh
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" class="spec-title">
                            Apple Car Play/Android Auto
                            </td>
                        </tr>
                        <tr>
                            ${apple_car_play_android_auto}
                        </tr>

                        <tr>
                            <td colspan="2" class="spec-title">
                            Đầu đĩa
                            </td>
                        </tr>
                        <tr>
                            ${dau_dia}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Số loa
                            </td>
                        </tr>
                        <tr>
                            ${so_loa}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Cổng kết nối AUX
                            </td>
                        </tr>
                        <tr>
                            ${cong_ket_noi_aux}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Cổng kết nối USB
                            </td>
                        </tr>
                        <tr>
                            ${cong_ket_noi_usb}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Kết nối Bluetooth
                            </td>
                        </tr>
                        <tr>
                            ${ket_noi_bluetooth}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Điều khiển bằng giọng nói
                            </td>
                        </tr>
                        <tr>
                            ${dieu_khien_bang_giong_noi}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Chức năng điều khiển từ hàng ghế sau
                            </td>
                        </tr>
                        <tr>
                            ${chuc_nang_dieu_khien_tu_hang_ghe_sau}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Cổng kết nối HDMI
                            </td>
                        </tr>
                        <tr>
                            ${cong_ket_noi_hdmi}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Hệ thống đàm thoại rảnh tay
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_dam_thoai_ranh_tay}
                        </tr>

                        <tr>
                            <td colspan="2" class="spec-title">
                            Kết nối wifi
                            </td>
                        </tr>
                        <tr>
                            ${ket_noi_wifi}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Kết nối điện thoại thông minh
                            </td>
                        </tr>
                        <tr>
                            ${ket_noi_dien_thoai_thong_minh}
                        </tr>
                `;
    return table_vehicle;
}

export function tableCuaSoDieuChinhDien(cua_so_dieu_chinh_dien) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Cửa sổ điều chỉnh điện
                            </td>
                        </tr>
                        <tr>
                            ${cua_so_dieu_chinh_dien}
                        </tr>`;
    return table_vehicle;
}

export function tableRemCheNangPhiaSau(rem_che_nang_kinh_sau) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Rèm che nắng kính sau
                            </td>
                        </tr>
                        <tr>
                            ${rem_che_nang_kinh_sau}
                        </tr>`;
    return table_vehicle;
}

export function tableHeThongDieuHoa(he_thong_dieu_hoa) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Hệ thống điều hòa
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_dieu_hoa}
                        </tr>`;
    return table_vehicle;
}

export function tableCuaGioSau(cua_gio_sau) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Cửa gió sau
                            </td>
                        </tr>
                        <tr>
                            ${cua_gio_sau}
                        </tr>`;
    return table_vehicle;
}

export function tableChiaKhoaThongMinh(chia_khoa_thong_minh_khoi_dong_bang_nut_bam) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Chìa khóa thông minh & khởi động bằng nút bấm
                            </td>
                        </tr>
                        <tr>
                            ${chia_khoa_thong_minh_khoi_dong_bang_nut_bam}
                        </tr>`;
    return table_vehicle;
}

export function tableChucNangKhoaCuaTuXa(chuc_nang_khoa_cua_tu_xa) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Khóa cửa tự động theo tốc độ
                            </td>
                        </tr>
                        <tr>
                            ${chuc_nang_khoa_cua_tu_xa}
                        </tr>`;
    return table_vehicle;
}

export function tablePhanhTayDienTu(phanh_tay_dien_tu) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Phanh tay điện tử
                            </td>
                        </tr>
                        <tr>
                            ${phanh_tay_dien_tu}
                        </tr>`;
    return table_vehicle;
}

export function tableCopDieuKhienDien(cop_dieu_khien_dien) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Cốp điều khiển điện
                            </td>
                        </tr>
                        <tr>
                            ${cop_dieu_khien_dien}
                        </tr>`;
    return table_vehicle;
}

export function tableGiuPhanh(giu_phanh) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Giữ phanh
                            </td>
                        </tr>
                        <tr>
                            ${giu_phanh}
                        </tr>`;
    return table_vehicle;
}

export function tableKhoaCuaDien(khoa_cua_dien) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Khóa cửa điện
                            </td>
                        </tr>
                        <tr>
                            ${khoa_cua_dien}
                        </tr>`;
    return table_vehicle;
}

export function tableHeThongSacKhongGiay(he_thong_sac_khong_day) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Hệ thống sạc không giây
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_sac_khong_day}
                        </tr>`;
    return table_vehicle;
}

export function tableHeThongTheoDoiApSuatLop(he_thong_theo_doi_ap_suat_lop) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Hệ thống theo dõi áp suất lốp
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_theo_doi_ap_suat_lop}
                        </tr>`;
    return table_vehicle;
}


// AN TOÀN CHỦ ĐỘNG

export function tableHeThongChongBoCungPhanhAbs(he_thong_chong_bo_cung_phanh_abs) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Hệ thống chống bó cứng phanh (ABS)
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_chong_bo_cung_phanh_abs}
                        </tr>`;
    return table_vehicle;
}

export function tableHeThongHoTroPhanhKhanCapBa(he_thong_ho_tro_luc_phanh_khan_cap_ba) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Hệ thống hỗ trợ lực phanh khẩn cấp (BA)
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_ho_tro_luc_phanh_khan_cap_ba}
                        </tr>`;
    return table_vehicle;
}

export function tableHeThongPhanhDienTuEbd(he_thong_phan_phoi_luc_phanh_dien_tu_ebd) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Hệ thống phân phối lực phanh điện tử (EBD)
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_phan_phoi_luc_phanh_dien_tu_ebd}
                        </tr>`;
    return table_vehicle;
}

export function tableHeThongCanBangDienTuVsc(he_thong_can_bang_dien_tu_vsc) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Hệ thống cân bằng điện tử (VSC)
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_can_bang_dien_tu_vsc}
                        </tr>`;
    return table_vehicle;
}

export function tableHeThongKiemSoatLucKeoTrc(he_thong_kiem_soat_luc_keo_trc) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Hệ thống kiểm soát lực kéo (TRC)
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_kiem_soat_luc_keo_trc}
                        </tr>`;
    return table_vehicle;
}

export function tableHeThongHoTroKhoiHanhNgangDoc(he_thong_ho_tro_khoi_hanh_ngang_doc_hac) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Hệ thống hỗ trợ khởi hành ngang dốc (HAC)
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_ho_tro_khoi_hanh_ngang_doc_hac}
                        </tr>`;
    return table_vehicle;
}

export function tableDenBaoPhanhKhanCapEbs(den_bao_phanh_khan_cap_ebs) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Đèn báo phanh khẩn cấp (EBS)
                            </td>
                        </tr>
                        <tr>
                            ${den_bao_phanh_khan_cap_ebs}
                        </tr>`;
    return table_vehicle;
}

export function tableCameraLui(camera_lui) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Camera lùi
                            </td>
                        </tr>
                        <tr>
                            ${camera_lui}
                        </tr>`;
    return table_vehicle;
}

export function tableCamBienDoXe(cam_bien_ho_tro_do_xe_sau, cam_bien_ho_tro_do_xe_goc_sau,
                                            cam_bien_ho_tro_do_xe_truoc, cam_bien_ho_tro_do_xe_goc_truoc) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Cảm biến hỗ trợ đỗ xe
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="spec-title">
                            Sau
                            </td>
                        </tr>
                        <tr>
                            ${cam_bien_ho_tro_do_xe_sau}
                        </tr>

                        <tr>
                            <td colspan="2" class="spec-title">
                            Góc sau
                            </td>
                        </tr>
                        <tr>
                            ${cam_bien_ho_tro_do_xe_goc_sau}
                        </tr>

                        <tr>
                            <td colspan="2" class="spec-title">
                            Trước
                            </td>
                        </tr>
                        <tr>
                            ${cam_bien_ho_tro_do_xe_truoc}
                        </tr>

                        <tr>
                            <td colspan="2" class="spec-title">
                            Góc Trước
                            </td>
                        </tr>
                        <tr>
                            ${cam_bien_ho_tro_do_xe_goc_truoc}
                        </tr>
                        `;
    return table_vehicle;
}

export function tableHeThongHoTroDoDeoDac(he_thong_ho_tro_do_deo_dac) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Hệ thống hỗ trợ đỗ đèo (DAC)
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_ho_tro_do_deo_dac}
                        </tr>`;
    return table_vehicle;
}

export function tableCanhBaoTienVaCham(canh_bao_tien_va_cham) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Cảnh báo tiền va chạm
                            </td>
                        </tr>
                        <tr>
                            ${canh_bao_tien_va_cham}
                        </tr>`;
    return table_vehicle;
}

export function tableCanhBaoLechLanDuong(canh_bao_chech_lan_duong_lda) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Cảnh báo chệch làn đường (LDA)
                            </td>
                        </tr>
                        <tr>
                            ${canh_bao_chech_lan_duong_lda}
                        </tr>`;
    return table_vehicle;
}

export function tableDieuKhienHanhTrinhChuDong(dieu_khien_hanh_trinh_chu_dong_drcc) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Điều khiển hành trình chủ động (DRCC)
                            </td>
                        </tr>
                        <tr>
                            ${dieu_khien_hanh_trinh_chu_dong_drcc}
                        </tr>`;
    return table_vehicle;
}

export function tableHeThongLuaChonVanToc(he_thong_lua_chon_van_toc_vuot_dia_hinh) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Hệ thống lựa chọn vận tốc vượt địa hình
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_lua_chon_van_toc_vuot_dia_hinh}
                        </tr>`;
    return table_vehicle;
}

export function tableHeThongThichNghiDiaHinh(he_thong_thich_nghi_dia_hinh_mts) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Hệ thống thích nghi địa hình (MTS)
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_thich_nghi_dia_hinh_mts}
                        </tr>`;
    return table_vehicle;
}

export function tableHeThongKiemSoatDiemMu(he_thong_kiem_soat_diem_mu_bsm) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Hệ thống kiểm soát điểm mù (BSM)
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_kiem_soat_diem_mu_bsm}
                        </tr>`;
    return table_vehicle;
}

export function tableCamera360(camera_360_do) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Camera 360 độ
                            </td>
                        </tr>
                        <tr>
                            ${camera_360_do}
                        </tr>`;
    return table_vehicle;
}


// AN TOAN BỊ ĐỘNG

export function tableTuiKhi(tui_khi_nguoi_lai_hanh_khach_phia_truoc, tui_khi_ben_hong_phia_truoc, tui_khi_ben_hong_phia_sau, 
                            tui_khi_rem, tui_khi_dau_goi_nguoi_lai, tui_khi_dau_goi_hanh_khach_phia_truoc, so_luong_tui_khi) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Túi khí
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" class="spec-title">
                            Túi khí người lái & hành khách phía trước
                            </td>
                        </tr>
                        <tr>
                            ${tui_khi_nguoi_lai_hanh_khach_phia_truoc}
                        </tr>

                        <tr>
                            <td colspan="2" class="spec-title">
                            Túi khí bên hông phía trước
                            </td>
                        </tr>
                        <tr>
                            ${tui_khi_ben_hong_phia_truoc}
                        </tr>

                        <tr>
                            <td colspan="2" class="spec-title">
                            Túi khí bên hông phía sau
                            </td>
                        </tr>
                        <tr>
                            ${tui_khi_ben_hong_phia_sau}
                        </tr>

                        <tr>
                            <td colspan="2" class="spec-title">
                            Túi khí rèm
                            </td>
                        </tr>
                        <tr>
                            ${tui_khi_rem}
                        </tr>

                        <tr>
                            <td colspan="2" class="spec-title">
                            Túi khí đầu gối người lái
                            </td>
                        </tr>
                        <tr>
                            ${tui_khi_dau_goi_nguoi_lai}
                        </tr>

                        <tr>
                            <td colspan="2" class="spec-title">
                            Túi khí đầu gối hành khách phía trước
                            </td>
                        </tr>
                        <tr>
                            ${tui_khi_dau_goi_hanh_khach_phia_truoc}
                        </tr>

                        <tr>
                            <td colspan="2" class="spec-title">
                            Số lượng túi khí
                            </td>
                        </tr>
                        <tr>
                            ${so_luong_tui_khi}
                        </tr>
                        `;
    return table_vehicle;
}

export function tableDayDaiAnToan(day_dai_an_toan, day_dai_an_toan_hang_ghe_thu_2, day_dai_an_toan_hang_ghe_thu_3,
                                day_dai_an_toan_hang_ghe_truoc) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Dây đai an toàn
                            </td>
                        </tr>
                        <tr>
                            ${day_dai_an_toan}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Hàng ghế thứ 2
                            </td>
                        </tr>
                        <tr>
                            ${day_dai_an_toan_hang_ghe_thu_2}
                        </tr>

                        <tr>
                            <td colspan="2" class="spec-title">
                            Hàng ghế thứ 3
                            </td>
                        </tr>
                        <tr>
                            ${day_dai_an_toan_hang_ghe_thu_3}
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="spec-title">
                            Hàng ghế trước
                            </td>
                        </tr>
                        <tr>
                            ${day_dai_an_toan_hang_ghe_truoc}
                        </tr>
                        `;
    return table_vehicle;
}

export function tableCotLaiTuDo(cot_lai_tu_do) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Cột lái tự đổ
                            </td>
                        </tr>
                        <tr>
                            ${cot_lai_tu_do}
                        </tr>`;
    return table_vehicle;
}

export function tableGheCoCauTrucGiamChanThuongCo(ghe_co_cau_truc_giam_chan_thuong_co) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Ghế có cấu trúc giảm chấn thương cổ
                            </td>
                        </tr>
                        <tr>
                            ${ghe_co_cau_truc_giam_chan_thuong_co}
                        </tr>`;
    return table_vehicle;
}

export function tableKhungXeGoa(khung_xe_goa) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Khung xe GOA
                            </td>
                        </tr>
                        <tr>
                            ${khung_xe_goa}
                        </tr>`;
    return table_vehicle;
}

export function tableBanDapPhanhTuDo(ban_dap_phanh_tu_do) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Bàn đạp phanh tự đổ
                            </td>
                        </tr>
                        <tr>
                            ${ban_dap_phanh_tu_do}
                        </tr>`;
    return table_vehicle;
}

// AN NINH
export function tableHeThongBaoDong(he_thong_bao_dong) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Hệ thống báo động
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_bao_dong}
                        </tr>`;
    return table_vehicle;
}

export function tableHeThongMaHoaKhoaDongCo(he_thong_ma_hoa_khoa_dong_co) {
    var table_vehicle =`<tr>
                            <td colspan="2" class="title-spec-group">
                            Hệ thống mã hóa khóa động cơ
                            </td>
                        </tr>
                        <tr>
                            ${he_thong_ma_hoa_khoa_dong_co}
                        </tr>`;
    return table_vehicle;
}