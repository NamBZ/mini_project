<?php

/**
 * 
 */
class Madmin extends Model
{
    
    function __construct(){
        parent::__construct();
    }
    
    function insertPost($data){

        $data_insert = array(
            'id_author' => (int)$data['id_author']['value'],
            'id_category' => (int)$data['id_category']['value'],
            'title' => $data['title']['value'],
            'content' => $data['content']['value'],
            'description' => $data['description']['value'],
            'keyword' => $data['keyword']['value'],
            'times' => (int)$data['time']['value'],
            'thumbnail' => $data['thumbnail']['value'],
            'status' => (int)$data['status']['value'],
            'slug' => $data['slug']['value']
        );


        return $this->db->table('posts')->insert($data_insert)->affected_rows >= 1;
    }

    function updatePost($data){
        $data_update = array(
            'id_category' => (int)$data['id_category']['value'],
            'title' => $data['title']['value'],
            'content' => $data['content']['value'],
            'description' => $data['description']['value'],
            'keyword' => $data['keyword']['value'],
            'times' => (int)$data['time']['value'],
            'thumbnail' => $data['thumbnail']['value'],
            'status' => (int)$data['status']['value'],
            'slug' => $data['slug']['value']
        );

        return $this->db->table('posts')->updateRow($data['id']['value'], $data_update)->affected_rows >= 1;
    }

    function deletePost($id){
        $query = $this->db->table('posts')->deleteId($id);
        return $query->affected_rows > 0;
    }

    function checkAlias($alias){
        $query = $this->db->table('posts')->getAll('`slug` = \''.$alias.'\'');
        return $query->fetch_array(MYSQLI_ASSOC);
    }

    function checkPostID($id){
        $query = $this->db->table('posts')->getAll('`id` = '.$id.'');
        return $query->fetch_array(MYSQLI_ASSOC);
    }

    function updateStatus($id, $data){
        $query = $this->db->table('posts')->updateRow($id, $data);
        return $query->affected_rows >= 1;
    }

    function coutPost($type) {
        $query = $this->db->table('posts')->getAll('status = '.$type);
        return $query->num_rows;
    }

    function getAllPost($type, $start, $limit) {
        $sql  = "SELECT `id`, `title`, `slug` FROM `posts` WHERE `status` = $type ORDER BY `id` DESC LIMIT $start, $limit";
        $query    = $this->db->query($sql);
        
        $result = $query->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    function getListCategory(){
        $query = $this->db->table('categories')->getAll();
        $result = $query->fetch_all(MYSQLI_ASSOC);
        return $result;
    }
}