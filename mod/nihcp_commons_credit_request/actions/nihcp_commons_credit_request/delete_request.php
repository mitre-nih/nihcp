<?php
$guid = get_input('request_guid');
if($guid) {
    $request = get_entity($guid);
    $pricing_upload_guid = $request->pricing_upload_guid;
    $supplementary_materials_upload_guid = $request->supplementary_materials_upload_guid;
    if($pricing_upload_guid) {
        $pricing_upload_file = get_entity($pricing_upload_guid);
        $pricing_upload_file->delete();
    }
    if($supplementary_materials_upload_guid) {
        $supplementary_materials_upload_file = get_entity($supplementary_materials_upload_guid);
        $supplementary_materials_upload_file->delete();
    }
    return $request->delete();
}
return false;