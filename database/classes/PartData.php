<?php

class PartData
{
  public $part_id;
  public $ok;
  public $part_name;
  public $short_desc;
  public $description;
  public $part_type;
  public $author;
  public $status;
  public $discontinued;
  public $part_status;
  public $sample_status;
  public $creation_date;
  public $uses;
  public $doc_size;
  public $works;
  public $favorite;
  public $deep_u_list;
  public $deep_count;
  public $scars;
  public $categories;
  public $sequence;
  public $sequence_length;
  public $ac10;
  public $ac1000;

  //new variables, may want to store back in mysql
  public $composition; //basic or composite
  public $sub_parts; //
  public $curate_this;
  public $delete_this;
  public $in_collection;

  public function __construct() {
    if($this->deep_count > 1) {
      $this->composition = "Composite";
      //$this->sub_parts = explode("_", $this->deep_u_list);
    } else {
      $this->composition = "Basic";
    }
  }

}