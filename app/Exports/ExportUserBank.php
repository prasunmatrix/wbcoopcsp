<?php

namespace App\Exports;

use App\UserDetails;
use App\User;
use App\District;
use App\Block;
use App\Software;
use Auth;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportUserBank implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
			$loginUser = Auth::guard('admin')->user();
			return UserDetails::whereBankId($loginUser->id)->whereNotNull('range_id')->get();			
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
        //'Type of Society',
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
        isset($user->userDetail['full_name'])?$user->userDetail['full_name']:'',
        isset($user->userDetail['email'])?$user->userDetail['email']:'',
        isset($user->userDetail['phone_no'])?$user->userDetail['phone_no']:'',
        isset($bank)?$bank:'',
        isset($zone)?$zone:'',
        isset($range)?$range:'',
        isset($user->userDistrict['district_name'])?$user->userDistrict['district_name']:'',
        isset($block)?$block:'',
        //$user->userSocietie['name'],
        isset($user->unique_id)?$user->unique_id:'',
        isset($arr[$user->pacs_using_software])?$arr[$user->pacs_using_software]:'',
        isset($user->userSoftware['full_name'])?$user->userSoftware['full_name']:'',
        isset($whether_the_pacs_received_csp_fund_from_ncdc)?$whether_the_pacs_received_csp_fund_from_ncdc:'',
        isset($whether_the_csp_infrastructure_is_ready)?$whether_the_csp_infrastructure_is_ready:'',
        isset($whether_csp_is_live)?$whether_csp_is_live:''
           ];
    }
}
