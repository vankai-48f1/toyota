
import * as table from "./create-table.js"; 


function createColumn (variableue = null) {
    var td = `<td>
                <span>${variableue ? variableue : ' '}</span>
            </td>`;
    return td;
}

function handleView(result) {
    var thead;
    var engine_and_chassis = '';

    // Kích thước
    var d_x_r_x_c__mm_, chieu_dai_co_so,  khoang_sang_gam_xe, ban_kinh_vong_quay_toi_thieu, trong_luong_khong_tai, trong_luong_toan_tai,
        chieu_rong_co_so_truoc_sau, kich_thuoc_noi_that;

    // Động cơ
    var toc_do_toi_da, loai_dong_co, dung_tich_xy_lanh_cc, cong_sust_toi_da__kw_hp_vongphut, mo_men_xoan_toi_da_nm_vongphut, dung_tich_binh_nhien_lieu,
        he_thong_nhien_lieu, nhien_lieu, so_xy_lanh, bo_tri_xy_lanh;

    // Hệ thống truyền động 
    var he_thong_truyen_dong;

    // Hộp số 
    var hop_so;

    // Hệ thống treo
    var he_thong_treo_truoc, he_thong_treo_sau;

    // Vành & Lốp xe
    var loai_vanh, kich_thuoc_lop, lop_du_phong;

    //  Phanh
    var phanh_truoc, phanh_sau;
    
    //  Tiêu chuẩn khí thải
    var tieu_chuan_khi_thai;

    // Tiêu thụ nhiên liệu
    var ttnl_trong_do_thi, ttnl_ngoai_do_thi, ttnl_ket_hop;
    
    // Chế độ lái
    var che_do_lai_eco_power, che_do_lai;


    // Ngoại thất
    // Thanh cản (giảm va chạm)
    var hanh_can_giam_va_cham_truoc, hanh_can_giam_va_cham_sau;
    
    var he_thong_chieu_sang_ban_ngay;

    // Gương chiếu hậu ngoài
    var mau_guong_chieu_hau, chuc_nang_dieu_chinh_dien, chuc_nang_gap_dien, tich_hop_den_bao_re, 
    chuc_nang_tu_dieu_chinh_khi_lui, chuc_nang_say_guong, 
    chuc_nang_chong_bam_nuoc, chuc_nang_chong_chay_tu_dong, cung_mau_than_xe, tich_hop_den_chao_mung;

    // Cụm đèn trước
    var he_thong_can_bang_den_pha, den_chieu_gan, den_chieu_xa, den_chieu_sang_ban_ngay, 
    he_thong_rua_den, he_thong_dieu_khien_den_tu_dong, he_thong_mo_rong_goc_chieu_tu_dong, 
    he_thong_dieu_chinh_goc_chieu, che_do_den_cho_dan_duong, he_thong_nhac_nho_den_sang;

    // Cụm đèn sau
    var den_vi_tri, den_phanh, den_bao_re, den_lui;

    // Đèn sương mù
    var den_suong_mu_truoc, den_suong_mu_sau, den_bao_phanh_tren_cao, gat_mua_gian_doan;
    var chuc_nang_say_kinh_sau, ang_ten, tay_nam_cua_ngoai, canh_huong_gio_can_sau,
        chan_bun_truoc_sau, ong_xa_kep, gat_mua_truoc, gat_mua_sau;

    // NỘI THẤT 
    var loai_tay_lai, chat_lieu_tay_lai, nut_bam_dieu_khien_tich_hop, dieu_chinh_tay_lai,
    lay_chuyen_so, tro_luc_lai, suoi_vo_lang, ghe_hanh_khach_truoc, loai_ghe_truoc, dieu_chinh_ghe_lai, dieu_chinh_ghe_khach,
    bo_nho_vi_tri, guong_chieu_hau_trong, tay_nam_cua_trong, loai_dong_ho, den_bao_che_do_eco, chuc_nang_bao_luong_tieu_thu_nhien_lieu, chuc_nang_bao_vi_tri_can_so,
    man_hinh_hien_thị_da_thong_tin, cua_so_troi, chat_lieu_boc_ghe, hang_ghe_thu_hai, tua_tay_hang_ghe_thu_hai, hang_ghe_thu_ba, hop_lanh, so_ghe_ngoi;

    // TIỆN NGHI    
    var khoa_cua_tu_dong_theo_toc_do, apple_car_play_android_auto, dau_dia, so_loa, cong_ket_noi_aux, 
    cong_ket_noi_usb, ket_noi_bluetooth, dieu_khien_bang_giong_noi, 
    chuc_nang_dieu_khien_tu_hang_ghe_sau, cong_ket_noi_hdmi, he_thong_dam_thoai_ranh_tay, 
    ket_noi_wifi, ket_noi_dien_thoai_thong_minh, 
    cua_so_dieu_chinh_dien, rem_che_nang_kinh_sau, he_thong_dieu_hoa, cua_gio_sau, 
    chia_khoa_thong_minh_khoi_dong_bang_nut_bam, chuc_nang_khoa_cua_tu_xa, phanh_tay_dien_tu, 
    cop_dieu_khien_dien, giu_phanh, khoa_cua_dien, he_thong_sac_khong_day, he_thong_theo_doi_ap_suat_lop;

    // AN TOAN CHỦ ĐỘNG
    var he_thong_chong_bo_cung_phanh_abs, he_thong_ho_tro_luc_phanh_khan_cap_ba, he_thong_phan_phoi_luc_phanh_dien_tu_ebd, 
    he_thong_can_bang_dien_tu_vsc, he_thong_kiem_soat_luc_keo_trc, he_thong_ho_tro_khoi_hanh_ngang_doc_hac, den_bao_phanh_khan_cap_ebs, 
    camera_lui, cam_bien_ho_tro_do_xe_sau, cam_bien_ho_tro_do_xe_goc_sau, cam_bien_ho_tro_do_xe_truoc, cam_bien_ho_tro_do_xe_goc_truoc, 
    he_thong_ho_tro_do_deo_dac, canh_bao_tien_va_cham, canh_bao_chech_lan_duong_lda, dieu_khien_hanh_trinh_chu_dong_drcc, 
    he_thong_lua_chon_van_toc_vuot_dia_hinh, he_thong_thich_nghi_dia_hinh_mts, he_thong_kiem_soat_diem_mu_bsm, camera_360_do;

    // AN TOÀN BỊ ĐỘNG
    var tui_khi_nguoi_lai_hanh_khach_phia_truoc, tui_khi_ben_hong_phia_truoc, tui_khi_ben_hong_phia_sau, 
    tui_khi_rem, tui_khi_dau_goi_nguoi_lai, tui_khi_dau_goi_hanh_khach_phia_truoc, so_luong_tui_khi, 
    day_dai_an_toan, day_dai_an_toan_hang_ghe_thu_2, day_dai_an_toan_hang_ghe_thu_3, 
    day_dai_an_toan_hang_ghe_truoc, cot_lai_tu_do, ghe_co_cau_truc_giam_chan_thuong_co, khung_xe_goa, ban_dap_phanh_tu_do;

    //  AN NINH 
    var he_thong_bao_dong, he_thong_ma_hoa_khoa_dong_co;


    for (var key_vehicle in result) {

        // header
        var img_vehicle = result[key_vehicle].image_vehicle,
            name_vehicle = result[key_vehicle].name_vehicle,
            fuel_type = result[key_vehicle].fuel_type,
            so_xylanh = result[key_vehicle].engine;

        thead += `
                    <th class="text-center" style="width: 50%;">
                        <div class="result-image">
                            <img src="${img_vehicle}" alt="">
                        </div>
                        <div>${name_vehicle}</div>
                    </th>`;


        // main
        var data = result[key_vehicle].data_vehicle;

        // Kích thước
            d_x_r_x_c__mm_                  += createColumn(data.d_x_r_x_c__mm_);
            chieu_dai_co_so                 += createColumn(data.chieu_dai_co_so);
            khoang_sang_gam_xe              += createColumn(data.khoang_sang_gam_xe);
            ban_kinh_vong_quay_toi_thieu    += createColumn(data.ban_kinh_vong_quay_toi_thieu);
            trong_luong_khong_tai           += createColumn(data.trong_luong_khong_tai);
            trong_luong_toan_tai            += createColumn(data.trong_luong_toan_tai);
            chieu_rong_co_so_truoc_sau      += createColumn(data.chieu_rong_co_so_truoc_sau);
            kich_thuoc_noi_that             += createColumn(data.kich_thuoc_noi_that);
        
        // Động cơ
            toc_do_toi_da                   += createColumn(data.toc_do_toi_da);
            loai_dong_co                    += createColumn(data.loai_dong_co);
            dung_tich_xy_lanh_cc            += createColumn(data.dung_tich_xy_lanh_cc);
            cong_sust_toi_da__kw_hp_vongphut += createColumn(data.cong_sust_toi_da__kw_hp_vongphut);
            mo_men_xoan_toi_da_nm_vongphut  += createColumn(data.mo_men_xoan_toi_da_nm_vongphut);
            dung_tich_binh_nhien_lieu       += createColumn(data.dung_tich_binh_nhien_lieu);
            he_thong_nhien_lieu             += createColumn(data.he_thong_nhien_lieu);
            nhien_lieu                      += createColumn(fuel_type);
            so_xy_lanh                      += createColumn(so_xylanh);
            bo_tri_xy_lanh                  += createColumn(data.bo_tri_xy_lanh);

        // Hệ thống truyền động
            he_thong_truyen_dong            += createColumn(data.he_thong_truyen_dong);
        
        // Hộp số
            hop_so                          += createColumn(data.hop_so);

        // Hệ thống treo    
            he_thong_treo_truoc             += createColumn(data.he_thong_treo_truoc);
            he_thong_treo_sau               += createColumn(data.he_thong_treo_sau);

        // Vành & Lốp xe
            loai_vanh                       += createColumn(data.loai_vanh);
            kich_thuoc_lop                  += createColumn(data.kich_thuoc_lop);
            lop_du_phong                    += createColumn(data.lop_du_phong);

        // Hệ thống phanh    
            phanh_truoc                     += createColumn(data.phanh_truoc);
            phanh_sau                       += createColumn(data.phanh_sau);

         //  Tiêu chuẩn khí thải
            tieu_chuan_khi_thai             += createColumn(data.tieu_chuan_khi_thai);

        // Tiêu thụ nhiên liệu
            ttnl_trong_do_thi               += createColumn(data.ttnl_trong_do_thi); 
            ttnl_ngoai_do_thi               += createColumn(data.ttnl_ngoai_do_thi); 
            ttnl_ket_hop                    += createColumn(data).ttnl_ket_hop;
        
        // Chế độ lái
            che_do_lai_eco_power            += createColumn(data.che_do_lai_eco_power);
            che_do_lai                      += createColumn(data.che_do_lai);

        // NGOẠI THẤT
        // Thanh cản ( giảm va chạm )
        hanh_can_giam_va_cham_truoc         += createColumn(data.che_do_lai_eco_power);
        hanh_can_giam_va_cham_sau           += createColumn(data.che_do_lai_eco_power);
        he_thong_chieu_sang_ban_ngay        += createColumn(data.he_thong_chieu_sang_ban_ngay);

        // Gương chiếu hậu ngoài
        mau_guong_chieu_hau                 += createColumn(data.mau_guong_chieu_hau);
        chuc_nang_dieu_chinh_dien           += createColumn(data.chuc_nang_dieu_chinh_dien);
        chuc_nang_gap_dien                  += createColumn(data.chuc_nang_gap_dien);
        tich_hop_den_bao_re                 += createColumn(data.tich_hop_den_bao_re);
        chuc_nang_tu_dieu_chinh_khi_lui     += createColumn(data.chuc_nang_tu_dieu_chinh_khi_lui);
        chuc_nang_say_guong                 += createColumn(data.chuc_nang_say_guong);
        chuc_nang_chong_bam_nuoc            += createColumn(data.chuc_nang_chong_bam_nuoc);
        chuc_nang_chong_chay_tu_dong        += createColumn(data.chuc_nang_chong_chay_tu_dong);
        cung_mau_than_xe                    += createColumn(data.cung_mau_than_xe);
        tich_hop_den_chao_mung              += createColumn(data.tich_hop_den_chao_mung);

        // Cụm đèn trước
        he_thong_can_bang_den_pha           += createColumn(data.he_thong_can_bang_den_pha);
        den_chieu_gan                       += createColumn(data.den_chieu_gan);
        den_chieu_xa                        += createColumn(data.den_chieu_xa);
        den_chieu_sang_ban_ngay             += createColumn(data.den_chieu_sang_ban_ngay);
        he_thong_rua_den                    += createColumn(data.he_thong_rua_den);
        he_thong_dieu_khien_den_tu_dong     += createColumn(data.he_thong_dieu_khien_den_tu_dong);
        he_thong_mo_rong_goc_chieu_tu_dong  += createColumn(data.he_thong_mo_rong_goc_chieu_tu_dong);
        he_thong_dieu_chinh_goc_chieu       += createColumn(data.he_thong_dieu_chinh_goc_chieu);
        che_do_den_cho_dan_duong            += createColumn(data.che_do_den_cho_dan_duong);
        he_thong_nhac_nho_den_sang          += createColumn(data.he_thong_nhac_nho_den_sang);

        // Cụm đèn sau
        den_vi_tri                          += createColumn(data.den_vi_tri);
        den_phanh                           += createColumn(data.den_phanh);
        den_bao_re                          += createColumn(data.den_bao_re);
        den_lui                             += createColumn(data.den_lui);

        // Đèn sương mù
        den_suong_mu_truoc                  += createColumn(data.den_suong_mu_truoc,);
        den_suong_mu_sau                    += createColumn(data.den_suong_mu_sau);
        den_bao_phanh_tren_cao              += createColumn(data.den_bao_phanh_tren_cao);
        gat_mua_gian_doan                   += createColumn(data.gat_mua_gian_doan);
        chuc_nang_say_kinh_sau              += createColumn(data.chuc_nang_say_kinh_sau);
        ang_ten                             += createColumn(data.ang_ten);
        tay_nam_cua_ngoai                   += createColumn(data.tay_nam_cua_ngoai);
        canh_huong_gio_can_sau              += createColumn(data.canh_huong_gio_can_sau);
        chan_bun_truoc_sau                  += createColumn(data.chan_bun_truoc_sau);
        ong_xa_kep                          += createColumn(data.ong_xa_kep);
        gat_mua_truoc                       += createColumn(data.gat_mua_truoc);
        gat_mua_sau                         += createColumn(data.gat_mua_sau);


        // NỘI THẤT
        loai_tay_lai                        += createColumn(data.loai_tay_lai);
        chat_lieu_tay_lai                   += createColumn(data.chat_lieu_tay_lai);
        nut_bam_dieu_khien_tich_hop         += createColumn(data.nut_bam_dieu_khien_tich_hop);
        dieu_chinh_tay_lai                  += createColumn(data.dieu_chinh_tay_lai);
        lay_chuyen_so                       += createColumn(data.lay_chuyen_so);
        tro_luc_lai                         += createColumn(data.tro_luc_lai);
        suoi_vo_lang                        += createColumn(data.suoi_vo_lang);
        ghe_hanh_khach_truoc                += createColumn(data.ghe_hanh_khach_truoc);
        loai_ghe_truoc                      += createColumn(data.loai_ghe_truoc);
        dieu_chinh_ghe_lai                  += createColumn(data.dieu_chinh_ghe_lai);
        dieu_chinh_ghe_khach                += createColumn(data.dieu_chinh_ghe_khach);
        bo_nho_vi_tri                       += createColumn(data.bo_nho_vi_tri);
        guong_chieu_hau_trong               += createColumn(data.guong_chieu_hau_trong);
        tay_nam_cua_trong                   += createColumn(data.tay_nam_cua_trong);
        loai_dong_ho                        += createColumn(data.loai_dong_ho);
        den_bao_che_do_eco                  += createColumn(data.den_bao_che_do_eco);
        chuc_nang_bao_luong_tieu_thu_nhien_lieu += createColumn(data.chuc_nang_bao_luong_tieu_thu_nhien_lieu);
        chuc_nang_bao_vi_tri_can_so             += createColumn(data.chuc_nang_bao_vi_tri_can_so);
        man_hinh_hien_thị_da_thong_tin          += createColumn(data.man_hinh_hien_thị_da_thong_tin);
        cua_so_troi                         += createColumn(data.cua_so_troi);
        chat_lieu_boc_ghe                   += createColumn(data.chat_lieu_boc_ghe);
        hang_ghe_thu_hai                    += createColumn(data.hang_ghe_thu_hai);
        tua_tay_hang_ghe_thu_hai            += createColumn(data.tua_tay_hang_ghe_thu_hai);
        hang_ghe_thu_ba                     += createColumn(data.hang_ghe_thu_ba);
        hop_lanh                            += createColumn(data.hop_lanh);
        so_ghe_ngoi                         += createColumn(data.so_ghe_ngoi);

        khoa_cua_tu_dong_theo_toc_do        += createColumn(data.khoa_cua_tu_dong_theo_toc_do);
        apple_car_play_android_auto         += createColumn(data.apple_car_play_android_auto);
        dau_dia                             += createColumn(data.dau_dia);
        so_loa                              += createColumn(data.so_loa);
        cong_ket_noi_aux                    += createColumn(data.cong_ket_noi_aux);

        cong_ket_noi_usb                    += createColumn(data.cong_ket_noi_usb);
        ket_noi_bluetooth                   += createColumn(data.ket_noi_bluetooth);
        dieu_khien_bang_giong_noi           += createColumn(data.dieu_khien_bang_giong_noi);
        
        chuc_nang_dieu_khien_tu_hang_ghe_sau += createColumn(data.chuc_nang_dieu_khien_tu_hang_ghe_sau);
        cong_ket_noi_hdmi                   += createColumn(data.cong_ket_noi_hdmi);
        he_thong_dam_thoai_ranh_tay         += createColumn(data.he_thong_dam_thoai_ranh_tay);
        
        ket_noi_wifi                        += createColumn(data.ket_noi_wifi);
        ket_noi_dien_thoai_thong_minh       += createColumn(data.ket_noi_dien_thoai_thong_minh);

        cua_so_dieu_chinh_dien              += createColumn(data.cua_so_dieu_chinh_dien);
        rem_che_nang_kinh_sau               += createColumn(data.rem_che_nang_kinh_sau);
        he_thong_dieu_hoa                   += createColumn(data.he_thong_dieu_hoa);
        cua_gio_sau                         += createColumn(data.cua_gio_sau);
        
        chia_khoa_thong_minh_khoi_dong_bang_nut_bam += createColumn(data.chia_khoa_thong_minh_khoi_dong_bang_nut_bam);
        chuc_nang_khoa_cua_tu_xa            += createColumn(data.chuc_nang_khoa_cua_tu_xa);
        phanh_tay_dien_tu                   += createColumn(data.phanh_tay_dien_tu);
        
        cop_dieu_khien_dien                 += createColumn(data.cop_dieu_khien_dien);
        giu_phanh                           += createColumn(data.giu_phanh);
        khoa_cua_dien                       += createColumn(data.khoa_cua_dien);
        he_thong_sac_khong_day              += createColumn(data.he_thong_sac_khong_day);
        he_thong_theo_doi_ap_suat_lop       += createColumn(data.he_thong_theo_doi_ap_suat_lop);

        // AN TOÀN CHỦ ĐỘNG
        he_thong_chong_bo_cung_phanh_abs                += createColumn(data.he_thong_chong_bo_cung_phanh_abs);
        he_thong_ho_tro_luc_phanh_khan_cap_ba           += createColumn(data.he_thong_ho_tro_luc_phanh_khan_cap_ba);
        he_thong_phan_phoi_luc_phanh_dien_tu_ebd        += createColumn(data.he_thong_phan_phoi_luc_phanh_dien_tu_ebd);
        
        he_thong_can_bang_dien_tu_vsc                   += createColumn(data.he_thong_can_bang_dien_tu_vsc);
        he_thong_kiem_soat_luc_keo_trc                  += createColumn(data.he_thong_kiem_soat_luc_keo_trc);
        he_thong_ho_tro_khoi_hanh_ngang_doc_hac         += createColumn(data.he_thong_ho_tro_khoi_hanh_ngang_doc_hac);
        den_bao_phanh_khan_cap_ebs                      += createColumn(data.den_bao_phanh_khan_cap_ebs);
        
        camera_lui                                      += createColumn(data.camera_lui);
        cam_bien_ho_tro_do_xe_sau                       += createColumn(data.cam_bien_ho_tro_do_xe_sau);
        cam_bien_ho_tro_do_xe_goc_sau                   += createColumn(data.cam_bien_ho_tro_do_xe_goc_sau);
        cam_bien_ho_tro_do_xe_truoc                     += createColumn(data.cam_bien_ho_tro_do_xe_truoc);
        cam_bien_ho_tro_do_xe_goc_truoc                 += createColumn(data.cam_bien_ho_tro_do_xe_goc_truoc);
        
        he_thong_ho_tro_do_deo_dac                      += createColumn(data.he_thong_ho_tro_do_deo_dac);
        canh_bao_tien_va_cham                           += createColumn(data.canh_bao_tien_va_cham);
        canh_bao_chech_lan_duong_lda                    += createColumn(data.canh_bao_chech_lan_duong_lda);
        dieu_khien_hanh_trinh_chu_dong_drcc             += createColumn(data.dieu_khien_hanh_trinh_chu_dong_drcc);
        
        he_thong_lua_chon_van_toc_vuot_dia_hinh         += createColumn(data.he_thong_lua_chon_van_toc_vuot_dia_hinh);
        he_thong_thich_nghi_dia_hinh_mts                += createColumn(data.he_thong_thich_nghi_dia_hinh_mts);
        he_thong_kiem_soat_diem_mu_bsm                  += createColumn(data.he_thong_kiem_soat_diem_mu_bsm);
        camera_360_do                                   += createColumn(data.camera_360_do);


        // AN TOÀN BỊ ĐỘNG
        tui_khi_nguoi_lai_hanh_khach_phia_truoc         += createColumn(data.tui_khi_nguoi_lai_hanh_khach_phia_truoc);
        tui_khi_ben_hong_phia_truoc                     += createColumn(data.tui_khi_ben_hong_phia_truoc);
        tui_khi_ben_hong_phia_sau                       += createColumn(data.tui_khi_ben_hong_phia_sau);

        tui_khi_rem                                     += createColumn(data.tui_khi_rem);
        tui_khi_dau_goi_nguoi_lai                       += createColumn(data.tui_khi_dau_goi_nguoi_lai);
        tui_khi_dau_goi_hanh_khach_phia_truoc           += createColumn(data.tui_khi_dau_goi_hanh_khach_phia_truoc);
        so_luong_tui_khi                                += createColumn(data.so_luong_tui_khi);
        
        day_dai_an_toan                                 += createColumn(data.day_dai_an_toan);
        day_dai_an_toan_hang_ghe_thu_2                  += createColumn(data.day_dai_an_toan_hang_ghe_thu_2);
        day_dai_an_toan_hang_ghe_thu_3                  += createColumn(data.day_dai_an_toan_hang_ghe_thu_3);
        
        day_dai_an_toan_hang_ghe_truoc                  += createColumn(data.day_dai_an_toan_hang_ghe_truoc);
        cot_lai_tu_do                                   += createColumn(data.cot_lai_tu_do);
        ghe_co_cau_truc_giam_chan_thuong_co             += createColumn(data.ghe_co_cau_truc_giam_chan_thuong_co);
        khung_xe_goa                                    += createColumn(data.khung_xe_goa);
        ban_dap_phanh_tu_do                             += createColumn(data.ban_dap_phanh_tu_do);

        // AN NINH  
        he_thong_bao_dong                               += createColumn(data.he_thong_bao_dong);
        he_thong_ma_hoa_khoa_dong_co                    += createColumn(data.he_thong_ma_hoa_khoa_dong_co);
    }   


    // ĐỘNG CƠ & KHUNG XE
    function dongCoVaKhungXe() {
        return  table.tableKichThuoc(d_x_r_x_c__mm_, chieu_dai_co_so, khoang_sang_gam_xe, ban_kinh_vong_quay_toi_thieu, 
                trong_luong_khong_tai, trong_luong_toan_tai, chieu_rong_co_so_truoc_sau, kich_thuoc_noi_that) +

                table.tableDongCo (toc_do_toi_da, loai_dong_co, dung_tich_xy_lanh_cc, cong_sust_toi_da__kw_hp_vongphut, 
                mo_men_xoan_toi_da_nm_vongphut, dung_tich_binh_nhien_lieu, 
                he_thong_nhien_lieu, nhien_lieu, so_xy_lanh, bo_tri_xy_lanh) +

                table.tableHeThongTruyenDong(he_thong_truyen_dong) +
                table.tableHopSo(hop_so) +
                table.tableHeThongTreo(he_thong_treo_truoc, he_thong_treo_sau) +
                table.tableVanhVaLopXe(loai_vanh, kich_thuoc_lop, lop_du_phong) +
                table.tablePhanh(phanh_truoc, phanh_sau) + 
                table.tableTieuChuanKhiThai(tieu_chuan_khi_thai) +
                table.tableTieuThuNhienLieu(ttnl_trong_do_thi, ttnl_ngoai_do_thi, ttnl_ket_hop) +
                table.tableCheDoLaiEco(che_do_lai_eco_power)+
                table.tableCheDoLai(che_do_lai);
    }

    // NGOẠI THẤT
    function ngoaiThat() {
        return  table.tableThanhCanGiamVaCham(hanh_can_giam_va_cham_truoc, hanh_can_giam_va_cham_sau) + 
                table.tableheThongChieuSangBanNgay(he_thong_chieu_sang_ban_ngay) +

                table.tableGuongChieuHauNgoai (mau_guong_chieu_hau, chuc_nang_dieu_chinh_dien, chuc_nang_gap_dien, tich_hop_den_bao_re, 
                    chuc_nang_tu_dieu_chinh_khi_lui, chuc_nang_say_guong, 
                    chuc_nang_chong_bam_nuoc, chuc_nang_chong_chay_tu_dong, cung_mau_than_xe, tich_hop_den_chao_mung) +

                table.tableCumDenTruoc (he_thong_can_bang_den_pha, den_chieu_gan, den_chieu_xa, den_chieu_sang_ban_ngay, 
                    he_thong_rua_den, he_thong_dieu_khien_den_tu_dong, he_thong_mo_rong_goc_chieu_tu_dong, 
                    he_thong_dieu_chinh_goc_chieu, che_do_den_cho_dan_duong, he_thong_nhac_nho_den_sang) +

                table.tableCumDenSau(den_vi_tri, den_phanh, den_bao_re, den_lui) +
                table.tableDenSuongMu(den_suong_mu_truoc, den_suong_mu_sau) +
                table.tableDenBaoPhanhTrenCao(den_bao_phanh_tren_cao) +
                table.tableGatMuaGianDoan(gat_mua_gian_doan) +
                table.tableChucNangSayKinhSau(chuc_nang_say_kinh_sau) +
                table.tableAngTen(ang_ten) + 
                table.tableTayNamCuaNgoai(tay_nam_cua_ngoai) +
                table.tableCanhHuongGioCanSau(canh_huong_gio_can_sau) + 
                table.tableChanBunTruocSau(chan_bun_truoc_sau) +
                table.tableOngXaKep(ong_xa_kep) +
                table.tableGatMua(gat_mua_truoc, gat_mua_sau);
    }

    // NỘI THẤT
    function noiThat() {
        return  table.tablekinh(loai_tay_lai, chat_lieu_tay_lai, nut_bam_dieu_khien_tich_hop, dieu_chinh_tay_lai,
                    lay_chuyen_so, tro_luc_lai, suoi_vo_lang) +

                table.tableGheTruoc(ghe_hanh_khach_truoc, loai_ghe_truoc, dieu_chinh_ghe_lai, dieu_chinh_ghe_khach,
                    bo_nho_vi_tri) +
                table.tableGuongChieuHauTrong(guong_chieu_hau_trong) +
                table.tableTayNamCuaTrong(tay_nam_cua_trong) +

                table.tableCumDongHo(loai_dong_ho, den_bao_che_do_eco, chuc_nang_bao_luong_tieu_thu_nhien_lieu, chuc_nang_bao_vi_tri_can_so,
                    man_hinh_hien_thị_da_thong_tin) +
                
                table.tableCuaSoTroi(cua_so_troi) +
                table.tableChatLieuBocGhe(chat_lieu_boc_ghe) +

                table.tableGheSau(hang_ghe_thu_hai, tua_tay_hang_ghe_thu_hai, hang_ghe_thu_ba) +
                table.tableHopLanh(hop_lanh) +
                table.tableSoGhe(so_ghe_ngoi);
    }

    // TIỆN NGHI
    function tienNghi() {
        return  table.tableKhoaCuaTuDongTheoTocDo(khoa_cua_tu_dong_theo_toc_do) +
        
                table.tableHeThongAmThanh (apple_car_play_android_auto, dau_dia, so_loa, cong_ket_noi_aux, 
                    cong_ket_noi_usb, ket_noi_bluetooth, dieu_khien_bang_giong_noi, 
                    chuc_nang_dieu_khien_tu_hang_ghe_sau, cong_ket_noi_hdmi, he_thong_dam_thoai_ranh_tay, 
                    ket_noi_wifi, ket_noi_dien_thoai_thong_minh) +

                table.tableCuaSoDieuChinhDien(cua_so_dieu_chinh_dien) +
                table.tableRemCheNangPhiaSau(rem_che_nang_kinh_sau) +
                table.tableHeThongDieuHoa(he_thong_dieu_hoa) +
                table.tableCuaGioSau(cua_gio_sau) +
                table.tableChiaKhoaThongMinh(chia_khoa_thong_minh_khoi_dong_bang_nut_bam) +
                table.tableChucNangKhoaCuaTuXa(chuc_nang_khoa_cua_tu_xa) +
                table.tablePhanhTayDienTu(phanh_tay_dien_tu) +
                table.tableCopDieuKhienDien(cop_dieu_khien_dien) +
                table.tableGiuPhanh(giu_phanh) +
                table.tableKhoaCuaDien(khoa_cua_dien) +
                table.tableHeThongSacKhongGiay(he_thong_sac_khong_day) +
                table.tableHeThongTheoDoiApSuatLop(he_thong_theo_doi_ap_suat_lop)

    }

    // AN TOÀN CHỦ ĐỘNG
    function anToanChuDong() {
        return  table.tableHeThongChongBoCungPhanhAbs(he_thong_chong_bo_cung_phanh_abs) +
                table.tableHeThongHoTroPhanhKhanCapBa(he_thong_ho_tro_luc_phanh_khan_cap_ba) +
                table.tableHeThongPhanhDienTuEbd(he_thong_phan_phoi_luc_phanh_dien_tu_ebd) +
                table.tableHeThongCanBangDienTuVsc(he_thong_can_bang_dien_tu_vsc) +
                table.tableHeThongKiemSoatLucKeoTrc(he_thong_kiem_soat_luc_keo_trc) +
                table.tableHeThongHoTroKhoiHanhNgangDoc(he_thong_ho_tro_khoi_hanh_ngang_doc_hac) +
                table.tableDenBaoPhanhKhanCapEbs(den_bao_phanh_khan_cap_ebs) +
                table.tableCameraLui(camera_lui) +
                table.tableCamBienDoXe(cam_bien_ho_tro_do_xe_sau, cam_bien_ho_tro_do_xe_goc_sau,
                    cam_bien_ho_tro_do_xe_truoc, cam_bien_ho_tro_do_xe_goc_truoc) +
                    
                table.tableHeThongHoTroDoDeoDac(he_thong_ho_tro_do_deo_dac) + 
                table.tableCanhBaoTienVaCham(canh_bao_tien_va_cham) +
                table.tableCanhBaoLechLanDuong(canh_bao_chech_lan_duong_lda) +
                table.tableDieuKhienHanhTrinhChuDong(dieu_khien_hanh_trinh_chu_dong_drcc) +
                table.tableHeThongLuaChonVanToc(he_thong_lua_chon_van_toc_vuot_dia_hinh) +
                table.tableHeThongThichNghiDiaHinh(he_thong_thich_nghi_dia_hinh_mts) +
                table.tableHeThongKiemSoatDiemMu(he_thong_kiem_soat_diem_mu_bsm) +
                table.tableCamera360(camera_360_do);
    }

    // AN TOÀN BỊ ĐỘNG
    function anToanBiDong() {
        return  table.tableTuiKhi(tui_khi_nguoi_lai_hanh_khach_phia_truoc, tui_khi_ben_hong_phia_truoc, tui_khi_ben_hong_phia_sau, 
                tui_khi_rem, tui_khi_dau_goi_nguoi_lai, tui_khi_dau_goi_hanh_khach_phia_truoc, so_luong_tui_khi) +  

                table.tableDayDaiAnToan(day_dai_an_toan, day_dai_an_toan_hang_ghe_thu_2, day_dai_an_toan_hang_ghe_thu_3,
                    day_dai_an_toan_hang_ghe_truoc) +
                table.tableCotLaiTuDo(cot_lai_tu_do) +
                table.tableGheCoCauTrucGiamChanThuongCo(ghe_co_cau_truc_giam_chan_thuong_co) +
                table.tableKhungXeGoa(khung_xe_goa) +
                table.tableBanDapPhanhTuDo(ban_dap_phanh_tu_do);
    }

    // AN NINH
    function anNinh() {
        return  table.tableHeThongBaoDong(he_thong_bao_dong) +
                table.tableHeThongMaHoaKhoaDongCo(he_thong_ma_hoa_khoa_dong_co);
    }

    
    return {
        thead : thead,
        dongCoVaKhungXe : dongCoVaKhungXe(),
        ngoaiThat : ngoaiThat(),
        noiThat: noiThat(),
        tienNghi: tienNghi(),
        anToanChuDong: anToanChuDong(),
        anToanBiDong: anToanBiDong(),
        anNinh: anNinh()
    }
}

export default handleView;