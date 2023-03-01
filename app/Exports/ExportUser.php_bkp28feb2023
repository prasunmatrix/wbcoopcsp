<?php

namespace App\Exports;

use App\UserDetails;
use App\User;
use App\District;
use App\Block;
use App\Software;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportUser implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //return UserDetails::all();
        return UserDetails::whereNotNull('range_id')->where('software_using', '!=', 'NULL')->get();
        //  return $user=User::select('users.full_name','user_details.district_id','user_details.block','user_details.socity_type','user_details.unique_id','user_details.pacs_using_software','user_details.software_using')
        //  ->join('user_details', 'users.id', '=', 'user_details.user_id')
        //  ->whereNotNull('user_details.range_id')->where('user_details.software_using', '!=', 'NULL')->get();
         //dd($user);
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
      //   foreach($user as $key=>$row)
      //   {
      //     $full_name=$row['full_name'];
      //     $district_id=$row['district_id'];
      //     $block=$row['block'];
      //     $socity_type=$row['socity_type'];
      //     $unique_id=$row['unique_id'];
      //     $pacs_using_software=$row['pacs_using_software'];
      //     $software_using=$row['software_using'];

      //   return [
      //       $full_name,
      //       $district_id,
      //       $block,
      //       $socity_type,
      //       $unique_id,
      //       $pacs_using_software,
      //       $software_using
      //   ];
      // } 
      return [
        $user->userDetail['full_name'],
        $user->userDistrict['district_name'],
        $user->block,
        $user->userSocietie['name'],
        $user->unique_id,
        $user->pacs_using_software,
        $user->userSoftware['full_name']
           ];
    }
    
}
