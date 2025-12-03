<?php
$token = 'ed799cbf-cfee-11f0-84c8-a649637e7c2d';

// 1. ProvinceId Khánh Hòa
$provinceId = 208;

// 2. Lấy District
$ch = curl_init("https://online-gateway.ghn.vn/shiip/public-api/master-data/district?province_id=$provinceId");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Token: $token"]);
$districts = json_decode(curl_exec($ch), true)['data'];

// 3. Lấy Ward cho từng District
foreach ($districts as $district) {
    echo "District: {$district['DistrictName']} ({$district['DistrictID']})\n";

    $districtId = $district['DistrictID'];
    $ch = curl_init("https://online-gateway.ghn.vn/shiip/public-api/master-data/ward?district_id=$districtId");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Token: $token"]);
    $wards = json_decode(curl_exec($ch), true)['data'];

    foreach ($wards as $ward) {
        echo "  <br> Ward: {$ward['WardName']} ({$ward['WardCode']})\n";
    }
}
