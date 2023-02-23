<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\UserDetails;

class UsersCustomExport implements FromCollection, WithHeadings, WithMapping
{
  protected $user;

  public function __construct($user)
  {
    $this->user = $user;
    //dd($this->user);
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    //
    return collect($this->user);
    //dd($this->user);
  }
  public function headings(): array
  {
    return [
      'Name of PACS',
      'District',
      'Block',
      'Type of Society',
      'Unique Id of PACS',
      'Confirmation Done By PACS',
      'Service Provider of PACS'
    ];
  }
  public function map($user): array
  {
    //dd($user);
    foreach($user as $key=>$row)
    {
      $full_name=$row['full_name'];
          $district_id=$row['district_id'];
          $block=$row['block'];
          $socity_type=$row['socity_type'];
          $unique_id=$row['unique_id'];
          $pacs_using_software=$row['pacs_using_software'];
          $software_using=$row['software_using'];  
    return [
      $full_name,
            $district_id,
            $block,
            $socity_type,
            $unique_id,
            $pacs_using_software,
            $software_using

    ];
    }
  }
}
