<?php

class Laporan_model extends CI_Model {

	public function tampil_data_master_barang() {
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$kd_brg_1 = $this->input->post('KD_BRG_1');
		$kd_brg_2 = $this->input->post('KD_BRG_2');
		$q1 = "SELECT sp_barang.kd_brg AS KD_PEG,
				sp_barang.kd_brg AS KD_BRG,
				sp_barang.na_brg AS NA_BRG,
				sp_barang.satuan AS SATUAN,
				CASE sp_barang.aktif
					WHEN '1' THEN 'AKTIF'
					WHEN '0' THEN 'TIDAK AKTIF'
				END AS `STATUS`,
				sp_barang.dr AS DR,
				sp_barang.flag AS FLAG
			FROM sp_barang
			WHERE sp_barang.dr='$dr'
			AND sp_barang.flag='$sub' 
			AND sp_barang.kd_brg BETWEEN '" . $kd_brg_1 . "' AND '" . $kd_brg_2 . "'
			ORDER BY sp_barang.kd_brg";
        return $this->db->query($q1);
	}

	public function tampil_data_kartu_stok() {
		$bulan = substr($this->input->post('TGL_1'),0,2);
		$tahun = substr($this->input->post('TGL_1'),-4);
		$per = $tahun.$bulan;
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$rak_1 = $this->input->post('RAK_1');
		$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
		$q1 = "SELECT RAK, BARANG, TGL, NO_BUKTI, AW, MA, KE, RKE (AW+MA-KE+RKE) AS AK
				FROM (
					SELECT sp_belid.RAK AS RAK,
						brgd.NA_BRG AS NA_BRG,
						sp_belid.TGL AS TGL,
						0 AS AW, 
						0 AS MA, 
						0 AS KE, 
						0 AS RKE, 
						0 AS AK
					FROM brgd, sp_belid, sp_pakaid
					WHERE brgd.KD_BRG=sp_belid.KD_BRG
					AND sp_belid.TGL='$tgl_1'
					AND sp_belid.DR='$dr'
					AND sp_belid.SP='$sub'
					AND sp_belid.ATK=0
					GROUP BY brgd.KD_BRG
					UNION ALL
					SELECT sp_pakaid.RAK AS RAK,
						brgd.NA_BRG AS NA_BRG,
						'' AS SATUAN,
						0 AS AW, 
						'' AS NO_BUKTI_MA,
						0 AS MA,
						sp_pakaid.NO_BUKTI AS NO_BUKTI_KE,
						sp_pakaid.QTY AS KE,
						sp_pakaid.NO_BUKTI AS NO_BUKTI_RKE,
						0 AS RKE,
						0 AS AK
					FROM brgd, sp_pakaid
					WHERE brgd.KD_BRG=sp_pakaid.KD_BRG
					AND sp_pakaid.TGL='$tgl_1'
					AND sp_pakaid.DR='$dr'
					AND sp_pakaid.SP='$sub'
					AND sp_pakaid.ATK=0
					GROUP BY brgd.KD_BRG
				) AS AAA ORDER BY NA_BRG";
		return $this->db->query($q1);
	}

	public function tampil_data_lpb_harian() {
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
		$tgl_2 = date("Y-m-d", strtotime($this->input->post('TGL_2', TRUE)));
		$q1 = "SELECT sp_beli.no_bukti AS NO_BUKTI,
				sp_beli.tgl AS TGL,
				CONCAT(sp_belid.kd_brg,' - ',sp_belid.na_brg) AS BARANG,
				sp_belid.satuan AS SATUAN,
				sp_belid.qty AS QTY,
				'-' AS NO_PP,
				sp_belid.dr AS DR,
				sp_belid.sp AS SP
			FROM sp_belid, sp_beli
			WHERE sp_beli.no_bukti=sp_belid.no_bukti 
			AND sp_belid.tgl >='$tgl_1'
			AND sp_belid.tgl <='$tgl_2'
			AND sp_belid.dr='$dr'
			AND sp_belid.sp='$sub'
			ORDER BY sp_belid.tgl";
		return $this->db->query($q1);
	}

	public function tampil_data_harian() {
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
		$q1 = "SELECT RAK, NA_BRG, SATUAN, AW, NO_BUKTI_MA, MA, NO_BUKTI_KE, KE, NO_BUKTI_RKE, RKE, (AW+MA-KE+RKE) AS AK
				FROM (
					SELECT sp_belid.RAK AS RAK,
						brgd.NA_BRG AS NA_BRG,
						sp_belid.SATUAN AS SATUAN,
						0 AS AW, 
						sp_belid.NO_BUKTI_BL_BELI AS NO_BUKTI_MA,
						sp_belid.QTY AS MA,
						'' AS NO_BUKTI_KE,
						0 AS KE,
						'' AS NO_BUKTI_RKE,
						0 AS RKE,
						0 AS AK
					FROM brgd, sp_belid, sp_pakaid
					WHERE brgd.KD_BRG=sp_belid.KD_BRG
					AND sp_belid.TGL='$tgl_1'
					AND sp_belid.DR='$dr'
					AND sp_belid.SP='$sub'
					AND sp_belid.ATK=0
					GROUP BY brgd.KD_BRG
					UNION ALL
					SELECT sp_pakaid.RAK AS RAK,
						brgd.NA_BRG AS NA_BRG,
						'' AS SATUAN,
						0 AS AW, 
						'' AS NO_BUKTI_MA,
						0 AS MA,
						sp_pakaid.NO_BUKTI AS NO_BUKTI_KE,
						sp_pakaid.QTY AS KE,
						sp_pakaid.NO_BUKTI AS NO_BUKTI_RKE,
						0 AS RKE,
						0 AS AK
					FROM brgd, sp_pakaid
					WHERE brgd.KD_BRG=sp_pakaid.KD_BRG
					AND sp_pakaid.TGL='$tgl_1'
					AND sp_pakaid.DR='$dr'
					AND sp_pakaid.SP='$sub'
					AND sp_pakaid.ATK=0
					GROUP BY brgd.KD_BRG
				) AS AAA ORDER BY NA_BRG";
		return $this->db->query($q1);
	}

	// public function tampil_data_harian() {
	// 	$bulan = substr($this->input->post('TGL_1'),0,2);
	// 	$tahun = substr($this->input->post('TGL_1'),-4);
	// 	$per = $tahun.$bulan;
	// 	$dr = $this->session->userdata['dr'];
	// 	$sub = $this->session->userdata['sub'];
	// 	$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
	// 	$q1 = "SELECT '-' AS RAK,
	// 		brgd.NA_BRG AS NA_BRG,
	// 		'-' AS SATUAN,
	// 		CASE 'AW$bulan'
	// 			WHEN 'AW$bulan'=01 THEN brgd.AW01
	// 			WHEN 'AW$bulan'=02 THEN brgd.AW02
	// 			WHEN 'AW$bulan'=03 THEN brgd.AW03
	// 			WHEN 'AW$bulan'=04 THEN brgd.AW04
	// 			WHEN 'AW$bulan'=05 THEN brgd.AW05
	// 			WHEN 'AW$bulan'=06 THEN brgd.AW06
	// 			WHEN 'AW$bulan'=07 THEN brgd.AW07
	// 			WHEN 'AW$bulan'=08 THEN brgd.AW08
	// 			WHEN 'AW$bulan'=09 THEN brgd.AW09
	// 			WHEN 'AW$bulan'=10 THEN brgd.AW10
	// 			WHEN 'AW$bulan'=11 THEN brgd.AW11
	// 			WHEN 'AW$bulan'=12 THEN brgd.AW12
	// 		END AS AW,
	// 		sp_belid.NO_BUKTI_BL_BELI AS NO_BUKTI_BL_BELI,
	// 		sp_belid.QTY AS MA,
	// 		sp_pakaid.NO_BUKTI AS NO_BUKTI_PAKAI,
	// 		sp_pakaid.QTY AS KE,
	// 		CASE 'AW$bulan'
	// 			WHEN 'AW$bulan'=01 THEN (brgd.AW01+sp_belid.QTY-sp_pakaid.QTY)
	// 			WHEN 'AW$bulan'=02 THEN (brgd.AW02+sp_belid.QTY-sp_pakaid.QTY)
	// 			WHEN 'AW$bulan'=03 THEN (brgd.AW03+sp_belid.QTY-sp_pakaid.QTY)
	// 			WHEN 'AW$bulan'=04 THEN (brgd.AW04+sp_belid.QTY-sp_pakaid.QTY)
	// 			WHEN 'AW$bulan'=05 THEN (brgd.AW05+sp_belid.QTY-sp_pakaid.QTY)
	// 			WHEN 'AW$bulan'=06 THEN (brgd.AW06+sp_belid.QTY-sp_pakaid.QTY)
	// 			WHEN 'AW$bulan'=07 THEN (brgd.AW07+sp_belid.QTY-sp_pakaid.QTY)
	// 			WHEN 'AW$bulan'=08 THEN (brgd.AW08+sp_belid.QTY-sp_pakaid.QTY)
	// 			WHEN 'AW$bulan'=09 THEN (brgd.AW09+sp_belid.QTY-sp_pakaid.QTY)
	// 			WHEN 'AW$bulan'=10 THEN (brgd.AW10+sp_belid.QTY-sp_pakaid.QTY)
	// 			WHEN 'AW$bulan'=11 THEN (brgd.AW11+sp_belid.QTY-sp_pakaid.QTY)
	// 			WHEN 'AW$bulan'=12 THEN (brgd.AW12+sp_belid.QTY-sp_pakaid.QTY)
	// 		END AS AK
	// 		FROM brgd, sp_belid, sp_pakaid
	// 		WHERE sp_belid.TGL<'$tgl_1'
	// 		AND sp_belid.DR='$dr'
	// 		AND sp_belid.SP='$sub'
	// 		AND sp_belid.KD_BRG=brgd.KD_BRG
	// 		AND sp_pakaid.KD_BRG=brgd.KD_BRG
	// 		GROUP BY brgd.KD_BRG
	// 		ORDER BY brgd.KD_BRG, brgd.NA_BRG";
	// 	return $this->db->query($q1);
	// }

}