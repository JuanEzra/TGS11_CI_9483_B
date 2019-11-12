<?php
defined('BASEPATH') or exit ('no direct script access allowed');

class userModel extends CI_Model
{
    private $table = 'spareparts ';

    public $id;
    public $name;
    public $merk;
    public $brand;
    public $amount;//email//
    public $created_at;//password//
    public $rule = [
        [
            'field' => 'name',
            'label' => 'name',
            'rules' => 'required'
        ],
    ];
    public function Rules() {return $this->rule; }

    public function getAll() { return
        $this->db->get('data_mahasiswa')->result();
    }

    public function store($request) {
        $this->name = $request->name;
        $this->amount = $request->amount;
        $this->created_at = created_at_hash($request->created_at, CREATED_AT_BCRYPT);
        if($this->db->insert($this->table, $this)) {
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id) {
        $updateData = ['amount' => $request->amount, 'name' => $request->name];
        if($this->db->where('id',$id)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return['msg'=>'Gagal','error'=>true];
    }
    public function destroy($id){
        if (empty($this->db->select('*')->where(array('id' => $id))->get($this->table)->row())) 
        return ['msg'=>'Id tidak ditemukan','error'=>true];

        if($this->db->delete($this->table, array('id' => $id))){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}

?>