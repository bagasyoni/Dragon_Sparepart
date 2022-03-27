<?php

class Lpo_model extends CI_Model{




    public function tampil_data()
    {
      	$q1="select * from piu_copy ";
        return $this->db->query($q1);
    }

    public function input_datah($datah) 
    {
       
        $this->db->insert('po',$datah);

    }




    public function tampil_id($bukti)

    {
      $q1="select max(NO_ID) as NO_ID from piu_copy where NO_BUKTI = ? group by NO_BUKTI";
        return $this->db->query($q1,array($bukti));
    }



    public function input_datad($datad) 
    {

        $this->db->insert('pod',$datad);

    }
    
    public function edit_data($id)
    {
        
			  $q1="select A.NO_ID AS ID,A.NO_BUKTI,A.TGL,A.KET,A.MERK,A.NAMA,A.TOTAL AS TTOTAL, A.PPN, B.REC,B.NO_FAKTUR, 
					B.TOTAL,B.NO_ID  from piu_copy A,piud_copy B where A.NO_ID=$id and  A.NO_ID=B.ID ORDER BY B.REC";
              return $this->db->query($q1);
     
    }

    public function update_data($where,$data,$table)
    {
   
         $this->db->where($where);
         $this->db->update($table,$data);
    }



    public function hapus_data($where,$table)
    {
   
         $this->db->where($where);
         $this->db->delete($table);
    }


    function remove_checked() {
	
			$delete = $this->input->post('check');
			for ($i=0; $i < count($delete) ; $i++) { 
				$this->db->where('no_id', $delete[$i]);
				$this->db->delete('piu_copy');
			}
		
	}



}
