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
		$periode = $tahun.$bulan;
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$per = $this->session->userdata['periode'];
		$rak_1 = $this->input->post('RAK_1');
		$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
		if ($rak_1 == '') {
			$rak_1x = ' ';
		} else {
			$rak_1x = " AND brg.RAK='$rak_1'";
		}
		$q3 = "CALL STOK_KARTU('MTS','$tg1','$tg2','GDG','$kd_brg1x','$kd_brg2x')";
		// $q1 = "SELECT RAK, NA_BRG, TGL, NO_BUKTI, AW, MA, KE, RKE, AK 
		// 	FROM (
		// 		SELECT brg.RAK AS RAK, 
		// 					brgd.NA_BRG AS NA_BRG, 
		// 					'' AS TGL, 
		// 					'' AS NO_BUKTI, 
		// 					CASE 'AW$bulan'
		// 						WHEN 'AW$bulan'=01 THEN brgd.AW01
		// 						WHEN 'AW$bulan'=02 THEN brgd.AW02
		// 						WHEN 'AW$bulan'=03 THEN brgd.AW03
		// 						WHEN 'AW$bulan'=04 THEN brgd.AW04
		// 						WHEN 'AW$bulan'=05 THEN brgd.AW05
		// 						WHEN 'AW$bulan'=06 THEN brgd.AW06
		// 						WHEN 'AW$bulan'=07 THEN brgd.AW07
		// 						WHEN 'AW$bulan'=08 THEN brgd.AW08
		// 						WHEN 'AW$bulan'=09 THEN brgd.AW09
		// 						WHEN 'AW$bulan'=10 THEN brgd.AW10
		// 						WHEN 'AW$bulan'=11 THEN brgd.AW11
		// 						WHEN 'AW$bulan'=12 THEN brgd.AW12
		// 					END AS AW,
		// 					0 AS MA, 
		// 					0 AS KE, 
		// 					0 AS RKE,
		// 					0 AS AK
		// 		FROM brg, brgd, sp_belid, sp_pakaid
		// 		WHERE brg.KD_BRG=brgd.KD_BRG
		// 		AND brgd.KD_BRG=sp_belid.KD_BRG
		// 		$rak_1x
		// 		AND sp_belid.DR='$dr'
		// 		AND sp_belid.SP='$sub'
		// 		AND sp_belid.TGL<'$tgl_1'
		// 		AND sp_belid.PER='$per'
		// 		AND sp_belid.ATK=0
		// 		GROUP BY sp_belid.TGL
		// 		UNION ALL
		// 		SELECT brg.RAK AS RAK, 
		// 					brgd.NA_BRG AS NA_BRG, 
		// 					sp_belid.TGL AS TGL, 
		// 					sp_belid.NO_BUKTI_BL_BELI AS NO_BUKTI, 
		// 					CASE 'AW$bulan'
		// 						WHEN 'AW$bulan'=01 THEN brgd.AW01
		// 						WHEN 'AW$bulan'=02 THEN brgd.AW02
		// 						WHEN 'AW$bulan'=03 THEN brgd.AW03
		// 						WHEN 'AW$bulan'=04 THEN brgd.AW04
		// 						WHEN 'AW$bulan'=05 THEN brgd.AW05
		// 						WHEN 'AW$bulan'=06 THEN brgd.AW06
		// 						WHEN 'AW$bulan'=07 THEN brgd.AW07
		// 						WHEN 'AW$bulan'=08 THEN brgd.AW08
		// 						WHEN 'AW$bulan'=09 THEN brgd.AW09
		// 						WHEN 'AW$bulan'=10 THEN brgd.AW10
		// 						WHEN 'AW$bulan'=11 THEN brgd.AW11
		// 						WHEN 'AW$bulan'=12 THEN brgd.AW12
		// 					END AS AW,
		// 					sp_belid.QTY AS MA, 
		// 					0 AS KE, 
		// 					0 AS RKE,
		// 					0 AS AK
		// 		FROM brg, brgd, sp_belid, sp_pakaid
		// 		WHERE brg.KD_BRG=brgd.KD_BRG
		// 		AND brgd.KD_BRG=sp_belid.KD_BRG
		// 		$rak_1x
		// 		AND sp_belid.DR='$dr'
		// 		AND sp_belid.SP='$sub'
		// 		AND sp_belid.TGL<'$tgl_1'
		// 		AND sp_belid.PER='$per'
		// 		AND sp_belid.ATK=0
		// 		GROUP BY sp_belid.TGL
		// 		UNION ALL
		// 		SELECT brg.RAK AS RAK, 
		// 					brgd.NA_BRG AS NA_BRG, 
		// 					sp_pakaid.TGL AS TGL, 
		// 					sp_pakaid.NO_BUKTI AS NO_BUKTI, 
		// 					CASE 'AW$bulan'
		// 						WHEN 'AW$bulan'=01 THEN brgd.AW01
		// 						WHEN 'AW$bulan'=02 THEN brgd.AW02
		// 						WHEN 'AW$bulan'=03 THEN brgd.AW03
		// 						WHEN 'AW$bulan'=04 THEN brgd.AW04
		// 						WHEN 'AW$bulan'=05 THEN brgd.AW05
		// 						WHEN 'AW$bulan'=06 THEN brgd.AW06
		// 						WHEN 'AW$bulan'=07 THEN brgd.AW07
		// 						WHEN 'AW$bulan'=08 THEN brgd.AW08
		// 						WHEN 'AW$bulan'=09 THEN brgd.AW09
		// 						WHEN 'AW$bulan'=10 THEN brgd.AW10
		// 						WHEN 'AW$bulan'=11 THEN brgd.AW11
		// 						WHEN 'AW$bulan'=12 THEN brgd.AW12
		// 					END AS AW,
		// 					0 AS MA, 
		// 					sp_pakaid.QTY AS KE, 
		// 					0 AS RKE,
		// 					0 AS AK
		// 		FROM brg, brgd, sp_pakaid
		// 		WHERE brg.KD_BRG=brgd.KD_BRG
		// 		AND brgd.KD_BRG=sp_pakaid.KD_BRG
		// 		$rak_1x
		// 		AND sp_pakaid.DR='$dr'
		// 		AND sp_pakaid.SP='$sub'
		// 		AND sp_pakaid.TGL<'$tgl_1'
		// 		AND sp_pakaid.PER='$per'
		// 		AND sp_pakaid.FLAG='PK'
		// 		AND sp_pakaid.ATK=0
		// 		GROUP BY sp_pakaid.TGL
		// 	) AS AAA ORDER BY TGL";
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



}