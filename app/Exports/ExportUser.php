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
        'Email',
        'Phone No',
        'Bank',
        'Zone',
        'Range',
        'District',
        'Block',
        'Type of Society',
        'Unique Id of PACS',
        'Confirmation Done By PACS',
        'Service Provider of PACS',
        'Whether the PACS  received CSP Fund  from NCDC',
        'Whether the CSP infrastructure is ready',
        'Whether CSP is live'
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
      if($user->userBlock!=NULL || $user->userBlock!='')
      {
        $block=$user->userBlock['block_name'];
      }
      else
      {
        $block=$user->block;
      }
      $arr = ['0' => 'No', '1'=>'Yes' ];
      if($user->whether_the_pacs_received_csp_fund_from_ncdc!=NULL || $user->whether_the_pacs_received_csp_fund_from_ncdc!='')
      {
        $whether_the_pacs_received_csp_fund_from_ncdc=$arr[$user->whether_the_pacs_received_csp_fund_from_ncdc];
      }
      else
      {
        $whether_the_pacs_received_csp_fund_from_ncdc='No';
      }
      if($user->whether_the_csp_infrastructure_is_ready!=NULL || $user->whether_the_csp_infrastructure_is_ready!='')
      {
        $whether_the_csp_infrastructure_is_ready=$arr[$user->whether_the_csp_infrastructure_is_ready];
      }
      else
      {
        $whether_the_csp_infrastructure_is_ready='No';
      }
      if($user->whether_csp_is_live!=NULL || $user->whether_csp_is_live!='')
      {
        $whether_csp_is_live=$arr[$user->whether_csp_is_live];
      }
      else
      {
        $whether_csp_is_live='No';
      }
      if($user->bank_id != NULL || $user->bank_id !='')
      {
        $bank=$user->userBank->full_name;
      }
      else
      {
        $bank="";
      }
      if($user->zone_id != NULL || $user->zone_id !='')
      {
        $zone=$user->userZone->full_name;
      }
      else
      {
        $zone="";
      }
      if($user->range_id != NULL || $user->range_id !='')
      {
        $range=$user->userRangeOne->full_name;
      }
      else
      {
        $range="";
      }   
      return [
        $user->userDetail['full_name'],
        $user->userDetail['email'],
        $user->userDetail['phone_no'],
        $bank,
        $zone,
        $range,
        $user->userDistrict['district_name'],
        $block,
        $user->userSocietie['name'],
        $user->unique_id,
        $arr[$user->pacs_using_software],
        $user->userSoftware['full_name'],
        $whether_the_pacs_received_csp_fund_from_ncdc,
        $whether_the_csp_infrastructure_is_ready,
        $whether_csp_is_live
           ];
    }
    
}
