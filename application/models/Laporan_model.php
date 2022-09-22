<?php

class Laporan_model extends CI_Model
{

	public function tampil_data_master_barang()
	{
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$kd_bhn_1 = $this->input->post('KD_BHN_1');
		$kd_bhn_2 = $this->input->post('KD_BHN_2');
		$q1 = "SELECT bhn.KD_BHN AS KD_BHN,
				bhn.NA_BHN AS NA_BHN,
				bhn.SATUAN AS SATUAN,
				CASE bhn.AKTIF
					WHEN '0' THEN 'TIDAK AKTIF'
					WHEN '1' THEN 'AKTIF'
				END AS 'STATUS'
			FROM bhn
			WHERE bhn.DR='$dr'
			AND bhn.FLAG='SP'
			AND bhn.SUB='$sub'
			-- AND bhn.KD_BHN BETWEEN '$kd_bhn_1' AND '$kd_bhn_2'
			ORDER BY KD_BHN";
		return $this->db->query($q1);
	}

	public function tampil_data_master_bagian()
	{
		$dr = $this->session->userdata['dr'];
		$q1 = "SELECT no_bukti AS NO_BUKTI,
			kode AS KODE,
			bagian AS BAGIAN,
			nama AS NAMA,
			total_qty AS TOTAL_QTY
			FROM sp_bagian 
			WHERE dr = '$dr'";
		return $this->db->query($q1);
	}

	public function tampil_data_triwulan()
	{
		$q1 = "SELECT
				inventaris.NO_BUKTI,
				inventaris.NA_BAGIAN AS BAGIAN,
				inventaris.KD_BAGIAN AS KODE,
				inventaris.NAMA,
				inventaris.TGL,
				inventarisd.JENIS,
				inventarisd.MERK,
				inventarisd.SATUAN,
				inventarisd.QTY,
				inventarisd.KET
			FROM
				inventaris,
				inventarisd
			WHERE
				inventaris.NO_BUKTI = inventarisd.NO_BUKTI
			AND
				inventaris.FLAG = 'INV'
			ORDER BY
				inventarisd.NO_BUKTI,
				inventarisd.JENIS";
		return $this->db->query($q1);
	}

	public function tampil_data_barang_per_ruangan()
	{
		$jenis_1 = $this->input->post('JENIS_1');
		$q1 = "SELECT
				'-' AS KODE,
				inventaris.NAMA,
				inventaris.NA_BAGIAN,
			
				inventarisd.JENIS,
				inventarisd.MERK,
				inventarisd.SATUAN,
				inventarisd.QTY,
				inventarisd.KET
			FROM
				inventaris,
				inventarisd
			WHERE
				inventaris.NO_BUKTI = inventarisd.NO_BUKTI
			AND
				inventaris.FLAG = 'INV'
			AND
				inventarisd.JENIS <> ' '
			AND
				inventarisd.JENIS = '$jenis_1'
			ORDER BY
				inventarisd.NO_BUKTI,
				inventarisd.JENIS";
		return $this->db->query($q1);
	}

	public function tampil_data_inventaris_cetakan()
	{
		$dr = $this->session->userdata['dr'];
		$cetak_1 = $this->input->post('CETAK_1');
		$q1 = "SELECT * FROM sp_invenc WHERE DR='$dr' AND CETAK='$cetak_1'";
		return $this->db->query($q1);
	}

	public function tampil_data_inventaris_cetakan_pernama()
	{
		$dr = $this->session->userdata['dr'];
		$cetak_1 = $this->input->post('CETAK_1');
		$q1 = "SELECT * FROM sp_invenc WHERE DR='$dr' AND CETAK='$cetak_1'";
		return $this->db->query($q1);
	}

	public function tampil_data_globalcetakan()
	{
		$dr = $this->session->userdata['dr'];
		$cetak_1 = $this->input->post('CETAK_1');
		$filter_cetak = " ";
		if ($this->input->post('CETAK_1', TRUE) != '') {
			$filter_cetak = "AND sp_invenc.cetak = '$cetak_1'";
		}
		$q1 = "SELECT *	FROM sp_invenc
			WHERE DR = '$dr'
			$filter_cetak
			ORDER BY sp_invenc.CETAK";
		return $this->db->query($q1);
	}

	public function tampil_data_kartustok()
	{
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$rak_1 = $this->input->post('RAK_1');
		$per_1 = $this->input->post('PER_1');
		if ($per_1 == '') {
			$per_1 = $this->session->userdata['periode']; 
		} else {
			$per_1 = $this->input->post('PER_1');
		}
		$q1 = "CALL spp_kartustok('$rak_1', '$dr', '$sub', '$per_1')";
		return $this->db->query($q1);
	}

	public function tampil_data_kartustok_atk()
	{
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$rak_1 = $this->input->post('RAK_1');
		$per = $this->session->userdata['periode'];
		$q1 = "CALL spp_kartustok_atk('$rak_1', '$dr', '$sub', '$per')";
		return $this->db->query($q1);
	}

	public function tampil_data_lpb_harian()
	{
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$per = $this->session->userdata['periode'];
		$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
		$q1 = "SELECT beli.PER AS PER,
				belid.TGL AS TGL,
				belid.NO_BELI AS NO_BUKTI_BL_BELI,
				CONCAT(belid.KD_BHN,' - ',belid.NA_BHN) AS BARANG,
				belid.SATUAN AS SATUAN,
				belid.QTY AS QTY,
				belid.NO_PO AS NO_PO,
				belid.NO_PP AS NO_PP,
				belid.NO_BELI AS NO_BUKTI,
				belid.REC AS REC
			FROM beli, belid
			WHERE beli.NO_BELI = belid.NO_BELI
			AND belid.TGL='$tgl_1'
			AND beli.SUB='$sub'
			AND beli.FLAG2='SP'
			ORDER BY belid.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_harian()
	{
		// $periode = $tahun.$bulan;
		$dr = $this->session->userdata['dr'];
		$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
		$sub = $this->session->userdata['sub'];
		$per = $this->session->userdata['periode'];
		if ($tgl_1 == '') {
			$bulan = Date('m');
		} else {
			$bulan = date("m", strtotime($tgl_1));
		}
		$tahun = substr($this->input->post('TGL_1'), -4);
		$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
		$q1 = "SELECT TGL, RAK, NA_BHN, SATUAN, AW, NO_BUKTI_MA, MA, NO_BUKTI_KE, KE, NO_BUKTI_RKE, RKE, AK,(AW+MA) T_MA,(AW-KE) T_KE, (MA+RKE) T_RKE
				FROM (
					SELECT '$tgl_1' AS TGL,
						bhnd.RAK AS RAK,
						bhnd.NA_BHN AS NA_BHN,
						bhn.SATUAN AS SATUAN,
						bhnd.AW$bulan+(belid.QTY-pakaid.QTY) AS AW,
						'' AS NO_BUKTI_MA,
						0 AS MA,
						'' AS NO_BUKTI_KE,
						0 AS KE,
						'' AS NO_BUKTI_RKE,
						0 AS RKE,
						0 AS AK
					FROM bhn, bhnd, belid, pakaid
					WHERE bhn.KD_BHN = bhnd.KD_BHN
					AND bhn.KD_BHN = belid.KD_BHN
					AND bhn.KD_BHN = pakaid.KD_BHN
					AND bhnd.YER = '$tahun'
					AND bhn.FLAG2 = '$sub'
					AND bhn.DR = '$dr'
					-- AND belid.PER = '$per'
					AND belid.TGL < '$tgl_1'
					AND pakaid.PER = '$per'
					AND pakaid.TGL < '$tgl_1'
					GROUP BY bhnd.NA_BHN
					UNION ALL
					SELECT belid.TGL AS TGL, 
						belid.RAK AS RAK,
						belid.NA_BHN AS NA_BHN,
						belid.SATUAN AS SATUAN,
						0 AS AW,
						belid.NO_BUKTI AS NO_BUKTI_MA,
						belid.QTY AS MA,
						'' AS NO_BUKTI_KE,
						0 AS KE,
						'' AS NO_BUKTI_RKE,
						0 AS RKE,
						0 AS AK
					FROM belid, bhnd
					WHERE bhnd.kd_bhn = belid.kd_bhn
					-- AND belid.PER = '$per'
					AND belid.DR = '$dr'
					AND bhnd.DR = '$dr'
					AND belid.SP = '$sub'
					AND belid.TGL = '$tgl_1'
					-- GROUP BY belid.NO_BUKTI
					UNION ALL
					SELECT pakaid.TGL AS TGL, 
						pakaid.RAK AS RAK,
						pakaid.NA_BHN AS NA_BHN,
						pakaid.SATUAN AS SATUAN,
						0 AS AW,
						'' AS NO_BUKTI_MA,
						0 AS MA,
						pakaid.NO_BUKTI AS NO_BUKTI_KE,
						pakaid.QTY AS KE,
						'' AS NO_BUKTI_RKE,
						0 AS RKE,
						0 AS AK
					FROM pakaid, bhnd
					WHERE bhnd.kd_bhn = pakaid.KD_BHN
					-- AND pakaid.PER = '$per'
					AND pakaid.DR = '$dr'
					AND bhnd.DR = '$dr'
					AND pakaid.SUB = '$sub'
					AND pakaid.TGL = '$tgl_1'
					AND pakaid.FLAG = 'PK'
					AND pakaid.FLAG2 = 'SP'
					-- GROUP BY pakaid.NO_BUKTI
					UNION ALL
					SELECT pakaid.TGL AS TGL, 
						pakaid.RAK AS RAK,
						pakaid.NA_BHN AS NA_BHN,
						pakaid.SATUAN AS SATUAN,
						0 AS AW,
						'' AS NO_BUKTI,
						0 AS MA,
						'' AS NO_BUKTI_KE,
						0 AS KE,
						pakaid.NO_BUKTI AS NO_BUKTI_RKE,
						pakaid.QTY AS RKE,
						0 AS AK
					FROM pakaid, bhnd
					WHERE bhnd.kd_bhn = pakaid.KD_BHN
					-- AND pakaid.PER = '$per'
					AND pakaid.DR = '$dr'
					AND bhnd.DR = '$dr'
					AND pakaid.SUB = '$sub'
					AND pakaid.TGL = '$tgl_1'
					AND pakaid.FLAG = 'KP'
					AND pakaid.FLAG2 = 'SP'
					-- GROUP BY pakaid.NO_BUKTI
				) AS AAA
				ORDER BY NA_BHN";
		return $this->db->query($q1);
	}

	public function tampil_data_harian_atk()
	{
		// $periode = $tahun.$bulan;
		$dr = $this->session->userdata['dr'];
		$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
		$sub = $this->session->userdata['sub'];
		$per = $this->session->userdata['periode'];
		if ($tgl_1 == '') {
			$bulan = Date('m');
		} else {
			$bulan = date("m", strtotime($tgl_1));
		}
		$tahun = substr($this->input->post('TGL_1'), -4);
		$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
		$q1 = "SELECT TGL, RAK, NA_BHN, SATUAN, AW, NO_BUKTI_MA, MA, NO_BUKTI_KE, KE, NO_BUKTI_RKE, RKE, AK
				FROM (
					SELECT '$tgl_1' AS TGL,
						bhnd.RAK AS RAK,
						bhnd.NA_BHN AS NA_BHN,
						bhn.SATUAN AS SATUAN,
						bhnd.AW$bulan+(belid.QTY-pakaid.QTY) AS AW,
						'' AS NO_BUKTI_MA,
						0 AS MA,
						'' AS NO_BUKTI_KE,
						0 AS KE,
						'' AS NO_BUKTI_RKE,
						0 AS RKE,
						0 AS AK
					FROM bhn, bhnd, belid, pakaid
					WHERE bhn.KD_BHN = bhnd.KD_BHN
					AND bhn.KD_BHN = belid.KD_BHN
					AND bhn.KD_BHN = pakaid.KD_BHN
					AND bhnd.YER = '$tahun'
					AND bhn.FLAG2 = '$sub'
					AND bhn.DR = '$dr'
					-- AND belid.PER = '$per'
					AND belid.TGL < '$tgl_1'
					AND pakaid.PER = '$per'
					AND pakaid.TGL < '$tgl_1'
					GROUP BY bhnd.NA_BHN
					UNION ALL
					SELECT belid.TGL AS TGL, 
						belid.RAK AS RAK,
						belid.NA_BHN AS NA_BHN,
						belid.SATUAN AS SATUAN,
						0 AS AW,
						belid.NO_BUKTI AS NO_BUKTI_MA,
						belid.QTY AS MA,
						'' AS NO_BUKTI_KE,
						0 AS KE,
						'' AS NO_BUKTI_RKE,
						0 AS RKE,
						0 AS AK
					FROM belid, bhnd
					WHERE bhnd.kd_bhn = belid.kd_bhn
					-- AND belid.PER = '$per'
					AND belid.DR = '$dr'
					AND belid.SP = '$sub'
					AND belid.TGL = '$tgl_1'
					GROUP BY belid.NA_BHN
					UNION ALL
					SELECT pakaid.TGL AS TGL, 
						pakaid.RAK AS RAK,
						pakaid.NA_BHN AS NA_BHN,
						pakaid.SATUAN AS SATUAN,
						0 AS AW,
						'' AS NO_BUKTI_MA,
						0 AS MA,
						pakaid.NO_BUKTI AS NO_BUKTI_KE,
						pakaid.QTY AS KE,
						'' AS NO_BUKTI_RKE,
						0 AS RKE,
						0 AS AK
					FROM pakaid, bhnd
					WHERE bhnd.kd_bhn = pakaid.KD_BHN
					-- AND pakaid.PER = '$per'
					AND pakaid.DR = '$dr'
					AND pakaid.SUB = '$sub'
					AND pakaid.TGL = '$tgl_1'
					AND pakaid.FLAG2 = 'SP'
					GROUP BY pakaid.NA_BHN
					UNION ALL
					SELECT pakaid.TGL AS TGL, 
						pakaid.RAK AS RAK,
						pakaid.NA_BHN AS NA_BHN,
						pakaid.SATUAN AS SATUAN,
						0 AS AW,
						'' AS NO_BUKTI,
						0 AS MA,
						'' AS NO_BUKTI_KE,
						0 AS KE,
						pakaid.NO_BUKTI AS NO_BUKTI_RKE,
						pakaid.QTY AS RKE,
						0 AS AK
					FROM pakaid, bhnd
					WHERE bhnd.kd_bhn = pakaid.KD_BHN
					-- AND pakaid.PER = '$per'
					AND pakaid.DR = '$dr'
					AND pakaid.SUB = '$sub'
					AND pakaid.TGL = '$tgl_1'
					AND pakaid.FLAG2 = 'SP'
					GROUP BY pakaid.NA_BHN
				) AS AAA
				ORDER BY NA_BHN";
		return $this->db->query($q1);
	}

	public function tampil_data_bulanan()
	{
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$sub = $this->session->userdata['sub'];
		$per = $this->session->userdata['periode'];
		$bulan = substr($this->session->userdata['periode'], 0, -5);
		$tahun = substr($this->session->userdata['periode'], -4);
		$q1 = "SELECT KD_BHN, NA_BHN, SATUAN, PER, AW, MA, KE, LN, AK
				FROM (
					SELECT bhnd.KD_BHN AS KD_BHN,
						bhnd.NA_BHN AS NA_BHN,
						bhn.SATUAN AS SATUAN,
						'$per' AS PER,
						bhnd.AW$bulan AS AW,
						bhnd.MA$bulan AS MA,
						bhnd.KE$bulan AS KE,
						bhnd.LN$bulan AS LN,
						bhnd.AK$bulan AS AK
					FROM bhnd, bhn
					WHERE bhnd.KD_BHN = bhn.KD_BHN
					AND bhnd.YER = '$tahun'
					AND bhn.DR = '$dr'
					AND bhn.FLAG = 'SP'
					AND bhn.SUB = '$sub'
					GROUP BY bhn.KD_BHN
				) AS KD_BHN
				ORDER BY KD_BHN";
		return $this->db->query($q1);
	}

	public function tampil_data_bulanan_atk()
	{
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$per = $this->session->userdata['periode'];
		$bulan = substr($this->session->userdata['periode'], 0, -5);
		$tahun = substr($this->session->userdata['periode'], -4);
		$q1 = "SELECT KD_BHN, NA_BHN, SATUAN, PER, AW, MA, KE, LN, AK
				FROM (
					SELECT bhnd.KD_BHN AS KD_BHN,
						bhnd.NA_BHN AS NA_BHN,
						bhn.SATUAN AS SATUAN,
						'$per' AS PER,
						bhnd.AW$bulan AS AW,
						bhnd.MA$bulan AS MA,
						bhnd.KE$bulan AS KE,
						bhnd.LN$bulan AS LN,
						bhnd.AK$bulan AS AK
					FROM bhnd, bhn
					WHERE bhnd.KD_BHN = bhn.KD_BHN
					AND bhnd.YER = '$tahun'
					AND bhn.DR = '$dr'
					AND bhn.FLAG = 'SP'
					AND bhn.SUB = '$sub'
					GROUP BY bhn.KD_BHN
				) AS KD_BHN
				ORDER BY KD_BHN";
		return $this->db->query($q1);
	}

	public function tampil_data_pemeliharaan()
	{
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$per = $this->session->userdata['periode'];
		$na_gol = $this->input->post('NA_GOL_1');
		$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
		$tgl_2 = date("Y-m-d", strtotime($this->input->post('TGL_2', TRUE)));
		$q1 = "SELECT pakaid.NO_ID AS ID,
				pakaid.RAK,
				'$per' AS PER,
				pakaid.NA_BHN,
				pakaid.KD_BHN,
				pakaid.SATUAN,
				pakaid.KET2,
				pakaid.NO_BUKTI,
				pakaid.TGL,
				pakaid.QTY,
				pakaid.NA_GOL,
				pakaid.GRUP
			FROM pakaid
			WHERE pakaid.TGL >='$tgl_1'
			AND pakaid.TGL <='$tgl_2'
			AND pakaid.DR = '$dr'
			AND pakaid.SUB = '$sub'
			AND pakaid.FLAG ='PK'
			AND pakaid.FLAG2 ='SP'
			AND pakaid.KET2 ='$na_gol'
			ORDER BY pakaid.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_lpb()
	{
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$per = $this->session->userdata['periode'];
		$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
		$tgl_2 = date("Y-m-d", strtotime($this->input->post('TGL_2', TRUE)));
		$q1 = "SELECT belid.rak AS RAK,
				CONCAT(belid.kd_bhn,' - ',belid.na_bhn) AS BARANG,
				beli.no_beli AS NO_BUKTI,
				beli.tgl AS TGL,
				belid.satuan AS SATUAN,
				belid.qty AS QTY,
				'$tgl_1' AS TGL_1,
				'$tgl_2' AS TGL_2
			FROM belid, beli
			WHERE beli.no_bukti=belid.no_bukti 
			AND beli.TGL >='$tgl_1'
			AND beli.TGL <='$tgl_2'
			AND beli.SUB = '$sub'
			AND beli.DR='$dr'
			AND belid.FLAG2='SP'
			ORDER BY belid.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_pemakaian()
	{
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$per = $this->session->userdata['periode'];
		$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
		$tgl_2 = date("Y-m-d", strtotime($this->input->post('TGL_2', TRUE)));
		$q1 = "SELECT pakai.NOTES AS NOTES,
				pakaid.TGL AS TGL,
				pakaid.NO_BUKTI AS NO_BUKTI,
				pakaid.KD_BHN AS KD_BHN,
				pakaid.NA_BHN AS NA_BHN,
				pakaid.QTY AS QTY,
				pakaid.SATUAN AS SATUAN,
				pakaid.KET1 AS KET1,
				pakaid.RAK AS RAK,
				$tgl_1 AS TGL_1,
				$tgl_2 AS TGL_2
			FROM pakaid, pakai
			WHERE pakai.NO_BUKTI = pakaid.NO_BUKTI
			AND pakai.TGL BETWEEN '$tgl_1' AND '$tgl_2'
			AND pakaid.TGL BETWEEN '$tgl_1' AND '$tgl_2'
			AND pakai.DR = '$dr'
			AND pakai.SUB = '$sub'
			-- AND pakai.PER = '$per'
			AND pakai.ATK = 0
			AND pakai.FLAG = 'PK'
			AND pakai.FLAG2 = 'SP'
			ORDER BY pakaid.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_usiastokia()
	{
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$per = $this->session->userdata['periode'];
		$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
		$hari_1 = substr($this->input->post('TGL_1'), 0.2);
		$bulan = substr(date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE))), 5, 2);
		$tahun = substr($this->input->post('TGL_1'), 6, 4);
		$q1 = "SELECT bhnd.RAK, 
			bhnd.KD_BHN,
			bhnd.NA_BHN, 
			bhn.SATUAN,
			bhnd.AK$bulan AS AK, 
			DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) as HARI,
			CASE 
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 720 
				THEN '> 24 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 630 
				THEN '> 21 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 540 
				THEN '> 18 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 450 
				THEN '> 15 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 360
				THEN '> 12 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 270
				THEN '> 9 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 180 
				THEN '> 6 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 90 
				THEN '> 3 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 30 
					THEN '> 1 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) < 30 
				THEN '< 1 Bulan'
			END AS KET
		FROM bhn, bhnd
		WHERE bhn.KD_BHN = bhnd.KD_BHN
		AND bhnd.DR='$dr'
		AND bhnd.FLAG='SP'
		AND bhnd.TG_BL < '$tgl_1'
		AND bhnd.TG_PK < '$tgl_1'
		AND bhnd.YER = '$tahun'
		GROUP BY bhnd.KD_BHN
		ORDER BY bhnd.KD_BHN";
		return $this->db->query($q1);
	}

	public function tampil_data_usia()
	{
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$per = $this->session->userdata['periode'];
		$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
		$hari_1 = substr($this->input->post('TGL_1'), 0.2);
		$bulan = substr(date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE))), 5, 2);
		$tahun = substr($this->input->post('TGL_1'), 6, 4);
		$masa = $this->input->post('MASA');
		$q1 = "SELECT bhnd.RAK, 
			bhnd.KD_BHN,
			bhnd.NA_BHN, 
			bhn.SATUAN,
			bhnd.AK$bulan AS AK, 
			DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) as HARI,
			CASE 
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 720 
				THEN '> 24 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 630 
				THEN '> 21 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 540 
				THEN '> 18 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 450 
				THEN '> 15 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 360
				THEN '> 12 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 270
				THEN '> 9 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 180 
				THEN '> 6 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 90 
				THEN '> 3 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 30 
					THEN '> 1 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) < 30 
				THEN '< 1 Bulan'
			END AS KET
		FROM bhn, bhnd
		WHERE bhn.KD_BHN = bhnd.KD_BHN
		AND bhnd.DR='$dr'
		AND bhnd.FLAG='SP'
		-- AND bhn.SUB='$sub'
		AND bhnd.TG_BL < '$tgl_1'
		AND bhnd.TG_PK < '$tgl_1'
		AND bhnd.YER = '$tahun'
		-- AND TIMESTAMPDIFF(MONTH,bhn.TG_BL, bhn.TG_PK) = '$masa'
		GROUP BY bhnd.KD_BHN
		ORDER BY bhnd.KD_BHN";
		return $this->db->query($q1);
	}

	public function tampil_data_global()
	{
		$dr = $this->session->userdata['dr'];
		$jenis_1 = $this->input->post('JENIS_1');
		$filter_jenis = " ";
		if ($this->input->post('JENIS_1', TRUE) != '') {
			$filter_jenis = "AND inventarisd.JENIS = '$jenis_1'";
		}
		$q1 = "SELECT inventaris.NO_BUKTI AS NO_BUKTI,
				inventaris.NA_BAGIAN AS NA_BAGIAN,
				inventarisd.JENIS AS JENIS,
				inventarisd.MERK AS MERK,
				inventarisd.QTY AS QTY,
				inventarisd.SATUAN AS SATUAN
			FROM inventaris, inventarisd
			WHERE inventaris.NO_BUKTI = inventarisd.NO_BUKTI 
			AND inventaris.DR='$dr'
			AND inventaris.FLAG='INV'
			$filter_jenis
			ORDER BY inventarisd.JENIS";
		return $this->db->query($q1);
	}

	public function tampil_data_inventarisglobal()
	{
		$dr = $this->session->userdata['dr'];
		$jenis_1 = $this->input->post('JENIS_1');
		$filter_jenis = " ";
		if ($this->input->post('JENIS_1', TRUE) != '') {
			$filter_jenis = "AND inventarisd.JENIS = '$jenis_1'";
		}
		$q1 = "SELECT inventaris.NO_BUKTI AS NO_BUKTI,
				inventaris.NA_BAGIAN AS NA_BAGIAN,
				inventarisd.JENIS AS JENIS,
				inventarisd.MERK AS MERK,
				inventarisd.QTY AS QTY,
				inventarisd.SATUAN AS SATUAN
			FROM inventaris, inventarisd
			WHERE inventaris.NO_BUKTI = inventarisd.NO_BUKTI 
			AND inventaris.DR='$dr'
			AND inventaris.FLAG='INV'
			$filter_jenis
			ORDER BY inventarisd.JENIS";
		return $this->db->query($q1);
	}

	// -- IF(bhnd.TG_BL>bhnd.TG_PK, DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL), 
	// -- IF(bhnd.TG_BL<bhnd.TG_PK,DATEDIFF(DATE('$tgl_1'),bhnd.TG_PK),
	// -- DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL))) as HARI,
	// -- CASE 
	// -- 	WHEN 
	// -- 		IF(bhnd.TG_BL>bhnd.TG_PK, DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL), 
	// -- 		IF(bhnd.TG_BL<bhnd.TG_PK,DATEDIFF(DATE('$tgl_1'),bhnd.TG_PK),
	// -- 		DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL))) < 30 
	// -- 	THEN '< 1 Bulan'
	// -- 	WHEN 
	// -- 		IF(bhnd.TG_BL>bhnd.TG_PK, DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL), 
	// -- 		IF(bhnd.TG_BL<bhnd.TG_PK,DATEDIFF(DATE('$tgl_1'),bhnd.TG_PK),
	// -- 		DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL))) >= 30 
	// -- 	THEN '> 2 Bulan'
	// -- 	WHEN 
	// -- 		IF(bhnd.TG_BL>bhnd.TG_PK, DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL), 
	// -- 		IF(bhnd.TG_BL<bhnd.TG_PK,DATEDIFF(DATE('$tgl_1'),bhnd.TG_PK),
	// -- 		DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL))) >= 90 
	// -- 	THEN '> 3 Bulan'
	// -- 	WHEN 
	// -- 		IF(bhnd.TG_BL>bhnd.TG_PK, DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL), 
	// -- 		IF(bhnd.TG_BL<bhnd.TG_PK,DATEDIFF(DATE('$tgl_1'),bhnd.TG_PK),
	// -- 		DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL))) >= 90 
	// -- 	THEN '> 3 Bulan'
	// -- 	WHEN 
	// -- 		IF(bhnd.TG_BL>bhnd.TG_PK, DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL), 
	// -- 		IF(bhnd.TG_BL<bhnd.TG_PK,DATEDIFF(DATE('$tgl_1'),bhnd.TG_PK),
	// -- 		DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL))) >= 180 
	// -- 	THEN '> 6 Bulan'
	// -- 	WHEN 
	// -- 		IF(bhnd.TG_BL>bhnd.TG_PK, DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL), 
	// -- 		IF(bhnd.TG_BL<bhnd.TG_PK,DATEDIFF(DATE('$tgl_1'),bhnd.TG_PK),
	// -- 		DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL))) >= 360 
	// -- 	THEN '> 12 Bulan'
	// -- 	WHEN 
	// -- 		IF(bhnd.TG_BL>bhnd.TG_PK, DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL), 
	// -- 		IF(bhnd.TG_BL<bhnd.TG_PK,DATEDIFF(DATE('$tgl_1'),bhnd.TG_PK),
	// -- 		DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL))) >= 540 
	// -- 	THEN '> 18 Bulan'
	// -- 	WHEN 
	// -- 		IF(bhnd.TG_BL>bhnd.TG_PK, DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL), 
	// -- 		IF(bhnd.TG_BL<bhnd.TG_PK,DATEDIFF(DATE('$tgl_1'),bhnd.TG_PK),
	// -- 		DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL))) >= 720 
	// -- 	THEN '> 24 Bulan'
	// -- 	WHEN 
	// -- 		IF(bhnd.TG_BL>bhnd.TG_PK, DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL), 
	// -- 		IF(bhnd.TG_BL<bhnd.TG_PK,DATEDIFF(DATE('$tgl_1'),bhnd.TG_PK),
	// -- 		DATEDIFF(DATE('$tgl_1'),bhnd.TG_BL))) >= 1080 
	// -- 	THEN '> 36 Bulan'
	// -- END AS KET


	public function tampil_data_stok_sparepart()
	{
		$tgl_1 = date("Y-m-d");
		$bulan = substr(date("Y-m-d"), 5, 2);
		$tahun = substr($this->input->post('PER'), -4);
		$tahun_1 = $this->input->post('PER');
		$q1 = "SELECT bhnd.KD_BHN,
						bhnd.NA_BHN,
						bhn.SATUAN,
						'$tahun_1' AS PER,
						SUM(if(bhnd.DR='I',bhnd.AK$bulan,0)) AS DR1,
						SUM(if(bhnd.DR='II',bhnd.AK$bulan,0)) AS DR2,
						SUM(if(bhnd.DR='III',bhnd.AK$bulan,0)) AS DR3,
						SUM(bhnd.AK$bulan) AS TOTAL,
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) as HARI,
						CASE 
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 720 
							THEN '> 24 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 630 
							THEN '> 21 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 540 
							THEN '> 18 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 450 
							THEN '> 15 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 360
							THEN '> 12 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 270
							THEN '> 9 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 180 
							THEN '> 6 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 90 
							THEN '> 3 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 30 
							THEN '> 1 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) < 30 
							THEN '< 1 Bulan'
						END AS KET
					FROM bhnd, bhn
					WHERE bhnd.KD_BHN = bhn.KD_BHN
					-- AND bhnd.YER = '$tahun'
					AND bhnd.FLAG = 'SP'
					AND bhnd.SUB = 'SP'
					GROUP BY bhn.KD_BHN
					ORDER BY bhn.NA_BHN";
		return $this->db->query($q1);
	}

	public function tampil_data_stok_inventaris()
	{
		$tgl_1 = date("Y-m-d");
		$bulan = substr(date("Y-m-d"), 5, 2);
		$tahun = substr($this->input->post('PER'), -4);
		$tahun_1 = $this->input->post('PER');
		$q1 = "SELECT bhnd.KD_BHN,
						bhnd.NA_BHN,
						bhn.SATUAN,
						'$tahun_1' AS PER,
						SUM(if(bhnd.DR='I',bhnd.AK$bulan,0)) AS DR1,
						SUM(if(bhnd.DR='II',bhnd.AK$bulan,0)) AS DR2,
						SUM(if(bhnd.DR='III',bhnd.AK$bulan,0)) AS DR3,
						SUM(bhnd.AK$bulan) AS TOTAL,
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) as HARI,
						CASE 
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 720 
							THEN '> 24 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 630 
							THEN '> 21 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 540 
							THEN '> 18 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 450 
							THEN '> 15 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 360
							THEN '> 12 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 270
							THEN '> 9 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 180 
							THEN '> 6 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 90 
							THEN '> 3 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 30 
							THEN '> 1 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) < 30 
							THEN '< 1 Bulan'
						END AS KET
					FROM bhnd, bhn
					WHERE bhnd.KD_BHN = bhn.KD_BHN
					-- AND bhnd.YER = '$tahun'
					AND bhnd.FLAG = 'SP'
					AND bhnd.SUB = 'INV'
					GROUP BY bhn.KD_BHN";
		return $this->db->query($q1);
	}

	public function tampil_data_stok_atk()
	{
		$tgl_1 = date("Y-m-d");
		$bulan = substr(date("Y-m-d"), 5, 2);
		$tahun = substr($this->input->post('PER'), -4);
		$tahun_1 = $this->input->post('PER');
		$q1 = "SELECT bhnd.KD_BHN,
						bhnd.NA_BHN,
						bhn.SATUAN,
						'$tahun_1' AS PER,
						SUM(if(bhnd.DR='I',bhnd.AK$bulan,0)) AS DR1,
						SUM(if(bhnd.DR='II',bhnd.AK$bulan,0)) AS DR2,
						SUM(if(bhnd.DR='III',bhnd.AK$bulan,0)) AS DR3,
						SUM(bhnd.AK$bulan) AS TOTAL,
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) as HARI,
						CASE 
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 720 
							THEN '> 24 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 630 
							THEN '> 21 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 540 
							THEN '> 18 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 450 
							THEN '> 15 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 360
							THEN '> 12 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 270
							THEN '> 9 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 180 
							THEN '> 6 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 90 
							THEN '> 3 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 30 
							THEN '> 1 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) < 30 
							THEN '< 1 Bulan'
						END AS KET
					FROM bhnd, bhn
					WHERE bhnd.KD_BHN = bhn.KD_BHN
					-- AND bhnd.YER = '$tahun'
					AND bhnd.FLAG = 'SP'
					AND bhnd.SUB = 'ATK'
					GROUP BY bhn.KD_BHN";
		return $this->db->query($q1);
	}

	public function tampil_data_stok_umum()
	{
		$tgl_1 = date("Y-m-d");
		$bulan = substr(date("Y-m-d"), 5, 2);
		$tahun = substr($this->input->post('PER'), -4);
		$tahun_1 = $this->input->post('PER');
		$q1 = "SELECT bhnd.KD_BHN,
						bhnd.NA_BHN,
						bhn.SATUAN,
						'$tahun_1' AS PER,
						SUM(if(bhnd.DR='I',bhnd.AK$bulan,0)) AS DR1,
						SUM(if(bhnd.DR='II',bhnd.AK$bulan,0)) AS DR2,
						SUM(if(bhnd.DR='III',bhnd.AK$bulan,0)) AS DR3,
						SUM(bhnd.AK$bulan) AS TOTAL,
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) as HARI,
						CASE 
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 720 
							THEN '> 24 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 630 
							THEN '> 21 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 540 
							THEN '> 18 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 450 
							THEN '> 15 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 360
							THEN '> 12 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 270
							THEN '> 9 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 180 
							THEN '> 6 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 90 
							THEN '> 3 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 30 
							THEN '> 1 Bulan'
							WHEN 
								DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) < 30 
							THEN '< 1 Bulan'
						END AS KET
					FROM bhnd, bhn
					WHERE bhnd.KD_BHN = bhn.KD_BHN
					-- AND bhnd.YER = '$tahun'
					AND bhnd.FLAG = 'SP'
					AND bhnd.SUB = 'UM'
					GROUP BY bhn.KD_BHN";
		return $this->db->query($q1);
	}

	public function tampil_data_laporan_sparepart()
	{
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$per = $this->session->userdata['periode'];
		$bulan = substr($this->session->userdata['periode'], 0, -5);
		$tahun = substr($this->session->userdata['periode'], -4);
		$q1 = "SELECT KD_BHN, NA_BHN, SATUAN, PER, AW, MA, KE, LN, AK
					FROM (
						SELECT bhnd.KD_BHN AS KD_BHN,
							bhnd.NA_BHN AS NA_BHN,
							bhn.SATUAN AS SATUAN,
							'$per' AS PER,
							bhnd.AW$bulan AS AW,
							bhnd.MA$bulan AS MA,
							bhnd.KE$bulan AS KE,
							bhnd.LN$bulan AS LN,
							bhnd.AK$bulan AS AK
						FROM bhnd, bhn
						WHERE bhnd.KD_BHN = bhn.KD_BHN
						AND bhnd.YER = '$tahun'
						-- AND bhn.DR = '$dr'
						AND bhn.FLAG = 'SP'
						AND bhn.SUB = 'SP'
						GROUP BY bhn.KD_BHN
						ORDER BY bhnd.TG_USIA DESC
					) AS KD_BHN";
		return $this->db->query($q1);
	}

	public function tampil_data_laporan_inventaris()
	{
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$per = $this->session->userdata['periode'];
		$bulan = substr($this->session->userdata['periode'], 0, -5);
		$tahun = substr($this->session->userdata['periode'], -4);
		$q1 = "SELECT KD_BHN, NA_BHN, SATUAN, PER, AW, MA, KE, LN, AK
					FROM (
						SELECT bhnd.KD_BHN AS KD_BHN,
							bhnd.NA_BHN AS NA_BHN,
							bhn.SATUAN AS SATUAN,
							'$per' AS PER,
							bhnd.AW$bulan AS AW,
							bhnd.MA$bulan AS MA,
							bhnd.KE$bulan AS KE,
							bhnd.LN$bulan AS LN,
							bhnd.AK$bulan AS AK
						FROM bhnd, bhn
						WHERE bhnd.KD_BHN = bhn.KD_BHN
						AND bhnd.YER = '$tahun'
						-- AND bhn.DR = '$dr'
						AND bhn.FLAG = 'SP'
						AND bhn.SUB = 'INV'
						GROUP BY bhn.KD_BHN
						ORDER BY bhnd.TG_USIA DESC
					) AS KD_BHN";
		return $this->db->query($q1);
	}

	public function tampil_data_laporan_atk()
	{
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$per = $this->session->userdata['periode'];
		$bulan = substr($this->session->userdata['periode'], 0, -5);
		$tahun = substr($this->session->userdata['periode'], -4);
		$q1 = "SELECT KD_BHN, NA_BHN, SATUAN, PER, AW, MA, KE, LN, AK
					FROM (
						SELECT bhnd.KD_BHN AS KD_BHN,
							bhnd.NA_BHN AS NA_BHN,
							bhn.SATUAN AS SATUAN,
							'$per' AS PER,
							bhnd.AW$bulan AS AW,
							bhnd.MA$bulan AS MA,
							bhnd.KE$bulan AS KE,
							bhnd.LN$bulan AS LN,
							bhnd.AK$bulan AS AK
						FROM bhnd, bhn
						WHERE bhnd.KD_BHN = bhn.KD_BHN
						AND bhnd.YER = '$tahun'
						-- AND bhn.DR = '$dr'
						AND bhn.FLAG = 'SP'
						AND bhn.SUB = 'ATK'
					GROUP BY bhn.KD_BHN
					ORDER BY bhnd.TG_USIA DESC
					) AS KD_BHN";
		return $this->db->query($q1);
	}

	public function tampil_data_laporan_lpb()
	{
		$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
		$q1 = "SELECT beli.NO_BUKTI,
						beli.KD_BAG,
						belid.TGL,
						belid.NA_BRG,
						belid.QTY,
						belid.NO_PO,
						belid.SATUAN
					FROM beli, belid
					WHERE beli.NO_BUKTI = belid.NO_BUKTI
					AND beli.TGL = '$tgl_1'
					ORDER BY belid.KD_BHN";
		return $this->db->query($q1);
	}

	public function tampil_data_monitor_order_lasting()
	{
		$dr = $this->session->userdata['dr'];
		$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
		$q1 = "SELECT pp.NO_BUKTI,
				pp.TGL,
				pp.TGL_DIMINTA,
				pp.DEVISI,
				pp.ARTICLE AS NA_BRG,
				pp.KET,
				pp.TOTAL_QTY,
				'-' AS AREA,
				'-' AS KABAG,
				'-' AS HARI,
				'-' AS TGLSLS,
				'-' AS SLS,
				if(pp.VAL = 1, 'SELESAI', 'BELUM SELESAI') AS STAT,

				ppd.NA_BHN,					
				ppd.QTY,					
				ppd.SATUAN,				
				ppd.KET1 AS KET

				-- pod.NO_BUKTI AS NO_PO,
				-- pod.TGL AS TGL_PO,
				-- pod.NO_PP,

				-- belid.NO_BUKTI AS NO_BELI,
				-- belid.NO_PO,
				-- belid.TGL AS TGL_BELI
			FROM pp, ppd
			WHERE pp.NO_BUKTI = ppd.NO_BUKTI
			AND pp.DR ='$dr'
			AND pp.SUB ='LS'
			-- AND pp.TYP = 'RND_LASTING'
			-- AND belid.FLAG2 = 'SP'
			GROUP BY pp.NO_BUKTI
			ORDER BY pp.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_monitor_order_cetakan()
	{
		$dr = $this->session->userdata['dr'];
		$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
		$q1 = "SELECT pp.NO_BUKTI,
				pp.TGL,
				pp.TGL_DIMINTA,
				pp.DEVISI,
				pp.ARTICLE AS NA_BRG,
				pp.KET,
				pp.TOTAL_QTY,
				'-' AS AREA,
				'-' AS KABAG,
				'-' AS HARI,
				'-' AS TGLSLS,
				'-' AS SLS,
				if(pp.VAL = 1, 'SELESAI', 'BELUM SELESAI') AS STAT,

				ppd.NA_BHN,					
				ppd.QTY,					
				ppd.SATUAN,				
				ppd.KET1 AS KET

				-- pod.NO_BUKTI AS NO_PO,
				-- pod.TGL AS TGL_PO,
				-- pod.NO_PP,

				-- belid.NO_BUKTI AS NO_BELI,
				-- belid.NO_PO,
				-- belid.TGL AS TGL_BELI
			FROM pp, ppd
			WHERE pp.NO_BUKTI = ppd.NO_BUKTI
			AND pp.DR ='$dr'
			AND pp.SUB ='CT'
			-- AND pp.TYP = 'RND_LASTING'
			-- AND belid.FLAG2 = 'SP'
			GROUP BY pp.NO_BUKTI
			ORDER BY pp.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_monitor_order_pisau()
	{
		$dr = $this->session->userdata['dr'];
		$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
		$q1 = "SELECT pp.NO_BUKTI,
				pp.TGL,
				pp.TGL_DIMINTA,
				pp.DEVISI,
				pp.ARTICLE AS NA_BRG,
				pp.KET,
				pp.TOTAL_QTY,
				'-' AS AREA,
				'-' AS KABAG,
				'-' AS HARI,
				'-' AS TGLSLS,
				'-' AS SLS,
				if(pp.VAL = 1, 'SELESAI', 'BELUM SELESAI') AS STAT,

				ppd.NA_BHN,					
				ppd.QTY,					
				ppd.SATUAN,				
				ppd.KET1 AS KET

				-- pod.NO_BUKTI AS NO_PO,
				-- pod.TGL AS TGL_PO,
				-- pod.NO_PP,

				-- belid.NO_BUKTI AS NO_BELI,
				-- belid.NO_PO,
				-- belid.TGL AS TGL_BELI
			FROM pp, ppd
			WHERE pp.NO_BUKTI = ppd.NO_BUKTI
			AND pp.DR ='$dr'
			AND pp.SUB ='1R&'
			-- AND pp.TYP = 'RND_LASTING'
			-- AND belid.FLAG2 = 'SP'
			GROUP BY pp.NO_BUKTI
			ORDER BY pp.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_monitor_order_meba()
	{
		$dr = $this->session->userdata['dr'];
		$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
		$q1 = "SELECT pp.NO_BUKTI,
				pp.TGL,
				pp.TGL_DIMINTA,
				pp.DEVISI,
				pp.ARTICLE AS NA_BRG,
				pp.KET,
				pp.TOTAL_QTY,
				'-' AS AREA,
				'-' AS KABAG,
				'-' AS HARI,
				'-' AS TGLSLS,
				'-' AS SLS,
				if(pp.VAL = 1, 'SELESAI', 'BELUM SELESAI') AS STAT,

				ppd.NA_BHN,					
				ppd.QTY,					
				ppd.SATUAN,				
				ppd.KET1 AS KET

				-- pod.NO_BUKTI AS NO_PO,
				-- pod.TGL AS TGL_PO,
				-- pod.NO_PP,

				-- belid.NO_BUKTI AS NO_BELI,
				-- belid.NO_PO,
				-- belid.TGL AS TGL_BELI
			FROM pp, ppd
			WHERE pp.NO_BUKTI = ppd.NO_BUKTI
			AND pp.DR ='$dr'
			AND pp.SUB ='MB'
			-- AND pp.TYP = 'RND_LASTING'
			-- AND belid.FLAG2 = 'SP'
			GROUP BY pp.NO_BUKTI
			ORDER BY pp.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_laporan_pesanan()
	{
		$per = $this->session->userdata['periode'];
		$sub = $this->session->userdata['sub'];
		$q1 = "SELECT pp.NO_BUKTI,
				pp.TGL,
				pp.TGL_DIMINTA,
				pp.DEVISI,
				pp.NA_BRG,
				pp.KET,
				if(pp.VAL = 1, 'SELESAI', 'BELUM SELESAI') AS STAT,

				ppd.NA_BHN,				
				ppd.KD_BHN,				
				ppd.QTY,					
				ppd.SATUAN,				
				ppd.KET1 AS KET1,

				pod.NO_BUKTI AS NO_PO,
				pod.TGL AS TGL_PO,
				pod.NO_PP,

				belid.NO_BUKTI,
				belid.NO_PO,
				belid.TGL AS TGL_BELI
			FROM pp, ppd, pod, belid
			WHERE pp.NO_BUKTI = ppd.NO_BUKTI
			AND pp.SUB = '$sub'
			AND pp.PER = '$per'
			AND belid.FLAG2 = 'SP'
			GROUP BY belid.NO_PO
			ORDER BY pp.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_proses_order_pembelian()
	{
		$per = $this->session->userdata['periode'];
		$sub = $this->session->userdata['sub'];
		$q1 = "SELECT pp.NO_BUKTI,
				pp.TGL,
				pp.TGL_DIMINTA,
				pp.DEVISI,
				pp.KD_DEV AS KD_DEVISI,
				pp.ARTICLE AS NA_BRG,
				pp.KET,
				pp.TOTAL_QTY,
				'-' AS AREA,
				'-' AS KABAG,
				'-' AS HARI,
				if(pp.VAL = 1, 'SELESAI', 'BELUM SELESAI') AS STAT,

				ppd.NA_BHN,					
				ppd.QTY,					
				ppd.SATUAN
			FROM pp, ppd
			WHERE pp.NO_BUKTI = ppd.NO_BUKTI
			AND pp.SUB = '$sub'
			AND pp.PER = '$per'
			-- AND pp.TYP = 'BL_CNC'
			-- AND belid.FLAG2 = 'SP'
			GROUP BY pp.NO_BUKTI
			ORDER BY pp.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_proses_order_pembelian_cor()
	{
		$per = $this->session->userdata['periode'];
		$sub = $this->session->userdata['sub'];
		$q1 = "SELECT pp.NO_BUKTI,
				pp.TGL,
				pp.TGL_DIMINTA,
				pp.DEVISI,
				pp.KD_DEV,
				pp.ARTICLE AS NA_BRG,
				pp.KET,
				pp.TOTAL_QTY,
				'-' AS AREA,
				'-' AS KABAG,
				'-' AS HARI,
				if(pp.VAL = 1, 'SELESAI', 'BELUM SELESAI') AS STAT,

				ppd.NA_BHN,					
				ppd.QTY,					
				ppd.SATUAN			

				-- pod.NO_BUKTI AS NO_PO,
				-- pod.TGL AS TGL_PO,
				-- pod.NO_PP,

				-- belid.NO_BUKTI AS NO_BELI,
				-- belid.NO_PO,
				-- belid.TGL AS TGL_BELI
			FROM pp, ppd
			WHERE pp.NO_BUKTI = ppd.NO_BUKTI
			AND pp.SUB = '$sub'
			AND pp.PER = '$per'
			-- AND pp.TYP = 'BOR_CNC'
			-- AND belid.FLAG2 = 'SP'
			GROUP BY pp.NO_BUKTI
			ORDER BY pp.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_proses_order_pembelian_cor_per_pp()
	{
		$per = $this->session->userdata['periode'];
		$sub = $this->session->userdata['sub'];
		$q1 = "SELECT pp.NO_BUKTI,
				pp.TGL,
				pp.TGL_DIMINTA,
				pp.DEVISI,
				pp.KD_DEV,
				pp.ARTICLE AS NA_BRG,
				pp.KET,
				pp.TOTAL_QTY,
				'-' AS AREA,
				'-' AS KABAG,
				'-' AS HARI,
				if(pp.VAL = 1, 'SELESAI', 'BELUM SELESAI') AS STAT,

				ppd.NA_BHN,					
				ppd.QTY,					
				ppd.SATUAN		

				-- pod.NO_BUKTI AS NO_PO,
				-- pod.TGL AS TGL_PO,
				-- pod.NO_PP,

				-- belid.NO_BUKTI AS NO_BELI,
				-- belid.NO_PO,
				-- belid.TGL AS TGL_BELI
			FROM pp, ppd
			WHERE pp.NO_BUKTI = ppd.NO_BUKTI
			AND pp.SUB = '$sub'
			AND pp.PER = '$per'
			-- AND pp.TYP = 'BOR_CNC'
			-- AND belid.FLAG2 = 'SP'
			GROUP BY pp.NO_BUKTI
			ORDER BY pp.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_proses_order_internal()
	{
		$per = $this->session->userdata['periode'];
		$sub = $this->session->userdata['sub'];
		$q1 = "SELECT pp.NO_BUKTI,
				pp.TGL,
				pp.TGL_DIMINTA,
				pp.DEVISI,
				pp.KD_DEV,
				pp.ARTICLE AS NA_BRG,
				pp.KET,
				pp.TOTAL_QTY,
				'-' AS AREA,
				'-' AS KABAG,
				'-' AS HARI,
				if(pp.VAL = 1, 'SELESAI', 'BELUM SELESAI') AS STAT,

				ppd.NA_BHN,					
				ppd.QTY,					
				ppd.SATUAN			

				-- pod.NO_BUKTI AS NO_PO,
				-- pod.TGL AS TGL_PO,
				-- pod.NO_PP,

				-- belid.NO_BUKTI AS NO_BELI,
				-- belid.NO_PO,
				-- belid.TGL AS TGL_BELI
			FROM pp, ppd
			WHERE pp.NO_BUKTI = ppd.NO_BUKTI
			AND pp.SUB = '$sub'
			AND pp.PER = '$per'
			-- AND pp.TYP = 'IN_CNC'
			-- AND belid.FLAG2 = 'SP'
			GROUP BY pp.NO_BUKTI
			ORDER BY pp.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_monitor_po()
	{
		$per = $this->session->userdata['periode'];
		$sub = $this->session->userdata['sub'];
		$dr = $this->session->userdata['dr'];
		$q1 = "SELECT po.NO_BUKTI AS NO_PO,
				po.KODES,
				po.NAMAS,
				po.DR,
				po.TGL,
				po.JTEMPO,
				po.NOTESKRM,

				pod.NO_BUKTI AS NO_PO,
				pod.TGL AS TGL_PO,
				pod.NO_PP AS NO_PP
			FROM po, pod
			WHERE po.NO_BUKTI = pod.NO_BUKTI
			AND po.DR = '$dr'
			AND po.PER = '$per'
			AND po.KD_TTD1 <> ''
			AND po.KD_TTD2 <> ''
			AND po.FLAG2 = 'NB'
			GROUP BY po.NO_BUKTI
			ORDER BY po.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_inventarisdr1()
	{
		$per = $this->session->userdata['periode'];
		$q1 = "SELECT pp.NO_BUKTI,
			pp.TGL,
			pp.TGL_DIMINTA,
			pp.DEVISI,
			pp.KD_DEV,
			pp.NA_BRG,
			pp.KET,

			ppd.NA_BHN,					
			ppd.QTY,					
			ppd.SATUAN
		FROM pp, ppd
		WHERE pp.NO_BUKTI = ppd.NO_BUKTI
		AND pp.SUB = 'INV'
		AND pp.DR = 'I'
		AND pp.PER = '$per'
		ORDER BY pp.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_sparepartdr1()
	{
		$per = $this->session->userdata['periode'];
		$q1 = "SELECT pp.NO_BUKTI,
			pp.TGL,
			pp.TGL_DIMINTA,
			pp.DEVISI,
			pp.KD_DEV,
			pp.NA_BRG,
			pp.KET,

			ppd.NA_BHN,					
			ppd.QTY,					
			ppd.SATUAN
		FROM pp, ppd
		WHERE pp.NO_BUKTI = ppd.NO_BUKTI
		AND pp.SUB = 'SP'
		AND pp.DR = 'I'
		AND pp.PER = '$per'
		ORDER BY pp.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_inventarisdr2()
	{
		$per = $this->session->userdata['periode'];
		$q1 = "SELECT pp.NO_BUKTI,
			pp.TGL,
			pp.TGL_DIMINTA,
			pp.DEVISI,
			pp.KD_DEV,
			pp.NA_BRG,
			pp.KET,

			ppd.NA_BHN,					
			ppd.QTY,					
			ppd.SATUAN
		FROM pp, ppd
		WHERE pp.NO_BUKTI = ppd.NO_BUKTI
		AND pp.SUB = 'INV'
		AND pp.DR = 'II'
		AND pp.PER = '$per'
		ORDER BY pp.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_sparepartdr2()
	{
		$per = $this->session->userdata['periode'];
		$q1 = "SELECT pp.NO_BUKTI,
			pp.TGL,
			pp.TGL_DIMINTA,
			pp.DEVISI,
			pp.KD_DEV,
			pp.NA_BRG,
			pp.KET,

			ppd.NA_BHN,					
			ppd.QTY,					
			ppd.SATUAN
		FROM pp, ppd
		WHERE pp.NO_BUKTI = ppd.NO_BUKTI
		AND pp.SUB = 'SP'
		AND pp.DR = 'II'
		AND pp.PER = '$per'
		ORDER BY pp.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_inventarisdr3()
	{
		$per = $this->session->userdata['periode'];
		$q1 = "SELECT pp.NO_BUKTI,
			pp.TGL,
			pp.TGL_DIMINTA,
			pp.DEVISI,
			pp.KD_DEV,
			pp.NA_BRG,
			pp.KET,

			ppd.NA_BHN,					
			ppd.QTY,					
			ppd.SATUAN
		FROM pp, ppd
		WHERE pp.NO_BUKTI = ppd.NO_BUKTI
		AND pp.SUB = 'INV'
		AND pp.DR = 'III'
		AND pp.PER = '$per'
		ORDER BY pp.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_sparepartdr3()
	{
		$per = $this->session->userdata['periode'];
		$q1 = "SELECT pp.NO_BUKTI,
			pp.TGL,
			pp.TGL_DIMINTA,
			pp.DEVISI,
			pp.KD_DEV,
			pp.NA_BRG,
			pp.KET,

			ppd.NA_BHN,					
			ppd.QTY,					
			ppd.SATUAN
		FROM pp, ppd
		WHERE pp.NO_BUKTI = ppd.NO_BUKTI
		AND pp.SUB = 'SP'
		AND pp.DR = 'III'
		AND pp.PER = '$per'
		ORDER BY pp.TGL";
		return $this->db->query($q1);
	}

	public function tampil_data_umum()
	{
		$per = $this->session->userdata['periode'];
		$q1 = "SELECT pp.NO_BUKTI,
			pp.TGL,
			pp.TGL_DIMINTA,
			pp.DEVISI,
			pp.KD_DEV,
			pp.NA_BRG,
			pp.KET,

			ppd.NA_BHN,					
			ppd.QTY,					
			ppd.SATUAN
		FROM pp, ppd
		WHERE pp.NO_BUKTI = ppd.NO_BUKTI
		AND pp.SUB = 'UM'
		AND pp.PER = '$per'
		ORDER BY pp.TGL";
		return $this->db->query($q1);
	}
}
